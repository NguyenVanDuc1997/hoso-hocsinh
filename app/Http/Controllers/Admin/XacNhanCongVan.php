<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception,Blueprint;
use Excel,datetime;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Models\qlhs_message;
use App\Models\HoSoBaoCao;
use App\Models\TongHopCheDo_Truong;
use App\Jobs\CapNhatCheDo;
use App\Helpers\tongHopChedo;
use App\Helpers\lapcongvanCapPhong;
use App\Helpers\bussinessClass;
use App\Helpers\lapcongvanCapTruong;

class XacNhanCongVan extends Controller
{
	// Load Data Sử dụng lập công văn
    public function loadCongVanLap(Request $request){
        try {
            $json = [];
            $start = $request->start;
            $limit = $request->limit;
            $type = $request->TYPE;
            $order = $request->ORDER;

            $schools_id = $request->SCHOOLID;
            $class_type = $request->CLASS_TYPE;
            $namhoc = explode('-',$request->YEAR);
            $year = $namhoc[1];
            $hocky = $namhoc[0];
            $keySearch = $request->KEY;
            $status = $request->STATUS;
            $getMoney = TongHopCheDo_Truong::leftJoin('qlhs_profile','profile_id','thcd_nhucau_profile_id')
                ->leftJoin('qlhs_schools','schools_id','profile_school_id')
                ->leftJoin('qlhs_class','class_id','profile_class_id')
                ->leftJoin('qlhs_unit','unit_id','class_unit_id')
                ->where('thcd_nhucau_school_id',Auth::user()->truong_id)
                ->where('thcd_nhucau_trangthai_'.$hocky,1)
                ->where('thcd_nhucau_nam',$year);
            if ($hocky != "CA") {
                $getMoney = $getMoney->select('thcd_nhucau_id', 
                    'thcd_nhucau_MGHP_'.$hocky.' as MGHP',
                    'thcd_nhucau_trangthai_MGHP_'.$hocky.' as ttMGHP',
                    'thcd_nhucau_CPHT_'.$hocky.' as CPHT',
                    'thcd_nhucau_trangthai_CPHT_'.$hocky.' as ttCPHT',
                    'thcd_nhucau_HTAT_'.$hocky.' as HTAT',
                    'thcd_nhucau_trangthai_HTAT_'.$hocky.' as ttHTAT',
                    'thcd_nhucau_HTBT_TA_'.$hocky.' as HTBT_TA',
                    'thcd_nhucau_trangthai_HTBT_TA_'.$hocky.' as ttHTBT_TA',
                    'thcd_nhucau_HTBT_TO_'.$hocky.' as HTBT_TO',
                    'thcd_nhucau_trangthai_HTBT_TO_'.$hocky.' as ttHTBT_T0',
                    'thcd_nhucau_HTBT_VHTT_'.$hocky.' as HTBT_VHTT',
                    'thcd_nhucau_trangthai_HTBT_VHTT_'.$hocky.' as ttHTBT_VHTT',
                    'thcd_nhucau_HSKT_HB_'.$hocky.' as HSKT_HB',
                    'thcd_nhucau_trangthai_HSKT_HB_'.$hocky.' as ttHSKT_HB',
                    'thcd_nhucau_HSKT_DDHT_'.$hocky.' as HSKT_DDHT',
                    'thcd_nhucau_trangthai_HSKT_DDHT_'.$hocky.' as ttHSKT_DDHT',
                    'thcd_nhucau_HSDTTS_'.$hocky.' as HSDTTS',
                    'thcd_nhucau_trangthai_HSDTTS_'.$hocky.' as ttHSDTTS',
                    'thcd_nhucau_HTATHS_'.$hocky.' as HTATHS',
                    'thcd_nhucau_trangthai_HTATHS_'.$hocky.' as ttHTATHS',
                    'thcd_nhucau_HBHSDTNT_'.$hocky.' as HBHSDTNT',
                    'thcd_nhucau_trangthai_HBHSDTNT_'.$hocky.' as ttHBHSDTNT',

                    'thcd_nhucau_tongtien_'.$hocky.' as TONGTIEN', 
                    'thcd_nhucau_trangthai_'.$hocky.' as TRANGTHAI', 
                    'trangthai_'.$hocky.' as TRANGTHAIPHEDUYET', 
                    'profile_id', 
                    'profile_name', 
                    'profile_birthday', 
                    'schools_name', 
                    'class_name', 
                    'unit_name',
                    'qlhs_thcd_ghichu_'.$hocky.' as GHICHU');
                if($status != null && $status != ""){
                    if($status == 0 || $status == 1){
                       // $getMoney = $getMoney->where('update_profile',$status);   
                        $getMoney = $getMoney->where('trangthai_'.$hocky,$status); 
                    }else if($status == 2){
                        $getMoney = $getMoney->where('trangthai_'.$hocky,0);
                    }else if($status == 3){
                        $getMoney = $getMoney->where('trangthai_'.$hocky,1);
                    }
                }
            }
            if($class_type != null && $class_type != ""){
                $getMoney = $getMoney->where('class_unit_id',$class_type);
            }
            if($keySearch != null && $keySearch != ""){
                $kw = bussinessClass::to_slug($keySearch);
                $getMoney = $getMoney->where(function($q) use($keySearch,$kw){
                    $q->orWhere('qlhs_profile.profile_name','LIKE','%'.$keySearch.'%')
                    ->orWhere("qlhs_profile.profile_rewrite", "LIKE", "%".$kw."%");
                });                
            }
            
            
            $json['totalRows'] =  $getMoney->count();
            $json['startRecord'] = ($start);
            $json['numRows'] = $limit;
            $or = 'asc';
            if($order == 1){
                $or = 'desc';
            }
            if ($hocky != "CA") {
                if($type == 1){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_MGHP_'.$hocky,$or);
                }else if($type == 2){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_CPHT_'.$hocky,$or);
                }else if($type == 3){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTAT_'.$hocky,$or);
                }else if($type == 4){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTBT_TA_'.$hocky,$or);
                }else if($type == 5){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTBT_TO_'.$hocky,$or);
                }else if($type == 6){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTBT_VHTT_'.$hocky,$or);
                }else if($type == 7){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTATHS_'.$hocky,$or);
                }else if($type == 8){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HSKT_HB_'.$hocky,$or);
                }else if($type == 9){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HSKT_DDHT_'.$hocky,$or);
                }else if($type == 10){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HBHSDTNT_'.$hocky,$or);
                }else if($type == 11){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HSDTTS_'.$hocky,$or);
                }else if($type == 12){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_tongtien_'.$hocky,$or);
                }else if($type == 13){
                    $getMoney = $getMoney->orderBy('class_name',$or);
                }else if($type == 14){
                    $getMoney = $getMoney->orderBy(DB::raw("substring_index(TRIM(profile_name), ' ', -1) "),$or);
                }
            }else{
                if($type == 1){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_MGHP_HK1',$or)
                    ->orderBy('thcd_nhucau_MGHP_HK2',$or);
                }else if($type == 2){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_CPHT_HK1',$or)
                    ->orderBy('thcd_nhucau_CPHT_HK2',$or);
                }else if($type == 3){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTAT_HK1',$or)
                    ->orderBy('thcd_nhucau_HTAT_HK2',$or);
                }else if($type == 4){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTBT_TA_HK1',$or)
                    ->orderBy('thcd_nhucau_HTBT_TA_HK2',$or);
                }else if($type == 5){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTBT_TO_HK1',$or)
                    ->orderBy('thcd_nhucau_HTBT_TO_HK2',$or);
                }else if($type == 6){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTBT_VHTT_HK1',$or)
                    ->orderBy('thcd_nhucau_HTBT_VHTT_HK2',$or);
                }else if($type == 7){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HTATHS_HK1',$or)
                    ->orderBy('thcd_nhucau_HTATHS_HK2',$or);
                }else if($type == 8){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HSKT_HB_HK1',$or)
                    ->orderBy('thcd_nhucau_HSKT_HB_HK2',$or);
                }else if($type == 9){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HSKT_DDHT_HK1',$or)
                    ->orderBy('thcd_nhucau_HSKT_DDHT_HK2',$or);
                }else if($type == 10){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HBHSDTNT_HK1',$or)
                    ->orderBy('thcd_nhucau_HBHSDTNT_HK2',$or);
                }else if($type == 11){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_HSDTTS_HK1',$or)
                    ->orderBy('thcd_nhucau_HSDTTS_HK2',$or);
                }else if($type == 12){
                    $getMoney = $getMoney->orderBy('thcd_nhucau_tongtien_HK1',$or)
                    ->orderBy('thcd_nhucau_tongtien_HK2',$or);
                }else if($type == 13){
                    $getMoney = $getMoney->orderBy('class_name',$or);
                }else if($type == 14){
                    $getMoney = $getMoney->orderBy(DB::raw("substring_index(TRIM(profile_name), ' ', -1) "),$or);
                }
            }
            $json['data'] = $getMoney->orderBy('class_unit_id')
            ->orderBy(DB::raw("substring_index(TRIM(profile_name), ' ', -1)"))
            ->orderBy('profile_name')
            ->skip($start*$limit)->take($limit)->get();
            
            return $json;
        } catch (Exception $e) {
            return $e;
        }
    }
	public function approvedAllCreate(Request $request){
        try {
            $result = [];
            $nam = explode('-', $request->YEAR);
            $schools_id = $request->SCHOOLID;
            $year = $nam[1];
            $hocky = $nam[0];
            $status = $request->level;
            $currentuser = Auth::user()->id;
            $currentdate = Carbon::now();
            $update = TongHopCheDo_Truong::where('thcd_nhucau_nam',$year)
            ->where('thcd_nhucau_school_id',Auth::user()->truong_id)->update([
                'trangthai_'.$hocky => $status
            ]);

            if($update > 0){
            	if($status == 1){
            		$result['success'] = 'Xác nhận tất cả toàn bộ học sinh';	
            	}else{
            		$result['success'] = 'Hủy xác nhận tất cả toàn bộ học sinh';
            	}
            }else{
            	if($status == 1){
            		$result['error'] = 'Lỗi xác nhận tất cả. Xin mời thử lại';
            	}else{
            		$result['error'] = 'Lỗi hủy xác nhận tất cả. Xin mời thử lại';
            	}
            }
            
        } catch (Exception $e) {
             $result['error'] = $e;
        }
        return $result;
    }

