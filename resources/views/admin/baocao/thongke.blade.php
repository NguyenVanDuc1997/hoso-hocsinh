@extends('layouts.front')

@section('title', 'This is a blank page')
@section('description', 'This is a blank page that needs to be implemented')

@section('content')
<link rel="stylesheet" href="{!! asset('css/select2.min.css') !!}">
<link rel="stylesheet" href="{!! asset('dist/css/bootstrap-multiselect.css') !!}">
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<section class="content">
<script type="text/javascript">
$(function () {
    GET_INITIAL_NGHILC();
    loadStatisticYear();
    getExportHS = function(schools_id,type){
        GET_INITIAL_NGHILC();
        loadProfile(schools_id,type);
    }
    $('#sltSchool').change(function(){
      $('#sltStatistic').trigger('change');
    });

   $('select#sltQuanHuyen').change(function() {
        if($(this).val() == ""){
            $("#txtPhuong").val('');
            $("#txtThon").val('');
            $("#txtQuan").val('');
            $('select#sltPhuongXa').html('<option value="">--Chọn danh mục--</option>');
            $('select#sltPhuongXa').attr('disabled','disabled');
            $('select#sltThonXom').attr('disabled','disabled');
        }else{
          $("#txtPhuong").val('');
          $("#txtThon").val('');
          $("#txtQuan").val($(this).val());
          $('select#sltPhuongXa').selectpicker('refresh');
          loadComboxTinhThanh($(this).val(),'sltPhuongXa', function(){
            $('select#sltPhuongXa').removeAttr('disabled');
            $('select#sltPhuongXa').selectpicker('refresh');
            $('select#sltPhuongXa').focus();
            $('select#sltThonXom').attr('disabled','disabled');

          });
        }
        $('select#sltThonXom').html('<option value="">--Chọn danh mục--</option>');
        $('select#sltPhuongXa').selectpicker('refresh');
        $('select#sltThonXom').selectpicker('refresh');
        GET_INITIAL_NGHILC();
        loadStatisticSite();
        
    });
    $('select#sltPhuongXa').change(function() {
        if($(this).val() == ""){
          $("#txtQuan").val($('#sltQuanHuyen').val());
          $("#txtThon").val('');
          $("#txtPhuong").val('');
          $('select#sltThonXom').html('<option value="">--Chọn danh mục--</option>');
          $('select#sltThonXom').attr('disabled','disabled');
        }else{
          $("#txtQuan").val('');
          $("#txtThon").val('');
          $("#txtPhuong").val($(this).val());
          $('select#sltThonXom').selectpicker('refresh');
            loadComboxTinhThanh($(this).val(),'sltThonXom', function(){
              $('select#sltThonXom').removeAttr('disabled');
              $('select#sltThonXom').selectpicker('refresh');
              $('select#sltThonXom').focus();
          });
        }
        $('select#sltPhuongXa').selectpicker('refresh');
        $('select#sltThonXom').selectpicker('refresh');
        GET_INITIAL_NGHILC();
        loadStatisticSite();
        
    });

    $('select#sltThonXom').change(function() {
      if($(this).val() == ""){
        if($('#sltPhuongXa').val() == ""){
          $("#txtThon").val('');
          $("#txtQuan").val($('#sltQuanHuyen').val());
          $("#txtPhuong").val('');
        }else{
          $("#txtThon").val('');
          $("#txtQuan").val('');
          $("#txtPhuong").val($('#sltPhuongXa').val());
        }
        
      }else{
        $("#txtThon").val($(this).val());
        $("#txtQuan").val('');
        $("#txtPhuong").val('');  
      }
      GET_INITIAL_NGHILC();
      loadStatisticSite();
    });

  $('#view-tonghopdanhsach').change(function(){
      GET_INITIAL_NGHILC();
      if(parseInt($('#sltStatistic').val()) == 1){
        loadStatisticSite();
      }else if(parseInt($('#sltStatistic').val()) == 0){
        loadStatisticBySub();
      }else if(parseInt($('#sltStatistic').val()) == 2){
        loadStatisticYear();
      }else{
       // loadStatisticTotal();
      }
  });
  $('#sltYear').change(function(){
      GET_INITIAL_NGHILC();
      if(parseInt($('#sltStatistic').val()) == 1){
        loadStatisticSite();
      }else if(parseInt($('#sltStatistic').val()) == 0){
        loadStatisticBySub();
      }else if(parseInt($('#sltStatistic').val()) == 2){
        loadStatisticYear();
      }else{
       // loadStatisticTotal();
      }
  });
    $('#sltStatistic').change(function(){
        if(parseInt($(this).val()) == 1){
          $('#diaban').removeAttr('hidden');
        }else if(parseInt($(this).val()) == 0){
          if($('#sltSchool').val() == ""){
            utility.messagehide("warning_msg", 'Xin mời chọn trường', 1, 3000);
            return;
          }
          GET_INITIAL_NGHILC();
          loadStatisticBySub();
          $('#diaban').attr('hidden','hidden');
        }else{
          $('#diaban').attr('hidden','hidden');
          GET_INITIAL_NGHILC();
          loadStatisticYear();
        }
    });

});
function loadStatisticYear(){
  var html_header = "<tr class='success'>";
  html_header += "<th class='text-center' style='vertical-align:middle'>STT</th>";
  html_header += "<th class='text-center' style='vertical-align:middle'>Trường học</th>";
  html_header += "<th class='text-center' style='vertical-align:middle'>Năm học "+$('#sltYear').val()+"-"+(parseInt($('#sltYear').val())+1)+"</th>";
  html_header += "</tr>";
  $('#headerThongKe').html(html_header);
  $('#dataTongHopHoSo').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
  var o = {
      start: (GET_START_RECORD_NGHILC()),
      limit: $('#view-tonghopdanhsach').val(),
      truong_id : $('#sltSchool').val(),
      year: $('#sltYear').val()
    };
  PostToServer('/bao-cao/statistic-by-year',o,function(result){
        SETUP_PAGING_NGHILC(result, function () {
            loadStatisticYear();
        });
        data = result.data;
        var html_show = "";
        var html_header = "<tr class='success'>";
        if(data.length>0){
            for (var i = 0; i < data.length; i++) {
              html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * $('#view-tonghopdanhsach').val()))+"</td>";
              html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].schools_name)+"</td>";
              html_show += "<td class='text-center' style='vertical-align:middle'>"+formatNumber_2(data[i].Tong)+"</td></tr>";
          }
      }else{
           html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataTongHopHoSo').html(html_show);
  },function(result){
      console.log("loadStatisticTotal");
      console.log(result);
  },"btnViewThongKe","","");

}
function loadStatisticSite(){
  if($('#txtQuan').val() == "" && $('#txtPhuong').val() == "" && $('#txtThon').val() == ""){
    utility.message("Thông báo","Xin mời chọn địa bàn thống kê!",null,5000,1);
    $('select#sltQuanHuyen').focus();
    return;
  }
  var html_header = "<tr class='success'>";
  html_header += "<th class='text-center' style='vertical-align:middle'>STT</th>";
  html_header += "<th class='text-center' style='vertical-align:middle'>Trường học</th>";
  if($('#txtQuan').val() != ""){
    html_header += "<th class='text-center' style='vertical-align:middle'>Tổng học sinh theo quận/huyện</th>";  
  }
  if($('#txtPhuong').val() != ""){
    html_header += "<th class='text-center' style='vertical-align:middle'>Tổng học sinh theo phường/xã</th>";  
  }
  if($('#txtThon').val() != ""){
    html_header += "<th class='text-center' style='vertical-align:middle'>Tổng học sinh theo thôn/xóm</th>";  
  }
  
  html_header += "</tr>";
  $('#headerThongKe').html(html_header);
  $('#dataTongHopHoSo').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
  var o = {
      start: (GET_START_RECORD_NGHILC()),
      limit: $('#view-tonghopdanhsach').val(),
      truong_id : $('#sltSchool').val(),
      year : $('#sltYear').val(),
      Quan: $('#txtQuan').val(),
      Phuong: $('#txtPhuong').val(),
      Thon : $('#txtThon').val()
    };
  PostToServer('/bao-cao/statistic-by-site',o,function(result){
        SETUP_PAGING_NGHILC(result, function () {
            loadStatisticSite();
        });
        data = result.data;
        var html_show = "";
        var html_header = "<tr class='success'>";
        if(data.length>0){
            for (var i = 0; i < data.length; i++) {
              html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * $('#view-tonghopdanhsach').val()))+"</td>";
              html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].schools_name)+"</td>";
              html_show += "<td class='text-center' style='vertical-align:middle'>"+formatNumber_2(data[i].Tong)+"</td></tr>";
          }
      }else{
           html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataTongHopHoSo').html(html_show);
  },function(result){
      console.log("loadStatisticTotal");
      console.log(result);
  },"btnViewThongKe","","");
}
function loadStatisticTotal(){

  var html_header = "<tr class='success'>";
    html_header += "<th class='text-center' style='vertical-align:middle'>STT</th>";
    html_header += "<th class='text-center' style='vertical-align:middle'>Trường học</th>";
    html_header += "<th class='text-center' style='vertical-align:middle'>Số học sinh đang học</th>";
    html_header += "<th class='text-center' style='vertical-align:middle'>Số học sinh nghỉ học/ra trường</th>";
    html_header += "<th class='text-center' style='vertical-align:middle'>Tổng số học sinh</th>";
  
  html_header += "</tr>";
  $('#headerThongKe').html(html_header);
  $('#dataTongHopHoSo').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
  var o = {
      start: (GET_START_RECORD_NGHILC()),
      limit: $('#view-tonghopdanhsach').val(),
      truong_id : $('#sltSchool').val()
    };
  PostToServer('/bao-cao/statistic-by-school',o,function(result){
        SETUP_PAGING_NGHILC(result, function () {
            loadStatisticTotal();
        });
        data = result.data;
        var html_show = "";
        var html_header = "<tr class='success'>";
        if(data.length>0){
            for (var i = 0; i < data.length; i++) {
              html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * $('#view-tonghopdanhsach').val()))+"</td>";
              html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].schools_name)+"</td>";
              html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:void(0)' onclick='getExportHS("+data[i].schools_id+",0)'>"+formatNumber_2(data[i].HSDH)+"</a></td>";
              html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:void(0)' onclick='getExportHS("+data[i].schools_id+",1)'>"+formatNumber_2(data[i].HSNH)+"</a></td>";
              html_show += "<td class='text-center' style='vertical-align:middle'><a href='javascript:void(0)' onclick='getExportHS("+data[i].schools_id+",2)'>"+formatNumber_2(parseInt(data[i].HSDH)+parseInt(data[i].HSNH))+"</a></td></tr>";
          }
        }else{
           html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataTongHopHoSo').html(html_show);
  },function(result){
      console.log("loadStatisticTotal");
      console.log(result);
  },"btnViewThongKe","","");
}
function loadStatisticBySub(){
  var html_header = "<tr class='success'>";
    html_header += "<th class='text-center' style='vertical-align:middle'>STT</th>";
    html_header += "<th class='text-center' style='vertical-align:middle'>Tên chế độ</th>";
    html_header += "<th class='text-center' style='vertical-align:middle'>Tổng số học sinh</th>";
  
  html_header += "</tr>";
  $('#headerThongKe').html(html_header);
  $('#dataTongHopHoSo').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
    var o = {
      start: (GET_START_RECORD_NGHILC()),
      limit: $('#view-tonghopdanhsach').val(),
      year: $('#sltYear').val(),
      truong_id : $('#sltSchool').val()
    };
    PostToServer('/bao-cao/post-statistic',o,function(result){
        SETUP_PAGING_NGHILC(result, function () {
            loadStatisticBySub();
        });
        data = result.data;
        var html_show = "";
        if(data.length>0){
          for (var i = 0; i < data.length; i++) {
            html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * $('#view-tonghopdanhsach').val()))+"</td>";
             html_show += "<td style='vertical-align:middle'>"+data[i].group_name+"</td>";
             html_show += "<td class='text-center' style='vertical-align:middle'>"+data[i].tong +"</td></tr>";
          }
          
        }else{
           html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataTongHopHoSo').html(html_show);
    },function(result){
      console.log("btnViewThongKe");
      console.log(result);
    },"btnViewThongKe","","");
}
function loadComboxTinhThanh(id,idselect,callback,idchoise=null) {
    GetFromServer('/danh-muc/load/city/'+id,function(dataget){
         $('#'+idselect).html("");
                    var html_show = "";
                    if(dataget.length > 0){
                      //if(id===0){
                            html_show += "<option value=''>-- Chọn danh mục --</option>";
                       //}
                        for (var i = 0; i < dataget.length; i++) {
                            if(parseInt(idchoise)===parseInt(dataget[i].site_id)){
                                html_show += "<option value='"+dataget[i].site_id+"' selected>"+dataget[i].site_name+"</option>";    
                            }else{
                                html_show += "<option value='"+dataget[i].site_id+"'>"+dataget[i].site_name+"</option>";
                            }
                        }
                        $('#'+idselect).html(html_show);
                    //   size = true;
                    }
                    else{
                        $('#'+idselect).html("<option value=''>-- Chưa có danh mục --</option>");
                        //size = false;
                    }

                    if(callback != null){
                        callback();
                    }
    },function(dataget){
      console.log("loadComboxTinhThanh-ThongKe");
      console.log(dataget);
    },"","","");

        };
