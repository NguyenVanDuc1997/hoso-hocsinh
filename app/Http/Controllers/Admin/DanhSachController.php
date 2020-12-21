<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\DanhMucHoSoHocSinh;
use App\Models\qlhs_tonghopchedo;
use App\Models\LichSuHSHocSinh;
use App\Models\HoKhauHSHocSinh;
use App\Models\DoiTuongHSHocSinh;
use App\Models\TongHopCheDo_Truong;
use App\Models\Attach_Decision;
use App\Models\HoSoBaoCao;
use Excel;
use File,datetime;
use App\Jobs\compilationProfile;
use App\Jobs\CapNhatCheDo;
use App\Helpers\tongHopChedo;
use App\Helpers\bussinessClass;


class DanhSachController extends Controller
{
	public function loadDanhSachDeNghi(Request $rq){
        $socongvan = $rq->socongvan;
        $schoolid = $rq->school_id;
        $nam_hoc = $rq->namhoc;
        $type_cv = $rq->type_cv;

        $start = $rq->start;
        $limit = $rq->limit;
        $levelUser  = Auth::user()->level;
        $qlhs_hosobaocao = DB::table('qlhs_hosobaocao')
        ->join('qlhs_schools', 'schools_id', 'report_id_truong')
        ->where('report_year', $nam_hoc)
        ->where('report_cap_nhan',$levelUser )
        ->whereIn('report_cap_status',[0,1,3]);

        if($rq->keysearch != null && $rq->keysearch != ""){
            $qlhs_hosobaocao = $qlhs_hosobaocao->where(function($q) use($rq){
                $q->orWhere('qlhs_schools.schools_name','LIKE','%'.$rq->keysearch.'%')
                  ->orWhere('qlhs_hosobaocao.report_name','LIKE','%'.$rq->keysearch.'%');
            }); 
        }

        if($schoolid == null || $schoolid == ""){
            if(Auth::user()->truong_id != null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                $qlhs_hosobaocao = $qlhs_hosobaocao->whereIn('schools_id',explode('-',Auth::user()->truong_id));
            }
        }else{
        	$qlhs_hosobaocao = $qlhs_hosobaocao->where('report_id_truong',$schoolid);
        }

        $json['totalRows'] = count($qlhs_hosobaocao->get());
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['data'] = $qlhs_hosobaocao
        ->select('report_type',
            DB::raw('GROUP_CONCAT(report_name_text) as report_name_text'),
            DB::raw('GROUP_CONCAT(schools_id) as schools_id'),
            DB::raw('GROUP_CONCAT(report_note) as report_note'),
            DB::raw('MAX(qlhs_hosobaocao.created_at) as created_at'),
            DB::raw('SUM(qlhs_hosobaocao.report_total) as report_total'),
            'report_name','schools_name','schools_id',
            'report_cap_status','report_status')
        ->groupBy('report_type','report_name','report_cap_status','schools_name','schools_id','report_status')
        ->orderBy('qlhs_hosobaocao.report_date', 'desc')
        ->orderBy('qlhs_schools.schools_name')
        ->skip($start*$limit)->take($limit)->get();
        return $json;
    }
}