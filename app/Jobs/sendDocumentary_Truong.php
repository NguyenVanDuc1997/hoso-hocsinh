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
use App\Models\qlhs_message;
use App\Models\HoSoBaoCao;

class sendDocumentary_Truong implements ShouldQueue
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

    protected $school;
    protected $school_type;
    protected $nam;
    protected $loaicv;
    protected $ten;
    protected $ghichu;
    protected $arrChedo;
    protected $getProfile;
    protected $unit;
    protected $cap;
    protected $user;
    protected $congvan;
    protected $string_cv;
    protected $hk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($school_ids,$school_types,$years,$loaicongvans,$names,$notes,$chedo,$getProfiles,$units,$capnhans,$users,$scv=null,$value_type,$hocky)
    {
        $this->school = $school_ids;
        $this->school_type = $school_types;
        $this->nam = $years;
        $this->loaicv = $loaicongvans;
        $this->ten = $names;
        $this->ghichu = $notes;
        $this->arrChedo = $chedo;
        $this->getProfile = $getProfiles;
        $this->unit = $units;
        $this->cap = $capnhans;
        $this->user = $users;
        $this->congvan = $scv;
        $this->string_cv = $value_type;
        $this->hk = $hocky;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(2);

        try {
            echo 'start ==> ';
            $arrChedo = $this->arrChedo;
            $khoi = $this->unit;
            $school_id = $this->school;
            $year = $this->nam;
            $capnhan = $this->cap;
            $loaicongvan = $this->loaicv;
            $name = $this->ten;
            $note = $this->ghichu;
            $getProfileBySchool = $this->getProfile;
            $current_date = Carbon::now();
            $report_name = $this->congvan;
            echo "cap truong lap cong van : ".$report_name." => ";
            $current_user_id = $this->user;
        
            $chedoMGHP = 0;
            $chedoCPHT = 0;
            $chedoHTAT = 0;
            $chedoHTBT = 0;
            $chedoHSKT = 0;
            $chedoHSDTTS = 0;
            $chedoHTATHS = 0;
            $chedoHBHSDTNT = 0;

            $value_type = $this->string_cv;
            // Tổng hợp chế độ cần lập
            foreach ($arrChedo as $value) {
                if ($value == 1){
                    $chedoMGHP = 1;
                }
                if ($value == 2){
                    $chedoCPHT = 1;
                }
                if ($value == 3){
                    $chedoHTAT = 1;
                }
                if ($value == 4){
                    $chedoHTBT = 1;
                }
                if ($value == 5){
                    $chedoHSKT = 1;
                }
                if ($value == 6){
                    $chedoHTATHS = 1;
                }
                if ($value == 7){
                    $chedoHSDTTS = 1;
                }
                if ($value == 8){
                    $chedoHBHSDTNT = 1;
                }
            }
            
            $st = 0;
            if($loaicongvan == 2){
                $st = 2;
            }
            echo 'Tao cong van '.$st.' ==> ';
            echo 'Tong so: '.count($getProfileBySchool).' ==> ';
            $insert_HSBC = new HoSoBaoCao();
            $insert_HSBC->report_name = $report_name;
            $insert_HSBC->report_value = bussinessClass::autoCodeFirst(1,$school_id);
            $insert_HSBC->report_type = $value_type;
            $insert_HSBC->report_name_text = $name;
            $insert_HSBC->report_date = $current_date;
            $insert_HSBC->create_userid = $current_user_id;
            $insert_HSBC->report_year = $year;
            $insert_HSBC->report_id_truong = $school_id;
            $insert_HSBC->report_note = $note;
            $insert_HSBC->report_cap_gui = 1;
            $insert_HSBC->report_cap_status = 0;
            $insert_HSBC->report_status = $st;
            
            $insert_HSBC->report_cap_nhan = $capnhan;
            $insert_HSBC->report_total = count($getProfileBySchool);
            $insert_HSBC->report_name_new = $school_id.'-'.$year;
            $insert_HSBC->number = bussinessClass::autoCodeNumber(1,$school_id);
            

            if (count($getProfileBySchool) > 0) {
                try{
                    if($khoi == null || $khoi == '' || $khoi == 'undefined'){
                        $khoi = 0;
                    }
                    $insert_HSBC->report = $khoi;//Khối lớp
                    $insert_HSBC->save();
                    DB::statement("call congvan_truong_gui_PGD_PTC('".$year."','".$year."-".($year+1)."',".$school_id.",0,".$loaicongvan.",".$chedoMGHP.",".$chedoCPHT.",".$chedoHTAT.",".$chedoHTBT.",".$chedoHSKT.",".$chedoHSDTTS.",".$chedoHTATHS.",".$chedoHBHSDTNT.",".$this->hk.",'".$report_name."',".$capnhan.",".$khoi.")");

                    DB::statement("call update_congvan_truong_gui_PGD_PTC('".$year."',".$chedoMGHP.",".$chedoCPHT.",".$chedoHTAT.",".$chedoHTBT.",".$chedoHSKT.",".$chedoHSDTTS.",".$chedoHTATHS.",".$chedoHBHSDTNT.",".$capnhan.",'".$report_name."',".$current_user_id.",".$loaicongvan.",".$khoi.")");
                }catch (Illuminate\Database\QueryException $e){
                    echo 'er : '.$e->getMessage();
                }
            }else{
                echo "Danh sách công văn trống";
            }
            $mess = new qlhs_message();    
            $mess->type = 0;
            $mess->message_text = "Công văn mới số : ".$report_name;
            $mess->school_id = $school_id;
            $mess->cap_gui = $current_user_id;
            $mess->cap_nhan = $capnhan;
            $mess->report_name = $report_name;                  
            $mess->save(); 
        } catch (Exception $e) {
            echo "cap truong lap cong van loi : ".$e;
        }
    }
}