function loadComboxDoiTuong(callback=null) {
    GetFromServer('/danh-muc/load/doi-tuong?type=0',function(dataget){
        $('#sltDoituongs').html("");
        var html_show = "";
        if(dataget.length >0){
            for (var i = dataget.length - 1; i >= 0; i--) {
                html_show += "<option value='"+dataget[i].subject_id+"'>"+dataget[i].subject_name+"</option>";
            }
            $('#sltDoituongs').html(html_show);
        }else{
            $('#sltDoituongs').html("<option value=''>-- Chưa có đối tượng --</option>");
        }
        if(callback!=null){
           callback();
        }
    },function(dataget){
        console.log("loadComboxDoiTuong-ThongKe");
        console.log(dataget);
    },"","","");
};
function loadProfile(schools_id,type){
  var html_show = "";
        var o = {
            start: (GET_START_RECORD_NGHILC()),
            limit : $('#view-tonghopdanhsach').val(),
            id_truong: schools_id,
            type: type,
           // key: keysearch
        };
        $('#dataProfile').html("<tr><td colspan='50' class='text-center' style='vertical-align:middle'>Đang tải dữ liệu</td></tr>");
            PostToServer('/bao-cao/loadprofile',o,function(dataget){
                    SETUP_PAGING_NGHILC(dataget, function () {
                        loadProfile(schools_id,type);
                    });
                    var leaveDate = "";
                    $('#dataProfile').html("");
                    data = dataget.data;
                    if(data.length>0){
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].profile_status == 1) {
                                leaveDate = data[i].profile_leaveschool_date;
                            }else {
                                leaveDate = "";
                            }
                            html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * $('#view-tonghopdanhsach').val()))+"</td>";
                            html_show += "<td style='vertical-align:middle'><a style='cursor:pointer' onclick='viewHistory("+data[i].profile_id+")'>"+ConvertString(data[i].profile_name)+"</a></td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>"+formatDates(data[i].profile_birthday)+"</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertString(data[i].nationals_name)+"</td>";
                            html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].profile_household)+" - "+ConvertString(data[i].phuong)+" - "+ConvertString(data[i].huyen)+"</td>";
                            html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].profile_guardian)+"</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertString(data[i].class_name)+"</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertString(data[i].history_year)+"</td>";
                            html_show += "<td class='text-center' style='vertical-align:middle'>"+formatMonth(data[i].profile_year)+"</td>";
                            html_show += "</tr>";
                        }
                        
                    } else {
                        html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
                    }
                    $('#dataTongHopHoSo').html(html_show);
        },function(dataget){
            console.log("loadHocSinh");
            console.log(dataget);
        },"","","");
}
</script>