    public function updateData(Request $rq){
        try{
            $user = Auth::user()->id;
            $school_id = Auth::user()->truong_id;
            $namhoc = explode('-',$rq->years);
            $year = $namhoc[1];
            $profile_id = $rq->profileid;
            // if($namhoc[0] == "HK2"){
            //     $year = $year + 1;
            // }
            $run_now =  tongHopChedo::tongHop_Truong($year, $profile_id, $school_id,null);
            // $getSchoolType = DB::table('qlhs_schools_history')
            //     ->where('type_his_school_id',$rq->schoolid)
            //     ->where('type_his_startdate','<=',$rq->years.'-09-05')
            //     ->where(function($q) use($rq){
            //         $q->orWhere('type_his_enddate','>=',$rq->years.'-09-05')
            //         ->orWhere('type_his_enddate',null);
            //     })
            //     ->select('type_his_type_id')->first(); 
            //         //Lấy danh sách học sinh
            //     $selectHS = DB::table('qlhs_profile_history')
            //     	->leftJoin('qlhs_class','history_class_id','class_id')
            //         ->where('history_school_id',$rq->schoolid)
            //         ->where('history_year',$rq->years.'-'.($rq->years+1));
            //     if($rq->profileid != null && $rq->profileid != ""){
            //     	$selectHS = $selectHS->where('history_profile_id',$rq->profileid);
            //     }
            //     $selectHS = $selectHS->select('history_profile_id','class_unit_id')->get();
            //         foreach ($selectHS as $key => $value) {
            //         	$checkExist = DB::table('qlhs_nhucau_dutoan')
	           //          ->where('id_profile',$value->history_profile_id)
	           //          ->where('namhoc',$rq->years)
	           //          ->where('school_id',$rq->schoolid)
	           //          ->select('id')->get();
	                    
	           //          if(count($checkExist) > 0){
	           //          	DB::table('qlhs_nhucau_dutoan')
		          //           ->where('id_profile',$value->history_profile_id)
		          //           ->where('school_id',$rq->schoolid)
		          //           ->where('namhoc',$rq->years)->update(['status' => 1]);
	           //          	dispatch(new CapNhatCheDo($rq->schoolid,$rq->years,$value->history_profile_id,$getSchoolType->type_his_type_id,$value->class_unit_id,$checkExist[0]->id));
	           //          }else{
	           //          	dispatch(new CapNhatCheDo($rq->schoolid,$rq->years,$value->history_profile_id,$getSchoolType->type_his_type_id,$value->class_unit_id,0));
	           //          }
            //         }                    
            $result['success'] = 'Đang thực hiện.Xin mời đợi!';
        } catch (Exception $e) {
            $result['error'] = $e;
        }
        return $result;
    }

