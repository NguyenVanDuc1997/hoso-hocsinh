<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use Excel,datetime;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Models\qlhs_message;
use App\Models\BaoCaoNhuCau_PGD;
use App\Models\BaoCaoNhuCau_PTC;
use App\Models\CapKinhPhi;
use App\Models\Attach_Decision_KP;
use App\Models\LichSuHSHocSinh;
use App\Models\HoSoBaoCao;
use App\Models\QuanLyChiTra;
use App\Models\TongHopCheDo_Truong;
use App\Jobs\compilationProfile;
use App\Jobs\sendDocumentary_Truong;
use App\Jobs\sendDocumentary_PhongSo;
use App\Jobs\CapNhatCheDo;
use App\Helpers\tongHopChedo;
use App\Helpers\lapcongvanCapPhong;
use App\Helpers\bussinessClass;
use App\Models\qlhs_nguoinauan;

class DuToanController extends Controller
{
	public function viewLapdutoan()
    {
        return view('admin.hoso.dutoanchitra.dutoan');
    }

    public function capkinhphi(){
        return view('admin.hoso.dutoanchitra.capkinhphi');
    }

    public function viewChiTra(){
        return view('admin.hoso.dutoanchitra.chitra');
    }

    

    // danh sách dự toán
    public function statistic(Request $rq){
    	$result = [];
        $nam = $rq->year;
    	$truong = $rq->school;
    	$namnhucau = ((int)$nam - 2).'-'.((int)$nam - 1);//nhucau
    	$namdutoan = ((int)$nam - 1).'-'.$nam;//dutoan

    	$result['lenlop'] = LichSuHSHocSinh::where('history_year',$namdutoan)
    	->where('history_school_id',$truong)->count();
    	$result['tongso'] = LichSuHSHocSinh::where('history_year',$namnhucau)
    	->where('history_school_id',$truong)->count();
    	$result['dutoan_1'] = TongHopCheDo_Truong::where('thcd_nhucau_nam',($nam - 2))
    	->where('thcd_nhucau_school_id',$truong)
    	->select(
    		DB::raw('SUM(thcd_nhucau_MGHP_HK2) as MGHP'),
    		DB::raw('SUM(thcd_nhucau_CPHT_HK2) as CPHT'),
    		DB::raw('SUM(thcd_nhucau_HTAT_HK2) as HTAT'),
    		DB::raw('SUM(thcd_nhucau_HTBT_TA_HK2) as HTBT_TA'),
    		DB::raw('SUM(thcd_nhucau_HTBT_TO_HK2) as HTBT_TO'),
    		DB::raw('SUM(thcd_nhucau_HTBT_VHTT_HK2) as HTBT_VHTT'),
    		DB::raw('SUM(thcd_nhucau_HTATHS_HK2) as HTATHS'),
    		DB::raw('SUM(thcd_nhucau_HBHSDTNT_HK2) as HBHSDTNT'),
    		DB::raw('SUM(thcd_nhucau_HSKT_HB_HK2) as HSKT_HB'),
    		DB::raw('SUM(thcd_nhucau_HSKT_DDHT_HK2) as HSKT_DDHT'),
    		DB::raw('SUM(thcd_nhucau_HSDTTS_HK2) as HSDTTS'),
    		DB::raw('SUM(thcd_nhucau_tongtien_HK2) as TONG')
    	)->first();
    	$result['dutoan_2'] = TongHopCheDo_Truong::where('thcd_nhucau_nam',($nam - 2))
    	->where('thcd_nhucau_school_id',$truong)
    	->select(
    		DB::raw('SUM(thcd_nhucau_MGHP_HK1) as MGHP'),
    		DB::raw('SUM(thcd_nhucau_CPHT_HK1) as CPHT'),
    		DB::raw('SUM(thcd_nhucau_HTAT_HK1) as HTAT'),
    		DB::raw('SUM(thcd_nhucau_HTBT_TA_HK1) as HTBT_TA'),
    		DB::raw('SUM(thcd_nhucau_HTBT_TO_HK1) as HTBT_TO'),
    		DB::raw('SUM(thcd_nhucau_HTBT_VHTT_HK1) as HTBT_VHTT'),
    		DB::raw('SUM(thcd_nhucau_HTATHS_HK1) as HTATHS'),
    		DB::raw('SUM(thcd_nhucau_HBHSDTNT_HK1) as HBHSDTNT'),
    		DB::raw('SUM(thcd_nhucau_HSKT_HB_HK1) as HSKT_HB'),
    		DB::raw('SUM(thcd_nhucau_HSKT_DDHT_HK1) as HSKT_DDHT'),
    		DB::raw('SUM(thcd_nhucau_HSDTTS_HK1) as HSDTTS'),
    		DB::raw('SUM(thcd_nhucau_tongtien_HK1) as TONG')
    	)->first();

    	return $result;
    }

