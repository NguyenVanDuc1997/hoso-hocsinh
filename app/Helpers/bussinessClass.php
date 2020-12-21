<?php 
namespace App\Helpers;
use Auth,DB;
use Exception;
use App\Jobs\compilationProfile;
use Carbon\Carbon;

class bussinessClass{

	public static function autoCode($type,$school_id){
		$number = 0;

        $auto_Reportname = '';
        if($type == 1){//Trường
        	$auto_Reportname = 'BC.T'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 2){// Phòng giáo dục
        	$auto_Reportname = 'BC.PGD'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 3){// Phòng tài chính
        	$auto_Reportname = 'BC.PTC'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 4){// Phòng tài chính
            $auto_Reportname = 'BC.STC'.$school_id.'.'.Carbon::now()->format('dmy');
        }
		$getReportNum = DB::table('qlhs_hosobaocao')
                ->where('report_value', $auto_Reportname)
                //->where('report_id_truong',  $school_id)
                ->where('report_cap_gui',$type)//Cấp trường
                ->select(DB::raw('MAX(number) as number'))->get();

        if($getReportNum[0]->number == null){
            $number = 1;
        }else{
            $number = (int)$getReportNum[0]->number + 1;
        }
        return $auto_Reportname.'.'.$number;
	}
	public static function autoCodeFirst($type,$school_id){
        $auto_Reportname = '';
        if($type == 1){//Trường
        	$auto_Reportname = 'BC.T'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 2){// Phòng giáo dục
        	$auto_Reportname = 'BC.PGD'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 3){// Phòng tài chính
        	$auto_Reportname = 'BC.PTC'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 4){// Phòng tài chính
            $auto_Reportname = 'BC.STC'.$school_id.'.'.Carbon::now()->format('dmy');
        }
        return $auto_Reportname;
	}
	public static function autoCodeNumber($type,$school_id){
        $number = 0;

        $auto_Reportname = '';
        if($type == 1){//Trường
        	$auto_Reportname = 'BC.T'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 2){// Phòng giáo dục
        	$auto_Reportname = 'BC.PGD'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 3){// Phòng tài chính
        	$auto_Reportname = 'BC.PTC'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 4){// Phòng tài chính
            $auto_Reportname = 'BC.STC'.$school_id.'.'.Carbon::now()->format('dmy');
        }
		$getReportNum = DB::table('qlhs_hosobaocao')
                ->where('report_value', $auto_Reportname)
                //->where('report_id_truong',  $school_id)
                ->where('report_cap_gui',$type)//Cấp trường
                ->select(DB::raw('MAX(number) as number'))->get();

        if($getReportNum[0]->number == null){
            $number = 1;
        }else{
            $number = (int)$getReportNum[0]->number + 1;
        }
        return $number;
	}
	//
	public static function autoCodeCallback($type,$school_id){
		$number = 0;

        $auto_Reportname = '';
        if($type == 1){//Trường
        	$auto_Reportname = 'TB.T'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 2){// Phòng giáo dục
        	$auto_Reportname = 'TB.PGD'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 3){// Phòng tài chính
        	$auto_Reportname = 'TB.PTC'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 4){// Phòng tài chính
        	$auto_Reportname = 'TB.STC'.$school_id.'.'.Carbon::now()->format('dmy');
        }
		$getReportNum = DB::table('qlhs_hosobaocao')
                ->where('report_value', $auto_Reportname)
                //->where('report_id_truong',  $school_id)
                ->where('report_cap_gui',$type)//Cấp trường
                ->select(DB::raw('MAX(number) as number'))->get();

        if($getReportNum[0]->number == null){
            $number = 1;
        }else{
            $number = (int)$getReportNum[0]->number + 1;
        }
        return $auto_Reportname.'.'.$number;
	}
	public static function autoCodeFirstCallback($type,$school_id){
        $auto_Reportname = '';
        if($type == 1){//Trường
        	$auto_Reportname = 'TB.T'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 2){// Phòng giáo dục
        	$auto_Reportname = 'TB.PGD'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 3){// Phòng tài chính
        	$auto_Reportname = 'TB.PTC'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 4){// Phòng tài chính
        	$auto_Reportname = 'TB.STC'.$school_id.'.'.Carbon::now()->format('dmy');
        }
        return $auto_Reportname;
	}
	public static function autoCodeNumberCallback($type,$school_id){
        $number = 0;

        $auto_Reportname = '';
        if($type == 1){//Trường
        	$auto_Reportname = 'TB.T'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 2){// Phòng giáo dục
        	$auto_Reportname = 'TB.PGD'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 3){// Phòng tài chính
        	$auto_Reportname = 'TB.PTC'.$school_id.'.'.Carbon::now()->format('dmy');
        }else if($type == 4){// Phòng tài chính
        	$auto_Reportname = 'TB.STC'.$school_id.'.'.Carbon::now()->format('dmy');
        }
		$getReportNum = DB::table('qlhs_hosobaocao')
                ->where('report_value', $auto_Reportname)
                //->where('report_id_truong',  $school_id)
                ->where('report_cap_gui',$type)//Cấp trường
                ->select(DB::raw('MAX(number) as number'))->get();

        if($getReportNum[0]->number == null){
            $number = 1;
        }else{
            $number = (int)$getReportNum[0]->number + 1;
        }
        return $number;
	}

    public static function to_slug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/', 'A', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/', 'E', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(Ì|Í|Ị|Ỉ|Ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/', 'O', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/', 'U', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/', 'Y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/(Đ)/', 'D', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}