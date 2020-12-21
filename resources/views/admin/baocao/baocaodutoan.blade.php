@extends('layouts.front')

@section('content')
<link rel="stylesheet" href="{!! asset('dist/css/bootstrap-multiselect.css') !!}">
<link rel="stylesheet" href="{!! asset('css/select2.min.css') !!}">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="{!! asset('bootstrap/css/bootstrap.min.css') !!}"> 

<link rel="stylesheet" href="{!! asset('css/toastr.css') !!}">

<script src="{!! asset('/plugins/jQuery/jquery-2.2.3.min.js') !!}"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<!-- datepicker -->
<script src="{!! asset('/plugins/datepicker/bootstrap-datepicker.js') !!}"></script>
<script src="{!! asset('js/select2.min.js') !!}"></script>
<script src="{!! asset('js/toastr.js') !!}"></script>
<script src="{!! asset('js/utility.js') !!}"></script>
<script src="{!! asset('/mystyle/js/styleProfile.js') !!}"></script>

<section class="content">
<script type="text/javascript">
  $(function () {
    loaddutoan($('#sltYear').val(),$('#sltTruong').val());
    $('#btnprn').click(function(){
      
        if($('#sltYear').val() == ""){
            utility.message("Thông báo","Xin mời chọn năm dự toán!",null,5000,1);
            $('select#sltYear').focus();
            return;
        }
        $('#btnprn').button('loading');
          var o = {
            school : $('#sltTruong').val(),
            year : $('#sltYear').val(),
            tentruong : $('#sltTruong option:selected').text()
        };
        $.ajax('/bao-cao/du-toan-view', {
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
          method: 'POST',
          methodType: 'HTML',
          data: o,
          success: function (data) { 
            var left = (screen.width) / 4;
            var top = (screen.height) / 8;  // for 25% - devide by 4  |  for 33% - devide by 3
            //newWin= window.open();
            newWin= window.open("/",'name','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=1, resizable=no, copyhistory=no, width='+(screen.width)+', height='+(screen.height)+', top=' + top + ', left=' + left);
            newWin.document.write(data);
            newWin.print();
            newWin.close();
            $('#btnprn').button('reset');
          },error: function (val) {
              $('#btnprn').button('reset');
          }
      });
    })
    $('#sltYear').change(function(){
        html_view = "";
        var _value = $(this).val();
        if(_value != ""){
          loaddutoan(_value,$('#sltTruong').val());
        }
    });
    $('#sltTruong').change(function(){
        html_view = "";
        var _value = $(this).val();
        if(_value != ""){
          loaddutoan($('#sltYear').val(),$('#sltTruong').val());
        }
    });

    permission(function(){
      var html_view  = '';
      var html_view_header  = '<b>Quản lý báo cáo</b> / Báo cáo dự toán';
        html_view += '<button type="button" onclick="" class="btn btn-success" id ="btnViewDanhSachTruongLap"><i class="glyphicon glyphicon-search"></i> Xem danh sách </button>';
      if(check_Permission_Feature('5')){
        
        // html_view += '<button type="button" onclick="loaddataDanhSachGroupB('+$('#drPagingDanhsach').val()+')" class="btn btn-success" id =""><i class="glyphicon glyphicon-search"></i> Xem danh sách học sinh mới nhập học </button>';
        // html_view += '<button type="button" onclick="loaddataDanhSachGroupC('+$('#drPagingDanhsach').val()+')" class="btn btn-success" id =""><i class="glyphicon glyphicon-search"></i> Xem danh sách học sinh dự kiến tuyển </button>';
      }
          
      if(check_Permission_Feature('1')){
        // html_view_header += '<a onclick="openModalAdd()" style="margin-left: 10px" class="btn btn-success btn-xs pull-right"> <i class="glyphicon glyphicon-plus"></i> Tạo mới </a >';
            
        // html_view += '<button type="button" onclick="openPopupLapTHCD()" class="btn btn-success" id =""><i class="glyphicon glyphicon-pushpin"></i> Lập danh sách </button>';

        // html_view += '<button type="button" onclick="loaddataBaocaoTongHop(10)" class="btn btn-success" id =""><i class="glyphicon glyphicon-search"></i> Xem danh sách</button>';
      }
      if(check_Permission_Feature('4')){
          html_view_header += '<a onclick="exportExcelTruongDeNghi()" class="btn btn-success btn-xs pull-right" href="#"> <i class="glyphicon glyphicon-print"></i> Xuất excel </a>';
      }
      $('#addnew-export-profile').html(html_view_header);
      // $('#event-thcd').html(html_view);
    }, 91);
  }); 

  function loaddutoan(year,school){
      GetFromServer('/du-toan-chi-tra/dutoan/load?year='+year+'&school='+school,function(dataget){
        html_header = "";//Detail
        html_body = "";
        html_header_detail = "";//
        html_body_detail = "";
        html_header_detail += "<tr class='success'>";
        html_header_detail += "<th class='text-center' rowspan='2'>Thời điểm</th>";
        html_header_detail += "<th class='text-center' colspan='11'>Hỗ trợ</th>";
        html_header_detail += "<th class='text-center' rowspan='2'>Tổng</th>";
        html_header_detail += "</tr>";

        html_header_detail += "<tr class='success'>";
        html_header_detail += "<th class='text-center'>MGHP</th>";
        html_header_detail += "<th class='text-center'>CPHT</th>";
        html_header_detail += "<th class='text-center'>AT TEMG</th>";
        html_header_detail += "<th class='text-center'>BT TA</th>";
        html_header_detail += "<th class='text-center'>BT TO</th>";
        html_header_detail += "<th class='text-center'>BT VHTT</th>";
        html_header_detail += "<th class='text-center'>TA HS</th>";
        html_header_detail += "<th class='text-center'>HSKT HB</th>";
        html_header_detail += "<th class='text-center'>HSKT DDHT</th>";
        html_header_detail += "<th class='text-center'>HSDTNT</th>";
        html_header_detail += "<th class='text-center'>HSDTTS</th>";
        html_header_detail += "</tr>";
        if(parseInt(dataget.tongso) > 0){
            html_header += "<tr class='success'>";
            html_header += "<th class='text-center'>Học sinh dự kiến lên lớp</th>";
            html_header += "<th class='text-center'>Học sinh dự kiến tuyển mới</th>";
            html_header += "<th class='text-center'>Tổng học sinh dự kiến</th>";
            html_header += "</tr>";

            html_body += "<tr>";
            html_body += "<td class='text-center'><b>"+parseInt(dataget.lenlop)+"</b></td>";
            html_body += "<td class='text-center'><b>"+(parseInt(dataget.tongso) - parseInt(dataget.lenlop))+"</b></td>";
            html_body += "<td class='text-center'><b>"+parseInt(dataget.tongso)+"</b></td>";
            html_body += "</tr>";
        
            if(dataget.dutoan_1 != null && dataget.dutoan_2 != null){
                
                var dt_1 = dataget.dutoan_1;
                html_body_detail += "<tr>";
                html_body_detail += "<td class='text-center'>Từ 01/"+year+" đến 05/"+year+"</td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.MGHP)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.CPHT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.HTAT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.HTBT_TA)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.HTBT_TO)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.HTBT_VHTT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.HTATHS)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.HSKT_HB)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.HSKT_DDHT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.HBHSDTNT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.HSDTTS)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_1.TONG)+"</b></td>";
                html_body_detail += "</tr>";

                var dt_2 = dataget.dutoan_2;
                html_body_detail += "<tr>";
                
                html_body_detail += "<td class='text-center'>Từ 09/"+year+" đến 12/"+year+"</td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.MGHP)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.CPHT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.HTAT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.HTBT_TA)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.HTBT_TO)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.HTBT_VHTT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.HTATHS)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.HSKT_HB)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.HSKT_DDHT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.HBHSDTNT)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.HSDTTS)+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(dt_2.TONG)+"</b></td>";
                html_body_detail += "</tr>";

                html_body_detail += "<tr>";
                
                html_body_detail += "<td class='text-center'>Dự toán</td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.MGHP)+parseInt(dt_2.MGHP))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.CPHT)+parseInt(dt_2.CPHT))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.HTAT)+parseInt(dt_2.HTAT))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.HTBT_TA)+parseInt(dt_2.HTBT_TA))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.HTBT_TO)+parseInt(dt_2.HTBT_TO))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.HTBT_VHTT)+parseInt(dt_2.HTBT_VHTT))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.HTATHS)+parseInt(dt_2.HTATHS))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.HSKT_HB)+parseInt(dt_2.HSKT_HB))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.HSKT_DDHT)+parseInt(dt_2.HSKT_DDHT))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.HBHSDTNT)+parseInt(dt_2.HBHSDTNT))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.HSDTTS)+parseInt(dt_2.HSDTTS))+"</b></td>";
                html_body_detail += "<td class='text-right'><b>"+formatter(parseInt(dt_1.TONG)+parseInt(dt_2.TONG))+"</b></td>";
                html_body_detail += "</tr>";
            }
          }else{
            html_body_detail += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
          }
        $('#dataHeaderDT').html(html_header);
        $('#dataBodyDT').html(html_body);
        $('#dataHeaderDetailDT').html(html_header_detail);
        $('#dataBodyDetailDT').html(html_body_detail);
        console.log(dataget);
         
      },function(dataget){

      },"","","");
  }