    public function statisticNGNA(Request $rq){
        $years = $rq->year;
        $schools_id = $rq->school;
        $load_data = qlhs_nguoinauan::whereIn('NGNA_school_id',explode('-',Auth::user()->truong_id))
        ->leftJoin('qlhs_schools_history as sh','sh.type_his_school_id','NGNA_school_id')
        ->leftJoin('years_months_kp as kp',function($q){
            $q->on('sh.type_his_type_id','kp.id_schooltype')
            ->where('NGNA_years',\DB::raw(" YEAR(kp.date_con) AND CASE WHEN SUBSTR(NGNA_HK,1,3) = 'HK1' THEN kp.months IN (9,10,11,12) ELSE kp.months IN (1,2,3,4,5) END"))
            ->where('kp.id_doituong',102);
        })
        ->where('NGNA_school_id',$schools_id)
        ->where(function($q) use($years){
            $q->orWhere('NGNA_HK','HK1-'.$years)
            ->orWhere('NGNA_HK','HK2-'.($years - 1));
        });
        $load_data = $load_data->select(\DB::raw("ROUND(SUM(CASE WHEN NGNA_Total < 5 THEN ((NGNA_TW - NGNA_68) * 1.35 * value_m)
       ELSE (5 * 1.35 * value_m) END)) as 'TW',
       ROUND(SUM(CASE WHEN NGNA_Total > 5 THEN ((NGNA_DP - NGNA_68) * 2.25 * value_m)
       ELSE 0 END)) as 'DP',NGNA_HK,NGNA_years,NGNA_TW,NGNA_DP,NGNA_68,NGNA_amount,NGNA_id"))
                              ->groupBy('value_m','years','ngna_hk','NGNA_HK','NGNA_years','NGNA_TW','NGNA_DP','NGNA_68','NGNA_amount','NGNA_id'); 
       // $data = DB::table(DB::raw("({$load_data->toSql()}) as m"))
       //                 ->mergeBindings($load_data)
        return $load_data->get();
    }
    // danh sách cấp kinh phí
    public function danhsachkinhphi(Request $rq){
        $json = [];
        $school = $rq->school_id;
        $user_id = $rq->user_id;
        $year   = $rq->year;
        $start  = $rq->start;
        $limit  = $rq->limit;//"CONCAT(users.username,' - ',schools_name) as schools_name"
        $list = CapKinhPhi::leftJoin('qlhs_schools','schools_id','school_id')
        ->leftJoin('users as u1','user_id','u1.id')
        //->leftJoin('users as u2','created_by','u2.id')

        ->where('year',$year)
        ->select('qlhs_capkinhphi.id',
            DB::raw("CASE WHEN schools_name is not null AND u1.username is not null THEN CONCAT(u1.username,' - ',u1.last_name) WHEN schools_name is not null AND u1.username is null THEN schools_name WHEN schools_name is  null AND u1.username is not null THEN CONCAT(u1.username,' - ',u1.last_name) END as schools_name"),
            'expense','year','note','qlhs_capkinhphi.created_at','using','attach_name','created_by','chedo',DB::raw(Auth::user()->id." as us"));
        if($school != null && $school != ''){
            $list = $list->where('school_id',$school)
                         ->whereIn('school_id',explode('-',Auth::user()->truong_id));

        }else{
            
            if(Auth::user()->level == 1){
                $list = $list->where('school_id',Auth::user()->truong_id);
            }else{
                $list = $list->orWhereIn('users.truong_id',explode('-',Auth::user()->truong_id));    
            }
        }

        // if($user_id != null && $user_id != ''){
        //     $list = $list->where('user_id',$user_id);
        // }else{
        //     $list = $list->orWhereIn('users.truong_id',explode('-',Auth::user()->truong_id));
        // }
        // if(Auth::user()->level == 4){
        //     $list = $list->where('user_id','<>',null);
        // }
      //  $list = $list->where('qlhs_capkinhphi.school_id',explode('-',Auth::user()->truong_id));
        if(Auth::user()->level == 3){
            $list = $list->where('qlhs_capkinhphi.level','<=',4);
        }
        if(Auth::user()->level == 2){
            $list = $list->where('qlhs_capkinhphi.level','<=',3);
        }
        if(Auth::user()->level == 1){
            $list = $list->where('qlhs_capkinhphi.level','<=',2);
        }
        if($rq->created_by != "" && $rq->created_by != null){
            $list = $list->where('qlhs_capkinhphi.level',$rq->created_by);
        }
        if($rq->chedo != null && count($rq->chedo) > 0 ){
            $str = "";
                foreach ($rq->chedo as $key => $value) {
                    if($key < (count($rq->chedo) - 1)){
                        $str .= $value.',';
                    }else{
                        $str .= $value;
                    }
                }
            $list = $list->where('qlhs_capkinhphi.chedo','LIKE','%'.$str.'%');
        }

        $json['totalRows'] = $list->get()->count();
        $json['startRecord'] = $start;
        $json['numRows'] = $limit;
        $json['data'] = $list->orderBy('qlhs_capkinhphi.updated_at','desc')
                             ->skip($start*$limit)
                             ->take($limit)->get();
        return $json;
    }
    public function capnhatkinhphi(Request $rq){
        $list = CapKinhPhi::findOrFail($rq->id);
        return $list;
    }

