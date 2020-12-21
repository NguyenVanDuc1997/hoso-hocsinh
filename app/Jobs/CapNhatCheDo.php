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

class CapNhatCheDo implements ShouldQueue
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
    protected $year;
    protected $profile_id;
    protected $type_school;
    protected $class_unit_id;
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($school_ids,$years,$profile_ids,$type_schools,$class_unit_ids,$types)
    {
        $this->school_id = $school_ids;
        $this->year = $years;
        $this->profile_id = $profile_ids;
        $this->type_school = $type_schools;
        $this->class_unit_id = $class_unit_ids;
        $this->type = $types;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(2);
         echo 'Start update profile id(money): '.$this->profile_id.' ==> ';
      // Log::info('Queue worker timed out.');
        $school_id = $this->school_id;
        $year = $this->year;
        $profile_id = $this->profile_id;
        $type_school = $this->type_school;
        $class_unit_id = $this->class_unit_id;
        $type = $this->type;
        echo $school_id.'-'.$year.'-'.$profile_id.'-'.$type_school.'-'.$class_unit_id.'-'.$type;

            $chedoMGHP = 1;
            $chedoCPHT = 1;
            $chedoHTAT = 1;
            $chedoHTBT = 1;
            $chedoHSKT = 1;
            $chedoHSDTTS = 1;
            $chedoHTATHS = 1;
            $chedoHBHSDTNT = 1;
            $chedoNGNA = 1;
        try{
            $DTMien100_1 = 0;
                    $DTMien100_2 = 0; //DT100
                    $DTMien100_3 = 0;
                    $DTMien100_4 = 0;
                    $DTMien100_5 = 0;
                    $DTMien100_6 = 0;
                    $DTMien70 = 0; //DT101
                    $DTMien50_1 = 0;
                    $DTMien50_2 = 0;
                    $money_nhucau_MGHP = 0;
                    $money_dutoan_MGHP = 0;

                    $DTCPHT_1 = 0;
                    $DTCPHT_2 = 0;
                    $money_nhucau_CPHT = 0;
                    $money_dutoan_CPHT = 0;

                    $DTHTAT_1 = 0;
                    $DTHTAT_2 = 0; //DT100
                    $DTHTAT_3 = 0;
                    $DTHTAT_4 = 0;
                    $money_nhucau_HTAT = 0;
                    $money_dutoan_HTAT = 0;

                    $DTHTBT_TA = 0;
                    $DTHTBT_TO = 0;
                    $DTHTBT_VHTT = 0;
                    $HTBT_TA_HK2_OLD = 0;
                    $HTBT_TA_HK1_CUR = 0;
                    $HTBT_TA_HK2_CUR = 0;
                    $HTBT_TA_HK1_NEW = 0;
                    $HTBT_TO_HK2_OLD = 0;
                    $HTBT_TO_HK1_CUR = 0;
                    $HTBT_TO_HK2_CUR = 0;
                    $HTBT_TO_HK1_NEW = 0;
                    $money_nhucau_HTBT_TA = 0;
                    $money_nhucau_HTBT_TO = 0;
                    $money_nhucau_HTBT_VHTT = 0;
                    $money_dutoan_HTBT_TA = 0;
                    $money_dutoan_HTBT_TO = 0;
                    $money_dutoan_HTBT_VHTT = 0;

                    $DTHSKT_HB = 0;
                    $DTHSKT_DDHT = 0;
                    $money_nhucau_HSKT_HB = 0;
                    $money_nhucau_HSKT_DDHT = 0;
                    $money_dutoan_HSKT_HB = 0;
                    $money_dutoan_HSKT_DDHT = 0;

                    $DTHSDTTS = 0;
                    $money_nhucau_HSDTS = 0;
                    $money_dutoan_HSDTS = 0;

                    $DTHTATHS = 0;
                    $money_nhucau_HTATHS = 0;
                    $money_dutoan_HTATHS = 0;

                    $DTHBHSDTNT = 0;
                    $money_nhucau_HBHSDTNT = 0;
                    $money_dutoan_HBHSDTNT = 0;

                    $HK2_OLD = 0;
                    $HK1_CUR = 0;
                    $HK2_CUR = 0;
                    $HK1_NEW = 0;
                    $TYPE = 0;
                    $TONG_NHUCAU = 0;
                    $TONG_DUTOAN = 0;

                //-------------------------------------------------------------------MGHP------------------------------------------------------------
                    if ($chedoMGHP > 0 && $class_unit_id != 2) {
                        // Lấy danh sách loại a - học sinh cũ
                        $dataMGHP1 = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'profile_id', DB::raw('profile_subject_profile_id AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))

                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            ->whereIn('profile_subject_subject_id', [28, 35, 36, 73, 38, 39, 40, 41, 100, 101])
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_MGHP_HK2 > 0 OR cur.rppst_Status_MGHP > 0 OR cur.rppst_Status_MGHP_HK2 > 0)'))
                            ->where('profile_year', '<', $year.'-06-01')

                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_MGHP_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_MGHP_HK2 else 0 END) as money_old_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_MGHP > 0 then yearcur.qlhs_thcd_tien_nhucau_MGHP else 0 END) as money_cur'),
                                DB::raw('MAX(CASE when cur.rppst_Status_MGHP_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_MGHP_HK2 else 0 END) as money_cur_HK2'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_MGHP > 0 then yearnew.qlhs_thcd_tien_nhucau_MGHP else 0 END) as money_new'), 
                                DB::raw('MAX(qlhs_profile_subject.profile_subject_subject_id) as profile_subject_subject_id'), 
                                DB::raw('MAX(CASE when profile_subject_subject_id = 100 then 1 else 0 END) Mien'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 35 then 1 else 0 END) Mien1'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 36 then 1 else 0 END) Mien2'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 38 then 1 else 0 END) Mien3'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 73 then 1 else 0 END) Mien4'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 39 then 1 else 0 END) Mien5'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 28 then 1 else 0 END) Mien6'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 101 then 1 else 0 END) Giam70'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 40 then 1 else 0 END) Giam501'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 41 then 1 else 0 END) Giam502'),

                                DB::raw("MAX(CASE when level_old <> '' and qlhs_profile.profile_year < '".$year."-06-01' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-01-01')) then 1 else 0 END) 'HK2_old'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".$year."-12-31' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-05-31')) then 1 else 0 END) 'HK1_cur'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".($year + 1)."-06-01' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year+1)."-01-01')) then 1 else 0 END) 'HK2_cur'"),
                                DB::raw("MAX(CASE when level_new <> '' and qlhs_profile.profile_year < '".($year +1)."-12-31' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year+1)."-05-31')) then 1 else 0 END) 'HK1_new'"));

                        $dataMGHP11 = DB::table(DB::raw("({$dataMGHP1->toSql()}) as m"))->mergeBindings( $dataMGHP1 )
                            ->select('m.Mien', 'm.Mien1', 'm.Mien2', 'm.Mien3', 'm.Mien4', 'm.Mien5', 'm.Mien6', 'm.Giam70', 'm.Giam501', 'm.Giam502', 'm.HK2_old','m.HK1_cur', 'm.HK2_cur', 'm.HK1_new', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();
                            // Lấy học sinh nhập học năm học hiện tại
                        $dataMGHP2 = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'profile_id', DB::raw('profile_subject_profile_id AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))

                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            // ->join('qlhs_hosobaocao_trangthai_Truong as new', 'new.rppst_profile_id', DB::raw('profile_id AND new.rppst_year = '.($year - 1)))

                            ->where('profile_year','>',$year.'-05-31')
                            ->where('profile_year','<',((int)$year+1).'-06-01')
                            ->whereIn('profile_subject_subject_id', [28, 35, 36, 73, 38, 39, 40, 41, 100, 101])
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_MGHP_HK2 > 0 OR cur.rppst_Status_MGHP > 0 OR cur.rppst_Status_MGHP_HK2 > 0)'))

                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_MGHP_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_MGHP_HK2 else 0 END) as money_old_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_MGHP > 0 then yearcur.qlhs_thcd_tien_nhucau_MGHP else 0 END) as money_cur'),
                                DB::raw('MAX(CASE when cur.rppst_Status_MGHP_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_MGHP_HK2 else 0 END) as money_cur_HK2'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_MGHP > 0 then yearnew.qlhs_thcd_tien_nhucau_MGHP else 0 END) as money_new'), 
                                DB::raw('MAX(qlhs_profile_subject.profile_subject_subject_id) as profile_subject_subject_id'), 
                                DB::raw('MAX(CASE when profile_subject_subject_id = 100 then 1 else 0 END) Mien'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 35 then 1 else 0 END) Mien1'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 36 then 1 else 0 END) Mien2'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 38 then 1 else 0 END) Mien3'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 73 then 1 else 0 END) Mien4'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 39 then 1 else 0 END) Mien5'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 28 then 1 else 0 END) Mien6'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 101 then 1 else 0 END) Giam70'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 40 then 1 else 0 END) Giam501'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 41 then 1 else 0 END) Giam502'),

                                DB::raw("MAX(CASE when level_old <> '' and qlhs_profile.profile_year < '".$year."-06-01' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-01-01')) then 1 else 0 END) 'HK2_old'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".$year."-12-31' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-05-31')) then 1 else 0 END) 'HK1_cur'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".($year + 1)."-06-01' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year+1)."-01-01')) then 1 else 0 END) 'HK2_cur'"),
                                DB::raw("MAX(CASE when level_new <> '' and qlhs_profile.profile_year < '".($year +1)."-12-31' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year+1)."-05-31')) then 1 else 0 END) 'HK1_new'"));

                        $dataMGHP22 = DB::table(DB::raw("({$dataMGHP2->toSql()}) as m"))->mergeBindings( $dataMGHP2 )
                            ->select('m.Mien', 'm.Mien1', 'm.Mien2', 'm.Mien3', 'm.Mien4', 'm.Mien5', 'm.Mien6', 'm.Giam70', 'm.Giam501', 'm.Giam502', 'm.HK2_old','m.HK1_cur', 'm.HK2_cur', 'm.HK1_new', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();
                            // Lấy học sinh dự kiến tuyển
                        $dataMGHP3 = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'profile_id', DB::raw('profile_subject_profile_id AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.($year + 1).' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.($year + 1).')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.($year + 1).'-'.($year + 2).'"'))

                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.($year + 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 2)))

                            ->where('profile_year','>',((int)$year+1).'-05-31')
                            ->where('profile_year','<',((int)$year+2).'-01-01')
                            ->whereIn('profile_subject_subject_id', [28, 35, 36, 73, 38, 39, 40, 41, 100, 101])
                            ->where('profile_id', $profile_id)

                            ->select(
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_MGHP > 0 then yearcur.qlhs_thcd_tien_nhucau_MGHP else 0 END) as money_cur'), 
                                DB::raw('MAX(qlhs_profile_subject.profile_subject_subject_id) as profile_subject_subject_id'), 
                                DB::raw('MAX(CASE when profile_subject_subject_id = 100 then 1 else 0 END) Mien'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 35 then 1 else 0 END) Mien1'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 36 then 1 else 0 END) Mien2'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 38 then 1 else 0 END) Mien3'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 73 then 1 else 0 END) Mien4'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 39 then 1 else 0 END) Mien5'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 28 then 1 else 0 END) Mien6'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 101 then 1 else 0 END) Giam70'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 40 then 1 else 0 END) Giam501'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 41 then 1 else 0 END) Giam502'),

                                DB::raw("MAX(CASE when level_old <> '' and qlhs_profile.profile_year < '".$year."-06-01' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-01-01')) then 1 else 0 END) 'HK2_old'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".$year."-12-31' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-05-31')) then 1 else 0 END) 'HK1_cur'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".($year + 1)."-06-01' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year+1)."-01-01')) then 1 else 0 END) 'HK2_cur'"),
                                DB::raw("MAX(CASE when level_new <> '' and qlhs_profile.profile_year < '".($year +1)."-12-31' and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year+1)."-05-31')) then 1 else 0 END) 'HK1_new'"));

                        $dataMGHP33 = DB::table(DB::raw("({$dataMGHP3->toSql()}) as m"))->mergeBindings( $dataMGHP3 )
                            ->select('m.Mien', 'm.Mien1', 'm.Mien2', 'm.Mien3', 'm.Mien4', 'm.Mien5', 'm.Mien6', 'm.Giam70', 'm.Giam501', 'm.Giam502', 'm.HK2_old','m.HK1_cur', 'm.HK2_cur', 'm.HK1_new', 
                                'm.money_cur')
                            ->get();
                        if (!is_null($dataMGHP11) && !empty($dataMGHP11) && count($dataMGHP11) > 0) {
                            foreach ($dataMGHP11 as $value) {
                                if ($value->Mien1 > 0 || $value->Mien > 0 || $value->Mien3 > 0 || $value->Mien4 > 0 || $value->Mien5 > 0 || $value->Mien6 > 0 || $value->Giam70 > 0 || $value->Giam501 > 0 || $value->Giam502 > 0) {
                                    $DTMien100_1 = $value->Mien1;
                                    $DTMien100_2 = $value->Mien; //DT100
                                    $DTMien100_3 = $value->Mien3;
                                    $DTMien100_4 = $value->Mien4;
                                    $DTMien100_5 = $value->Mien5;
                                    $DTMien100_6 = $value->Mien6;
                                    $DTMien70 = $value->Giam70; //DT101
                                    $DTMien50_1 = $value->Giam501;
                                    $DTMien50_2 = $value->Giam502;

                                    $HK2_OLD = $value->HK2_old;
                                    $HK1_CUR = $value->HK1_cur;
                                    $HK2_CUR = $value->HK2_cur;
                                    $HK1_NEW = $value->HK1_new;
                                    $TYPE = 1;

                                    if ($value->Mien > 0 || $value->Mien1 > 0 || $value->Mien3 > 0 || $value->Mien4 > 0 || $value->Mien5 > 0 || $value->Mien6 > 0) {
                                        $money_nhucau_MGHP = ($value->money_old_HK2 * $value->HK2_old) + ($value->money_cur * $value->HK1_cur);
                                        $money_dutoan_MGHP = ($value->money_cur_HK2 * $value->HK2_cur) + $value->money_new;
                                    }
                                    else if ($value->Giam70 > 0) {
                                        $money_nhucau_MGHP = (($value->money_old_HK2 * $value->HK2_old) + ($value->money_cur * $value->HK1_cur));
                                        $money_dutoan_MGHP = (($value->money_cur_HK2 * $value->HK2_cur) + $value->money_new);
                                    }
                                    else if ($value->Giam501 > 0 || $value->Giam502 > 0) {
                                        $money_nhucau_MGHP = (($value->money_old_HK2 * $value->HK2_old) + ($value->money_cur * $value->HK1_cur));
                                        $money_dutoan_MGHP = (($value->money_cur_HK2 * $value->HK2_cur) + $value->money_new);
                                    }
                                }
                            }
                        }

                        if (!is_null($dataMGHP22) && !empty($dataMGHP22) && count($dataMGHP22) > 0) {
                            foreach ($dataMGHP22 as $value) {
                                if ($value->Mien1 > 0 || $value->Mien > 0 || $value->Mien3 > 0 || $value->Mien4 > 0 || $value->Mien5 > 0 || $value->Mien6 > 0 || $value->Giam70 > 0 || $value->Giam501 > 0 || $value->Giam502 > 0) {
                                    $DTMien100_1 = $value->Mien1;
                                    $DTMien100_2 = $value->Mien; //DT100
                                    $DTMien100_3 = $value->Mien3;
                                    $DTMien100_4 = $value->Mien4;
                                    $DTMien100_5 = $value->Mien5;
                                    $DTMien100_6 = $value->Mien6;
                                    $DTMien70 = $value->Giam70; //DT101
                                    $DTMien50_1 = $value->Giam501;
                                    $DTMien50_2 = $value->Giam502;

                                    $HK2_OLD = $value->HK2_old;
                                    $HK1_CUR = $value->HK1_cur;
                                    $HK2_CUR = $value->HK2_cur;
                                    $HK1_NEW = $value->HK1_new;
                                    $TYPE = 1;

                                    if ($value->Mien > 0 || $value->Mien1 > 0 || $value->Mien3 > 0 || $value->Mien4 > 0 || $value->Mien5 > 0 || $value->Mien6 > 0) {
                                        $money_nhucau_MGHP = ($value->money_old_HK2 * $value->HK2_old) + ($value->money_cur * $value->HK1_cur);
                                        $money_dutoan_MGHP = ($value->money_cur_HK2 * $value->HK2_cur) + $value->money_new;
                                    }
                                    else if ($value->Giam70 > 0) {
                                        $money_nhucau_MGHP = (($value->money_old_HK2 * $value->HK2_old) + ($value->money_cur * $value->HK1_cur));
                                        $money_dutoan_MGHP = (($value->money_cur_HK2 * $value->HK2_cur) + $value->money_new);
                                    }
                                    else if ($value->Giam501 > 0 || $value->Giam502 > 0) {
                                        $money_nhucau_MGHP = (($value->money_old_HK2 * $value->HK2_old) + ($value->money_cur * $value->HK1_cur));
                                        $money_dutoan_MGHP = (($value->money_cur_HK2 * $value->HK2_cur) + $value->money_new);
                                    }
                                }
                            }
                        }

                        if (!is_null($dataMGHP33) && !empty($dataMGHP33) && count($dataMGHP33) > 0) {
                            foreach ($dataMGHP33 as $value) {
                                if ($value->Mien1 > 0 || $value->Mien > 0 || $value->Mien3 > 0 || $value->Mien4 > 0 || $value->Mien5 > 0 || $value->Mien6 > 0 || $value->Giam70 > 0 || $value->Giam501 > 0 || $value->Giam502 > 0) {
                                    $DTMien100_1 = $value->Mien1;
                                    $DTMien100_2 = $value->Mien; //DT100
                                    $DTMien100_3 = $value->Mien3;
                                    $DTMien100_4 = $value->Mien4;
                                    $DTMien100_5 = $value->Mien5;
                                    $DTMien100_6 = $value->Mien6;
                                    $DTMien70 = $value->Giam70; //DT101
                                    $DTMien50_1 = $value->Giam501;
                                    $DTMien50_2 = $value->Giam502;

                                    $HK2_OLD = $value->HK2_old;
                                    $HK1_CUR = $value->HK1_cur;
                                    $HK2_CUR = $value->HK2_cur;
                                    $HK1_NEW = $value->HK1_new;
                                    $TYPE = 3;

                                    if ($value->Mien > 0 || $value->Mien1 > 0 || $value->Mien3 > 0 || $value->Mien4 > 0 || $value->Mien5 > 0 || $value->Mien6 > 0) {
                                        // $money_nhucau_MGHP = ($value->money_old_HK2 * $value->HK2_old) + ($value->money_cur * $value->HK1_cur);
                                        $money_dutoan_MGHP = $value->money_cur;
                                    }
                                    else if ($value->Giam70 > 0) {
                                        // $money_nhucau_MGHP = (($value->money_old_HK2 * $value->HK2_old) + ($value->money_cur * $value->HK1_cur)) * 7 / 10;
                                        $money_dutoan_MGHP = $value->money_cur;
                                    }
                                    else if ($value->Giam501 > 0 || $value->Giam502 > 0) {
                                        // $money_nhucau_MGHP = (($value->money_old_HK2 * $value->HK2_old) + ($value->money_cur * $value->HK1_cur)) * 5 / 10;
                                        $money_dutoan_MGHP = $value->money_cur;
                                    }
                                }
                            }
                        }
                    }
                  
                //-------------------------------------------------------------------HTAT------------------------------------------------------------
                    if ($chedoHTAT > 0 && $class_unit_id == 1) {
                        $dataHTAT1 = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'profile_id', DB::raw('profile_subject_profile_id AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', '=', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))

                            ->where('profile_year', '<', $year.'-06-01')
                            ->whereIn('profile_subject_subject_id', [73, 26, 34, 28, 41, 74, 100,101])
                            ->where('profile_id', '=', DB::raw($profile_id.' AND (old.rppst_Status_HTAT_HK2 > 0 OR cur.rppst_Status_HTAT > 0 OR cur.rppst_Status_HTAT_HK2 > 0)'))

                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HTAT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTAT_HK2 else 0 END) as money_old_HK2'),
                                DB::raw('MAX(CASE when cur.rppst_Status_HTAT > 0 then yearcur.qlhs_thcd_tien_nhucau_HTAT else 0 END) as money_cur'),
                                DB::raw('MAX(CASE when cur.rppst_Status_HTAT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTAT_HK2 else 0 END) as money_cur_HK2'),
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTAT > 0 then yearnew.qlhs_thcd_tien_nhucau_HTAT else 0 END) as money_new'),
                                DB::raw('MAX(profile_subject_subject_id) as profile_subject_subject_id'),
                                DB::raw('MAX(CASE when profile_subject_subject_id in (26, 34,101) then 1 else 0 END) DT1'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 100 then 1 else 0 END) DT2'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 28 then 1 else 0 END) DT3'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 73 then 1 else 0 END) DT4'),

                                DB::raw("MAX(CASE when level_old <> '' and qlhs_profile.profile_year < '".$year."-06-01' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".$year."-01-01')) then 1 else 0 END) 'HK2_old'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".$year."-12-31' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".$year."-05-31')) then 1 else 0 END) 'HK1_cur'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".($year +1)."-06-01' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".((int)$year+1)."-01-01')) then 1 else 0 END) 'HK2_cur'"),
                                DB::raw("MAX(CASE when level_new <> '' and qlhs_profile.profile_year < '".($year +1)."-12-31' and profile_subject_subject_id in (73,26,34,28,41,74,100,101) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".((int)$year+1)."-05-31')) then 1 else 0 END) 'HK1_new'"));

                        $dataHTAT11 = DB::table(DB::raw("({$dataHTAT1->toSql()}) as m"))->mergeBindings( $dataHTAT1 )
                            ->select('m.HK2_old', 'm.HK1_cur', 'm.HK2_cur', 'm.HK1_new', 'm.DT1', 'm.DT2', 'm.DT3', 'm.DT4', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->groupBy('m.HK2_old', 'm.HK1_cur', 'm.HK2_cur', 'm.HK1_new', 'm.DT1', 'm.DT2', 'm.DT3', 'm.DT4', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();

                        $dataHTAT2 = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'profile_id', DB::raw('profile_subject_profile_id AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))
                            
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))

                            ->where('profile_year','>',$year.'-05-31')
                            ->where('profile_year','<',($year + 1).'-06-01')
                            ->whereIn('profile_subject_subject_id', [73, 26, 34, 28, 41, 74, 100,101])
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HTAT_HK2 > 0 OR cur.rppst_Status_HTAT > 0 OR cur.rppst_Status_HTAT_HK2 > 0)'))

                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HTAT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTAT_HK2 else 0 END) as money_old_HK2'),
                                DB::raw('MAX(CASE when cur.rppst_Status_HTAT > 0 then yearcur.qlhs_thcd_tien_nhucau_HTAT else 0 END) as money_cur'),
                                DB::raw('MAX(CASE when cur.rppst_Status_HTAT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTAT_HK2 else 0 END) as money_cur_HK2'),
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTAT > 0 then yearnew.qlhs_thcd_tien_nhucau_HTAT else 0 END) as money_new'),
                                DB::raw('MAX(profile_subject_subject_id) as profile_subject_subject_id'),
                                DB::raw('MAX(CASE when profile_subject_subject_id in (26, 34,101) then 1 else 0 END) DT1'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 100 then 1 else 0 END) DT2'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 28 then 1 else 0 END) DT3'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 73 then 1 else 0 END) DT4'),

                                DB::raw("MAX(CASE when level_old <> '' and qlhs_profile.profile_year < '".$year."-06-01' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".$year."-01-01')) then 1 else 0 END) 'HK2_old'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".$year."-12-31' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".$year."-05-31')) then 1 else 0 END) 'HK1_cur'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".($year +1)."-06-01' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".((int)$year+1)."-01-01')) then 1 else 0 END) 'HK2_cur'"),
                                DB::raw("MAX(CASE when level_new <> '' and qlhs_profile.profile_year < '".($year +1)."-12-31' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".((int)$year+1)."-05-31')) then 1 else 0 END) 'HK1_new'"));

                        $dataHTAT22 = DB::table(DB::raw("({$dataHTAT2->toSql()}) as m"))->mergeBindings( $dataHTAT2 )
                            ->select('m.HK2_old', 'm.HK1_cur', 'm.HK2_cur', 'm.HK1_new', 'm.DT1', 'm.DT2', 'm.DT3', 'm.DT4', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->groupBy('m.HK2_old', 'm.HK1_cur', 'm.HK2_cur', 'm.HK1_new', 'm.DT1', 'm.DT2', 'm.DT3', 'm.DT4', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();

                        $dataHTAT3 = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'profile_id', DB::raw('profile_subject_profile_id AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.($year + 1).' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.($year + 1).')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.($year + 1).'-'.($year + 2).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.($year + 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 2)))

                            ->where('profile_year','>',($year + 1).'-05-31')
                            ->where('profile_year','<',($year + 2).'-06-01')
                            ->whereIn('profile_subject_subject_id', [73, 26, 34, 28, 41, 74, 100,101])
                            ->where('profile_id', $profile_id)

                            ->select(
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_HTAT > 0 then yearcur.qlhs_thcd_tien_nhucau_HTAT else 0 END) as money_cur'),
                                DB::raw('MAX(profile_subject_subject_id) as profile_subject_subject_id'),
                                DB::raw('MAX(CASE when profile_subject_subject_id in (26, 34,101) then 1 else 0 END) DT1'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 100 then 1 else 0 END) DT2'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 28 then 1 else 0 END) DT3'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 73 then 1 else 0 END) DT4'),

                                DB::raw("MAX(CASE when level_old <> '' and qlhs_profile.profile_year < '".$year."-06-01' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".$year."-01-01')) then 1 else 0 END) 'HK2_old'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".$year."-12-31' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".$year."-05-31')) then 1 else 0 END) 'HK1_cur'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".($year +1)."-06-01' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".((int)$year+1)."-01-01')) then 1 else 0 END) 'HK2_cur'"),
                                DB::raw("MAX(CASE when level_new <> '' and qlhs_profile.profile_year < '".($year +1)."-12-31' and profile_subject_subject_id in (73,26,34,28,41,74,101,100) and (profile_leaveschool_date is null or (profile_leaveschool_date >= '".((int)$year+1)."-05-31')) then 1 else 0 END) 'HK1_new'"));

                        $dataHTAT33 = DB::table(DB::raw("({$dataHTAT3->toSql()}) as m"))->mergeBindings( $dataHTAT3 )
                            ->select('m.HK2_old', 'm.HK1_cur', 'm.HK2_cur', 'm.HK1_new', 'm.DT1', 'm.DT2', 'm.DT3', 'm.DT4', 'm.money_cur')
                            ->groupBy('m.HK2_old', 'm.HK1_cur', 'm.HK2_cur', 'm.HK1_new', 'm.DT1', 'm.DT2', 'm.DT3', 'm.DT4', 'm.money_cur')
                            ->get();

                        if (!is_null($dataHTAT11) && !empty($dataHTAT11) && count($dataHTAT11) > 0) {
                            foreach ($dataHTAT11 as $value) {
                                if ($value->DT1 > 0 || $value->DT2 > 0 || $value->DT3 > 0 || $value->DT4 > 0) {
                                    $DTHTAT_1 = $value->DT1;
                                    $DTHTAT_2 = $value->DT2; //DT100
                                    $DTHTAT_3 = $value->DT3;
                                    $DTHTAT_4 = $value->DT4;

                                    $HK2_OLD = $value->HK2_old;
                                    $HK1_CUR = $value->HK1_cur;
                                    $HK2_CUR = $value->HK2_cur;
                                    $HK1_NEW = $value->HK1_new;
                                    $TYPE = 1;

                                    $money_nhucau_HTAT = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HTAT = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($dataHTAT22) && !empty($dataHTAT22) && count($dataHTAT22) > 0) {
                            foreach ($dataHTAT22 as $value) {
                                if ($value->DT1 > 0 || $value->DT2 > 0 || $value->DT3 > 0 || $value->DT4 > 0) {
                                    $DTHTAT_1 = $value->DT1;
                                    $DTHTAT_2 = $value->DT2; //DT100
                                    $DTHTAT_3 = $value->DT3;
                                    $DTHTAT_4 = $value->DT4;

                                    $HK2_OLD = $value->HK2_old;
                                    $HK1_CUR = $value->HK1_cur;
                                    $HK2_CUR = $value->HK2_cur;
                                    $HK1_NEW = $value->HK1_new;
                                    $TYPE = 2;

                                    $money_nhucau_HTAT = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HTAT = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($dataHTAT33) && !empty($dataHTAT33) && count($dataHTAT33) > 0) {
                            foreach ($dataHTAT33 as $value) {
                                if ($value->DT1 > 0 || $value->DT2 > 0 || $value->DT3 > 0 || $value->DT4 > 0) {
                                    $DTHTAT_1 = $value->DT1;
                                    $DTHTAT_2 = $value->DT2; //DT100
                                    $DTHTAT_3 = $value->DT3;
                                    $DTHTAT_4 = $value->DT4;

                                    $HK2_OLD = $value->HK2_old;
                                    $HK1_CUR = $value->HK1_cur;
                                    $HK2_CUR = $value->HK2_cur;
                                    $HK1_NEW = $value->HK1_new;
                                    $TYPE = 3;

                                    // $money_nhucau_HTAT = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HTAT = $value->money_cur;
                                }
                            }
                        }
                    }
                //-------------------------------------------------------------------HSDTTS----------------------------------------------------------
                    if ($chedoHSDTTS > 0 && $class_unit_id == 33) {
                        $getDataTypeA = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and (qlhs_profile_subject.profile_subject_subject_id = 49 OR
                                qlhs_profile_subject.profile_subject_subject_id = 101) AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', '=', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))

                            ->join('history_profile_site', 'history_profile_site.p_id', DB::raw('qlhs_profile.profile_id and history_profile_site.start_date <= "'.$year.'-09-05" AND (history_profile_site.end_date >= "'.$year.'-09-05" OR history_profile_site.end_date is null)'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))
                            

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->where('qlhs_profile.profile_year', '<', $year.'-06-01')
                            ->whereIn('qlhs_profile.profile_site_id2', [100, 101])
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HSDTTS_HK2 > 0 OR cur.rppst_Status_HSDTTS > 0 OR cur.rppst_Status_HSDTTS_HK2 > 0)'))

                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HSDTTS_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HSDTTS_HK2 else 0 END) as money_old_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HSDTTS > 0 then yearcur.qlhs_thcd_tien_nhucau_HSDTTS else 0 END) as money_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HSDTTS_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HSDTTS_HK2 else 0 END) as money_cur_HK2'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HSDTTS > 0 then yearnew.qlhs_thcd_tien_nhucau_HSDTTS else 0 END) as money_new'), 

                                DB::raw('MAX(CASE when (qlhs_profile_subject.profile_subject_subject_id = 49 OR
                                    qlhs_profile_subject.profile_subject_subject_id = 101) then 1 else 0 END) as hotrokinhphi'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hocky1_new'));
                        
                        $getDataType1 = DB::table(DB::raw("({$getDataTypeA->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeA )
                            ->select(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur', 'm.money_new', 
                                    'm.money_old_HK2', 'm.money_cur_HK2')

                            ->groupBy(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur', 'm.money_new', 
                                    'm.money_old_HK2', 'm.money_cur_HK2')
                            ->get();

                        $getDataTypeB = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and (qlhs_profile_subject.profile_subject_subject_id = 49 OR
                                qlhs_profile_subject.profile_subject_subject_id = 101) AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', '=', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))

                            ->join('history_profile_site', 'history_profile_site.p_id', DB::raw('qlhs_profile.profile_id and history_profile_site.start_date <= "'.$year.'-09-05" AND (history_profile_site.end_date >= "'.$year.'-09-05" OR history_profile_site.end_date is null)'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->where('qlhs_profile.profile_year', '>', $year.'-05-31')
                            ->where('qlhs_profile.profile_year', '<', ($year + 1).'-06-01')
                            ->whereIn('qlhs_profile.profile_site_id2', [100, 101])
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HSDTTS_HK2 > 0 OR cur.rppst_Status_HSDTTS > 0 OR cur.rppst_Status_HSDTTS_HK2 > 0)'))

                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HSDTTS_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HSDTTS_HK2 else 0 END) as money_old_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HSDTTS > 0 then yearcur.qlhs_thcd_tien_nhucau_HSDTTS else 0 END) as money_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HSDTTS_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HSDTTS_HK2 else 0 END) as money_cur_HK2'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HSDTTS > 0 then yearnew.qlhs_thcd_tien_nhucau_HSDTTS else 0 END) as money_new'), 

                                DB::raw('MAX(CASE when (qlhs_profile_subject.profile_subject_subject_id = 49 OR
                                    qlhs_profile_subject.profile_subject_subject_id = 101) then 1 else 0 END) as hotrokinhphi'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hocky1_new'));

                        $getDataType2 = DB::table(DB::raw("({$getDataTypeB->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeB )
                            ->select(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur', 'm.money_new', 
                                    'm.money_old_HK2', 'm.money_cur_HK2')

                            ->groupBy(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur', 'm.money_new', 
                                    'm.money_old_HK2', 'm.money_cur_HK2')
                            ->get();


                        $getDataTypeC = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and (qlhs_profile_subject.profile_subject_subject_id = 49 OR
                                qlhs_profile_subject.profile_subject_subject_id = 101) AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.($year + 1).' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.($year + 1).')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.($year + 1).'-'.($year + 2).'"'))

                            ->join('history_profile_site', 'history_profile_site.p_id', DB::raw('qlhs_profile.profile_id and history_profile_site.start_date <= "'.($year + 1).'-09-05" AND (history_profile_site.end_date >= "'.($year + 1).'-09-05" OR history_profile_site.end_date is null)'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.($year + 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 2)))
                            
                            ->where('profile_id', $profile_id)
                            ->where('qlhs_profile.profile_year', '>', ($year + 1).'-05-31')
                            ->where('qlhs_profile.profile_year', '<', ($year + 2).'-06-01')
                            ->whereIn('qlhs_profile.profile_site_id2', [100, 101])
                            
                            ->select(
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_HSDTTS > 0 then yearcur.qlhs_thcd_tien_nhucau_HSDTTS else 0 END) as money_cur'), 

                                DB::raw('MAX(CASE when (qlhs_profile_subject.profile_subject_subject_id = 49 OR
                                    qlhs_profile_subject.profile_subject_subject_id = 101) then 1 else 0 END) as hotrokinhphi'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hocky1_new'));
                        
                        $getDataType3 = DB::table(DB::raw("({$getDataTypeC->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeC )
                            ->select(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur')

                            ->groupBy(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur')
                            ->get();

                        if (!is_null($getDataType1) && !empty($getDataType1) && count($getDataType1) > 0) {
                            foreach ($getDataType1 as $value) {
                                if ($value->hotrokinhphi > 0) {
                                    $DTHSDTTS = $value->hotrokinhphi;

                                    $HK2_OLD = $value->hocky2_old;
                                    $HK1_CUR = $value->hocky1_cur;
                                    $HK2_CUR = $value->hocky2_cur;
                                    $HK1_NEW = $value->hocky1_new;
                                    $TYPE = 1;

                                    $money_nhucau_HSDTS = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HSDTS = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($getDataType2) && !empty($getDataType2) && count($getDataType2) > 0) {
                            foreach ($getDataType2 as $value) {
                                if ($value->hotrokinhphi > 0) {
                                    $DTHSDTTS = $value->hotrokinhphi;

                                    $HK2_OLD = $value->hocky2_old;
                                    $HK1_CUR = $value->hocky1_cur;
                                    $HK2_CUR = $value->hocky2_cur;
                                    $HK1_NEW = $value->hocky1_new;
                                    $TYPE = 2;

                                    $money_nhucau_HSDTS = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HSDTS = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($getDataType3) && !empty($getDataType3) && count($getDataType3) > 0) {
                            foreach ($getDataType3 as $value) {
                                if ($value->hotrokinhphi > 0) {
                                    $DTHSDTTS = $value->hotrokinhphi;

                                    $HK2_OLD = $value->hocky2_old;
                                    $HK1_CUR = $value->hocky1_cur;
                                    $HK2_CUR = $value->hocky2_cur;
                                    $HK1_NEW = $value->hocky1_new;
                                    $TYPE = 3;

                                    // $money_nhucau_HSDTS = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HSDTS = $value->money_cur;
                                }
                            }
                        }
                    }
                //-------------------------------------------------------------------CPHT------------------------------------------------------------
                    if ($chedoCPHT > 0) {
                        $dataCPHT1 = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'profile_id', DB::raw('profile_subject_profile_id AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))

                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->whereIn('profile_subject_subject_id', [28, 73])
                            ->where('profile_year', '<', $year.'-06-01')
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_CPHT_HK2 > 0 OR cur.rppst_Status_CPHT > 0 OR cur.rppst_Status_CPHT_HK2 > 0)'))
                            
                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_CPHT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_CPHT_HK2 else 0 END) money_old_HK2'),
                                DB::raw('MAX(CASE when cur.rppst_Status_CPHT > 0 then yearcur.qlhs_thcd_tien_nhucau_CPHT else 0 END) money_cur'),
                                DB::raw('MAX(CASE when cur.rppst_Status_CPHT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_CPHT_HK2 else 0 END) money_cur_HK2'),
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_CPHT > 0 then yearnew.qlhs_thcd_tien_nhucau_CPHT else 0 END) money_new'),
                                DB::raw('MAX(profile_subject_subject_id) as profile_subject_subject_id'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 28 then 1 else 0 END) DT1'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 73 then 1 else 0 END) DT2'),

                                DB::raw("MAX(CASE when level_old <> '' and qlhs_profile.profile_year < '".$year."-06-01' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-01-01')) then 1 else 0 END) 'HK2_old'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".$year."-12-31' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-05-31')) then 1 else 0 END) 'HK1_cur'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".($year + 1)."-06-01' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year + 1)."-01-01')) then 1 else 0 END) 'HK2_cur'"),
                                DB::raw("MAX(CASE when level_new <> '' and qlhs_profile.profile_year < '".($year + 1)."-12-31' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year + 1)."-05-31')) then 1 else 0 END) 'HK1_new'"));

                        $dataCPHT11 = DB::table(DB::raw("({$dataCPHT1->toSql()}) as m"))->mergeBindings( $dataCPHT1 )
                            ->select('HK2_old', 'HK1_cur', 'HK2_cur', 'HK1_new', 'm.DT1', 'm.DT2', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->groupBy('HK2_old', 'HK1_cur', 'HK2_cur', 'HK1_new', 'm.DT1', 'm.DT2', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();

                        $dataCPHT2 = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'profile_id', DB::raw('profile_subject_profile_id AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))

                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->whereIn('profile_subject_subject_id', [28, 73])
                            ->where('profile_year', '>', $year.'-06-01')
                            ->where('profile_year', '<', ($year + 1).'-06-01')
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_CPHT_HK2 > 0 OR cur.rppst_Status_CPHT > 0 OR cur.rppst_Status_CPHT_HK2 > 0)'))
                            
                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_CPHT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_CPHT_HK2 else 0 END) money_old_HK2'),
                                DB::raw('MAX(CASE when cur.rppst_Status_CPHT > 0 then yearcur.qlhs_thcd_tien_nhucau_CPHT else 0 END) money_cur'),
                                DB::raw('MAX(CASE when cur.rppst_Status_CPHT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_CPHT_HK2 else 0 END) money_cur_HK2'),
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_CPHT > 0 then yearnew.qlhs_thcd_tien_nhucau_CPHT else 0 END) money_new'),
                                DB::raw('MAX(profile_subject_subject_id) as profile_subject_subject_id'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 28 then 1 else 0 END) DT1'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 73 then 1 else 0 END) DT2'),

                                DB::raw("MAX(CASE when level_old <> '' and qlhs_profile.profile_year < '".$year."-06-01' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-01-01')) then 1 else 0 END) 'HK2_old'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".$year."-12-31' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-05-31')) then 1 else 0 END) 'HK1_cur'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".($year + 1)."-06-01' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year + 1)."-01-01')) then 1 else 0 END) 'HK2_cur'"),
                                DB::raw("MAX(CASE when level_new <> '' and qlhs_profile.profile_year < '".($year + 1)."-12-31' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year + 1)."-05-31')) then 1 else 0 END) 'HK1_new'"));

                        $dataCPHT22 = DB::table(DB::raw("({$dataCPHT2->toSql()}) as m"))->mergeBindings( $dataCPHT2 )
                            ->select('HK2_old', 'HK1_cur', 'HK2_cur', 'HK1_new', 'm.DT1', 'm.DT2', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->groupBy('HK2_old', 'HK1_cur', 'HK2_cur', 'HK1_new', 'm.DT1', 'm.DT2', 
                                'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();

                        $dataCPHT3 = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'profile_id', DB::raw('profile_subject_profile_id AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))

                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.($year + 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 2)))
                            
                            ->where('profile_year','>', ($year + 1).'-06-01')
                            ->where('profile_year','<', ($year + 2).'-06-01')
                            ->whereIn('profile_subject_subject_id', [28,73])
                            ->where('profile_id', $profile_id)
                            
                            ->select(
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_CPHT > 0 then yearcur.qlhs_thcd_tien_nhucau_CPHT else 0 END) money_cur'),
                                DB::raw('MAX(profile_subject_subject_id) as profile_subject_subject_id'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 28 then 1 else 0 END) DT1'),
                                DB::raw('MAX(CASE when profile_subject_subject_id = 73 then 1 else 0 END) DT2'),

                                DB::raw("MAX(CASE when level_old <> '' and qlhs_profile.profile_year < '".$year."-06-01' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-01-01')) then 1 else 0 END) 'HK2_old'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".$year."-12-31' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".$year."-05-31')) then 1 else 0 END) 'HK1_cur'"),
                                DB::raw("MAX(CASE when level_cur <> '' and qlhs_profile.profile_year < '".($year + 1)."-06-01' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year + 1)."-01-01')) then 1 else 0 END) 'HK2_cur'"),
                                DB::raw("MAX(CASE when level_new <> '' and qlhs_profile.profile_year < '".($year + 1)."-12-31' and profile_subject_subject_id in (28,73) and (profile_leaveschool_date is null or ( profile_leaveschool_date > '".((int)$year + 1)."-05-31')) then 1 else 0 END) 'HK1_new'"));

                        $dataCPHT33 = DB::table(DB::raw("({$dataCPHT3->toSql()}) as m"))->mergeBindings( $dataCPHT3 )
                            ->select('HK2_old', 'HK1_cur', 'HK2_cur', 'HK1_new', 'm.DT1', 'm.DT2', 'm.money_cur')
                            ->groupBy('HK2_old', 'HK1_cur', 'HK2_cur', 'HK1_new', 'm.DT1', 'm.DT2', 'm.money_cur')
                            ->get();

                        if (!is_null($dataCPHT11) && !empty($dataCPHT11) && count($dataCPHT11) > 0) {
                            foreach ($dataCPHT11 as $value) {
                                if ($value->DT1 > 0 || $value->DT2 > 0) {
                                    $DTCPHT_1 = $value->DT1;
                                    $DTCPHT_2 = $value->DT2;

                                    $HK2_OLD = $value->HK2_old;
                                    $HK1_CUR = $value->HK1_cur;
                                    $HK2_CUR = $value->HK2_cur;
                                    $HK1_NEW = $value->HK1_new;
                                    $TYPE = 1;

                                    $money_nhucau_CPHT = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_CPHT = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($dataCPHT22) && !empty($dataCPHT22) && count($dataCPHT22) > 0) {
                            foreach ($dataCPHT22 as $value) {
                                if ($value->DT1 > 0 || $value->DT2 > 0) {
                                    $DTCPHT_1 = $value->DT1;
                                    $DTCPHT_2 = $value->DT2;

                                    $HK2_OLD = $value->HK2_old;
                                    $HK1_CUR = $value->HK1_cur;
                                    $HK2_CUR = $value->HK2_cur;
                                    $HK1_NEW = $value->HK1_new;
                                    $TYPE = 2;

                                    $money_nhucau_CPHT = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_CPHT = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($dataCPHT33) && !empty($dataCPHT33) && count($dataCPHT33) > 0) {
                            foreach ($dataCPHT33 as $value) {
                                if ($value->DT1 > 0 || $value->DT2 > 0) {
                                    $DTCPHT_1 = $value->DT1;
                                    $DTCPHT_2 = $value->DT2;

                                    $HK2_OLD = $value->HK2_old;
                                    $HK1_CUR = $value->HK1_cur;
                                    $HK2_CUR = $value->HK2_cur;
                                    $HK1_NEW = $value->HK1_new;
                                    $TYPE = 3;

                                    // $money_nhucau_CPHT = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_CPHT = $value->money_cur;
                                }
                            }
                        }
                    }
                //-------------------------------------------------------------------HTBT------------------------------------------------------------
                    if ($chedoHTBT > 0 && $type_school != 4) {
                        $getDataTypeA = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_subject.profile_subject_subject_id in (34, 46, 72,101) AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))

                            ->where('qlhs_profile.profile_year', '<', $year.'-06-01')
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HTBT_TA_HK2 > 0 OR old.rppst_Status_HTBT_TO_HK2 > 0 OR old.rppst_Status_HTBT_VHTT_HK2 > 0 OR cur.rppst_Status_HTBT_TA > 0 OR cur.rppst_Status_HTBT_TO > 0 OR cur.rppst_Status_HTBT_VHTT > 0 OR cur.rppst_Status_HTBT_TA_HK2 > 0 OR cur.rppst_Status_HTBT_TO_HK2 > 0 OR cur.rppst_Status_HTBT_VHTT_HK2 > 0)'))
                            
                            ->select(
                                DB::raw('MAX(CASE when profile_bantru > 0 then 1 else 0 END) as bantru'), 
                                DB::raw('MAX(CASE when old.rppst_Status_HTBT_TA_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTBT_TA_HK2 else 0 END) as money_TA_old_HK2'), 
                                DB::raw('MAX(CASE when old.rppst_Status_HTBT_TO_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTBT_TO_HK2 else 0 END) as money_TO_old_HK2'), 
                                DB::raw('MAX(CASE when old.rppst_Status_HTBT_VHTT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTBT_VHTT_HK2 else 0 END) as money_VHTT_old_HK2'), 

                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_TA > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TA else 0 END) as money_TA_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_TO > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TO else 0 END) as money_TO_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_VHTT > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_VHTT else 0 END) as money_VHTT_cur'),

                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_TA_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TA_HK2 else 0 END) as money_TA_cur_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_TO_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TO_HK2 else 0 END) as money_TO_cur_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_VHTT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_VHTT_HK2 else 0 END) as money_VHTT_cur_HK2'), 

                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTBT_TA > 0 then yearnew.qlhs_thcd_tien_nhucau_HTBT_TA else 0 END) as money_TA_new'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTBT_TO > 0 then yearnew.qlhs_thcd_tien_nhucau_HTBT_TO else 0 END) as money_TO_new'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTBT_VHTT > 0 then yearnew.qlhs_thcd_tien_nhucau_HTBT_VHTT else 0 END) as money_VHTT_new'),

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 46 then 1 else 0 END) as hotrotienan'), 
                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 46 then 1 else 0 END) as hotrotieno'), 
                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 46 then 1 else 0 END) as hotroVHTT'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as TA_HK2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as TO_HK2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as VHTT_HK2_old'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as TA_HK1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as TO_HK1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as VHTT_HK1_cur'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as TA_HK2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as TO_HK2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year +1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as VHTT_HK2_cur'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as TA_HK1_new'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as TO_HK1_new'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year +1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as VHTT_HK1_new'));

                        $getDataType1 = DB::table(DB::raw("({$getDataTypeA->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeA )
                            ->select(
                                    'm.bantru', 
                                    'm.hotrotienan', 'm.hotrotieno', 'm.hotroVHTT', 
                                    'm.TA_HK2_old', 'm.TO_HK2_old', 'm.VHTT_HK2_old',
                                    'm.TA_HK1_cur', 'm.TO_HK1_cur', 'm.VHTT_HK1_cur',
                                    'm.TA_HK2_cur', 'm.TO_HK2_cur', 'm.VHTT_HK2_cur',
                                    'm.TA_HK1_new', 'm.TO_HK1_new', 'm.VHTT_HK1_new',

                                    'm.money_TA_old_HK2', 'm.money_TO_old_HK2', 'm.money_VHTT_old_HK2', 
                                    'm.money_TA_cur', 'm.money_TO_cur', 'm.money_VHTT_cur', 
                                    'm.money_TA_cur_HK2', 'm.money_TO_cur_HK2', 'm.money_VHTT_cur_HK2', 
                                    'm.money_TA_new', 'm.money_TO_new', 'm.money_VHTT_new')

                            ->groupBy(
                                    'm.bantru', 
                                    'm.hotrotienan', 'm.hotrotieno', 'm.hotroVHTT', 
                                    'm.TA_HK2_old', 'm.TO_HK2_old', 'm.VHTT_HK2_old',
                                    'm.TA_HK1_cur', 'm.TO_HK1_cur', 'm.VHTT_HK1_cur',
                                    'm.TA_HK2_cur', 'm.TO_HK2_cur', 'm.VHTT_HK2_cur',
                                    'm.TA_HK1_new', 'm.TO_HK1_new', 'm.VHTT_HK1_new',

                                    'm.money_TA_old_HK2', 'm.money_TO_old_HK2', 'm.money_VHTT_old_HK2', 
                                    'm.money_TA_cur', 'm.money_TO_cur', 'm.money_VHTT_cur', 
                                    'm.money_TA_cur_HK2', 'm.money_TO_cur_HK2', 'm.money_VHTT_cur_HK2', 
                                    'm.money_TA_new', 'm.money_TO_new', 'm.money_VHTT_new')
                            ->get();

                        $getDataTypeB = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_subject.profile_subject_subject_id in (34, 46, 72,101) AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))

                            ->where('qlhs_profile.profile_year', '>', $year.'-05-31')
                            ->where('qlhs_profile.profile_year', '<', ($year + 1).'-06-01')
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HTBT_TA_HK2 > 0 OR old.rppst_Status_HTBT_TO_HK2 > 0 OR old.rppst_Status_HTBT_VHTT_HK2 > 0 OR cur.rppst_Status_HTBT_TA > 0 OR cur.rppst_Status_HTBT_TO > 0 OR cur.rppst_Status_HTBT_VHTT > 0 OR cur.rppst_Status_HTBT_TA_HK2 > 0 OR cur.rppst_Status_HTBT_TO_HK2 > 0 OR cur.rppst_Status_HTBT_VHTT_HK2 > 0)'))
                            
                            ->select(
                                DB::raw('MAX(CASE when profile_bantru > 0 then 1 else 0 END) as bantru'), 
                                DB::raw('MAX(CASE when old.rppst_Status_HTBT_TA_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTBT_TA_HK2 else 0 END) as money_TA_old_HK2'), 
                                DB::raw('MAX(CASE when old.rppst_Status_HTBT_TO_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTBT_TO_HK2 else 0 END) as money_TO_old_HK2'), 
                                DB::raw('MAX(CASE when old.rppst_Status_HTBT_VHTT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTBT_VHTT_HK2 else 0 END) as money_VHTT_old_HK2'), 

                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_TA > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TA else 0 END) as money_TA_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_TO > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TO else 0 END) as money_TO_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_VHTT > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_VHTT else 0 END) as money_VHTT_cur'),

                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_TA_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TA_HK2 else 0 END) as money_TA_cur_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_TO_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TO_HK2 else 0 END) as money_TO_cur_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HTBT_VHTT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_VHTT_HK2 else 0 END) as money_VHTT_cur_HK2'), 

                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTBT_TA > 0 then yearnew.qlhs_thcd_tien_nhucau_HTBT_TA else 0 END) as money_TA_new'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTBT_TO > 0 then yearnew.qlhs_thcd_tien_nhucau_HTBT_TO else 0 END) as money_TO_new'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTBT_VHTT > 0 then yearnew.qlhs_thcd_tien_nhucau_HTBT_VHTT else 0 END) as money_VHTT_new'),

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 46 then 1 else 0 END) as hotrotienan'), 
                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 46 then 1 else 0 END) as hotrotieno'), 
                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 46 then 1 else 0 END) as hotroVHTT'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as TA_HK2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as TO_HK2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as VHTT_HK2_old'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as TA_HK1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as TO_HK1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as VHTT_HK1_cur'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as TA_HK2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as TO_HK2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year +1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as VHTT_HK2_cur'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as TA_HK1_new'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as TO_HK1_new'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year +1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as VHTT_HK1_new'));

                        $getDataType2 = DB::table(DB::raw("({$getDataTypeB->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeB )
                            ->select(
                                    'm.bantru', 
                                    'm.hotrotienan', 'm.hotrotieno', 'm.hotroVHTT', 
                                    'm.TA_HK2_old', 'm.TO_HK2_old', 'm.VHTT_HK2_old',
                                    'm.TA_HK1_cur', 'm.TO_HK1_cur', 'm.VHTT_HK1_cur',
                                    'm.TA_HK2_cur', 'm.TO_HK2_cur', 'm.VHTT_HK2_cur',
                                    'm.TA_HK1_new', 'm.TO_HK1_new', 'm.VHTT_HK1_new',

                                    'm.money_TA_old_HK2', 'm.money_TO_old_HK2', 'm.money_VHTT_old_HK2', 
                                    'm.money_TA_cur', 'm.money_TO_cur', 'm.money_VHTT_cur', 
                                    'm.money_TA_cur_HK2', 'm.money_TO_cur_HK2', 'm.money_VHTT_cur_HK2', 
                                    'm.money_TA_new', 'm.money_TO_new', 'm.money_VHTT_new')

                            ->groupBy(
                                    'm.bantru', 
                                    'm.hotrotienan', 'm.hotrotieno', 'm.hotroVHTT', 
                                    'm.TA_HK2_old', 'm.TO_HK2_old', 'm.VHTT_HK2_old',
                                    'm.TA_HK1_cur', 'm.TO_HK1_cur', 'm.VHTT_HK1_cur',
                                    'm.TA_HK2_cur', 'm.TO_HK2_cur', 'm.VHTT_HK2_cur',
                                    'm.TA_HK1_new', 'm.TO_HK1_new', 'm.VHTT_HK1_new',

                                    'm.money_TA_old_HK2', 'm.money_TO_old_HK2', 'm.money_VHTT_old_HK2', 
                                    'm.money_TA_cur', 'm.money_TO_cur', 'm.money_VHTT_cur', 
                                    'm.money_TA_cur_HK2', 'm.money_TO_cur_HK2', 'm.money_VHTT_cur_HK2', 
                                    'm.money_TA_new', 'm.money_TO_new', 'm.money_VHTT_new')
                            ->get();

                        $getDataTypeC = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_subject.profile_subject_subject_id in (34, 46, 72,101) AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.($year + 1).' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.($year + 1).')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.($year + 1).'-'.($year + 2).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.($year + 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 2)))

                            ->where('profile_id', $profile_id)
                            ->where('qlhs_profile.profile_year', '>', ($year + 1).'-05-31')
                            ->where('qlhs_profile.profile_year', '<', ($year + 2).'-06-01')

                            ->select(
                                DB::raw('MAX(CASE when profile_bantru > 0 then 1 else 0 END) as bantru'), 

                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_HTBT_TA > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TA else 0 END) as money_TA_cur'), 
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_HTBT_TO > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_TO else 0 END) as money_TO_cur'), 
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_HTBT_VHTT > 0 then yearcur.qlhs_thcd_tien_nhucau_HTBT_VHTT else 0 END) as money_VHTT_cur'),

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 46 then 1 else 0 END) as hotrotienan'), 
                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 46 then 1 else 0 END) as hotrotieno'), 
                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 46 then 1 else 0 END) as hotroVHTT'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as TA_HK2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as TO_HK2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as VHTT_HK2_old'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as TA_HK1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as TO_HK1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as VHTT_HK1_cur'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as TA_HK2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as TO_HK2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year +1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as VHTT_HK2_cur'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as TA_HK1_new'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile_subject.profile_subject_subject_id = 46 and qlhs_profile.profile_year < "'.($year +1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as TO_HK1_new'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year +1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as VHTT_HK1_new'));

                        $getDataType3 = DB::table(DB::raw("({$getDataTypeC->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeC )
                            ->select(
                                    'm.bantru', 
                                    'm.hotrotienan', 'm.hotrotieno', 'm.hotroVHTT', 
                                    'm.TA_HK2_old', 'm.TO_HK2_old', 'm.VHTT_HK2_old',
                                    'm.TA_HK1_cur', 'm.TO_HK1_cur', 'm.VHTT_HK1_cur',
                                    'm.TA_HK2_cur', 'm.TO_HK2_cur', 'm.VHTT_HK2_cur',
                                    'm.TA_HK1_new', 'm.TO_HK1_new', 'm.VHTT_HK1_new',

                                    'm.money_TA_cur', 'm.money_TO_cur', 'm.money_VHTT_cur')

                            ->groupBy(
                                    'm.bantru', 
                                    'm.hotrotienan', 'm.hotrotieno', 'm.hotroVHTT', 
                                    'm.TA_HK2_old', 'm.TO_HK2_old', 'm.VHTT_HK2_old',
                                    'm.TA_HK1_cur', 'm.TO_HK1_cur', 'm.VHTT_HK1_cur',
                                    'm.TA_HK2_cur', 'm.TO_HK2_cur', 'm.VHTT_HK2_cur',
                                    'm.TA_HK1_new', 'm.TO_HK1_new', 'm.VHTT_HK1_new',

                                    'm.money_TA_cur', 'm.money_TO_cur', 'm.money_VHTT_cur')
                            ->get();

                        if (!is_null($getDataType1) && !empty($getDataType1) && count($getDataType1) > 0) {
                            foreach ($getDataType1 as $value) {
                                if ($value->hotrotienan > 0 || $value->hotrotieno > 0 || $value->hotroVHTT > 0) {
                                    $DTHTBT_TA = $value->hotrotienan;
                                    $DTHTBT_TO = $value->hotrotieno;
                                    $DTHTBT_VHTT = $value->hotroVHTT;

                                    $HTBT_TA_HK2_OLD = $value->TA_HK2_old;
                                    $HTBT_TA_HK1_CUR = $value->TA_HK1_cur;
                                    $HTBT_TA_HK2_CUR = $value->TA_HK2_cur;
                                    $HTBT_TA_HK1_NEW = $value->TA_HK1_new;

                                    $HTBT_TO_HK2_OLD = $value->TO_HK2_old;
                                    $HTBT_TO_HK1_CUR = $value->TO_HK1_cur;
                                    $HTBT_TO_HK2_CUR = $value->TO_HK2_cur;
                                    $HTBT_TO_HK1_NEW = $value->TO_HK1_new;
                                    $TYPE = 1;

                                    $money_nhucau_HTBT_TA = $value->money_TA_old_HK2 + $value->money_TA_cur;
                                    $money_dutoan_HTBT_TA = $value->money_TA_cur_HK2 + $value->money_TA_new;
                                    $money_nhucau_HTBT_VHTT = $value->money_VHTT_old_HK2 + $value->money_VHTT_cur;
                                    $money_dutoan_HTBT_VHTT = $value->money_VHTT_cur_HK2 + $value->money_VHTT_new;

                                    if ($value->bantru == 0) {
                                        $money_nhucau_HTBT_TO = $value->money_TO_old_HK2 + $value->money_TO_cur;
                                        $money_dutoan_HTBT_TO = $value->money_TO_cur_HK2 + $value->money_TO_new;
                                    }
                                }
                            }
                        }

                        if (!is_null($getDataType2) && !empty($getDataType2) && count($getDataType2) > 0) {
                            foreach ($getDataType2 as $value) {
                                if ($value->hotrotienan > 0 || $value->hotrotieno > 0 || $value->hotroVHTT > 0) {
                                    $DTHTBT_TA = $value->hotrotienan;
                                    $DTHTBT_TO = $value->hotrotieno;
                                    $DTHTBT_VHTT = $value->hotroVHTT;

                                    $HTBT_TA_HK2_OLD = $value->TA_HK2_old;
                                    $HTBT_TA_HK1_CUR = $value->TA_HK1_cur;
                                    $HTBT_TA_HK2_CUR = $value->TA_HK2_cur;
                                    $HTBT_TA_HK1_NEW = $value->TA_HK1_new;

                                    $HTBT_TO_HK2_OLD = $value->TO_HK2_old;
                                    $HTBT_TO_HK1_CUR = $value->TO_HK1_cur;
                                    $HTBT_TO_HK2_CUR = $value->TO_HK2_cur;
                                    $HTBT_TO_HK1_NEW = $value->TO_HK1_new;
                                    $TYPE = 2;
                                    
                                    $money_nhucau_HTBT_TA = $value->money_TA_old_HK2 + $value->money_TA_cur;
                                    $money_dutoan_HTBT_TA = $value->money_TA_cur_HK2 + $value->money_TA_new;
                                    $money_nhucau_HTBT_VHTT = $value->money_VHTT_old_HK2 + $value->money_VHTT_cur;
                                    $money_dutoan_HTBT_VHTT = $value->money_VHTT_cur_HK2 + $value->money_VHTT_new;

                                    if ($value->bantru == 0) {
                                        $money_nhucau_HTBT_TO = $value->money_TO_old_HK2 + $value->money_TO_cur;
                                        $money_dutoan_HTBT_TO = $value->money_TO_cur_HK2 + $value->money_TO_new;
                                    }
                                }
                            }
                        }

                        if (!is_null($getDataType3) && !empty($getDataType3) && count($getDataType3) > 0) {
                            foreach ($getDataType3 as $value) {
                                if ($value->hotrotienan > 0 || $value->hotrotieno > 0 || $value->hotroVHTT > 0) {
                                    $DTHTBT_TA = $value->hotrotienan;
                                    $DTHTBT_TO = $value->hotrotieno;
                                    $DTHTBT_VHTT = $value->hotroVHTT;

                                    $HTBT_TA_HK2_OLD = $value->TA_HK2_old;
                                    $HTBT_TA_HK1_CUR = $value->TA_HK1_cur;
                                    $HTBT_TA_HK2_CUR = $value->TA_HK2_cur;
                                    $HTBT_TA_HK1_NEW = $value->TA_HK1_new;

                                    $HTBT_TO_HK2_OLD = $value->TO_HK2_old;
                                    $HTBT_TO_HK1_CUR = $value->TO_HK1_cur;
                                    $HTBT_TO_HK2_CUR = $value->TO_HK2_cur;
                                    $HTBT_TO_HK1_NEW = $value->TO_HK1_new;
                                    $TYPE = 3;
                                    
                                    $money_dutoan_HTBT_TA = $value->money_TA_cur;
                                    
                                    $money_dutoan_HTBT_VHTT = $value->money_VHTT_cur;

                                    if ($value->bantru == 0) {
                                        $money_dutoan_HTBT_TO = $value->money_TO_cur;
                                    }
                                }
                            }
                        }
                    }
                //-------------------------------------------------------------------HSKT------------------------------------------------------------
                    if ($chedoHSKT > 0) {
                        $getDataTypeA = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject',function($q) use($year){
                                $q->on('qlhs_profile_subject.profile_subject_profile_id','qlhs_profile.profile_id')
                                ->where(function($p){
                                    $p->orWhere('qlhs_profile_subject.profile_subject_subject_id',74)
                                    ->orWhere('qlhs_profile_subject.profile_subject_subject_id',100);
                                })
                                ->where('qlhs_profile_subject.active',1)
                                ->where('qlhs_profile_subject.start_year','<=',$year)
                                ->where(function($r) use ($year){
                                    $r->orWhere('qlhs_profile_subject.end_year',null)
                                    ->orWhere('qlhs_profile_subject.end_year','>',$year);
                                });
                            })
                            ->join('qlhs_profile_history',function($q) use ($year){
                                $q->on('qlhs_profile_history.history_profile_id','qlhs_profile.profile_id')
                                ->where('qlhs_profile_history.history_year',$year.'-'.($year + 1));
                            })                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->where('qlhs_profile.profile_year', '<', $year.'-06-01')
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HSKT_HB_HK2 > 0 OR old.rppst_Status_HSKT_DDHT_HK2 > 0 OR cur.rppst_Status_HSKT_HB > 0 OR cur.rppst_Status_HSKT_DDHT > 0 OR cur.rppst_Status_HSKT_HB_HK2 > 0 OR cur.rppst_Status_HSKT_DDHT_HK2 > 0)'))
                            
                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HSKT_HB_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HSKT_HB_HK2 else 0 END) as money_HB_old_HK2'), 
                                DB::raw('MAX(CASE when old.rppst_Status_HSKT_DDHT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HSKT_DDHT_HK2 else 0 END) as money_DDHT_old_HK2'), 
                                
                                DB::raw('MAX(CASE when cur.rppst_Status_HSKT_HB > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_HB else 0 END) as money_HB_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HSKT_DDHT > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_DDHT else 0 END) as money_DDHT_cur'), 
                                
                                DB::raw('MAX(CASE when cur.rppst_Status_HSKT_HB_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_HB_HK2 else 0 END) as money_HB_cur_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HSKT_DDHT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_DDHT_HK2 else 0 END) as money_DDHT_cur_HK2'), 
                                
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HSKT_HB > 0 then yearnew.qlhs_thcd_tien_nhucau_HSKT_HB else 0 END) as money_HB_new'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HSKT_DDHT > 0 then yearnew.qlhs_thcd_tien_nhucau_HSKT_DDHT else 0 END) as money_DDHT_new'), 

                                DB::raw('MAX(CASE when (qlhs_profile_subject.profile_subject_subject_id = 74 OR
                                qlhs_profile_subject.profile_subject_subject_id = 100) then 1 else 0 END) as hotrohocbong'), 
                                DB::raw('MAX(CASE when (qlhs_profile_subject.profile_subject_subject_id = 74 OR
                                    qlhs_profile_subject.profile_subject_subject_id = 100)  then 1 else 0 END) as hotromuadodunght'),
                                
                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hotro_hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hotro_hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hotro_hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hotro_hocky1_new'));

                        $getDataType1 = DB::table(DB::raw("({$getDataTypeA->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeA )
                            ->select(
                                    'm.hotrohocbong', 'm.hotromuadodunght',
                                    'm.hotro_hocky2_old', 
                                    'm.hotro_hocky1_cur', 
                                    'm.hotro_hocky2_cur', 
                                    'm.hotro_hocky1_new', 

                                    'm.money_HB_old_HK2', 'm.money_DDHT_old_HK2', 
                                    'm.money_HB_cur', 'm.money_DDHT_cur', 
                                    'm.money_HB_cur_HK2', 'm.money_DDHT_cur_HK2',
                                    'm.money_HB_new', 'm.money_DDHT_new')

                            ->groupBy(
                                    'm.hotrohocbong', 'm.hotromuadodunght',
                                    'm.hotro_hocky2_old', 
                                    'm.hotro_hocky1_cur', 
                                    'm.hotro_hocky2_cur', 
                                    'm.hotro_hocky1_new', 

                                    'm.money_HB_old_HK2', 'm.money_DDHT_old_HK2', 
                                    'm.money_HB_cur', 'm.money_DDHT_cur', 
                                    'm.money_HB_cur_HK2', 'm.money_DDHT_cur_HK2',
                                    'm.money_HB_new', 'm.money_DDHT_new')
                            ->get();


                        $getDataTypeB = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and (qlhs_profile_subject.profile_subject_subject_id = 74 OR qlhs_profile_subject.profile_subject_subject_id = 100) AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', '=', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HSKT_HB_HK2 > 0 OR old.rppst_Status_HSKT_DDHT_HK2 > 0 OR cur.rppst_Status_HSKT_HB > 0 OR cur.rppst_Status_HSKT_DDHT > 0 OR cur.rppst_Status_HSKT_HB_HK2 > 0 OR cur.rppst_Status_HSKT_DDHT_HK2 > 0)'))
                            ->where('profile_year', '>', $year.'-05-31')
                            ->where('profile_year', '<', ($year + 1).'-06-01')
                            
                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HSKT_HB_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HSKT_HB_HK2 else 0 END) as money_HB_old_HK2'), 
                                DB::raw('MAX(CASE when old.rppst_Status_HSKT_DDHT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HSKT_DDHT_HK2 else 0 END) as money_DDHT_old_HK2'), 
                                
                                DB::raw('MAX(CASE when cur.rppst_Status_HSKT_HB > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_HB else 0 END) as money_HB_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HSKT_DDHT > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_DDHT else 0 END) as money_DDHT_cur'), 
                                
                                DB::raw('MAX(CASE when cur.rppst_Status_HSKT_HB_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_HB_HK2 else 0 END) as money_HB_cur_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HSKT_DDHT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_DDHT_HK2 else 0 END) as money_DDHT_cur_HK2'), 
                                
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HSKT_HB > 0 then yearnew.qlhs_thcd_tien_nhucau_HSKT_HB else 0 END) as money_HB_new'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HSKT_DDHT > 0 then yearnew.qlhs_thcd_tien_nhucau_HSKT_DDHT else 0 END) as money_DDHT_new'), 

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 74 OR
                                    qlhs_profile_subject.profile_subject_subject_id = 100 then 1 else 0 END) as hotrohocbong'), 
                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 74 OR
                                    qlhs_profile_subject.profile_subject_subject_id = 100 then 1 else 0 END) as hotromuadodunght'),
                                
                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hotro_hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hotro_hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hotro_hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hotro_hocky1_new'));

                        $getDataType2 = DB::table(DB::raw("({$getDataTypeB->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeB )
                            ->select(
                                    'm.hotrohocbong', 'm.hotromuadodunght',
                                    'm.hotro_hocky2_old', 
                                    'm.hotro_hocky1_cur', 
                                    'm.hotro_hocky2_cur', 
                                    'm.hotro_hocky1_new', 

                                    'm.money_HB_old_HK2', 'm.money_DDHT_old_HK2', 
                                    'm.money_HB_cur', 'm.money_DDHT_cur', 
                                    'm.money_HB_cur_HK2', 'm.money_DDHT_cur_HK2',
                                    'm.money_HB_new', 'm.money_DDHT_new')

                            ->groupBy(
                                    'm.hotrohocbong', 'm.hotromuadodunght',
                                    'm.hotro_hocky2_old', 
                                    'm.hotro_hocky1_cur', 
                                    'm.hotro_hocky2_cur', 
                                    'm.hotro_hocky1_new', 

                                    'm.money_HB_old_HK2', 'm.money_DDHT_old_HK2', 
                                    'm.money_HB_cur', 'm.money_DDHT_cur', 
                                    'm.money_HB_cur_HK2', 'm.money_DDHT_cur_HK2',
                                    'm.money_HB_new', 'm.money_DDHT_new')
                            ->get();

                        $getDataTypeC = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and (qlhs_profile_subject.profile_subject_subject_id = 74 OR qlhs_profile_subject.profile_subject_subject_id = 100) AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.($year + 1).' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.($year + 1).')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', '=', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.($year + 1).'-'.($year + 2).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.$year.''))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.($year + 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 2).''))
                            
                            ->where('profile_id', $profile_id)
                            ->where('qlhs_profile.profile_year', '>', ($year + 1).'-05-31')
                            ->where('qlhs_profile.profile_year', '<', ($year + 2).'-06-01')
                            
                            ->select(
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_HSKT_HB > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_HB else 0 END) as money_HB_cur'), 
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_HSKT_DDHT > 0 then yearcur.qlhs_thcd_tien_nhucau_HSKT_DDHT else 0 END) as money_DDHT_cur'), 

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 74 OR
                                    qlhs_profile_subject.profile_subject_subject_id = 100 then 1 else 0 END) as hotrohocbong'), 
                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 74 OR
                                    qlhs_profile_subject.profile_subject_subject_id = 100 then 1 else 0 END) as hotromuadodunght'),
                                
                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hotro_hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hotro_hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hotro_hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hotro_hocky1_new'));

                        $getDataType3 = DB::table(DB::raw("({$getDataTypeC->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeC )
                            ->select(
                                    'm.hotrohocbong', 'm.hotromuadodunght',
                                    'm.hotro_hocky2_old', 
                                    'm.hotro_hocky1_cur', 
                                    'm.hotro_hocky2_cur', 
                                    'm.hotro_hocky1_new', 

                                    'm.money_HB_cur', 'm.money_DDHT_cur')

                            ->groupBy(
                                    'm.hotrohocbong', 'm.hotromuadodunght',
                                    'm.hotro_hocky2_old', 
                                    'm.hotro_hocky1_cur', 
                                    'm.hotro_hocky2_cur', 
                                    'm.hotro_hocky1_new', 
         
                                    'm.money_HB_cur', 'm.money_DDHT_cur')
                            ->get();

                        if (!is_null($getDataType1) && !empty($getDataType1) && count($getDataType1) > 0) {
                            foreach ($getDataType1 as $value) {
                                if ($value->hotrohocbong > 0 || $value->hotromuadodunght > 0) {
                                    $DTHSKT_HB = $value->hotrohocbong;
                                    $DTHSKT_DDHT = $value->hotromuadodunght;

                                    $HK2_OLD = $value->hotro_hocky2_old;
                                    $HK1_CUR = $value->hotro_hocky1_cur;
                                    $HK2_CUR = $value->hotro_hocky2_cur;
                                    $HK1_NEW = $value->hotro_hocky1_new;
                                    $TYPE = 1;

                                    $money_nhucau_HSKT_HB = $value->money_HB_old_HK2 + $value->money_HB_cur;
                                    $money_dutoan_HSKT_HB = $value->money_HB_cur_HK2 + $value->money_HB_new;
                                    $money_nhucau_HSKT_DDHT = $value->money_DDHT_old_HK2 + $value->money_DDHT_cur;
                                    $money_dutoan_HSKT_DDHT = $value->money_DDHT_cur_HK2 + $value->money_DDHT_new;
                                }
                            }
                        }

                        if (!is_null($getDataType2) && !empty($getDataType2) && count($getDataType2) > 0) {
                            foreach ($getDataType2 as $value) {
                                if ($value->hotrohocbong > 0 || $value->hotromuadodunght > 0) {
                                    $DTHSKT_HB = $value->hotrohocbong;
                                    $DTHSKT_DDHT = $value->hotromuadodunght;

                                    $HK2_OLD = $value->hotro_hocky2_old;
                                    $HK1_CUR = $value->hotro_hocky1_cur;
                                    $HK2_CUR = $value->hotro_hocky2_cur;
                                    $HK1_NEW = $value->hotro_hocky1_new;
                                    $TYPE = 2;

                                    $money_nhucau_HSKT_HB = $value->money_HB_old_HK2 + $value->money_HB_cur;
                                    $money_dutoan_HSKT_HB = $value->money_HB_cur_HK2 + $value->money_HB_new;
                                    $money_nhucau_HSKT_DDHT = $value->money_DDHT_old_HK2 + $value->money_DDHT_cur;
                                    $money_dutoan_HSKT_DDHT = $value->money_DDHT_cur_HK2 + $value->money_DDHT_new;
                                }
                            }
                        }

                        if (!is_null($getDataType3) && !empty($getDataType3) && count($getDataType3) > 0) {
                            foreach ($getDataType3 as $value) {
                                if ($value->hotrohocbong > 0 || $value->hotromuadodunght > 0) {
                                    $DTHSKT_HB = $value->hotrohocbong;
                                    $DTHSKT_DDHT = $value->hotromuadodunght;

                                    $HK2_OLD = $value->hotro_hocky2_old;
                                    $HK1_CUR = $value->hotro_hocky1_cur;
                                    $HK2_CUR = $value->hotro_hocky2_cur;
                                    $HK1_NEW = $value->hotro_hocky1_new;
                                    $TYPE = 3;

                                    // $money_nhucau_HSKT_HB = $value->money_HB_old_HK2 + $value->money_HB_cur;
                                    $money_dutoan_HSKT_HB = $value->money_HB_cur;
                                    // $money_nhucau_HSKT_DDHT = $value->money_DDHT_old_HK2 + $value->money_DDHT_cur;
                                    $money_dutoan_HSKT_DDHT = $value->money_DDHT_cur;
                                }
                            }
                        }
                    }
                //-------------------------------------------------------------------HTATHS----------------------------------------------------------
                    if ($chedoHTATHS > 0 && $type_school == 2) {
                        $getDataTypeA = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_subject.profile_subject_subject_id = 69 AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', '=', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HTATHS_HK2 > 0 OR cur.rppst_Status_HTATHS > 0 OR cur.rppst_Status_HTATHS_HK2 > 0)'))
                            ->where('qlhs_profile.profile_year', '<', $year.'-06-01')
                            ->where('qlhs_profile.profile_statusNQ57', 1)
                            
                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HTATHS_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTATHS_HK2 else 0 END) as money_old_HK2'), 

                                DB::raw('MAX(CASE when cur.rppst_Status_HTATHS > 0 then yearcur.qlhs_thcd_tien_nhucau_HTATHS else 0 END) as money_cur'), 

                                DB::raw('MAX(CASE when cur.rppst_Status_HTATHS_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTATHS_HK2 else 0 END) as money_cur_HK2'), 

                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTATHS > 0 then yearnew.qlhs_thcd_tien_nhucau_HTATHS else 0 END) as money_new'), 

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 69 then 1 else 0 END) as hotrokinhphi'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hocky1_new'));
                        
                        $getDataType1 = DB::table(DB::raw("({$getDataTypeA->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeA )
                            ->select(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 

                                    'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')

                            ->groupBy(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 

                                    'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();

                        $getDataTypeB = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_subject.profile_subject_subject_id = 69 AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HTATHS_HK2 > 0 OR cur.rppst_Status_HTATHS > 0 OR cur.rppst_Status_HTATHS_HK2 > 0)'))
                            ->where('qlhs_profile.profile_year', '>', $year.'-05-31')
                            ->where('qlhs_profile.profile_year', '<', ($year + 1).'-06-01')
                            ->where('qlhs_profile.profile_statusNQ57', 1)
                            
                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HTATHS_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HTATHS_HK2 else 0 END) as money_old_HK2'), 

                                DB::raw('MAX(CASE when cur.rppst_Status_HTATHS > 0 then yearcur.qlhs_thcd_tien_nhucau_HTATHS else 0 END) as money_cur'), 

                                DB::raw('MAX(CASE when cur.rppst_Status_HTATHS_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HTATHS_HK2 else 0 END) as money_cur_HK2'), 

                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HTATHS > 0 then yearnew.qlhs_thcd_tien_nhucau_HTATHS else 0 END) as money_new'), 

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 69 then 1 else 0 END) as hotrokinhphi'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hocky1_new'));
                        
                        $getDataType2 = DB::table(DB::raw("({$getDataTypeB->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeB )
                            ->select(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 

                                    'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')

                            ->groupBy(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 

                                    'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();

                        $getDataTypeC = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', '=', DB::raw('qlhs_profile.profile_id and qlhs_profile_subject.profile_subject_subject_id = 69 AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.($year + 1).' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.($year + 1).')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', '=', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.($year + 1).'-'.($year + 2).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.($year + 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 2)))
                            
                            ->where('profile_id', $profile_id)
                            ->where('qlhs_profile.profile_year', '>', ($year + 1).'-05-31')
                            ->where('qlhs_profile.profile_year', '<', ($year + 2).'-06-01')
                            ->where('qlhs_profile.profile_statusNQ57', 1)
                            
                            ->select(
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_HTATHS > 0 then yearcur.qlhs_thcd_tien_nhucau_HTATHS else 0 END) as money_cur'), 

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 69 then 1 else 0 END) as hotrokinhphi'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hocky1_new'));
                        
                        $getDataType3 = DB::table(DB::raw("({$getDataTypeC->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeC )
                            ->select(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur')

                            ->groupBy(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur')
                            ->get();

                        if (!is_null($getDataType1) && !empty($getDataType1) && count($getDataType1) > 0) {
                            foreach ($getDataType1 as $value) {
                                if ($value->hotrokinhphi > 0) {
                                    $DTHTATHS = $value->hotrokinhphi;

                                    $HK2_OLD = $value->hocky2_old;
                                    $HK1_CUR = $value->hocky1_cur;
                                    $HK2_CUR = $value->hocky2_cur;
                                    $HK1_NEW = $value->hocky1_new;
                                    $TYPE = 1;

                                    $money_nhucau_HTATHS = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HTATHS = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($getDataType2) && !empty($getDataType2) && count($getDataType2) > 0) {
                            foreach ($getDataType2 as $value) {
                                if ($value->hotrokinhphi > 0) {
                                    $DTHTATHS = $value->hotrokinhphi;

                                    $HK2_OLD = $value->hocky2_old;
                                    $HK1_CUR = $value->hocky1_cur;
                                    $HK2_CUR = $value->hocky2_cur;
                                    $HK1_NEW = $value->hocky1_new;
                                    $TYPE = 2;

                                    $money_nhucau_HTATHS = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HTATHS = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($getDataType3) && !empty($getDataType3) && count($getDataType3) > 0) {
                            foreach ($getDataType3 as $value) {
                                if ($value->hotrokinhphi > 0) {
                                    $DTHTATHS = $value->hotrokinhphi;

                                    $HK2_OLD = $value->hocky2_old;
                                    $HK1_CUR = $value->hocky1_cur;
                                    $HK2_CUR = $value->hocky2_cur;
                                    $HK1_NEW = $value->hocky1_new;
                                    $TYPE = 3;

                                    // $money_nhucau_HSDTS = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HTATHS = $value->money_cur;
                                }
                            }
                        }
                    }
                //-------------------------------------------------------------------HBHSDTNT--------------------------------------------------------
                    if ($chedoHBHSDTNT > 0 && $type_school == 4) {
                        $getDataTypeA = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_subject.profile_subject_subject_id = 70 AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HBHSDTNT_HK2 > 0 OR cur.rppst_Status_HBHSDTNT > 0 OR cur.rppst_Status_HBHSDTNT_HK2 > 0)'))
                            ->where('qlhs_profile.profile_year', '<', $year.'-06-01')

                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HBHSDTNT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HBHSDTNT_HK2 else 0 END) as money_old_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HBHSDTNT > 0 then yearcur.qlhs_thcd_tien_nhucau_HBHSDTNT else 0 END) as money_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HBHSDTNT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HBHSDTNT_HK2 else 0 END) as money_cur_HK2'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HBHSDTNT > 0 then yearnew.qlhs_thcd_tien_nhucau_HBHSDTNT else 0 END) as money_new'), 

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 70 then 1 else 0 END) as hotrokinhphi'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hocky1_new'));
                        
                        $getDataType1 = DB::table(DB::raw("({$getDataTypeA->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeA )
                            ->select(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 

                                    'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')

                            ->groupBy(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 

                                    'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();


                        $getDataTypeB = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_subject.profile_subject_subject_id = 70 AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.$year.' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.$year.')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.$year.'-'.($year + 1).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.($year - 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 1)))

                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as old', 'old.rppst_profile_id', DB::raw('profile_id AND old.rppst_year = '.($year - 1)))
                            ->leftJoin('qlhs_hosobaocao_trangthai_Truong as cur', 'cur.rppst_profile_id', DB::raw('profile_id AND cur.rppst_year = '.$year))
                            
                            ->where('profile_id', DB::raw($profile_id.' AND (old.rppst_Status_HBHSDTNT_HK2 > 0 OR cur.rppst_Status_HBHSDTNT > 0 OR cur.rppst_Status_HBHSDTNT_HK2 > 0)'))
                            ->where('qlhs_profile.profile_year', '>', $year.'-05-31')
                            ->where('qlhs_profile.profile_year', '<', ($year + 1).'-06-01')
                            
                            ->select(
                                DB::raw('MAX(CASE when old.rppst_Status_HBHSDTNT_HK2 > 0 then yearold.qlhs_thcd_tien_nhucau_HBHSDTNT_HK2 else 0 END) as money_old_HK2'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HBHSDTNT > 0 then yearcur.qlhs_thcd_tien_nhucau_HBHSDTNT else 0 END) as money_cur'), 
                                DB::raw('MAX(CASE when cur.rppst_Status_HBHSDTNT_HK2 > 0 then yearcur.qlhs_thcd_tien_nhucau_HBHSDTNT_HK2 else 0 END) as money_cur_HK2'), 
                                DB::raw('MAX(CASE when yearnew.qlhs_thcd_tien_nhucau_HBHSDTNT > 0 then yearnew.qlhs_thcd_tien_nhucau_HBHSDTNT else 0 END) as money_new'), 

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 70 then 1 else 0 END) as hotrokinhphi'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hocky1_new'));
                        
                        $getDataType2 = DB::table(DB::raw("({$getDataTypeB->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeB )
                            ->select(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 

                                    'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')

                            ->groupBy(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 

                                    'm.money_old_HK2', 'm.money_cur', 'm.money_cur_HK2', 'm.money_new')
                            ->get();


                        $getDataTypeC = DB::table('qlhs_profile')
                            ->join('qlhs_profile_subject', 'qlhs_profile_subject.profile_subject_profile_id', DB::raw('qlhs_profile.profile_id and qlhs_profile_subject.profile_subject_subject_id = 70 AND qlhs_profile_subject.active = 1 AND qlhs_profile_subject.start_year <= '.($year + 1).' AND (qlhs_profile_subject.end_year is null OR qlhs_profile_subject.end_year > '.($year + 1).')'))
                            ->join('qlhs_profile_history', 'qlhs_profile_history.history_profile_id', '=', DB::raw('qlhs_profile.profile_id and qlhs_profile_history.history_year = "'.($year + 1).'-'.($year + 2).'"'))
                            
                            ->leftJoin('qlhs_tonghopchedo as yearold', 'yearold.qlhs_thcd_profile_id', DB::raw('profile_id AND yearold.qlhs_thcd_nam = '.$year))
                            ->leftJoin('qlhs_tonghopchedo as yearcur', 'yearcur.qlhs_thcd_profile_id', DB::raw('profile_id AND yearcur.qlhs_thcd_nam = '.($year + 1)))
                            ->leftJoin('qlhs_tonghopchedo as yearnew', 'yearnew.qlhs_thcd_profile_id', DB::raw('profile_id AND yearnew.qlhs_thcd_nam = '.($year + 2)))
                            
                            ->where('profile_id', $profile_id)
                            ->where('qlhs_profile.profile_year', '>', ($year + 1).'-05-31')
                            ->where('qlhs_profile.profile_year', '<', ($year + 2).'-06-01')
                            
                            ->select(
                                DB::raw('MAX(CASE when yearcur.qlhs_thcd_tien_nhucau_HBHSDTNT > 0 then yearcur.qlhs_thcd_tien_nhucau_HBHSDTNT else 0 END) as money_cur'), 

                                DB::raw('MAX(CASE when qlhs_profile_subject.profile_subject_subject_id = 70 then 1 else 0 END) as hotrokinhphi'), 

                                DB::raw('MAX(CASE when qlhs_profile_history.level_old <> "" and qlhs_profile.profile_year < "'.$year.'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-01-01")) then 1 else 0 END) as hocky2_old'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.$year.'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.$year.'-05-31")) then 1 else 0 END) as hocky1_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_cur <> "" and qlhs_profile.profile_year < "'.($year + 1).'-06-01" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-01-01")) then 1 else 0 END) as hocky2_cur'), 
                                DB::raw('MAX(CASE when qlhs_profile_history.level_new <> "" and qlhs_profile.profile_year < "'.($year + 1).'-12-31" and (qlhs_profile.profile_leaveschool_date is null or ( qlhs_profile.profile_leaveschool_date > "'.($year + 1).'-05-31")) then 1 else 0 END) as hocky1_new'));
                        
                        $getDataType3 = DB::table(DB::raw("({$getDataTypeC->toSql()}) as m"))
                            ->mergeBindings( $getDataTypeC )
                            ->select(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur')

                            ->groupBy(
                                    'm.hotrokinhphi',
                                    'm.hocky2_old', 
                                    'm.hocky1_cur', 
                                    'm.hocky2_cur', 
                                    'm.hocky1_new', 
                                    'm.money_cur')
                            ->get();

                        if (!is_null($getDataType1) && !empty($getDataType1) && count($getDataType1) > 0) {
                            foreach ($getDataType1 as $value) {
                                if ($value->hotrokinhphi > 0) {
                                    $DTHBHSDTNT = $value->hotrokinhphi;

                                    $HK2_OLD = $value->hocky2_old;
                                    $HK1_CUR = $value->hocky1_cur;
                                    $HK2_CUR = $value->hocky2_cur;
                                    $HK1_NEW = $value->hocky1_new;
                                    $TYPE = 1;

                                    $money_nhucau_HBHSDTNT = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HBHSDTNT = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($getDataType2) && !empty($getDataType2) && count($getDataType2) > 0) {
                            foreach ($getDataType2 as $value) {
                                if ($value->hotrokinhphi > 0) {
                                    $DTHBHSDTNT = $value->hotrokinhphi;

                                    $HK2_OLD = $value->hocky2_old;
                                    $HK1_CUR = $value->hocky1_cur;
                                    $HK2_CUR = $value->hocky2_cur;
                                    $HK1_NEW = $value->hocky1_new;
                                    $TYPE = 2;

                                    $money_nhucau_HBHSDTNT = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HBHSDTNT = $value->money_cur_HK2 + $value->money_new;
                                }
                            }
                        }

                        if (!is_null($getDataType3) && !empty($getDataType3) && count($getDataType3) > 0) {
                            foreach ($getDataType3 as $value) {
                                if ($value->hotrokinhphi > 0) {
                                    $DTHBHSDTNT = $value->hotrokinhphi;

                                    $HK2_OLD = $value->hocky2_old;
                                    $HK1_CUR = $value->hocky1_cur;
                                    $HK2_CUR = $value->hocky2_cur;
                                    $HK1_NEW = $value->hocky1_new;
                                    $TYPE = 3;

                                    // $money_nhucau_HSDTS = $value->money_old_HK2 + $value->money_cur;
                                    $money_dutoan_HBHSDTNT = $value->money_cur;
                                }
                            }
                        }
                    }
                $TONG_NHUCAU = $money_nhucau_MGHP + $money_nhucau_CPHT + $money_nhucau_HTAT + $money_nhucau_HTBT_TA + $money_nhucau_HTBT_TO + $money_nhucau_HTBT_VHTT + $money_nhucau_HSKT_HB + $money_nhucau_HSKT_DDHT + $money_nhucau_HSDTS + $money_nhucau_HTATHS + $money_nhucau_HBHSDTNT;
                $TONG_DUTOAN = $money_dutoan_MGHP + $money_dutoan_CPHT + $money_dutoan_HTAT + $money_dutoan_HTBT_TA + $money_dutoan_HTBT_TO + $money_dutoan_HTBT_VHTT + $money_dutoan_HSKT_HB + $money_dutoan_HSKT_DDHT + $money_dutoan_HSDTS + $money_dutoan_HTATHS + $money_dutoan_HBHSDTNT;
                if($type == 0){
                    echo 'insert';
                   // Insert Tiền theo công văn của từng học sinh và cấp nhận
                    $insertTien = DB::table('qlhs_nhucau_dutoan')
                    ->insert([
                        'namhoc' => $year, 
                        'id_profile' => $profile_id, 
                        'rpp_MGHP_100_1' => $DTMien100_1, 
                        'rpp_MGHP_100_2' => $DTMien100_2, 
                        'rpp_MGHP_100_3' => $DTMien100_3, 
                        'rpp_MGHP_100_4' => $DTMien100_4, 
                        'rpp_MGHP_100_5' => $DTMien100_5, 
                        'rpp_MGHP_100_6' => $DTMien100_6, 
                        'rpp_MGHP_70' => $DTMien70, 
                        'rpp_MGHP_50_1' => $DTMien50_1, 
                        'rpp_MGHP_50_2' => $DTMien50_2, 
                        'rpp_MGHP_NhuCau' => $money_nhucau_MGHP, 
                        'rpp_MGHP_DuToan' => $money_dutoan_MGHP, 
                        'rpp_CPHT_DT1' => $DTCPHT_1, 
                        'rpp_CPHT_DT2' => $DTCPHT_2, 
                        'rpp_CPHT_NhuCau' => $money_nhucau_CPHT, 
                        'rpp_CPHT_DuToan' => $money_dutoan_CPHT, 

                        'rpp_HTAT_DT1' => $DTHTAT_1, 
                        'rpp_HTAT_DT2' => $DTHTAT_2, 
                        'rpp_HTAT_DT3' => $DTHTAT_3, 
                        'rpp_HTAT_DT4' => $DTHTAT_4, 
                        'rpp_HTAT_NhuCau' => $money_nhucau_HTAT, 
                        'rpp_HTAT_DuToan' => $money_dutoan_HTAT, 
                        'rpp_HTBT_HTTA' => $DTHTBT_TA, 
                        'rpp_HTBT_HTTO' => $DTHTBT_TO, 
                        'rpp_HTBT_HTTA_HK2_old' => $HTBT_TA_HK2_OLD, 
                        'rpp_HTBT_HTTO_HK2_old' => $HTBT_TO_HK2_OLD, 
                        'rpp_HTBT_HTTA_HK1_cur' => $HTBT_TA_HK1_CUR, 
                        'rpp_HTBT_HTTO_HK1_cur' => $HTBT_TO_HK1_CUR, 
                        'rpp_HTBT_HTTA_HK2_cur' => $HTBT_TA_HK2_CUR, 
                        'rpp_HTBT_HTTO_HK2_cur' => $HTBT_TO_HK2_CUR, 
                        'rpp_HTBT_HTTA_HK1_new' => $HTBT_TA_HK1_NEW, 
                        'rpp_HTBT_HTTO_HK1_new' => $HTBT_TO_HK1_NEW, 
                        'rpp_HTBT_NhuCau_TA' => $money_nhucau_HTBT_TA, 
                        'rpp_HTBT_NhuCau_TO' => $money_nhucau_HTBT_TO, 
                        'rpp_HTBT_NhuCau_VHTT' => $money_nhucau_HTBT_VHTT, 
                        'rpp_HTBT_Tong_NhuCau' => ($money_nhucau_HTBT_TA + $money_nhucau_HTBT_TO + $money_nhucau_HTBT_VHTT), 
                        'rpp_HTBT_DuToan_TA' => $money_dutoan_HTBT_TA, 
                        'rpp_HTBT_DuToan_TO' => $money_dutoan_HTBT_TO, 
                        'rpp_HTBT_DuToan_VHTT' => $money_dutoan_HTBT_VHTT, 
                        'rpp_HTBT_Tong_DuToan' => ($money_dutoan_HTBT_TA + $money_dutoan_HTBT_TO + $money_dutoan_HTBT_VHTT), 
                        'rpp_HSKT_HTHB' => $DTHSKT_HB, 
                        'rpp_HSKT_HTDDHT' => $DTHSKT_DDHT, 
                        'rpp_HSKT_NhuCau_HB' => $money_nhucau_HSKT_HB, 
                        'rpp_HSKT_NhuCau_DDHT' => $money_nhucau_HSKT_DDHT, 
                        'rpp_HSKT_Tong_NhuCau' => ($money_nhucau_HSKT_HB + $money_nhucau_HSKT_DDHT), 
                        'rpp_HSKT_DuToan_HB' => $money_dutoan_HSKT_HB, 
                        'rpp_HSKT_DuToan_DDHT' => $money_dutoan_HSKT_DDHT, 
                        'rpp_HSKT_Tong_DuToan' => ($money_dutoan_HSKT_HB + $money_dutoan_HSKT_DDHT), 
                        'rpp_HSDTTS_HTKP' => $DTHSDTTS, 
                        'rpp_HSDTTS_NhuCau' => $money_nhucau_HSDTS, 
                        'rpp_HSDTTS_DuToan' => $money_dutoan_HSDTS, 
                        'rpp_HTATHS_HTKP' => $DTHTATHS, 
                        'rpp_HTATHS_NhuCau' => $money_nhucau_HTATHS, 
                        'rpp_HTATHS_DuToan' => $money_dutoan_HTATHS, 
                        'rpp_HBHSDTNT_HTKP' => $DTHBHSDTNT, 
                        'rpp_HBHSDTNT_NhuCau' => $money_nhucau_HBHSDTNT, 
                        'rpp_HBHSDTNT_DuToan' => $money_dutoan_HBHSDTNT,                         
                        'rpp_Tong_Nhu_Cau' => $TONG_NHUCAU, 
                        'rpp_Tong_Du_Toan' => $TONG_DUTOAN, 
                        'trangthai' => 0,
                        'school_id' => $school_id,
                        'status' => 0
                        ]); 
            }else {
                echo 'update';
                // Update Tiền theo công văn của từng học sinh và cấp nhận
                    $insertTien = DB::table('qlhs_nhucau_dutoan')
                    ->where('id_profile',$profile_id)
                    ->where('namhoc',$year)
                    ->where('id',$this->type)
                    ->update([
                        'rpp_MGHP_100_1' => $DTMien100_1, 
                        'rpp_MGHP_100_2' => $DTMien100_2, 
                        'rpp_MGHP_100_3' => $DTMien100_3, 
                        'rpp_MGHP_100_4' => $DTMien100_4, 
                        'rpp_MGHP_100_5' => $DTMien100_5, 
                        'rpp_MGHP_100_6' => $DTMien100_6, 
                        'rpp_MGHP_70' => $DTMien70, 
                        'rpp_MGHP_50_1' => $DTMien50_1, 
                        'rpp_MGHP_50_2' => $DTMien50_2, 
                        'rpp_MGHP_NhuCau' => $money_nhucau_MGHP, 
                        'rpp_MGHP_DuToan' => $money_dutoan_MGHP, 
                        'rpp_CPHT_DT1' => $DTCPHT_1, 
                        'rpp_CPHT_DT2' => $DTCPHT_2, 
                        'rpp_CPHT_NhuCau' => $money_nhucau_CPHT, 
                        'rpp_CPHT_DuToan' => $money_dutoan_CPHT, 

                        'rpp_HTAT_DT1' => $DTHTAT_1, 
                        'rpp_HTAT_DT2' => $DTHTAT_2, 
                        'rpp_HTAT_DT3' => $DTHTAT_3, 
                        'rpp_HTAT_DT4' => $DTHTAT_4, 
                        'rpp_HTAT_NhuCau' => $money_nhucau_HTAT, 
                        'rpp_HTAT_DuToan' => $money_dutoan_HTAT, 
                        'rpp_HTBT_HTTA' => $DTHTBT_TA, 
                        'rpp_HTBT_HTTO' => $DTHTBT_TO, 
                        'rpp_HTBT_HTTA_HK2_old' => $HTBT_TA_HK2_OLD, 
                        'rpp_HTBT_HTTO_HK2_old' => $HTBT_TO_HK2_OLD, 
                        'rpp_HTBT_HTTA_HK1_cur' => $HTBT_TA_HK1_CUR, 
                        'rpp_HTBT_HTTO_HK1_cur' => $HTBT_TO_HK1_CUR, 
                        'rpp_HTBT_HTTA_HK2_cur' => $HTBT_TA_HK2_CUR, 
                        'rpp_HTBT_HTTO_HK2_cur' => $HTBT_TO_HK2_CUR, 
                        'rpp_HTBT_HTTA_HK1_new' => $HTBT_TA_HK1_NEW, 
                        'rpp_HTBT_HTTO_HK1_new' => $HTBT_TO_HK1_NEW, 
                        'rpp_HTBT_NhuCau_TA' => $money_nhucau_HTBT_TA, 
                        'rpp_HTBT_NhuCau_TO' => $money_nhucau_HTBT_TO, 
                        'rpp_HTBT_NhuCau_VHTT' => $money_nhucau_HTBT_VHTT, 
                        'rpp_HTBT_Tong_NhuCau' => ($money_nhucau_HTBT_TA + $money_nhucau_HTBT_TO + $money_nhucau_HTBT_VHTT), 
                        'rpp_HTBT_DuToan_TA' => $money_dutoan_HTBT_TA, 
                        'rpp_HTBT_DuToan_TO' => $money_dutoan_HTBT_TO, 
                        'rpp_HTBT_DuToan_VHTT' => $money_dutoan_HTBT_VHTT, 
                        'rpp_HTBT_Tong_DuToan' => ($money_dutoan_HTBT_TA + $money_dutoan_HTBT_TO + $money_dutoan_HTBT_VHTT), 
                        'rpp_HSKT_HTHB' => $DTHSKT_HB, 
                        'rpp_HSKT_HTDDHT' => $DTHSKT_DDHT, 
                        'rpp_HSKT_NhuCau_HB' => $money_nhucau_HSKT_HB, 
                        'rpp_HSKT_NhuCau_DDHT' => $money_nhucau_HSKT_DDHT, 
                        'rpp_HSKT_Tong_NhuCau' => ($money_nhucau_HSKT_HB + $money_nhucau_HSKT_DDHT), 
                        'rpp_HSKT_DuToan_HB' => $money_dutoan_HSKT_HB, 
                        'rpp_HSKT_DuToan_DDHT' => $money_dutoan_HSKT_DDHT, 
                        'rpp_HSKT_Tong_DuToan' => ($money_dutoan_HSKT_HB + $money_dutoan_HSKT_DDHT), 
                        'rpp_HSDTTS_HTKP' => $DTHSDTTS, 
                        'rpp_HSDTTS_NhuCau' => $money_nhucau_HSDTS, 
                        'rpp_HSDTTS_DuToan' => $money_dutoan_HSDTS, 
                        'rpp_HTATHS_HTKP' => $DTHTATHS, 
                        'rpp_HTATHS_NhuCau' => $money_nhucau_HTATHS, 
                        'rpp_HTATHS_DuToan' => $money_dutoan_HTATHS, 
                        'rpp_HBHSDTNT_HTKP' => $DTHBHSDTNT, 
                        'rpp_HBHSDTNT_NhuCau' => $money_nhucau_HBHSDTNT, 
                        'rpp_HBHSDTNT_DuToan' => $money_dutoan_HBHSDTNT,                         
                        'rpp_Tong_Nhu_Cau' => $TONG_NHUCAU, 
                        'rpp_Tong_Du_Toan' => $TONG_DUTOAN, 
                        'trangthai' => 0,
                        'status' => 0
                        ]);
            }
                    
        } catch (Exception $e) {
            echo "compilationProfile : ".$e;
        }
    }
}
