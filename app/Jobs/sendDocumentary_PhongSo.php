<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;
use File,datetime;
use App\Models\qlhs_tonghopchedo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\bussinessClass;
use App\Helpers\lapcongvanCapPhong;
use App\Models\qlhs_message;

class sendDocumentary_PhongSo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Số giây job có thể chạy trước khi timeout
     *
     * @var int
     */
    public $timeout = 120;
    /**
     * Số lần job sẽ thử thực hiện lại
     *
     * @var int
     */
    public $tries = 20;

    protected $school_id;
    protected $report;
    protected $status;
    protected $arrChedo;
    protected $tencv;
    protected $ghichu;
    protected $timer;
    protected $user_id;
    protected $cap;
    protected $auto;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($school_ids,$reports,$statuss,$arrChedos,$tencvs,$ghichu,$timer,$user_ids,$cap,$Codes)
    {
        $this->school_id = $school_ids;
        $this->report = $reports;
        $this->status = $statuss;
        $this->arrChedo = $arrChedos;
        $this->tencv = $tencvs;
        $this->ghichu = $ghichu;
        $this->timer = $timer;
        $this->user_id = $user_ids;
        $this->cap = $cap;
        $this->auto = $Codes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(2);
        
        $report_name = $this->report;
        $report_status = $this->status;// 1- CV duyệt 2-CV trả lại
        $chedo = $this->arrChedo;
        $name =  $this->tencv;
        $note = $this->ghichu;
        $autoCode = $this->auto;


        $time = $this->timer;
        $current_date = Carbon::now();
        $user = $this->user_id;
        $levelUser = $this->cap;
        $getReportYear = DB::table('qlhs_hosobaocao')
            ->where('report_name', $report_name)
            ->select('report_year','report_cap_gui','report_cap_nhan','report_id_truong','report')
            ->first();
        $schools_id = $getReportYear->report_id_truong;
        try {
            echo 'start ==> ';
            echo 'lap cv: '.$autoCode;
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

            foreach ($chedo as $value) {
                if ($value == 1){
                    $chedoMGHP = $report_status;
                    $value_type .= "MGHP-";
                }
                if ($value == 2){
                    $chedoCPHT = $report_status;
                    $value_type .= "CPHT-";
                }
                if ($value == 3){
                    $chedoHTAT = $report_status;
                    $value_type .= "HTAT-";
                }
                if ($value == 4){
                    $chedoHTBT = $report_status;
                    $value_type .= "HTBT-";
                }
                if ($value == 5){
                    $chedoHSKT = $report_status;
                    $value_type .= "HSKT-";
                }
                if ($value == 6){
                    $chedoHTATHS = $report_status;
                    $value_type .= "HTATHS-";
                }
                if ($value == 7){
                    $chedoHSDTTS = $report_status;
                    $value_type .= "HSDTTS-";
                }
                if ($value == 8){
                    $chedoHBHSDTNT = $report_status;
                    $value_type .= "HBHSDTNT-";
                }
                if ($value == 9){
                    $chedoNGNA = $report_status;
                }
            }
            $value_type = substr($value_type,0,strlen($value_type) - 1);
            $getDataHS = [];
            $dataHS = [];     

            lapcongvanCapPhong::insertTien_modify($report_name, $levelUser, $time, $user, $current_date, $chedoMGHP, $chedoCPHT, $chedoHTAT, $chedoHTBT, $chedoHSKT, $chedoHSDTTS, $chedoHTATHS, $chedoHBHSDTNT, $report_status,$autoCode,$value_type);
                    

            $insert_HSBC = 0;
                $getTienProfile = null;
                if($report_status == 1){
                    if($levelUser == 2){
                        $getTienProfile = DB::table('qlhs_baocaokinhphi_PTC');
                    }else if($levelUser == 3){
                        $getTienProfile = DB::table('qlhs_baocaokinhphi_SGD');
                    }else if($levelUser == 4){
                        $getTienProfile = DB::table('qlhs_baocaokinhphi_SGD');
                    }
                }else if($report_status == 2){
                    if($levelUser == 2){
                        $getTienProfile = DB::table('qlhs_tralaikinhphi_PGD');
                    }else if($levelUser == 3){
                        $getTienProfile = DB::table('qlhs_tralaikinhphi_PTC');
                    }else if($levelUser == 4){
                        $getTienProfile = DB::table('qlhs_tralaikinhphi_SGD');
                    }
                }
                //echo '++>'.$autoCode.'-'.$schools_id.'-'.$getReportYear->report;
                $getTienProfile =  $getTienProfile->where('bckp_name', $autoCode);
                if($report_status == 1){
                    $getTienProfile = $getTienProfile
                    ->where('bckp_truong', $schools_id)
                    ->where('bckp_khoilop',$getReportYear->report);
                }
                $getTienProfile = $getTienProfile->count();
                echo 'tong so: '.$getTienProfile;
                if ($getTienProfile > 0) {
                    // Công văn duyệt
                    if ($report_status == 1) {
                        // Sở
                        if ($levelUser == 4) {
                            $insert_HSBC = DB::table('qlhs_hosobaocao')
                            ->insert([
                                'report_name' => $autoCode,
                                'report_name_text' => $name,
                                'report_type' =>$value_type,
                                'report_date' => $current_date,
                                'created_at' => $current_date,
                                'updated_at' => $current_date,
                                'create_userid' => $user,
                                'update_userid' => $user,
                                'report_year' => $getReportYear->report_year,
                                'report_id_truong' => $schools_id,
                                'report_cap_gui' => $levelUser,
                                'report_cap_status' => 1,
                                'report_cap_nhan' => $levelUser,
                                'report_name_new' => $report_name,
                                'report_total' => $getTienProfile,
                                'report_note' => $note,
                                'report_value' => bussinessClass::autoCodeFirst($levelUser,$user),
                                'number' => bussinessClass::autoCodeNumber($levelUser,$user)
                                ]);
                        }
                        else { // Công văn của các cáp dưới
                          
                            $insert_HSBC = DB::table('qlhs_hosobaocao')
                            ->insert([
                                'report_name' => $autoCode,
                                'report_name_text' => $name,
                                'report_type' =>$value_type,
                                'report_date' => $current_date,
                                'created_at' => $current_date,
                                'updated_at' => $current_date,
                                'create_userid' => $user,
                                'update_userid' => $user,
                                'report_year' => $getReportYear->report_year,
                                'report_id_truong' => $schools_id,
                                'report_cap_gui' => $levelUser,
                                'report_cap_status' => 0,
                                'report_cap_nhan' => $levelUser + 1,
                                'report_name_new' => $report_name,
                                'report_total' => $getTienProfile,
                                'report_note' => $note,
                                'report' => $getReportYear->report,//Khối lớp
                                'report_value' => bussinessClass::autoCodeFirst($levelUser,$user),
                                'number' => bussinessClass::autoCodeNumber($levelUser,$user)
                                ]);
                            
                        }

                    }else {
                        $cap_tra = $levelUser - 1;
                        if($levelUser == 3){
                           $check = DB::table('qlhs_hosobaocao')
                            ->where('report_name',$report_name)
                            ->select('report_cap_gui')->first();
                            $cap_tra = $check->report_cap_gui != null ? $check->report_cap_gui : $cap_tra;
                            $cvtl = DB::table('qlhs_tralaikinhphi_PTC')
                            ->where('bckp_name', $autoCode)
                            ->groupBy('bckp_truong','bckp_khoilop','bckp_name')
                            ->select(
                                DB::raw('COUNT(bckp_profile_id) as total'),
                                'bckp_truong','bckp_khoilop','bckp_name'
                            )->get();
                            DB::beginTransaction();
                            foreach ($cvtl as $key => $value) {
                               $insert_HSBC = DB::table('qlhs_hosobaocao')
                                ->insert([
                                    'report_name' => $autoCode,
                                    'report_name_text' => $name,
                                    'report_type' =>$value_type,
                                    'report_date' => $current_date,
                                    'created_at' => $current_date,
                                    'updated_at' => $current_date,
                                    'create_userid' => $user,
                                    'update_userid' => $user,
                                    'report_year' => $getReportYear->report_year,
                                    'report_id_truong' => $value->bckp_truong,
                                    'report' => $value->bckp_khoilop,
                                    'report_cap_gui' => $levelUser,
                                    'report_cap_status' => 2,
                                    'report_cap_nhan' => $cap_tra,
                                    'report_name_new' => $report_name,
                                    'report_total' => $value->total,
                                    'report_note' => $note,
                                    'report_value' => bussinessClass::autoCodeFirstCallback($levelUser,$user),
                                    'number' => bussinessClass::autoCodeNumberCallback($levelUser,$user)
                                    ]);
                            }
                            DB::commit(); 
                        }else{
                            $insert_HSBC = DB::table('qlhs_hosobaocao')
                            ->insert([
                                'report_name' => $autoCode,
                                'report_name_text' => $name,
                                'report_type' =>$value_type,
                                'report_date' => $current_date,
                                'created_at' => $current_date,
                                'updated_at' => $current_date,
                                'create_userid' => $user,
                                'update_userid' => $user,
                                'report_year' => $getReportYear->report_year,
                                'report_id_truong' => $schools_id,
                                'report' => $getReportYear->report,
                                'report_cap_gui' => $levelUser,
                                'report_cap_status' => 2,
                                'report_cap_nhan' => $cap_tra,
                                'report_name_new' => $report_name,
                                'report_total' => $getTienProfile,
                                'report_note' => $note,
                                'report_value' => bussinessClass::autoCodeFirstCallback($levelUser,$user),
                                'number' => bussinessClass::autoCodeNumberCallback($levelUser,$user)
                                ]);
                        }
                        
                    }

                
                $mess = new qlhs_message();    
                $mess->type = 0;
                if($report_status == 1){
                    $mess->message_text = "Công văn mới số : ".$autoCode;
                    $mess->school_id = $schools_id;
                    $mess->cap_gui = $levelUser;
                    $mess->cap_nhan = $levelUser + 1;
                }else{
                    $mess->message_text = "Công văn trả lại : ".$autoCode;
                    $mess->school_id = $schools_id;
                    $mess->cap_gui = $levelUser;
                    $mess->cap_nhan = $getReportYear->report_cap_gui;
                }  
                $mess->report_name = $autoCode;                  
                $mess->save(); 
            }      
        } catch (Exception $e) {
            echo "cap truong lap cong van loi : ".$e;
        }
    }
}