    public function themmoichitra(Request $request){
        try{
            $mess          = [];
            $files         = $request->file('file');
            $name          = $request->report_name;
            //$school        = isset($request->school_id) ? $request->school_id : null;
           // $year          = $request->year;
            $expense       = $request->expense;
            $note          = $request->note;
            $using         = $request->using;
            $times         = $request->times;
            $value = explode('||',$request->value);

            $total         = $request->total;// Tong so hs

           // $_value        = $request->value;// Tiền cấp bằng hoặc khác
            $nam_1 = $request->year - 1;
            $nam_2 = $request->year;
            $chedo = $request->type;
            $school_id = $request->school_id;
            $class_id = $request->class_id;
            $id = $school_id.$class_id.$chedo.$nam_2;
            
           // $start = $request->start;
           // $limit = $request->limit;
            if(isset($request->id) && $request->id != '' ){
                $mess['success'] = "Cập nhật thành công";
                $kp        = CapKinhPhi::find($request->id);
                
            }else{
                $check = CapKinhPhi::where('balance',$id)
                ->where('year',$nam_2)
                ->where('times',$times)
                ->first();
                if($check != null){
                    $kp  = CapKinhPhi::find($check->id);
                }else{
                    $mess['success'] = "Thêm mới thành công";
                    $kp        = new CapKinhPhi();
                    $kp->school_id = $school_id;
                    $kp->year      = $nam_2;
                }
                
            }
            
            $kp->expense   = $expense;
           // $kp->balance   = 1;
            $kp->note      = $note;
            $kp->using     = 1;
            $kp->level     = Auth::user()->level;
            $kp->created_by    = Auth::user()->id;
            
            if($request->hasFile('file'))
            {
                $file_hs = "";
                foreach ($files as $file) {
                    $filename = Auth::user()->id.'_'.time().'_'.$file->getClientOriginalName();
                    $file->move(storage_path().'/quyetdinh',$filename);
                    $upload = new Attach_Decision_KP();
                    $upload->capkp_id    = $kp->id;
                    $upload->attach_name = $filename;
                    $upload->save();
                }
                $kp->attach_name     = $filename;
                
            }
            
            $kp->balance   = $id;
            $kp->times   = $times;
            $kp->save();
            
            
            $queryResult = \DB::select('call get_data_chi_tra_cap_truong(?,?,?,?,?,?,?,?,?,?)', [$chedo,$value[1],$value[0],$nam_2.'-'.($nam_2+1),$school_id,$class_id,$id,$expense,Auth::user()->id,$times]);
            $kp->expense   = collect($queryResult)->first()->sum_amount;
            $kp->save();

            $mess['success'] = "Chi trả thành công";

            $mess['id'] =  $id;
            return $mess;
        }catch(Exception $e){
            $mess['error'] = "Thêm mới có lỗi ".$e;
        }
        return $mess;
    }

