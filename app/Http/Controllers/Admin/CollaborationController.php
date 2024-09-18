<?php

namespace App\Http\Controllers\Admin;

use Datatables;
use App\Models\Collaboration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollaborationController extends AdminBaseController
{
    //*** JSON Request
    public function datatables()
    {
         $datas = Collaboration::orderBy('id','desc')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->editColumn('details', function(Collaboration $data) {
                                $details = mb_strlen(strip_tags($data->details),'utf-8') > 250 ? mb_substr(strip_tags($data->details),0,250,'utf-8').'...' : strip_tags($data->details);
                                return  $details;
                            })
                            ->addColumn('action', function(Collaboration $data) {
                                return '<div class="action-list"><a href="' . route('admin-collaboration-edit',$data->id) . '"> <i class="fas fa-edit"></i>'.__("Edit").'</a></div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index(){
        return view('admin.collaboration.index');
    }

    public function create(){
        return view('admin.collaboration.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section
        $data = new Collaboration();
        $input = $request->all();
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = __('New Data Added Successfully.').'<a href="'.route("admin-collaboration-index").'">'.__("View collaboration Lists").'</a>';
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Collaboration::findOrFail($id);
        return view('admin.collaboration.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section

        //--- Validation Section Ends

        //--- Logic Section
        $data = Collaboration::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = __('Data Updated Successfully.').'<a href="'.route("admin-collaboration-index").'">'.__("View collaboration Lists").'</a>';
        return response()->json($msg);    
        //--- Redirect Section Ends              
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Collaboration::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }
}