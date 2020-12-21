<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth,Excel;
use Carbon\Carbon;
use App\Models\LichSuHSHocSinh;
use App\Models\TongHopCheDo_Truong;
use App\Models\HoSoBaoCao;
use App\Models\BaoCaoNhuCau_PGD;
use App\Models\BaoCaoNhuCau_PTC;

class BaocaoController extends Controller
{
    public function getReport(){
        return view('admin.baocao.listing');
        // /return view('category/wards')->with('wards', $wards);
    }
    public function viewThongKe(){
    	return view('admin.baocao.thongke');
    }
    public function viewThongKeHocSinh(){
        return view('admin.baocao.thongkehocsinh');
    }
    public function viewThongKeDoiTuong(){
        return view('admin.baocao.thongkedoituong');
    }    
    public function viewThongKeCongVan(){
        return view('admin.baocao.congvan');
    }
    public function viewDuToan(){
        return view('admin.baocao.baocaodutoan');
    }
    public function viewTongHop(){
        return view('admin.baocao.baocaotonghop');
    }
    

    public function postThongKe(Request $rq){
        $year = $rq->year;
    	$truong_id = $rq->truong_id;
        $getStatisticDetail = DB::select("call statistic_by_group('".$year."-09-01',".$truong_id.",'".$year."-".($year+1)."')");
        $json['totalRows'] = count($getStatisticDetail);
        $json['startRecord'] = ($rq->start);
        $json['numRows'] = $rq->limit;
        $json['data'] = $getStatisticDetail;
        return  $json;
    }

    public function statisticBySchool(Request $rq){
        $getStatisticDetail = DB::table('qlhs_schools as sc')
        ->leftJoin('qlhs_profile as p','sc.schools_id','p.profile_school_id');
        if($rq->truong_id == 0 || $rq->truong_id == null){
            if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                $getStatisticDetail = $getStatisticDetail->whereIn('sc.schools_id',explode('-',Auth::user()->truong_id));
            }
        }else{
            $getStatisticDetail = $getStatisticDetail->where('sc.schools_id',$rq->truong_id);
        }
        $getStatisticDetail = $getStatisticDetail->groupBy('profile_status','sc.schools_id','sc.schools_name')
        ->select('sc.schools_name','sc.schools_id',DB::raw('case when p.profile_status = 0 OR p.profile_status is null then count(p.profile_id) else 0 end "HSDH"'),DB::raw('case when p.profile_status = 1 then count(p.profile_id) else 0 end "HSNH"'));
        $getStatistic = DB::table(DB::raw("({$getStatisticDetail->toSql()}) as m"))->mergeBindings($getStatisticDetail)->select('m.schools_id','m.schools_name',DB::raw('sum(m.HSDH) as HSDH'),DB::raw('sum(m.HSNH) as HSNH'))->groupBy('m.schools_id','m.schools_name');
        