    public function themmoicapkinhphi(Request $request){
        try{
            $mess          = [];
            $files         = $request->file('file');
            $name          = $request->report_name;
            $school        = isset($request->school_id) ? $request->school_id : null;
            $year          = $request->year;
            $expense       = $request->expense;
            $note          = $request->note;
            $using         = $request->using;
            // if($request->chedo != null && count($request->chedo) > 0 ){
            //     $str = "";
            //     foreach ($request->chedo as $key => $value) {
            //         if($key < (count($request->chedo) - 1)){
            //             $str .= $value.'-';
            //         }else{
            //             $str .= $value;
            //         }
            //     }
                 $chedo         = $request->chedo;
            // }
            
            if(isset($request->id) && $request->id != '' ){
                $mess['success'] = "Cập nhật thành công";
                $kp        = CapKinhPhi::find($request->id);
                
            }else{
                $mess['success'] = "Thêm mới thành công";
                $kp        = new CapKinhPhi();
                $kp->user_id     = isset($request->user_id) ? $request->user_id : null;
                $kp->school_id = $school;
                $kp->year      = $year;
            }
            
            $kp->expense   = $expense;
            $kp->note      = $note;
            $kp->using     = $using;
            $kp->chedo     = $chedo;
            $kp->level     = Auth::user()->level;
            $kp->created_by    = Auth::user()->id;
            if($request->hasFile('file'))
            {
                $file_hs = "";
                foreach ($files as $file) {
                    $filename = Auth::user()->id.'_'.time().'_'.$file->getClientOriginalName();
                    $file->move(storage_path().'/quyetdinh',$filename);
                    $upload = new Attach_Decision_KP();
                    $upload->capkp_id    = $kp->id;
                    $upload->attach_name = $filename;
                    $upload->save();
                }
                $kp->attach_name     = $filename;
                
            }
            
            
            $kp->save();
            
            
        }catch(Exception $e){
            $mess['error'] = "Thêm mới có lỗi ".$e;
        }

        return $mess;
    }

