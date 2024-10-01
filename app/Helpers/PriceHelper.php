<?php

namespace App\Helpers;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Package;
use App\Models\Shipping;
use App\Models\State;
use Session;
use DB;

class PriceHelper
{

    public static function showPrice($price)
    {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        if (is_numeric($price) && floor($price) != $price) {
            return number_format($price, 2, $gs->decimal_separator, $gs->thousand_separator);
        } else {
            return number_format($price, 0, $gs->decimal_separator, $gs->thousand_separator);
        }
    }

    public static function apishowPrice($price)
    {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        if (is_numeric($price) && floor($price) != $price) {
            return round($price, 2);
        } else {
            return round($price, 0);
        }
    }


    public static function showCurrencyPrice($price)
    {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $new_price = 0;
        if (is_numeric($price) && floor($price) != $price) {
            $new_price = number_format($price, 2, $gs->decimal_separator, $gs->thousand_separator);
        } else {
            $new_price = number_format($price, 0, $gs->decimal_separator, $gs->thousand_separator);
        }
        if (Session::has('currency')) {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        } else {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default', '=', 1)->first();
            });
        }

        if ($gs->currency_format == 0) {
            return $curr->sign . $new_price;
        } else {
            return $new_price . $curr->sign;
        }
    }


    public static function showAdminCurrencyPrice($price)
    {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $new_price = 0;
        if (is_numeric($price) && floor($price) != $price) {
            $new_price = number_format($price, 2, $gs->decimal_separator, $gs->thousand_separator);
        } else {
            $new_price = number_format($price, 0, $gs->decimal_separator, $gs->thousand_separator);
        }

        $curr = Currency::where('is_default', '=', 1)->first();


        if ($gs->currency_format == 0) {
            return $curr->sign . $new_price;
        } else {
            return $new_price . $curr->sign;
        }
    }


    public static function showOrderCurrencyPrice($price, $currency)
    {
        $gs = cache()->remember('generalsettings', now()->addDay(), function () {
            return DB::table('generalsettings')->first();
        });
        $new_price = 0;
        if (is_numeric($price) && floor($price) != $price) {
            $new_price = number_format($price, 2, $gs->decimal_separator, $gs->thousand_separator);
        } else {
            $new_price = number_format($price, 0, $gs->decimal_separator, $gs->thousand_separator);
        }

        if ($gs->currency_format == 0) {
            return $currency . $new_price;
        } else {
            return $new_price . $currency;
        }
    }


    public static function ImageCreateName($image)
    {
        $name = time() . preg_replace('/[^A-Za-z0-9\-]/', '', $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
        return $name;
    }


    public static function getOrderTotal($input, $cart)
    {
   

        // dd($input['shipping_cost']);
        try {
            $vendor_ids = [];
            foreach ($cart->items as $item) {
                if (!in_array($item['item']['user_id'], $vendor_ids)) {
                    $vendor_ids[] = $item['item']['user_id'];
                }
            }

            $gs = cache()->remember('generalsettings', now()->addDay(), function () {
                return DB::table('generalsettings')->first();
            });
         
            $totalAmount = $cart->totalPrice;
            $tax_amount = 0;
            // if ($input['tax'] && @$input['tax_type']) {
            //     if (@$input['tax_type'] == 'state_tax') {
            //         $tax = State::findOrFail($input['tax'])->tax;
            //     } else {
            //         $tax = Country::findOrFail($input['tax'])->tax;
            //     }
            //     $tax_amount = ($totalAmount / 100) * $tax;
            //     $totalAmount = $totalAmount + $tax_amount;
            // }
            if ($gs->multiple_shipping == 0) {
                
                $vendor_shipping_ids = [];
                $vendor_packing_ids = [];
                foreach ($vendor_ids as $vendor_id) {
                    $vendor_shipping_ids[$vendor_id] = $input['shipping_id'] != 0 ? $input['shipping_id'] : null;
                    $vendor_packing_ids[$vendor_id] = $input['packaging_id'] != 0 ? $input['packaging_id'] : null;
                    $vendor_packing_ids[$vendor_id] = array_key_exists('refferal_discount', $input) && $input['refferal_discount'] != 0 ? $input['refferal_discount'] : null;
                }

                $shipping = $input['shipping_id'] != 0 ? Shipping::findOrFail($input['shipping_id']) : null;
                $packeing = $input['packaging_id'] != 0 ? Package::findOrFail($input['packaging_id']) : null;
                $referral_discount = array_key_exists('refferal_discount', $input) && $input['refferal_discount'] != 0 ? $input['refferal_discount'] : null;

                // $totalAmount = $totalAmount + @$shipping->price + @$packeing->price;
                $totalAmount = $totalAmount - $referral_discount +  $input['shipping_cost'] + @$packeing->price - $input['coupon_discount'];
                // dd($totalAmount);
                return [
                    'total_amount' => $totalAmount,
                    'shipping' => $shipping,
                    'packeing' => $packeing,
                    'referral_discount' => $referral_discount,
                    'is_shipping' => 0,
                    'vendor_shipping_ids' => @json_encode($vendor_shipping_ids),
                    'vendor_packing_ids' => @json_encode($vendor_packing_ids),
                    'vendor_ids' => @json_encode($vendor_ids),
                    'success' => true
                ];

               
            } else {

                // dd('j');
                if (gettype($input['shipping']) == 'string') {
                    $shippingData = json_decode($input['shipping'], true);
                } else {
                    $shippingData = $input['shipping'];
                }
              
                $shipping_cost = 0;
                $packaging_cost = 0;
                $vendor_ids = [];
                if ($input['shipping'] && $input['shipping'] != 0) {
                    foreach ($shippingData as $key => $shipping_id) {
                        $shipping = Shipping::findOrFail($shipping_id);
                        $shipping_cost += $shipping->price;

                       
                        if (!in_array($shipping->user_id, $vendor_ids)) {
                            $vendor_ids[] = $shipping->user_id;
                        }
                    }
                }


                if (gettype($input['packeging']) == 'string') {
                    $packegingData = json_decode($input['packeging'], true);
                    } else {
                        $packegingData = $input['packeging'];
                    }

                if ($input['packeging'] && $input['packeging'] != 0) {
                    foreach ($packegingData as $key => $packaging_id) {
                        $packeing = Package::findOrFail($packaging_id);
                        $packaging_cost += $packeing->price;
                        if (!in_array($packeing->user_id, $vendor_ids)) {
                            $vendor_ids[] = $packeing->user_id;
                        }
                    }
                }


                $referral_discount = $input['refferal_discount'] != 0 ? $input['refferal_discount'] : null;
                // $totalAmount = $totalAmount + $shipping_cost + $packaging_cost;
                $totalAmount = $totalAmount - $referral_discount + $input['shipping_cost'] + $packaging_cost - $input['coupon_discount'];


                return [
                    'total_amount' => $totalAmount,
                    'shipping' => isset($shipping) ? $shipping : null,
                    'packeing' => isset($packeing) ? $packeing : null,
                    'referral_discount' => $referral_discount,
                    'is_shipping' => 1,
                    'tax' => $tax_amount,
                    'vendor_shipping_ids' => @json_encode($input['shipping']),
                    'vendor_packing_ids' => @json_encode($input['packeging']),
                    'vendor_ids' => @json_encode($vendor_ids),
                    // 'shipping_cost' => $shipping_cost,
                    'shipping_cost' => $input['shipping_cost'],

                    'packing_cost' => $packaging_cost,
                    'success' => true
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public static function getOrderTotalAmount($input, $cart)
    {

        if (Session::has('currency')) {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        } else {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default', '=', 1)->first();
            });
        }

        try {
            $vendor_ids = [];
            foreach ($cart->items as $item) {
                if (!in_array($item['item']['user_id'], $vendor_ids)) {
                    $vendor_ids[] = $item['item']['user_id'];
                }
            }

            $gs = cache()->remember('generalsettings', now()->addDay(), function () {
                return DB::table('generalsettings')->first();
            });
            $totalAmount = $cart->totalPrice;
            // if ($input['tax'] && @$input['tax_type']) {
            //     if (@$input['tax_type'] == 'state_tax') {
            //         $tax = State::findOrFail($input['tax'])->tax;
            //     } else {
            //         $tax = Country::findOrFail($input['tax'])->tax;
            //     }
            //     $tax_amount = ($totalAmount / 100) * $tax;
            //     $totalAmount = $totalAmount + $tax_amount;
            // }

            if ($gs->multiple_shipping == 0) {
                $vendor_shipping_ids = [];
                $vendor_packing_ids = [];
                foreach ($vendor_ids as $vendor_id) {
                    $vendor_shipping_ids[$vendor_id] = $input['shipping_id'];
                    $vendor_packing_ids[$vendor_id] = $input['packaging_id'];
                }


                $shipping = Shipping::findOrFail($input['shipping_id']);
                $packeing = Package::findOrFail($input['packaging_id']);
                // $totalAmount = $totalAmount + $shipping->price + $packeing->price;
                
                 $totalAmount = $totalAmount + $input['shipping_cost'] + $packeing->price - $input['coupon_discount'];

                return round($totalAmount / $curr->value, 2);
            } else {

                $shipping_cost = 0;
                $packaging_cost = 0;
                $vendor_ids = [];
                if ($input['shipping']) {
                    foreach ($input['shipping'] as $key => $shipping_id) {
                        $shipping = Shipping::findOrFail($shipping_id);
                        $shipping_cost += $shipping->price;
                        if (!in_array($shipping->user_id, $vendor_ids)) {
                            $vendor_ids[] = $shipping->user_id;
                        }
                    }
                }
                if ($input['packeging']) {
                    foreach ($input['packeging'] as $key => $packaging_id) {
                        $packeing = Package::findOrFail($packaging_id);
                        $packaging_cost += $packeing->price;
                        if (!in_array($packeing->user_id, $vendor_ids)) {
                            $vendor_ids[] = $packeing->user_id;
                        }
                    }
                }

                // $totalAmount = $totalAmount + $shipping_cost + $packaging_cost;
              
                $totalAmount = $totalAmount + $input['shipping_cost'] + $packaging_cost - $input['coupon_discount'];


                return round($totalAmount * $curr->value, 2);
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
    