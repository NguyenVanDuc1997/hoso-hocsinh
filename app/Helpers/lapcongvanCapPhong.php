<?php 
namespace App\Helpers;
use Auth,DB;
use Exception;
use App\Jobs\compilationProfile;
use App\Helpers\bussinessClass;
use Carbon\Carbon;

class lapcongvanCapPhong{

    public static function insertTien_modify($report_name, $levelUser, $time, $current_user_id, $current_date, $chedoMGHP, $chedoCPHT, $chedoHTAT, $chedoHTBT, $chedoHSKT, $chedoHSDTTS, $chedoHTATHS, $chedoHBHSDTNT, $reportType,$autoCode,$value_type)
    {
        try {
        	   $report_status = $reportType;

               echo "Thuc hien ==> ";
            	DB::statement("call insert_money_by_auth('".$report_name."',".$levelUser.",".$chedoMGHP.",".$chedoCPHT.",".$chedoHTAT.",".$chedoHTBT.",".$chedoHSKT.",".$chedoHSDTTS.",".$chedoHTATHS.",".$chedoHBHSDTNT.",'".$autoCode."',".$reportType.")");
                $getDataHS = null;
                if ($levelUser == 2) {
                    if($reportType == 1){
                        $getDataHS = DB::table('qlhs_baocaokinhphi_PTC');
                    }else{
                        $getDataHS = DB::table('qlhs_tralaikinhphi_PGD');
                    }
                           
                }else if($levelUser == 3){
                    if($reportType == 1){
                        $getDataHS = DB::table('qlhs_baocaokinhphi_SGD');
                    }else{
                        $getDataHS = DB::table('qlhs_tralaikinhphi_PTC');
                    }                        
                }else if($levelUser == 4){
                    if($reportType == 1){
                        $getDataHS = DB::table('qlhs_baocaokinhphi_SGD');
                    }else{
                        $getDataHS = DB::table('qlhs_tralaikinhphi_SGD');
                    }       
                }

                $getDataHS = $getDataHS->where('bckp_name', $autoCode)
                ->select('bckp_profile_id','bckp_danhsach_chedo','bckp_nhucau_mghp','bckp_nhucau_cpht','bckp_nhucau_htat','bckp_nhucau_htbt_to','bckp_nhucau_htbt_ta','bckp_nhucau_htbt_vhtt','bckp_nhucau_htkt_hb','bckp_nhucau_htkt_ddht','bckp_nhucau_hsdtts','bckp_nhucau_htaths','bckp_nhucau_hbhsdtnt')->get();
                $soHS = count($getDataHS);
                
                if (!is_null($getDataHS) && !empty($getDataHS) && $soHS > 0) {
                    DB::beginTransaction();
                    foreach ($getDataHS as $values) {
                        $group_sub_name = '';
                        $arrValue = [];
                        $arrValue = explode(';', $values->bckp_danhsach_chedo);
                        foreach ($arrValue as $item) {
                            $arrItem = [];
                            $arrItem = explode('|', $item);
                            $itemSub = $arrItem[0];
                            if (($itemSub == 89 || $itemSub == 90 || $itemSub == 91) && $chedoMGHP > 0 && $values->bckp_nhucau_mghp > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 92 && $chedoCPHT > 0 && $values->bckp_nhucau_cpht > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 93 && $chedoHTAT > 0 && $values->bckp_nhucau_htat > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 94 && $chedoHTBT > 0 && $values->bckp_nhucau_htbt_ta > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 98 && $chedoHTBT > 0 && $values->bckp_nhucau_htbt_to > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 115 && $chedoHTBT > 0 && $values->bckp_nhucau_htbt_vhtt > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 95 && $chedoHSKT > 0 && $values->bckp_nhucau_htkt_hb > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 100 && $chedoHSKT > 0 && $values->bckp_nhucau_htkt_ddht > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 99 && $chedoHSDTTS > 0 && $values->bckp_nhucau_hsdtts > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 118 && $chedoHTATHS > 0 && $values->bckp_nhucau_htaths > 0) {
                                $group_sub_name .= $item.';';
                            }else if ($itemSub == 119 && $chedoHBHSDTNT > 0 && $values->bckp_nhucau_hbhsdtnt > 0) {
                                $group_sub_name .= $item.';';
                            }
                        }
                        $check = null;
                        if($report_status == 2){
                            if ($levelUser == 2) {
                                $check = DB::table('qlhs_tralaikinhphi_PGD');
                            }else if ($levelUser == 3) {
                                $check = DB::table('qlhs_tralaikinhphi_PTC');
                            }else if ($levelUser == 4) {
                                $check = DB::table('qlhs_tralaikinhphi_SGD');
                            }
                        }else{        
                            if ($levelUser == 2) {
                                $check = DB::table('qlhs_baocaokinhphi_PTC');
                            }else if ($levelUser == 3) {
                                $check = DB::table('qlhs_baocaokinhphi_SGD');
                            }
                        }
                        $check->where('bckp_name',$autoCode)
                            ->where('bckp_profile_id',$values->bckp_profile_id)
                            ->update([
                                'bckp_danhsach_chedo' => $group_sub_name
                            ]);
                    }  
                    DB::commit(); 
                }
            }catch (Exception $e) {
                DB::rollBack();
                return $e;
            }
    }
}