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
use File, datetime;
use App\Jobs\compilationProfile;
use App\Jobs\CapNhatCheDo;
use App\Helpers\tongHopChedo;
use App\Helpers\bussinessClass;

class HosohocsinhController extends Controller
{
    public function viewCreateHS()
    {
        return view('admin.hoso.hosohocsinh.createHoSoHS');
    }

    //Get permission
    public function getPermission($id)
    {
        $json = [];
        $val = [];
        $data = DB::select('SELECT pu.module_id,pu.permission_id FROM permission_users pu WHERE pu.role_user_id = ' . Auth::user()->id . ' and pu.module_id = ' . $id . '');
        foreach ($data as $key => $value) {
            $val[] = $value->permission_id . '';
        }
        $json['permission'] = $val;
        return $json;
    }

    public function getChangeSubject($id)
    {
        $p_id = DB::table('qlhs_profile')->where('profile_id', $id)->first();
        return view('admin.hoso.hosohocsinh.doituong', ['s_id' => $p_id->profile_school_id, 'c_id' => $p_id->profile_class_id, 'p_id' => $p_id->profile_name]);
    }
    //
    public function updateSubject()
    {
        return view('admin.hoso.hosohocsinh.updateRegistration', ['s_id' => '', 'c_id' => '', 'p_id' => '']);
    }
    public function getUpdateSubject($id)
    {
        $p_id = DB::table('qlhs_profile')->where('profile_id', $id)->first();
        return view('admin.hoso.hosohocsinh.updateRegistration', ['s_id' => $p_id->profile_school_id, 'c_id' => $p_id->profile_class_id, 'p_id' => $p_id->profile_name]);
    }

