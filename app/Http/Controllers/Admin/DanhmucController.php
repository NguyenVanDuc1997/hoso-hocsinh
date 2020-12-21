<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\DanhMucNhomDoiTuong;
use App\Models\DanhMucKhoi;
use App\Models\DanhMucDoiTuong;
use App\Models\DanhMucDantoc;
use App\Models\DanhMucLichSuDoiTuong;
use App\Models\DanhMucLop;
use App\Models\DanhMucPhongBan;
use App\Models\DanhMucPLXa;
use App\Models\DanhMucTruong;
use App\Models\DanhMucXaPhuong;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use Excel;
use Illuminate\Support\Str;
use App\Models\LichSuHSHocSinh;
use App\Models\qlhs_nguoinauan;

class DanhmucController extends Controller
{
//Get permission
    public function getPermission(Request $rq){
      $json = [];
      $val = [];
     // return ;
      $data = DB::select('select pu.module_id,pu.permission_id from permission_users pu where pu.role_user_id = '.Auth::user()->id.' and pu.module_id = ' .$rq->module );
      foreach ($data as $key => $value) {
        $val[] = $value->permission_id.'';
      }
      $json['permission'] = $val;
      return $json;
    }

//------------------------------------------------------------------Khối lớp------------------------------------------------------------

    public function viewKhoilop(){
      // $results = DB::table('qlhs_school_type')->orderBy('create_date', 'desc')->get();

      return view('admin.danhmuc.khoilop.listing');//->with('list', $results);
    }

