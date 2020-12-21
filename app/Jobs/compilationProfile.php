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
use App\Models\TongHopCheDo_Truong;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class compilationProfile implements ShouldQueue
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

    protected $yearJob;
    protected $profileIdJob;
    protected $schoolidJob;
    protected $thcd_IDJob;
   // protected $end_yearJob;
    protected $userJob;
   // protected $typeJob;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($yearj, $profileIdj, $schoolidj, $thcd_IDj=null,$userj)
    {
        $this->yearJob = $yearj;
        $this->profileIdJob = $profileIdj;
        $this->schoolidJob = $schoolidj;
        $this->thcd_IDJob = $thcd_IDj;
       // $this->end_yearJob = $end_yearj;
        $this->userJob = $userj;
        //$this->typeJob = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(2);
        //if($this->typeJob == 1){
        //     echo 'Start update profile id(insert): '.$this->profileIdJob.' ==> ';
        //}else if($this->typeJob == 2){
        //    echo 'Start insert profile id(insert): '.$this->profileIdJob.' ==> ';
        //}else if($this->typeJob == 3){
            echo 'Start insert profile id(update): '.$this->profileIdJob.' ==> ';
        //}
        $year = $this->yearJob;
        $profileId = $this->profileIdJob;
        $schoolid = $this->schoolidJob;
        $thcd_ID = $this->thcd_IDJob;
      //  $end_year = $this->end_yearJob;
        $user = $this->userJob;
        $currentdate = Carbon::now();
            $PROFILEID = $profileId;
            // Khởi tạo giá trị
            $MONEYMGHP = 0;// Hỗ trợ miễn giảm học phí học kỳ 1
            $MONEYCPHT = 0;// Hỗ trợ chi phí học tập học kỳ 1
            $MONEYHTAT = 0;// Hỗ trợ ăn trưa trẻ em học kỳ 1
            $MONEYHTBTTIENAN = 0;// Hỗ trợ học sinh bán trú - tiền ăn học kỳ 1
            $MONEYHTBTTIENO = 0;// Hỗ trợ học sinh bán trú - tiền ở học kỳ 1
            $MONEYHTBTVHTT = 0;// Hỗ trợ học sinh bán trú VHTT học kỳ 1
            $MONEYHSKTHOCBONG = 0;// Hỗ trợ học sinh khuyết tật - học bổng học kỳ 1
            $MONEYHSKTDDHT = 0;// Hỗ trợ học sinh khuyết tật - dụng cụ học tập học kỳ 1
            $MONEYHSDTTS = 0;// Hỗ trợ học sinh dân tộc thiểu số học kỳ 1
            $MONEYHTATHS = 0;// Hỗ trợ tiền ăn học sinh học kỳ 1
            $MONEYHBHSDTNT = 0;// Hỗ trợ học sinh dân tộc nội trú học kỳ 1
            $MONEYHTHT = 0;// Hỗ trợ học tập NQ57 - CP học kỳ 1

            $MONEYMGHPHK2 = 0;
            $MONEYCPHTHK2 = 0;
            $MONEYHTATHK2 = 0;
            $MONEYHTBTTIENANHK2 = 0;
            $MONEYHTBTTIENOHK2 = 0;
            $MONEYHTBTVHTTHK2 = 0;
            $MONEYHSKTHOCBONGHK2 = 0;
            $MONEYHSKTDDHTHK2 = 0;
            $MONEYHSDTTSHK2 = 0;
            $MONEYHTATHSHK2 = 0;
            $MONEYHBHSDTNTHK2 = 0;
            $MONEYHTHTHK2 = 0;
        try {
            // Kiểm tra khối lớp học sinh
            $getUnit = DB::table('qlhs_class')
                ->join('qlhs_profile', 'profile_class_id','class_id')
                ->where('profile_id',$profileId)
                ->select('class_unit_id')
                ->first();
            // Kiếm tra loại trường học sinh
            $getSchoolType = DB::table('qlhs_schools_history')
                ->where('type_his_school_id',$schoolid)
                ->where('type_his_startdate','<=',$year.'-09-05')
                ->where(function($q) use ($year){
                    $q->orWhere('type_his_enddate','>=',$year.'-09-05')
                    ->orWhere('type_his_enddate',null);
                })
                ->select('type_his_type_id')
                ->first();

//-------------------------------------------------------------------MGHP--------------------------------------------------------------------

            if ($getUnit && $getUnit->class_unit_id != 2) {
                try{
                    
                    $dataMGHP2 = DB::table('qlhs_profile_history AS ph')
                        ->leftJoin('history_profile_site AS his', 'his.p_id','ph.history_profile_id' )

                        ->leftJoin('qlhs_profile', 'ph.history_profile_id','qlhs_profile.profile_id')
 
                        ->leftJoin('years_months_kp_dt AS kp', 'kp.idXa', DB::raw('his.site_phuongxa AND ph.unit_class = idKhoi AND YEAR (kp.date_con) <= '.($year + 1).' AND '.$year.' <= YEAR (kp.date_con)'))

                        ->leftJoin('qlhs_profile_subject AS hisSub', 'hisSub.profile_subject_profile_id', 'qlhs_profile.profile_id')

                        ->leftJoin('qlhs_subject_history AS s', 's.subject_history_subject_id', 'hisSub.profile_subject_subject_id')

                        ->select('kp.value_m', 'kp.months', 'kp.years', 'kp.idKhoi', 'kp.idXa', 'kp.date_con', 'hisSub.start_date', 'hisSub.end_date', 'his.start_date AS start_site', 'his.end_date AS end_site', 'hisSub.profile_subject_subject_id','ph.P_His_startdate','ph.p_His_enddate')
                        ->where('ph.history_year',$year.'-'.($year + 1))
                        ->where('profile_id', $profileId)
                        ->whereIn('profile_subject_subject_id', [ 28, 35, 36, 73, 38, 39, 34, 40, 100, 49, 41, 74,101,100,103]);
                    
                    $dataMGHP22 = DB::table(DB::raw("({$dataMGHP2->toSql()}) as m"))->mergeBindings( $dataMGHP2 )
                        ->select('m.years', 'm.profile_subject_subject_id',
                            DB::raw('CASE WHEN m.profile_subject_subject_id IN (28,35,100,38,73,39,103) AND m.years = '.$year.'  AND m.profile_subject_subject_id NOT IN (101,41,40) THEN m.value_m WHEN m.profile_subject_subject_id IN (101) AND m.profile_subject_subject_id NOT IN (28,35,100,38,73,39,41,40) AND m.years = '.$year.' THEN m.value_m * 7 / 10 WHEN m.profile_subject_subject_id IN (40, 41) AND m.profile_subject_subject_id NOT IN (28,35,100,38,73,39,101) AND m.years = '.$year.' THEN m.value_m * 5 / 10 ELSE 0 END nhu_cau'),

                            DB::raw('CASE WHEN m.profile_subject_subject_id IN (28,35,100,38,73,39) AND m.years = '.($year + 1).' AND m.profile_subject_subject_id NOT IN (101,41,40) THEN m.value_m WHEN m.profile_subject_subject_id IN (101)  AND m.profile_subject_subject_id NOT IN (28,35,100,38,73,39,41,40)   AND m.years = '.($year + 1).' THEN m.value_m * 7 / 10 WHEN m.profile_subject_subject_id IN (40, 41) AND m.profile_subject_subject_id NOT IN (28,35,100,38,73,39,101) AND m.years = '.($year + 1).' THEN m.value_m * 5 / 10 ELSE 0   END du_toan'))
                        ->where(DB::raw(' ((CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_date, DATETIME ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_date, DATETIME ) OR m.end_date IS NULL ) ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_site, DATETIME )AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_site, DATETIME ) OR m.end_site IS NULL ) ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.P_His_startdate, DATETIME ) AND CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) < CONVERT ( m.p_His_enddate, DATETIME ) ))  AND ( ( m.years = '.$year.' AND m.months IN (9, 10, 11, 12) ) OR ( m.years'), '=', DB::raw(($year + 1).' AND m.months IN (1, 2, 3, 4, 5)))'))
                        ->groupBy('m.date_con', 'm.months', 'm.years','m.profile_subject_subject_id');

                    $dataMGHP222 = DB::table(DB::raw("({$dataMGHP22->toSql()}) as k"))
                        ->mergeBindings( $dataMGHP22 )
                        ->select('k.profile_subject_subject_id',
                            DB::raw('SUM(k.nhu_cau) as NhuCau'),
                            DB::raw('SUM(k.du_toan) as DuToan'))->groupBy('k.profile_subject_subject_id')->orderBy(DB::raw('SUM(k.nhu_cau)'),'desc')->orderBy(DB::raw('SUM(k.du_toan)'),'desc')->get();

                    if (!is_null($dataMGHP222) && !empty($dataMGHP222) && count($dataMGHP222) > 0) {
                        $checkMGHP222 = 0;
                        foreach ($dataMGHP222 as $value) {
                            if($checkMGHP222 == 0 || $checkMGHP222 != $value->profile_subject_subject_id){
                                if($value->NhuCau == 0 && $value->DuToan == 0){
                                    $checkMGHP222 = 0;

                                }else{
                                    echo "MGHP1 => ".$MONEYMGHP;
                                    $MONEYMGHP += $value->NhuCau;
                                    $MONEYMGHPHK2 += $value->DuToan;
                                    $checkMGHP222 = $value->profile_subject_subject_id;
                                }
                                
                            }
                        }
                    }
                    echo "MGHP => ";
                }catch (Exception $e) {
                    echo "MGHP: ".$e;
                }
            }

//-------------------------------------------------------------------CPHT--------------------------------------------------------------------
            
            try{

                $dataCPHT2 = DB::table('qlhs_profile_history AS ph')
                        ->leftJoin('qlhs_profile_subject AS his', 'his.profile_subject_profile_id','ph.history_profile_id' )

                        ->leftJoin('qlhs_profile', 'ph.history_profile_id','qlhs_profile.profile_id')

                    ->leftJoin('qlhs_schools_history as sh', 'sh.type_his_school_id','ph.history_school_id')
                        
                    ->leftJoin('qlhs_subject_history as s', 's.subject_history_subject_id', '=','his.profile_subject_subject_id')

                    ->leftJoin('years_months_kp as kp','kp.id_doituong', '=', DB::raw('s.subject_history_group_id AND YEAR (kp.date_con) <= '.($year + 1).' AND '.$year.' <= YEAR (kp.date_con) AND sh.type_his_type_id = kp.id_schooltype'))

                    ->where('ph.history_year',$year.'-'.($year + 1))
                    ->where('qlhs_profile.profile_id', $profileId)
                    ->where('s.subject_history_group_id', '92')
                
                    ->select('his.profile_subject_profile_id', 'kp.*', 'his.start_date', 'his.end_date', 'his.profile_subject_subject_id','ph.P_His_startdate','ph.p_His_enddate');

                $dataCPHT22 = DB::table(DB::raw("({$dataCPHT2->toSql()}) as m"))
                    ->mergeBindings( $dataCPHT2 )
                    ->select('m.row_id',
                        DB::raw('CASE WHEN m.months in (9,10,11,12) THEN SUM(m.value_m) END as NhuCau'),
                        DB::raw('CASE WHEN m.months in (1,2,3,4,5) THEN SUM(m.value_m) END as DuToan'),
                        'm.months',
                        'm.years',
                        'm.id_schooltype',
                        'm.id_doituong',
                        'm.start_date',
                        'm.end_date',
                        'm.profile_subject_subject_id')
                    ->where(DB::raw('((CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_date, DATETIME ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_date, DATETIME ) OR m.end_date IS NULL )) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.P_His_startdate, DATETIME ) AND CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) < CONVERT ( m.p_His_enddate, DATETIME ) ) ) AND ((m.years = '.$year.' AND m.months in (9,10,11,12)) or (m.years'), '=', DB::raw(($year + 1).' AND m.months in (1,2,3,4,5)))'))
                    ->groupBy('m.row_id',
                        'm.months',
                        'm.years',
                        'm.id_schooltype',
                        'm.id_doituong',
                        'm.start_date',
                        'm.end_date',
                        'm.profile_subject_subject_id');

                $dataCPHT222 = DB::table(DB::raw("({$dataCPHT22->toSql()}) as k"))
                    ->mergeBindings( $dataCPHT22 )
                    ->leftJoin('qlhs_group as g', 'g.group_id', 'k.id_doituong')
                    ->select('k.id_doituong',
                        'g.group_name',
                        DB::raw('SUM(k.NhuCau) as NhuCau'),
                        DB::raw('IFNULL(SUM(k.DuToan),0) as DuToan'))
                    ->groupBy('k.id_doituong',
                        'g.group_name')->get();

                if (count($dataCPHT222) > 0) {                                    
                    foreach ($dataCPHT222 as $value) {

                        $MONEYCPHT += $value->NhuCau;
                        $MONEYCPHTHK2 += $value->DuToan;
                    }
                }
                echo "CPHT => ";
            }catch (Exception $e) {
                    echo "CPHT: ".$e;
            }

//-------------------------------------------------------------------HTATTE--------------------------------------------------------------------
            
            if ($getUnit && $getUnit->class_unit_id == 1) {
                try{
                    $dataHTAT2 = DB::table('qlhs_profile_history AS ph')
                        ->leftJoin('qlhs_profile_subject AS his', 'his.profile_subject_profile_id','ph.history_profile_id' )

                        ->leftJoin('qlhs_profile', 'ph.history_profile_id','qlhs_profile.profile_id')

                        ->leftJoin('qlhs_schools_history as sh', 'sh.type_his_school_id','ph.history_school_id')
                            
                        ->leftJoin('qlhs_subject_history as s', 's.subject_history_subject_id', '=','his.profile_subject_subject_id')

                        ->leftJoin('years_months_kp as kp','kp.id_doituong', '=', DB::raw('s.subject_history_group_id AND YEAR (kp.date_con) <= '.($year + 1).' AND '.$year.' <= YEAR (kp.date_con) AND sh.type_his_type_id = kp.id_schooltype'))

                        ->where('ph.history_year',$year.'-'.($year + 1))
                        ->where('qlhs_profile.profile_id', $profileId)
                        ->whereIn('s.subject_history_subject_id', [73,34,28,38,101])
                    
                        ->select('his.profile_subject_profile_id', 'kp.*', 'his.start_date', 'his.end_date', 'his.profile_subject_subject_id','ph.P_His_startdate','ph.p_His_enddate');

                    $dataHTAT22 = DB::table(DB::raw("({$dataHTAT2->toSql()}) as m"))
                        ->mergeBindings( $dataHTAT2 )
                        ->select('m.row_id',
                            DB::raw('CASE WHEN m.months in (9,10,11,12) THEN SUM(m.value_m) END as NhuCau'),
                            DB::raw('CASE WHEN m.months in (1,2,3,4,5) THEN SUM(m.value_m) END as DuToan'),
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id')
                        ->where(DB::raw('((CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_date, DATETIME ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_date, DATETIME ) OR m.end_date IS NULL ) ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.P_His_startdate, DATETIME ) AND CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) < CONVERT ( m.p_His_enddate, DATETIME ) ) ) AND ((m.years = '.$year.' AND m.months in (9,10,11,12)) or (m.years'), '=', DB::raw(($year + 1).' AND m.months in (1,2,3,4,5)))'))
                        ->groupBy('m.row_id',
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id');

                    $dataHTAT222 = DB::table(DB::raw("({$dataHTAT22->toSql()}) as k"))
                        ->mergeBindings( $dataHTAT22 )
                        ->leftJoin('qlhs_group as g', 'g.group_id', 'k.id_doituong')
                        ->select('k.id_doituong',
                            'g.group_name','k.profile_subject_subject_id',
                            DB::raw('SUM(k.NhuCau) as NhuCau'),
                            DB::raw('SUM(k.DuToan) as DuToan'))
                        ->groupBy('k.id_doituong',
                            'g.group_name','k.profile_subject_subject_id')->orderBy(DB::raw('SUM(k.NhuCau)'),'desc')->orderBy(DB::raw('SUM(k.DuToan)'),'desc')->get();
                       
                    if (!is_null($dataHTAT222) && !empty($dataHTAT222) && count($dataHTAT222) > 0) {
                        $checkHTAT222 = 0;
                        foreach ($dataHTAT222 as $value) {
                            if ($value->id_doituong == 93) {
                                if($checkHTAT222 == $value->profile_subject_subject_id || $checkHTAT222 == 0){
                                    if($value->NhuCau == 0 &&  $value->DuToan == 0){
                                        $checkHTAT222 = 0;
                                    }else{
                                        $MONEYHTAT += $value->NhuCau;
                                        $MONEYHTATHK2 += $value->DuToan;
                                        $checkHTAT222  = $value->profile_subject_subject_id;
                                    }
                                }
                            }
                        }
                    }
                    echo "HTAT => ";
                }catch (Exception $e) {
                    echo "HTAT: ".$e;
                }
            }
            
//-------------------------------------------------------------------HTBT--------------------------------------------------------------------
            if ($getSchoolType && $getSchoolType->type_his_type_id != 4) {                
                try{
                    $dataHTBT2 = DB::table('qlhs_profile_history as ph')
                        ->leftJoin('qlhs_profile_subject as his', 'his.profile_subject_profile_id', 'ph.history_profile_id')
                        ->leftJoin('qlhs_profile','ph.history_profile_id','qlhs_profile.profile_id')
                        ->leftJoin('qlhs_schools_history as sh', 'sh.type_his_school_id','ph.history_school_id')
                        ->leftJoin('qlhs_subject_history as s', 's.subject_history_subject_id','his.profile_subject_subject_id')
                        ->leftJoin('years_months_kp as kp',function($q) use($year){
                            $q->on('kp.id_doituong','s.subject_history_group_id')
                            ->whereYear('kp.date_con','<=',($year + 1))
                            ->whereYear('kp.date_con','>=',$year)
                            ->on('sh.type_his_type_id','kp.id_schooltype');
                        })
                        ->where('ph.history_year',$year.'-'.($year + 1))
                        ->where('qlhs_profile.profile_id', $profileId)
                        // ->where(function($q){
                        //     $q->orWhere('ph.history_statute_57',1)
                        //     ->orWhereIn('s.subject_history_subject_id', [46]);
                        // })
                        ->where('s.subject_history_subject_id',46)
                    
                        ->select('his.profile_subject_profile_id', 'kp.*', 'his.start_date', 'his.end_date', 'his.profile_subject_subject_id', 'ph.history_statute_116 as profile_bantru','ph.P_His_startdate','ph.p_His_enddate','ph.history_statute_57');

                    $dataHTBT22 = DB::table(DB::raw("({$dataHTBT2->toSql()}) as m"))
                        ->mergeBindings( $dataHTBT2 )
                        ->select('m.row_id',
                            DB::raw('CASE WHEN m.months in (9,10,11,12) THEN SUM(m.value_m) END as NhuCau'),
                            DB::raw('CASE WHEN m.months in (1,2,3,4,5) THEN SUM(m.value_m) END as DuToan'),
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id', 'm.profile_bantru','m.history_statute_57')
                        ->where(DB::raw('((CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_date, DATETIME ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_date, DATETIME )OR m.end_date IS NULL )) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.P_His_startdate, DATETIME ) AND CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) < CONVERT ( m.p_His_enddate, DATETIME ) ) )  AND ((m.years = '.$year.' AND m.months in (9,10,11,12)) or (m.years'), '=', DB::raw(($year + 1).' AND m.months in (1,2,3,4,5)))'))
                        ->groupBy('m.row_id',
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id', 'm.profile_bantru','m.history_statute_57');

                    $dataHTBT222 = DB::table(DB::raw("({$dataHTBT22->toSql()}) as k"))
                        ->mergeBindings( $dataHTBT22 )
                        ->leftJoin('qlhs_group as g', 'g.group_id', 'k.id_doituong')
                        ->select('k.id_doituong','k.history_statute_57',
                            'g.group_name', 'k.profile_bantru',
                            DB::raw('SUM(k.NhuCau) as NhuCau'),
                            DB::raw('SUM(k.DuToan) as DuToan'))
                        ->groupBy('k.id_doituong',
                            'g.group_name', 'k.profile_bantru','k.history_statute_57')->get();

                    // if (!is_null($dataHTBT111) && !empty($dataHTBT111) && count($dataHTBT111) > 0) {
                    //     foreach ($dataHTBT111 as $value) {
                    //         //Hỗ trợ học sinh bán trú_VHTT, Tủ thuốc và trường bán trú
                    //         if ($value->id_doituong == 115 && $getSchoolType->type_his_type_id == 2) {
                    //             $MONEYHTBTVHTT += $value->NhuCau;
                    //             $MONEYHTBTVHTTHK2 += $value->DuToan;
                    //         }
                    //         //NĐ số 116-NĐ-CP_HT tiền ăn
                    //         if ($value->id_doituong == 94) {
                    //             $MONEYHTBTTIENAN += $value->NhuCau;
                    //             $MONEYHTBTTIENANHK2 += $value->DuToan;
                    //         }
                    //         //NĐ số 116-NĐ-CP_HT tiền ở và ở trong trường
                    //         if ($value->id_doituong == 98 && $value->profile_bantru == 0) {
                    //             $MONEYHTBTTIENO += $value->NhuCau;
                    //             $MONEYHTBTTIENOHK2 += $value->DuToan;
                    //         }
                    //     }
                    // }

                    if (!is_null($dataHTBT222) && !empty($dataHTBT222) && count($dataHTBT222) > 0) {
                        foreach ($dataHTBT222 as $value) {

                            if ($value->id_doituong == 115 && $getSchoolType->type_his_type_id == 2) {
                                $MONEYHTBTVHTT += round($value->NhuCau/1000)*1000;
                                $MONEYHTBTVHTTHK2 += round($value->DuToan/1000)*1000;
                            }

                            if ($value->id_doituong == 94){// || ($value->history_statute_57 == 1 && $value->id_doituong == 118)) {
                          //  if ($value->id_doituong == 94){
                                $MONEYHTBTTIENAN += $value->NhuCau;
                                $MONEYHTBTTIENANHK2 += $value->DuToan;
                            }
                            if ($value->id_doituong == 98 && $value->profile_bantru == 0) {
                                $MONEYHTBTTIENO += $value->NhuCau;
                                $MONEYHTBTTIENOHK2 += $value->DuToan;
                            }
                        }
                    }
                    echo "HTBT => ";
                }catch (Exception $e) {
                    echo "HTBTTIENO: ".$e;
                }
            }

//-------------------------------------------------------------------HSKT--------------------------------------------------------------------
            echo "HSKT => ";
            try{
                $dataHSKT2 =  DB::table('qlhs_profile_history AS ph')
                        ->leftJoin('qlhs_profile_subject AS his', 'his.profile_subject_profile_id','ph.history_profile_id' )
                        ->leftJoin('qlhs_profile', 'ph.history_profile_id','qlhs_profile.profile_id')
                        ->leftJoin('qlhs_schools_history as sh', 'sh.type_his_school_id','ph.history_school_id')
                        ->leftJoin('qlhs_subject_history as s', 's.subject_history_subject_id', '=','his.profile_subject_subject_id')
                        ->leftJoin('years_months_kp as kp','kp.id_doituong', '=', DB::raw('s.subject_history_group_id AND YEAR (kp.date_con) <= '.($year + 1).' AND '.$year.' <= YEAR (kp.date_con) AND sh.type_his_type_id = kp.id_schooltype'))
                        ->where('ph.history_year',$year.'-'.($year + 1))
                        ->where('qlhs_profile.profile_id', $profileId)
                        ->whereIn('s.subject_history_group_id', [95, 100])
                        ->select('his.profile_subject_profile_id', 'kp.*', 'his.start_date', 'his.end_date', 'his.profile_subject_subject_id','ph.P_His_startdate','ph.p_His_enddate');

                $dataHSKT22 = DB::table(DB::raw("({$dataHSKT2->toSql()}) as m"))
                    ->mergeBindings( $dataHSKT2 )
                    ->select('m.row_id',
                        DB::raw('CASE WHEN m.months in (9,10,11,12) THEN SUM(m.value_m) END as NhuCau'),
                        DB::raw('CASE WHEN m.months in (1,2,3,4,5) THEN SUM(m.value_m) END as DuToan'),
                        'm.months',
                        'm.years',
                        'm.id_schooltype',
                        'm.id_doituong',
                        'm.start_date',
                        'm.end_date',
                        'm.profile_subject_subject_id')
                    ->where(DB::raw('((CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_date, DATETIME ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_date, DATETIME ) OR m.end_date IS NULL)) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.P_His_startdate, DATETIME ) AND CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) < CONVERT ( m.p_His_enddate, DATETIME ) ) )  AND ((m.years = '.$year.' AND m.months in (9,10,11,12)) or (m.years'), '=', DB::raw(($year + 1).' AND m.months in (1,2,3,4,5)))'))
                    ->groupBy('m.row_id',
                        'm.months',
                        'm.years',
                        'm.id_schooltype',
                        'm.id_doituong',
                        'm.start_date',
                        'm.end_date',
                        'm.profile_subject_subject_id');

                $dataHSKT222 = DB::table(DB::raw("({$dataHSKT22->toSql()}) as k"))
                    ->mergeBindings( $dataHSKT22 )
                    ->leftJoin('qlhs_group as g', 'g.group_id', 'k.id_doituong')
                    ->select('k.id_doituong',
                        'g.group_name',
                        DB::raw('SUM(k.NhuCau) as NhuCau'),
                        DB::raw('SUM(k.DuToan) as DuToan'))
                    ->groupBy('k.id_doituong',
                        'g.group_name')->get();

                if (!is_null($dataHSKT222) && !empty($dataHSKT222) && count($dataHSKT222) > 0) {
                    foreach ($dataHSKT222 as $value) {
                        if ($value->id_doituong == 95) {
                                $MONEYHSKTHOCBONG += $value->NhuCau;
                                $MONEYHSKTHOCBONGHK2 += $value->DuToan;
                        }
                        if ($value->id_doituong == 100) {                    
                                $MONEYHSKTDDHT += $value->NhuCau;
                                $MONEYHSKTDDHTHK2 += $value->DuToan;
                        }
                    }
                }
            }catch (Exception $e) {
                echo "HSKTDDHT: ".$e;
            }

//-------------------------------------------------------------------HSDTTS------------------------------------------------------------------
            if ($getUnit && $getUnit->class_unit_id == 33) {
                echo "HSDTTS => ";
                try{

                    $dataHSDTTS2 = DB::table('qlhs_profile_history AS ph')
                        ->leftJoin('qlhs_profile_subject AS his', 'his.profile_subject_profile_id','ph.history_profile_id' )

                        ->leftJoin('qlhs_profile', 'ph.history_profile_id','qlhs_profile.profile_id')

                        ->leftJoin('qlhs_schools_history as sh', 'sh.type_his_school_id', '=', 'ph.history_school_id')
                            
                        ->leftJoin('qlhs_subject_history as s', 's.subject_history_subject_id', '=','his.profile_subject_subject_id')

                        ->leftJoin('years_months_kp as kp','kp.id_doituong', '=', DB::raw('s.subject_history_group_id AND YEAR (kp.date_con) <= '.($year + 1).' AND '.$year.' <= YEAR (kp.date_con) AND sh.type_his_type_id = kp.id_schooltype'))

                        ->leftJoin('years_months_by_profile as kphk', 'kphk.id', DB::raw('profile_id AND YEAR (kphk.date_con) <= '.($year + 1).' AND '.$year.' <= YEAR (kphk.date_con)'))

                        ->where('ph.history_year',$year.'-'.($year + 1))
                        ->where('qlhs_profile.profile_id', $profileId)
                        ->where('s.subject_history_group_id', '99')
                        ->groupBy('his.profile_subject_profile_id','kp.row_id','kp.id','kp.value_m','kp.months','kp.years','kp.id_doituong','kp.date_con','his.start_date','his.end_date','his.profile_subject_subject_id','kphk.quanhuyen','kp.id_schooltype','ph.P_His_startdate','ph.p_His_enddate')
                        ->select('his.profile_subject_profile_id', 'kp.*', 'his.start_date', 'his.end_date', 'his.profile_subject_subject_id', 'kphk.quanhuyen','ph.P_His_startdate','ph.p_His_enddate');

                    $dataHSDTTS22 = DB::table(DB::raw("({$dataHSDTTS2->toSql()}) as m"))
                        ->mergeBindings( $dataHSDTTS2 )
                        ->select('m.row_id',
                            DB::raw('CASE WHEN m.months in (9,10,11,12) AND (m.quanhuyen = 100 OR m.quanhuyen = 101) THEN SUM(m.value_m) END as NhuCau'),
                            DB::raw('CASE WHEN m.months in (1,2,3,4,5) AND (m.quanhuyen = 100 OR m.quanhuyen = 101) THEN SUM(m.value_m) END as DuToan'),
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id', 'm.quanhuyen')
                        ->where(DB::raw('((CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_date, DATETIME ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_date, DATETIME ) OR m.end_date IS NULL )) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.P_His_startdate, DATETIME ) AND CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) < CONVERT ( m.p_His_enddate, DATETIME ) ) )  AND ((m.years = '.$year.' AND m.months in (9,10,11,12)) or (m.years'), '=', DB::raw(($year + 1).' AND m.months in (1,2,3,4,5)))'))
                        ->groupBy('m.row_id',
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id', 'm.quanhuyen');

                    $dataHSDTTS222 = DB::table(DB::raw("({$dataHSDTTS22->toSql()}) as k"))
                        ->mergeBindings( $dataHSDTTS22 )
                        ->leftJoin('qlhs_group as g', 'g.group_id', 'k.id_doituong')
                        ->select('k.id_doituong','k.profile_subject_subject_id',
                            'g.group_name', 'k.quanhuyen', 
                            DB::raw('SUM(k.NhuCau) as NhuCau'),
                            DB::raw('SUM(k.DuToan) as DuToan'))
                        ->groupBy('k.id_doituong','k.profile_subject_subject_id',
                            'g.group_name', 'k.quanhuyen')->orderBy(DB::raw('SUM(k.NhuCau)'),'desc')->orderBy(DB::raw('SUM(k.DuToan)'),'desc')->get();


                    if (!is_null($dataHSDTTS222) && !empty($dataHSDTTS222) && count($dataHSDTTS222) > 0) {
                        $checkHSDTTS222 = 0;
                        foreach ($dataHSDTTS222 as $value) {
                            if($value->quanhuyen != null){
                                if($checkHSDTTS222 == 0 || $checkHSDTTS222 == $value->profile_subject_subject_id){
                                    if($value->NhuCau == 0 && $value->DuToan == 0){
                                        $checkHSDTTS222 = 0;
                                    }else{
                                        $MONEYHSDTTS += $value->NhuCau;
                                        $MONEYHSDTTSHK2 += $value->DuToan;
                                        $checkHSDTTS222 = $value->profile_subject_subject_id;
                                    }
                                }
                            }
                        }
                    }
                }catch (Exception $e) {
                    echo "HSDTTS: ".$e;
                }
            }

//--------------------------------------------------------Hỗ trợ ăn trưa cho HS--------------------------------------------------------------
            if ($getSchoolType && $getSchoolType->type_his_type_id == 2) {
                echo "HTATHS => ";
                try{

                    $dataHTATHS2 = DB::table('qlhs_profile_history AS ph')
                        ->leftJoin('qlhs_profile_subject AS his', 'his.profile_subject_profile_id','ph.history_profile_id' )

                        ->leftJoin('qlhs_profile', 'ph.history_profile_id','qlhs_profile.profile_id')

                        ->leftJoin('qlhs_schools_history as sh', 'sh.type_his_school_id', 'ph.history_school_id')
                            
                        ->leftJoin('qlhs_subject_history as s', 's.subject_history_subject_id', '=','his.profile_subject_subject_id')

                        ->leftJoin('years_months_kp as kp','kp.id_doituong', '=', DB::raw('s.subject_history_group_id AND YEAR (kp.date_con) <= '.($year + 1).' AND '.$year.' <= YEAR (kp.date_con) AND sh.type_his_type_id = kp.id_schooltype'))

                        ->where('ph.history_year',$year.'-'.($year + 1))
                        ->where('qlhs_profile.profile_id', $profileId)
                        ->where('history_statute_57',1)
                        ->where('subject_history_group_id','118')
                        ->select('his.profile_subject_profile_id', 'kp.*', 'his.start_date', 'his.end_date', 'his.profile_subject_subject_id','ph.P_His_startdate','ph.p_His_enddate');

                    $dataHTATHS22 = DB::table(DB::raw("({$dataHTATHS2->toSql()}) as m"))
                        ->mergeBindings( $dataHTATHS2 )
                        ->select('m.row_id',
                            DB::raw('CASE WHEN m.months in (9,10,11,12) THEN SUM(m.value_m) END as NhuCau'),
                            DB::raw('CASE WHEN m.months in (1,2,3,4,5) THEN SUM(m.value_m) END as DuToan'),
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id')
                        ->where(DB::raw('((CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_date, DATETIME )  AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_date, DATETIME ) OR m.end_date IS NULL  ))  AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.P_His_startdate, DATETIME )  AND CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) < CONVERT ( m.p_His_enddate, DATETIME ) ) )  AND ((m.years = '.$year.' AND m.months in (9,10,11,12)) or (m.years'), '=', DB::raw(($year + 1).' AND m.months in (1,2,3,4,5)))'))
                        ->groupBy('m.row_id',
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id');

                    $dataHTATHS222 = DB::table(DB::raw("({$dataHTATHS22->toSql()}) as k"))
                        ->mergeBindings( $dataHTATHS22 )
                        ->leftJoin('qlhs_group as g', 'g.group_id', 'k.id_doituong')
                        ->select('k.id_doituong',
                            'g.group_name','k.profile_subject_subject_id',
                            DB::raw('SUM(k.NhuCau) as NhuCau'),
                            DB::raw('SUM(k.DuToan) as DuToan'))
                        ->groupBy('k.id_doituong','k.profile_subject_subject_id',
                            'g.group_name')->get();

                    if (!is_null($dataHTATHS222) && !empty($dataHTATHS222) && count($dataHTATHS222) > 0) {
                        $checkHTATHS222 = 0;
                        foreach ($dataHTATHS222 as $value) {
                            if($checkHTATHS222 == 0 || $checkHTATHS222 == $value->profile_subject_subject_id){
                                if($value->NhuCau == 0 && $value->DuToan == 0){
                                    $checkHTATHS222 = 0;
                                }else{
                                    $MONEYHTATHS += $value->NhuCau;
                                    $MONEYHTATHSHK2 += $value->DuToan;
                                    $checkHTATHS222 = $value->profile_subject_subject_id ;
                                }
                            }
                        }
                    }
                }catch (Exception $e) {
                    echo "HTATHS: ".$e;
                }
            }

//--------------------------------------------------------Học bổng hs dân tộc nội trú--------------------------------------------------------
            if ($getSchoolType && $getSchoolType->type_his_type_id == 4) {// trường nội trú
                echo "HBDTNT => ";
                try{

                    $dataHBHSDTNT2 = DB::table('qlhs_profile_history AS ph')
                        ->leftJoin('qlhs_profile_subject AS his', 'his.profile_subject_profile_id','ph.history_profile_id' )

                        ->leftJoin('qlhs_profile', 'ph.history_profile_id','qlhs_profile.profile_id')

                        ->leftJoin('qlhs_schools_history as sh', 'sh.type_his_school_id', 'ph.history_school_id')
                            
                        ->leftJoin('qlhs_subject_history as s', 's.subject_history_subject_id', 'his.profile_subject_subject_id')

                        ->leftJoin('years_months_kp as kp','kp.id_doituong', '=', DB::raw('s.subject_history_group_id AND YEAR (kp.date_con) <= '.($year + 1).' AND '.$year.' <= YEAR (kp.date_con) AND sh.type_his_type_id = kp.id_schooltype'))

                        ->where('ph.history_year',$year.'-'.($year + 1))
                        ->where('qlhs_profile.profile_id', $profileId)
                        ->where('s.subject_history_group_id', '119')
                    
                        ->select('his.profile_subject_profile_id', 'kp.*', 'his.start_date', 'his.end_date', 'his.profile_subject_subject_id','ph.P_His_startdate','ph.p_His_enddate');

                    $dataHBHSDTNT22 = DB::table(DB::raw("({$dataHBHSDTNT2->toSql()}) as m"))
                        ->mergeBindings( $dataHBHSDTNT2 )
                        ->select('m.row_id',
                            DB::raw('CASE WHEN m.months in (9,10,11,12) THEN SUM(m.value_m) END as NhuCau'),
                            DB::raw('CASE WHEN m.months in (1,2,3,4,5) THEN SUM(m.value_m) END as DuToan'),
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id')
                        ->where(DB::raw('((CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_date, DATETIME ) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_date, DATETIME )  OR m.end_date IS NULL  )) AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.P_His_startdate, DATETIME ) AND CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) < CONVERT ( m.p_His_enddate, DATETIME )) )  AND ((m.years = '.$year.' AND m.months in (9,10,11,12)) or (m.years'), '=', DB::raw(($year + 1).' AND m.months in (1,2,3,4,5)))'))
                        ->groupBy('m.row_id',
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id');

                    $dataHBHSDTNT222 = DB::table(DB::raw("({$dataHBHSDTNT22->toSql()}) as k"))
                        ->mergeBindings( $dataHBHSDTNT22 )
                        ->leftJoin('qlhs_group as g', 'g.group_id', 'k.id_doituong')
                        ->select('k.id_doituong',
                            'g.group_name',
                            DB::raw('SUM(k.NhuCau) as NhuCau'),
                            DB::raw('SUM(k.DuToan) as DuToan'))
                        ->groupBy('k.id_doituong',
                            'g.group_name')->get();

                    if (!is_null($dataHBHSDTNT222) && !empty($dataHBHSDTNT222) && count($dataHBHSDTNT222) > 0) {
                        foreach ($dataHBHSDTNT222 as $value) {

                            $MONEYHBHSDTNT += $value->NhuCau;// + (3*$value->NhuCau/4);
                            $MONEYHBHSDTNTHK2 += $value->DuToan;
                        }
                    }
                }catch (Exception $e) {
                    echo "HBHSDTNT: ".$e;
                }
            }
            
//--------------------------------------------------------End Học bổng hs dân tộc nội trú----------------------------------------------------

// ---------------------- Hỗ trợ học tập - NQ57 - CP
            try{

                    $dataHTHT2 = DB::table('qlhs_profile_history AS ph')
                        ->leftJoin('qlhs_profile_subject AS his', 'his.profile_subject_profile_id','ph.history_profile_id' )

                        ->leftJoin('qlhs_profile', 'ph.history_profile_id','qlhs_profile.profile_id')

                        ->leftJoin('qlhs_schools_history as sh', 'sh.type_his_school_id', 'ph.history_school_id')
                            
                        ->leftJoin('qlhs_subject_history as s', 's.subject_history_subject_id', '=','his.profile_subject_subject_id')

                        ->leftJoin('years_months_kp as kp','kp.id_doituong', '=', DB::raw('s.subject_history_group_id AND YEAR (kp.date_con) <= '.($year + 1).' AND '.$year.' <= YEAR (kp.date_con) AND sh.type_his_type_id = kp.id_schooltype'))

                        ->where('ph.history_year',$year.'-'.($year + 1))
                        ->where('qlhs_profile.profile_id', $profileId)
                        ->where('history_status_57CP',1)
                        ->where('subject_history_group_id','120')
                        ->select('his.profile_subject_profile_id', 'kp.*', 'his.start_date', 'his.end_date', 'his.profile_subject_subject_id','ph.P_His_startdate','ph.p_His_enddate','ph.unit_class');

                    $dataHTHT22 = DB::table(DB::raw("({$dataHTHT2->toSql()}) as m"))
                        ->mergeBindings( $dataHTHT2 )
                        ->select('m.row_id',
                            DB::raw('CASE WHEN m.months in (9,10,11,12) THEN SUM(m.value_m) END as NhuCau'),
                            DB::raw('CASE WHEN m.months in (1,2,3,4,5) THEN SUM(m.value_m) END as DuToan'),
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id','m.unit_class')
                        ->where(DB::raw('((CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.start_date, DATETIME )  AND (CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) <= CONVERT ( m.end_date, DATETIME ) OR m.end_date IS NULL  ))  AND (CONVERT ( CONCAT( m.years, "-", m.months, "-30" ), DATETIME ) >= CONVERT ( m.P_His_startdate, DATETIME )  AND CONVERT ( CONCAT( m.years, "-", m.months, "-01" ), DATETIME ) < CONVERT ( m.p_His_enddate, DATETIME ) ) )  AND ((m.years = '.$year.' AND m.months in (9,10,11,12)) or (m.years'), '=', DB::raw(($year + 1).' AND m.months in (1,2,3,4,5)))'))
                        ->groupBy('m.row_id',
                            'm.months',
                            'm.years',
                            'm.id_schooltype',
                            'm.id_doituong',
                            'm.start_date',
                            'm.end_date',
                            'm.profile_subject_subject_id','m.unit_class');

                    $dataHTHT222 = DB::table(DB::raw("({$dataHTHT22->toSql()}) as k"))
                        ->mergeBindings( $dataHTHT22 )
                        ->leftJoin('qlhs_group as g', 'g.group_id', 'k.id_doituong')
                        ->select('k.id_doituong',
                            'g.group_name','k.profile_subject_subject_id',
                            DB::raw('CASE WHEN k.unit_class = 1 THEN (SUM( k.NhuCau ) * 3/10)
             WHEN k.unit_class = 2 OR k.unit_class = 3 OR (k.unit_class = 33 AND `k`.`id_schooltype` <> 2) THEN (SUM( k.NhuCau ) * 4/10)
             WHEN k.unit_class = 33 AND `k`.`id_schooltype` = 2 THEN (SUM( k.NhuCau ) * 6/10) END NhuCau'),
                            DB::raw('CASE WHEN k.unit_class = 1 THEN (SUM( k.DuToan ) * 3/10)
             WHEN k.unit_class = 2 OR k.unit_class = 3 OR (k.unit_class = 33 AND `k`.`id_schooltype` <> 2) THEN (SUM( k.DuToan ) * 4/10)
             WHEN k.unit_class = 33 AND `k`.`id_schooltype` = 2 THEN (SUM( k.DuToan ) * 6/10) END DuToan'))
                        ->groupBy('k.id_doituong','k.profile_subject_subject_id',
                            'g.group_name','k.unit_class','k.id_schooltype')->get();

                    if (!is_null($dataHTHT222) && !empty($dataHTHT222) && count($dataHTHT222) > 0) {
                        $checkHTHT = 0;
                        $checkKH1 = DB::table('qlhs_profile_history AS ph')
                        ->where('history_profile_id',$profileId)
                        ->where('ph.history_year',$year.'-'.($year + 1))
                        ->select(\DB::raw('TIMESTAMPDIFF(MONTH, P_His_startdate, p_His_enddate) as months'))->first();
                        $months = 4;
                        if($checkKH1 && $checkKH1->months < 4){
                            $months = $checkKH1->months == 0 ? 1 : $checkKH1->months;
                        }
                        foreach ($dataHTHT222 as $value) {
                            if($checkHTHT == 0 || $checkHTHT == $value->profile_subject_subject_id){
                                if($value->NhuCau == 0 && $value->DuToan == 0){
                                    $checkHTHT = 0;
                                }else{
                                    $MONEYHTHT += $value->NhuCau + (3*$value->NhuCau/$months);
                                    $MONEYHTHTHK2 += $value->DuToan;
                                    $checkHTHT = $value->profile_subject_subject_id ;
                                }
                            }
                        }
                    }
                }catch (Exception $e) {
                    echo "HTHT57: ".$e;
                }
//----------------------- End Hỗ trợ học tập - NQ57 - CP
            

            $TOTALMONEY = $MONEYMGHP + $MONEYCPHT + $MONEYHTAT + $MONEYHTBTTIENAN + $MONEYHTBTTIENO + $MONEYHTBTVHTT + $MONEYHSKTHOCBONG + $MONEYHSKTDDHT + $MONEYHSDTTS + $MONEYHTATHS + $MONEYHBHSDTNT;// + $MONEYHTHT;

            $TOTALMONEYHK2 = $MONEYMGHPHK2 + $MONEYCPHTHK2 + $MONEYHTATHK2 + $MONEYHTBTTIENANHK2 + $MONEYHTBTTIENOHK2 + $MONEYHTBTVHTTHK2 + $MONEYHSKTHOCBONGHK2 + $MONEYHSKTDDHTHK2 + $MONEYHSDTTSHK2 + $MONEYHTATHSHK2 + $MONEYHBHSDTNTHK2;// + $MONEYHTHTHK2;

            // $hocky1 = 'HK1'.$year;
            // $hocky2 = 'HK2'.$year;

            

            if($thcd_ID != null){
                echo "year: " . $year." ==> ";
                $status = TongHopCheDo_Truong::where('thcd_nhucau_profile_id',$profileId)
                    ->where('thcd_nhucau_nam',$year)
                    ->where('thcd_nhucau_id', $thcd_ID)->first();
                if($status != null){
                    $status->thcd_nhucau_MGHP_HK1 = $MONEYMGHP;
                    $status->thcd_nhucau_CPHT_HK1 = $MONEYCPHT;
                    $status->thcd_nhucau_HTAT_HK1 = $MONEYHTAT;
                    $status->thcd_nhucau_HTBT_TA_HK1 = $MONEYHTBTTIENAN;
                    $status->thcd_nhucau_HTBT_TO_HK1 = $MONEYHTBTTIENO;
                    $status->thcd_nhucau_HTBT_VHTT_HK1 = $MONEYHTBTVHTT;
                    $status->thcd_nhucau_HTATHS_HK1 = $MONEYHTATHS;
                    $status->thcd_nhucau_HSDTTS_HK1 = $MONEYHSDTTS;
                    $status->thcd_nhucau_HBHSDTNT_HK1 = $MONEYHBHSDTNT;
                    $status->thcd_nhucau_HSKT_HB_HK1 = $MONEYHSKTHOCBONG;
                    $status->thcd_nhucau_HSKT_DDHT_HK1 = $MONEYHSKTDDHT;
                    $status->thcd_nhucau_HTHT57_HK1 = $MONEYHTHT;

                    $status->thcd_nhucau_MGHP_HK2 = $MONEYMGHPHK2;
                    $status->thcd_nhucau_CPHT_HK2 = $MONEYCPHTHK2;
                    $status->thcd_nhucau_HTAT_HK2 = $MONEYHTATHK2;
                    $status->thcd_nhucau_HTBT_TA_HK2 = $MONEYHTBTTIENANHK2;
                    $status->thcd_nhucau_HTBT_TO_HK2 = $MONEYHTBTTIENOHK2;
                    $status->thcd_nhucau_HTBT_VHTT_HK2 = $MONEYHTBTVHTTHK2;
                    $status->thcd_nhucau_HTATHS_HK2 = $MONEYHTATHSHK2;
                    $status->thcd_nhucau_HSDTTS_HK2 = $MONEYHSDTTSHK2;
                    $status->thcd_nhucau_HBHSDTNT_HK2 = $MONEYHBHSDTNTHK2;
                    $status->thcd_nhucau_HSKT_HB_HK2 = $MONEYHSKTHOCBONGHK2;
                    $status->thcd_nhucau_HSKT_DDHT_HK2 = $MONEYHSKTDDHTHK2;
                    $status->thcd_nhucau_HTHT57_HK2 = $MONEYHTHTHK2;

                    $status->thcd_nhucau_tongtien_HK1 = $TOTALMONEY;
                    $status->thcd_nhucau_tongtien_HK2 = $TOTALMONEYHK2;
                    $status->save();
                }else{
                    echo "profile ID: " . $profileId." deleted. ==> ";
                }
            }
        } catch (Exception $e) {
            echo "compilationProfile : ".$e;
        }
    }
}