    // load doi tuong - use
    public function changeSubjectLoad(Request $rq)
    {
        $json = [];
        $start = $rq->input('start');
        $limit = $rq->input('limit');
        $keysearch = $rq->input('key');
        $type = $rq->input('TYPE');
        $order = $rq->input('ORDER');
        $year = $rq->year;
        $data = DB::table('qlhs_profile')
            ->leftJoin('qlhs_profile_subject', 'profile_id', 'profile_subject_profile_id')
            ->leftJoin('qlhs_subject', 'profile_subject_subject_id', 'subject_id')
            ->leftJoin('qlhs_class', 'class_id', 'profile_class_id')
            ->where('qlhs_profile_subject.active', 1)
            ->select(DB::raw('profile_name,profile_status,is_finish ,GROUP_CONCAT(subject_name) as subject_name,profile_id,profile_birthday,class_name,profile_start_time,profile_end_time'), 'start_date', 'end_date')
            ->groupBy('is_finish', 'profile_status', 'profile_name', 'profile_id', 'class_name', 'profile_start_time', 'profile_end_time', 'profile_birthday', 'start_date', 'end_date');

        $count = DB::table('qlhs_profile')
            ->leftJoin('qlhs_profile_subject', 'profile_id', 'profile_subject_profile_id')
            ->leftJoin('qlhs_subject', 'profile_subject_subject_id', 'subject_id')
            ->leftJoin('qlhs_class', 'class_id', 'profile_class_id')
            ->where('qlhs_profile_subject.active', 1)
            ->select(DB::raw('profile_name ,GROUP_CONCAT(subject_name) as subject_name,profile_id,profile_birthday,class_name,profile_start_time,profile_end_time'), 'start_date', 'end_date')
            ->groupBy('profile_name', 'profile_id', 'class_name', 'profile_start_time', 'profile_end_time', 'profile_birthday', 'start_date', 'end_date');
        if ($rq->schools_id == 0 || $rq->schools_id == null) {
            if (Auth::user()->truong_id != null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0') {
                $data = $data->whereIn('profile_school_id', explode('-', Auth::user()->truong_id));
                $count = $count->whereIn('profile_school_id', explode('-', Auth::user()->truong_id));
            }
        } else {
            $data = $data->where('profile_school_id', $rq->schools_id);
            $count = $count->where('profile_school_id', $rq->schools_id);
        }
        if ($keysearch != null && $keysearch != "") {
            $kw = bussinessClass::to_slug($keysearch);
            $data = $data->where(function ($q) use ($keysearch, $kw) {
                $q->orWhere('profile_name', 'LIKE', '%' . $keysearch . '%')
                    ->orWhere("profile_rewrite", "LIKE", "%" . $kw . "%")
                    ->orWhere('subject_name', 'LIKE', '%' . $keysearch . '%')
                    ->orWhere('class_name', 'LIKE', '%' . $keysearch . '%');
            });
            $count = $count->where(function ($q) use ($keysearch, $kw) {
                $q->orWhere('profile_name', 'LIKE', '%' . $keysearch . '%')
                    ->orWhere("profile_rewrite", "LIKE", "%" . $kw . "%")
                    ->orWhere('subject_name', 'LIKE', '%' . $keysearch . '%')
                    ->orWhere('class_name', 'LIKE', '%' . $keysearch . '%');
            });
        }

        if ($rq->class_id != null) {
            $data = $data->where('profile_class_id', $rq->class_id);
            $count = $count->where('profile_class_id', $rq->class_id);
        }
        $json['totalRows'] = DB::table(DB::raw("({$count->toSql()}) as m"))
            ->mergeBindings($count)->select(DB::raw('count(*) as total'))->first()->total;
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $or = 'asc';
        if ($order == 1) {
            $or = 'desc';
        }
        if ($type == 1) {
            $data = $data->orderBy('profile_name', $or);
        } else if ($type == 2) {
            $data = $data->orderBy('profile_birthday', $or);
        } else if ($type == 3) {
            $data = $data->orderBy('class_name', $or);
        } else if ($type == 4) {
            $data = $data->orderBy('subject_id', $or);
        } else if ($type == 5) {
            $data = $data->orderBy('start_date', $or);
        }
        $json['data'] = $data->orderBy('profile_id', 'desc')->orderBy('profile_name')
            ->orderBy('profile_start_time', 'desc')
            ->orderBy('qlhs_profile_subject.profile_subject_updatedate', 'desc')
            ->skip($start * $limit)->take($limit)->get();
        return $json;
    }
    public function updateSubjectLoad(Request $rq)
    {
        $json = [];
        $start = $rq->input('start');
        $limit = $rq->input('limit');
        $data = DB::table('qlhs_profile_subject')->leftJoin('qlhs_profile', 'profile_id', 'profile_subject_profile_id')->leftJoin('qlhs_subject', 'profile_subject_subject_id', 'subject_id')->leftJoin('qlhs_class', 'class_id', 'profile_class_id')->where('profile_school_id', $rq->schools_id)->where('profile_end_time', null)->select(DB::raw('profile_name ,GROUP_CONCAT(subject_name) as subject_name,profile_id,profile_birthday,class_name,profile_start_time'))->groupBy('profile_name', 'profile_id', 'class_name', 'profile_start_time', 'profile_birthday');
        //return $data->toSql();
        $count = DB::table('qlhs_profile_subject')->leftJoin('qlhs_profile', 'profile_id', 'profile_subject_profile_id')->leftJoin('qlhs_subject', 'profile_subject_subject_id', 'subject_id')->leftJoin('qlhs_class', 'class_id', 'profile_class_id')->where('profile_school_id', $rq->schools_id)->where('profile_end_time', null)->select('profile_id')->groupBy('profile_name', 'profile_id', 'class_name');
        if ($rq->class_id != null) {
            $count = $count->where('profile_class_id', $rq->class_id);
        }
        $json['totalRows'] = DB::table(DB::raw("({$count->toSql()}) as m"))->mergeBindings($count)->select(DB::raw('count(*) as total'))->first()->total;
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        if ($rq->class_id != null) {
            $json['data'] = $data->where('profile_class_id', $rq->class_id)->orderBy('qlhs_profile_subject.profile_subject_updatedate', 'desc')->skip($start * $limit)->take($limit)->get();
        } else {
            $json['data'] = $data->orderBy('qlhs_profile_subject.profile_subject_updatedate', 'desc')->skip($start * $limit)->take($limit)->get();
        }


        return $json;
    }
    public function getByProfile($time, $id)
    {
        $data['data'] = DB::table('qlhs_subject')
            ->leftJoin('qlhs_profile_subject', function ($q) use ($id, $time) {
                $q->on('profile_subject_subject_id', 'subject_id')
                    ->where('profile_subject_profile_id', $id)
                    ->where('profile_start_time', $time);
            })
            ->get();

        $data['type'] = DB::table('qlhs_profile')
            ->leftJoin('qlhs_schools_history', 'type_his_school_id', 'profile_school_id')
            ->where('profile_id', $id)
            ->select('type_his_type_id')->first();
        return $data;
    }
    //use
    public function load(Request $req)
    {
        try {
            $json = [];
            $start = $req->input('start');
            $limit = $req->input('limit');
            $id_truong = $req->input('id_truong');
            $id_lop = $req->input('id_lop');
            $keysearch = $req->input('key');
            $type = $req->input('TYPE');
            $order = $req->input('ORDER');
            $year = $req->year;

            $qlhs_profile = DB::table('qlhs_profile_history')
                ->leftJoin('qlhs_profile', 'qlhs_profile_history.history_profile_id', 'qlhs_profile.profile_id')
                ->leftJoin('qlhs_nationals', 'qlhs_profile.profile_nationals_id', 'qlhs_nationals.nationals_id')
                ->leftJoin('qlhs_site as huyen', 'huyen.site_id', 'qlhs_profile.profile_site_id2')
                ->leftJoin('qlhs_site as phuong', 'phuong.site_id', 'qlhs_profile.profile_site_id3')
                ->leftJoin('qlhs_site as thon', 'thon.site_id', 'qlhs_profile.profile_household')
                ->leftJoin('qlhs_class', 'qlhs_profile_history.history_class_id', 'qlhs_class.class_id')
                //  ->where('qlhs_nationals.nationals_active', 1)
                //  ->where('huyen.site_active', 1)
                ->where('qlhs_class.class_active', 1);

            if (Auth::user()->truong_id != null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0') { //explode('-',Auth::user()->truong_id)
                $qlhs_profile->whereIn('qlhs_profile.profile_school_id', explode('-', Auth::user()->truong_id));
            }
            if ($year != null && $year != "") {
                $qlhs_profile = $qlhs_profile->where('history_year', $year);
            }
            if ($id_truong != 0) {
                if ($id_lop != 0) {
                    $qlhs_profile->where('qlhs_profile_history.history_class_id', $id_lop);
                } else {
                    $qlhs_profile->where('qlhs_profile.profile_school_id', $id_truong);
                }
            }
            if ($keysearch != null && $keysearch != "") {
                $kw = bussinessClass::to_slug($keysearch);
                $qlhs_profile->where(function ($query) use ($keysearch, $kw) {
                    $query->orWhere("profile_code", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("profile_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("profile_rewrite", "LIKE", "%" . $kw . "%")
                        ->orWhere("profile_birthday", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("nationals_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("huyen.site_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("phuong.site_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("thon.site_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("profile_parentname", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("class_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("history_year", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("profile_year", "LIKE", "%" . $keysearch . "%");
                });
            }

            $json['startRecord'] = ($start);
            $json['numRows'] = $limit;
            $json['totalRows'] = $qlhs_profile->count();

            $or = 'asc';
            if ($order == 1) {
                $or = 'desc';
            }
            if ($type == 1) {
                $qlhs_profile = $qlhs_profile->orderBy('qlhs_profile.profile_name', $or);
            } else if ($type == 2) {
                $qlhs_profile = $qlhs_profile->orderBy('qlhs_profile.profile_birthday', $or);
            } else if ($type == 3) {
                $qlhs_profile = $qlhs_profile->orderBy('qlhs_nationals.nationals_name', $or);
            } else if ($type == 4) {
                $qlhs_profile = $qlhs_profile->orderBy('thon.site_name', $or);
            } else if ($type == 5) {
                $qlhs_profile = $qlhs_profile->orderBy('qlhs_profile.profile_parentname', $or)
                    ->orderBy('qlhs_profile.profile_guardian', $or);
            } else if ($type == 6) {
                $qlhs_profile = $qlhs_profile->orderBy('qlhs_class.class_name', $or);
            } else if ($type == 7) {
                $qlhs_profile = $qlhs_profile->orderBy('qlhs_profile_history.history_year', $or);
            } else if ($type == 8) {
                $qlhs_profile = $qlhs_profile->orderBy('qlhs_profile.profile_year', $or);
            } else if ($type == 9) {
                $qlhs_profile = $qlhs_profile->orderBy('qlhs_profile.profile_leaveschool_date', $or);
            }
            $json['data'] = $qlhs_profile->select('qlhs_profile.profile_id', 'qlhs_profile.profile_code', 'qlhs_profile.profile_name', 'qlhs_profile.profile_birthday', 'thon.site_name as profile_household', 'qlhs_profile.profile_parentname', 'qlhs_profile.profile_status', 'qlhs_profile.profile_year', 'qlhs_profile.profile_leaveschool_date', 'qlhs_nationals.nationals_name', 'huyen.site_name as huyen', 'phuong.site_name as phuong', 'qlhs_class.class_name', 'qlhs_profile_history.history_year', 'qlhs_profile.profile_guardian', 'history_upto_level', 'p_His_enddate', 'P_His_startdate')
                ->orderBy('qlhs_profile.profile_id', 'desc')
                ->orderBy('qlhs_class.class_name')
                ->skip($start * $limit)->take($limit)->get();
            return $json;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getView1()
    {
        return view('admin.hoso.lapdanhsach.miengiamhocphi');
        // /return view('category/wards')->with('wards', $wards);
    }

    public function exportExcel($jsonData)
    {
        $schools_id = 0; //$request->input('SCHOOLID');
        $class_id = 0; //$request->input('CLASSID');
        $keysearch = ""; //$request->input('KEY');
        $year = ""; //$request->input('KEY');

        $my_array_data = json_decode($jsonData, TRUE);

        foreach ($my_array_data as $key => $value) {
            switch ($key) {
                case 'SCHOOLID':
                    $schools_id = $value;
                    break;
                case 'CLASSID':
                    $class_id = $value;
                    break;
                case 'KEY':
                    $keysearch = $value;
                    break;
                case 'year':
                    $year = $value;
                    break;
                default:
                    # code...
                    break;
            }
        }
        try {
            $data_results = [];

            $qlhs_profile = DB::table('qlhs_profile_history')
                ->leftJoin('qlhs_profile', 'qlhs_profile_history.history_profile_id', 'qlhs_profile.profile_id')
                ->leftJoin('qlhs_nationals', 'qlhs_profile.profile_nationals_id', 'qlhs_nationals.nationals_id')
                ->leftJoin('qlhs_site as huyen', 'huyen.site_id', 'qlhs_profile.profile_site_id2')
                ->leftJoin('qlhs_site as phuong', 'phuong.site_id', 'qlhs_profile.profile_site_id3')
                ->leftJoin('qlhs_site as thon', 'thon.site_id', 'qlhs_profile.profile_household')
                ->leftJoin('qlhs_class', 'qlhs_profile_history.history_class_id', 'qlhs_class.class_id')
                ->leftJoin('qlhs_schools', 'qlhs_profile.profile_school_id', 'qlhs_schools.schools_id')
                // ->leftJoin(DB::raw('(SELECT history_profile_id,max(history_year) history_year,max(history_upto_level) history_upto_level from qlhs_profile_history
                //     GROUP BY history_profile_id) as qlhs_profile_history '),'qlhs_profile_history.history_profile_id', '=', 'qlhs_profile.profile_id')
                ->where('qlhs_nationals.nationals_active', 1)
                ->where('huyen.site_active', 1)
                ->where('qlhs_class.class_active', 1);

            if (Auth::user()->truong_id != null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0') { //explode('-',Auth::user()->truong_id)
                $qlhs_profile->whereIn('qlhs_profile.profile_school_id', explode('-', Auth::user()->truong_id));
            }
            if ($year != null && $year != "") {
                $qlhs_profile = $qlhs_profile->where('history_year', $year);
            }
            if ($schools_id != 0) {
                if ($class_id != 0) {
                    $qlhs_profile->where('qlhs_profile_history.history_class_id', $class_id);
                } else {
                    $qlhs_profile->where('qlhs_profile.profile_school_id', $schools_id);
                }
            }
            if ($keysearch != null && $keysearch != "") {
                $qlhs_profile->where(function ($query) use ($keysearch) {
                    $query->where("profile_code", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("profile_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("profile_birthday", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("nationals_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("huyen.site_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("phuong.site_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("thon.site_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("profile_parentname", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("class_name", "LIKE", "%" . $keysearch . "%")
                        ->orWhere("history_year", "=", "%" . $keysearch . "%")
                        ->orWhere("profile_year", "=", "%" . $keysearch . "%");
                });
            }
            $data_results = $qlhs_profile->select('qlhs_profile.profile_id', 'qlhs_profile.profile_code', 'qlhs_profile.profile_name', 'qlhs_profile.profile_birthday', 'thon.site_name as profile_household', 'qlhs_profile.profile_parentname', 'qlhs_profile.profile_status', 'qlhs_profile.profile_year', 'qlhs_profile.profile_leaveschool_date', 'qlhs_nationals.nationals_name', 'huyen.site_name as tenhuyen', 'phuong.site_name as tenxa', 'qlhs_class.class_name', 'qlhs_profile_history.history_year', 'qlhs_profile.profile_guardian', 'qlhs_profile.profile_school_id', 'qlhs_schools.schools_name', 'qlhs_profile.profile_bantru', 'profile_giaothong', 'profile_km', 'profile_statusNQ57')->orderBy('qlhs_profile.updated_at', 'desc')->get();
            $this->addCellExcel($data_results, 'Hồ sơ học sinh' . '_' . Auth::user()->username, FALSE);
        } catch (Exception $e) {
            return $e;
        }
    }

    private function addCellExcel($data_results, $filename, $type = true)
    {
        $excel =    Excel::load(storage_path() . '/exceltemplate/hosohocsinh.xlsx', function ($reader) use ($data_results) {
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
            foreach ($data_results as $value) {
                $row++;
                $indexa++;
                //if ($school->schools_id == $value->profile_school_id) {

                $reader->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $indexa)->getStyle('A' . $row)->applyFromArray($borderArray)->applyFromArray($style); // SO THU TU
                $reader->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $value->profile_name)->getStyle('B' . $row)->applyFromArray($borderArray)->applyFromArray($styleLeft); // TEN HOC SINH
                if ($value->profile_statusNQ57 != null && $value->profile_statusNQ57 != 0) {
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'x')->getStyle('C' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                }

                $reader->getActiveSheet()->setCellValueByColumnAndRow(3, $row, Carbon::parse($value->profile_year)->format('d-m-Y'))->getStyle('D' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                $reader->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $value->schools_name)->getStyle('E' . $row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                $reader->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $value->class_name)->getStyle('F' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                $reader->getActiveSheet()->setCellValueByColumnAndRow(6, $row, Carbon::parse($value->profile_birthday)->format('d-m-Y'))->getStyle('G' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                $reader->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $value->profile_guardian)->getStyle('H' . $row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                $reader->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $value->nationals_name)->getStyle('I' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                $reader->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $value->profile_household)->getStyle('J' . $row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                $reader->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $value->tenxa)->getStyle('K' . $row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                $reader->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $value->tenhuyen)->getStyle('L' . $row)->applyFromArray($borderArray)->applyFromArray($styleLeft);
                $reader->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $value->profile_parentname)->getStyle('M' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                $subject = DB::table('qlhs_profile_subject')->where('profile_subject_profile_id', $value->profile_id)->where(function ($q) {
                    $q->whereNull('end_year')->orWhere('start_year', '>=', Carbon::now()->format('Y'));
                })->select('profile_subject_subject_id')->get();
                foreach ($subject as $key => $val) {
                    $sub = $val->profile_subject_subject_id;
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(13, $row, '')->getStyle('N' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(14, $row, '')->getStyle('O' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(15, $row, '')->getStyle('P' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(16, $row, '')->getStyle('Q' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(17, $row, '')->getStyle('R' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(18, $row, '')->getStyle('S' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(19, $row, '')->getStyle('T' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(20, $row, '')->getStyle('U' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(21, $row, '')->getStyle('V' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(22, $row, '')->getStyle('W' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(23, $row, '')->getStyle('X' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(24, $row, '')->getStyle('Y' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    if ((int) $sub == 35) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(13, $row, 'x')->getStyle('N' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 74) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(14, $row, 'x')->getStyle('O' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 41) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(15, $row, 'x')->getStyle('P' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 38) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(16, $row, 'x')->getStyle('Q' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 28) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(17, $row, 'x')->getStyle('R' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 73) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(18, $row, 'x')->getStyle('S' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 39) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(19, $row, 'x')->getStyle('T' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 49) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(20, $row, 'x')->getStyle('U' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 34) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(21, $row, 'x')->getStyle('V' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 26) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(22, $row, 'x')->getStyle('W' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 40) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(23, $row, 'x')->getStyle('X' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ((int) $sub == 46) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(24, $row, 'x')->getStyle('Y' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    }
                }
                if ($value->profile_bantru != null && $value->profile_bantru != '') {
                    if ($value->profile_bantru == 0) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(25, $row, 'x')->getStyle('Z' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(26, $row, '')->getStyle('AA' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    } else if ($value->profile_bantru == 1) {
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(25, $row, '')->getStyle('Z' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                        $reader->getActiveSheet()->setCellValueByColumnAndRow(26, $row, 'x')->getStyle('AA' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    }
                } else {
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(25, $row, '')->getStyle('Z' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(26, $row, '')->getStyle('AA' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                }
                if ($value->profile_km == 0) {
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(27, $row, '')->getStyle('AB' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                } else {
                    $reader->getActiveSheet()->setCellValueByColumnAndRow(27, $row, $value->profile_km)->getStyle('AB' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                }

                $reader->getActiveSheet()->setCellValueByColumnAndRow(28, $row, $value->profile_giaothong)->getStyle('AC' . $row)->applyFromArray($borderArray)->applyFromArray($style);
            }
        });
        if ($type) {
            return $excel->setFilename($filename)->store('xlsx', storage_path() . '/excel/MGHP');
        } else {
            return $excel->setFilename($filename)->download('xlsx');
        }
    }

    // thêm mới học sinh trên form - use
    public function insertHoSoHocSinh(Request $request)
    {
        $results = [];
        try {
            if ($request->input("PROFILENAME") == null || $request->input("PROFILENAME") == "") {
                $results['error'] = 'Xin mời nhập tên học sinh!';
                return  $results;
            } else if (strlen($request->input("PROFILENAME")) > 101) {
                $results['error'] = 'Tên học sinh không quá 100 ký tự!';
                return  $results;
            }
            $profile_name = trim($request->input("PROFILENAME"));
            if ($request->input("PROFILEBIRTHDAY") == null || $request->input("PROFILEBIRTHDAY") == "") {
                $results['error'] = 'Xin mời nhập ngày sinh!';
                return  $results;
            }
            $profile_birthday = Carbon::parse($request->input("PROFILEBIRTHDAY"))->format('Y-m-d');

            if ($request->input("PROFILENATIONALID") == null || $request->input("PROFILENATIONALID") == "") {
                $results['error'] = 'Xin mời nhập dân tộc!';
                return  $results;
            }
            $profile_nationals_id = $request->input("PROFILENATIONALID");
            if ($request->input("PROFILESITE1") == null || $request->input("PROFILESITE1") == "" || $request->input("PROFILESITE2") == null || $request->input("PROFILESITE2") == "" || $request->input("PROFILESITE3") == null || $request->input("PROFILESITE3") == "") {
                $results['error'] = 'Xin mời nhập tỉnh-quận/huyện-phường/xã!';
                return  $results;
            }

            $profile_site_id1 = $request->input("PROFILESITE1");
            $profile_site_id2 = $request->input("PROFILESITE2");
            $profile_site_id3 = $request->input("PROFILESITE3");
            $profile_targetid = $request->input("targetsId");

            $profile_household = $request->input("PROFILEHOUSEHOLD") ? $request->input("PROFILEHOUSEHOLD") : null;
            $profile_guardian = $request->input("profile_guardian");

            $profile_parentname = trim($request->input("PROFILEPARENTNAME"));
            if ($request->input("PROFILEYEAR") == null || $request->input("PROFILEYEAR") == "") {
                $results['error'] = 'Xin mời nhập năm học!';
                return  $results;
            }
            $profile_year = Carbon::parse("01" . "-" . (string) $request->input("PROFILEYEAR"));
            if ($request->input("PROFILESCHOOLID") == null || $request->input("PROFILESCHOOLID") == "") {
                $results['error'] = 'Xin mời nhập trường học!';
                return  $results;
            }
            $profile_school_id = $request->input("PROFILESCHOOLID");
            if ($request->input("PROFILECLASSID") == null || $request->input("PROFILECLASSID") == "") {
                $results['error'] = 'Xin mời nhập lớp học!';
                return  $results;
            }
            $profile_class_id = $request->input("PROFILECLASSID");

            $profile_status = $request->input("PROFILESTATUS");

            $profile_statusNQ57  = $request->input("PROFILESTATUSNQ57");

            $profile_leaveschool_date = null;
            $arrDecided = array();
            $arrDecided = $request->input("ARRDECIDED");


            $profile_bantru = (int) $request->input("PROFILEBANTRU");
            $profile_KM = $request->input("PROFILEKM") ? $request->input("PROFILEKM") : 0;
            $profile_giaothong = $request->input("PROFILEGIAOTHONG");
            if ($profile_status == 1) {
                $profile_leaveschool_date = $request->input("PROFILELEAVESCHOOLDATE");
                $profile_leaveschool_date = Carbon::parse($profile_leaveschool_date);
            }

            $arrSubjectID = $request->input("ARRSUBJECTID") != "" ? explode('-', $request->input("ARRSUBJECTID")) : [];
            // $val74 = array_search(74, $arrSubjectID) != null || array_search(74, $arrSubjectID) >= 0 ? array_search(74, $arrSubjectID) : -1;
            // $val41 = array_search(41, $arrSubjectID) != null || array_search(41, $arrSubjectID) >= 0 ? array_search(41, $arrSubjectID) : -1;

            // $val34 = array_search(34, $arrSubjectID) != null || array_search(34, $arrSubjectID) >= 0 ? array_search(34, $arrSubjectID) : -1;
            // $val49 = array_search(49, $arrSubjectID) != null || array_search(49, $arrSubjectID) >= 0 ? array_search(49, $arrSubjectID) : -1;\
            $val74 = -1;
            $val41 = -1;
            $val34 = -1;
            $val49 = -1;
            foreach ($arrSubjectID as $key => $value) {
                if ($value == 74) {
                    $val74 = 74;
                }
                if ($value == 41) {
                    $val41 = 41;
                }
                if ($value == 34) {
                    $val34 = 34;
                }
                if ($value == 49) {
                    $val49 = 49;
                }
            }
            if ($val74 >= 0 && $val41 >= 0) {
                unset($arrSubjectID[array_search(74, $arrSubjectID)]);
                unset($arrSubjectID[array_search(41, $arrSubjectID)]);
                array_push($arrSubjectID, 100);
            }
            if ($val34 >= 0 && $val49 >= 0) {
                unset($arrSubjectID[array_search(34, $arrSubjectID)]);
                unset($arrSubjectID[array_search(49, $arrSubjectID)]);
                array_push($arrSubjectID, 101);
            }
            //return $arrSubjectID;
            $parentSTT = $request->input("PROFILEPARENTSTT");

            $qlhs_schools = DB::table('qlhs_schools')
                ->leftJoin('qlhs_schools_history', 'schools_id', 'type_his_school_id')
                ->where('schools_active', 1)
                ->where('type_his_startdate', '<=', DB::raw('NOW()'))
                ->where(function ($q) {
                    $q->orWhere('type_his_enddate', '>=', DB::raw('NOW()'))
                        ->orWhere('type_his_enddate', null);
                })
                ->where('type_his_school_id', $profile_school_id)
                ->select('qlhs_schools.schools_id', 'qlhs_schools.schools_name', 'qlhs_schools.schools_unit_id', 'qlhs_schools_history.type_his_type_id')
                ->first();
            if ($qlhs_schools->type_his_type_id == 4) {
                array_push($arrSubjectID, 70);
                if ($profile_statusNQ57 > 0) {
                    $results['error'] = 'Trường nội trú không hưởng nghị quyết 57.';
                    return  $results;
                }
            }

            if (count($arrSubjectID) == 0) {
                array_push($arrSubjectID, 102);
            }
            //  return $arrSubjectID;
            $bool = TRUE;
            $currentuser_id = Auth::user()->id;
            $currentdate = Carbon::now();

            $strYear = substr((string) $profile_year, 0, 4);
            $year_his = (int) $strYear;
            $strYear = (string) $year_his . "-" . (string) ($year_his + 1);

            $getbyProfile_Code = DB::table('qlhs_profile')
                ->where('profile_name', $profile_name)
                ->where('profile_birthday', $profile_birthday)
                ->where('profile_nationals_id', $profile_nationals_id)
                ->where('profile_site_id1', $profile_site_id1)
                ->where('profile_site_id2', $profile_site_id2)
                ->where('profile_site_id3', $profile_site_id3)
                ->where('profile_household', $profile_household)
                ->where('profile_parentname', $profile_parentname)
                ->where('profile_school_id', $profile_school_id)
                ->where('profile_class_id', $profile_class_id)->count();

            if ($getbyProfile_Code > 0 && $request->value_exist == 0) {
                $results['warning'] = 'Học sinh đã tồn tại, vui lòng nhập lại!';
                return $results;
            } else {
                //Insert Hồ sơ học sinh
                $hosohocsinh = new DanhMucHoSoHocSinh(); // them moi hoc sinh
                $hosohocsinh->profile_name = $profile_name;
                $hosohocsinh->profile_birthday = $profile_birthday;
                $hosohocsinh->profile_parentstt = $parentSTT;
                $hosohocsinh->profile_nationals_id = $profile_nationals_id;
                $hosohocsinh->profile_site_id1 = $profile_site_id1;
                $hosohocsinh->profile_site_id2 = $profile_site_id2;
                $hosohocsinh->profile_site_id3 = $profile_site_id3;
                $hosohocsinh->profile_household = $profile_household;
                $hosohocsinh->profile_parentname = $profile_parentname;
                $hosohocsinh->profile_year = $profile_year;
                $hosohocsinh->profile_school_id = $profile_school_id;
                $hosohocsinh->profile_class_id = $profile_class_id;
                $hosohocsinh->profile_status = $profile_status;
                $hosohocsinh->profile_statusNQ57 = $profile_statusNQ57;
                $hosohocsinh->profile_leaveschool_date = $profile_leaveschool_date;
                $hosohocsinh->profile_bantru = $profile_bantru;
                $hosohocsinh->profile_create_userid = $currentuser_id;
                $hosohocsinh->created_at = $currentdate;
                $hosohocsinh->profile_update_userid = $currentuser_id;
                $hosohocsinh->updated_at = $currentdate;
                $hosohocsinh->profile_km = $profile_KM;
                $hosohocsinh->profile_giaothong = $profile_giaothong;
                $hosohocsinh->profile_guardian = $profile_guardian;
                $hosohocsinh->profile_rewrite = bussinessClass::to_slug($profile_name);
                // $hosohocsinh->target_eat = $profile_targetid != '' ? $profile_targetid : null;
                if ($profile_targetid > 0) {
                    $hosohocsinh->target_eat = $profile_targetid != '' && $profile_targetid != null ? $profile_targetid : null; //$profile_targetid;
                } else {
                    $hosohocsinh->target_eat = null;
                }
                $hosohocsinh->save();

                $insertGetIdProfile = $hosohocsinh->profile_id;

                // lich su ho khau
                $his_site = new HoKhauHSHocSinh();
                $his_site->p_id = $insertGetIdProfile;
                $his_site->class_id = $profile_class_id;
                $his_site->site_tinh = $profile_site_id1;
                $his_site->site_quanhuyen = $profile_site_id2;
                $his_site->site_phuongxa = $profile_site_id3;
                $his_site->site_thon = $profile_household;
                $his_site->start_date = $profile_year;
                $his_site->save();

                if ($his_site->id <= 0 || $his_site->id == null) {
                    DanhMucHoSoHocSinh::where('profile_id', $insertGetIdProfile)->delete();
                    $results['error'] = "Lỗi lưu hộ khẩu.Xin mời thử lại!";
                    return  $results;
                }
                if ($insertGetIdProfile > 0) {
                    // Lấy thông tin lớp của học sinh
                    $getLevelClass = DB::table('qlhs_level')
                        ->join('qlhs_class', 'qlhs_class.class_level_id', 'qlhs_level.level_id')
                        ->where('qlhs_class.class_id', $profile_class_id)
                        ->select('level_level', 'level_next', 'level_next_1', 'level_next_2', 'level_name', 'class_old', 'class_new', 'class_unit_id')
                        ->first();

                    //Insert Hoso_History (NOW)
                    $insert_history = new LichSuHSHocSinh();
                    $insert_history->history_class_id = $profile_class_id;
                    $insert_history->unit_class = $getLevelClass->class_unit_id;
                    $insert_history->history_school_id = $profile_school_id;
                    $insert_history->history_profile_id = $insertGetIdProfile;
                    $insert_history->history_parentstt = $parentSTT;
                    $insert_history->history_year = $strYear;
                    $insert_history->history_upto_level = 0;
                    $insert_history->level_old = '';
                    $insert_history->level_new = $getLevelClass->class_new;
                    $insert_history->level_cur = $getLevelClass->level_name;
                    $insert_history->history_update_user_id = Auth::user()->id;
                    $insert_history->history_update_date = $currentdate;
                    $insert_history->nationals_id = $profile_nationals_id;
                    $insert_history->P_His_startdate = $profile_year;
                    $insert_history->history_statute_57 = $profile_statusNQ57;
                    if ($profile_statusNQ57 > 0) {
                        $insert_history->history_statute_116 = null;
                    } else {
                        $insert_history->history_statute_116 = $profile_bantru;
                    }
                    $insert_history->p_His_enddate = ($year_his + 1) . '-06-01';
                    $insert_history->save();

                    //Insert Hoso_History (NEW)
                    if ($getLevelClass->class_new != null && $getLevelClass->class_new != "") {
                        $randomclass = DB::select("select get_class_random(" . $profile_school_id . "," . ($getLevelClass->level_level + 1) . ") AS result");
                        $insert_history_new = new LichSuHSHocSinh();
                        $insert_history_new->history_class_id = $randomclass[0] != null ? $randomclass[0]->result : null;
                        $insert_history_new->unit_class = $getLevelClass->class_unit_id;
                        $insert_history_new->history_school_id = $profile_school_id;
                        $insert_history_new->history_profile_id = $insertGetIdProfile;
                        $insert_history_new->history_parentstt = $parentSTT;
                        $insert_history_new->history_year = ($year_his + 1) . '-' . ($year_his + 2);
                        $insert_history_new->history_statute_57 = $profile_statusNQ57;
                        if ($profile_statusNQ57 > 0) {
                            $insert_history_new->history_statute_116 = null;
                        } else {
                            $insert_history_new->history_statute_116 = $profile_bantru;
                        }
                        $insert_history_new->history_upto_level = 0;
                        $insert_history_new->level_old = $getLevelClass->level_name;
                        $insert_history_new->level_new = '';
                        $insert_history_new->level_cur = $getLevelClass->class_new;
                        $insert_history_new->history_update_user_id = Auth::user()->id;
                        $insert_history_new->history_update_date = $currentdate;
                        $insert_history_new->P_His_startdate = ($year_his + 1) . '-06-01';
                        $insert_history_new->p_His_enddate = ($year_his + 2) . '-06-01';
                        $insert_history_new->save();
                    }
                    if ($insert_history->history_id <= 0) {
                        HoKhauHSHocSinh::where('p_id', $insertGetIdProfile)->delete();
                        DanhMucHoSoHocSinh::where('profile_id', $insertGetIdProfile)->delete();
                        $results['error'] = "Lỗi lưu học sinh.Xin mời thử lại!";
                        return  $results;
                    }
                    $date  = Carbon::now()->timestamp;

                    if (count($arrSubjectID) > 0) {

                        foreach ($arrSubjectID as $value) {
                            $subject_id = (int) $value;
                            $insert_hosohs_subject = new DoiTuongHSHocSinh();
                            $insert_hosohs_subject->profile_subject_profile_id = $insertGetIdProfile;
                            $insert_hosohs_subject->profile_subject_subject_id = $subject_id;
                            $insert_hosohs_subject->profile_subject_create_userid = $currentuser_id;
                            $insert_hosohs_subject->profile_subject_update_userid = $currentuser_id;
                            $insert_hosohs_subject->profile_subject_createdate = $currentdate;
                            $insert_hosohs_subject->profile_subject_updatedate = $currentdate;
                            $insert_hosohs_subject->profile_start_time = $date;
                            $insert_hosohs_subject->is_finish = 1;
                            $insert_hosohs_subject->start_date = $profile_year;
                            $insert_hosohs_subject->start_year = substr((string) $profile_year, 0, 4);
                            $insert_hosohs_subject->save();

                            if ($insert_hosohs_subject->profile_subject_id <= 0) {
                                $bool = FALSE;
                                $deleteProfileHis = LichSuHSHocSinh::where('history_profile_id', $insertGetIdProfile)->delete();
                                $deleteProfileSub = DoiTuongHSHocSinh::where('profile_subject_profile_id', $insertGetIdProfile)->delete();
                                $deleteProfileDec = DB::table('qlhs_decided')
                                    ->where('decided_profile_id', $insertGetIdProfile)->delete();
                                HoKhauHSHocSinh::where('p_id', $insertGetIdProfile)->delete();
                                DanhMucHoSoHocSinh::where('profile_id', $insertGetIdProfile)->delete();
                                $results['error'] = 'Thêm mới thất bại!';
                                return $results;
                            }
                        }
                    }
                    if ($insert_history->history_id > 0) {
                        // kiểm tra học sinh thuộc huyện trạm tấu hoặc mù căng chải
                        if ($profile_site_id2 == 100 || $profile_site_id2 == 101) {
                            $end_date_exe = $currentdate->addYears(2)->format('Y-m-d');
                            DB::statement("call year_month_table_by_profile(" . $insertGetIdProfile . ",'" . $end_date_exe . "')");
                        }
                        $run_now =  tongHopChedo::tongHop_Truong($year_his, $insertGetIdProfile, $profile_school_id);
                        $run_new =  tongHopChedo::tongHop_Truong(($year_his + 1), $insertGetIdProfile, $profile_school_id);
                        $results['success'] = $run_now;
                    } else {
                        $deleteProfileHis = DB::table('qlhs_profile_history')
                            ->where('history_profile_id', $insertGetIdProfile)->delete();
                        $deleteProfileSub = DB::table('qlhs_profile_subject')
                            ->where('profile_subject_profile_id', $insertGetIdProfile)->delete();
                        $deleteProfileDec = DB::table('qlhs_decided')
                            ->where('decided_profile_id', $insertGetIdProfile)->delete();
                        DB::table('history_profile_site')
                            ->where('p_id', $insertGetIdProfile)->delete();
                        DanhMucHoSoHocSinh::where('profile_id', $insertGetIdProfile)->delete();
                        $results['error'] = 'Thêm mới thất bại!';
                    }
                    $num = (int) $request->input("decided_number");
                    if ($num > 0) {

                        $dir = storage_path() . '/HOSO/QUYETDINH';
                        $filename_attach  = "";
                        $getTime = time();
                        for ($i = 0; $i < $num; $i++) {
                            // format date
                            $files = $request->file('file_' . $i);
                            $filename_attach  = $request->input('fileold_' . $i);
                            $decided_confirmdate = $request->input('confirmdate_' . $i) != "" ? Carbon::parse($request->input('confirmdate_' . $i)) : null;
                            if (trim($files) != "") {
                                $filename_attach = 'QD_' . $insertGetIdProfile . '_' . Auth::user()->id . '_' . $getTime . '_' . $files->getClientOriginalName();
                                if (file_exists($dir . '/' . $filename_attach)) {
                                    $files->move($dir, $filename_attach . '-' . $getTime);
                                } else {
                                    $files->move($dir, $filename_attach);
                                }
                            }
                            $insert_diceded = DB::table('qlhs_decided')
                                ->insert([
                                    'decided_type' => $request->input('decided_type_' . $i),
                                    'decided_profile_id' => $insertGetIdProfile,
                                    'decided_code' => $request->input('code_' . $i),
                                    'decided_name' => $request->input('name_' . $i),
                                    'decided_number' => $request->input('number_' . $i),
                                    'decided_confirmation' =>  $request->input('confirmation_' . $i),
                                    'decided_confirmdate' => $decided_confirmdate,
                                    'decided_filename' => $filename_attach,
                                    'decided_user_id' => $currentuser_id,
                                    'decided_createdate' => $currentdate,
                                    'decided_updatedate' => $currentdate
                                ]);
                        }
                    }
                }
            }

            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }
    // Tải tài liệu của học sinh - use
    public function download_quyetdinh($id)
    {
        $data = DB::table('qlhs_decided')->where('decided_id', $id)
            ->select('decided_filename')->first();
        $dir = storage_path() . '/HOSO/QUYETDINH/' . $data->decided_filename;
        return response()->download($dir, $data->decided_filename);
    }

    // Cập nhật hồ sơ học sinh - use
    public function updateHoSoHocSinh(Request $request)
    {

        $results = [];
        try {
            $profile_id = $request->input("PROFILEID");
            if ($request->input("PROFILENAME") == null || $request->input("PROFILENAME") == "") {
                $results['error'] = 'Xin mời nhập tên học sinh!';
                return  $results;
            } else if (strlen($request->input("PROFILENAME")) > 101) {
                $results['error'] = 'Tên học sinh không quá 100 ký tự!';
                return  $results;
            }
            $profile_name = trim($request->input("PROFILENAME"));
            if ($request->input("PROFILEBIRTHDAY") == null || $request->input("PROFILEBIRTHDAY") == "") {
                $results['error'] = 'Xin mời nhập ngày sinh!';
                return  $results;
            }
            $profile_birthday = Carbon::parse($request->input("PROFILEBIRTHDAY"));

            if ($request->input("PROFILENATIONALID") == null || $request->input("PROFILENATIONALID") == "") {
                $results['error'] = 'Xin mời nhập dân tộc!';
                return  $results;
            }
            $profile_nationals_id = $request->input("PROFILENATIONALID");
            if ($request->input("PROFILESITE1") == null || $request->input("PROFILESITE1") == "" || $request->input("PROFILESITE2") == null || $request->input("PROFILESITE2") == "" || $request->input("PROFILESITE3") == null || $request->input("PROFILESITE3") == "") {
                $results['error'] = 'Xin mời nhập tỉnh-quận/huyện-phường/xã!';
                return  $results;
            }
            $profile_site_id1 = $request->input("PROFILESITE1");
            $profile_site_id2 = $request->input("PROFILESITE2");
            $profile_site_id3 = $request->input("PROFILESITE3");
            $profile_targetid = $request->input("targetsId");
            $parentSTT = $request->input("PROFILEPARENTSTT");
            $profile_household = $request->input("PROFILEHOUSEHOLD") ? $request->input("PROFILEHOUSEHOLD") : "";
            $profile_guardian = $request->input("profile_guardian") ? $request->input("profile_guardian") : "";
            $profile_parentname = trim($request->input("PROFILEPARENTNAME"));
            if ($request->input("PROFILEYEAR") == null || $request->input("PROFILEYEAR") == "") {
                $results['error'] = 'Xin mời nhập năm học!';
                return  $results;
            }
            $profile_year = Carbon::parse("01" . "-" . (string) $request->input("PROFILEYEAR"));

            $profile_statusNQ57  = $request->input("PROFILESTATUSNQ57");
            if ($request->input("PROFILESCHOOLID") == null || $request->input("PROFILESCHOOLID") == "") {
                $results['error'] = 'Xin mời nhập trường học!';
                return  $results;
            }
            $profile_school_id = $request->input("PROFILESCHOOLID");
            if ($request->input("PROFILECLASSID") == null || $request->input("PROFILECLASSID") == "") {
                $results['error'] = 'Xin mời nhập lớp học!';
                return  $results;
            }
            $profile_class_id = $request->input("PROFILECLASSID");

            $profile_status = $request->input("PROFILESTATUS"); //trạng thái nghỉ học
            if ((int) $profile_status == 1) {
                $profile_leaveschool_date = $request->input("PROFILELEAVESCHOOLDATE");
                if (!is_null($profile_leaveschool_date) && !empty($profile_leaveschool_date)) {
                    $profile_leaveschool_date = Carbon::parse($profile_leaveschool_date);
                } else {
                    $profile_leaveschool_date = null;
                }
            } else {
                $profile_leaveschool_date = null;
            }

            $arrSubjectID = explode('-', $request->input("ARRSUBJECTID")); //tổng hợp đối tượng

            $arrDecided = $request->input("ARRDECIDED"); // tổng hợp file đính kèm

            $profile_bantru = (int) $request->input("PROFILEBANTRU"); // bán trú 116
            $profile_KM = $request->input("PROFILEKM") ? $request->input("PROFILEKM") : 0;
            $profile_giaothong = $request->input("PROFILEGIAOTHONG");
            $profile_currentYear = (int) $request->input("CURRENTYEAR");
            $historyid = $request->input("HISTORYID");

            $currentuser_id = Auth::user()->id;
            $currentdate = Carbon::now();

            $strYear = substr((string) $profile_year, 0, 4);
            $year_his = (int) $strYear;
            $strYear = (string) $year_his . "-" . (string) ($year_his + 1);

            $selectCountHis = LichSuHSHocSinh::where('history_profile_id', $profile_id)
                ->where('history_school_id', Auth::user()->truong_id)
                ->where('history_id', $historyid)->count();

            // Kiểm tra học sinh và quyền học sinh
            if ($selectCountHis > 0) {
                $p = DanhMucHoSoHocSinh::find($profile_id);
                $p->profile_name =  $request->input("PROFILENAME");
                $p->profile_birthday = Carbon::parse($request->input("PROFILEBIRTHDAY"))->format('Y-m-d'); //Ngày sinh
                $p->profile_km = $profile_KM;
                $p->profile_giaothong = $profile_giaothong;
                $p->profile_guardian = $profile_guardian;
                $p->profile_parentname = $profile_parentname;
                $p->profile_nationals_id = $profile_nationals_id;
                // $p->target_eat = $profile_targetid != '' ? $profile_targetid : null;//$profile_targetid;
                if ($profile_targetid > 0) {
                    $p->target_eat = $profile_targetid != '' && $profile_targetid != null ? $profile_targetid : null; //$profile_targetid;
                } else {
                    $p->target_eat = null;
                }
                $p->save();
                $his_profile = LichSuHSHocSinh::where('history_profile_id', $profile_id)
                    ->where('history_school_id', Auth::user()->truong_id)
                    ->where('history_id', $historyid)->first();
                //  return $his_profile;
                $getLevelClass = DB::table('qlhs_level')
                    ->join('qlhs_class', 'qlhs_class.class_level_id', 'qlhs_level.level_id')
                    ->where('qlhs_class.class_id', $profile_class_id)
                    ->select('level_level', 'level_next', 'level_next_1', 'level_next_2', 'level_name', 'class_old', 'class_new')
                    ->first();
                if ($his_profile != null) {
                    if ($profile_year != null && $profile_year != "") {
                        $his_profile->history_class_id = $profile_class_id;
                        $his_profile->history_year = $strYear;
                        $his_profile->history_update_user_id = $currentuser_id;
                        $his_profile->history_update_date = $currentdate;
                        $his_profile->history_parentstt = $parentSTT;
                        $his_profile->parent_name = $profile_parentname;
                        $his_profile->guardian = $profile_guardian;
                        $his_profile->nationals_id = $profile_nationals_id;
                        $his_profile->level_old = '';
                        $his_profile->level_new = $getLevelClass->class_new;
                        $his_profile->level_cur = $getLevelClass->level_name;
                        $his_profile->P_His_startdate = $profile_year;
                        $his_profile->history_statute_57 = $profile_statusNQ57;
                        if ($profile_statusNQ57 > 0) {
                            $his_profile->history_statute_116 = null;
                        } else {
                            $his_profile->history_statute_116 = $profile_bantru;
                        }
                        $his_profile->p_His_enddate = ($year_his + 1) . '-06-01';
                        $his_profile->save();
                        //Tổng hợp lại chế độ
                        $delCheDo = DB::table('qlhs_tonghopchedo')
                            ->where('qlhs_thcd_profile_id', $profile_id)
                            ->where('qlhs_thcd_school_id', Auth::user()->truong_id)
                            ->delete();

                        if ($delCheDo > 0) {
                            tongHopChedo::tonghop((int) Carbon::parse($profile_year)->format('Y'), $profile_id, Auth::user()->truong_id, null, 0);
                        }
                        $num = (int) $request->input("decided_number");

                        if ($num > 0) {
                            //Xóa quyết định cũ
                            $deleteProfileDec = DB::table('qlhs_decided')
                                ->where('decided_profile_id', $profile_id)
                                ->delete();

                            $dir = storage_path() . '/HOSO/QUYETDINH';
                            $filename_attach  = "";
                            $getTime = time();
                            for ($i = 0; $i < $num; $i++) {
                                // format date
                                $files = $request->file('file_' . $i);
                                $filename_attach  = $request->input('fileold_' . $i);
                                $decided_confirmdate = $request->input('confirmdate_' . $i) != "" ? Carbon::parse($request->input('confirmdate_' . $i)) : null; //date('Y-m-d', strtotime(str_replace('-', '/', $files)));
                                if (trim($files) != "") {
                                    $filename_attach = 'QD_' . $profile_id . '_' . Auth::user()->id . '_' . $getTime . '_' . $files->getClientOriginalName();
                                    if (file_exists($dir . '/' . $filename_attach)) {
                                        $files->move($dir, $filename_attach . '-' . $getTime);
                                        //File::delete($dir.'/'. $filename_attach);
                                    } else {
                                        $files->move($dir, $filename_attach);
                                    }
                                }
                                $insert_diceded = DB::table('qlhs_decided')
                                    ->insert([
                                        'decided_type' => $request->input('decided_type_' . $i),
                                        'decided_profile_id' => $profile_id,
                                        'decided_code' => $request->input('code_' . $i),
                                        'decided_name' => $request->input('name_' . $i),
                                        'decided_number' => $request->input('number_' . $i),
                                        'decided_confirmation' =>  $request->input('confirmation_' . $i),
                                        'decided_confirmdate' => $decided_confirmdate,
                                        'decided_filename' => $filename_attach,
                                        'decided_user_id' => $currentuser_id,
                                        'decided_createdate' => $currentdate,
                                        'decided_updatedate' => $currentdate,
                                        'decided_update_id' => $currentuser_id
                                    ]);
                            }
                        }
                        $results['success'] = "Cập nhật hồ sơ thành công!";
                    } else {
                        $results['error'] = 'Xin mời nhập ngày vào học.';
                    }
                }
            } else {
                $results['error'] = 'Học sinh không tồn tại hoặc học sinh không thuộc quyền quản lý!';
            }

            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }
    public function viewHistory($id)
    {
        return DB::table('qlhs_profile_history')
            ->leftJoin('qlhs_class', 'class_id', 'history_class_id')
            ->where('history_profile_id', $id)
            ->select('class_name', 'history_year', 'history_update_date', 'history_upto_level', 'P_His_startdate', 'p_His_enddate')
            ->get();
    }
    public function deleteHoSoHocSinh(Request $request)
    {
        $results = [];
        try {
            $profile_id = $request->PROFILEID;

            if ($profile_id > 0 && $profile_id != null) {
                $checkLCV = DB::table('qlhs_profile')
                    ->where('profile_id', $profile_id)
                    ->where('using', 0)->count();
                if ($checkLCV == 0) {
                    $results['error'] = "Học sinh đã được lập công văn không thể xóa!";
                    return $results;
                }
                $selectFileName = DB::table('qlhs_decided')
                    ->where('decided_profile_id', $profile_id)
                    ->get(); // xóa file đính kèm
                $dir = storage_path() . '/HOSO/QUYETDINH';
                foreach ($selectFileName as $key => $value) {
                    if (file_exists($dir . '/' . $value->decided_filename)) {
                        File::delete($dir . '/' . $value->decided_filename);
                    }
                }
                // xóa nội dung đính kèm
                $deleteProfileDec = DB::table('qlhs_decided')
                    ->where('decided_profile_id', $profile_id)
                    ->delete();
                // xóa tổng hợp tiền
                $deleteTongHopCheDo = DB::table('qlhs_tonghopchedo_truong')
                    ->where('thcd_nhucau_profile_id', $profile_id)
                    ->delete();
                // xóa tổng hợp nhu cầu dự toán
                $deleteNhuCauDuToan = DB::table('qlhs_tonghopchedo_trangthai_truong')
                    ->where('thcd_trangthai_profile_id', $profile_id)
                    ->delete();

                // xóa lịch sử
                $deleteProfileHis = DB::table('qlhs_profile_history')
                    ->where('history_profile_id', $profile_id)
                    ->delete();
                // xóa hộ khẩu
                $deleteProfileSite = DB::table('history_profile_site')
                    ->where('p_id', $profile_id)
                    ->delete();
                // xóa đối tượng
                $deleteProfileSub = DB::table('qlhs_profile_subject')
                    ->where('profile_subject_profile_id', $profile_id)
                    ->delete();
                // Xoa hoc sinh
                $deleteProfile = DB::table('qlhs_profile')
                    ->where('profile_id', $profile_id)
                    ->delete();
                // Xóa dữ liệu bảng tạm years_months_by_profile đối với học sinh trường DTTS Mù Căng Chải và Trạm Tấu
                $deleteProfile = DB::table('years_months_by_profile')
                    ->where('id', $profile_id)
                    ->delete();
                $results['success'] = 'Xóa hồ sơ học sinh thành công!';
            }
            return $results;
        } catch (Exception $e) {
            $results['error'] = 'Xóa hồ sơ học sinh thất bại!' . $e;
        }
    }

    public function getHoSoHocSinhbyID(Request $request)
    {
        try {
            $profile_id = $request->input("PROFILEID");
            $year = $request->input("YEAR");
            $arrProfile = array();
            if ($profile_id > 0) {
                $arrProfile['objProfile'] = DB::table('qlhs_profile_history')
                    ->leftJoin('qlhs_profile', 'profile_id', 'history_profile_id')
                    ->leftJoin('qlhs_site as tinh', 'tinh.site_id', 'profile_site_id1')
                    ->leftJoin('qlhs_site as quan', 'quan.site_id', 'profile_site_id2')
                    ->leftJoin('qlhs_site as phuong', 'phuong.site_id', 'profile_site_id3')
                    ->leftJoin('qlhs_site as thon', 'thon.site_id', 'profile_household')
                    ->where('history_year', $year)
                    ->where('history_profile_id', $profile_id)
                    ->select('profile_id', 'profile_code', 'profile_name', 'profile_birthday', 'profile_nationals_id', 'profile_site_id1', 'profile_site_id2', 'profile_site_id3', 'profile_household', 'profile_parentname', 'profile_guardian', 'profile_year', 'profile_school_id', 'history_class_id as profile_class_id', 'history_upto_level as profile_status', 'history_statute_57 as profile_statusNQ57', 'profile_leaveschool_date', 'profile_rewrite', 'profile_create_userid', 'profile_update_userid', 'history_statute_116 as profile_bantru', 'profile_km', 'profile_giaothong', 'profile_revert', 'P_His_startdate', 'tinh.site_name as tinhthanh', 'quan.site_name as quanhuyen', 'phuong.site_name as phuongxa', 'thon.site_name as thonxom', 'p_His_enddate', 'history_parentstt', 'history_id', 'history_year', 'parent_name', 'guardian', 'nationals_id', 'target_eat')->get();
                $arrProfile['arrProfileSub'] = DB::table('qlhs_profile_subject')
                    ->select('profile_subject_subject_id')
                    ->where('profile_subject_profile_id', $profile_id)->get();

                $arrProfile['arrProfileDec'] = DB::table('qlhs_decided')
                    ->where('decided_profile_id', $profile_id)->get();
            }
            return $arrProfile;
        } catch (Exception $e) { }
    }
    // danh sach học sinh chọn chức năng - use
    public function getProfilePopupUpto(Request $request)
    {
        $results = [];
        try {
            $schools_id = $request->input('PROFILESCHOOL');
            $class_id = $request->input('PROFILECLASS');
            $year = $request->input('PROFILEYEAR');
            $out = $request->input('OUT');
            $data = null;

            if ($schools_id > 0 && $class_id > 0 && (!is_null($year) && !empty($year))) {
                $data = DB::table('qlhs_profile')
                    ->join('qlhs_nationals', 'qlhs_nationals.nationals_id', 'qlhs_profile.profile_nationals_id')
                    ->join('qlhs_schools', 'qlhs_schools.schools_id', 'qlhs_profile.profile_school_id')
                    ->join('qlhs_class', 'qlhs_class.class_id', 'qlhs_profile.profile_class_id')
                    ->join('qlhs_level', 'qlhs_level.level_id', 'qlhs_class.class_level_id')
                    ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', 'qlhs_profile.profile_id')
                    ->leftJoin('qlhs_site as quan', 'quan.site_id', 'profile_site_id2')
                    ->leftJoin('qlhs_site as phuong', 'phuong.site_id', 'profile_site_id3')
                    ->leftJoin('qlhs_site as thon', 'thon.site_id', 'profile_household')
                    ->where('qlhs_profile.profile_school_id', $schools_id)
                    ->where('qlhs_profile_history.history_class_id', $class_id)
                    ->where('qlhs_profile_history.history_year', $year);
            }
            if (!json_decode($out)) {
                $data = $data->whereIn('qlhs_profile_history.history_upto_level', [0, 1, 3, 4]);
            } else {
                $data = $data->whereIn('qlhs_profile_history.history_upto_level', [2]);
            }
            $results['data'] = $data->select(
                'qlhs_profile.profile_id',
                'qlhs_profile.profile_name',
                'qlhs_profile.profile_birthday',
                DB::raw("CONCAT(thon.site_name,' - ',phuong.site_name,' - ',quan.site_name) as profile_household"),
                'qlhs_profile.profile_parentname',
                'qlhs_nationals.nationals_id',
                'qlhs_nationals.nationals_name',
                'qlhs_class.class_id',
                'qlhs_class.class_name',
                'qlhs_schools.schools_id',
                'qlhs_schools.schools_name',
                'qlhs_level.level_id',
                'qlhs_level.level_name',
                'qlhs_profile_history.history_year',
                'history_upto_level',
                'P_His_startdate',
                'p_His_enddate'
            )
                ->orderBy('qlhs_profile.profile_id', 'desc')->get();

            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getYearHistory(Request $request)
    {
        try {
            $class_id = $request->input('PROFILECLASS');
            $class = [];
            $class['levelClass'] = DB::table('qlhs_class')->leftJoin('qlhs_level', 'level_id', '=', 'class_level_id')->where('class_id', '=', $class_id)->select('level_level', 'class_schools_id')->get();
            $class['year_his'] = DB::table('qlhs_profile_history')->where('history_class_id', '=', $class_id)->select('history_year as history_year')->groupBy('history_year')->orderBy('history_year', 'asc')->get();

            return $class;
        } catch (Exception $e) { }
    }


    public function getYearHisByClassID(Request $request)
    {
        try {
            $class_id = $request->input('CLASSID');
            $class = [];

            $class = DB::table('qlhs_profile_history')->where('history_class_id', '=', $class_id)->select('history_year as his_year')->groupBy('history_year')->orderBy('history_year', 'asc')->get();

            return $class;
        } catch (Exception $e) { }
    }
    /// Chức năng học sinh - use
    public function uptoProfile(Request $request)
    {
        $results = [];
        try {
            $currentdate = Carbon::now();
            $dateOutProfile =  $request->input('DATEOUTPROFIEL') != "" ? Carbon::parse($request->input('DATEOUTPROFIEL'))->format('Y-m-d') : $currentdate->format('Y-m-d'); // ngày nghỉ học
            $classBack = $request->input('CLASSBACK'); //Học lại
            $classChange = $request->input('CLASSCHANGE'); //chuyen lop
            $ClassNext = $request->input('CLASSNEXT'); //Lên lớn
            $arrProfileInput = $request->input('ARRPROFILEID'); //Danh sách học sinh thực hiện
            $classID = $request->input('CLASSID'); // id lớp thưcj hiện
            $strYear = $request->input('YEAR'); // năm học
            $classID_next = $request->input('CLASSIDNEXT'); //chức năng thực hiện
            $selectedALL = $request->input('SELECTEDALL'); //chức năng chọn tất cả
            $out = json_decode($request->input('OUT')); //lam viec voi hoc sinh nghi hoc
            $currentuser_id = Auth::user()->id;
            $school = Auth::user()->truong_id;
            $time = time();
            $his_year = (int) explode('-', $strYear)[1];
            $val = 1;
            $profile_class_id = 0;
            $modified_date = $request->input('DATECHANGE');
            $modified_date = $modified_date ? Carbon::createFromFormat('d-m-Y', '01-' . $modified_date)->toDateString() : date('Y-m-d');
            if ($classBack != null && $classBack != "") {
                $val = 3;
                $profile_class_id = $classBack;
            }
            if ($classChange != null && $classChange != "") {
                $val = 4;
                $profile_class_id = $classChange;
                $his_year = (int) explode('-', $strYear)[0];
            }
            if ($ClassNext != null && $ClassNext != "") {
                $val = 1;
                $profile_class_id = $ClassNext;
            }
            if (($classBack == null || $classBack == "") && ($classChange == null || $classChange == "")
                && ($ClassNext == null || $ClassNext == "")
            ) {
                $val = 2;
                $profile_class_id = 0;
                $his_year = (int) explode('-', $strYear)[0];
            }
            // $qlhs_schools = DB::table('qlhs_schools')
            // ->leftJoin('qlhs_schools_history','type_his_school_id','schools_id')
            // ->where('schools_id',$school)
            // ->select('type_his_type_id','TramTauOrMCC')->first();
            $getLevelClass = DB::table('qlhs_class')
                ->where('class_id', $classID)
                ->select('class_unit_id')->first();

            $check_out = 0;
            if ($out) {
                $check_out = 1;
            }

            if ($selectedALL > 0) {
                DB::statement("call UpClassALL('" . $strYear . "'," . $school . "," . $classID . "," . $time . "," . $val . "," . $check_out . ")");
            } else {
                $insert_temp = DB::table('qlhs_profile_temp')
                    ->insert($arrProfileInput); // thêm vào bảng tạm để chạy store
                DB::table('qlhs_profile_temp')
                    ->where('date_time', 0)
                    ->where('typeval', $val)
                    ->where('s_id', $school)
                    ->update(['date_time' => $time]);
            }


            if ($profile_class_id != 0) {
                $start_date = $his_year . '-09-01';
                $end_date = ($his_year + 1) . '-06-01';
                $start_date_new = ($his_year + 1) . '-09-01';
                $end_date_new = ($his_year + 2) . '-06-01';
                // return $classID.",".$profile_class_id.",
                //     '".$his_year."-".($his_year+1)."','".($his_year+1)."-".($his_year+2)."',".$val.",null,".$currentuser_id.",".$time.",".$school.",'".$start_date."','".$end_date."','".$start_date_new."','".$end_date_new;
                // dd($classID,$profile_class_id,$his_year . "-" . ($his_year + 1),($his_year + 1) . "-" . ($his_year + 2),$val,$dateOutProfile,$currentuser_id,$time,$school,$school,$start_date,$end_date,$start_date_new,$end_date_new,$modified_date);
                DB::statement("call update_hisprofile_upclass(" . $classID . "," . $profile_class_id . ",
                    '" . $his_year . "-" . ($his_year + 1) . "','" . ($his_year + 1) . "-" . ($his_year + 2) . "'," . $val . ",null," . $currentuser_id . "," . $time . "," . $school . ",'" . $start_date . "','" . $end_date . "','" . $start_date_new . "','" . $end_date_new . "','" . $modified_date . "')");
            } else {
                $start_date = $his_year . '-09-01';
                $end_date = ($his_year + 1) . '-06-01';
                $start_date_new = ($his_year + 1) . '-09-01';
                $end_date_new = ($his_year + 2) . '-06-01';
                $date_now = Carbon::parse(($his_year + 1) . '-06-01')->format('Y-m-d');
                if ($dateOutProfile > $date_now) {
                    $dateOutProfile = $date_now;
                }
                DB::statement("call update_hisprofile_upclass(" . $classID . "," . $profile_class_id . ",
                    '" . $his_year . "-" . ($his_year + 1) . "','" . ($his_year + 1) . "-" . ($his_year + 2) . "'," . $val . ",'" . $dateOutProfile . "'," . $currentuser_id . "," . $time . "," . $school . ",'" . $start_date . "','" . $end_date . "','" . $start_date_new . "','" . $end_date_new . "','" . $modified_date . "')");
            }
            $GetIdProfile = DB::table('qlhs_profile_temp')
                ->where('typeval', $val)
                ->where('s_id', $school)
                ->where('date_time', $time)
                ->select('pro_id', 's_id')->get();

            foreach ($GetIdProfile as $key => $value) {
                // if($qlhs_schools->TramTauOrMCC != null && $qlhs_schools->TramTauOrMCC == 1){
                //     $end_date_exe = $currentdate->addYears(5)->format('Y-m-d');
                //     DB::statement("call year_month_table_by_profile(".$value->pro_id.",'".$end_date_exe."')");
                // }
                // tongHopChedo::tonghop($his_year, $value->pro_id,$value->s_id, 0);
                // tongHopChedo::tonghop(($his_year+1),$value->pro_id,$value->s_id, 0);
                tongHopChedo::tongHop_Truong($his_year, $value->pro_id, $value->s_id, 0);
                tongHopChedo::tongHop_Truong(($his_year + 1), $value->pro_id, $value->s_id, 0);
                // dispatch(new CapNhatCheDo($value->s_id,$his_year,$value->pro_id,$qlhs_schools->type_his_type_id,$getLevelClass->class_unit_id,0));

                DB::table('qlhs_profile_temp')->where('typeval', $val)
                    ->where('s_id', $school)
                    ->where('pro_id', $value->pro_id)
                    ->where('date_time', $time)->delete();
            }


            $results['success'] = "Thực hiện thành công.";
        } catch (Exception $e) {
            $results['error'] = "Thực hiện có lỗi." . $e;
        }
        return $results;
    }

    public function revertProfile(Request $request)
    {
        $results = [];
        try {
            // $arrProfileID = $request->input('ARRPROFILEID');
            $classID = $request->input('CLASSID');
            $strYear = $request->input('YEAR');
            $classID_next = $request->input('CLASSIDNEXT');
            $currentuser_id = Auth::user()->id;
            $currentdate = Carbon::now('Asia/Ho_Chi_Minh');
            $bool = TRUE;

            $arrProfileID = DB::table('qlhs_profile')
                ->join('qlhs_profile_history', 'history_profile_id', DB::raw('profile_id AND history_year = "' . $strYear . '"'))
                ->where('profile_class_id', $classID)->select('profile_id')->get();

            if (count($arrProfileID) > 0) {

                foreach ($arrProfileID as $value) {
                    // Lấy thao tác gần nhất
                    $revert = DB::table('qlhs_profile')->where('profile_id', '=', $value->profile_id)->select('profile_revert')->first();
                    // nếu là lên lớp hoặc học lại thì xóa lịch sử mới nhất
                    if ((int) $revert->profile_revert == 1 || (int) $revert->profile_revert == 3) {
                        // lấy thao tác mới nhất trong lịch sử
                        $getLevelHis = DB::table('qlhs_profile_history')->where('history_profile_id', '=', $value->profile_id)->select(DB::raw('MAX(history_upto_level) as history_upto_level'))->first();
                        if ((int) $getLevelHis->history_upto_level != 0) {
                            // Xóa bản ghi đấy
                            $delete_history = DB::table("qlhs_profile_history")->where('history_profile_id', '=', $value->profile_id)->where('history_upto_level', '=', $getLevelHis->history_upto_level)->delete();
                            if (!is_null($delete_history) && !empty($delete_history)) {
                                // Lấy lớp gần nhất để update lại hồ sơ
                                $getLevelHis2 = DB::table('qlhs_profile_history')->where('history_profile_id', '=', $value->profile_id)->select(DB::raw('MAX(history_upto_level) as history_upto_level'))->first();
                                $getIdHis = DB::table('qlhs_profile_history')->where('history_profile_id', '=', $value->profile_id)->where('history_upto_level', '=', $getLevelHis2->history_upto_level)->select('history_class_id')->first();
                                $updateProfile = DB::update("update qlhs_profile set profile_revert = 0, profile_class_id = '$getIdHis->history_class_id',profile_update_userid = '$currentuser_id',updated_at = '$currentdate' where profile_id = '$value->profile_id'");

                                if ($updateProfile <= 0) {
                                    $bool = false;
                                    break;
                                }
                            }
                        }
                        // Trường hợp nghỉ học trả về chưa nghỉ học
                    } else if ((int) $revert->profile_revert == 2) {

                        $updateProfile = DB::update("update qlhs_profile set profile_revert = 0, profile_leaveschool_date = null,profile_status = 0,profile_update_userid = '$currentuser_id',updated_at = '$currentdate' where profile_id = '$value->profile_id'");
                    }

                    // $this->tonghop($year, $profileID, $getSchoolId->class_schools_id, 1);
                }

                if ($bool) {
                    $results['success'] = "Hoàn tác thành công!";
                } else {
                    $results['error'] = "Hoàn tác thất bại!";
                }
            } else {
                $results['error'] = "Tất cả học sinh đã được thao tác!";
            }

            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function loadMoneyBySubject(Request $request)
    {
        try {
            $results = [];
            $resultsData = null;

            $schools_id = $request->SCHOOLID;
            $class_id = $request->CLASSID;
            $site_id = $request->XAID;

            $year = $request->YEAR;
            $hocky = $request->HOCKY;
            $arrSubId = $request->ARRSUBJECT;
            $bantru = $request->BANTRU;

            $data = [];
            $arrSub = [];

            $date = '';
            $month = 0;

            if ($hocky == 'HK1') {
                $date = '-09-01';
                $month = 4;
            } else if ($hocky == 'HK2') {
                $date = '-09-01';
                $month = 5;
            } else if ($hocky == 'CA') {
                $date = '-09-01';
                $month = 9;
            }

            $getSchoolType = DB::table('qlhs_schools_history')
                ->leftJoin('qlhs_schools', 'schools_id', 'type_his_school_id')
                ->where('type_his_school_id', $schools_id)
                ->where('type_his_startdate', '<=', $year . '-09-05')
                ->where(function ($p) use ($year) {
                    $p->orWhere('type_his_enddate', '>=', $year . '-09-05')
                        ->orWhere('type_his_enddate', null);
                })->select('type_his_type_id', 'TramTauOrMCC')->first();

            $getUnitByClassId = DB::table('qlhs_class')
                ->where('class_id', $class_id)
                ->select('class_unit_id')->first();

            if ($month > 0) {
                if (!is_null($arrSubId) && !empty($arrSubId)) {
                    $arrSub = explode(",", $arrSubId);

                    foreach ($arrSub as $value) {
                        $data = null;
                        if (($value == 28 || $value == 35 || $value == 38 || $value == 39 || $value == 40 || $value == 41 || $value == 73 || $value == 100 || $value == 101) &&  $getUnitByClassId->class_unit_id != 2) {

                            $result = DB::table('qlhs_kinhphinamhoc')
                                ->where('idKhoi', $getUnitByClassId->class_unit_id)
                                ->where('idXa', $site_id)
                                ->where('start_date', '<=', $year . '-09-05')
                                ->where(function ($q) use ($year) {
                                    $q->orWhere('end_date', '>=', $year . '-09-05')
                                        ->orWhere('end_date', null);
                                })
                                ->select('money')->groupBy('money')->first();
                            if (!is_null($result) && !empty($result) && $result) {
                                $data['group_name'] = "Cấp bù học phí";
                                $data['money'] = $result->money; // * $month;
                                if ($data != null) {
                                    array_push($results, $data);
                                }


                                break;
                            }
                        }
                    }

                    if ($month > 0) {
                        $resultsData = DB::table('qlhs_group')
                            ->leftJoin('qlhs_subject_history', 'subject_history_group_id', 'group_id')
                            ->leftJoin('qlhs_kinhphidoituong', 'doituong_id', 'group_id')
                            ->whereIn('subject_history_subject_id', $arrSub)
                            ->where('start_date', '<=', $year . $date)
                            ->where(function ($q) use ($year, $date) {
                                $q->orWhere('end_date', '>=', $year . $date)
                                    ->orWhere('end_date', null);
                            })
                            ->where('idLoaiTruong', $getSchoolType->type_his_type_id)
                            ->select('group_id', 'group_name', 'money', 'subject_history_subject_id')->get();
                    }
                }
                if (!is_null($resultsData) && count($resultsData) > 0) {
                    // $MGHP = 0;
                    $CPHT = 0;
                    $ATTE = 0;
                    $BT_TA = 0;
                    $BT_TO = 0;
                    $BT_VHTT = 0;
                    $HSKT_HB = 0;
                    $HSKT_DDHT = 0;
                    $BT_57 = 0;
                    $DTNT = 0;
                    $DTTS = 0;
                    foreach ($resultsData as $value) {
                        $data = null;
                        // if($value->{'group_id'} == 89 && $getUnitByClassId->class_unit_id != 2 && $MGHP == 0){//Miễn giảm học phí
                        //     $data['group_id'] = $value->{'group_id'};
                        //     $data['group_name'] = "Hỗ trợ chi phí học tập";
                        //     $data['money'] = $value->{'money'}*$month;
                        //     $CPHT = $value->{'money'}*$month;
                        // }else
                        if ($value->{'group_id'} == 92 && $CPHT == 0) { //chi phi hoc tap
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "Hỗ trợ chi phí học tập";
                            $data['money'] = $value->{'money'} * $month;
                            $CPHT = $value->{'money'} * $month;
                        } else if ($value->{'group_id'} == 93 && $getUnitByClassId->class_unit_id == 1 && $ATTE == 0) { //truong mau giao
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "Hỗ trợ ăn trưa cho trẻ em mẫu giáo";
                            $data['money'] = $value->{'money'} * $month;
                            $ATTE = $value->{'money'} * $month;
                        } else if ($value->{'group_id'} == 94 && $getUnitByClassId->class_unit_id != 1 && $BT_TA == 0) {
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "NĐ số 116-NĐ-CP_HT tiền ăn";
                            $data['money'] = $value->{'money'} * $month;
                            $BT_TA = $value->{'money'} * $month;
                        } else if ($value->{'group_id'} == 98 && $getUnitByClassId->class_unit_id != 1 && $BT_TO == 0 && $bantru == 0) {
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "NĐ số 116-NĐ-CP_HT tiền ở";
                            $data['money'] = $value->{'money'} * $month;
                            $BT_TO = $value->{'money'} * $month;
                        } else if ($value->{'group_id'} == 115 && $getSchoolType->type_his_type_id == 2 && $BT_VHTT == 0) { //Truong ban tru
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "NĐ số 116-NĐ-CP_VHTT, Tủ thuốc";
                            $data['money'] = round(($value->{'money'} * $month) / 1000) * 1000;
                            $BT_VHTT = $value->{'money'} * $month;
                        } else if ($value->{'group_id'} == 95 && $HSKT_HB == 0) {
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "Hỗ trợ HS khuyết tật_Học bổng";
                            $data['money'] = $value->{'money'} * $month;
                            $HSKT_HB = $value->{'money'} * $month;
                        } else if ($value->{'group_id'} == 100 && $HSKT_DDHT == 0) {
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "Hỗ trợ HS khuyết tật_Mua đồ dùng HT";
                            $data['money'] = $value->{'money'} * $month;
                            $HSKT_DDHT = $value->{'money'} * $month;
                        } else if ($value->{'group_id'} == 118 && $request->NQ57 == 1  && $getSchoolType->type_his_type_id == 2 && $BT_57 == 0) {
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "Hỗ trợ ăn trưa cho học sinh theo NQ 57-2016-NQ-HĐND";
                            $data['money'] = $value->{'money'} * $month;
                            $BT_57 = $value->{'money'} * $month;
                        } else if ($value->{'group_id'} == 119 && $getSchoolType->type_his_type_id == 4 && $DTNT == 0) {
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "Học bổng HS DTNT theo TTLT số 109";
                            $data['money'] = $value->{'money'} * $month;
                            $DTNT = $value->{'money'} * $month;
                        } else if ($value->{'group_id'} == 99 && $getSchoolType->TramTauOrMCC > 0 && $DTTS == 0) {
                            $data['group_id'] = $value->{'group_id'};
                            $data['group_name'] = "Hỗ trợ HS dân tộc thiểu số huyện Mù Cang Chải và Trạm Tấu";
                            $data['money'] = $value->{'money'} * $month;
                            $DTTS = $value->{'money'} * $month;
                        }

                        if ($data != null) {
                            array_push($results, $data);
                        }
                    }
                }
            }

            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function updateByProfile(Request $rq)
    {
        $result = [];
        try {
            if ($rq->start_time == null || $rq->start_time == '') {
                $rq->start_time = '123456';
            }
            //$startDateformat = Carbon::parse($rq->start_year.'-09-05')->format('d-m-Y');
            //if($rq->subject != null && $rq->subject != ""){

            $getData = DB::table('qlhs_profile_subject')
                ->where('profile_subject_profile_id', $rq->profile_id)
                ->where('profile_start_time', $rq->start_time)
                ->select('end_date')->get();

            // $start_date = null;
            $end_date = null;
            if (!is_null($getData) && !empty($getData) && count($getData) > 0) {
                foreach ($getData as $value) {
                    // $start_date = $value;
                    $end_date = $value->end_date;
                }
            }

            $sub = explode('-', $rq->un_subject);

            $data = explode('-', $rq->subject);

            if (count($data) == 0 || $data == null) {
                array_push($data, 0); // Nếu không có đối tượng
            }
            //unset($data[in_array(100, $data)]);// Xóa đối tượng 41-74 => in
            //return  in_array(100, $data);
            //unset($data[in_array(101, $data)]);// XÓa đối tượng 49-34 => in
            array_push($sub, 100); // Thêm đối tượng 41-74 để xóa => out
            array_push($sub, 101); // Thêm đối tượng 49-34 để xóa => out
            array_push($sub, 0); //Thêm đối  tượng 0 để xóa => out
            if (isset($data[0])) {
                if ($data[0] == 41) {
                    if (array_search(74, $data)) {

                        unset($data[array_search(41, $data)]);
                        unset($data[array_search(74, $data)]);
                        array_push($data, 100);
                    }
                } else {
                    if ($data[0] == 74) {
                        if (array_search(41, $data)) {
                            unset($data[array_search(41, $data)]);
                            unset($data[array_search(74, $data)]);
                            array_push($data, 100);
                        }
                    } else {
                        if (array_search(41, $data)) {
                            if (array_search(74, $data)) {
                                unset($data[array_search(41, $data)]);
                                unset($data[array_search(74, $data)]);
                                array_push($data, 100);
                            }
                        }
                    }
                }
            }

            if (isset($data[0])) {
                if ($data[0] == 34) {
                    if (array_search(49, $data)) {
                        unset($data[array_search(34, $data)]);
                        unset($data[array_search(49, $data)]);
                        array_push($data, 101);
                    }
                } else {

                    if ($data[0] == 49) {
                        if (array_search(34, $data)) {
                            unset($data[array_search(34, $data)]);
                            unset($data[array_search(49, $data)]);
                            array_push($data, 101);
                        }
                    } else {
                        if (array_search(34, $data)) {
                            if (array_search(49, $data)) {
                                unset($data[array_search(34, $data)]);
                                unset($data[array_search(49, $data)]);
                                array_push($data, 101);
                            }
                        }
                    }
                }
            }
            // Trường hợp cập nhật thì xóa bản ghi cũ trong lịch sử thay bộ bản ghi mới
            $del = DB::table('qlhs_profile_subject')
                ->where('profile_subject_profile_id', $rq->profile_id)
                ->where('profile_start_time', $rq->start_time)
                ->whereIn('profile_subject_subject_id', $sub)->delete();
            foreach ($data as $key => $value) {

                $check = DB::table('qlhs_profile_subject')
                    ->where('profile_subject_subject_id', $value)
                    ->where('profile_subject_profile_id', $rq->profile_id)
                    ->where('profile_start_time', $rq->start_time);

                if ($check->count() == 0) {

                    $update = DB::table('qlhs_profile_subject')->insert([
                        'profile_subject_profile_id' => $rq->profile_id,
                        'profile_start_time' => $rq->start_time,
                        'profile_subject_subject_id' => (int) $value,
                        'profile_subject_updatedate' => new datetime,
                        'profile_subject_createdate' => new datetime,
                        'profile_subject_create_userid' => Auth::user()->id,
                        'profile_subject_update_userid' =>  Auth::user()->id,
                        'start_date' => Carbon::parse($rq->start_year),
                        'end_date' => $end_date,
                        'active' => 1,
                        'is_finish' => 1,
                        'start_year' => Carbon::parse($rq->start_year)->format('Y'),
                        'end_year' => null
                    ]);
                } else {
                    $check->update(['profile_subject_updatedate' => new datetime, 'start_year' => Carbon::parse($rq->start_year)->format('Y')]);
                }
            }

            $year = substr($rq->start_year, 6);

            $getMaxYear = TongHopCheDo_Truong::where('thcd_nhucau_profile_id', $rq->profile_id)
                ->where('thcd_nhucau_school_id', Auth::user()->truong_id)
                ->select(DB::raw('MAX(thcd_nhucau_nam) as nam'))->first();
            //  return $getMaxYear->nam;
            if ($year != null && $year != '' && $year > 0) {
                $end = (int) $getMaxYear->nam + 1;

                $schoolid = DB::table('qlhs_profile')
                    ->where('profile_id', $rq->profile_id)
                    ->select('profile_school_id')->first()->profile_school_id;
                for ($i = (int) $year; $i < (int) $end; $i++) {
                    //tongHopChedo::tonghop($i, $rq->profile_id, $schoolid, null, $end);
                    tongHopChedo::tongHop_Truong($i, $rq->profile_id, $schoolid, null);
                }
            }
            $result['success'] = "Cập nhật thành công đối tượng!";
        } catch (Exception $e) {
            return $e;
            $result['error'] = "Cập nhật đối tượng có lỗi.Xin mời thử lại!";
        }
        return $result;
    }

    public function insertByProfile(Request $rq)
    {
        $result = [];
        try {
            $profile_id = $rq->input('profile_id');
            $subject_id = $rq->input('subject');
            $start_date = $rq->input('start_year');

            $start_time = Carbon::now()->timestamp;
            $curYear = Carbon::now()->format('Y');
            $now = Carbon::now();

            //--------------------------Tính thời gian-----------------------------------
            $startDateformat = Carbon::parse($start_date);

            $year = substr($start_date, 6);
            $month = substr($start_date, 3, 2);
            $day = substr($start_date, 0, 2);

            if ($month == 1 && $year > 0) {
                $month = 12;
                $year = $year - 1;
            } else {
                $month = $month - 1;
                $year = $year;
            }

            $endDateFormat = Carbon::parse('30-' . $month . '-' . $year);

            $getHis = DB::table('qlhs_profile_subject')
                ->where('profile_subject_profile_id', $profile_id)
                ->where('start_date', '>=', $startDateformat)->get();
            if (is_null($getHis) || empty($getHis) || count($getHis) <= 0) {
                $getMaxYear = DB::table('qlhs_profile_subject')
                    ->where('profile_subject_profile_id', $profile_id)
                    ->select(DB::raw('MAX(start_date) as start_dates'))
                    ->groupBy('start_date')->get();

                if (!is_null($getMaxYear) && !empty($getMaxYear) && count($getMaxYear) > 0) {
                    $insertHis = 0;
                    $maxYear = null;
                    foreach ($getMaxYear as $value) {
                        $maxYear = $value->start_dates;
                    }

                    if (!is_null($maxYear) && !empty($maxYear)) {
                        $updateHis = DB::table('qlhs_profile_subject')
                            ->where('profile_subject_profile_id', $profile_id)
                            ->where('start_date',  $maxYear)
                            ->where('active', 1)
                            ->update([
                                'end_date' => $endDateFormat,
                                'is_finish' => 0
                            ]);
                        //if (!is_null($subject_id) && !empty($subject_id)) {
                        $arrSubjectID = explode('-', $subject_id);
                        if (count($arrSubjectID) == 0 || $arrSubjectID == null) {
                            array_push($arrSubjectID, 0); // Nếu không có đối tượng
                        }
                        if (isset($arrSubjectID[0])) {
                            if ($arrSubjectID[0] == 41) {
                                if (array_search(74, $arrSubjectID)) {
                                    unset($arrSubjectID[array_search(41, $arrSubjectID)]);
                                    unset($arrSubjectID[array_search(74, $arrSubjectID)]);
                                    array_push($arrSubjectID, 100);
                                }
                            } else {
                                if ($arrSubjectID[0] == 74) {
                                    if (array_search(41, $arrSubjectID)) {
                                        unset($arrSubjectID[array_search(41, $arrSubjectID)]);
                                        unset($arrSubjectID[array_search(74, $arrSubjectID)]);
                                        array_push($arrSubjectID, 100);
                                    }
                                } else {
                                    if (array_search(41, $arrSubjectID)) {
                                        if (array_search(74, $arrSubjectID)) {
                                            unset($arrSubjectID[array_search(41, $arrSubjectID)]);
                                            unset($arrSubjectID[array_search(74, $arrSubjectID)]);
                                            array_push($arrSubjectID, 100);
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($arrSubjectID[0])) {
                            if ($arrSubjectID[0] == 34) {
                                if (array_search(49, $arrSubjectID)) {
                                    unset($arrSubjectID[array_search(34, $arrSubjectID)]);
                                    unset($arrSubjectID[array_search(49, $arrSubjectID)]);
                                    array_push($arrSubjectID, 101);
                                }
                            } else {
                                if ($arrSubjectID[0] == 49) {
                                    if (array_search(34, $arrSubjectID)) {
                                        unset($arrSubjectID[array_search(34, $arrSubjectID)]);
                                        unset($arrSubjectID[array_search(49, $arrSubjectID)]);
                                        array_push($arrSubjectID, 101);
                                    }
                                } else {
                                    if (array_search(34, $arrSubjectID)) {
                                        if (array_search(49, $arrSubjectID)) {
                                            unset($arrSubjectID[array_search(34, $arrSubjectID)]);
                                            unset($arrSubjectID[array_search(49, $arrSubjectID)]);
                                            array_push($arrSubjectID, 101);
                                        }
                                    }
                                }
                            }
                        }


                        // if (array_search(74, $arrSubjectID))// Cố đối tượng 74
                        // {

                        //     if (array_search(41, $arrSubjectID)){
                        //         unset($arrSubjectID[array_search(74, $arrSubjectID)]);
                        //         unset($arrSubjectID[array_search(41, $arrSubjectID)]);
                        //         array_push($arrSubjectID,100);
                        //     }else{
                        //         unset($arrSubjectID[array_search(74, $arrSubjectID)]);
                        //         unset($arrSubjectID[array_search(41, $arrSubjectID)]);
                        //         array_push($arrSubjectID,100);
                        //     }
                        // }
                        // if (array_search(34, $arrSubjectID))
                        // {
                        //     if (array_search(49, $arrSubjectID)){
                        //         unset($arrSubjectID[array_search(49, $arrSubjectID)]);
                        //         unset($arrSubjectID[array_search(34, $arrSubjectID)]);
                        //         array_push($arrSubjectID,101);
                        //     }else{
                        //         unset($arrSubjectID[array_search(49, $arrSubjectID)]);
                        //         unset($arrSubjectID[array_search(34, $arrSubjectID)]);
                        //         array_push($arrSubjectID,101);
                        //     }
                        // }
                        foreach ($arrSubjectID as $value) {
                            $insertHis = DB::table('qlhs_profile_subject')->insert([
                                'profile_subject_profile_id' => $profile_id,
                                'profile_start_time' => $start_time,
                                'profile_subject_subject_id' => (int) $value,
                                'profile_subject_updatedate' => new datetime,
                                'profile_subject_createdate' => new datetime,
                                'profile_subject_create_userid' => Auth::user()->id,
                                'profile_subject_update_userid' =>  Auth::user()->id,
                                'start_date' => $startDateformat,
                                'active' => 1,
                                'is_finish' => 1
                            ]);
                        }
                        // }
                    } else {
                        //if (!is_null($subject_id) && !empty($subject_id)) {
                        $timestam = '123456';
                        $arrSubjectID = explode('-', $subject_id);
                        if (count($arrSubjectID) == 0 || $data == null) {
                            array_push($arrSubjectID, 0); // Nếu không có đối tượng
                        }
                        if (array_search(74, $arrSubjectID)) // Cố đối tượng 74
                        {

                            if (array_search(41, $arrSubjectID)) {
                                unset($arrSubjectID[array_search(74, $arrSubjectID)]);
                                unset($arrSubjectID[array_search(41, $arrSubjectID)]);
                                array_push($arrSubjectID, 100);
                            } else {
                                unset($arrSubjectID[array_search(74, $arrSubjectID)]);
                                unset($arrSubjectID[array_search(41, $arrSubjectID)]);
                                array_push($arrSubjectID, 100);
                            }
                        }
                        if (array_search(34, $arrSubjectID)) {
                            if (array_search(49, $arrSubjectID)) {
                                unset($arrSubjectID[array_search(49, $arrSubjectID)]);
                                unset($arrSubjectID[array_search(34, $arrSubjectID)]);
                                array_push($arrSubjectID, 101);
                            } else {
                                unset($arrSubjectID[array_search(49, $arrSubjectID)]);
                                unset($arrSubjectID[array_search(34, $arrSubjectID)]);
                                array_push($arrSubjectID, 101);
                            }
                        }
                        foreach ($arrSubjectID as $value) {
                            $check = DB::table('qlhs_profile_subject')->where('profile_subject_subject_id', $value)->where('profile_subject_profile_id', $profile_id)->whereNull('start_date');

                            if ($check->count() > 0) {
                                foreach ($check->get() as $value) {
                                    $timestam = $value->profile_start_time;
                                }

                                $insertHis = DB::table('qlhs_profile_subject')
                                    ->where('profile_subject_profile_id', $profile_id)
                                    ->where('start_date', $maxYear)
                                    ->where('active', 1)
                                    ->update([
                                        'start_date' => $startDateformat,
                                        'is_finish' => 1
                                    ]);
                            }
                            // else {
                            //     return $timestam;
                            //     $insertHis = DB::table('qlhs_profile_subject')->insert([
                            //         'profile_subject_profile_id' => $profile_id,
                            //         'profile_start_time' => $timestam,
                            //         'profile_subject_subject_id' => (int)$value,
                            //         'profile_subject_updatedate' => new datetime,
                            //         'profile_subject_createdate' => new datetime,
                            //         'profile_subject_create_userid' => Auth::user()->id,
                            //         'profile_subject_update_userid' =>  Auth::user()->id,
                            //         'start_date' => $startDateformat,
                            //         'active' => 1,
                            //         'is_finish' => 1
                            //         ]);
                            // }
                        }
                        //}
                    }

                    if ($insertHis > 0) {
                        $getMaxYear = DB::table('qlhs_tonghopchedo')
                            ->where('qlhs_thcd_profile_id', $rq->profile_id)
                            ->select(DB::raw('MAX(qlhs_thcd_nam) as nam'))->first();

                        $end = (int) $getMaxYear->nam + 1;

                        $schoolid = DB::table('qlhs_profile')->where('profile_id', $rq->profile_id)->select('profile_school_id')->first()->profile_school_id;
                        for ($i = ((int) $curYear - 1); $i < (int) $end; $i++) {
                            tongHopChedo::tonghop($i, $rq->profile_id, $schoolid, null, $end);
                        }

                        $results['success'] = "Thay đổi đối tượng thành công!";
                    } else {
                        $results['error'] = "Thay đổi đối tượng thất bại!";
                    }
                }
            } else {
                $results['error'] = "Mời chọn lại ngày bắt đầu!";
            }

            //return count(explode('-', $rq->subject));
            // $date  = Carbon::now()->timestamp;
            // $year = Carbon::now()->format('Y');

            // if($rq->start_year != '' && $rq->start_year != null){
            //     if($rq->start_year < $rq->start_year_cur){
            //         $result['error'] = "Năm bắt đầu phải lớn hơn hoặc bằng năm kết thúc!";
            //         return $result;
            //     }else{
            //         $year = $rq->start_year;
            //     }

            // }else{
            //     $year = (int)$rq->start_year_cur+1;
            // }

            // if($rq->subject == null || $rq->subject == ''){
            //      DB::table('qlhs_profile_subject')->where('profile_subject_profile_id',$rq->profile_id)->whereNull('profile_end_time')->where('active',1)->update(['profile_end_time' => $date,'end_year' => $year,'profile_subject_updatedate' => new datetime,'is_finish' => 1]);

            // }else{
            //     $check = DB::table('qlhs_profile_subject')->where('profile_subject_profile_id',$rq->profile_id)->where('is_finish',1)->where('start_year',(int)$year)->where('active',1)->select('profile_subject_profile_id','start_year')->groupBy('profile_subject_profile_id','start_year')->count();
            //     //return $rq->profile_id.'-'.$year;
            //     if($check==0){
            //         $del = DB::table('qlhs_profile_subject')->where('profile_subject_profile_id',$rq->profile_id)->where('is_finish',1)->where('active',1)->update(['profile_end_time' => $date,'end_year' => $year,'profile_subject_updatedate' => new datetime,'is_finish' => 0]);
            //     }
            //     $data = explode('-', $rq->subject);
            //     if($rq->subject != null && $rq->subject != ''){
            //         foreach ($data as $key => $value) {
            //             if($check > 0){
            //                 $update = DB::table('qlhs_profile_subject')->insert(['profile_subject_profile_id' => $rq->profile_id,'profile_start_time' =>  $date,'profile_subject_subject_id' => (int)$value,'profile_subject_updatedate' => new datetime,'profile_subject_createdate' => new datetime,'profile_subject_create_userid' => Auth::user()->id,'profile_subject_update_userid' =>  Auth::user()->id,
            //                 'start_year' => (int)$year+1,'is_finish' => 1]);
            //             }else{
            //                 $update = DB::table('qlhs_profile_subject')->insert(['profile_subject_profile_id' => $rq->profile_id,'profile_start_time' =>  $date,'profile_subject_subject_id' => (int)$value,'profile_subject_updatedate' => new datetime,'profile_subject_createdate' => new datetime,'profile_subject_create_userid' => Auth::user()->id,'profile_subject_update_userid' =>  Auth::user()->id,
            //                 'start_year' => $year,'is_finish' => 1]);
            //             }

            //             $updateKinhphi = DB::table('qlhs_kinhphidoituong')->where('doituong_id', '=', $value)
            //                 ->update([
            //                     'status' => 1
            //                     ]);
            //         }
            //     }
            // }

            // $getMaxYear = DB::table('qlhs_tonghopchedo')->where('qlhs_thcd_profile_id', '=',$rq->profile_id)->select(DB::raw('MAX(qlhs_thcd_nam) as nam'))->first();
            //       //  return $getMaxYear->nam;
            //     //if ($rq->start_year != null && $rq->start_year != '') {
            //     $end = (int)$getMaxYear->nam + 1;

            //     $schoolid = DB::table('qlhs_profile')->where('profile_id',$rq->profile_id)->select('profile_school_id')->first()->profile_school_id;
            //     for ($i=(int)$curYear; $i < (int)$end; $i++) {
            //             //array_push($y, $i);
            //         $this->tonghop($i,$rq->profile_id,$schoolid,null,$end);
            //     }
            // //return $y;
            // }

        } catch (Exception $e) {
            return $e;
        }
        return $results;
    }

    public function delSubject($time, $p_id)
    {
        try {
            $result = [];

            $getCount = DB::table('qlhs_profile_subject')->where('profile_subject_profile_id', $p_id)->select('profile_start_time')->groupBy('profile_start_time')->get();

            if (is_null($getCount) || empty($getCount) || count($getCount) <= 0 || (!is_null($getCount) && !empty($getCount) && count($getCount) == 1)) {
                $result['error'] = "Đây là bản ghi cuối hoặc học sinh chưa có đối tượng nào nên không thể xóa!";
            } else {
                $update = DB::table('qlhs_profile_subject')->where('profile_start_time', $time)->where('profile_subject_profile_id', $p_id)->delete(); //->update(['active' => 0, 'is_finish' => 2]);

                $getData = DB::table('qlhs_profile_subject')->where('active', 1)->where('profile_subject_profile_id', $p_id)->select(DB::raw('MAX(profile_start_time) as profile_start_times'))->groupBy('profile_start_time')->get();

                $start_time = '';
                if (!is_null($getData) && !empty($getData) && count($getData) > 0) {
                    foreach ($getData as $value) {
                        $start_time = $value->profile_start_times;
                    }
                } else {
                    $start_time = '123456';
                }

                $update = DB::table('qlhs_profile_subject')->where('profile_start_time', $start_time)->where('profile_subject_profile_id', $p_id)->where('active', 1)->update(['end_date' => null, 'is_finish' => 1]);

                $result['success'] = "Xóa đối tượng thành công!";
            }

            return $result;
        } catch (Exception $e) {
            return $e;
        }
        // $rs = [];
        // $check = DB::table('qlhs_profile_subject')->where('active',1)->where('profile_subject_profile_id',$p_id)->select('profile_subject_profile_id','start_year')->groupBy('profile_subject_profile_id','start_year')->get();
        // //return count($check).'=';
        // if(count($check) <= 1){
        //     $rs['fall'] = "Bản ghi cuối không thể xóa.";
        // }else{
        //     try{
        //         $record_cur = DB::table('qlhs_profile_subject')->where('is_finish',1)->where('active',1)->where('profile_subject_profile_id',$p_id)->select('profile_subject_profile_id','start_year')->groupBy('profile_subject_profile_id','start_year')->first();
        //         //return count($record_old).'=';
        //         if(count($record_cur) > 0){
        //             $del = DB::table('qlhs_profile_subject')->where('is_finish',1)->where('active',1)->where('profile_subject_profile_id',$p_id)->update(['active' => 0,'profile_subject_updatedate' => new datetime,'profile_subject_update_userid' => Auth::user()->id]);
        //             $record_old  = DB::table('qlhs_profile_subject')->where('end_year',$record_cur->start_year)->where('active',1)->where('profile_subject_profile_id',$p_id);
        //             if($record_old->count() > 0){
        //                 $record_old->update(['end_year' => null,'profile_end_time' => null,'is_finish' => 1]);
        //             }else{
        //                 $record_old  = DB::table('qlhs_profile_subject')->where('end_year',(int)$record_cur->start_year-1)->where('active',1)->where('profile_subject_profile_id',$p_id)->update(['end_year' => null,'profile_end_time' => null,'is_finish' => 1]);
        //             }

        //             $getMaxYear = DB::table('qlhs_tonghopchedo')->where('qlhs_thcd_profile_id', '=',$p_id)->select(DB::raw('MAX(qlhs_thcd_nam) as nam'))->first();

        //                 $end = (int)$getMaxYear->nam + 1;
        //                 $schoolid = DB::table('qlhs_profile')->where('profile_id',$p_id)->select('profile_school_id')->first()->profile_school_id;
        //                 $y = DB::table('qlhs_profile_subject')->whereNull('end_year')->where('active',1)->where('profile_subject_profile_id',$p_id)->select('profile_subject_profile_id','start_year')->groupBy('profile_subject_profile_id','start_year')->first();
        //                 for ($i=(int)$y->start_year; $i < (int)$end  ; $i++) {
        //                      $this->tonghop($i,$p_id,$schoolid,null,$end);
        //                 }

        //         }



        //         $rs['success'] = "Đã xóa bản ghi thành công.";
        //     }catch(Exception $e){
        //         $rs['error'] = "Đã xóa bản ghi có lỗi!Xin mời thử lại.".$e;
        //     }
        // }
        // return $rs;
    }
    public function updateRegistration(Request $rq)
    {
        $json = [];
        $start = $rq->input('start');
        $limit = $rq->input('limit');
        $keysearch = $rq->input('key');
        if ($keysearch != null && $keysearch != '') {
            $data = DB::table('history_profile_site as h')->leftJoin('qlhs_profile as p', 'h.p_id', 'p.profile_id')->leftJoin('qlhs_class as c', 'c.class_id', 'p.profile_class_id')->leftJoin('qlhs_site as s1', function ($q) {
                $q->on('s1.site_id', 'h.site_tinh')->where('s1.site_level', 1);
            })->leftJoin('qlhs_site as s2', function ($q) {
                $q->on('s2.site_id', 'h.site_quanhuyen')->where('s2.site_level', 2);
            })->leftJoin('qlhs_site as s3', function ($q) {
                $q->on('s3.site_id', 'h.site_phuongxa')->where('s3.site_level', 3);
            })->leftJoin('qlhs_site as s4', function ($q) {
                $q->on('s4.site_id', 'h.site_thon')->where('s4.site_level', 4);
            })
                ->where(function ($q) use ($keysearch) {
                    $q->orWhere('p.profile_name', 'LIKE', '%' . $keysearch . '%')
                        ->orWhere('s1.site_name', 'LIKE', '%' . $keysearch . '%');
                })
                ->select('h.id', 'p.profile_id', 'p.profile_name', 'p.profile_birthday', DB::raw('CONCAT(IFNULL(CONCAT(s4.site_name,"-"),""),s3.site_name,"-",s2.site_name,"-",s1.site_name) as site_name'), 'c.class_name', 'h.start_date', 'h.end_date', 'h.updated_at');
            if ($rq->schools_id == null && Auth::user()->truong_id != null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0') {
                $data = $data->whereIn('p.profile_school_id', explode('-', Auth::user()->truong_id));
            } else if ($rq->schools_id != null && $rq->schools_id != '') {
                $data = $data->where('p.profile_school_id', $rq->schools_id);
            }
            if ($rq->class_id != null && $rq->class_id != '') {
                $data = $data->where('p.profile_class_id', $rq->class_id);
            }
            $json['data'] = $data->orderBy('h.updated_at', 'desc')->skip($start * $limit)->take($limit)->get();;
            $json['totalRows'] = $data->count();
            $json['startRecord'] = ($start);
            $json['numRows'] = $limit;
        } else {
            $data = DB::table('history_profile_site as h')->leftJoin('qlhs_profile as p', 'h.p_id', 'p.profile_id')->leftJoin('qlhs_class as c', 'c.class_id', 'p.profile_class_id')->leftJoin('qlhs_site as s1', function ($q) {
                $q->on('s1.site_id', 'h.site_tinh')->where('s1.site_level', 1);
            })->leftJoin('qlhs_site as s2', function ($q) {
                $q->on('s2.site_id', 'h.site_quanhuyen')->where('s2.site_level', 2);
            })->leftJoin('qlhs_site as s3', function ($q) {
                $q->on('s3.site_id', 'h.site_phuongxa')->where('s3.site_level', 3);
            })->leftJoin('qlhs_site as s4', function ($q) {
                $q->on('s4.site_id', 'h.site_thon')->where('s4.site_level', 4);
            })->select('h.id', 'p.profile_id', 'p.profile_name', 'p.profile_birthday', DB::raw("CONCAT(IFNULL(CONCAT(s4.site_name,'-'),''),s3.site_name,'-',s2.site_name,'-',s1.site_name) as site_name"), 'c.class_name', 'h.start_date', 'h.end_date', 'h.updated_at');
            if ($rq->schools_id == null && Auth::user()->truong_id != null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0') {
                $data = $data->whereIn('p.profile_school_id', explode('-', Auth::user()->truong_id));
            } else if ($rq->schools_id != null && $rq->schools_id != '') {
                $data = $data->where('p.profile_school_id', $rq->schools_id);
            }
            if ($rq->class_id != null && $rq->class_id != '') {
                $data = $data->where('p.profile_class_id', $rq->class_id);
            }
            //  return $data->toSql();
            $json['data'] = $data->orderBy('h.updated_at', 'desc')->skip($start * $limit)->take($limit)->get();;
            $json['totalRows'] = $data->count();
            $json['startRecord'] = ($start);
            $json['numRows'] = $limit;
        }
        return $json;
    }
    public function downloadDemo()
    {
        return Excel::load(storage_path() . '/exceltemplate/demo_profile.xlsx')->download('xlsx');;
    }
    public function downloadFileImport($filename)
    {
        return Excel::load('storage/excel/' . $filename . '.xlsx')->download('xlsx');
    }
    public function getprofilebySite($id)
    {
        $data = DB::table('history_profile_site as h')->leftJoin('qlhs_profile as p', 'h.p_id', 'p.profile_id')->leftJoin('qlhs_class as c', 'c.class_id', 'p.profile_class_id')->leftJoin('qlhs_site as s1', function ($q) {
            $q->on('s1.site_id', 'h.site_tinh')->where('s1.site_level', 1);
        })->leftJoin('qlhs_site as s2', function ($q) {
            $q->on('s2.site_id', 'h.site_quanhuyen')->where('s2.site_level', 2);
        })->leftJoin('qlhs_site as s3', function ($q) {
            $q->on('s3.site_id', 'h.site_phuongxa')->where('s3.site_level', 3);
        })->leftJoin('qlhs_site as s4', function ($q) {
            $q->on('s4.site_id', 'h.site_thon')->where('s4.site_level', 4);
        })->where('h.id', $id)->select('h.id', 'p.profile_id', 'p.profile_name', 'p.profile_birthday', 'h.site_tinh', 'h.site_quanhuyen', 'h.site_phuongxa', 'h.site_thon', 'c.class_name', 'h.start_date', 'h.end_date', 'h.updated_at', 'p.profile_nationals_id', 'p.profile_year', 'p.profile_parentname', 'p.profile_school_id', 'p.profile_class_id', 'h.start_date', 'h.end_date')->get();
        return $data;
    }

    public function importHoSo(Request $rq)
    {
        $result = [];
        try {
            if ($rq->hasFile('FILE')) {
                $files =  $rq->file('FILE');
                $path = $files->getRealPath();
                $data = Excel::selectSheetsByIndex(0)->load($path)->skip(4)->get();
                //return $data;
                if (!empty($data) && count($data)) {
                    $return =  Excel::load($path, function ($reader) use ($data, $rq) {
                        $style = array(
                            'alignment' => array(
                                'horizontal' => 'center',
                            )
                        );
                        $borderArray = array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => 'thin',
                                    'color' => array('argb' => 'FF000000')
                                )
                            )
                        );
                        $row = 5;

                        foreach ($data as $key => $value) {
                            $row++;

                            $rs = $this->importHoSoHS($value, Auth::user()->truong_id);
                            $reader->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $rs)->getStyle('AB' . $row)->applyFromArray($borderArray)->applyFromArray($style);
                        }
                    });
                    $filename = time() . '_' . Auth::user()->truong_id . '_' . Auth::user()->id . '_import_profile';
                    $result['success'] = "Nhập dữ liệu thành công";
                    $result['file'] = $filename;
                    $return->setFilename($filename)->store('xlsx', storage_path() . '/excel');
                }
            }
        } catch (Exception $e) {
            $result['error'] = "Nhập dữ liệu  có lỗi hoặc không đúng dạng file mẫu xin mời thử lại sau 10s" . $e;
        }
        return $result;
    }
    public function importHoSoHS($data, $schoolid)
    {
        $results = [];
        try {
            if (trim($data->b) == '') {
                return "Nhập tên học sinh";
            }

            $profile_name = trim($data->b); //Tên học sinh
            if (trim($data->f) == '') {
                return "Nhập ngày sinh";
            }
            $f = str_replace('/', '-', $data->f);
            $profile_birthday = Carbon::parse($f)->format('Y-m-d'); //Ngày sinh

            $profile_nationals_id = DB::table('qlhs_nationals')
                ->where(function ($q) use ($data) {
                    $q->orWhere('nationals_name', 'LIKE', '%' . trim($data->h) . '%')->orWhere('nationals_rewrite', '%' . trim(str_slug($data->h, '-')) . '%');
                })->select('nationals_id')->first();
            if ($profile_nationals_id != null) {
                $profile_nationals_id = $profile_nationals_id->nationals_id; // Dan toc
            } else {
                return "Sai danh mục dân tộc";
            }
            $profile_site_id1 = 95; // tinh Yen Bai
            if (trim($data->k) == null || trim($data->k) == "") {
                return "Xin mời nhập quận huyện";
            }
            $profile_site_id2 = DB::table('qlhs_site')->where('site_level', 2)
                ->where(function ($q) use ($data) {
                    $q->orWhere('site_name', 'LIKE', '%' . trim($data->k) . '%')->orWhere('site_rewrite', '%' . trim(str_slug($data->k, '-')) . '%');
                })->select('site_id')->first();

            if ($profile_site_id2 != null) {
                $profile_site_id2 = $profile_site_id2->site_id; //Huyện

            } else {
                return "Sai danh mục huyện";
            }
            if (trim($data->j) == null || trim($data->j) == "") {
                return "Xin mời nhập phường xã";
            }
            $profile_site_id3 = DB::table('qlhs_site')->where('site_level', 3)
                ->where(function ($q)  use ($data) {
                    $q->orWhere('site_name', 'LIKE', '%' . trim($data->j) . '%')->orWhere('site_rewrite', '%' . trim(str_slug($data->j, '-')) . '%');
                })->select('site_id')->first();
            if ($profile_site_id3 != null) {
                $profile_site_id3 = $profile_site_id3->site_id; //Xã
            } else {
                return "Sai danh mục xã";
            }
            if (trim($data->i) != null && trim($data->i) != "") {
                $profile_household = DB::table('qlhs_site')->where('site_level', 4)
                    ->where(function ($q)  use ($data) {
                        $q->orWhere('site_name', 'LIKE', '%' . trim($data->i) . '%')->orWhere('site_rewrite', '%' . trim(str_slug($data->i, '-')) . '%');
                    })->select('site_id')->first();
                if ($profile_household != null) {
                    $profile_household = $profile_household->site_id; //Thôn
                }
            } else {
                $profile_household = null;
            }
            $profile_guardian = trim($data->g); //nguoi giam ho

            $profile_parentname = trim($data->l); //CHu ho
            if (trim($data->d) == '') {
                return "Nhập năm nhập học định dạng tháng-năm.";
            }

            $profile_year = $data->d; // năm nhập học
            if (count(explode('-', $profile_year)) == 2) {
                $strProfile_year = "01-" . $profile_year;
            } else if (count(explode('-', $profile_year)) == 1) {
                $strProfile_year = "01-09-" . $profile_year;
            } else if (count(explode('-', $profile_year)) == 3) {
                $strProfile_year = $profile_year;
            }

            $profile_year = Carbon::parse($strProfile_year)->format('Y-m-d');

            $profile_school_id = $schoolid;
            $profile_class_id = DB::table('qlhs_class')
                ->where('class_schools_id', $schoolid)
                ->where(function ($q) use ($data) {
                    $q->orWhere('class_name', 'LIKE', '%' . trim($data->e) . '%')
                        ->orWhere('class_rewrite', '%' . trim(str_slug($data->e, '-')) . '%');
                })->select('class_id', 'class_unit_id')->first(); //
            if ($profile_class_id != null) {
                $profile_class_id = $profile_class_id->class_id; // id lop
            } else {
                return "Nhập lớp hoặc lớp không đúng dữ liệu.";
            }
            $profile_status = null;
            $profile_statusNQ57  = $data->c;
            if ($data->c != null && $data->c != '') {
                $profile_statusNQ57 = 1;
            }


            $profile_leaveschool_date = null;

            $arrSubjectID = array();

            if ($data->m != null && $data->m != '') {
                array_push($arrSubjectID, 35);   // than nhan co cong voi cach mang
            }

            if ($data->n != null && $data->n != '') {
                array_push($arrSubjectID, 74);   // tan tat khuyet tat
            }

            if ($data->o != null && $data->o != '') {
                array_push($arrSubjectID, 41);   //  ho can ngheo
            }

            if ($data->p != null && $data->p != '') {
                array_push($arrSubjectID, 38);   //  khong co nguon nuoi duong

            }

            if ($data->q != null && $data->q != '') {
                array_push($arrSubjectID, 28);   //  Mo coi ca cha lan me
            }

            if ($data->r != null && $data->r != '') {
                array_push($arrSubjectID, 73);   //  HO ngheo

            }

            if ($data->s != null && $data->s != '') {
                array_push($arrSubjectID, 39);   //  Con ha sy quan
            }

            if ($data->t != null && $data->t != '') {
                array_push($arrSubjectID, 49);   //  dan toc thieu so
            }

            if ($data->u != null && $data->u != '') {
                array_push($arrSubjectID, 34);   // Dac biet kho khan

            }

            if ($data->v != null && $data->v != '') {
                array_push($arrSubjectID, 26);   // Vung cao

            }

            if ($data->w != null && $data->w != '') {
                array_push($arrSubjectID, 40);   // Con CB

            }

            if ($data->x != null && $data->x != '') {
                array_push($arrSubjectID, 46);   // La hoc sinh ban tru

            }

            $qlhs_schools = DB::table('qlhs_schools')
                ->leftJoin('qlhs_schools_history', 'schools_id', 'type_his_school_id')
                ->where('schools_active', 1)
                ->where('type_his_startdate', '<=', DB::raw('NOW()'))
                ->where(function ($q) {
                    $q->orWhere('type_his_enddate', '>=', DB::raw('NOW()'))
                        ->orWhere('type_his_enddate', null);
                })
                ->where('type_his_school_id', $schoolid)
                ->select('qlhs_schools.schools_id', 'qlhs_schools.schools_name', 'qlhs_schools.schools_unit_id', 'qlhs_schools_history.type_his_type_id')
                ->first();
            if ($qlhs_schools != null) {
                if ($qlhs_schools->type_his_type_id == 4) {
                    array_push($arrSubjectID, 70); //la hoc sinh noi tru
                    if ($profile_statusNQ57 != null && $profile_statusNQ57 != '') {
                        return "Trường nội trú không hưởng nghị quyết 57";
                    }
                    if ($data->y != null && $data->y != '') {
                        return "Trường nội trú không hưởng chế độ 116";
                    }
                    if ($data->z != null && $data->z != '') {
                        return "Trường nội trú không hưởng chế độ 116";
                    }
                    if ($data->aa != null && $data->aa != '') {
                        return "Trường nội trú không hưởng chế độ 116";
                    }
                    if ($data->ab != null && $data->ab != '') {
                        return "Trường nội trú không hưởng chế độ 116";
                    }
                }
            }

            if (count($arrSubjectID) == 0) {
                array_push($arrSubjectID, 102); // khong co doi tuong
            }

            if ($data->y != null && $data->y != '') {
                $profile_bantru = 0;
            } else {
                if ($data->z != null && $data->z != '') {
                    $profile_bantru = 1;
                } else {
                    $profile_bantru = null;
                }
            }

            if ($data->aa != null && $data->aa != '') {
                $profile_KM = $data->aa;
            } else {
                $profile_KM = 0;
            }
            if ($data->ab != null && $data->ab != '') {
                $profile_giaothong = $data->ab;
            } else {
                $profile_giaothong = null;
            }
            $rs = 2;

            $bool = TRUE;
            $currentuser_id = Auth::user()->id;
            $currentdate = Carbon::now();

            $strYear = substr((string) $profile_year, 0, 4);
            $year_his = (int) $strYear;
            $strYear = (string) $year_his . "-" . (string) ($year_his + 1);

            $getbyProfile_Code = DB::table('qlhs_profile')
                ->where('profile_name', $profile_name)
                ->where('profile_birthday', $profile_birthday)
                ->where('profile_nationals_id', $profile_nationals_id)
                ->where('profile_site_id1', $profile_site_id1)
                ->where('profile_site_id2', $profile_site_id2)
                ->where('profile_site_id3', $profile_site_id3)
                ->where('profile_household', $profile_household)
                ->where('profile_parentname', $profile_parentname)
                ->where('profile_school_id', $profile_school_id)
                ->where('profile_class_id', $profile_class_id)->count();

            if ($getbyProfile_Code > 0) {
                return "Học sinh đã tồn tại"; // check ton tai
            } else {
                $hosohocsinh = new DanhMucHoSoHocSinh(); // them moi hoc sinh
                // $hosohocsinh->profile_code = $profile_code;
                $hosohocsinh->profile_name = $profile_name;
                $hosohocsinh->profile_birthday = $profile_birthday;
                $hosohocsinh->profile_nationals_id = $profile_nationals_id;
                $hosohocsinh->profile_site_id1 = $profile_site_id1;
                $hosohocsinh->profile_site_id2 = $profile_site_id2;
                $hosohocsinh->profile_site_id3 = $profile_site_id3;
                $hosohocsinh->profile_household = $profile_household;
                $hosohocsinh->profile_parentname = $profile_parentname;
                $hosohocsinh->profile_year = $profile_year;
                $hosohocsinh->profile_school_id = $profile_school_id;
                $hosohocsinh->profile_class_id = $profile_class_id;
                $hosohocsinh->profile_status = $profile_status;
                $hosohocsinh->profile_statusNQ57 = $profile_statusNQ57;
                $hosohocsinh->profile_leaveschool_date = $profile_leaveschool_date;
                $hosohocsinh->profile_bantru = $profile_bantru;
                $hosohocsinh->profile_create_userid = $currentuser_id;
                $hosohocsinh->created_at = $currentdate;
                $hosohocsinh->profile_update_userid = $currentuser_id;
                $hosohocsinh->updated_at = $currentdate;
                $hosohocsinh->profile_bantru = $profile_bantru;
                $hosohocsinh->profile_km = $profile_KM;
                $hosohocsinh->profile_giaothong = $profile_giaothong;
                $hosohocsinh->profile_guardian = $profile_guardian;
                $hosohocsinh->profile_rewrite = bussinessClass::to_slug($profile_name);
                $hosohocsinh->save();

                $insertGetIdProfile = $hosohocsinh->profile_id;

                try {
                    // lich su ho khau
                    $his_site = new HoKhauHSHocSinh();
                    $his_site->p_id = $insertGetIdProfile;
                    $his_site->class_id = $profile_class_id;
                    $his_site->site_tinh = $profile_site_id1;
                    $his_site->site_quanhuyen = $profile_site_id2;
                    $his_site->site_phuongxa = $profile_site_id3;
                    $his_site->site_thon = $profile_household;
                    $his_site->start_date = $profile_year;
                    $his_site->save();
                } catch (Illuminate\Database\QueryException $e) {
                    DanhMucHoSoHocSinh::where('profile_id', $insertGetIdProfile)->delete();
                    return "Lỗi lưu hộ khẩu.Xin mời thử lại!";
                }

                if ($insertGetIdProfile > 0) {
                    // Lấy thông tin lớp của học sinh
                    $getLevelClass = DB::table('qlhs_level')
                        ->join('qlhs_class', 'qlhs_class.class_level_id', 'qlhs_level.level_id')
                        ->where('qlhs_class.class_id', $profile_class_id)
                        ->select('level_level', 'level_next', 'level_next_1', 'level_next_2', 'level_name', 'class_old', 'class_new', 'class_unit_id')
                        ->first();
                    try {
                        //Insert Hoso_History (NOW)
                        $insert_history = new LichSuHSHocSinh();
                        $insert_history->history_class_id = $profile_class_id;
                        $insert_history->unit_class = $getLevelClass->class_unit_id;
                        $insert_history->history_school_id = $profile_school_id;
                        $insert_history->history_profile_id = $insertGetIdProfile;
                        $insert_history->history_year = $strYear;
                        $insert_history->history_upto_level = 0;
                        $insert_history->level_old = '';
                        $insert_history->nationals_id = $profile_nationals_id;
                        $insert_history->level_new = $getLevelClass->class_new;
                        $insert_history->level_cur = $getLevelClass->level_name;
                        $insert_history->history_update_user_id = Auth::user()->id;
                        $insert_history->history_update_date = $currentdate;
                        $insert_history->P_His_startdate = $profile_year;
                        $insert_history->history_statute_57 = $profile_statusNQ57;
                        if ($profile_statusNQ57 > 0) {
                            $insert_history->history_statute_116 = null;
                        } else {
                            $insert_history->history_statute_116 = $profile_bantru;
                        }

                        $insert_history->p_His_enddate = ($year_his + 1) . '-06-01';
                        $insert_history->save();
                    } catch (Illuminate\Database\QueryException $e) {
                        $deleteProfileHis = DB::table('qlhs_profile_history')->where('history_profile_id', $insertGetIdProfile)->delete();
                        $deleteProfileSub = DB::table('qlhs_profile_subject')->where('profile_subject_profile_id', $insertGetIdProfile)->delete();
                        $deleteProfileDec = DB::table('qlhs_decided')->where('decided_profile_id', $insertGetIdProfile)->delete();
                        DB::table('history_profile_site')->where('p_id', $insertGetIdProfile)->delete();
                        DanhMucHoSoHocSinh::where('profile_id', $insertGetIdProfile)->delete();

                        return "Lỗi lưu học sinh.Xin mời thử lại!";
                    }
                    //Insert Hoso_History (NEW)
                    if ($getLevelClass->class_new != null && $getLevelClass->class_new != "") {

                        $insert_history_new = new LichSuHSHocSinh();
                        $insert_history_new->unit_class = $getLevelClass->class_unit_id;
                        $insert_history_new->history_school_id = $profile_school_id;
                        $insert_history_new->history_profile_id = $insertGetIdProfile;
                        $insert_history_new->history_year = ($year_his + 1) . '-' . ($year_his + 2);
                        $insert_history_new->history_upto_level = 0;
                        $insert_history_new->level_old = $getLevelClass->level_name;
                        $insert_history_new->level_new = '';
                        $insert_history_new->level_cur = $getLevelClass->class_new;
                        $insert_history_new->history_update_user_id = Auth::user()->id;
                        $insert_history_new->history_update_date = $currentdate;
                        $insert_history_new->P_His_startdate = ($year_his + 1) . '-06-01';
                        $insert_history_new->p_His_enddate = ($year_his + 2) . '-06-01';
                        $insert_history_new->history_statute_57 = $profile_statusNQ57;
                        if ($profile_statusNQ57 > 0) {
                            $insert_history_new->history_statute_116 = null;
                        } else {
                            $insert_history_new->history_statute_116 = $profile_bantru;
                        }
                        $insert_history_new->save();
                    }

                    $date  = Carbon::now()->timestamp;
                    if ($data->n != null && $data->n != '' && $data->o != null && $data->o != '') {
                        unset($arrSubjectID[array_search(74, $arrSubjectID)]);
                        unset($arrSubjectID[array_search(41, $arrSubjectID)]);
                        array_push($arrSubjectID, 100);
                    }
                    if ($data->t != null && $data->t != '' && $data->u != null && $data->u != '') {
                        unset($arrSubjectID[array_search(34, $arrSubjectID)]);
                        unset($arrSubjectID[array_search(49, $arrSubjectID)]);
                        array_push($arrSubjectID, 101);
                    }

                    if (count($arrSubjectID) > 0) {

                        foreach ($arrSubjectID as $value) {
                            $subject_id = (int) $value;

                            $insert_hosohs_subject = new DoiTuongHSHocSinh();
                            $insert_hosohs_subject->profile_subject_profile_id = $insertGetIdProfile;
                            $insert_hosohs_subject->profile_subject_subject_id = $subject_id;
                            $insert_hosohs_subject->profile_subject_create_userid = $currentuser_id;
                            $insert_hosohs_subject->profile_subject_update_userid = $currentuser_id;
                            $insert_hosohs_subject->profile_subject_createdate = $currentdate;
                            $insert_hosohs_subject->profile_subject_updatedate = $currentdate;
                            $insert_hosohs_subject->profile_start_time = $date;
                            $insert_hosohs_subject->is_finish = 1;
                            $insert_hosohs_subject->start_date = $profile_year;
                            $insert_hosohs_subject->start_year = substr((string) $profile_year, 0, 4);
                            $insert_hosohs_subject->save();
                        }
                    }


                    // kiểm tra trường trạm tấu hoặc trường mù căng chải
                    if ($profile_site_id2 == 100 || $profile_site_id2 == 101) {
                        $end_date_exe = $currentdate->addYears(2)->format('Y-m-d');
                        DB::statement("call year_month_table_by_profile(" . $insertGetIdProfile . ",'" . $end_date_exe . "')");
                    }
                    $run_now =  tongHopChedo::tongHop_Truong($year_his, $insertGetIdProfile, $profile_school_id, null);
                    $run_new =  tongHopChedo::tongHop_Truong(($year_his + 1), $insertGetIdProfile, $profile_school_id, null);
                    return $run_now;
                }
            }
            return "Thành công";
        } catch (Exception $e) {
            return $e;
        }
    }
    public function delProfileByClass(Request $rq)
    {
        $results = [];
        try {
            $school_id = $rq->school_id;
            $class_id = $rq->class_id;

            $data = DB::table('qlhs_profile')
                ->leftJoin('qlhs_profile_history', 'history_profile_id', 'profile_id')
                ->where('profile_school_id', $school_id)
                ->where('history_class_id', $class_id)
                ->where('history_year', $rq->year)
                ->where('using', 0)
                ->select('profile_id')
                ->get();
            $json = array();
            $jsChange = array();
            foreach ($data as $key => $value) {
                $checkHS = DB::table('qlhs_profile_history')
                    ->where('history_profile_id', $value->profile_id)
                    ->get();
                if (!is_null($checkHS) && !empty($checkHS) && count($checkHS) > 0) {
                    $json[] = $value->profile_id;
                } else {
                    $jsChange[] = $value->profile_id;
                }
            }
            if (count($jsChange) > 0) {
                $results['fall'] = 'Có ' . count($jsChange) . ' không thể xóa vì đã thay đổi.';
            }
            if (count($json) > 0) {
                $selectFileName = DB::table('qlhs_decided')
                    ->whereIn('decided_profile_id', $json)
                    ->get(); // xóa file đính kèm
                $dir = storage_path() . '/HOSO/QUYETDINH';
                foreach ($selectFileName as $key => $value) {
                    if (file_exists($dir . '/' . $value->decided_filename)) {
                        File::delete($dir . '/' . $value->decided_filename);
                    }
                }
                // xóa nội dung đính kèm
                $deleteProfileDec = DB::table('qlhs_decided')
                    ->whereIn('decided_profile_id', $json)
                    ->delete();
                // xóa tổng hợp tiền
                $deleteTongHopCheDo = DB::table('qlhs_tonghopchedo')
                    ->whereIn('qlhs_thcd_profile_id', $json)
                    ->delete();
                // xóa tổng hợp nhu cầu dự toán
                $deleteNhuCauDuToan = DB::table('qlhs_nhucau_dutoan')
                    ->whereIn('id_profile', $json)
                    ->delete();
                // xóa tổng hợp trường
                $deleteTongHopCheDoTruong = DB::table('qlhs_hosobaocao_trangthai_Truong')
                    ->whereIn('rppst_profile_id', $json)
                    ->delete();

                // xóa lịch sử
                $deleteProfileHis = DB::table('qlhs_profile_history')
                    ->whereIn('history_profile_id', $json)
                    ->delete();
                // xóa hộ khẩu
                $deleteProfileSite = DB::table('history_profile_site')
                    ->whereIn('p_id', $json)
                    ->delete();
                // xóa đối tượng
                $deleteProfileSub = DB::table('qlhs_profile_subject')
                    ->whereIn('profile_subject_profile_id', $json)
                    ->delete();
                // Xoa hoc sinh
                $deleteProfile = DB::table('qlhs_profile')
                    ->where('profile_school_id', $school_id)
                    ->where('profile_class_id', $class_id)
                    ->where('using', 0)
                    ->delete();
                // Xóa dữ liệu bảng tạm years_months_by_profile đối với học sinh trường DTTS Mù Căng Chải và Trạm Tấu
                $deleteProfile = DB::table('years_months_by_profile')
                    ->whereIn('id', $json)
                    ->delete();
                $results['success'] = 'Xóa hồ sơ học sinh thành công!';
            } else {
                $results['fall'] = 'Không có học sinh hoặc học sinh đã được lập đề nghị không thể xóa!';
            }
        } catch (Exception $e) {
            $results['error'] = 'Xóa hồ sơ học sinh thất bại!' . $e;
        }
        return $results;
    }

    public function getDataCVDL(Request $rq)
    {
        $socongvan = $rq->socongvan;
        $start = $rq->start;
        $limit = $rq->limit;
        // $schools_id = DB::table('qlhs_hosobaocao')->where('report_name',$socongvan)->select('report_id_truong')->first()->report_id_truong;
        $getProfile = DB::table('qlhs_profile')
            ->join('qlhs_nationals', 'nationals_id', 'profile_nationals_id')
            ->join('qlhs_hosobaocao_tien', 'rpp_profile_id', 'qlhs_profile.profile_id')
            ->join('qlhs_schools', 'schools_id', 'profile_school_id')
            ->join('qlhs_class', 'class_id', 'profile_class_id')
            ->join('qlhs_unit', 'class_unit_id', 'unit_id')
            ->where('rpp_report_name', $socongvan);

        if ($rq->keysearch != null && $rq->keysearch != "") {
            $getProfile = $getProfile->where(function ($q) use ($rq) {
                $q->orWhere('qlhs_profile.profile_name', 'LIKE', '%' . $rq->keysearch . '%')
                    ->orWhere('qlhs_class.class_name', 'LIKE', '%' . $rq->keysearch . '%')
                    ->orWhere('qlhs_unit.unit_name', 'LIKE', '%' . $rq->keysearch . '%');
            });
        }

        $json['totalRows'] = $getProfile->count();
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        if ($rq->type == 0) {
            $json['data'] = $getProfile->select('qlhs_profile.profile_id', 'qlhs_profile.profile_name', 'rpp_MGHP_NhuCau as MGHP', 'rpp_CPHT_NhuCau as CPHT', 'rpp_HTAT_NhuCau as HTAT', 'rpp_HTBT_NhuCau_TA as HTBT_TA', 'rpp_HTBT_NhuCau_TO as HTBT_TO', 'rpp_HTBT_NhuCau_VHTT as HTBT_VHTT', 'rpp_HSKT_NhuCau_HB as HSKT_HB', 'rpp_HSKT_NhuCau_DDHT as HSKT_DDHT', 'rpp_HSDTTS_NhuCau as HSDTTS', 'rpp_HTATHS_NhuCau as HTATHS', 'rpp_HBHSDTNT_NhuCau as HBHSDTNT', 'qlhs_profile.profile_birthday', 'qlhs_class.class_name', 'qlhs_unit.unit_name')->orderBy('qlhs_profile.profile_name', 'desc')->skip($start * $limit)->take($limit)->get();
        } else {
            $json['data'] = $getProfile->select('qlhs_profile.profile_id', 'qlhs_profile.profile_name', 'rpp_MGHP_DuToan as MGHP', 'rpp_CPHT_DuToan as CPHT', 'rpp_HTAT_DuToan as HTAT', 'rpp_HTBT_DuToan_TA as HTBT_TA', 'rpp_HTBT_DuToan_TO as HTBT_TO', 'rpp_HTBT_DuToan_VHTT as HTBT_VHTT', 'rpp_HSKT_DuToan_HB as HSKT_HB', 'rpp_HSKT_DuToan_DDHT as HSKT_DDHT', 'rpp_HSDTTS_DuToan as HSDTTS', 'rpp_HTATHS_DuToan as HTATHS', 'rpp_HBHSDTNT_DuToan as HBHSDTNT', 'qlhs_profile.profile_birthday', 'qlhs_class.class_name', 'qlhs_unit.unit_name')->orderBy('qlhs_profile.profile_name', 'desc')->skip($start * $limit)->take($limit)->get();
        }

        return $json;
    }
    // use -- Danh sách công văn cấp phòng đã lập
    public function getDataCVDLBySchool(Request $rq)
    {

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
            ->where('report_cap_gui', $levelUser);

        if ($rq->keysearch != null && $rq->keysearch != "") {
            $qlhs_hosobaocao = $qlhs_hosobaocao->where(function ($q) use ($rq) {
                $q->orWhere('qlhs_schools.schools_name', 'LIKE', '%' . $rq->keysearch . '%')
                    ->orWhere('qlhs_hosobaocao.report_name', 'LIKE', '%' . $rq->keysearch . '%');
            });
        }
        if ($type_cv == 1) {
            $qlhs_hosobaocao = $qlhs_hosobaocao->whereIn('report_cap_status', [0, 1, 3]);
        } else if ($type_cv == 2) {
            $qlhs_hosobaocao = $qlhs_hosobaocao->where('report_cap_status', 2);
        }
        if ($schoolid == null || $schoolid == "") {
            if (Auth::user()->truong_id != null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0') {
                $qlhs_hosobaocao = $qlhs_hosobaocao->whereIn('schools_id', explode('-', Auth::user()->truong_id));
            }
        } else {
            $qlhs_hosobaocao = $qlhs_hosobaocao->where('schools_id', $schoolid);
        }

        $json['totalRows'] = count($qlhs_hosobaocao->select(
            'report_type',
            DB::raw('GROUP_CONCAT(report_name_text) as report_name_text'),
            DB::raw('GROUP_CONCAT(schools_id) as schools_id'),
            DB::raw('GROUP_CONCAT(report_note) as report_note'),
            DB::raw('MAX(qlhs_hosobaocao.created_at) as created_at'),
            DB::raw('SUM(qlhs_hosobaocao.report_total) as report_total'),
            'report_name',
            'report_cap_status'
        )->groupBy('report_type', 'report_name', 'report_cap_status')->get());
        $json['startRecord'] = ($start);
        $json['numRows'] = $limit;
        $json['data'] = $qlhs_hosobaocao
            ->select(
                'report_type',
                DB::raw('GROUP_CONCAT(report_name_text) as report_name_text'),
                DB::raw('GROUP_CONCAT(schools_id) as schools_id'),
                DB::raw('GROUP_CONCAT(report_note) as report_note'),
                DB::raw('MAX(qlhs_hosobaocao.created_at) as created_at'),
                DB::raw('SUM(qlhs_hosobaocao.report_total) as report_total'),
                'report_name',
                'report_cap_status'
            )
            ->groupBy('report_type', 'report_name', 'report_cap_status')
            ->orderBy('qlhs_hosobaocao.report_date', 'desc')
            ->orderBy('qlhs_schools.schools_name')
            ->skip($start * $limit)->take($limit)->get();
        return $json;
    }
    public function deleteDetailCVDLBySchool(Request $request)
    {
        $mess = [];
        $id = $request->id;
        $attach = Attach_Decision::where('attach_id', $id)->first();

        $report = $attach->report_name;
        $attach = Attach_Decision::where('attach_id', $id)->delete();
        if ($attach > 0) {
            $mess['success'] = "Đã xóa.";
        } else {
            $mess['error'] = "Xin mời thử lại.";
        }
        $mess['data'] = Attach_Decision::where('report_name', $report)->get();
        return $mess;
    }
    public function downloadDetailCVDLBySchool(Request $request)
    {
        $id = $request->id;
        $attach = Attach_Decision::where('attach_id', $id)->first();
        $dir = storage_path() . '/quyetdinh/' . $attach->attach_name;
        return response()->download($dir, $attach->attach_name);
    }
    public function uploadDetailCVDLBySchool(Request $request)
    {
        $mess = [];
        $files = $request->file('file');
        $name = $request->report_name;
        $truong_id = Auth::user()->truong_id;
        if ($request->hasFile('file')) {
            $file_hs = "";
            foreach ($files as $file) {
                $filename = Auth::user()->id . '_' . time() . '_' . $file->getClientOriginalName();
                $file->move(storage_path() . '/quyetdinh', $filename);
                $upload = new Attach_Decision();
                $upload->report_name = $name;
                $upload->attach_name = $filename;
                $upload->save();
            }
            $mess['success'] = "Tải file thành công";
        } else {
            $mess['error'] = "Không có file đính kèm.";
        }
        $attach = Attach_Decision::where('report_name', $name)->get();
        if (count($attach) > 0) {
            $mess['data'] = $attach;
        } else {
            $mess['empty'] = "Không có dữ liệu.";
        }
        return $mess;
    }
    public function getDetailCVDLBySchool(Request $rq)
    {
        $socongvan = $rq->keysearch;
        $schoolid = $rq->school_id;
        $nam_hoc = $rq->namhoc;
        $type_cv = $rq->type_cv;

        // $start = $rq->start;
        // $limit = $rq->limit;
        // $levelUser  = Auth::user()->level;
        $capgui = DB::table('qlhs_hosobaocao')
            ->where('report_name', $socongvan)
            ->select('report_cap_nhan', 'report_cap_gui', 'report_cap_status', 'report_type')->first();

        $levelUser = $capgui != null ? $capgui->report_cap_gui : Auth::user()->level;
        $capnhan = $capgui != null ? $capgui->report_cap_nhan : 2;
        $loaibaocao = $capgui != null ? $capgui->report_type : null;
        $qlhs_hosobaocao = DB::table('qlhs_hosobaocao');
        if ($loaibaocao == 'NGNA') {

            if ($type_cv != 2) {
                if ($levelUser == 1) {
                    if ($capnhan == 2) {
                        $qlhs_hosobaocao->join('qlhs_hosobaocao_ngna_PGD', function ($q) {
                            $q->on('qlhs_hosobaocao_ngna_PGD.report_name', 'qlhs_hosobaocao.report_name')
                                ->on('report_id_truong', 'hsbc_school_id');
                            // ->on('report','hsbc_unit_id');
                        });
                    } else if ($capnhan == 3) {
                        $qlhs_hosobaocao->join('qlhs_hosobaocao_ngna_PTC', function ($q) {
                            $q->on('qlhs_hosobaocao_ngna_PTC.report_name', 'qlhs_hosobaocao.report_name')
                                ->on('report_id_truong', 'hsbc_school_id');
                            // ->on('report','hsbc_unit_id');
                        });
                    }
                } else if ($levelUser == 2) {
                    if ($capnhan == 2) {
                        $qlhs_hosobaocao->join('qlhs_hosobaocao_ngna_PGD', function ($q) {
                            $q->on('qlhs_hosobaocao_ngna_PGD.report_name', 'qlhs_hosobaocao.report_name')
                                ->on('report_id_truong', 'hsbc_school_id');
                            // ->on('report','hsbc_unit_id');
                        });
                    } else if ($capnhan == 3) {
                        $qlhs_hosobaocao->join('qlhs_hosobaocao_ngna_PTC', function ($q) {
                            $q->on('qlhs_hosobaocao_ngna_PTC.report_name', 'qlhs_hosobaocao.report_name')
                                ->on('report_id_truong', 'hsbc_school_id');
                            // ->on('report','hsbc_unit_id');
                        });
                    }
                } else if ($levelUser == 3) {
                    if ($capnhan == 3) {
                        $qlhs_hosobaocao->join('qlhs_hosobaocao_ngna_PTC', function ($q) {
                            $q->on('qlhs_hosobaocao_ngna_PTC.report_name', 'qlhs_hosobaocao.report_name')
                                ->on('report_id_truong', 'hsbc_school_id');
                            // ->on('report','hsbc_unit_id');
                        });
                    } else if ($capnhan == 4) {
                        $qlhs_hosobaocao->join('qlhs_hosobaocao_ngna_STC', function ($q) {
                            $q->on('qlhs_hosobaocao_ngna_STC.report_name', 'qlhs_hosobaocao.report_name')
                                ->on('report_id_truong', 'hsbc_school_id');
                            // ->on('report','hsbc_unit_id');
                        });
                    }
                }
            }
            $qlhs_hosobaocao->join('qlhs_schools', 'schools_id', 'report_id_truong')
                ->where('report_year', $nam_hoc)
                ->where('qlhs_hosobaocao.report_name', $socongvan);
            $json['type'] = 'NGNA';
            $json['data'] = $qlhs_hosobaocao->select('schools_id', 'schools_name', 'report_name_text', 'qlhs_hosobaocao.created_at', 'qlhs_hosobaocao.report_name', 'hsbc_HK', 'hsbc_amount', 'hsbc_TW', 'hsbc_DP', 'hsbc_68', 'hsbc_amount_TW', 'hsbc_amount_DP', 'hsbc_amount_TW', 'hsbc_amount_DP')->get();
            //,'MGHP','CPHT','TATEMG','BTTA','BTTO','BTVHTT','TAHS','HSKTHB','HSKTDDHT','HSDTNT','HSDTTS'
            $json['attach'] = Attach_Decision::where('report_name', $socongvan)->orderBy('updated_at', 'desc')->get();
            return $json;
        } else {
            $json['type'] = 'ALL';
            if ($type_cv == 2) {
                if ($levelUser == 2) {
                    $qlhs_hosobaocao->join('qlhs_tralaikinhphi_PGD', function ($q) {
                        $q->on('bckp_name', 'report_name')
                            ->on('report', 'bckp_khoilop')
                            ->on('report_id_truong', 'bckp_truong');
                    });
                } else if ($levelUser == 3) {
                    $qlhs_hosobaocao->join('qlhs_tralaikinhphi_PTC', function ($q) {
                        $q->on('bckp_name', 'report_name')
                            ->on('report', 'bckp_khoilop')
                            ->on('report_id_truong', 'bckp_truong');
                    });
                } else if ($levelUser == 4) {
                    $qlhs_hosobaocao->join('qlhs_tralaikinhphi_SGD', function ($q) {
                        $q->on('bckp_name', 'report_name')
                            ->on('report', 'bckp_khoilop')
                            ->on('report_id_truong', 'bckp_truong');
                    });
                }
            } else {

                if ($levelUser == 1) {
                    if ($capnhan == 2) {
                        $qlhs_hosobaocao->join('qlhs_baocaokinhphi_PGD', function ($q) {
                            $q->on('bckp_name', 'report_name')
                                ->on('report_id_truong', 'bckp_truong')
                                ->on('report', 'bckp_khoilop');
                        });
                    } else {
                        $qlhs_hosobaocao->join('qlhs_baocaokinhphi_PTC', function ($q) {
                            $q->on('bckp_name', 'report_name')
                                ->on('report', 'bckp_khoilop')
                                ->on('report_id_truong', 'bckp_truong');
                        });
                    }
                } else if ($levelUser == 2) {
                    $qlhs_hosobaocao->join('qlhs_baocaokinhphi_PTC', function ($q) {
                        $q->on('bckp_name', 'report_name')
                            ->on('report', 'bckp_khoilop')
                            ->on('report_id_truong', 'bckp_truong');
                    });
                } else if ($levelUser == 3) {
                    $qlhs_hosobaocao->join('qlhs_baocaokinhphi_SGD', function ($q) {
                        $q->on('bckp_name', 'report_name')
                            ->on('report', 'bckp_khoilop')
                            ->on('report_id_truong', 'bckp_truong');
                    });
                } else if ($levelUser == 4) {
                    $qlhs_hosobaocao->join('qlhs_baocaokinhphi_tonghop', function ($q) {
                        $q->on('bckp_name', 'report_name')
                            ->on('report', 'bckp_khoilop')
                            ->on('report_id_truong', 'bckp_truong');
                    });
                }
            }
        }

        $qlhs_hosobaocao->join('qlhs_schools', 'schools_id', 'report_id_truong')
            ->where('report_year', $nam_hoc)
            ->where('report_name', $socongvan);

        if ($schoolid == null || $schoolid == "") {
            if (Auth::user()->truong_id != null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0') {
                $qlhs_hosobaocao = $qlhs_hosobaocao
                    ->whereIn('schools_id', explode('-', Auth::user()->truong_id))->where(function ($q) {
                        $q->orWhere('bckp_nhucau_mghp', '>', 0)
                            ->orWhere('bckp_nhucau_cpht', '>', 0)
                            ->orWhere('bckp_nhucau_htat', '>', 0)
                            ->orWhere('bckp_nhucau_htbt_tong', '>', 0)
                            ->orWhere('bckp_nhucau_htkt_tong', '>', 0)
                            ->orWhere('bckp_nhucau_hsdtts', '>', 0)
                            ->orWhere('bckp_nhucau_htaths', '>', 0)
                            ->orWhere('bckp_nhucau_hbhsdtnt', '>', 0)
                            ->orWhere('bckp_nhucau_tong', '>', 0);
                    });
            } else {
                $qlhs_hosobaocao = $qlhs_hosobaocao

                    ->where(function ($q) {
                        $q->orWhere('bckp_nhucau_mghp', '>', 0)
                            ->orWhere('bckp_nhucau_cpht', '>', 0)
                            ->orWhere('bckp_nhucau_htat', '>', 0)
                            ->orWhere('bckp_nhucau_htbt_tong', '>', 0)
                            ->orWhere('bckp_nhucau_htkt_tong', '>', 0)
                            ->orWhere('bckp_nhucau_hsdtts', '>', 0)
                            ->orWhere('bckp_nhucau_htaths', '>', 0)
                            ->orWhere('bckp_nhucau_hbhsdtnt', '>', 0)
                            ->orWhere('bckp_nhucau_tong', '>', 0);
                    });
            }
        } else {
            $qlhs_hosobaocao = $qlhs_hosobaocao
                ->whereIn('schools_id', explode(',', $schoolid))
                ->where(function ($q) {
                    $q->orWhere('bckp_nhucau_mghp', '>', 0)
                        ->orWhere('bckp_nhucau_cpht', '>', 0)
                        ->orWhere('bckp_nhucau_htat', '>', 0)
                        ->orWhere('bckp_nhucau_htbt_tong', '>', 0)
                        ->orWhere('bckp_nhucau_htkt_tong', '>', 0)
                        ->orWhere('bckp_nhucau_hsdtts', '>', 0)
                        ->orWhere('bckp_nhucau_htaths', '>', 0)
                        ->orWhere('bckp_nhucau_hbhsdtnt', '>', 0)
                        ->orWhere('bckp_nhucau_tong', '>', 0);
                });
        }
        $total_sum = $qlhs_hosobaocao;
        if (count(explode(',', $schoolid)) > 1) {
            $total_sum = $total_sum
                ->groupBy('report_name')
                ->select(
                    'qlhs_hosobaocao.report_name as report_name',
                    DB::raw('SUM(bckp_nhucau_mghp) as MGHP'),
                    DB::raw('SUM(bckp_nhucau_cpht) as CPHT'),
                    DB::raw('SUM(bckp_nhucau_htat) as TATEMG'),
                    DB::raw('SUM(bckp_nhucau_htbt_ta) as BTTA'),
                    DB::raw('SUM(bckp_nhucau_htbt_to) as BTTO'),
                    DB::raw('SUM(bckp_nhucau_htbt_vhtt) as BTVHTT'),
                    DB::raw('SUM(bckp_nhucau_htkt_hb) as HSKTHB'),
                    DB::raw('SUM(bckp_nhucau_htkt_ddht) as HSKTDDHT'),
                    DB::raw('SUM(bckp_nhucau_hsdtts) as HSDTTS'),
                    DB::raw('SUM(bckp_nhucau_htaths) as TAHS'),
                    DB::raw('SUM(bckp_nhucau_hbhsdtnt) as HSDTNT')
                );
            $json['total_sum'] = $total_sum->get();
        }


        $qlhs_hosobaocao = $qlhs_hosobaocao->groupBy('report_id_truong', 'qlhs_schools.schools_name', 'qlhs_hosobaocao.report_name', 'qlhs_hosobaocao.created_at', 'report_name_text', 'report_note', 'report', 'report_type');

        //if($rq->type == 0){
        $qlhs_hosobaocao = $qlhs_hosobaocao->select(
            'report_id_truong as schools_id',
            'qlhs_schools.schools_name',
            'report',
            'qlhs_hosobaocao.report_name as report_name',
            DB::raw('SUM(bckp_nhucau_mghp) as MGHP'),
            DB::raw('SUM(bckp_nhucau_cpht) as CPHT'),
            DB::raw('SUM(bckp_nhucau_htat) as TATEMG'),
            DB::raw('SUM(bckp_nhucau_htbt_ta) as BTTA'),
            DB::raw('SUM(bckp_nhucau_htbt_to) as BTTO'),
            DB::raw('SUM(bckp_nhucau_htbt_vhtt) as BTVHTT'),
            DB::raw('SUM(bckp_nhucau_htkt_hb) as HSKTHB'),
            DB::raw('SUM(bckp_nhucau_htkt_ddht) as HSKTDDHT'),
            DB::raw('SUM(bckp_nhucau_hsdtts) as HSDTTS'),
            DB::raw('SUM(bckp_nhucau_htaths) as TAHS'),
            DB::raw('SUM(bckp_nhucau_hbhsdtnt) as HSDTNT'),
            'qlhs_hosobaocao.created_at',
            'report_name_text',
            'report_note',
            'report_type'
        );
        //}
        //return $qlhs_hosobaocao->toSql();


        $json['data'] = $qlhs_hosobaocao->orderBy('qlhs_schools.schools_name')->orderBy('qlhs_hosobaocao.report_date', 'desc')->get();
        $json['attach'] = Attach_Decision::where('report_name', $socongvan)->orderBy('updated_at', 'desc')->get();

        return $json;
    }

    //-------------------------------------------------------------cập nhật hộ khẩu--------------------------------------------------------------use
    public function loadDataHisSite(Request $request)
    {
        try {
            $result = [];
            $start = $request->input('start');
            $limit = $request->input('limit');

            $schools_id = $request->input('SCHOOLID');
            $class_id = $request->input('CLASSID');
            $keySearch = $request->input('KEY');
            $type = $request->input('TYPE');
            $order = $request->input('ORDER');
            $year = $request->year;

            $getData = DB::table('qlhs_profile')
                ->join('qlhs_schools', 'schools_id', 'profile_school_id')
                ->join('qlhs_class', 'class_id', 'profile_class_id')
                ->leftJoin('history_profile_site', 'p_id', 'profile_id')
                ->leftJoin('qlhs_site as tinh', 'tinh.site_id', 'site_tinh')
                ->leftJoin('qlhs_site as huyen', 'huyen.site_id', 'site_quanhuyen')
                ->leftJoin('qlhs_site as xa', 'xa.site_id', 'site_phuongxa')
                ->leftJoin('qlhs_site as thon', 'thon.site_id', 'site_thon');

            if (!is_null($schools_id) && !empty($schools_id)) {
                $getData = $getData->where('profile_school_id', $schools_id);
            } else {
                if (Auth::user()->truong_id != null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0') {
                    $getData = $getData->whereIn('profile_school_id', explode('-', Auth::user()->truong_id));
                }
            }

            if (!is_null($keySearch) && !empty($keySearch)) {
                $kw = bussinessClass::to_slug($keySearch);
                $getData = $getData->where(function ($q) use ($keySearch, $kw) {
                    $q->orWhere('profile_name', 'LIKE', '%' . $keySearch . '%')
                        ->orWhere("profile_rewrite", "LIKE", "%" . $kw . "%")
                        //->orWhere('schools_name', 'LIKE', '%'.$keySearch.'%')
                        ->orWhere('class_name', 'LIKE', '%' . $keySearch . '%')
                        ->orWhere('tinh.site_name', 'LIKE', '%' . $keySearch . '%')
                        ->orWhere('huyen.site_name', 'LIKE', '%' . $keySearch . '%')
                        ->orWhere('xa.site_name', 'LIKE', '%' . $keySearch . '%')
                        ->orWhere('thon.site_name', 'LIKE', '%' . $keySearch . '%');
                });
                //  ->orWhere('tinh.site_name', 'LIKE', '%'.$keySearch.'%')
                //  ->orWhere('huyen.site_name', 'LIKE', '%'.$keySearch.'%')
                // ->orWhere('xa.site_name', 'LIKE', '%'.$keySearch.'%')
                // ->orWhere('thon.site_name', 'LIKE', '%'.$keySearch.'%');
            }
            $result['totalRows'] = $getData->get()->count();

            $result['startRecord'] = ($start);
            $result['numRows'] = $limit;
            $or = 'asc';
            if ($order == 1) {
                $or = 'desc';
            }
            if ($type == 1) {
                $getData = $getData->orderBy('profile_name', $or);
            } else if ($type == 2) {
                $getData = $getData->orderBy('profile_birthday', $or);
            } else if ($type == 3) {
                $getData = $getData->orderBy('class_name', $or);
            } else if ($type == 4) {
                $getData = $getData->orderBy('thon.site_name', $or);
            } else if ($type == 5) {
                $getData = $getData->orderBy('history_profile_site.start_date', $or);
            }
            $result['data'] = $getData->select('history_profile_site.id', 'profile_id', 'profile_name', 'profile_birthday', 'schools_name', 'class_name', 'tinh.site_name as tentinh', 'huyen.site_name as tenhuyen', 'xa.site_name as tenxa', 'thon.site_name as tenthon', 'history_profile_site.start_date', 'history_profile_site.end_date')
                ->orderBy('profile_name')
                ->orderBy('profile_id', 'desc')
                ->skip($start * $limit)->take($limit)->get();
            return $result;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getDataByProfileID(Request $request)
    {
        try {
            $his_id = $request->input("HISID");
            $profile_id = $request->input("PROFILEID");
            $arrProfile = array();
            if ($profile_id > 0) {
                $arrProfile['objProfile'] = DB::table('qlhs_profile')
                    ->join('qlhs_profile_history', 'profile_id', '=', 'history_profile_id')
                    ->join('history_profile_site', 'p_id', '=', 'profile_id')
                    ->where('history_profile_site.p_id', $profile_id)
                    ->where('history_profile_site.id', $his_id)
                    ->select('profile_id', 'profile_name', 'profile_birthday', 'profile_nationals_id', 'site_tinh', 'site_quanhuyen', 'site_phuongxa', 'site_thon', 'profile_parentname', 'profile_guardian', 'profile_school_id', 'profile_class_id', 'history_profile_site.start_date')
                    ->get();
            }
            return $arrProfile;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function updateSiteByProfile(Request $request)
    {
        try {
            $results = [];

            $his_id = $request->input('HISID');
            $profile_id = $request->input('PROFILEID');
            $class_id = $request->input('CLASSID');
            $tentinh = $request->input('TENTINH');
            $tenhuyen = $request->input('TENHUYEN');
            $tenxa = $request->input('TENXA');
            $tenthon = $request->input('TENTHON') != "" ? $request->input('TENTHON') : null;
            $start_date = $request->input('STARTDATE');

            $now = Carbon::now('Asia/Ho_Chi_Minh');

            $startDateformat = Carbon::parse($start_date);

            if (!is_null($his_id) && !empty($his_id) && $his_id > 0) {
                $updateSite = DB::table('history_profile_site')->where('id', $his_id)
                    ->update([
                        'p_id' => $profile_id,
                        'class_id' => $class_id,
                        'site_tinh' => $tentinh,
                        'site_quanhuyen' => $tenhuyen,
                        'site_phuongxa' => $tenxa,
                        'site_thon' => $tenthon,
                        'start_date' => $startDateformat,
                        'updated_at' => $now
                    ]);
                $updateProfile = DB::table('qlhs_profile')->where('profile_id', $profile_id)->update([
                    'profile_site_id1' => $tentinh,
                    'profile_site_id2' => $tenhuyen,
                    'profile_site_id3' => $tenxa,
                    'profile_household' => $tenthon,
                    'profile_update_userid' => Auth::user()->id,
                    'updated_at' => $now

                ]);
                $results['success'] = "Cập nhật hộ khẩu thành công!";

                $year = substr($start_date, 6);
                $getMaxYear = TongHopCheDo_Truong::where('thcd_nhucau_profile_id', $profile_id)
                    ->where('thcd_nhucau_school_id', Auth::user()->truong_id)
                    ->select(DB::raw('MAX(thcd_nhucau_nam) as nam'))->first();

                // $getMaxYear = DB::table('qlhs_tonghopchedo')
                //             ->where('qlhs_thcd_profile_id',$profile_id)
                //             ->select(DB::raw('MAX(qlhs_thcd_nam) as nam'))->first();

                if ($getMaxYear != null && $year != null && $year != '' && $year > 0) {
                    $end = (int) $getMaxYear->nam + 1;

                    $schoolid = DB::table('qlhs_profile')->where('profile_id', $profile_id)->select('profile_school_id')->first()->profile_school_id;
                    for ($i = (int) $year; $i < (int) $end; $i++) {
                        //tongHopChedo::tonghop($i, $profile_id, $schoolid, null, $end);
                        tongHopChedo::tongHop_Truong($i, $profile_id, $schoolid, null);
                    }
                }
            } else {
                $results['error'] = "Học sinh chưa có lịch sử hộ khẩu, vui lòng chọn thay đổi trước khi cập nhật!";
            }

            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function insertSiteProfile(Request $request)
    {
        try {
            $results = [];

            $his_id = $request->input('HISID');
            $profile_id = $request->input('PROFILEID');
            $class_id = $request->input('CLASSID');
            $tentinh = $request->input('TENTINH');
            $tenhuyen = $request->input('TENHUYEN');
            $tenxa = $request->input('TENXA');
            $tenthon = $request->input('TENTHON') != "" ? $request->input('TENTHON') : null;
            $start_date = $request->input('STARTDATE');

            $now = Carbon::now('Asia/Ho_Chi_Minh');

            //--------------------------Tính thời gian-----------------------------------
            $startDateformat = Carbon::parse($start_date);

            $year = substr($start_date, 6);
            $month = substr($start_date, 3, 2);
            $day = substr($start_date, 0, 2);

            if ($month == 1 && $year > 0) {
                $month = 12;
                $year = $year - 1;
            } else {
                $month = $month - 1;
                $year = $year;
            }

            $endDateFormat = Carbon::parse($day . '-' . $month . '-' . $year);

            if (!is_null($his_id) && !empty($his_id) && $his_id > 0) {
                $getHisSite = DB::table('history_profile_site')->where('p_id', '=', $profile_id)->where('start_date', '>=', $startDateformat)->get();

                if (is_null($getHisSite) || empty($getHisSite) || count($getHisSite) <= 0) {
                    $updateHis = DB::table('history_profile_site')->where('id', '=', $his_id)
                        ->where('p_id', '=', $profile_id)
                        ->update(['end_date' => $endDateFormat]);

                    $insertHis = DB::table('history_profile_site')->insert([
                        'p_id' => $profile_id,
                        'class_id' => $class_id,
                        'site_tinh' => $tentinh,
                        'site_quanhuyen' => $tenhuyen,
                        'site_phuongxa' => $tenxa,
                        'site_thon' => $tenthon,
                        'start_date' => $startDateformat,
                        'updated_at' => $now
                    ]);
                    $updateProfile = DB::table('qlhs_profile')->where('profile_id', $profile_id)->update([
                        'profile_site_id1' => $tentinh,
                        'profile_site_id2' => $tenhuyen,
                        'profile_site_id3' => $tenxa,
                        'profile_household' => $tenthon,
                        'profile_update_userid' => Auth::user()->id,
                        'updated_at' => $now

                    ]);
                    if ($updateHis > 0 && $insertHis > 0) {
                        $results['success'] = "Thay đổi hộ khẩu thành công!";

                        // $year = substr($start_date, 6);
                        $getMaxYear = TongHopCheDo_Truong::where('thcd_nhucau_profile_id', $profile_id)
                            ->where('thcd_nhucau_school_id', Auth::user()->truong_id)
                            ->select(DB::raw('MAX(thcd_nhucau_nam) as nam'))->first();
                        // $getMaxYear = DB::table('qlhs_tonghopchedo')->where('qlhs_thcd_profile_id', '=',$profile_id)->select(DB::raw('MAX(qlhs_thcd_nam) as nam'))->first();
                        if ($getMaxYear != null && $year != null && $year != '' && $year > 0) {
                            $end = (int) $getMaxYear->nam + 1;

                            $schoolid = DB::table('qlhs_profile')->where('profile_id', $profile_id)->select('profile_school_id')->first()->profile_school_id;
                            for ($i = (int) $year; $i < (int) $end; $i++) {
                                tongHopChedo::tongHop_Truong($i, $profile_id, $schoolid, null);
                                //tongHopChedo::tonghop($i, $profile_id, $schoolid, null, $end);
                            }
                        }
                    } else {
                        $results['error'] = "Thay đổi hộ khẩu thất bại!";
                    }
                } else {
                    $results['error'] = "Mời chọn lại ngày bắt đầu!";
                }
            } else {
                $insertHis = DB::table('history_profile_site')
                    ->insert([
                        'p_id' => $profile_id,
                        'class_id' => $class_id,
                        'site_tinh' => $tentinh,
                        'site_quanhuyen' => $tenhuyen,
                        'site_phuongxa' => $tenxa,
                        'site_thon' => $tenthon,
                        'start_date' => $startDateformat,
                        'updated_at' => $now
                    ]);

                $results['success'] = "Thay đổi hộ khẩu thành công!";

                // $year = substr($start_date, 6);

                $getMaxYear = DB::table('qlhs_tonghopchedo')->where('qlhs_thcd_profile_id', '=', $profile_id)->select(DB::raw('MAX(qlhs_thcd_nam) as nam'))->first();
                if ($year != null && $year != '' && $year > 0) {
                    $end = (int) $getMaxYear->nam + 1;

                    $schoolid = DB::table('qlhs_profile')->where('profile_id', $profile_id)->select('profile_school_id')->first()->profile_school_id;
                    for ($i = (int) $year; $i <= (int) $end; $i++) {
                        tongHopChedo::tonghop($i, $profile_id, $schoolid, null, $end);
                    }
                }
            }

            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function deleteSiteProfile(Request $request)
    {
        try {
            $results = [];

            $his_id = $request->input('HISID');
            $profile_id = $request->input('PROFILEID');

            $getData = DB::table('history_profile_site')->where('p_id', '=', $profile_id)->get();
            if (is_null($getData) || empty($getData) || count($getData) <= 0 || (!is_null($getData) && !empty($getData) && count($getData) == 1)) {
                $results['error'] = "Học sinh chưa có hộ khẩu hoặc đang là bản ghi cuối nên không thể xóa!";
            } else {
                $deleteSite = DB::table('history_profile_site')->where('id', '=', $his_id)->delete();

                if ($deleteSite > 0) {
                    $getMaxHis = DB::table('history_profile_site')->where('p_id', $profile_id)->select(DB::raw('MAX(id) as id'))->groupBy('id')->get();

                    if (!is_null($getMaxHis) && !empty($getMaxHis) && count($getMaxHis) > 0) {
                        $maxID = 0;
                        foreach ($getMaxHis as $value) {
                            $maxID = $value->id;
                        }
                        if ($maxID > 0) {
                            $updateSiteHis = DB::table('history_profile_site')->where('id', $maxID)->update(['end_date' => null]);
                        }
                    }
                }

                $results['success'] = "Xóa lịch sử hộ khẩu thành công!";
            }

            return $results;
        } catch (Exception $e) {
            return $e;
        }
    }
}