        $json['totalRows'] = count($getStatistic->get());
        $json['startRecord'] = ($rq->start);
        $json['numRows'] = $rq->limit;
        $json['data'] = $getStatistic->orderBy('m.HSDH','desc')->orderBy('m.schools_name')->skip($rq->start*$rq->limit)->take($rq->limit)->get();
        return  $json;
    }
    public function statisticBySite(Request $rq){
        $year = $rq->year;
        $getStatisticDetail = DB::table('qlhs_schools as sc')
        ->leftJoin('qlhs_profile_history as p',function($q) use($year){
            $q->on('sc.schools_id','p.history_school_id')
            ->where('history_year',$year.'-'.($year+1));
        })
        ->leftJoin('history_profile_site as his',function($q) use($year){
            $q->on('his.p_id','p.history_profile_id')
            ->whereYear('his.start_date','<=',$year)
            ->where(function($p) use($year){
                $p->orWhere('his.end_date','>',($year+1).'-09-01')
                ->orWhere('his.end_date',null);
            });
        });
        if($rq->truong_id == 0 || $rq->truong_id == null){
            if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                    $getStatisticDetail = $getStatisticDetail
                    ->whereIn('sc.schools_id',explode('-',Auth::user()->truong_id));
                }
            
        }else{
            $getStatisticDetail = $getStatisticDetail->where('sc.schools_id',$rq->truong_id);
        }
        
        if($rq->Quan != null && $rq->Quan != ''){
            $getStatisticDetail = $getStatisticDetail
            ->leftJoin('qlhs_site as st','st.site_id','his.site_quanhuyen')
            ->where('his.site_quanhuyen',$rq->Quan)
            ->groupBy('sc.schools_id','sc.schools_name');
        }else{
            if($rq->Phuong != null && $rq->Phuong != ''){
                $getStatisticDetail = $getStatisticDetail
                ->leftJoin('qlhs_site as st','st.site_id','his.site_phuongxa')
                ->where('his.site_phuongxa',$rq->Phuong)
                ->groupBy('sc.schools_id','sc.schools_name');
            }else{
                if($rq->Thon != null && $rq->Thon != ''){
                    $getStatisticDetail = $getStatisticDetail
                    ->leftJoin('qlhs_site as st','st.site_id','his.site_thon')
                    ->where('his.site_thon',$rq->Thon)
                    ->groupBy('sc.schools_id','sc.schools_name');
                }
            }
        }

        $getStatisticDetail = $getStatisticDetail
        ->select('sc.schools_name','sc.schools_id',DB::raw('Count(st.site_id) as Tong'));

        $json['totalRows'] = count($getStatisticDetail->get());
        $json['startRecord'] = ($rq->start);
        $json['numRows'] = $rq->limit;
        $json['data'] = $getStatisticDetail->orderBy('sc.schools_name')
        ->skip($rq->start*$rq->limit)->take($rq->limit)->get();
        return  $json;
    }

    public function exportStatisticByBanTru(Request $rq)
    {
        $schools_id = $rq->schools_id;
        $profiles = DB::table('qlhs_profile')
        ->join('qlhs_nationals', 'qlhs_nationals.nationals_id', '=', 'qlhs_profile.profile_nationals_id')
        ->join('qlhs_schools', 'qlhs_schools.schools_id', '=', 'qlhs_profile.profile_school_id')
        ->join('qlhs_class', 'qlhs_class.class_id', '=', 'qlhs_profile.profile_class_id')
        ->join('qlhs_site as tinh', 'tinh.site_id', '=', 'qlhs_profile.profile_site_id1')
        ->join('qlhs_site as huyen', 'huyen.site_id', '=', 'qlhs_profile.profile_site_id2')
        ->leftJoin('qlhs_site as thon', 'thon.site_id', '=', 'qlhs_profile.profile_household')
        ->leftJoin('qlhs_site as xa', 'xa.site_id', '=', 'qlhs_profile.profile_site_id3')
        ->where('qlhs_profile.profile_school_id',$schools_id);
        if($rq->type == 0){
            $profiles = $profiles->where(function($q){
                $q->orWhere('qlhs_profile.profile_status',0)->orWhere('qlhs_profile.profile_status',null);
            });
        }else if($rq->type == 1){
            $profiles = $profiles->where('qlhs_profile.profile_status',1);
        }
        $profiles = $profiles->select('qlhs_profile.profile_code', 'qlhs_profile.profile_name', 'qlhs_profile.profile_birthday', 'qlhs_nationals.nationals_name', 'qlhs_profile.profile_parentname', 'tinh.site_name as tentinh', 'huyen.site_name as tenhuyen', 'xa.site_name as tenxa', 'thon.site_name as profile_household', 'qlhs_profile.profile_school_id', 'qlhs_schools.schools_name', 'qlhs_class.class_name', 'qlhs_profile.profile_year', 'qlhs_profile.profile_bantru', 'qlhs_profile.profile_status', 'qlhs_profile.profile_leaveschool_date','qlhs_profile.profile_id','profile_giaothong','profile_km','profile_statusNQ57','profile_guardian')->get();
        $this->addCellExcel($profiles, 'Hồ sơ học sinh'.'_'.Auth::user()->username, FALSE);
    }
    private function addCellExcel($data_results, $filename, $type = true){
        $excel =    Excel::load(storage_path().'/exceltemplate/hosohocsinh.xlsx', function($reader) use($data_results){
            $borderArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => 'thin',
                        'color' => array('argb' => 'FF000000')
                    )
                )
            );
            $FontArray = array(
                'font' => array(
                    'bold' => 'bold'
                )
            );
            $FontArrayitalic = array(
                'font' => array(
                    'italic' => 'italic'
                )
            );
            $style = array(
                'alignment' => array(
                    'horizontal' => 'center',
                )
            );
            $styleLeft = array(
                'alignment' => array(
                    'horizontal' => 'left',
                )
            );
            $styleRight = array(
                'alignment' => array(
                    'horizontal' => 'right',
                )
            );

            $row = 5;

            $indexa = 0;
                foreach($data_results as $value){
                     $row++;$indexa++;
     
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $indexa)->getStyle('A'.$row)->applyFromArray($borderArray)->applyFromArray($style);// SO THU TU
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $value->profile_name)->getStyle('B'.$row)->applyFromArray($borderArray)->applyFromArray($styleLeft);// TEN HOC SINH
                        if($value->profile_statusNQ57 != null && $value->profile_statusNQ57 != 0){
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'x')->getStyle('C'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                        }
                        
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(3, $row,Carbon::parse($value->profile_year)->format('d-m-Y'))->getStyle('D'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(4, $row,$value->schools_name)->getStyle('E'.$row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(5, $row,$value->class_name)->getStyle('F'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(6, $row,Carbon::parse($value->profile_birthday)->format('d-m-Y'))->getStyle('G'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(7, $row,$value->profile_guardian)->getStyle('H'.$row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(8, $row,$value->nationals_name)->getStyle('I'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(9, $row,$value->profile_household)->getStyle('J'.$row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(10, $row,$value->tenxa)->getStyle('K'.$row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(11, $row,$value->tenhuyen)->getStyle('L'.$row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(12, $row,$value->profile_parentname)->getStyle('M'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                        $subject = DB::table('qlhs_profile_subject')->where('profile_subject_profile_id',$value->profile_id)->where(function($q){
                            $q->whereNull('end_year')->orWhere('start_year','>=',Carbon::now()->format('Y'));
                        })->select('profile_subject_subject_id')->get();
                        foreach ($subject as $key => $val) {
                            $sub = $val->profile_subject_subject_id;
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(13, $row, '')->getStyle('N'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(14, $row, '')->getStyle('O'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(15, $row, '')->getStyle('P'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(16, $row, '')->getStyle('Q'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(17, $row, '')->getStyle('R'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(18, $row, '')->getStyle('S'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(19, $row, '')->getStyle('T'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(20, $row, '')->getStyle('U'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(21, $row, '')->getStyle('V'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(22, $row, '')->getStyle('W'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(23, $row, '')->getStyle('X'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(24, $row, '')->getStyle('Y'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            if((int)$sub == 35){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(13, $row, 'x')->getStyle('N'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 74){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(14, $row, 'x')->getStyle('O'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 41){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(15, $row, 'x')->getStyle('P'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }
                            else if((int)$sub == 38){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(16, $row, 'x')->getStyle('Q'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 28){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(17, $row, 'x')->getStyle('R'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 73){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(18, $row, 'x')->getStyle('S'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 39){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(19, $row, 'x')->getStyle('T'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 49){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(20, $row, 'x')->getStyle('U'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 34){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(21, $row, 'x')->getStyle('V'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 26){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(22, $row, 'x')->getStyle('W'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 40){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(23, $row, 'x')->getStyle('X'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if((int)$sub == 46){
                                 $reader->getActiveSheet()->setCellValueByColumnAndRow(24, $row, 'x')->getStyle('Y'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }
                        }
                        if($value->profile_bantru != null && $value->profile_bantru != ''){
                            if($value->profile_bantru == 0 ){
                                $reader->getActiveSheet()->setCellValueByColumnAndRow(25, $row, 'x')->getStyle('Z'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                                $reader->getActiveSheet()->setCellValueByColumnAndRow(26, $row, '')->getStyle('AA'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }else if($value->profile_bantru == 1){
                                $reader->getActiveSheet()->setCellValueByColumnAndRow(25, $row, '')->getStyle('Z'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                                $reader->getActiveSheet()->setCellValueByColumnAndRow(26, $row, 'x')->getStyle('AA'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            }
                        }else {
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(25, $row, '')->getStyle('Z'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(26, $row, '')->getStyle('AA'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                        } 
                        if($value->profile_km == 0){
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(27, $row, '')->getStyle('AB'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                        }else{
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $value->profile_km)->getStyle('AB'.$row)->applyFromArray($borderArray)->applyFromArray($style);
                        }                      
                        
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $value->profile_giaothong)->getStyle('AC'.$row)->applyFromArray($borderArray)->applyFromArray($style);
            }
        });
        if($type){
            return $excel->setFilename($filename)->store('xlsx', storage_path().'/excel/MGHP');
        }else{
            return $excel->setFilename($filename)->download('xlsx');
        }
    }

    public function statisticByYear(Request $rq){
        $year = $rq->year;
        $getStatisticDetail = DB::table('qlhs_schools as sc')
        ->leftJoin('qlhs_profile_history as his',function($q) use($year){
            $q->on('his.history_school_id','sc.schools_id')
            ->where('his.history_year',$year.'-'.($year+1));
        });
        if($rq->truong_id == 0 || $rq->truong_id == null){
            if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                    $getStatisticDetail = $getStatisticDetail->whereIn('sc.schools_id',explode('-',Auth::user()->truong_id));
                }
            
        }else{
            $getStatisticDetail = $getStatisticDetail->where('sc.schools_id',$rq->truong_id);
        }
        $getStatisticDetail = $getStatisticDetail
        ->groupBy('sc.schools_id','sc.schools_name')
        ->select('sc.schools_name','sc.schools_id',DB::raw('COUNT(his.history_profile_id) as Tong'));    

        $json['totalRows'] = ($getStatisticDetail->count());
        $json['startRecord'] = ($rq->start);
        $json['numRows'] = $rq->limit;
        $json['data'] = $getStatisticDetail
        ->orderBy('sc.schools_name')
        ->skip($rq->start*$rq->limit)->take($rq->limit)->get();
        return  $json;
    }
    public function loadProfile(Request $req){
        try{
        $json = [];
            $start = $req->input('start');
            $limit = $req->input('limit');
            $id_truong = $req->input('id_truong');
            $type = $req->input('type');
            //$keysearch = $req->input('key');

            $qlhs_profile = DB::table('qlhs_profile')
            ->leftJoin('qlhs_nationals','qlhs_profile.profile_nationals_id' ,'=', 'qlhs_nationals.nationals_id')
            ->leftJoin('qlhs_site as huyen','huyen.site_id' ,'=', 'qlhs_profile.profile_site_id2')
            ->leftJoin('qlhs_site as phuong','phuong.site_id' ,'=', 'qlhs_profile.profile_site_id3')
            ->leftJoin('qlhs_site as thon','thon.site_id' ,'=', 'qlhs_profile.profile_household')
            ->leftJoin('qlhs_class','qlhs_profile.profile_class_id' ,'=', 'qlhs_class.class_id')
            ->leftJoin(DB::raw('(SELECT history_profile_id,max(history_year) history_year,max(history_upto_level) history_upto_level from qlhs_profile_history 
                GROUP BY history_profile_id) as qlhs_profile_history '),'qlhs_profile_history.history_profile_id', '=', 'qlhs_profile.profile_id')
            ->where('qlhs_nationals.nationals_active', 1)
            ->where('huyen.site_active', 1)         
            ->where('qlhs_class.class_active', 1);
            if($type == 0){
                $qlhs_profile = $qlhs_profile->where(function($q){
                    $q->orWhere('qlhs_profile.profile_status',0)->orWhere('qlhs_profile.profile_status',null);
                }); 
            }else if($type == 1){
                $qlhs_profile = $qlhs_profile->where('qlhs_profile.profile_status',1);
            }
            if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                $qlhs_profile->whereIn('qlhs_profile.profile_school_id',explode('-',Auth::user()->truong_id));
            }
            if($id_truong!=0){
                $qlhs_profile->where('qlhs_profile.profile_school_id','=',$id_truong);
            }
            // if ($keysearch != null && $keysearch != "") {
            //     $qlhs_profile->where(function($query) use ($keysearch){
            //        $query->where("profile_code", "LIKE","%".$keysearch."%")
            //        ->orWhere("profile_name", "LIKE", "%".$keysearch."%")
            //        ->orWhere("profile_birthday", "LIKE", "%".$keysearch."%")
            //        ->orWhere("nationals_name", "LIKE", "%".$keysearch."%")
            //        ->orWhere("huyen.site_name", "LIKE", "%".$keysearch."%")
            //        ->orWhere("phuong.site_name", "LIKE", "%".$keysearch."%")
            //        ->orWhere("thon.site_name", "LIKE", "%".$keysearch."%")
            //        ->orWhere("profile_parentname", "LIKE", "%".$keysearch."%")
            //        ->orWhere("class_name", "LIKE", "%".$keysearch."%")
            //        ->orWhere("history_year", "=", "%".$keysearch."%")
            //        ->orWhere("profile_year", "=", "%".$keysearch."%");
            //     });
            // }
            $json['startRecord'] = ($start);
            $json['numRows'] = $limit;
            $json['totalRows'] = $qlhs_profile->count();
            $json['data'] = $qlhs_profile->select('qlhs_profile.profile_id','qlhs_profile.profile_code','qlhs_profile.profile_name','qlhs_profile.profile_birthday','thon.site_name as profile_household','qlhs_profile.profile_parentname','qlhs_profile.profile_status','qlhs_profile.profile_year','qlhs_profile.profile_leaveschool_date','qlhs_nationals.nationals_name','huyen.site_name as huyen','phuong.site_name as phuong','qlhs_class.class_name', 'qlhs_profile_history.history_year','qlhs_profile.profile_guardian')->orderBy('qlhs_profile.updated_at', 'desc')->skip($start*$limit)->take($limit)->get();
            return $json;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getDataCVDLBySchool(Request $rq){
        $socongvan = $rq->socongvan;
        $schoolid = $rq->school_id;
        $nam_hoc = $rq->namhoc;
        $type_cv = $rq->type_cv;

        $start = $rq->start;
        $limit = $rq->limit;
        if($type_cv == null || $type_cv == ""){
            $type_cv = 1;
        }
        $qlhs_hosobaocao = DB::table('qlhs_hosobaocao')
        ->join('qlhs_schools', 'schools_id', 'report_id_truong')
        ->where('report_year', $nam_hoc)
        ->where('report_cap_gui',$type_cv );
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
            $qlhs_hosobaocao = $qlhs_hosobaocao->where('schools_id',$schoolid);
        }
        $json['totalRows'] = count($qlhs_hosobaocao->get());
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['data'] = $qlhs_hosobaocao->orderBy('qlhs_schools.schools_name')
        ->orderBy('qlhs_hosobaocao.report_date', 'desc')
        ->skip($start*$limit)->take($limit)->get();
        return $json;
    }

    public function statisticBySubject(Request $request){
        $truong = $request->truong_id;
        $hocky = $request->hocky;
        $khoilop = $request->khoilop;
        $nam = explode("-",$hocky);
        $year = $nam[1];
        $namhoc = $year.'-'.($year + 1);
        if($nam[0] == 'HK1'){
            $type = 0;    
        }else{
            $type = 1;
            $year = $year + 1;    
        }
        $getSchoolType = DB::table('qlhs_schools_history')
                ->where('type_his_school_id',$truong)
                ->where('type_his_startdate','<=',$year.'-09-05')
                ->where(function($q) use ($year){
                    $q->orWhere('type_his_enddate','>=',$year.'-09-05')
                    ->orWhere('type_his_enddate',null);
                })
                ->select('type_his_type_id')
                ->first();
        if($khoilop == null){
            $liencap = DB::table('qlhs_schools')
                    ->where('schools_id',Auth::user()->truong_id)
                    ->select('LienCap')->first();
                if($liencap->LienCap != null){
                    $khoilop = explode('-', $liencap->LienCap)[0];
                }
        }
        //return $year.'-'.$namhoc.'-'.$type.'-'.$truong.'-'.$khoilop;
        $queryResult = [];
        if($request->chedo == 1 && $khoilop != 2){
            $queryResult = \DB::select('call bao_cao_mghp(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);    
        }else if($request->chedo == 2){
            $queryResult = \DB::select('call bao_cao_cpht(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
        }else if($request->chedo == 3 && $khoilop == 1){
            $queryResult = \DB::select('call bao_cao_htatmg(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
        }else if($request->chedo == 4 && $getSchoolType->type_his_type_id != 4){
            $queryResult = \DB::select('call bao_cao_btta(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
        }else if($request->chedo == 5 && $getSchoolType->type_his_type_id != 4){
            $queryResult = \DB::select('call bao_cao_btto(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
        }else if($request->chedo == 6 && $getSchoolType->type_his_type_id != 4){
            $queryResult = \DB::select('call bao_cao_btvhtt(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
        }else if($request->chedo == 7 && $getSchoolType->type_his_type_id == 2){
            $queryResult = \DB::select('call bao_cao_tahs(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
        }else if($request->chedo == 8){
            $queryResult = \DB::select('call bao_cao_hskt(?,?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop,95]);
        }else if($request->chedo == 9){
            $queryResult = \DB::select('call bao_cao_hskt(?,?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop,100]);
        }else if($request->chedo == 10 && $khoilop == 33){
            $queryResult = \DB::select('call bao_cao_dtts(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
        }else if($request->chedo == 11 && $getSchoolType->type_his_type_id == 4){
            $queryResult = \DB::select('call bao_cao_dtnt(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
        }

        $json = [];
        //if($request->start != null && $request->limit != null){
            $r = collect($queryResult);
           // return $r;
            $json['totalRows'] = count($r);
            $json['startRecord'] = ($request->start);
            $json['numRows'] = $request->limit;
            $json['data'] = $r->splice($request->start*$request->limit,$request->limit);
            
        //}
        return $json;
    }

    public function statisticBySubjectView(Request $request){
        $truong = $request->truong_id;
        $hocky = $request->hocky;
        $khoilop = $request->khoilop;
        $nam = explode("-",$hocky);
        $year = $nam[1];
        $namhoc = $year.'-'.($year + 1);
        $hk = '';
        if($nam[0] == 'HK1'){
            $type = 0;  
            $hk = 'Học kỳ 1';
        }else{
            $type = 1;
            $year = $year + 1;    
            $hk = 'Học kỳ 2';
        }
        $getSchoolType = DB::table('qlhs_schools_history')
                ->where('type_his_school_id',$truong)
                ->where('type_his_startdate','<=',$year.'-09-05')
                ->where(function($q) use ($year){
                    $q->orWhere('type_his_enddate','>=',$year.'-09-05')
                    ->orWhere('type_his_enddate',null);
                })
                ->select('type_his_type_id')
                ->first();
        if($khoilop == null){
            $liencap = DB::table('qlhs_schools')
                    ->where('schools_id',Auth::user()->truong_id)
                    ->select('LienCap')->first();
                if($liencap->LienCap != null){
                    $khoilop = explode('-', $liencap->LienCap)[0];
                }
        }
        $title = '';
        $queryResult = [];
        if($request->chedo == 1 && $khoilop != 2){
            $queryResult = \DB::select('call bao_cao_mghp(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ MIỄN GIẢM HỌC PHÍ';    
        }else if($request->chedo == 2){
            $queryResult = \DB::select('call bao_cao_cpht(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ CHI PHÍ HỌC TẬP';    
        }else if($request->chedo == 3 && $khoilop == 1){
            $queryResult = \DB::select('call bao_cao_htatmg(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ TIỀN ĂN TRƯA CHO TRẺ EM MẪU GIÁO';    
        }else if($request->chedo == 4 && $getSchoolType->type_his_type_id != 4){
            $queryResult = \DB::select('call bao_cao_btta(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ TIỀN ĂN BÁN TRÚ';    
        }else if($request->chedo == 5 && $getSchoolType->type_his_type_id != 4){
            $queryResult = \DB::select('call bao_cao_btto(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ TIỀN Ở BÁN TRÚ';    
        }else if($request->chedo == 6 && $getSchoolType->type_his_type_id != 4){
            $queryResult = \DB::select('call bao_cao_btvhtt(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ TIỀN VĂN HÓA TỦ THUỐC';    
        }else if($request->chedo == 7 && $getSchoolType->type_his_type_id == 2){
            $queryResult = \DB::select('call bao_cao_tahs(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ TIỀN ĂN CHO HỌC SINH';    
        }else if($request->chedo == 8){
            $queryResult = \DB::select('call bao_cao_hskt(?,?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop,95]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ HỌC BỔNG HỌC SINH KHUYẾT TẬT';    
        }else if($request->chedo == 9){
            $queryResult = \DB::select('call bao_cao_hskt(?,?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop,100]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ ĐỒ DÙNG HỌC TẬP HỌC SINH KHUYẾT TẬT';    
        }else if($request->chedo == 10 && $khoilop == 33){
            $queryResult = \DB::select('call bao_cao_dtts(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ HỌC SINH DÂN TỘC THIỂU SỐ';    
        }else if($request->chedo == 11 && $getSchoolType->type_his_type_id == 4){
            $queryResult = \DB::select('call bao_cao_dtnt(?,?,?,?,?)', [$year,$namhoc,$type,$truong,$khoilop]);
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ HỌC BỔNG HỌC SINH DÂN TỘC NỘI TRÚ';    
        }

        return view('admin.baocao.elements.thongkehocsinh', [
            'data' => collect($queryResult),
            'title' => $title,
            'hocky' => $hk,
            'namhoc' => $namhoc,
            'school' => $request->tentruong
        ]);
    }

    public function statisticByHistorySubject(Request $request){
        $truong   = $request->truong_id;
        $hk       = explode('-', $request->hocky);
        $year     = (int) $hk[1];
        $khoilop  = $request->khoilop;
        $namhoc   = $year.'-'.($year + 1);
        $chedo    =  $request->chedo;
        $doituong = [];
        //$doituong = [$chedo];
        $queryResult = \DB::table('qlhs_profile_history')
                ->leftJoin('qlhs_profile_subject','profile_subject_profile_id','history_profile_id')
                ->leftJoin('qlhs_profile','profile_id','history_profile_id')
                ->leftJoin('qlhs_class','qlhs_class.class_id','qlhs_profile_history.history_class_id')
                ->where('history_year',$namhoc)
                ->where('history_school_id',$truong);
        if($chedo && count($chedo) > 0){
            
            $value7441 = 0;
            $value4934 = 0;
            foreach ($chedo as $key => $value) {
                if($value == 74){
                    if($value7441 > 0){
                        $doituong[100] = [100];
                        unset($doituong[74]);
                        unset($doituong[41]);
                    }else{
                        $value7441++;
                        $doituong[74] = [74,100];
                    }                    
                }else if($value == 41){
                    if($value7441 > 0){
                        $doituong[100] = [100];
                        unset($doituong[74]);
                        unset($doituong[41]);
                    }else{
                        $value7441++;
                        $doituong[74] = [41,100];
                    }                    
                }else if($value == 49){
                    if($value4934 > 0){
                        $doituong[101] = [101];
                        unset($doituong[49]);
                        unset($doituong[34]);

                    }else{
                        $value4934++;
                        $doituong[49] = [49,101];
                    }                    
                }else if($value == 34){
                    if($value4934 > 0){
                        $doituong[101] = [101];
                        unset($doituong[49]);
                        unset($doituong[34]);
                    }else{
                        $value4934++;
                        $doituong[34] = [34,101];
                    }                    
                }else{
                    $doituong[$value] = [$value];
                }
            }
            
        }
        if($doituong && count($doituong) > 0){
            foreach ($doituong as $key => $value) {
                $queryResult = $queryResult->whereIn('profile_subject_subject_id',$value);
            } 
        }
        

        

        if($hk[0] == 'HK1'){
            $s_date = $year.'-09-01';
            $e_date = $year.'-12-31';
        }else if($hk[0] == 'HK2'){
            $s_date = ($year+1).'-01-01';
            $e_date = ($year+1).'-05-31';
        }
        
        $queryResult = $queryResult->where('start_date','<=',$e_date)
                        ->where(function($q) use($s_date,$e_date){
                            $q->where(function($p) use ($s_date,$e_date){
                                $p->where('end_date','>',$s_date)
                                ->where('end_date','<=',$e_date);
                            })
                            ->orWhere('end_date','>=',$e_date)
                            ->orWhereNull('end_date');
                        })
                        //->whereIn('profile_subject_subject_id',$doituong)
                        ->select('profile_name','profile_birthday','profile_parentname','qlhs_class.class_name');
        // $queryResult = \DB::table('qlhs_profile_history')
        //         ->leftJoin('qlhs_profile_subject','profile_subject_profile_id','history_profile_id')
        //         ->leftJoin('qlhs_profile','profile_id','history_profile_id')
        //         ->leftJoin('qlhs_class','qlhs_class.class_id','qlhs_profile_history.history_class_id')
        //         ->where('history_year',$namhoc)
        //         ->where('history_school_id',$truong)
        //         ->whereYear('start_date','<=',$year)
        //         ->where(function($q) use($year){
        //             $q->whereYear('end_date','>=',$year)
        //             ->orWhereNull('end_date');
        //         })
        //         ->whereIn('profile_subject_subject_id',$doituong)
        //         ->select('profile_name','profile_birthday','profile_parentname','qlhs_class.class_name');
        if($khoilop != null){
            $queryResult = $queryResult->where('unit_class',$khoilop);
        }
       // return $queryResult->toSql();
        
        $json = [];
        $r = $queryResult;
        $json['totalRows'] = ($r->count());
        $json['startRecord'] = ($request->start);
        $json['numRows'] = $request->limit;
        $json['data'] = $queryResult->skip($request->start*$request->limit)->take($request->limit)->get();

        return $json;
    }

    public function statisticByHistorySubjectView(Request $request){
        // $truong = $request->truong_id;
        // $year = $request->hocky;
        // $khoilop = $request->khoilop;
        // $namhoc = $year.'-'.($year + 1);
        // $chedo = (int) $request->chedo;
        // $doituong = [];
        // $doituong = [$chedo];
        // if($chedo == 74 || $chedo == 41){
        //     $doituong = [$chedo,100];

        // }else if($chedo == 49 || $chedo == 34){
        //     $doituong = [$chedo,101];
        // }

        // $queryResult = \DB::table('qlhs_profile_history')
        //         ->leftJoin('qlhs_profile_subject','profile_subject_profile_id','history_profile_id')
        //         ->leftJoin('qlhs_profile','profile_id','history_profile_id')
        //         ->leftJoin('qlhs_class','qlhs_class.class_id','qlhs_profile_history.history_class_id')
        //         ->where('history_year',$namhoc)
        //         ->where('history_school_id',$truong)
        //         ->whereYear('start_date','<=',$year)
        //         ->where(function($q) use($year){
        //             $q->whereYear('end_date','>=',$year)
        //             ->orWhereNull('end_date');
        //         })
        //         ->whereIn('profile_subject_subject_id',$doituong)
        //         ->select('profile_name','profile_birthday','profile_parentname','qlhs_class.class_name');
        $truong   = $request->truong_id;
        $hk       = explode('-', $request->hocky);
        $year     = (int) $hk[1];
        $khoilop  = $request->khoilop;
        $namhoc   = $year.'-'.($year + 1);
        $chedo    =  $request->chedo;
        $doituong = [];
        $doituong = [$chedo];
        if(count($chedo) > 0){
            $i = 0;
            foreach ($chedo as $key => $value) {
                if($value == 74 || $value == 41){
                    $doituong[$i] = 100;
                    $i++;
                }else if($value == 49 || $value == 34){
                    $doituong[$i] = 101;
                    $i++;
                }
                $doituong[$i] = $value;
                $i++;
            }
        }
        if($hk[0] == 'HK1'){
            $s_date = $year.'-09-01';
            $e_date = $year.'-12-31';
        }else if($hk[0] == 'HK2'){
            $s_date = ($year+1).'-01-01';
            $e_date = ($year+1).'-05-31';
        }
        $queryResult = \DB::table('qlhs_profile_history')
                ->leftJoin('qlhs_profile_subject','profile_subject_profile_id','history_profile_id')
                ->leftJoin('qlhs_profile','profile_id','history_profile_id')
                ->leftJoin('qlhs_class','qlhs_class.class_id','qlhs_profile_history.history_class_id')
                ->where('history_year',$namhoc)
                ->where('history_school_id',$truong)
                ->where('start_date','<=',$e_date)
                ->where(function($q) use($s_date,$e_date){
                    $q->where(function($p) use ($s_date,$e_date){
                        $p->where('end_date','>',$s_date)
                        ->where('end_date','<=',$e_date);
                    })
                    ->orWhere('end_date','>=',$e_date)
                    ->orWhereNull('end_date');
                })
                ->whereIn('profile_subject_subject_id',$doituong)
                ->select('profile_name','profile_birthday','profile_parentname','qlhs_class.class_name');
        if($khoilop != null){
            $queryResult = $queryResult->where('unit_class',$khoilop);
            
        }
        
        return view('admin.baocao.elements.thongkedoituong', [
            'data' => $queryResult->get(),
            'title' => 'DANH SÁCH HỌC SINH THUỘC ĐỐI TƯỢNG '.$request->title,
            'namhoc' => $namhoc,
            'school' => $request->tentruong
        ]);
    }

    public function BaoCaoDuToanView(Request $rq){
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
        return view('admin.baocao.elements.baocaodutoan', [
            'data' => $result,
            'title' => 'DANH SÁCH DỰ TOÁN NĂM '.$nam,
            'nam' => $nam,
            'school' => $rq->tentruong
        ]);
    }

    public function statisticByTotal(Request $request){
        $levelUser = Auth::user()->level;
        if($levelUser == 2){
            $hsbc = BaoCaoNhuCau_PGD::leftJoin('qlhs_schools','schools_id','bckp_truong');
        }else if($levelUser == 3){
            $hsbc = BaoCaoNhuCau_PTC::leftJoin('qlhs_schools','schools_id','bckp_truong');
        }else if($levelUser == 4){
            $hsbc = \DB::table('qlhs_baocaokinhphi_SGD')->leftJoin('qlhs_schools','schools_id','bckp_truong');
        }
        
        if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
            $hsbc = $hsbc->whereIn('bckp_truong',explode('-',Auth::user()->truong_id));
        }
        
        $hsbc = $hsbc->select('schools_name','bckp_name as report_name',\DB::raw('SUM(bckp_nhucau_'.$request->chedo.') as bckp_nhucau'),\DB::raw('COUNT(bckp_nhucau_'.$request->chedo.') as report_total'))
            ->where('bckp_nhucau_'.$request->chedo.'','>', 0);
            if($request->chedo == 'htbt_tong'){
                $hsbc = $hsbc->where(function($q){
                    $q->orWhereIn('bckp_trangthai_htbt_to',[1,4])
                      ->orWhereIn('bckp_trangthai_htbt_ta',[1,4])
                      ->orWhereIn('bckp_trangthai_htbt_vhtt',[1,4]);
                });
            }else if($request->chedo == 'htkt_tong'){
                $hsbc = $hsbc->where(function($q){
                    $q->orWhereIn('bckp_trangthai_htkt_hb',[1,4])
                      ->orWhereIn('bckp_trangthai_htkt_ddht',[1,4]);
                });
            }else{
                $hsbc = $hsbc->whereIn('bckp_trangthai_'.$request->chedo.'',[1,4]);
            }
            
            

        $json = [];
       // if($levelUser == 2){
            $json['data'] = $hsbc->where('bckp_namhoc',$request->hocky)->groupBy('bckp_name','schools_name')->orderBy('schools_name')->get();
        // }else if($levelUser == 3){
        //     $json['data'] = $hsbc->where('bckp_namhoc',$request->hocky)->groupBy('bckp_name','schools_name')->orderBy('schools_name')->get();
        // }
        return $json;
    }

    public function statisticByTotalView(Request $request){
        $title = '';
        $namhoc = $request->hocky.'-'.($request->hocky + 1);
        if($request->chedo == 'mghp'){
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ MIỄN GIẢM HỌC PHÍ';    
        }else if($request->chedo == 'cpht'){
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ CHI PHÍ HỌC TẬP';    
        }else if($request->chedo == 'htat'){
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ TIỀN ĂN TRƯA CHO TRẺ EM MẪU GIÁO';    
        }else if($request->chedo == 'htbt_tong'){
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ BÁN TRÚ';    
        }else if($request->chedo == 'htaths'){
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ TIỀN ĂN CHO HỌC SINH';    
        }else if($request->chedo == 'htkt_tong'){
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ HỌC SINH KHUYẾT TẬT';    
        }else if($request->chedo == 'hsdtts'){
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ HỌC SINH DÂN TỘC THIỂU SỐ';    
        }else if($request->chedo == 'hbhsdtnt'){
            $title = 'TỔNG HỢP KINH PHÍ HỖ TRỢ HỌC BỔNG HỌC SINH DÂN TỘC NỘI TRÚ';    
        }
        $levelUser = Auth::user()->level;
        $dt = '';
        if($levelUser == 2){
            $dt = 'TRƯỞNG PHÒNG GIÁO DỤC VÀ ĐÀO TẠO';
        }else if($levelUser == 3){
            $dt = 'TRƯỞNG PHÒNG TÀI CHÍNH';
        }
        return view('admin.baocao.elements._baocaotonghop', [
            'data' => json_decode($request->data),
            'title' => $title,
            'namhoc' => $namhoc,
            'school' => Auth::user()->last_name,
            'level_name' => $dt
        ]);
    }
}
