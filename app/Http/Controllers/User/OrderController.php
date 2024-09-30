<?php

namespace App\Http\Controllers\User;

use App\{
    Models\Order,
    Models\User,
    Models\Product,
    Classes\GeniusMailer,
};
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Log;

class OrderController extends UserBaseController
{

    public function orders()
    {
        $user = $this->user;
        $orders = Order::where('user_id','=',$user->id)->latest('id')->get();
        return view('user.order.index',compact('user','orders'));
    }

    public function ordertrack()
    {
        $user = $this->user;
        return view('user.order-track',compact('user'));
    }

    public function trackload($id)
    {
        $user = $this->user;
        $order = $user->orders()->where('order_number','=',$id)->first();
        $datas = array('Pending','Processing','On Delivery','Completed');
        return view('load.track-load',compact('order','datas'));

    }


    public function order($id)
    {
        $user = $this->user;
        $order = $user->orders()->whereId($id)->firstOrFail();
        $cart = json_decode($order->cart, true);;
        return view('user.order.details',compact('user','order','cart'));
    }

    public function orderdownload($slug,$id)
    {
        $user = $this->user;
        $order = Order::where('order_number','=',$slug)->first();
        $prod = Product::findOrFail($id);
        if(!isset($order) || $prod->type == 'Physical' || $order->user_id != $user->id)
        {
            return redirect()->back();
        }
        return response()->download(public_path('assets/files/'.$prod->file));
    }

    public function orderprint($id)
    {
        $user = $this->user;
        $order = Order::findOrfail($id);
        $cart = json_decode($order->cart, true);
        return view('user.order.print',compact('user','order','cart'));
    }

    public function trans()
    {
        $id = $_GET['id'];
        $trans = $_GET['tin'];
        $order = Order::findOrFail($id);
        $order->txnid = $trans;
        $order->update();
        $data = $order->txnid;
        return response()->json($data);            
    }  

    public function cancel_order(Request $request,$id)
    {
        $this->cancelWaybill($id);
        $data = Order::findOrFail($id);
        if ($data->user_id != 0) {
            if ($data->wallet_price != 0) {
                $user = User::find($data->user_id);
                if ($user) {
                    $user->balance = $user->balance + $data->wallet_price;
                    $user->save();
                }
            }
        }
        $cart = json_decode($data->cart, true);
        foreach ($cart['items'] ?? []as $prod) {
            $x = (string)$prod['stock'];
            if ($x != null) {
                $product = Product::findOrFail($prod['item']['id']);
                $product->stock = $product->stock + $prod['qty'];
                $product->update();
            }
        }
        // Restore Product Size Qty If Any
        foreach ($cart['items'] ?? [] as $prod) {
            $x = (string)$prod['size_qty'];
            if (!empty($x)) {
                $product = Product::findOrFail($prod['item']['id']);
                $x = (int)$x;
                $temp = $product->size_qty;
                $temp[$prod['size_key']] = $x;
                $temp1 = implode(',', $temp);
                $product->size_qty =  $temp1;
                $product->update();
            }
        }
        $maildata = [
            'to' => $data->customer_email,
            'subject' => 'Your order ' . $data->order_number . ' is Declined!',
            'body' => "Hello " . $data->customer_name . "," . "\n We are sorry for the inconvenience caused. We are looking forward to your next visit.",
        ];
        $mailer = new GeniusMailer();
        $mailer->sendCustomMail($maildata);
        $status = "declined";
        $data->update(['status' => $status]);
        return redirect()->back()->with('message', 'Order Canceled Successfully');
    }


    public function cancelWaybill($id)
    {
        $order = Order::where('id',$id)->first();
        $client = new Client();
        $url = 'https://track.delhivery.com/api/p/edit';
        $headers = [
            'Authorization' => 'Token 4fe90509d391df11535a3533bc932022b11f9fd4',  // Replace with your actual token
            'Content-Type' => 'application/json',
        ];
        $body = [
            'waybill' => $order->third_party_delivery_tracking_id,
            'cancellation' => 'true',
        ];
        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);
            $statusCode = $response->getStatusCode();
           
            $content = $response->getBody()->getContents();
            Log::info($content);
            // Handle the response as needed
            return response()->json([
                'status_code' => $statusCode,
                'content' => json_decode($content),
            ]);
        } catch (\Exception $e) {
            // Handle any errors that occur during the request
            Log::info($e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
