<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\qlhs_message;
use Carbon\Carbon;
class PagesController extends Controller
{
    public function loadListMessage($limit){
      if(Auth::check()){
            $result = [];
            $mess = qlhs_message::leftJoin('qlhs_schools','schools_id','school_id')
            ->where('cap_nhan',Auth::user()->level)
            ->where('status',0);
        if(Auth::user()->truong_id != '0'){
            if(count(explode('-',Auth::user()->truong_id)) > 0){
                $mess = $mess->whereIn('school_id',explode('-',Auth::user()->truong_id));
            }
        }
            $result['data'] = $mess->select('type','message_text','schools_name','qlhs_message.updated_at','qlhs_message.report_name','school_id')
            ->orderBy('qlhs_message.updated_at','desc')
            ->take($limit)->get();
            $result['total'] = $mess->count();
            return $result;
      }else{
        //return redirect('auth/logout');
      }
        
        
    }
	public function Dashboard(){
        return view('home',['category' => 'Phần mềm quản lý hồ sơ']);
    }
	
    public function loadMessage(Request $rq){
        if(Auth::check()){
            $result = [];
            $start_time = Carbon::parse($rq->start_time)->format('Y-m-d');
            $end_time = Carbon::parse($rq->end_time)->format('Y-m-d');
            $mess = qlhs_message::leftJoin('qlhs_schools','schools_id','school_id')->whereDate('qlhs_message.updated_at','>=', $start_time)->whereDate('qlhs_message.updated_at','<=', $end_time);
            if($rq->status == 1){
                $mess = $mess->where('qlhs_message.status',0);
            }else if($rq->status == 2){
                $mess = $mess->where('qlhs_message.status',1);
            }
            if($rq->id_school != '' && $rq->id_school != null && Auth::user()->truong_id != '0'){
                $mess = $mess->whereIn('school_id',explode('-',Auth::user()->truong_id))->where('school_id',$rq->id_school);
            }
            else if(Auth::user()->truong_id != '0' && ($rq->id_school == '' || $rq->id_school == null)){
              if(count(explode('-',Auth::user()->truong_id)) > 0){
                  $mess = $mess->whereIn('school_id',explode('-',Auth::user()->truong_id));
              }
            }
            $result['data'] = $mess->select('status','type','message_text','schools_name','qlhs_message.updated_at','report_name','school_id')->orderBy('qlhs_message.updated_at','desc')->skip($rq->start*$rq->limit)->take($rq->limit)->get();
            $result['startRecord'] = ($rq->start);
            $result['numRows'] = $rq->limit;
            $result['totalRows'] = $mess->count();
            return $result;
        }else{
         // return redirect('auth/logout');
        }
    }
    public function listingMessage(){
      return view('admin.hoso.hosohocsinh.listingmessage');
    }
    public function getPage($url)
    {
      $module = DB::select('select qlhs_modules.* from qlhs_modules LEFT JOIN (SELECT module_id,role_user_id from permission_users GROUP BY module_id,role_user_id ) as permission_user
 on qlhs_modules.module_id = permission_user.module_id and role_user_id = :id where module_view = :view and module_path = :path order by module_order,module_id', ['id' => Auth::user()->id,'view' => 1,'path' => $url]);
      if($url=='welcome' || $url==''){
            return view('welcome',['category' => 'Phần mềm quản lý hồ sơ']);
      }
      if(count($module)>0 ){
         if($url=='bao-cao'){
            return view('admin.baocao.listing',['category' => 'Hệ thống']);
        }else if($url=='ho-so-chinh-sach'){
            return view('admin.hoso.listing',['category' => 'Hồ sơ chinh sách']);
        }else if($url=='kinh-phi-ho-tro'){
            return view('admin.kinhphi.listing',['category' => 'Quản lý kinh phí hỗ trợ']);
        }else if($url=='danh-muc'){
            return view('admin.danhmuc.listing',['category' => 'Quản lý danh mục']);
        }else if($url=='he-thong'){
            return view('admin.hethong.listing',['category' => 'Hệ thống']);
        }
      }else{
          return view('errors.permission');
      }

    }

    public function getDashboard()
    {
      if(Auth::check()){
          return view('welcome',['category' => 'Phần mềm quản lý hồ sơ']);
      }else{
        return redirect('/login');
      }
       
		 	// return view('admin.pages.dashboard', ['category' => 'welcome']);
		
        //return view('admin.pages.dashboard');
    }
	public function addGet(){
      //  if($request->isMethod('get')){
        //    $category = DB::table('qlhs_modules')->select('module_id','module_name','module_path','module_parentid','module_icon')->get();
    //return view('admin.pages.blank');
            return view('admin.danhmuc.addCategory');
       // }
    }
    public function phanquyennguoidung(){
           return view('layouts.intro');
    }
    public function listGet(){
       $results = DB::table('qlhs_department')->get();

    //return response()->json($results);
    //die (json_encode($results));
    return view('admin.danhmuc.listCategory')->with('departments', $results);
    }
       
    public function listGets(){
       $list = DB::table('roles')->paginate(5);
        return view('admin.danhmuc.listCategory',['list'=>$list]);
      //      return view('admin.danhmuc.listCategory');
       // }
    }
	public function getBlank($category)
    {
    	return view('admin.pages.dashboard', ['category' => $category]);
        //return view('admin.pages.blank');
    }
    public function myFunction()
  	{
     return view('admin.pages.dashboard')->with('category', 'ssss');

  	}
}