    public function xetduyethocsinh(Request $rq){
    	$re = [];
    	try{
    		$profile_id = $rq->id;
	    	$trangthai = $rq->trangthai;
	    	$nam = explode('-',$rq->namhoc);
            $year = $nam[1];
            $hocky = $nam[0];
	    	$note = $rq->note;
            $update = TongHopCheDo_Truong::where('thcd_nhucau_profile_id',$profile_id)
            ->where('thcd_nhucau_nam',$year)
            ->where('thcd_nhucau_school_id',Auth::user()->truong_id)->first();
            if($update != null){
                if($hocky == "HK1"){
                    $update->trangthai_HK1 = $trangthai;
                    $update->qlhs_thcd_ghichu_HK1 = $note;
                }else if($hocky == "HK2"){
                    $update->trangthai_HK2 = $trangthai;
                    $update->qlhs_thcd_ghichu_HK2 = $note;
                }
                $update->save();
                $re['success'] = 'Xét duyệt thành công';
            }else{
                $re['error'] = 'Học sinh không tồn tại hoặc không thuộc quyền quản lý!';
            }
	    	
    	}catch (Exception $e) {
            $re['error'] = $e;
        }
    	return $re;
    }
    public function load_congvan_tonghop(Request $rq){
    	$id = $rq->id;
    	$year = $rq->year;
    	$chedo = $rq->chedo;
    	$value_type = "";
            // Tổng hợp chế độ cần lập
        if($chedo != null){
            foreach ($chedo as $value) {
                if ($value == 1){
                    $chedoMGHP = 1;
                    $value_type .= "MGHP-";
                }
                if ($value == 2){
                    $chedoCPHT = 1;
                    $value_type .= "CPHT-";
                }
                if ($value == 3){
                    $chedoHTAT = 1;
                    $value_type .= "HTAT-";
                }
                if ($value == 4){
                    $chedoHTBT = 1;
                     $value_type .= "HTBT-";
                }
                if ($value == 5){
                    $chedoHSKT = 1;
                    $value_type .= "HSKT-";
                }
                if ($value == 6){
                    $chedoHTATHS = 1;
                    $value_type .= "HTATHS-";
                }
                if ($value == 7){
                    $chedoHSDTTS = 1;
                    $value_type .= "HSDTTS-";
                }
                if ($value == 8){
                    $chedoHBHSDTNT = 1;
                    $value_type .= "HBHSDTNT-";
                }
                if ($value == 9){
                    $chedoNGNA = 1;
                }
            }

            
            $value_type = substr($value_type,0,strlen($value_type) - 1);

        	$rs['root'] = DB::table('qlhs_hosobaocao')
        	->where('report_id_truong',$id)
        	->where('report_year',$year)
        	->where('report_status','<>',2)
        	->where('report_type','LIKE','%'.$value_type.'%')
        	->where('report_cap_nhan',Auth::user()->level)
        	->select('report_name')
        	->orderBy('updated_at','desc')->get();
        	$rs['change'] = DB::table('qlhs_hosobaocao')
        	->where('report_id_truong',$id)
        	->where('report_year',$year)
        	->where('report_status',2)
        	->where('report_type','LIKE','%'.$value_type.'%')
        	->where('report_cap_nhan',Auth::user()->level)
        	->select('report_name')
        	->orderBy('updated_at','desc')->get();
        	return $rs;
        }
    }
    public function TongHopCongVan(Request $rq){
    	$rs = [];

    	$cvGoc = $rq->cvGoc;
        $cvDC = $rq->cvDC;
        $chedo = $rq->chedo;
        $year = $rq->year;
        $note = $rq->note;
        $name = $rq->name;
        $socv = $rq->socongvan;
        try{

        	$report_name = $socv != null || $socv != "" ? $socv : bussinessClass::autoCode(1,$rq->school_id);
        	$chedoMGHP = 0;
            $chedoCPHT = 0;
            $chedoHTAT = 0;
            $chedoHTBT = 0;
            $chedoHSKT = 0;
            $chedoHSDTTS = 0;
            $chedoHTATHS = 0;
            $chedoHBHSDTNT = 0;
            $chedoNGNA = 0;
            $value_type = "";
            $checkSCV = HoSoBaoCao::where('report_name',$report_name)->count();
            if($checkSCV > 0){
                $rs['error'] = "Công văn đã tồn tại.";
                return $rs;
            }
            // Tổng hợp chế độ cần lập
            foreach ($chedo as $value) {
                if ($value == 1){
                    $chedoMGHP = 1;
                    $value_type .= "MGHP-";
                }else if ($value == 2){
                    $chedoCPHT = 1;
                    $value_type .= "CPHT-";
                }else if ($value == 3){
                    $chedoHTAT = 1;
                    $value_type .= "HTAT-";
                }else if ($value == 4){
                    $chedoHTBT = 1;
                     $value_type .= "HTBT-";
                }else if ($value == 5){
                    $chedoHSKT = 1;
                    $value_type .= "HSKT-";
                }else if ($value == 6){
                    $chedoHTATHS = 1;
                    $value_type .= "HTATHS-";
                }else if ($value == 7){
                    $chedoHSDTTS = 1;
                    $value_type .= "HSDTTS-";
                }else if ($value == 8){
                    $chedoHBHSDTNT = 1;
                    $value_type .= "HBHSDTNT-";
                }
            }

			$value_types =  substr($value_type,0,strlen($value_type) - 1);
	        $rp_value = bussinessClass::autoCodeFirst(1,$rq->school_id);
	        $number = bussinessClass::autoCodeNumber(1,$rq->school_id);
            //return $cvDC;
	        DB::statement("call tonghopcongvan_PGD_PTC('".$cvGoc."','".$cvDC."','".$report_name."',".$chedoMGHP.",".$chedoCPHT.",".$chedoHTAT.",".$chedoHTBT.",".$chedoHSKT.",".$chedoHTATHS.",".$chedoHSDTTS.",".$chedoHBHSDTNT.",".$chedoNGNA.",".Auth::user()->level.",'".$rp_value."','".$value_types."','".$year."',".$rq->school_id.",'".$note."',".$number.",".Auth::user()->id.",'".$name."')");
	        $rs['success'] = "Tổng hợp công văn thành công!";
        }catch (Exception $e) {
            $rs['error'] = $e;
        }
        	
        return $rs;
    }
}