</script>



<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-profile">
       <!-- <a href="/ho-so/lap-danh-sach/list"><b> Hồ sơ </b></a> / Danh sách hỗ trợ -->
    </div>
</div>
    <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <form id="formdutoan">
            <div class="row" id="messageValidate" style="padding-left: 10%;padding-right: 10%"></div> 
              <div class="box-body">

                <div class="form-group">
                  <div class="col-sm-4">
                    <label  class="col-sm-4 control-label text-right">Trường<font style="color: red">*</font></label>
                    <div class="col-sm-8">
                      <select name="sltTruong" id="sltTruong"  class="form-control selectpicker"  @if(count(explode('-',Auth::user()->truong_id)) > 1) data-live-search="true" @endif style="width: 100% !important">
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
                      <input type="hidden" name="typeCV" id="typeCV" value="0">
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <label  class="col-sm-4 control-label text-right">Năm dự toán<font style="color: red">*</font></label>
                    <div class="col-sm-8">
                        <select name='sltYear' class="form-control selectpicker"  data-live-search="true" id='sltYear'>
                            <?php 
                              $getYear = DB::table('qlhs_years')->select('name','code')->get();
                              $year = Carbon\Carbon::now()->format('Y');
                              if(Carbon\Carbon::now()->format('m') >= 1 && Carbon\Carbon::now()->format('m') <= 8){
                                $year = $year + 1;
                              }
                              
                            ?>
                            @foreach($getYear as $val)
                                  <option value="{{$val->code}}" @if($year == $val->code) selected="selected" @endif>{{$val->code}}</option>

                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                      <button id="btnprn" type="button" class="btn btn-info" data-loading-text="Đang tải dữ liệu" ><i class="fa fa-print" aria-hidden="true"></i> In</button>
                  </div>
                </div>
             
                </div>
            </form>
          </div>

            <div class="box box-primary">

                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                      <thead id="dataHeaderDetailDT">
                 
                      </thead>
                        <tbody id="dataBodyDetailDT">                     
                        </tbody>
                    </table>
                  
                </div>       
            </div>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

@endsection