    public function downloadQDChiTra(Request $request){
        try {
            $capkinhphi = CapKinhPhi::findOrFail($request->id);

            $dir = storage_path().'/quyetdinh/'.$capkinhphi->attach_name;
            return response()->download($dir,$capkinhphi->attach_name);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function load_data_chi_tra(Request $request){
       $nam_1 = $request->years - 1;
       $nam_2 = $request->years;
       $type = $request->type;
       $school_id = $request->school_id;
       $class_id = $request->class_id;
       $start = $request->start;
       $limit = $request->limit;
       $value = explode('||',$request->value);
        $data =  BaoCaoNhuCau_PTC::leftJoin('qlhs_profile_history',function($q) use($nam_2,$school_id){
                $q->on('history_profile_id','bckp_profile_id')
                ->where('history_year',$nam_2.'-'.($nam_2+1))
                ->where('history_school_id',$school_id);
            });
       if($value[1] == 2){
            $data =  BaoCaoNhuCau_PGD::leftJoin('qlhs_profile_history',function($q) use($nam_2,$school_id){
                $q->on('history_profile_id','bckp_profile_id')
                ->where('history_year',$nam_2.'-'.($nam_2+1))
                ->where('history_school_id',$school_id);
            });
        }
        // else if($value[1] == 3)
        //     $data =  BaoCaoNhuCau_PTC::leftJoin('qlhs_profile_history',function($q) use($nam_2,$school_id){
        //         $q->on('history_profile_id','bckp_profile_id')
        //         ->where('history_year',$nam_2.'-'.($nam_2+1))
        //         ->where('history_school_id',$school_id);
        //     });
        // }
            $data = $data->leftJoin('qlhs_profile', 'history_profile_id', 'profile_id')
            ->leftJoin('qlhs_schools', 'schools_id','bckp_truong')
            ->leftJoin('qlhs_class', 'class_id',  'history_class_id')
            ->where('bckp_name', $value[0])
            ->where('bckp_truong', $school_id)
            ->where('history_class_id', $class_id);
            if($type == 1){
                $data= $data->where('bckp_nhucau_mghp','>',0)
                ->select('bckp_nhucau_mghp as chiphi','profile_name','class_name','schools_name');
            }else if($type == 2){
                $data= $data->where('bckp_nhucau_cpht','>',0)
                ->select('bckp_nhucau_cpht as chiphi','profile_name','class_name','schools_name');
            }else if($type == 3){
                $data= $data->where('bckp_nhucau_htat','>',0)
                ->select('bckp_nhucau_htat as chiphi','profile_name','class_name','schools_name');
            }else if($type == 4){
                $data= $data->where('bckp_nhucau_htbt_ta','>',0)
                ->select('bckp_nhucau_htbt_ta as chiphi','profile_name','class_name','schools_name');
            }else if($type == 5){
                $data= $data->where('bckp_nhucau_htbt_to','>',0)
                ->select('bckp_nhucau_htbt_to as chiphi','profile_name','class_name','schools_name');
            }else if($type == 6){
                $data= $data->where('bckp_nhucau_htbt_vhtt','>',0)
                ->select('bckp_nhucau_htbt_vhtt as chiphi','profile_name','class_name','schools_name');
            }else if($type == 7){
                $data= $data->where('bckp_nhucau_htkt_hb','>',0)
                ->select('bckp_nhucau_htkt_hb as chiphi','profile_name','class_name','schools_name');
            }else if($type == 8){
                $data= $data->where('bckp_nhucau_htkt_ddht','>',0)
                ->select('bckp_nhucau_htkt_ddht as chiphi','profile_name','class_name','schools_name');
            }else if($type == 9){
                $data= $data->where('bckp_nhucau_htaths','>',0)
                ->select('bckp_nhucau_htaths as chiphi','profile_name','class_name','schools_name');
            }else if($type == 10){
                $data= $data->where('bckp_nhucau_hsdtts','>',0)
                ->select('bckp_nhucau_hsdtts as chiphi','profile_name','class_name','schools_name');
            }else if($type == 11){
                $data= $data->where('bckp_nhucau_hbhsdtnt','>',0)
                ->select('bckp_nhucau_hbhsdtnt as chiphi','profile_name','class_name','schools_name');
            }
        

        $json['totalRows'] = count($data->get());
        $json['startRecord'] = $start;
        $json['numRows'] = $limit;
        $json['data'] = $data->orderBy('profile_name')->skip($start*$limit)->take($limit)->get();
        return $json;
    }
    public function load_data_done(Request $request){
        $namhoc = $request->years.'-'.($request->years + 1);
        $id = $request->id != null ? $request->id : $request->school_id.$request->class_id.$request->type.$request->years;
        $data = \DB::table('quanly_chitra')
        ->leftJoin('qlhs_profile','quanly_chitra.profile_id','qlhs_profile.profile_id')
        ->leftJoin('qlhs_profile_history','quanly_chitra.profile_id','qlhs_profile_history.history_profile_id')
        ->leftJoin('qlhs_class', 'qlhs_class.class_id',  'qlhs_profile_history.history_class_id')
        ->where('kinhphi',$id)
        ->where('types',$request->type)
        ->where('years',$namhoc)
        ->where('history_year',$namhoc)
        ->select('quanly_chitra.id','quanly_chitra.profile_id','profile_name','class_name','real_expense','expense_1','types','expense_2','expense_3','expense_4','expense_5',DB::raw("(expense_1+expense_2+expense_3+expense_4+expense_5) as expense"));

        $json['totalRows'] = count($data->get());
        $json['startRecord'] = $request->start;
        $json['numRows'] = $request->limit;
        $json['data'] = $data->skip($request->start*$request->limit)->take($request->limit)->get();
        return $json;
    }

    public function capnhatchitra(Request $request){
       $id = $request->id;
       $expense = $request->expense;
       $type = $request->type;
       $times = $request->times;
       $ql = QuanLyChiTra::find($id);
       if($times == 1){
            $ql->expense_1 = $ql->expense_1 + ($type * $expense);  
       }else if($times == 2){
            $ql->expense_2 = $ql->expense_2 + ($type * $expense);  
       }else if($times == 3){
            $ql->expense_3 = $ql->expense_3 + ($type * $expense);  
       }else if($times == 4){
            $ql->expense_4 = $ql->expense_4 + ($type * $expense);  
       }else if($times == 5){
            $ql->expense_5 = $ql->expense_5 + ($type * $expense);  
       }
       $ql->save();
       $kp        = CapKinhPhi::where('balance',$ql->kinhphi)
       ->where('times',$times)->first();
       if($times == 1){
            $kp->expense = $kp->expense + ($type * $expense);  
       }else if($times == 2){
            $kp->expense = $kp->expense + ($type * $expense);  
       }else if($times == 3){
            $kp->expense = $kp->expense + ($type * $expense);  
       }else if($times == 4){
            $kp->expense = $kp->expense + ($type * $expense);  
       }else if($times == 5){
            $kp->expense = $kp->expense + ($type * $expense);  
       }
       $kp->using = 2;
       $kp->save();

       $mess['success'] = "Cập nhật thành công!";
       return  $mess;
    }

    public function getDataCVDLBySchool(Request $rq){

        $schoolid = $rq->school_id;
        $nam_hoc = $rq->years;
        $type = $rq->type;
        $chedo = "";
        if($type == 1){
            $chedo = "MGHP";
        }else if($type == 2){
            $chedo = "CPHT";
        }else if($type == 3){
            $chedo = "HTAT";
        }else if($type == 4){
            $chedo = "HTBT";
        }else if($type == 5){
            $chedo = "HTBT";
        }else if($type == 6){
            $chedo = "HTBT";
        }else if($type == 7){
            $chedo = "HSKT";
        }else if($type == 8){
            $chedo = "HSKT";
        }else if($type == 9){
            $chedo = "HTATHS";
        }else if($type == 10){
            $chedo = "HSDTTS";
        }else if($type == 11){
            $chedo = "HBHSDTNT";
        }
      //  return $chedo;
        //$levelUser  = Auth::user()->level;
       // data[i].report_value+'.'+data[i].number
        $qlhs_hosobaocao = DB::table('qlhs_hosobaocao')
        ->join('qlhs_schools', 'schools_id', 'report_id_truong')
        ->where('report_year', $nam_hoc)
        ->where('report_type','LIKE','%'.$chedo.'%')
        ->where('report_cap_gui',1)
        ->whereIn('report_cap_status',[0,1])
        ->where('schools_id',$schoolid)
        ->select('report_value','number','report_name','report_cap_nhan')
        // ->select('report_type',
        //     DB::raw('GROUP_CONCAT(report_name_text) as report_name_text'),
        //     DB::raw('GROUP_CONCAT(schools_id) as schools_id'),
        //     DB::raw('GROUP_CONCAT(report_note) as report_note'),
        //     DB::raw('MAX(qlhs_hosobaocao.created_at) as created_at'),
        //     DB::raw('SUM(qlhs_hosobaocao.report_total) as report_total'),
        //     'report_name','report_value','number',
        //     'report_cap_status')
        // ->groupBy('report_type','report_name','report_cap_status','report_value','number')
        ->orderBy('qlhs_hosobaocao.report_date', 'desc')
        ->orderBy('qlhs_schools.schools_name')->get();

        return $qlhs_hosobaocao;
    }
}
