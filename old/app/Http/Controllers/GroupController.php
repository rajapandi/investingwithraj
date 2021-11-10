<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use view;
use DB;
use Session;
use App\Http\Requests;
use App\Models\Groups;
use App\Models\GroupDetail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon;
use File;
use AngelBroking\SmartApi;

class GroupController extends Controller
{
    
    public function store(Request $request){
        if($request->accountCheckbox=='' || $request->accountCheckbox==null){
            return back()->with('accountmsg', 'Select Account');
        }
        $cd = new Groups;
        $cd->name  = $request->group_name;
        $cd->description  = $request->description;
        $cd->is_active="active";
        if($cd->save()){
            foreach($request->accountCheckbox as $key=> $account_id){
            
                $gd = new GroupDetail;
                $gd->group_id = $cd->id;
                $gd->account_id = $account_id;
                $gd->save();
                
            }
        }
        
        return Redirect::back()->with('msg', 'Group Created successfully');
        
    }
    
    public function update(Request $request){
        
        $cd = Groups::where('id', $request->group_id)->first();
        $cd->name  = $request->group_name;
        $cd->description  = $request->description;
        $cd->is_active="active";
        if($cd->save()){
            foreach($request->accountCheckbox as $key=> $account_id){
            
                $gd = GroupDetail::where('group_id', $request->group_id)->where('account_id', $account_id)->first();
                $gd->group_id = $cd->id;
                $gd->account_id = $account_id;
                $gd->save();
                
            }
        }
        
        return Redirect::back()->with('msg', 'Group updated successfully');
        
    }

    public function delete($id){
        
        if(Groups::where('id', $id)->first()){
            GroupDetail::where('group_id', $id)->delete();
        }
        Groups::where('id', $id)->delete();
        
        return Redirect::back()
        ->with('msg', 'Group delete successfull');
    }

    
}