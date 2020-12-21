<?php 
namespace App\Helpers;
use Auth,DB;
use Exception;
use App\Jobs\compilationProfile;
use Carbon\Carbon;
use App\Models\qlhs_tonghopchedo;
use App\Models\TongHopCheDo_Truong;
use App\Models\TongHopCheDo_TrangThai;
use App\Models\LichSuHSHocSinh;

class tongHopChedo{

	 public static function tonghop($year, $profileId, $schoolid, $thcd_ID, $end_year=0){
        $user = Auth::user()->id;

        
        try {
            $status = 0;
            if($end_year == 0){
                // Lấy ID tổng hợp
                $getProfile = TongHopCheDo_Truong::where('thcd_nhucau_profile_id',$profileId)
                    ->where('thcd_nhucau_school_id',Auth::user()->truong_id)
                    ->where('thcd_nhucau_nam',$year)
                    ->first();
                if ($getProfile != null) {
                    $getProfile->update_profile = 1;
                    $getProfile->save();
                    dispatch(new compilationProfile($year, $profileId, $schoolid,$getProfile->thcd_nhucau_id, $end_year,$user,1));
                   return "Xử lý thành công";

                }else {
                    // Nếu không tồn tại bản ghi tổng hợp thì thêm mới
                    $insert = new TongHopCheDo_Truong();
                    $insert->thcd_nhucau_profile_id = $profileId;
                    $insert->thcd_nhucau_school_id = $schoolid;
                    $insert->thcd_nhucau_nam = $year;
                    $insert->update_profile = 1;
                    $insert->thcd_nhucau_usercreate_id = Auth::user()->id;
                    $insert->save();
                    // Trạng thái bản ghi tổng hợp học sinh
                    $update_THCD_Status = TongHopCheDo_TrangThai::where('thcd_trangthai_profile_id',$profileId)
                    ->where('thcd_trangthai_school_id',Auth::user()->truong_id)
                    ->where('thcd_trangthai_year',$year)->first();
                    if($update_THCD_Status == null){
                        $insert_status = new TongHopCheDo_Truong();
                        $insert_status->thcd_trangthai_profile_id = $profileId;
                        $insert_status->thcd_trangthai_school_id = $schoolid;
                        $insert_status->thcd_trangthai_year = $year;
                        $insert_status->thcd_trangthai_user_id = Auth::user()->id;
                        $insert_status->save();
                    }
//start
                    // $delProfile = DB::table('qlhs_tonghopchedo')
                    //     ->where('qlhs_thcd_profile_id',$profileId)
                    //     ->where('qlhs_thcd_school_id',$schoolid)
                    //     ->where('qlhs_thcd_nam',$year)
                    //     ->delete();

                    // $status = DB::table('qlhs_tonghopchedo')
                    // ->insert([
                    //     'qlhs_thcd_tien_nhucau_MGHP' => 0, 
                    //     'qlhs_thcd_tien_nhucau_CPHT' => 0, 
                    //     'qlhs_thcd_tien_nhucau_HTAT' => 0, 
                    //     'qlhs_thcd_tien_nhucau_HTBT_TA' => 0, 
                    //     'qlhs_thcd_tien_nhucau_HTBT_TO' => 0, 
                    //     'qlhs_thcd_tien_nhucau_HTBT_VHTT' => 0, 
                    //     'qlhs_thcd_tien_nhucau_HSDTTS' => 0, 
                    //     'qlhs_thcd_tien_nhucau_HSKT_HB' => 0, 
                    //     'qlhs_thcd_tien_nhucau_HSKT_DDHT' => 0,
                    //     'qlhs_thcd_tien_nhucau_HTATHS' => 0,
                    //     'qlhs_thcd_tien_nhucau_HBHSDTNT' => 0,

                    //     'qlhs_thcd_tien_nhucau_MGHP_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_CPHT_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_HTAT_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_HTBT_TA_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_HTBT_TO_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_HTBT_VHTT_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_HSDTTS_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_HSKT_HB_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_HSKT_DDHT_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_HTATHS_HK2' => 0,
                    //     'qlhs_thcd_tien_nhucau_HBHSDTNT_HK2' => 0,

                    //     'qlhs_thcd_tongtien_nhucau' => 0,
                    //     'qlhs_thcd_tongtien_nhucau_HK2' => 0,

                    //     'qlhs_thcd_trangthai' => 1,
                    //     'qlhs_thcd_trangthai_HK2' => 1,
                    //     'qlhs_thcd_trangthai_PD' => 0,
                    //     'qlhs_thcd_trangthai_PD_HK2' => 0,
                    //     'qlhs_thcd_trangthai_TD' => 0,
                    //     'qlhs_thcd_trangthai_TD_HK2' => 0,

                    //     'qlhs_thcd_profile_id' => $profileId,
                    //     'qlhs_thcd_school_id' => $schoolid,
                    //     'qlhs_thcd_nam' => $year,

                    //     'qlhs_thcd_trangthai_MGHP' => 1,
                    //     'qlhs_thcd_trangthai_CPHT' => 1,
                    //     'qlhs_thcd_trangthai_HTAT' => 1,
                    //     'qlhs_thcd_trangthai_HTBT_TA' => 1,
                    //     'qlhs_thcd_trangthai_HTBT_TO' => 1,
                    //     'qlhs_thcd_trangthai_HTBT_VHTT' => 1,
                    //     'qlhs_thcd_trangthai_HSKT_HB' => 1,
                    //     'qlhs_thcd_trangthai_HSKT_DDHT' => 1,
                    //     'qlhs_thcd_trangthai_HSDTTS' => 1,
                    //     'qlhs_thcd_trangthai_HTATHS' => 1,
                    //     'qlhs_thcd_trangthai_HBHSDTNT' => 1,

                    //     'PheDuyet_trangthai_MGHP' => 0,
                    //     'PheDuyet_trangthai_CPHT' => 0,
                    //     'PheDuyet_trangthai_HTAT' => 0,
                    //     'PheDuyet_trangthai_HTBT_TA' => 0,
                    //     'PheDuyet_trangthai_HTBT_TO' => 0,
                    //     'PheDuyet_trangthai_HTBT_VHTT' => 0,
                    //     'PheDuyet_trangthai_HSKT_HB' => 0,
                    //     'PheDuyet_trangthai_HSKT_DDHT' => 0,
                    //     'PheDuyet_trangthai_HSDTTS' => 0,
                    //     'PheDuyet_trangthai_HTATHS' => 0,
                    //     'PheDuyet_trangthai_HBHSDTNT' => 0,

                    //     'ThamDinh_trangthai_MGHP' => 0,
                    //     'ThamDinh_trangthai_CPHT' => 0,
                    //     'ThamDinh_trangthai_HTAT' => 0,
                    //     'ThamDinh_trangthai_HTBT_TA' => 0,
                    //     'ThamDinh_trangthai_HTBT_TO' => 0,
                    //     'ThamDinh_trangthai_HTBT_VHTT' => 0,
                    //     'ThamDinh_trangthai_HSKT_HB' => 0,
                    //     'ThamDinh_trangthai_HSKT_DDHT' => 0,
                    //     'ThamDinh_trangthai_HSDTTS' => 0,
                    //     'ThamDinh_trangthai_HTATHS' => 0,
                    //     'ThamDinh_trangthai_HBHSDTNT' => 0,

                    //     'qlhs_thcd_usercreate_id' => $user,

                    //     'qlhs_thcd_trangthai_MGHP_HK2' => 1,
                    //     'qlhs_thcd_trangthai_CPHT_HK2' => 1,
                    //     'qlhs_thcd_trangthai_HTAT_HK2' => 1,
                    //     'qlhs_thcd_trangthai_HTBT_TA_HK2' => 1,
                    //     'qlhs_thcd_trangthai_HTBT_TO_HK2' => 1,
                    //     'qlhs_thcd_trangthai_HTBT_VHTT_HK2' => 1,
                    //     'qlhs_thcd_trangthai_HSKT_HB_HK2' => 1,
                    //     'qlhs_thcd_trangthai_HSKT_DDHT_HK2' => 1,
                    //     'qlhs_thcd_trangthai_HSDTTS_HK2' => 1,
                    //     'qlhs_thcd_trangthai_HTATHS_HK2' => 1,
                    //     'qlhs_thcd_trangthai_HBHSDTNT_HK2' => 1,

                    //     'PheDuyet_trangthai_MGHP_HK2' => 0,
                    //     'PheDuyet_trangthai_CPHT_HK2' => 0,
                    //     'PheDuyet_trangthai_HTAT_HK2' => 0,
                    //     'PheDuyet_trangthai_HTBT_TA_HK2' => 0,
                    //     'PheDuyet_trangthai_HTBT_TO_HK2' => 0,
                    //     'PheDuyet_trangthai_HTBT_VHTT_HK2' => 0,
                    //     'PheDuyet_trangthai_HSKT_HB_HK2' => 0,
                    //     'PheDuyet_trangthai_HSKT_DDHT_HK2' => 0,
                    //     'PheDuyet_trangthai_HSDTTS_HK2' => 0,
                    //     'PheDuyet_trangthai_HTATHS_HK2' => 0,
                    //     'PheDuyet_trangthai_HBHSDTNT_HK2' => 0,

                    //     'ThamDinh_trangthai_MGHP_HK2' => 0,
                    //     'ThamDinh_trangthai_CPHT_HK2' => 0,
                    //     'ThamDinh_trangthai_HTAT_HK2' => 0,
                    //     'ThamDinh_trangthai_HTBT_TA_HK2' => 0,
                    //     'ThamDinh_trangthai_HTBT_TO_HK2' => 0,
                    //     'ThamDinh_trangthai_HTBT_VHTT_HK2' => 0,
                    //     'ThamDinh_trangthai_HSKT_HB_HK2' => 0,
                    //     'ThamDinh_trangthai_HSKT_DDHT_HK2' => 0,
                    //     'ThamDinh_trangthai_HSDTTS_HK2' => 0,
                    //     'ThamDinh_trangthai_HTATHS_HK2' => 0,
                    //     'ThamDinh_trangthai_HBHSDTNT_HK2' => 0,
                    //     'update_profile' => 1,
                    //     'created_at' => Carbon::now(),
                    //     'updated_at' => Carbon::now()
                    // ]);

                    
                    

                    // $insertStatus = DB::table('qlhs_hosobaocao_trangthai_Truong')
                    //             ->where('rppst_profile_id',$profileId)
                    //             ->where('rppst_year',$year)
                    //             ->where('rppst_school_id',$schoolid)->delete();
                    // $insertStatus = DB::table('qlhs_hosobaocao_trangthai_Truong')->insert([
                    //             'rppst_profile_id' => $profileId,
                    //             'rppst_year' => $year,
                    //             'rppst_school_id' => $schoolid,
                    //             'rppst_Status_MGHP' => 1,
                    //             'rppst_Status_CPHT' => 1,
                    //             'rppst_Status_HTAT' => 1,
                    //             'rppst_Status_HTBT_TA' => 1,
                    //             'rppst_Status_HTBT_TO' => 1,
                    //             'rppst_Status_HTBT_VHTT' => 1,
                    //             'rppst_Status_HSKT_HB' => 1,
                    //             'rppst_Status_HSKT_DDHT' => 1,
                    //             'rppst_Status_HSDTTS' => 1,
                    //             'rppst_Status_HTATHS' => 1,
                    //             'rppst_Status_HBHSDTNT' => 1,
                    //             'rppst_Status_HK1' => 1,
                    //             'rppst_Status_MGHP_HK2' => 1,
                    //             'rppst_Status_CPHT_HK2' => 1,
                    //             'rppst_Status_HTAT_HK2' => 1,
                    //             'rppst_Status_HTBT_TA_HK2' => 1,
                    //             'rppst_Status_HTBT_TO_HK2' => 1,
                    //             'rppst_Status_HTBT_VHTT_HK2' => 1,
                    //             'rppst_Status_HSKT_HB_HK2' => 1,
                    //             'rppst_Status_HSKT_DDHT_HK2' => 1,
                    //             'rppst_Status_HSDTTS_HK2' => 1,
                    //             'rppst_Status_HTATHS_HK2' => 1,
                    //             'rppst_Status_HBHSDTNT_HK2' => 1,
                    //             'rppst_Status_HK2' => 1,
                    //             'rppst_updated_user_id' => $user,
                    //             'rppst_updated_date' => Carbon::now()
                    //             ]);    
//end
                    dispatch(new compilationProfile($year, $profileId, $schoolid, $insert->thcd_nhucau_id, $end_year,$user,2));  
                    return "Thành công";
                }
            }else{

                $check = LichSuHSHocSinh::where('history_profile_id',$profileId)
                ->where('history_year',$year.'-'.($year+1))->count();  
                if($check > 0){
                    $getProfile = TongHopCheDo_Truong::where('thcd_nhucau_profile_id',$profileId)
                    ->where('thcd_nhucau_school_id',Auth::user()->truong_id)
                    ->where('thcd_nhucau_nam',$year)
                    ->first();
                    if($getProfile != null){
                        $insert = new TongHopCheDo_Truong();
                        $insert->thcd_nhucau_profile_id = $profileId;
                        $insert->thcd_nhucau_school_id = $schoolid;
                        $insert->thcd_nhucau_nam = $year;
                        $insert->update_profile = 1;
                        $insert->thcd_nhucau_usercreate_id = Auth::user()->id;
                        $insert->save();
                    }
//start                        
                    // $statuss = qlhs_tonghopchedo::where('qlhs_thcd_profile_id',$profileId)
                    // ->where('qlhs_thcd_nam', $year)
                    // ->first();
                    // if(count($statuss)==0){
                    //     $statuss = new qlhs_tonghopchedo();
                    //         $statuss->qlhs_thcd_nam = $year;
                    //         $statuss->qlhs_thcd_profile_id = $profileId;
                    //         $statuss->qlhs_thcd_school_id = $schoolid;

                    //         $statuss->qlhs_thcd_trangthai = 1;
                    //         $statuss->qlhs_thcd_trangthai_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_PD = 0;
                    //         $statuss->qlhs_thcd_trangthai_PD_HK2 = 0;
                    //         $statuss->qlhs_thcd_trangthai_TD = 0;
                    //         $statuss->qlhs_thcd_trangthai_TD_HK2 = 0;
                    //         $statuss->qlhs_thcd_trangthai_MGHP = 1;
                    //         $statuss->qlhs_thcd_trangthai_CPHT = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTAT = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_TA = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_TO = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_VHTT = 1;
                    //         $statuss->qlhs_thcd_trangthai_HSKT_HB = 1;
                    //         $statuss->qlhs_thcd_trangthai_HSKT_DDHT = 1;
                    //         $statuss->qlhs_thcd_trangthai_HSDTTS = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTATHS = 1;
                    //         $statuss->qlhs_thcd_trangthai_HBHSDTNT = 1;
                    //         $statuss->qlhs_thcd_trangthai_MGHP_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_CPHT_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTAT_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_TA_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_TO_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_VHTT_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HSKT_HB_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HSKT_DDHT_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HSDTTS_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTATHS_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HBHSDTNT_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_TA_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_TA_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_TA_HK2 = 1;
                    //         $statuss->qlhs_thcd_trangthai_HTBT_TA_HK2 = 1;

                    //         $statuss->PheDuyet_trangthai_MGHP = 0;
                    //         $statuss->PheDuyet_trangthai_CPHT = 0;
                    //         $statuss->PheDuyet_trangthai_HTAT = 0;
                    //         $statuss->PheDuyet_trangthai_HTBT_TA = 0;
                    //         $statuss->PheDuyet_trangthai_HTBT_TO = 0;
                    //         $statuss->PheDuyet_trangthai_HTBT_VHTT = 0;
                    //         $statuss->PheDuyet_trangthai_HSKT_HB = 0;
                    //         $statuss->PheDuyet_trangthai_HSKT_DDHT = 0;
                    //         $statuss->PheDuyet_trangthai_HSDTTS = 0;
                    //         $statuss->PheDuyet_trangthai_HTATHS = 0;
                    //         $statuss->PheDuyet_trangthai_HBHSDTNT = 0;
                    //         $statuss->ThamDinh_trangthai_MGHP = 0;
                    //         $statuss->ThamDinh_trangthai_CPHT = 0;
                    //         $statuss->ThamDinh_trangthai_HTAT = 0;
                    //         $statuss->ThamDinh_trangthai_HTBT_TA = 0;
                    //         $statuss->ThamDinh_trangthai_HTBT_TO = 0;
                    //         $statuss->ThamDinh_trangthai_HTBT_VHTT = 0;
                    //         $statuss->ThamDinh_trangthai_HSKT_HB = 0;
                    //         $statuss->ThamDinh_trangthai_HSKT_DDHT = 0;
                    //         $statuss->ThamDinh_trangthai_HSDTTS = 0;
                    //         $statuss->ThamDinh_trangthai_HTATHS = 0;
                    //         $statuss->ThamDinh_trangthai_HBHSDTNT = 0;
                    //         $statuss->PheDuyet_trangthai_MGHP_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_CPHT_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_HTAT_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_HTBT_TA_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_HTBT_TO_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_HTBT_VHTT_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_HSKT_HB_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_HSKT_DDHT_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_HSDTTS_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_HTATHS_HK2 = 0;
                    //         $statuss->PheDuyet_trangthai_HBHSDTNT_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_MGHP_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_CPHT_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_HTAT_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_HTBT_TA_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_HTBT_TO_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_HTBT_VHTT_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_HSKT_HB_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_HSKT_DDHT_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_HSDTTS_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_HTATHS_HK2 = 0;
                    //         $statuss->ThamDinh_trangthai_HBHSDTNT_HK2 = 0;                         
                    //         $statuss->update_profile = 1;
                    //         $statuss->save();  
                    // }
//end
                    dispatch(new compilationProfile($year, $profileId, $schoolid,$insert->thcd_nhucau_id, $end_year,$user,3));  
                }
                    return 1;
            }
            return $status;
        } catch (Exception $e) {
            return $e;
        }

    }
    // year: năm học cần tổng hợp
    // profile: học sinh cần tổng hợp
    // trường của học sinh
    // thcd_ID : trường hợp cần cập nhật
    public static function tongHop_Truong($year, $profileId, $schoolid, $thcd_ID=null){
        $user = Auth::user()->id;
        try{
            if($thcd_ID == null){ // Trường hợp không phải cập nhật
                // Kiêm tra bản ghi đã tổn tại hay chưa
                $getProfile = TongHopCheDo_Truong::where('thcd_nhucau_profile_id',$profileId)
                            ->where('thcd_nhucau_school_id',Auth::user()->truong_id)
                            ->where('thcd_nhucau_nam',$year)
                            ->first();
                if ($getProfile != null) {// Nếu đã tồn tại thì cập nhật tổng hợp
                    $getProfile->update_profile = 0;
                    $getProfile->save();
                    // Xử lý

                    dispatch(new compilationProfile($year, $profileId, $schoolid,$getProfile->thcd_nhucau_id,$user));
                    return "Xử lý thành công";
                } else {// Nếu không tồn tại bản ghi thì thêm mới với giá trị tiền bằng = 0
                    // thêm mới
                    $insert = new TongHopCheDo_Truong();
                    $insert->thcd_nhucau_profile_id = $profileId;
                    $insert->thcd_nhucau_school_id = $schoolid;
                    $insert->thcd_nhucau_nam = $year;
                    $insert->update_profile = 0;
                    $insert->thcd_nhucau_usercreate_id = Auth::user()->id;
                    $insert->save();
                    // trạng thái  tổng hợp
                    $update_THCD_Status = TongHopCheDo_TrangThai::where('thcd_trangthai_profile_id',$profileId)
                        ->where('thcd_trangthai_school_id',Auth::user()->truong_id)
                        ->where('thcd_trangthai_year',$year)->first();
                    if($update_THCD_Status == null){// Chưa có thì thêm mới
                        $insert_status = new TongHopCheDo_TrangThai();
                        $insert_status->thcd_trangthai_profile_id = $profileId;
                        $insert_status->thcd_trangthai_school_id = $schoolid;
                        $insert_status->thcd_trangthai_year = $year;
                        $insert_status->thcd_trangthai_user_id = Auth::user()->id;
                        $insert_status->save();
                    }
                    dispatch(new compilationProfile($year, $profileId, $schoolid,$insert->thcd_nhucau_id,$user));  
                    return "Thành công";
                }
            }else{
                return "loi k co id";
            }
        }catch (Illuminate\Database\QueryException $e){
            return "Loi";
        }

    }   

}