<div class="panel panel-default">
    <div class="panel-heading">
       <a href="/ho-so/lap-danh-sach/list"><b> Báo cáo</b></a> / Thống kê học sinh
    </div>
</div>
    <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <form id="formmiengiamhocphi">
            <div class="row" id="warning_msg" style="padding-left: 10%;padding-right: 10%"></div>
              <div class="box-body">
<div class="form-group" >
                  <div class="col-sm-4">
                      <label  class="col-sm-12 control-label">Trường </label>
                      <div class="col-sm-8">
                        <select name="sltSchool" id="sltSchool" class="form-control selectpicker" @if(count(explode('-',Auth::user()->truong_id)) > 1 ) data-live-search="true" @endif>
                         <?php 
                          if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                            $qlhs_schools = DB::table('qlhs_schools')->where('schools_active',1)->whereIn('schools_id',explode('-',Auth::user()->truong_id))->select('schools_id','schools_name')->get();
                            if(count(explode('-',Auth::user()->truong_id)) > 1){
                              ?>
                                <option value="" selected="selected">-- Chọn trường học --</option>
                              <?php
                            }
                          }else{
                            $qlhs_schools = DB::table('qlhs_schools')->where('schools_active',1)->select('schools_id','schools_name')->get();
                          ?>
                          <option value="" selected="selected">-- Chọn trường học --</option>
                            <?php
                         }
                         ?>
                         
                         @foreach($qlhs_schools as $val)
                            <option value="{{$val->schools_id}}" @if($val->schools_id == Auth::user()->truong_id) selected @endif>{{$val->schools_name}}</option>
                         @endforeach
                       </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <label  class="col-sm-12 control-label">Năm học</label>
                      <div class="col-sm-8">
                         <select name='sltYear' class="selectpicker form-control" id='sltYear'>
                          <?php 
                              $namhoc = DB::table('qlhs_years')->orderBy('code','asc')->get();
                              $str_date = Carbon\Carbon::now()->format('Y')-1;
                          ?>
                          @foreach($namhoc as $nam)
                            <option  value="{{$nam->code}}" @if(trim($nam->code) == trim($str_date)) selected @endif>{{$nam->name}}</option>

                          @endforeach
                          
                         </select>
                      </div>
                    </div>
                     <div class="col-sm-4">
                      <label  class="col-sm-12 control-label">Loại thống kê</label>
                      <div class="col-sm-8">
                        <select name='sltStatistic' class="form-control selectpicker" id='sltStatistic'>
                          <!-- <option value="" selected="selected">Thống kê học sinh</option> -->
                          <option value="2" selected>Theo năm học</option>
                          <option value="0" >Theo chế độ</option>
                          <option value="1">Theo địa bàn</option>
                          
                         </select>
                      </div>
                    </div></div>
            <div class="form-group" hidden="hidden" id="diaban">
                  <div class="col-sm-4">
                      <label  class="col-sm-12 control-label">Quận/huyện</label>
                      <div class="col-sm-8">
                        <select name='sltQuanHuyen' class="form-control selectpicker" id='sltQuanHuyen' data-live-search="true">
                          <?php 
                          $qlhs_site = DB::table('qlhs_site')->where('site_active','=',1)->where('site_level',2)->where('site_parent_id',95)->select('site_id','site_name')->get();
                         ?>
                         <option value="">--Chọn quận/huyện--</option>
                         @foreach($qlhs_site as $val)
                            <option value="{{$val->site_id}}">{{$val->site_name}}</option>
                         @endforeach
                        </select>
                        <input type="hidden" name="txtQuan" id="txtQuan" value="">
                      </div>
                    </div>
                     <div class="col-sm-4">
                      <label  class="col-sm-12 control-label">Phường/xã</label>
                      <div class="col-sm-8">
                        <select name='sltPhuongXa' disabled="disabled" class="form-control selectpicker" id='sltPhuongXa' data-live-search="true">
                          <option value="">--Chọn danh mục--</option>
                         </select>
                         <input type="hidden" name="txtPhuong" id="txtPhuong" value="">
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <label  class="col-sm-12 control-label">Thôn/xóm</label>
                      <div class="col-sm-8">
                        <select name='sltThonXom' class="form-control selectpicker" id='sltThonXom' disabled="disabled" data-live-search="true">
                            <option value="">--Chọn danh mục--</option>
                         </select>
                         <input type="hidden" name="txtThon" id="txtThon" value="">
                      </div>
                    </div>
                </div>
                 
              </div>
            </form>
          </div>

            <div class="box box-primary">

                <div class="box-body" style="font-size:12px">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead id="headerThongKe">
                         
                      </thead>
                        <tbody id="dataTongHopHoSo">
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                      <div class="row">
                          <div class="col-md-2">
                              <label class="text-right col-md-9 control-label">Tổng </label>
                              <label class="col-md-3 control-label g_countRowsPaging">0</label>
                          </div>
                          <div class="col-md-3">
                              <label class="col-md-6 control-label text-right">Trang </label>
                              <div class="col-md-4">
                                  <select class="form-control input-sm g_selectPaging">
                                      <option value="0">0 / 20 </option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-3">
                                        <label  class="col-md-6 control-label text-right">Hiển thị: </label>
                                        <div class="col-md-3">
                                          <select name="view-tonghopdanhsach" id="view-tonghopdanhsach"  class="selectpicker form-control input-sm pagination-show-row">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="50">50</option>
                                          </select>
                                        </div>
                                      </div>
                          <div class="col-md-4">
                          <label  class="col-md-2 control-label"></label>
                          <div class="col-md-10">
                              <ul class="pagination pagination-sm no-margin pull-right g_clickedPaging">
                                  <li><a>&laquo;</a></li>
                                  <li><a>0</a></li>
                                  <li><a>&raquo;</a></li>
                              </ul>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>       
            </div>
          
</div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

@endsection