    public function loadKhoilop(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $keysearch = $req->input('key');
        $load_level = null;

        if ($keysearch != null && $keysearch != "") {
          $load_level = DB::table('qlhs_level')
            // ->join('qlhs_schools', 'schools_id', '=', 'level_school_id')
            ->join('qlhs_unit', 'unit_id', '=', 'level_unit_id')
            // ->where("schools_name", "LIKE","%".$keysearch."%")
            ->orWhere("unit_name", "LIKE", "%".$keysearch."%")
            ->orWhere("level_name", "LIKE", "%".$keysearch."%");
        }
        else {
          $load_level = DB::table('qlhs_level')
            // ->join('qlhs_schools', 'schools_id', '=', 'level_school_id')
            ->join('qlhs_unit', 'unit_id', '=', 'level_unit_id');
        }
        
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['totalRows'] = $load_level->count();
        $json['data'] = $load_level->orderBy('unit_name', 'asc')->orderBy('level_level', 'asc')->skip($start*$limit)->take($limit)->get();
        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function getbyKhoilopid(Request $request)
    {
      try {
        $levelid = $request->input("KHOILOPID");
        $objType = null;
        if ($levelid > 0) {
          $objType = DB::table('qlhs_level')->where('level_id', '=', $levelid)->get();
        }
        return $objType;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function insertKhoilop(Request $request){
      $results = [];
      try{
        $name = $request->input("KHOILOPNAME");
        $name = trim($name);
        $level = $request->input("LEVEL");
        // $school = $request->input("SCHOOLID");
        $unit = $request->input("UNITID");
        
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getbyLevel = DB::table('qlhs_level')
          ->where('level_level', '=', $level)
          ->where('level_unit_id', '=', $unit)->get();

        if (count($getbyLevel) > 0) {
            $results['error'] = 'Khối đã tồn tại khối lớp!';
        }
        else{
          DB::table('qlhs_level')
            ->insert(['level_name' => $name, 'level_level' => $level, 'level_unit_id' => $unit, 'create_date' => $currentdate]);

          $results['success'] = "Thêm mới thành công";
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }
    
    public function updateKhoilop(Request $request){
      $results = [];
      try{
        $id = $request->input("KHOILOPID");
        $name = $request->input("KHOILOPNAME");
        $name = trim($name);
        $level = $request->input("LEVEL");
        // $school = $request->input("SCHOOLID");
        $unit = $request->input("UNITID");
        
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        // $getbyLevel = DB::table('qlhs_level')
        //   ->where('level_level', '=', $level)
        //   ->where('level_school_id', '=', $school)
        //   ->where('level_unit_id', '=', $unit)->get();

        // if (count($getbyLevel) > 0) {
        //     $results['error'] = 'Trường đã tồn tại khối lớp!';
        // }
        // else{
          DB::table('qlhs_level')->where('level_id', '=', $id)
            ->update(['level_name' => $name, 'level_level' => $level, 'level_unit_id' => $unit, 'create_date' => $currentdate]);

          $results['success'] = "Sửa thành công";
        // }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }
    
    public function deleteKhoilop(Request $request){
      $results = [];
      try{
        $levelid = $request->input("KHOILOPID");
        $delete = DB::table('qlhs_level')->where('level_id', '=', $levelid)->delete();

        if ($delete > 0) {
          $results['success'] = "Xóa thành công";
        }
        else {
          $results['error'] = "Xóa thất bại";
        }
        
        return $results;
      }catch(\Exception $e){
        $results['error'] = $e . "Exception";
        return $results;
      }
    }

//------------------------------------------------------------------Loại trường------------------------------------------------------------

    public function viewLoaitruong(){
      // $results = DB::table('qlhs_school_type')->orderBy('create_date', 'desc')->get();

      return view('admin.danhmuc.loaitruong.listing');//->with('list', $results);
    }

    public function loadLoaiTruong(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $keysearch = $req->input('key');
        $load_schooltype = null;

        if ($keysearch != null && $keysearch != "") {
          $load_schooltype = DB::table('qlhs_school_type')->where("school_type_name", "LIKE","%".$keysearch."%")->orWhere("create_date", "LIKE", "%".$keysearch."%");
        }
        else {
          $load_schooltype = DB::table('qlhs_school_type');
        }
        
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['totalRows'] = $load_schooltype->count();
        $json['data'] = $load_schooltype->orderBy('create_date', 'desc')->skip($start*$limit)->take($limit)->get();
        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function getbytypeid(Request $request)
    {
      try {
        $typeid = $request->input("SCHOOLTYPEID");
        $objType = null;
        if ($typeid > 0) {
          $objType = DB::table('qlhs_school_type')->where('school_type_id', '=', $typeid)->get();
        }
        return $objType;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function insertLoaitruong(Request $request){
      $results = [];
      try{
        $nametype = $request->input("SCHOOLTYPENAME");
        $nametype = trim($nametype);
        $typeactive = $request->input("SCHOOLTYPEACTIVE");
        
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getbyNametype = DB::table('qlhs_school_type')->where('school_type_name', '=', $nametype)->get();

        if (count($getbyNametype) > 0) {
            $results['error'] = 'Tên đã tồn tại, vui lòng nhập tên khác!';
        }
        else{
          DB::table('qlhs_school_type')->insert(['school_type_name' => $nametype, 'active' => $typeactive, 'create_user_id' => $currentuser_id, 'create_date' => $currentdate]);

          $results['success'] = "Thêm mới thành công";
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }
    
    public function updateLoaitruong(Request $request){
      $results = [];
      try{
        $typeid = $request->input("SCHOOLTYPEID");
        $nametype = $request->input("SCHOOLTYPENAME");
        $nametype = trim($nametype);
        $typeactive = $request->input("SCHOOLTYPEACTIVE");
        
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getbyNametype = DB::table('qlhs_school_type')->where('school_type_name', '=', $nametype)->get();

        if (count($getbyNametype) > 0) {
            $results['error'] = 'Tên đã tồn tại, vui lòng nhập tên khác!';
        }
        else{
          DB::table('qlhs_school_type')
            ->where('school_type_id', '=', $typeid)
            ->update(['school_type_name' => $nametype, 'active' => $typeactive, 'create_user_id' => $currentuser_id, 'create_date' => $currentdate]);

          $results['success'] = "Sửa thành công";
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }
    
    public function deleteLoaitruong(Request $request){
      $results = [];
      try{
        $typeid = $request->input("SCHOOLTYPEID");
        $delete = DB::table('qlhs_school_type')->where('school_type_id', '=', $typeid)->delete();

        if ($delete > 0) {
          $results['success'] = "Xóa thành công";
        }
        else {
          $results['error'] = "Xóa thất bại";
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e . "Exception";
        return $results;
      }
    }

//-----------------------------------------------------Danh sách hộ nghèo----------------------------------------------------------------------------
    public function listingDShongheo(){
      return view('admin.danhmuc.danhsachhongheo.listing');
    }

    public function loadDanhsachhongheo(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $keysearch = $req->input('key');
        $load_data = null;

        if ($keysearch != null && $keysearch != "") {
          $load_data = DB::table('qlhs_danhsachhongheo')
          ->join('qlhs_nationals', 'nationals_id', '=', 'DShongheo_nation_id')
          ->leftJoin('qlhs_site as thon', 'thon.site_id', '=', 'DShongheo_site_idthon')
          ->leftJoin('qlhs_site as xa', 'xa.site_id', '=', 'DShongheo_site_idxa')
          ->select('qlhs_danhsachhongheo.*', 'nationals_name', 'thon.site_id as thon_id', 'thon.site_name as tenthon', 'xa.site_id as xa_id', 'xa.site_name as tenxa')
          ->where("DShongheo_name", "LIKE","%".$keysearch."%")
          ->orWhere("DShongheo_birthday", "LIKE", "%".$keysearch."%")
          ->orWhere("DShongheo_sex", "LIKE", "%".$keysearch."%")
          ->orWhere("DShongheo_relationship", "LIKE", "%".$keysearch."%")
          ->orWhere("DShongheo_typename", "LIKE", "%".$keysearch."%")
          ->orWhere("thon.site_name", "LIKE", "%".$keysearch."%")
          ->orWhere("xa.site_name", "LIKE", "%".$keysearch."%");
        }
        else {
          $load_data = DB::table('qlhs_danhsachhongheo')
          ->join('qlhs_nationals', 'nationals_id', '=', 'DShongheo_nation_id')
          ->leftJoin('qlhs_site as thon', 'thon.site_id', '=', 'DShongheo_site_idthon')
          ->leftJoin('qlhs_site as xa', 'xa.site_id', '=', 'DShongheo_site_idxa')
          ->select('qlhs_danhsachhongheo.*', 'nationals_name', 'thon.site_id as thon_id', 'thon.site_name as tenthon', 'xa.site_id as xa_id', 'xa.site_name as tenxa');
        }
        
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['totalRows'] = $load_data->count();
        $json['data'] = $load_data->orderBy('DShongheo_createdate', 'desc')->skip($start*$limit)->take($limit)->get();

        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function loadDataSite(){
      try {
        $results = [];

        $results['SITE1'] = DB::table('qlhs_site')->where('site_level', '=', 2)->where('site_active', '=', 1)
          ->select('site_id', 'site_parent_id', 'site_name')->get();

        $results['SITE2'] = DB::table('qlhs_site')->where('site_level', '=', 3)->where('site_active', '=', 1)
          ->select('site_id', 'site_parent_id', 'site_name')->get();

        return $results;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function loadDataSiteByID($id){
      try {

        $results = DB::table('qlhs_site')->where('site_parent_id', '=', $id)->where('site_active', '=', 1)
          ->select('site_id', 'site_parent_id', 'site_name')->get();

        return $results;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function insertDSHN(Request $request){
      $results = [];
      try{
        $name = $request->input("NAME");
        $birthday = $request->input("BIRTHDAY");
        $sex = $request->input("SEX");
        $nation = $request->input("NATION");
        $relation = $request->input("RELATIONSHIP");
        $site1 = $request->input("SITE1");
        $site2 = $request->input("SITE2");
        $type = $request->input("TYPE");
        $index = $request->input("INDEX");

        $typename = "";

        if ($type == 1) {
          $typename = "Hộ nghèo thu nhập dưới 700";
        }
        else if ($type == 2) {
          $typename = "Hộ nghèo thu nhập trên 700";
        }
        
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getData = DB::table('qlhs_danhsachhongheo')->where('DShongheo_name', $name)->where('DShongheo_birthday', $birthday)->where('DShongheo_sex', $sex)->where('DShongheo_nation_id', $nation)->where('DShongheo_type', $type)->where('DShongheo_site_idthon', $site2)->where('DShongheo_site_idxa', $site1)->where('DShongheo_index', $index)->get();

        if (!is_null($getData) && !empty($getData) && count($getData) > 0) {
          $results['error'] = "Hộ nghèo đã tồn tại!";
        }
        else {
          $insertDSHN = DB::table('qlhs_danhsachhongheo')->insert([
              'DShongheo_name' => $name,
              'DShongheo_birthday' => $birthday,
              'DShongheo_sex' => $sex,
              'DShongheo_relationship' => $relation,
              'DShongheo_nation_id' => $nation,
              'DShongheo_type' => $type,
              'DShongheo_typename' => $typename,
              'DShongheo_site_idthon' => $site2,
              'DShongheo_site_idxa' => $site1,
              'DShongheo_usercreate_id' => $currentuser_id,
              'DShongheo_createdate' => $currentdate,
              'DShongheo_index' => $index
            ]);

          if ($insertDSHN > 0) {
            $results['success'] = "Thêm mới thành công";
          }else{
            $results['error'] = "Thêm mới thất bại";
          }
        }

        return $results;
      }catch(Exception $e){
        return $e;
      }
    }

    public function updateDSHN(Request $request){
      $results = [];
      try{
        $dshn_id = $request->input("DSHNID");
        $name = $request->input("NAME");
        $birthday = $request->input("BIRTHDAY");
        $sex = $request->input("SEX");
        $nation = $request->input("NATION");
        $relation = $request->input("RELATIONSHIP");
        $site1 = $request->input("SITE1");
        $site2 = $request->input("SITE2");
        $type = $request->input("TYPE");
        $index = $request->input("INDEX");

        $typename = "";

        if ($type == 1) {
          $typename = "Hộ nghèo thu nhập dưới 700";
        }
        else if ($type == 2) {
          $typename = "Hộ nghèo thu nhập trên 700";
        }
        
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $updateDSHN = DB::table('qlhs_danhsachhongheo')
        ->where('DShongheo_id', '=', $dshn_id)
        ->update([
            'DShongheo_name' => $name,
            'DShongheo_birthday' => $birthday,
            'DShongheo_sex' => $sex,
            'DShongheo_relationship' => $relation,
            'DShongheo_nation_id' => $nation,
            'DShongheo_type' => $type,
            'DShongheo_typename' => $typename,
            'DShongheo_site_idthon' => $site2,
            'DShongheo_site_idxa' => $site1,
            'DShongheo_usercreate_id' => $currentuser_id,
            'DShongheo_createdate' => $currentdate,
            'DShongheo_index' => $index
          ]);

        if ($updateDSHN > 0) {
          $results['success'] = "Sửa thành công";
        }
        else{
          $results['error'] = 'Sửa thất bại!';
        }

        return $results;
      }catch(Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function deleteDSHN(Request $request){
      $results = [];
      try{        
        $dshn_id = $request->input("DSHNID");

        $deleteDSHN = DB::table("qlhs_danhsachhongheo")->where('DShongheo_id', '=', $dshn_id)->delete();

        if ($deleteDSHN > 0) { $results['success'] = "Xóa thành công!"; }
        else { $results['error'] = "Xóa thất bại!"; }

        return $results;
      }catch(Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function getDSHNbyID(Request $request)
    {
      try {
        $dshn_id = $request->input("DSHNID");

        $objDSHN = null;
        if ($dshn_id > 0) {
          $objDSHN = DB::table('qlhs_danhsachhongheo')->where('DShongheo_id', '=', $dshn_id)->get();
        }
        return $objDSHN;
      } catch (Exception $e) {
        return $e;
      }
    }

//-----------------------------------------------------Quản lý người nấu ăn--------------------------------------------------------------------------
    public function listGetNGNA(){
      // $results = DB::table('qlhs_nguoinauan')->orderBy('NGNA_school_id', 'asc')->orderBy('NGNA_create_date', 'desc')->get();
      return view('admin.danhmuc.nguoinauan.listing');//->with('list', $results)
    }

    public function loadDataUpdateInsert(Request $request){
        $schools_id = $request->schools_id;
        $namhoc = explode('-', $request->years);
        $hocky = $namhoc[0];
        $years =  $namhoc[1];
        $units =  $request->units;
        $hs = LichSuHSHocSinh::whereIn('history_school_id',explode('-',Auth::user()->truong_id))->where('history_statute_116',1);
        // if($units){
        //     $hs = $hs->where('unit_class',$units);
        // }
        if($schools_id != "" && $schools_id != null){
            $hs = $hs->where('history_school_id',$schools_id);
        }
        $hs = $hs->where('history_year',$years.'-'.($years + 1));
        if($hocky == 'HK1'){
            $hs = $hs->where('P_His_startdate','<=',$years.'-09-01')
            ->where('p_His_enddate','>=',$years.'-12-31');
        }

        if($hocky == 'HK2'){
            $hs = $hs->where('P_His_startdate','<=',$years.'-12-31')
            ->where('p_His_enddate','>=',$years.'-05-01');
        }
        $soluong = $hs->count();

        $data['tongso'] = $soluong;
        $data['KPTW'] = $soluong <= 150 ? $soluong : 150;
        $data['KPDP'] = $soluong > 150 ? ($soluong - 150) : 0;
        return $data;

    }

    public function loadNguoinauan(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $schools_id = $req->input('schools_id');
        $units = $req->input('units');
        $years = $req->input('years');
        $load_data = qlhs_nguoinauan::leftJoin('qlhs_schools', 'schools_id','NGNA_school_id')
        ->leftJoin('qlhs_unit', 'unit_id','NGNA_unit_id')
        ->leftJoin('users', 'users.id','NGNA_create_user')
        ->leftJoin('qlhs_schools_history as sh','sh.type_his_school_id','NGNA_school_id')
        ->leftJoin('years_months_kp as kp',function($q){
            $q->on('sh.type_his_type_id','kp.id_schooltype')
            ->where('NGNA_years',\DB::raw(" YEAR(kp.date_con) AND CASE WHEN SUBSTR(NGNA_HK,1,3) = 'HK1' THEN kp.months IN (9,10,11,12) ELSE kp.months IN (1,2,3,4,5) END"))
            ->where('kp.id_doituong',102);
        })
        ->whereIn('NGNA_school_id',explode('-',Auth::user()->truong_id));
        if($years != null && $years != ''){
            $load_data = $load_data->where('NGNA_HK',$years);
        }
        if($schools_id != null && $schools_id != ''){
            $load_data = $load_data->where('schools_id',$schools_id);
        }
        if($units != null && $units != ''){
            $load_data = $load_data->where('NGNA_unit_id',$units);
        }
        //if ($keysearch != null && $keysearch != "") {
            

            // ->where("schools_name", "LIKE","%".$keysearch."%")
            // // $load_data = DB::table('qlhs_nguoinauan')
            // // ->join('qlhs_schools', 'schools_id', '=', 'NGNA_school_id')
            // // ->join('users', 'id', '=', 'NGNA_create_user')
            // ->select('NGNA_id', 'NGNA_school_id', 'schools_name', 'NGNA_amount', 'NGNA_startdate', 'NGNA_enddate', 'NGNA_create_user', 'NGNA_create_date', 'first_name', 'last_name')
            // ->where("schools_name", "LIKE","%".$keysearch."%")
            // ->orWhere("NGNA_startdate", "LIKE", "%".$keysearch."%")
            // ->orWhere("NGNA_enddate", "LIKE", "%".$keysearch."%")
            // ->orWhere("NGNA_create_date", "LIKE", "%".$keysearch."%")
            // ->orWhere("first_name", "LIKE", "%".$keysearch."%")
            // ->orWhere("last_name", "LIKE", "%".$keysearch."%");
        //}
       // else {
        //   $load_data = DB::table('qlhs_nguoinauan')
        //   ->join('qlhs_schools', 'schools_id', '=', 'NGNA_school_id')
        //   ->join('users', 'id', '=', 'NGNA_create_user')
        //   ->select('NGNA_id', 'NGNA_school_id', 'schools_name', 'NGNA_amount', 'NGNA_startdate', 'NGNA_enddate', 'NGNA_create_user', 'NGNA_create_date', 'first_name', 'last_name');
        // }
        $load_data = $load_data->select(\DB::raw("ROUND(SUM(CASE WHEN NGNA_Total < 5 THEN ((NGNA_TW - NGNA_68) * 1.35 * value_m)
       ELSE (5 * 1.35 * value_m) END)) as 'TW',
       ROUND(SUM(CASE WHEN NGNA_Total > 5 THEN ((NGNA_DP - NGNA_68) * 2.25 * value_m)
       ELSE 0 END)) as 'DP',
       schools_name,unit_name,NGNA_HK,NGNA_years,NGNA_TW,NGNA_DP,NGNA_68,NGNA_amount,NGNA_id,NGNA_Status,NGNA_unit_id"))
                              ->groupBy('value_m','years','ngna_hk','schools_name','unit_name','NGNA_HK','NGNA_years','NGNA_TW','NGNA_DP','NGNA_68','NGNA_amount','NGNA_id','NGNA_Status','NGNA_unit_id');      
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['totalRows'] = count($load_data->get());
        $json['data'] = $load_data->orderBy('qlhs_nguoinauan.created_at', 'desc')->skip($start*$limit)->take($limit)->get();

        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function insertNGNA(Request $request){
      $results = [];
      try{        
        $schools_id = $request->SCHOOLID;
        $amount = $request->HSBT;
        $NVTW = $request->NVTW;
        $NVDP = $request->NVDP;
        $NV68 = $request->NV68 != null ?$request->NV68 : 0;

        $Years =  $request->Years;
        $Units =  $request->Units;
        $NGNAID = $request->NGNAID;

        $NGNA_HSTW = $request->HSTW;
        $NGNA_HSDP = $request->HSDP;
        $NGNA_Total = $NVTW + $NVDP - $NV68;
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now();
        if($NGNAID != null && $NGNAID != ""){
            $NGNA = qlhs_nguoinauan::find($NGNAID);
        }else{
            $NGNA = new qlhs_nguoinauan();
        }
        $NGNA->NGNA_school_id = $schools_id;
        $NGNA->NGNA_amount = $amount;
        $NGNA->NGNA_HK = $Years;
        $NGNA->NGNA_years = explode('-',$Years)[1];
        $NGNA->NGNA_unit_id = $Units;
        $NGNA->NGNA_TW = $NVTW;
        $NGNA->NGNA_DP = $NVDP;
        $NGNA->NGNA_68 = $NV68;
        $NGNA->NGNA_HSTW = $NGNA_HSTW;
        $NGNA->NGNA_HSDP = $NGNA_HSDP;
        $NGNA->NGNA_Total = $NGNA_Total;

        $NGNA->NGNA_create_user = $currentuser_id;
        $NGNA->save();

        $results['success'] = "Lưu bản ghi thành công";

      }catch(Exception $e){
          $results['error'] = "Lỗi lưu dữ liệu.";
      }
      return $results;
    }

    public function updateNGNA(Request $request){
      $results = [];
      try{
        $ngnaid = $request->input("NGNAID");
        $schools_id = $request->input("SCHOOLID");
        $amount = $request->input("AMOUNT");
        $startdate = $request->input("STARTDATE");
        $enddate = $request->input("ENDDATE");

        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $updateNGNA = DB::table('qlhs_nguoinauan')
        ->where('NGNA_id', '=', $ngnaid)
        ->update([
          // 'NGNA_school_id' => $schools_id,
          'NGNA_amount' => $amount,
          // 'NGNA_startdate' => Carbon::parse($startdate),
          // 'NGNA_enddate' => Carbon::parse($enddate),
          'NGNA_create_user' => $currentuser_id,
          'NGNA_create_date' => $currentdate,
          ]);

        if ($updateNGNA > 0) {
          $results['success'] = "Sửa thành công";
        }
        else{
          $results['error'] = 'Sửa thất bại!';
        }

        return $results;
      }catch(Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function deleteNGNA(Request $request){
      $results = [];
      try{        
        $ngnaid = $request->input("NGNAID");

        $deleteNGNA = DB::table("qlhs_nguoinauan")->where('NGNA_id', '=', $ngnaid)->delete();

        if ($deleteNGNA > 0) { $results['success'] = "Xóa thành công!"; }
        else { $results['error'] = "Xóa thất bại!"; }

        return $results;
      }catch(Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function getNgnabyID(Request $request)
    {
      try {
        $ngnaid = $request->input("NGNAID");

        $objNGNA = null;
        if ($ngnaid > 0) {
          $objNGNA = DB::table('qlhs_nguoinauan')->where('NGNA_id', '=', $ngnaid)->get();
        }
        return $objNGNA;
      } catch (Exception $e) {
        return $e;
      }
    }

//Export Excel----------------------------------------------------------------------------------------------    

    public function exportExcel($formName)
    {
      $items = "";
      $resultArray = [];
      $status = "";
      $fileName = "";
      $title = "";
      $description = "";
      $sheetname = '';

      if ($formName == "DEPARTMENT") {
        $items = DanhMucPhongBan::select('department_code', 'department_name', 'department_active', 'department_create_userid', 'created_at', 'department_update_userid', 'updated_at')->get();
        $resultArray[] = ['Mã phòng ban', 'Tên phòng ban', 'Trạng thái', 'Người tạo', 'Ngày tạo', 'Người sửa', 'Ngày sửa'];
        $status = "department_active";
        $fileName = "Danh mục phòng ban";
        $title = "Danh mục phòng ban";
        $description = "Danh mục phòng ban";
        $sheetname = 'phòng ban';
      }

      if ($formName == "WARD") {
        $items = DanhMucPLXa::select('wards_code', 'wards_name', 'wards_active', 'wards_create_userid', 'created_at', 'wards_update_userid', 'updated_at')->get();
        $resultArray[] = ['Mã phân loại xã', 'Tên phân loại xã', 'Trạng thái', 'Người tạo', 'Ngày tạo', 'Người sửa', 'Ngày sửa'];
        $status = "wards_active";
        $fileName = "Danh mục phân loại xã";
        $title = "Danh mục phân loại xã";
        $description = "Danh mục phân loại xã";
        $sheetname = "phân loại xã";
      }

      if ($formName == "SITE") {
        $items = DanhMucXaPhuong::select('site_code', 'site_name', 'site_active', 'site_create_userid', 'created_at', 'site_update_userid', 'updated_at')->get();
        $resultArray[] = ['Mã địa phương', 'Tên địa phương', 'Trạng thái', 'Người tạo', 'Ngày tạo', 'Người sửa', 'Ngày sửa'];
        $status = "site_active";
        $fileName = "Danh mục Tỉnh/ thành";
        $title = "Danh mục Tỉnh/ thành";
        $description = "Danh mục Tỉnh/ thành";
        $sheetname = "tỉnh/ thành";
      }

      foreach ($items as $item) {
        if ($item[$status] == 1) {
          $item[$status] = "Đang hoạt động";
        }
        else if ($item[$status] == 0) {
          $item[$status] = "Không hoạt động";
        }
        $resultArray[] = $item->toArray();
      }

      Excel::create($fileName, function($excel) use ($resultArray) {

          // $excel->setTitle($title);
          // $excel->setCreator('Laravel')->setCompany('VUNK, LLC');
          // $excel->setDescription($description);

          $excel->sheet('sheet1', function($sheet) use ($resultArray) {
              $sheet->fromArray($resultArray, null, 'A1', false, false);
          });

      })->export('xlsx');
    }

//Wards-Phân loại xã-----------------------------------------------------------------------------------------
    public function listGetPLXa(){

      //$wards = DB::table('qlhs_wards')->get();
      return view('admin.danhmuc.phanloaixa.listing');//->with('wards', $wards);
      // /return view('category/wards')->with('wards', $wards);
    }
    
    public function insertPLXa(Request $request){
      $results = [];
      try{        
        // $ward_code = $request->input("WARDCODE");
        // $ward_code = trim($ward_code);
        $ward_name = $request->input("WARDNAME");
        $ward_name = trim($ward_name);
        $ward_parent_id = $request->input("WARDPARENTID");
        $ward_active = $request->input("WARDACTIVE");
        $ward_rewrite = $this->to_slug(trim($ward_name));
        $ward_md5 = md5($ward_rewrite);
        $ward_level = $request->input("WARDLEVEL") ? $request->input("WARDLEVEL") : 1;
        if ($ward_level > 1) {
          $ward_level = $ward_level + 1;
        }
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getbyWard_Code = DB::table('qlhs_wards')->where('wards_name', '=', $ward_name)->get();

        if (count($getbyWard_Code) > 0) {
            $results['error'] = 'Tên đã tồn tại, vui lòng nhập tên khác!';
        }
        else{
          $phanloaixa = new DanhMucPLXa();
          // $phanloaixa->wards_code = $ward_code;
          $phanloaixa->wards_name = $ward_name;
          $phanloaixa->wards_parent_id = $ward_parent_id;
          $phanloaixa->wards_active = $ward_active;
          $phanloaixa->wards_rewrite = $ward_rewrite;
          $phanloaixa->wards_md5 = $ward_md5;
          $phanloaixa->wards_level = $ward_level;
          $phanloaixa->wards_create_userid = $currentuser_id;
          $phanloaixa->created_at = $currentdate;
          $phanloaixa->wards_update_userid = $currentuser_id;
          $phanloaixa->updated_at = $currentdate;
          $phanloaixa->save();

          $results['success'] = 'Thêm mới thành công!';
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }
    
    public function updatePLXa(Request $request){
      $results = [];
      try{
        //$user = Auth::user()->id;
        $ward_id = $request->input("WARDID");
        $ward_name = $request->input("WARDNAME");
        $ward_name = trim($ward_name);
        $ward_parent_id = $request->input("WARDPARENTID");
        $ward_active = $request->input("WARDACTIVE");
        $ward_rewrite = $this->to_slug(trim($ward_name));
        $ward_md5 = md5($ward_rewrite);
        $ward_level = $request->input("WARDLEVEL") ? $request->input("WARDLEVEL") : 1;
        if ($ward_level != 0) {
          $ward_level = $ward_level + 1;
        }
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');
        //$now = Carbon::now('Asia/Ho_Chi_Minh');
        
        $updateWard = DB::update("update qlhs_wards set wards_name = '$ward_name', wards_parent_id = '$ward_parent_id', wards_active = '$ward_active', wards_rewrite = '$ward_rewrite', wards_md5 = '$ward_md5', wards_level = '$ward_level', wards_update_userid = '$currentuser_id', updated_at = '$currentdate' where wards_id = '$ward_id'");

        //$updatePLXa = DanhMucPLXa::where('wards_id', '=', $ward_id)->update(['wards_name' => $ward_name, 'wards_parent_id' => $ward_parent_id, 'wards_active' => $ward_active, 'wards_rewrite' => $ward_rewrite, 'wards_md5' => $ward_md5, 'wards_level' => $ward_level, 'wards_update_userid' => $currentuser_id, 'wards_updatedate' => $currentdate]);
        if ($updateWard >= 0) {
          $results['success'] = "Sửa thành công!";
        }
        else {$results['error'] = "Sửa thất bại!";}
        return $results;
      }catch(\Exception $e){
        return $e;
      }
    }
    
    public function deletePLXa(Request $request){
      $results = [];
      try{
        $ward_id = $request->input("WARDID");

        $getbyWard_Id = DB::table('qlhs_wards')->where('wards_parent_id', '=', $ward_id)->where('wards_active', '=', 1)->get();

        if (count($getbyWard_Id) > 0) {                
          $results['error'] = "Hiện đang có cấp nhỏ hơn hoạt động, không thể xóa!";
        }else{
          $deleteWard = DB::table('qlhs_wards')->where('wards_id', '=', $ward_id)->delete();
          //$deletePLXa = DanhMucPLXa::where('wards_id', '=', $ward_id)->delete();
          if ($deleteWard > 0) {
            $results['success'] = "Xóa thành công!";
          }
          else {$results['error'] = "Xóa thất bại!";}
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $e;
      }
    }

//Unit-Khối--------------------------------------------------------------------------------------------------

    public function listGetKhoi(){
      $results = DB::table('qlhs_unit')->orderBy('updated_at', 'desc')->get();

      //return response()->json($results);
      //die (json_encode($results));['list'=>$list]
      return view('admin.danhmuc.khoi.listing')->with('list', $results);
    }

    public function loadKhoi(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $keysearch = $req->input('key');
        $load_unit = null;

        if ($keysearch != null && $keysearch != "") {
          $load_unit = DB::table('qlhs_unit')
            ->where("unit_code", "LIKE","%".$keysearch."%")->orWhere("unit_name", "LIKE", "%".$keysearch."%");
        }
        else {
          $load_unit = DB::table('qlhs_unit');
        }
        
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['totalRows'] = $load_unit->count();
        $json['data'] = $load_unit->orderBy('qlhs_unit.updated_at', 'desc')->skip($start*$limit)->take($limit)->get();
        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function exportExcelUnit()
    {
      $units = DanhMucKhoi::select('unit_code', 'unit_name', 'unit_active', 'unit_create_userid', 'created_at', 'unit_update_userid', 'updated_at')->get();
      
      $unitArray = []; 

      $unitArray[] = ['Mã khối', 'Tên khối', 'Trạng thái', 'Người tạo', 'Ngày tạo', 'Người sửa', 'Ngày sửa'];

      foreach ($units as $unit) {
        if ($unit['unit_active'] == 1) {
          $unit['unit_active'] = "Đang hoạt động";
        }
        else if ($unit['unit_active'] == 0) {
          $unit['unit_active'] = "Không hoạt động";
        }
          $unitArray[] = $unit->toArray();
      }

      Excel::create('Danh mục khối', function($excel) use ($unitArray) {

          $excel->setTitle('Khối');
          $excel->setCreator('Laravel')->setCompany('VUNK, LLC');
          $excel->setDescription('danh mục khối');

          $excel->sheet('khối', function($sheet) use ($unitArray) {
              $sheet->fromArray($unitArray, null, 'A1', false, false);
          });

      })->export('xlsx');
    }

    public function insertKhoi(Request $request){
      $results = [];
      try{        
        // $unit_code = $request->input("UNITCODE");
        // $unit_code = trim($unit_code);
        $unit_name = $request->input("UNITNAME");
        $unit_name = trim($unit_name);
        // $unit_school_id = $request->input("UNITSCHOOLID");
        $unit_active = $request->input("UNITACTIVE");
        $unit_rewrite = $this->to_slug(trim($unit_name));
        $unit_md5 = md5($unit_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getbyUnit_Name = DB::table('qlhs_unit')->where('unit_name', '=', $unit_name)->get();

        if (count($getbyUnit_Name) > 0) {
            $results['error'] = 'Tên đã tồn tại, vui lòng nhập tên khác!';
        }
        else{
          $khoi = new DanhMucKhoi();
          // $khoi->unit_school_id = $unit_school_id;
          // $khoi->unit_code = $unit_code;
          $khoi->unit_name = $unit_name;
          $khoi->unit_active = $unit_active;
          $khoi->unit_rewrite = $unit_rewrite;
          $khoi->unit_md5 = $unit_md5;
          $khoi->unit_create_userid = $currentuser_id;
          $khoi->created_at = $currentdate;
          $khoi->unit_update_userid = $currentuser_id;
          $khoi->updated_at = $currentdate;
          $khoi->save();

          $results['success'] = "Thêm mới khối thành công";
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }
    
    public function updateKhoi(Request $request){
      $results = [];
      try{
        $unit_id = $request->input("UNITID");
        $unit_name = $request->input("UNITNAME");
        $unit_name = trim($unit_name);
        $unit_active = $request->input("UNITACTIVE");
        $unit_rewrite = $this->to_slug(trim($unit_name));
        $unit_md5 = md5($unit_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $updateKhoi = DB::table('qlhs_unit')->where('unit_id', '=', $unit_id)
          ->update([
            'unit_name' => $unit_name,
            'unit_active' => $unit_active,
            'unit_rewrite' => $unit_rewrite,
            'unit_md5' => $unit_md5,
            'unit_update_userid' => $currentuser_id,
            'updated_at' => $currentdate
            ]);

        if ($updateKhoi > 0) { $results['success'] = "Sửa khối thành công!"; }
        else { $results['error'] = "Sửa khối thất bại!"; }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }
    
    public function deleteKhoi(Request $request){
      $results = [];
      try{
        $unit_id = $request->input("UNITID");
        $getUnit = DB::table('qlhs_class')->where('class_unit_id', '=', $unit_id)->get();
        if (count($getUnit) > 0) {
          $results['error'] = "Yêu cầu xóa lớp trước khi xóa khối!";
        }
        else{
          $deleteKhoi = DB::table("qlhs_unit")->where('unit_id', '=', $unit_id)->delete();
         // $deleteKhoi = DB::delete("delete from qlhs_unit where unit_id = ?", array($unit_id));//DanhMucKhoi::where('unit_id', '=', $unit_id)->delete();

          if ($deleteKhoi > 0) { $results['success'] = "Xóa khối thành công!"; }
          else { $results['error'] = "Xóa khối thất bại!"; }
        }
        return $results;
      }catch(\Exception $e){
        $results['error'] = $e . "Exception";
        return $results;
      }
    }

//Group-Nhóm đối tượng---------------------------------------------------------------------------------------

    public function listGetGroupDoiTuong(){
      $groups = DB::table('qlhs_group')->orderBy('updated_at', 'desc')->get();

      //return response()->json($results);
      //die (json_encode($results));['list'=>$list]
      return view('admin.danhmuc.nhomdoituong.listing')->with('groups', $groups);
    }

    public function loadNhomDoiTuong(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $keysearch = $req->input('key');
        $load_group = null;

        if ($keysearch != null && $keysearch != "") {
          $load_group = DB::table('qlhs_group')->select('group_id', 'group_code', 'group_name', 'group_active', 'updated_at')->where("group_code", "LIKE","%".$keysearch."%")->orWhere("group_name", "LIKE", "%".$keysearch."%");
        }
        else {
          $load_group = DB::table('qlhs_group')->select('group_id', 'group_code', 'group_name', 'group_active', 'updated_at');
        }
        
        $json['startRecord'] = $start;
        $json['numRows'] = $limit;
        $json['totalRows'] = $load_group->count();
        $json['data'] = $load_group->orderBy('updated_at', 'desc')->skip($start*$limit)->take($limit)->get();
        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function exportExcelGroup()
    {
      $groups = DanhMucNhomDoiTuong::select('group_code', 'group_name', 'group_active', 'group_create_userid', 'created_at', 'group_update_userid', 'updated_at')->get();
      
      $groupArray = []; 

      $groupArray[] = ['Mã chế độ', 'Tên chế độ', 'Trạng thái', 'Người tạo', 'Ngày tạo', 'Người sửa', 'Ngày sửa'];

      foreach ($groups as $group) {
        if ($group['group_active'] == 1) {
          $group['group_active'] = "Đang hoạt động";
        }
        else if ($group['group_active'] == 0) {
          $group['group_active'] = "Không hoạt động";
        }
          $groupArray[] = $group->toArray();
      }

      Excel::create('Danh mục chế độ', function($excel) use ($groupArray) {

          $excel->setTitle('Danh mục chế độ');
          $excel->setCreator('Laravel')->setCompany('VUNK, LLC');
          $excel->setDescription('Danh mục chế độ');

          $excel->sheet('nhóm đối tượng', function($sheet) use ($groupArray) {
              $sheet->fromArray($groupArray, null, 'A1', false, false);
          });

      })->export('xlsx');
    }

    public function insertGroupDoiTuong(Request $request){
      $results = [];
      try{        
        // $group_code = $request->input("GROUPCODE");
        // $group_code = trim($group_code);
        $group_name = $request->input("GROUPNAME");
        $group_name = trim($group_name);
        $group_active = $request->input("GROUPACTIVE");
        $group_rewrite = $this->to_slug(trim($group_name));
        $group_md5 = md5($group_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getbyGroup_Code = DB::table('qlhs_group')->where('group_name', '=', $group_name);
        
        if ($getbyGroup_Code->count() > 0) {
          $results['error'] = "Tên chế độ đã tồn tại, vui lòng nhập tên khác!";
        }
        else{
          $nhomdoituong = new DanhMucNhomDoiTuong();
          // $nhomdoituong->group_code = $group_code;
          $nhomdoituong->group_name = $group_name;
          $nhomdoituong->group_active = $group_active;
          $nhomdoituong->group_rewrite = $group_rewrite;
          $nhomdoituong->group_md5 = $group_md5;
          $nhomdoituong->group_create_userid = $currentuser_id;
          $nhomdoituong->created_at = $currentdate;
          $nhomdoituong->group_update_userid = $currentuser_id;
          $nhomdoituong->updated_at = $currentdate;
          $nhomdoituong->save();
          $results['success'] = "Thêm mới chế độ thành công!";
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }
    
    public function updateGroupDoiTuong(Request $request){
      $results = [];
      try{
        $group_id = $request->input("GROUPID");
        $group_name = $request->input("GROUPNAME");
        $group_name = trim($group_name);
        $group_active = $request->input("GROUPACTIVE");
        $group_rewrite = $this->to_slug(trim($group_name));
        $group_md5 = md5($group_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        //$updateNhomDoiTuong = DanhMucNhomDoiTuong::where('group_id', '=', $group_id)->update(['group_name' => $group_name, 'group_active' => $group_active, 'group_rewrite' => $group_rewrite, 'group_md5' => $group_md5, 'group_update_userid' => $currentuser_id, 'updated_at' => $currentdate]);

        $updateNhomDoiTuong = DB::update("update qlhs_group set group_name = '$group_name', group_active = '$group_active', group_rewrite = '$group_rewrite', group_md5 = '$group_md5', group_update_userid = '$currentuser_id', updated_at = '$currentdate' where group_id = '$group_id'");

        if ($updateNhomDoiTuong > 0) { $results['success'] = "Sửa chế độ thành công!"; }
        else { $results['error'] = "Sửa chế độ thất bại!"; }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }
    
    public function deleteGroupDoiTuong(Request $request){
      $results = [];
      try{
        $group_id = $request->input("GROUPID");

        $deleteNhom = DB::table("qlhs_group")->where('group_id', '=', $group_id)->delete();//DanhMucNhomDoiTuong::where('group_id', '=', $group_id)->delete();
        if ($deleteNhom > 0) { $results['success'] = "Xóa chế độ thành công!"; }
        else { $results['error'] = "Xóa chế độ thất bại!"; }
        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

//Nationals-Dân tộc------------------------------------------------------------------------------------------

    public function listGetDantoc(){
      //$results = DB::table('qlhs_nationals')->orderBy('updated_at', 'desc')->get();
      return view('admin.danhmuc.dantoc.listing');
    }

    public function loadDantoc(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $keysearch = $req->input('key');
        $load_nation = null;

        if ($keysearch != null && $keysearch != "") {
          $load_nation = DB::table('qlhs_nationals')->where("nationals_code", "LIKE","%".$keysearch."%")->orWhere("nationals_name", "LIKE", "%".$keysearch."%");
        }
        else {
          $load_nation = DB::table('qlhs_nationals');
        }
        
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['totalRows'] = $load_nation->count();
        $json['data'] = $load_nation->orderBy('updated_at', 'desc')->skip($start*$limit)->take($limit)->get();
        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function exportExcelNation()
    {
      $nations = DanhMucDantoc::select('nationals_code', 'nationals_name', 'nationals_active', 'nationals_create_userid', 'created_at', 'nationals_update_userid', 'updated_at')->get();//->toArray();
      // Excel::create('dantoc', function($excel) use ($data) {
      //   $excel->sheet('dantoc1', function($sheet) use ($data) {
      //     $sheet->fromArray($data);
      //   });
      // })->export('xlsx');
      //return $data;
      //--------------------------------------------------------------------------------------------
      
      $nationArray = []; 

      $nationArray[] = ['Mã dân tộc', 'Tên dân tộc', 'Trạng thái', 'Người tạo', 'Ngày tạo', 'Người sửa', 'Ngày sửa'];

      foreach ($nations as $nation) {
        if ($nation['nationals_active'] == 1) {
          $nation['nationals_active'] = "Đang hoạt động";
        }
        else if ($nation['nationals_active'] == 0) {
          $nation['nationals_active'] = "Không hoạt động";
        }
          $nationArray[] = $nation->toArray();
      }

      Excel::create('nations', function($excel) use ($nationArray) {

          $excel->setTitle('nations');
          $excel->setCreator('Laravel')->setCompany('VUNK, LLC');
          $excel->setDescription('nations file');

          $excel->sheet('sheet1', function($sheet) use ($nationArray) {
              $sheet->fromArray($nationArray, null, 'A1', false, false);
          });

      })->export('xlsx');
    }

    public function insertDantoc(Request $request){
      $results = [];
      try{        
        $nation_type = $request->input("NATIONTYPE");
        // $nation_code = trim($nation_code);
        $nation_name = $request->input("NATIONNAME");
        $nation_name = trim($nation_name);
        $nation_active = $request->input("NATIONACTIVE");
        $nation_rewrite = $this->to_slug(trim($nation_name));
        $nation_md5 = md5($nation_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getbyNation_Code = DB::table('qlhs_nationals')->where('nationals_name', '=', $nation_name)->get();

        if (count($getbyNation_Code) > 0) {
            $results['error'] = 'Tên dân tộc đã tồn tại, vui lòng nhập tên khác!';
        }
        else{
          $dantoc = new DanhMucDantoc();
          $dantoc->nationals_type = $nation_type;
          $dantoc->nationals_name = $nation_name;
          $dantoc->nationals_active = $nation_active;
          $dantoc->nationals_rewrite = $nation_rewrite;
          $dantoc->nationals_md5 = $nation_md5;
          $dantoc->nationals_create_userid = $currentuser_id;
          $dantoc->created_at = $currentdate;
          $dantoc->nationals_update_userid = $currentuser_id;
          $dantoc->updated_at = $currentdate;
          $dantoc->save();
          $results['success'] = "Thêm mới dân tộc thành công";
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function updateDantoc(Request $request){
      $results = [];
      try{        
        $nation_id = $request->input("NATIONID");
        $nation_name = $request->input("NATIONNAME");
        $nation_name = trim($nation_name);
        $nation_type = $request->input("NATIONTYPE");
        $nation_active = $request->input("NATIONACTIVE");
        $nation_rewrite = $this->to_slug(trim($nation_name));
        $nation_md5 = md5($nation_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $updateDantoc = DB::table('qlhs_nationals')->where('nationals_id', $nation_id)
          ->update([
              'nationals_name' => $nation_name, 
              'nationals_active' => $nation_active, 
              'nationals_update_userid' => $currentuser_id, 
              'updated_at' => $currentdate, 
              'nationals_type' => $nation_type
            ]);

        if ($updateDantoc > 0) {
          $results['success'] = "Sửa dân tộc thành công";
        }
        else {$results['error'] = "Sửa dân tộc thất bại";}

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function deleteDantoc(Request $request){
      $results = [];
      try{        
        $nation_id = $request->input("NATIONID");

        $deleteDantoc = DB::table("qlhs_nationals")->where('nationals_id', '=', $nation_id)->delete();

        if ($deleteDantoc > 0) { $results['success'] = "Xóa dân tộc thành công!"; }
        else { $results['error'] = "Xóa dân tộc thất bại!"; }
        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

//Subject-Đối tượng------------------------------------------------------------------------------------------

    public function listGetDoiTuong(){
      $groups = DB::select('select * from qlhs_group where group_active = 1', array());

      $subjects = DB::table('qlhs_subject')->orderBy('updated_at', 'desc')->get();

      $subjects_his = DB::select('select * from qlhs_subject_history', array());
      return view('admin.danhmuc.doituong.listing', compact( 'groups', 'subjects', 'subjects_his'));
    }

    public function loadDoiTuong(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $keysearch = $req->input('key');
        $load_subject = null;

        if ($keysearch != null && $keysearch != "") {
          $load_subject = DB::table('qlhs_subject')
          ->leftJoin('qlhs_subject_history','qlhs_subject.subject_id' ,'=', 'qlhs_subject_history.subject_history_subject_id')
          ->leftJoin('qlhs_group','qlhs_group.group_id' ,'=', 'qlhs_subject_history.subject_history_group_id')
          ->where("subject_name", "LIKE", "%".$keysearch."%")->where('key','<>',0)
          ->orWhere("group_name", "LIKE", "%".$keysearch."%")
          ->select('subject_name','subject_id','subject_active',DB::raw('GROUP_CONCAT(group_name) as group_name '))->groupBy('subject_name','subject_id','subject_active');
        }
        else {
          $load_subject = DB::table('qlhs_subject')
          ->leftJoin('qlhs_subject_history','qlhs_subject.subject_id' ,'=', 'qlhs_subject_history.subject_history_subject_id')
          ->leftJoin('qlhs_group','qlhs_group.group_id' ,'=', 'qlhs_subject_history.subject_history_group_id')->where('key','<>',0)
          ->select('subject_name','subject_id','subject_active',DB::raw('GROUP_CONCAT(group_name) as group_name '))->groupBy('subject_name','subject_id','subject_active');
        }

        // return $load_subject->toSql();
        
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['totalRows'] = $load_subject->get()->count();
        $json['data'] = $load_subject->orderBy('qlhs_subject.updated_at', 'desc')->skip($start*$limit)->take($limit)->get();
        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function exportExcelSubject()
    {
      $subjects = DanhMucDoiTuong::select('subject_code', 'subject_name', 'subject_active', 'subject_create_userid', 'created_at', 'subject_update_userid', 'updated_at')->get();

      //$groupSubject = DB::table('qlhs_subject_history')->join('qlhs_group','qlhs_group.group_id' ,'=', 'qlhs_subject_history.subject_history_group_id')->select('qlhs_subject_history.subject_history_subject_id', 'qlhs_group.group_id', 'qlhs_group.group_name')->get();
      $groupSubject = DB::select("select qlhs_subject_history.subject_history_subject_id, qlhs_group.group_id, qlhs_group.group_name from qlhs_subject_history, qlhs_group where qlhs_group.group_id = qlhs_subject_history.subject_history_group_id", array());
      
      $subjectArray = []; 

      $subjectArray[] = ['Mã nhóm đối tượng', 'Tên nhóm đối tượng', 'Trạng thái', 'Người tạo', 'Ngày tạo', 'Người sửa', 'Ngày sửa', 'Thuộc chế độ'];

      foreach ($subjects as $subject) {
        if ($subject['subject_active'] == 1) {
          $subject['subject_active'] = "Đang hoạt động";
        }
        else if ($subject['subject_active'] == 0) {
          $subject['subject_active'] = "Không hoạt động";
        }
        $subjectArray[] = $subject->toArray();
        // $subjectArray['Tên đối tượng'] = $subject['subject_name'];
        // $subjectArray['Trạng thái'] = $subject['subject_active'];
        // $subjectArray['Người tạo'] = $subject['subject_create_userid'];
        // $subjectArray['Ngày tạo'] = $subject['created_at'];
        // $subjectArray['Người sửa'] = $subject['subject_update_userid'];
        // $subjectArray['Ngày sửa'] = $subject['updated_at'];
        // foreach ($groupSubject as $value) {
        //   if ($subject['subject_id'] == $value->{'subject_history_subject_id'}) {
        //     $subjectArray['Thuộc nhóm'] = $value->{'group_name'} . ',';
        //   }          
        // }
      }

      Excel::create('Danh mục nhóm đối tượng', function($excel) use ($subjectArray) {

          $excel->setTitle('Danh mục nhóm đối tượng');
          $excel->setCreator('Laravel')->setCompany('VUNK, LLC');
          $excel->setDescription('Danh mục nhóm đối tượng');

          $excel->sheet('nhóm đối tượng', function($sheet) use ($subjectArray) {
              $sheet->fromArray($subjectArray, null, 'A1', false, false);
          });

      })->export('xlsx');
    }

    public function insertDoiTuong(Request $request){
      $results = [];
      try{
        $arrGroup_id = array();
        $arrGroup_id = $request->input("ARRGROUPID");
        // $subject_code = $request->input("SUBJECTCODE");
        // $subject_code = trim($subject_code);
        $subject_name = $request->input("SUBJECTNAME");
        $subject_name = trim($subject_name);
        $subject_active = $request->input("SUBJECTACTIVE");
        $subject_rewrite = $this->to_slug(trim($subject_name));
        $subject_md5 = md5($subject_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getbySubject_Code = DB::table('qlhs_subject')->where('subject_name', '=', $subject_name)->get();

        if (count($getbySubject_Code) > 0) {
            $results['error'] = 'Tên nhóm đối tượng đã tồn tại, vui lòng nhập tên khác!';
        }
        else{
          $doituong = new DanhMucDoiTuong();
          // $doituong->subject_code = $subject_code;
          $doituong->subject_name = $subject_name;
          $doituong->subject_active = $subject_active;
          $doituong->subject_rewrite = $subject_rewrite;
          $doituong->subject_md5 = $subject_md5;
          $doituong->subject_create_userid = $currentuser_id;
          $doituong->created_at = $currentdate;
          $doituong->subject_update_userid = $currentuser_id;
          $doituong->updated_at = $currentdate;
          $doituong->save();

          $insertedId = $doituong->id;

          if (!is_null($arrGroup_id) && !empty($arrGroup_id) && count($arrGroup_id) > 0 && $insertedId > 0) {
            foreach ($arrGroup_id as $value) {
              
              $idGroup = (int)$value['value'];
              $insertSubject_His = DB::table('qlhs_subject_history')->insert([
                'subject_history_subject_id' => $insertedId, 
                'subject_history_group_id' => $idGroup
              ]);
            }
          }
          $results['success'] = "Thêm mới nhóm đối tượng thành công!";
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function updateDoiTuong(Request $request){
      $results = [];
      try{        
        $arrGroup_id = array();
        $arrGroup_id = $request->input("ARRGROUPID");
        $subject_id = $request->input("SUBJECTID");
        $subject_name = $request->input("SUBJECTNAME");
        $subject_name = trim($subject_name);
        $subject_active = $request->input("SUBJECTACTIVE");
        $subject_rewrite = $this->to_slug(trim($subject_name));
        $subject_md5 = md5($subject_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $updateDoiTuong = DB::update("update qlhs_subject set subject_name = '$subject_name', subject_active = '$subject_active', subject_rewrite = '$subject_rewrite', subject_md5 = '$subject_md5', subject_update_userid = '$currentuser_id', updated_at = '$currentdate' where subject_id = '$subject_id'");

        if (!is_null($arrGroup_id) && !empty($arrGroup_id) && count($arrGroup_id) > 0) {
          $deleteLichsu = DB::table("qlhs_subject_history")->where('subject_history_subject_id', '=', $subject_id)->delete();           

          foreach ($arrGroup_id as $value) {
            // $lichsudoituong = new DanhMucLichSuDoiTuong();
            // $lichsudoituong->subject_history_subject_id = $subject_id;
            // $lichsudoituong->subject_history_group_id = $value;
            // $lichsudoituong->update_at = $currentdate;
            // $lichsudoituong->save();
            $idGroup = (int)$value['value'];
            $insertSubject_His = DB::insert('insert into qlhs_subject_history (subject_history_subject_id, subject_history_group_id) values (?, ?)', array($subject_id, $idGroup));
          }
        }

        if ($updateDoiTuong > 0) {
          $results['success'] = "Sửa nhóm đối tượng thành công!";
        }
        else { $results['error'] = "Sửa nhóm đối tượng thành công!"; }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function deleteDoiTuong(Request $request){
      $results = [];
      try{        
        $subject_id = $request->input("SUBJECTID");
        //Xóa đối tượng
        $deleteDoiTuong = DB::table("qlhs_subject")->where('subject_id', '=', $subject_id)->delete();
        //Xóa History_Subject
        $deleteLichsu = DB::table("qlhs_subject_history")->where('subject_history_subject_id', '=', $subject_id)->delete();

        if ($deleteDoiTuong > 0) {
          $results['success'] = "Xóa nhóm đối tượng thành công!";
        }
        else { $results['error'] = "Xóa nhóm đối tượng thất bại!"; }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

//Schools-Trường---------------------------------------------------------------------------------------------

    public function listGetTruong(){
     // $schools = DB::table('qlhs_schools')->orderBy('updated_at', 'desc')->get();

     // $units = DB::select('select unit_id, unit_name from qlhs_unit where unit_active = 1', array());

      return view('admin.danhmuc.truong.listing');//, compact('schools', 'units'));
    }

    public function loadTruong(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $keysearch = $req->input('key');
        $load_schools = null;

        if ($keysearch != null && $keysearch != "") {
          $load_schools = DB::table('qlhs_schools')
            // ->leftJoin('qlhs_unit','qlhs_schools.schools_unit_id' ,'=', 'qlhs_unit.unit_id')
            ->leftJoin('qlhs_schools_history', 'type_his_school_id', '=', 'schools_id')
            ->leftJoin('qlhs_school_type', 'school_type_id', '=', 'type_his_type_id')
            ->where("schools_code", "LIKE","%".$keysearch."%")
            ->orWhere("schools_name", "LIKE", "%".$keysearch."%")
            // ->orWhere("unit_name", "LIKE", "%".$keysearch."%")
            ->orWhere("school_type_name", "LIKE", "%".$keysearch."%");
        }
        else {
          $load_schools = DB::table('qlhs_schools')
            // ->leftJoin('qlhs_unit','qlhs_schools.schools_unit_id' ,'=', 'qlhs_unit.unit_id')
            ->leftJoin('qlhs_schools_history', 'type_his_school_id', '=', 'schools_id')
            ->leftJoin('qlhs_school_type', 'school_type_id', '=', 'type_his_type_id');
        }

        if (Auth::user()->truong_id != null && Auth::user()->truong_id > 0) {
          $load_schools->where('schools_id', '=', Auth::user()->truong_id);
        }
        
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['totalRows'] = $load_schools->count();
        $json['data'] = $load_schools->orderBy('qlhs_schools.updated_at', 'desc')->orderBy('type_his_startdate', 'desc')->skip($start*$limit)->take($limit)->get();
        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function loadSchoolType(){
      try {
        $getData = DB::table('qlhs_school_type')->orderBy('school_type_name', 'asc')->get();

        return $getData;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function exportExcelSchool()
    {
      //$schools = DB::select("select qlhs_schools.schools_code, qlhs_schools.schools_name, qlhs_schools.schools_active, qlhs_unit.unit_name, qlhs_schools.schools_create_userid, qlhs_schools.created_at, qlhs_schools.schools_update_userid, qlhs_schools.created_at from qlhs_schools, qlhs_unit where qlhs_schools.schools_unit_id = qlhs_unit.unit_id", array());
      $schools = DanhMucTruong::select('schools_code', 'schools_name', 'schools_active', 'schools_create_userid', 'created_at', 'schools_update_userid', 'updated_at')->get();
      
      $schoolArray = []; 

      $schoolArray[] = ['Mã trường', 'Tên trường', 'Trạng thái', 'Người tạo', 'Ngày tạo', 'Người sửa', 'Ngày sửa'];

      foreach ($schools as $school) {
        if ($school->{'schools_active'} == 1) {
          $school->{'schools_active'} = "Đang hoạt động";
        }
        else if ($school->{'schools_active'} == 0) {
          $school->{'schools_active'} = "Không hoạt động";
        }
        $schoolArray[] = $school->toArray();
      }
      //return $schoolArray;
      Excel::create('Danh mục trường', function($excel) use ($schoolArray) {

          $excel->setTitle('Danh mục trường');
          $excel->setCreator('Laravel')->setCompany('VUNK, LLC');
          $excel->setDescription('Danh mục trường');

          $excel->sheet('trường', function($sheet) use ($schoolArray) {
              $sheet->fromArray($schoolArray, null, 'A1', false, false);
          });

      })->export('xlsx');
    }

    public function insertTruong(Request $request){
      $results = [];
      try{
        $cbxMCCTT = $request->input("MCCTT");
        $school_name = $request->input("SCHOOLNAME");
        $school_name = trim($school_name);
        $school_type = $request->input("SCHOOLTYPE");
        $startDate = $request->input("STARTDATE");
        $school_active = $request->input("SCHOOLACTIVE");
        $school_rewrite = $this->to_slug(trim($school_name));
        $UnitSchool = $request->input("UnitSchool");//truong lien cap
        $school_md5 = md5($school_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now();
        //--------------------------Tính thời gian-----------------------------------
        $startDateformat = Carbon::parse($startDate);

        //----------------------------------------------------------------------------

        $getbySchool_Name = DB::table('qlhs_schools')
            ->where('schools_name',$school_name)
            ->get();

        if (count($getbySchool_Name) > 0) {
            $results['error'] = 'Tên trường đã tồn tại, vui lòng nhập tên khác!';
        }
        else{
          $truong = new DanhMucTruong();
          // $truong->schools_code = $school_code;
          $truong->schools_name = $school_name;
          $truong->schools_active = $school_active;
          $truong->schools_rewrite = $school_rewrite;
          $truong->schools_md5 = $school_md5;
          $truong->TramTauOrMCC = $cbxMCCTT;
          $truong->LienCap = $UnitSchool;
          $truong->schools_create_userid = $currentuser_id;
          $truong->created_at = $currentdate;
          $truong->schools_update_userid = $currentuser_id;
          $truong->updated_at = $currentdate;
          $truong->save();

          $insertGetID = $truong->schools_id;

          if ($insertGetID > 0) {
            $insertHis = DB::table('qlhs_schools_history')
              ->insert([
                'type_his_school_id' => $insertGetID, 
                'type_his_type_id' => $school_type,
                'type_his_startdate' => $startDateformat,
                'type_his_createdate' => $currentdate
              ]);
          }

          $results['success'] = 'Thêm mới trường thành công!';
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function updateTruong(Request $request){
      $results = [];
      try{
        $cbxMCCTT = $request->input("MCCTT");
        $school_id = $request->input("SCHOOLID");
        $school_name = $request->input("SCHOOLNAME");
        $school_name = trim($school_name);
        $school_type = $request->input("SCHOOLTYPE");
        $startDate = $request->input("STARTDATE");
        $school_active = $request->input("SCHOOLACTIVE");
        $school_rewrite = $this->to_slug(trim($school_name));
        $school_md5 = md5($school_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now();
        $UnitSchool = $request->input("UnitSchool");//truong lien cap

        $updatetype = $request->input("UPDATETYPE");
        $schoolHisID = $request->input("HISID");

        //--------------------------Tính thời gian-----------------------------------
        $startDateformat = Carbon::parse($startDate);

        $year = substr($startDate, 6);
        $month = substr($startDate, 3, 2);
        $day = substr($startDate, 0, 2);

        if ($month == 1 && $year > 0) {
          $month = 12;
          $year = $year - 1;
        }
        else {
          $month = $month - 1;
          $year = $year;
        }

        $endDateFormat = Carbon::parse($day.'-'.$month.'-'.$year);

        //------------------------------Thay đổi loại trường thì update trạng thái tiền----------------------------
        $getSchoolType = DB::table('qlhs_schools_history')
          ->where('type_his_school_id', '=', DB::raw($school_id.' AND type_his_startdate <= "'.$startDateformat.'" AND (type_his_enddate >= "'.$startDateformat.'" OR type_his_enddate is null)'))
          ->select('type_his_type_id')
          ->get();

        if (!is_null($getSchoolType) && !empty($getSchoolType) && count($getSchoolType) > 0) {
          foreach ($getSchoolType as $value) {
            if ($value->type_his_type_id > 0 && $value->type_his_type_id != $school_type) {
              $updateKinhphi = DB::table('qlhs_kinhphidoituong')->where('idLoaiTruong', '=', $school_type)
                ->update([
                    'status' => 1
                  ]);
            }
          }
        }
          
        //----------------------------------------------------------------------------

        if ($updatetype == 0) {
          
          $getMaxIDHis = DB::table('qlhs_schools_history')
            ->where('type_his_school_id', $school_id)
            ->select(DB::raw('MAX(type_his_id) as type_his_id'))->first();

          if (!is_null($getMaxIDHis->type_his_id) && !empty($getMaxIDHis->type_his_id) && $getMaxIDHis->type_his_id > 0) {
            $getHisSchool = DB::table('qlhs_schools_history')
            ->where('type_his_id', $getMaxIDHis->type_his_id)
            ->where('type_his_startdate','>=', $startDateformat)->count();
            if ($getHisSchool == 0) {
                $updateTruong = DanhMucTruong::where('schools_id',$school_id)->first();
                $updateTruong->TramTauOrMCC =  $cbxMCCTT;
                $updateTruong->schools_name =  $school_name;
                $updateTruong->schools_active =  $school_active;
                $updateTruong->schools_rewrite =  $school_rewrite;
                $updateTruong->schools_md5 =  $school_md5;
                $updateTruong->schools_update_userid =  $currentuser_id;
                $updateTruong->LienCap =  $UnitSchool;
                $k = $updateTruong->save();

              $updateHis = DB::table('qlhs_schools_history')
                ->where('type_his_id', $getMaxIDHis->type_his_id)
                ->where('type_his_school_id',$school_id)
                ->update(['type_his_enddate' => $endDateFormat]);

              $insertHis = DB::table('qlhs_schools_history')
                      ->insert([
                        'type_his_school_id' => $school_id, 
                        'type_his_type_id' => $school_type,
                        'type_his_startdate' => $startDateformat,
                        'type_his_createdate' => $currentdate
                      ]);

              if ($k > 0) {
                $results['success'] = "Sửa trường thành công!";
              }
              else {
                $results['error'] = "Sửa trường thất bại!";
              }
            }
            else {
              $results['error'] = "Mời chọn lại ngày bắt đầu!";
            }
          }
          else {
            $insertHis = DB::table('qlhs_schools_history')
                    ->insert([
                      'type_his_school_id' => $school_id, 
                      'type_his_type_id' => $school_type,
                      'type_his_startdate' => $startDateformat,
                      'type_his_createdate' => $currentdate
                    ]);

            $results['success'] = "Sửa trường thành công!";
          }
        }
        else {
                $updateTruong = DanhMucTruong::where('schools_id',$school_id)->first();
                $updateTruong->TramTauOrMCC =  $cbxMCCTT;
                $updateTruong->schools_name =  $school_name;
                $updateTruong->LienCap =  $UnitSchool;
                $updateTruong->schools_active =  $school_active;
                $updateTruong->schools_rewrite =  $school_rewrite;
                $updateTruong->schools_md5 =  $school_md5;
                $updateTruong->schools_update_userid =  $currentuser_id;
                $updateTruong->save();    

          $updateHis = DB::table('qlhs_schools_history')
            ->where('type_his_id',$schoolHisID)
            ->where('type_his_school_id',$school_id)
            ->update(['type_his_type_id' => $school_type]);

            $results['success'] = "Thay đổi trường thành công!";
        }

        

        return $results;
      }catch(\Exception $e){
        return $e;
      }
    }

    public function deleteTruong(Request $request){
      $results = [];
      try{        
        $school_id = $request->input("SCHOOLID");
        $type_id = $request->input("SCHOOLHISID");
        
        $deleteHisTruong = DB::table('qlhs_schools_history')->where('type_his_id', '=', $type_id)->whereNull('type_his_enddate')->delete();

        if ($deleteHisTruong > 0) {
          //Check lịch sử của trường có ko
          $getSchoolHis = DB::table('qlhs_schools_history')->where('type_his_school_id', '=', $school_id)->get();
          if (count($getSchoolHis) > 0) {
            //Lấy id lịch sử lớn nhất của trường
            $getMaxIDHis = DB::table('qlhs_schools_history')->where('type_his_school_id', '=', $school_id)
              ->select(DB::raw('MAX(type_his_id) as type_his_id'))->first();
            if (!is_null($getMaxIDHis->type_his_id) && !empty($getMaxIDHis->type_his_id) && $getMaxIDHis->type_his_id > 0) {
              $updateHis = DB::table('qlhs_schools_history')->where('type_his_id', '=', $getMaxIDHis->type_his_id)
                ->where('type_his_school_id', '=', $school_id)
                ->update(['type_his_enddate' => null]);
            }
            $results['success'] = "Xóa lịch sử thành công!";
          }
          else {
            //Check trường có khối ko
            $getUnit = DB::table('qlhs_unit')->where('unit_school_id', '=', $school_id)->get();
            if (count($getUnit) > 0) {
              $results['error'] = "Yêu cầu xóa khối trước khi xóa trường!";
            }
            else {
              //Check trường có lớp học ko
              $getClassbySchoolID = DB::table('qlhs_class')->where('class_schools_id', '=', $school_id)->get();
              if (count($getClassbySchoolID) > 0) {
                $results['error'] = "Yêu cầu xóa hết lớp trước khi xóa trường!";
              }
              else {
                //Check trường có học sinh ko
                $getProfilebySchoolID = DB::table('qlhs_profile')->where('profile_school_id', '=', $school_id)->get();
                if (count($getProfilebySchoolID) > 0) {
                  $results['error'] = "Trường đang có học sinh nên không thể xóa!";
                }
                else {
                  $deleteTruong = DB::table('qlhs_schools')->where('schools_id', '=', $school_id)->delete();

                  if ($deleteHisTruong > 0) {
                    $results['success'] = "Xóa trường thành công!";
                  }
                  else {
                    $results['error'] = "Xóa trường thất bại!";
                  }
                }
              }
            }
          }
        }
        else {
          $results['error'] = "Xóa lịch sử thất bại!";
        }

        return $results;
      }catch(\Exception $e){
        return $e;
      }
    }

//Class-Lớp--------------------------------------------------------------------------------------------------

    public function listGetLop(){
      $classs = DB::table('qlhs_class')->orderBy('updated_at', 'desc')->paginate(10);

      $schools = DB::select('select schools_id, schools_name from qlhs_schools where schools_active = 1', array());

      $units = DB::select('select unit_id, unit_name from qlhs_unit where unit_active = 1', array());

      $levels = DB::select('select level_id, level_name from qlhs_level', array());

      return view('admin.danhmuc.lop.listing', compact('classs', 'schools', 'units', 'levels'));
    }

    public function loadLop(Request $req){
      try {
        $json = [];
        $start = $req->input('start');
        $limit = $req->input('limit');
        $keysearch = $req->input('key');
        $load_class = null;

        if ($keysearch != null && $keysearch != "") {
          $load_class = DB::table('qlhs_class')
            ->leftJoin('qlhs_schools','qlhs_class.class_schools_id' ,'qlhs_schools.schools_id')
            ->leftJoin('qlhs_unit','qlhs_unit.unit_id' ,'qlhs_class.class_unit_id')
            ->leftJoin('qlhs_level','qlhs_class.class_level_id' ,'qlhs_level.level_id')
            ->leftJoin('warehouses','qlhs_class.warehouse_id' ,'warehouses.id')
            ->where(function($q) use($keysearch){
              $q->orWhere("schools_name", "LIKE","%".$keysearch."%")
              ->orWhere("unit_name", "LIKE", "%".$keysearch."%")
              ->orWhere("class_name", "LIKE", "%".$keysearch."%")
              ->orWhere("level_name", "LIKE", "%".$keysearch."%");
            });
            
        }
        else {
          $load_class = DB::table('qlhs_class')
            ->leftJoin('qlhs_schools','qlhs_class.class_schools_id' , 'qlhs_schools.schools_id')
            ->leftJoin('qlhs_unit','qlhs_unit.unit_id' ,'qlhs_class.class_unit_id')
            ->leftJoin('qlhs_level','qlhs_class.class_level_id' ,'qlhs_level.level_id')
            ->leftJoin('warehouses','qlhs_class.warehouse_id' ,'warehouses.id');
        }

        if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
              $load_class->whereIn('class_schools_id',explode('-',Auth::user()->truong_id));
        }
        
        $json['startRecord'] = $start;
        $json['numRows'] = $limit;
        $json['totalRows'] = $load_class->count();
        $json['data'] = $load_class->orderBy('qlhs_class.updated_at', 'desc')
        ->skip($start*$limit)->take($limit)->get();
        return $json;
      } catch (Exception $e) {
        return $e;
      }
    }

    public function exportExcelClass()
    {
      $classs = DanhMucLop::select('class_code', 'class_name', 'class_active', 'class_create_userid', 'created_at', 'class_update_userid', 'updated_at')->get();
      
      $classArray = []; 

      $classArray[] = ['Mã trường', 'Tên trường', 'Trạng thái', 'Người tạo', 'Ngày tạo', 'Người sửa', 'Ngày sửa'];

      foreach ($classs as $class) {
        if ($class->{'class_active'} == 1) {
          $class->{'class_active'} = "Đang hoạt động";
        }
        else if ($class->{'class_active'} == 0) {
          $class->{'class_active'} = "Không hoạt động";
        }
        $classArray[] = $class->toArray();
      }
      //return $schoolArray;
      Excel::create('Danh mục lớp', function($excel) use ($classArray) {

          $excel->setTitle('Danh mục lớp');
          $excel->setCreator('Laravel')->setCompany('VUNK, LLC');
          $excel->setDescription('Danh mục lớp');

          $excel->sheet('lớp', function($sheet) use ($classArray) {
              $sheet->fromArray($classArray, null, 'A1', false, false);
          });

      })->export('xlsx');
    }

    public function insertLop(Request $request){
      $results = [];
      try{
        $school_id = $request->input("SCHOOLID");
        $level_id = $request->input("LEVELID");
        $unit_id = $request->input("UNITID");
        $class_name = $request->input("CLASSNAME");
        $class_name = trim($class_name);
        $class_active = $request->input("CLASSACTIVE");
        $warehouse_id = $request->input("DIEMTRUONG");
        $class_rewrite = $this->to_slug(trim($class_name));
        $class_md5 = md5($class_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now();

          $truong = new DanhMucLop();
          $truong->class_schools_id = $school_id;
          $truong->class_level_id = $level_id;
          $truong->class_unit_id = $unit_id;
          $truong->class_name = $class_name;
          $truong->class_active = $class_active;
          $truong->class_rewrite = $class_rewrite;
          $truong->class_md5 = $class_md5;
          $truong->class_create_userid = $currentuser_id;
          $truong->created_at = $currentdate;
          $truong->class_update_userid = $currentuser_id;
          $truong->updated_at = $currentdate;
          $truong->warehouse_id = $warehouse_id;
          $truong->save();

          $results['success'] = "Thêm mới lớp thành công!";

        
        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function updateLop(Request $request){
      $results = [];
      try{
        $school_id = $request->input("SCHOOLID");
        $unit_id = $request->input("UNITID");
        $level_id = $request->input("LEVELID");
        $class_id = $request->input("CLASSID");
        $class_name = $request->input("CLASSNAME");
        $class_name = trim($class_name);
        $class_active = $request->input("CLASSACTIVE");
        $warehouse_id = $request->input("DIEMTRUONG");
        $class_rewrite = $this->to_slug(trim($class_name));
        $class_md5 = md5($class_rewrite);

        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        // $updateLop = DB::update("update qlhs_class set class_schools_id = '$school_id', class_level_id = '$level_id', class_name = '$class_name', class_active = '$class_active', class_rewrite = '$class_rewrite', class_md5 = '$class_md5', class_update_userid = '$currentuser_id', updated_at = '$currentdate' where class_id = '$class_id'");

        $updateLop = DB::table('qlhs_class')
          ->where('class_id',$class_id)
          ->update([
            'class_schools_id' => $school_id,
            'class_level_id' => $level_id,
            'class_name' => $class_name,
            'class_active' => $class_active,
            'class_rewrite' => $class_rewrite,
            'class_md5' => $class_md5,
            'class_update_userid' => $currentuser_id,
            'updated_at' => $currentdate,
            'warehouse_id' => $warehouse_id,
            'class_unit_id' => $unit_id
          ]);

        if ($updateLop > 0) {
          $results['success'] = "Sửa lớp thành công!";
        }
        else {$results['error'] = "Sửa lớp thất bại!";}

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function deleteLop(Request $request){
      $results = [];
      try{        
        $class_id = $request->input("CLASSID");

        $getProfilebyClassID = DB::table('qlhs_profile')->where('profile_class_id', '=', $class_id)->get();
        if (count($getProfilebyClassID) > 0) {
          $results['error'] = "Không thể xóa lớp vì có học sinh đang học!";
        }
        else {
          $deleteLop = DB::table('qlhs_class')->where('class_id', '=', $class_id)->delete();

          if ($deleteLop > 0) {
            $results['success'] = "Xóa lớp thành công!";
          }
          else{
            $results['error'] = "Xóa lớp thất bại";
          }
        }
        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

//Department-Phòng ban---------------------------------------------------------------------------------------

    public function listGetPhongBan(){
      $departments = DB::table('qlhs_department')->get();
      return view('admin.danhmuc.phongban.listing')->with('departments', $departments);
    }

    public function insertPhongBan(Request $request){
      $results = [];
      try{
        $department_parent_id = $request->input("DEPARTMENTPARENTID") ? $request->input("DEPARTMENTPARENTID") : 0;
        // $department_code = $request->input("DEPARTMENTCODE");
        // $department_code = trim($department_code);
        $department_name = $request->input("DEPARTMENTNAME");
        $department_name = trim($department_name);
        // $department_function = $request->input("department_function");
        // $department_manager = $request->input("department_manager");
        // $department_deputy = $request->input("department_deputy");
        $department_level = $request->input("DEPARTMENTLEVEL") ? $request->input("DEPARTMENTLEVEL") : 1;
        if ($department_level > 1) {
          $department_level = $department_level + 1;
        }
        $department_active = $request->input("DEPARTMENTACTIVE");
        $department_rewrite = $this->to_slug(trim($department_name));
        $department_md5 = md5($department_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $getbyDepartment_Code = DB::table('qlhs_department')->where('department_name', '=', $department_name)->get();

        if (count($getbyDepartment_Code) > 0) {
            $results['error'] = 'Tên phòng ban đã tồn tại, vui lòng nhập tên khác!';
        }
        else{
          $phongban = new DanhMucPhongBan();
          // $phongban->department_code = $department_code;
          $phongban->department_name = $department_name;
          // $phongban->department_function = $department_function;
          // $phongban->department_manager = $department_manager;
          // $phongban->department_deputy = $department_deputy;
          $phongban->department_parent_id = $department_parent_id;
          $phongban->department_level = $department_level;
          $phongban->department_active = $department_active;
          $phongban->department_rewrite = $department_rewrite;
          $phongban->department_md5 = $department_md5;
          $phongban->department_create_userid = $currentuser_id;
          $phongban->created_at = $currentdate;
          $phongban->department_update_userid = $currentuser_id;
          $phongban->updated_at = $currentdate;

          $phongban->save();

          $results['success'] = 'Thêm mới thành công!';
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function updatePhongBan(Request $request){
      $results = [];
      try{
        $department_parent_id = $request->input("DEPARTMENTPARENTID") ? $request->input("DEPARTMENTPARENTID") : 0;
        $department_id = $request->input("DEPARTMENTID");
        $department_name = $request->input("DEPARTMENTNAME");
        $department_name = trim($department_name);
        // $department_function = $request->input("department_function");
        // $department_manager = $request->input("department_manager");
        // $department_deputy = $request->input("department_deputy");
        $department_level = $request->input("DEPARTMENTLEVEL");
        if ($department_level != 0) {
          $department_level = $department_level + 1;
        }
        $department_active = $request->input("DEPARTMENTACTIVE");
        $department_rewrite = $this->to_slug(trim($department_name));
        $department_md5 = md5($department_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $updatePhongBan = DB::update("update qlhs_department set department_name = '$department_name', department_parent_id = '$department_parent_id', department_active = '$department_active', department_rewrite = '$department_rewrite', department_md5 = '$department_md5', department_level = '$department_level', department_update_userid = '$currentuser_id', updated_at = '$currentdate' where department_id = '$department_id'");        

        if ($updatePhongBan > 0) {
          $results['success'] = "Sửa thành công!";
        }
        else {$results['error'] = "Sửa thất bại!";}
        return $results;
      }catch(\Exception $e){
        return $e;
      }
    }

    public function deletePhongBan(Request $request){
      $results = [];
      try{        
        $department_id = $request->input("DEPARTMENTID");

        $getbyDepart_Id = DB::table('qlhs_department')->where('department_parent_id', '=', $department_id)->where('department_active', '=', 1)->get();

        if (count($getbyDepart_Id) > 0) {                
          $results['error'] = "Hiện đang có cấp nhỏ hơn hoạt động, không thể xóa!";
        }else{
          $deletePhongBan = DB::table('qlhs_department')->where('department_id', '=', $department_id)->delete();

          if ($deletePhongBan > 0) {
            $results['success'] = "Xóa thành công!";
          }
          else {$results['error'] = "Xóa thất bại!";}
        }

        return $results;
      }catch(\Exception $e){
        return $e;
      }
    }

//Site-Tỉnh/Thành-Quận/Huyện-Phường/Xã-----------------------------------------------------------------------

    public function listGetXaPhuong(){
      //$sites = DB::table('qlhs_site')->orderBy('site_id', 'desc')->get();
      return view('admin.danhmuc.xaphuong.listing');//->with('sites', $sites);
    }

    public function insertXaPhuong(Request $request){
      $results = [];
      try{
        $site_name = $request->input("SITENAME");
        $site_name = trim($site_name);
        $site_parent_id = $request->input("SITEPARENTID") ? $request->input("SITEPARENTID") : 0;
        $site_level = $request->input("SITELEVEL");
        $site_type = $request->input("SITETYPE");
        $site_active = $request->input("SITEACTIVE");
        $site_rewrite = $this->to_slug($site_name);
        $site_md5 = md5($site_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        $strType = '';

        if (!is_null($site_type) && !empty($site_type) && count($site_type) > 0) {
          foreach ($site_type as $value) {
            $strType .= $value . '-';
          }
        }

        $getbySite_Name = DB::table('qlhs_site')
        ->where('site_name',$site_name)
        ->where('site_parent_id',$site_parent_id)
        ->get();

        if (count($getbySite_Name) > 0) {
            $results['error'] = 'Tên địa phương đã tồn tại, vui lòng nhập tên khác!';
        }
        else{
          $xaphuong = new DanhMucXaPhuong();
          // $xaphuong->site_code = $site_code;
          $xaphuong->site_name = $site_name;
          $xaphuong->site_parent_id = $site_parent_id;
          $xaphuong->site_active = $site_active;
          $xaphuong->site_level = $site_level;
          $xaphuong->site_type = $strType;
          $xaphuong->site_rewrite = $site_rewrite;
          $xaphuong->site_md5 = $site_md5;
          $xaphuong->site_create_userid = $currentuser_id;
          $xaphuong->created_at = $currentdate;
          $xaphuong->site_update_userid = $currentuser_id;
          $xaphuong->updated_at = $currentdate;

          $xaphuong->save();

          $results['success'] = 'Thêm mới thành công!';
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function updateXaPhuong(Request $request){
      $results = [];
      try{
        $site_id = $request->input("SITEID");
        $site_name = $request->input("SITENAME");
        $site_name = trim($site_name);
        $site_parent_id = $request->input("SITEPARENTID") ? $request->input("SITEPARENTID") : 0;
        $site_level = $request->input("SITELEVEL");
        $site_type = $request->input("SITETYPE");
        $site_active = $request->input("SITEACTIVE");
        $site_rewrite = $this->to_slug($site_name);
        $site_md5 = md5($site_rewrite);
        $currentuser_id = Auth::user()->id;
        $currentdate = Carbon::now('Asia/Ho_Chi_Minh');

        // $updateXaPhuong = DB::update("update qlhs_site set site_name = '$site_name', site_parent_id = '$site_parent_id', site_level = '$site_level', site_active = '$site_active', site_rewrite = '$site_rewrite', site_md5 = '$site_md5', site_update_userid = '$currentuser_id', updated_at = '$currentdate' where site_id = '$site_id'");

        $strType = '';

        if (!is_null($site_type) && !empty($site_type) && count($site_type) > 0) {
          foreach ($site_type as $value) {
            $strType .= $value . '-';
          }
        }

        $updateXaPhuong = DB::table('qlhs_site')->where('site_id', $site_id)
          ->update([
            'site_name' => $site_name,
            'site_parent_id' => $site_parent_id,
            'site_level' => $site_level,
            'site_active' => $site_active,
            'site_update_userid' => $currentuser_id,
            'updated_at' => $currentdate,
            'site_type' => $strType
          ]);

        if ($updateXaPhuong > 0) {
          $results['success'] = "Sửa thành công!";
        }
        else {$results['error'] = "Sửa thất bại!";}

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

    public function deleteXaPhuong(Request $request){
      $results = [];
      try{        
        $site_id = $request->input("SITEID");

        $getbySite_Id = DB::table('qlhs_site')->where('site_parent_id', '=', $site_id)->where('site_active', '=', 1)->get();

        if (count($getbySite_Id) > 0) {                
          $results['error'] = "Hiện đang có cấp nhỏ hơn hoạt động, không thể xóa!";
        }else{
          $deleteXaPhuong = DB::table('qlhs_site')->where('site_id', '=', $site_id)->delete();

          if ($deleteXaPhuong > 0) {
            $results['success'] = "Xóa thành công!";
          }
          else {$results['error'] = "Xóa thất bại!";}
        }

        return $results;
      }catch(\Exception $e){
        $results['error'] = $e;
        return $results;
      }
    }

//Helper-----------------------------------------------------------------------------------------------------------------------------------------------

  // public function listDoituongByNameAndId(){
  //   $subjects = DB::table('qlhs_subject')->get();
  //   return $subjects;
  // }

//Wards-Controller-------------------------------------------------------------------------------------------
  public function getWardbyID(Request $request)
  {
    try {
      $ward_id = $request->input("WARDID");
      $objWard = null;
      if ($ward_id > 0) {
        $objWard = DB::table('qlhs_wards')->where('wards_id', '=', $ward_id)->select('wards_id', 'wards_code', 'wards_name', 'wards_level', 'wards_parent_id', 'wards_active')->get();
      }
      return $objWard;
    } catch (Exception $e) {
      return $e;
    }
  }

  public function loadcomboWard(){
    $wards = DB::table('qlhs_wards')->select('wards_id', 'wards_name', 'wards_level','wards_parent_id')->get();
    return $wards;
  }

//Unit-Controller--------------------------------------------------------------------------------------------
  public function getUnitbyID(Request $request)
  {
    try {
      $unit_id = $request->input("UNITID");
      $objUnit = null;
      if ($unit_id > 0) {
        // $objUnit = DB::select('select * from qlhs_unit where unit_id = ?', array($unit_id));//->where('unit_id', '=', $unit_id)->get();
        $objUnit = DB::table('qlhs_unit')->leftJoin('qlhs_schools', 'unit_school_id', '=', 'schools_id')->where('unit_id', '=', $unit_id)->get();
      }
      return $objUnit;
    } catch (Exception $e) {
      return $e;
    }
  }

//Group-Controller-------------------------------------------------------------------------------------------
  public function getGroupbyID(Request $request)
  {
    try {
      $group_id = $request->input("GROUPID");
      $objGroup = null;
      if ($group_id > 0) {
        $objGroup = DB::select('select * from qlhs_group where group_id = ?', array($group_id));//->where('group_id', '=', $group_id);
      }
      return $objGroup;
    } catch (Exception $e) {
      return $e;
    }
  }

//Nationals-Controller---------------------------------------------------------------------------------------
  public function getNationalbyID(Request $request)
  {
    try {
      $nationals_id = $request->input("NATIONID");
      $objNational = null;
      if ($nationals_id > 0) {
        $objNational = DB::table('qlhs_nationals')->where('nationals_id', '=', $nationals_id)->get();
      }
      return $objNational;
    } catch (Exception $e) {
      return $e;
    }
  }

//Department-Controller--------------------------------------------------------------------------------------
  public function getDepartmentbyID(Request $request)
  {
    try {
      $department_id = $request->input("DEPARTMENTID");
      $objDepartment = null;
      if ($department_id > 0) {
        $objDepartment = DB::table('qlhs_department')->where('department_id', '=', $department_id)->get();
      }
      return $objDepartment;
    } catch (Exception $e) {
      return $e;
    }
  }

  public function loadcomboDepartment(){
    $departments = DB::table('qlhs_department')->select('department_id', 'department_name', 'department_level')->get();
    return $departments;
  }

//Class Controller-------------------------------------------------------------------------------------------
  public function getClassbyID(Request $request)
  {
    try {
      $class_id = $request->input("CLASSID");
      $objClass = null;
      if ($class_id > 0) {
        $objClass = DB::select('select * from qlhs_class where class_id = ?', array($class_id));
      }
      return $objClass;
    } catch (Exception $e) {
      return $e;
    }
  }

  public function getSchoolbyClassID(Request $request)
  {
    try {
      $class_id = $request->input("CLASSID");
      $arrSchools = array();
      if ($class_id > 0) {
        $arrSchools = DB::select("select schools_id, schools_name from qlhs_schools where schools_id = (select class_schools_id from qlhs_class where class_id = ?)", array($class_id));
      }
      return $arrSchools;
    } catch (Exception $e) {
      return $e;
    }
  }

  public function getUnit(Request $request)
  {
    try {
      // $school_id = $request->input("SCHOOLID");
      // $arrUnit = array();
      // if ($school_id > 0) {
        
        $arrUnit = DB::table('qlhs_unit')->get();
      // }
      return $arrUnit;
    } catch (Exception $e) {
      return $e;
    }
  }

  public function getLevelbyUnitID(Request $request)
  {
    try {
      $unit_id = $request->input("UNITID");
      $arrLevel = array();
      if ($unit_id > 0) {
        
        $arrLevel = DB::table('qlhs_level')->where('level_unit_id', '=', $unit_id)->get();
      }
      return $arrLevel;
    } catch (Exception $e) {
      return $e;
    }
  }

//School Controller------------------------------------------------------------------------------------------
  public function getSchoolbyID(Request $request)
  {
    try {
      $schools_id = $request->input("SCHOOLID") ? $request->input("SCHOOLID") : 0;
      $his_id = $request->input("SCHOOLHISID") ? $request->input("SCHOOLHISID") : 0;
      $objSchool = null;

      if ($his_id > 0) {
        $objSchool = DB::table('qlhs_schools')
          ->leftJoin('qlhs_schools_history', 'type_his_school_id','schools_id')
          ->where('type_his_id', '=', $his_id)->get();
      }
      else if ($schools_id > 0) {
        $objSchool = DB::table('qlhs_schools')->where('schools_id', $schools_id)->get();
      }

      return $objSchool;
    } catch (Exception $e) {
      return $e;
    }
  }

  public function getUnitbySchoolID(Request $request)
  {
    try {
      $school_id = $request->input("school_id");
      $arrUnit = array();
      if ($school_id > 0) {
        $arrUnit = DB::select("select unit_id, unit_name from qlhs_unit where unit_id = (select schools_unit_id from qlhs_schools where schools_id = ?)", array($school_id));
      }
      return $arrUnit;
    } catch (Exception $e) {
      return $e;
    }
  }

//Site Controller--------------------------------------------------------------------------------------------
  public function getSitebyID(Request $request)
  {
    try {
      $site_id = $request->input("SITEID");
      $objSite = null;
      if ($site_id > 0) {
        $objSite = DB::table('qlhs_site')->where('site_id', '=', $site_id)->select('site_id', 'site_code', 'site_name', 'site_level', 'site_type', 'site_parent_id', 'site_active')->get();
      }
      return $objSite;
    } catch (Exception $e) {
      return $e;
    }
  }

  public function getSitebyLevel(Request $request)
  {
    try {
      $arrSite = [];
      $site_level = $request->input("SITELEVEL") - 1;
      // $arrSite = array();
      if ($site_level > 0) {
        $arrSite['LEVEL1'] = DB::table('qlhs_site')->where('site_level', '=', $site_level)->select('site_id', 'site_parent_id', 'site_name')->get();
        $arrSite['LEVEL2'] = DB::table('qlhs_site')->where('site_level', '=', $site_level - 1)->select('site_id', 'site_parent_id', 'site_name')->get();
      }
      return $arrSite;
    } catch (Exception $e) {
      return $e;
    }
  }

  public function loadcomboxaphuong($level){
    if($level != null && $level != 0){
      $sites = DB::table('qlhs_site')->where('site_level',$level)->select('site_id','site_name')->get();
    }else{
      $sites = DB::table('qlhs_site')->select('site_id','site_name')->get();
    }
    
    return $sites;
  }

//Subject Controller-----------------------------------------------------------------------------------------
  public function getSubjectbyID(Request $request)
  {
    try {
      $subject_id = $request->input("SUBJECTID");
      $objSubject = [];
      if ($subject_id > 0) {
        $objSubject['objSubject'] = DB::select('select * from qlhs_subject where subject_id = ?', array($subject_id));
        $objSubject['arrGroupID'] = DB::select('select * from qlhs_subject_history where subject_history_subject_id = ?', array($subject_id));
      }
      return $objSubject;
    } catch (Exception $e) {
      return $e;
    }
  }

  public function getListGroupIDbySubID(Request $request)
  {
    try {
      $subject_id = $request->input("SUBJECTID");
      $arrGroupID = array();
      if ($subject_id > 0) {
        $arrGroupID = DB::select('select * from qlhs_subject_history where subject_history_subject_id = ?', array($subject_id));//DB::table('qlhs_subject_history')->where('subject_history_subject_id', '=', $subject_id)->get();
      }
      return $arrGroupID;
    } catch (Exception $e) {
      return $e;
    }
  }

//Create ReWrite function
  public function to_slug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/', 'A', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/', 'E', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(Ì|Í|Ị|Ỉ|Ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/', 'O', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/', 'U', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/', 'Y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/(Đ)/', 'D', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
    public function listDoituongByNameAndId(Request $rq){

      $qlhs_subject = DB::table('qlhs_subject');//->where('subject_active',1);
      if($rq->type != null || $rq->type != ""){
          $qlhs_subject = $qlhs_subject->orWhere('subject_id',0)->orderBy('subject_id','desc');
      }
      return $qlhs_subject->select('subject_id','subject_name')->get();
    }
    public function listDoituongById($id){
      $qlhs_subject = DB::table('qlhs_subject')->where('subject_active','=',1)->where('subject_id','=',$id)->select('subject_id','subject_name')->get();
      return $qlhs_subject;
    }
    public function listNhomDoituongByNameAndId(){
      $qlhs_group = DB::table('qlhs_group')->where('group_active','=',1)->select('group_id','group_name')->get();
      return $qlhs_group;
    }
    public function listTruongHocByNameAndId(){
      $json = [];

      if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
         $qlhs_schools = DB::table('qlhs_schools')->where('schools_active','=',1)->whereIn('schools_id',explode('-',Auth::user()->truong_id))->select('schools_id','schools_name','schools_unit_id')->get();
        $qlhs_unit = DB::table('qlhs_unit')->leftJoin('qlhs_schools','unit_id','=','schools_unit_id')->where('schools_id','=',Auth::user()->truong_id)->where('unit_active','=',1)->select('unit_id','unit_name')->get();
        $json['khoi'] = $qlhs_unit;
        $json['truong'] = $qlhs_schools;
      }else{
        $qlhs_schools = DB::table('qlhs_schools')->leftJoin('qlhs_schools_history','schools_id','type_his_school_id')->where('schools_active','=',1)->where('type_his_startdate','<=',DB::raw('NOW()'))->where(function($q){
          $q->orWhere('type_his_enddate','>=',DB::raw('NOW()'))->orWhere('type_his_enddate',null);
        })->select('qlhs_schools.schools_id','qlhs_schools.schools_name','qlhs_schools.schools_unit_id','qlhs_schools_history.type_his_type_id')->get();

        $json['truong'] = $qlhs_schools;
       }
       
      return $json;
    }
    public function listLopByNameAndId($id){
      $qlhs_class = DB::table('qlhs_class')
      ->leftJoin('qlhs_level','level_id','class_level_id')
      ->leftJoin('qlhs_schools_history','type_his_school_id','class_schools_id')
      ->where('class_active',1)
      ->where('class_schools_id',$id)->select('class_id','class_name','level_level','type_his_type_id')
      ->orderBy('level_level')->orderBy('class_name')->get();
      return $qlhs_class;
    }
    public function listCityByNameAndId($id){
      $qlhs_site = DB::table('qlhs_site')
      ->where('site_active',1)
      ->where('site_parent_id',$id)
      ->select('site_id','site_name')->get();
      return $qlhs_site;
    }
    public function listCityByNameAndIds(Request $rq){

      $qlhs_site = DB::table('qlhs_site')->where('site_active','=',1)->where('site_parent_id','=',$rq->id)->where('site_level',$rq->type);
      if($rq->searchText != null && $rq->searchText != '' ){
          $qlhs_site = $qlhs_site->whereRaw('LOWER(site_name) like "%'.Str::lower($rq->searchText).'%"');

      }
      return $qlhs_site->select('site_id','site_name')->get();
    }
    public function listNamHoc(){
      $qlhs_years = DB::table('qlhs_years')->select('code','name')->get();
      return $qlhs_years;
    }
    public function listDanTocByNameAndId(){
      $qlhs_nationals = DB::table('qlhs_nationals')->where('nationals_active',1)
      ->select('nationals_id','nationals_name')->get();
      return $qlhs_nationals;
    }
    public function getChuHo(Request $rq){
        return DB::table('qlhs_danhsachhongheo')->leftJoin('qlhs_site as xa','xa.site_id','qlhs_danhsachhongheo.DShongheo_site_idxa')->leftJoin('qlhs_site as thon','thon.site_id','qlhs_danhsachhongheo.DShongheo_site_idthon')->leftJoin('qlhs_nationals as dantoc','dantoc.nationals_id','qlhs_danhsachhongheo.DShongheo_nation_id')->where('DShongheo_name', "LIKE", "%".$rq->name."%")->select('DShongheo_name',DB::raw('CONCAT(thon.site_name,"-",xa.site_name) as DShongheo_typename'),'DShongheo_birthday')->orderBy('DShongheo_name')->take(100)->get();

    }
    public function loadClassByClass($school,$value){
      $type = explode('-',$value)[0];
      $class_id = explode('-',$value)[1];
      $result = null;
      // type : 1 - lên lớp , 2 : học lại
      // lấy level của lớp hiện tại
      $getlevel = DB::table('qlhs_class')
      ->leftJoin('qlhs_level','class_level_id','level_id')
      ->where('class_schools_id',$school)
      ->where('class_id',$class_id)
      ->select('level_level','class_unit_id')->first();
      // kiểm tra trường liên cấp
      $LienCap = null;
      $getLienCap = DB::table('qlhs_schools')
      ->where('schools_id',$school)->select('LienCap')->first();
      if($getLienCap != null && $getLienCap->LienCap != null){
          $LienCap = explode('-', $getLienCap->LienCap);
      }
      $result = DB::table('qlhs_class')
                ->leftJoin('qlhs_level','class_level_id','level_id')
                ->where('class_schools_id',$school);
      if($type == 1){
          $result = $result->where('level_level',$getlevel->level_level+1);
      }else{
          $result = $result->where('class_unit_id',$getlevel->class_unit_id)
          ->where('level_level',$getlevel->level_level);
      }
      if($LienCap != null && count($LienCap) > 0){
          $result = $result->whereIn('class_unit_id',$LienCap);
      }
      return $result->orderBy('level_level')
                ->select('class_id','class_name')->get();
    }
}
