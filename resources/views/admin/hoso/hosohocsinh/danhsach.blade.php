@extends('layouts.front')
@section('content')
<!-- <link rel="stylesheet" href="{!! asset('css/select2.min.css') !!}"> -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="{!! asset('/dist/css/check-radio.css') !!}"> -->
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="{!! asset('bootstrap/css/bootstrap.min.css') !!}">
<link rel="stylesheet" href="{!! asset('css/toastr.css') !!}">
<script src="{!! asset('/plugins/jQuery/jquery-2.2.3.min.js') !!}"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<!-- datepicker -->
<script src="{!! asset('/plugins/datepicker/bootstrap-datepicker.js') !!}"></script>
<!-- <script src="{!! asset('js/select2.js') !!}"></script> -->
<script src="{!! asset('js/toastr.js') !!}"></script>
<script src="{!! asset('js/utility.js') !!}"></script>
<script src="{!! asset('/mystyle/js/styleProfile.js') !!}"></script>
<section class="content">
   <script type="text/javascript">
      $(function () {
        onFocus = function(classs){
          $("td#checkboxfocus_"+classs).addClass('focuscheckboxactive');
        };
        onBlur = function(classs){
          $("td#checkboxfocus_"+classs).removeClass('focuscheckboxactive');
        };
        validBirthday = function(val){
          if($(val).val() != ""){
              var key = replaceAll(($(val).val()+""),'/','-');
              var ngay='',thang='',nam='';
              if(key.split('-').length > 2){
                  ngay = key.split('-')[0];
                  thang = key.split('-')[1];
                  nam = key.split('-')[2];

                    if(ngay.length == 1){
                      ngay = '0'+ngay;
                    }
                    if(thang.length == 1){
                      thang = '0'+thang;
                    }
                    if(nam.length == 2){
                      nam = '20'+nam;
                    }
                    key = ngay+'-'+thang+'-'+nam;
                  var check = moment(key, 'DD-MM-YYYY',true).isValid();
                  if(check){
                    if(parseInt(moment().format('YYYY')) < parseInt(nam)){
                        utility.messagehide('group_message',"Năm sinh không được lớn hơn năm hiện tại!",0,5000);
                    }else{
                        $(val).val(key);
                    }

                  }else{
                    $("#txtBirthday").focus();
                    utility.messagehide('group_message',"Ngày tháng không đúng định dạng!",1,5000);
                  }
              }else{
                  $("#txtBirthday").focus();
                  utility.messagehide('group_message',"Ngày tháng không đúng định dạng!",1,5000);
              }

          }
        };
        validYearProfile = function(val){
          if($(val).val() != ""){
              var key = replaceAll(($(val).val()+""),'/','-');
              var check = false;
              var ngay='',thang='',nam='';

              if(key.split('-').length > 2){
                ngay = key.split('-')[0];
                thang = key.split('-')[1];
                nam = key.split('-')[2];
                if(ngay.length == 1){
                  ngay = '0'+ngay;
                }
                if(thang.length == 1){
                  thang = '0'+thang;
                }
                if(nam.length == 2){
                  nam = '20'+nam;
                }
                key = ngay+'-'+thang+'-'+nam;
                check = moment(key, 'DD-MM-YYYY',true).isValid();

                if(check){

                  if(parseInt(moment().format('YYYY')) < parseInt(nam)){
                    utility.messagehide('group_message',"Năm nhập học lớn hơn năm hiện tại!",0,5000);
                  }
                  $(val).val(key.split('-')[1]+"-"+key.split('-')[2]);
                }else{
                  $("#txtYearProfile").focus();
                  utility.messagehide('group_message',"Không đúng định dạng ngày-tháng-năm!",1,5000);
                }
              }else{
                thang = key.split('-')[0];
                nam = key.split('-')[1];
                if(thang.length == 1){
                  thang = '0'+thang;
                }
                if(nam.length == 2){
                  nam = '20'+nam;
                }
                key = thang+'-'+nam;
                check = moment("01-"+key, 'DD-MM-YYYY',true).isValid();
                if(check){
                  if(parseInt(moment().format('YYYY')) < parseInt(nam)){
                    utility.messagehide('group_message',"Năm nhập học lớn hơn năm hiện tại!",0,5000);
                  }
                  $(val).val(key);
                }else{
                  $("#txtYearProfile").focus();
                  utility.messagehide('group_message',"Không đúng định dạng ngày-tháng-năm!",1,5000);
                }
              }

          }
        }
        $('#btnCloseMessage').click(function(){
            $('#tbMessage').attr('hidden','hidden');
            $('.chuho_id').remove();
        });
        $('#ckbHSNghiHoc').change(function(){
            loadTableUpto();
        });
        $("#uploadexcel").filestyle({
              buttonText : ' Chọn file',
              buttonName : 'btn-info'
            });
        $('#btnImportHS').click(function(){
          if(($('#school-per').val()+'').split('-').length > 1 || parseInt($('#school-per').val()) === 0){
            utility.message('Thông báo','Tài khoản không được quyền sử dụng!',null,3000,1);
          }else{
              var file_data = $('input#uploadexcel').prop('files')[0];
              if(file_data == null || file_data == undefined){
                  utility.message("Thông báo", "Xin mời chọn file dữ liệu.", null,5000,1);
              }else{
                var form_datas = new FormData();
                form_datas.append('FILE', file_data);
              }
              uploadHoSoHocSinh(form_datas);
          }

        });


      //  autocompleteGiamHo();
        permission(function(){
          var html_view  = '';
        if(check_Permission_Feature('3')){
         // html_view += '<a onclick="delProfileByClass()" style="margin-right: 10px" class="btn btn-success btn-xs pull-right" href="#"> <i class="glyphicon glyphicon-remove"></i> Xóa  </a>';
        }

          if(check_Permission_Feature('4')){
              html_view += '<a onclick="exportExcelProfile()" style="margin-right: 10px" class="btn btn-success btn-xs pull-right" href="#"> <i class="glyphicon glyphicon-print"></i> Xuất excel </a>';
	      const searchParams = new URLSearchParams(window.location.search);
              if(searchParams.has('import_student')) {
               html_view += '<a onclick="importExcelProfile()" style="margin-right: 10px" class="btn btn-success btn-xs pull-right" href="#"> <i class="glyphicon glyphicon-print"></i> Nhập excel </a>';
              }
          }


          if(check_Permission_Feature('1')){
            html_view += '<a onclick="openModalUpto()" style="margin-right: 10px" class="btn btn-success btn-xs pull-right"> <i class="glyphicon glyphicon-plus"></i> Chức năng </a >';
            html_view += '<a href="/ho-so/hoc-sinh/listing" style="margin-right: 10px" class="btn btn-success btn-xs pull-right"> <i class="glyphicon glyphicon-plus"></i> Thêm mới </a >';
            // html_view += '<a onclick="openModalAdd()" style="margin-left: 10px" class="btn btn-success btn-xs pull-right"> <i class="glyphicon glyphicon-plus"></i> Tạo mới </a >';

          }
          $('#addnew-export-profile').html(html_view);
        }, 8);

        if(parseInt($('#school-per').val()) != 0 && ($('#school-per').val()+'').split('-').length == 1){
                loadComboxLop($('#school-per').val(),'sltLopGrid',function(){
                    $('select#sltLopGrid').removeAttr('disabled');
                    $('select#sltLopGrid').selectpicker('refresh');

                });
        }
        GET_INITIAL_NGHILC();
        loaddataProfile($('select#viewTableProfile').val(),$('#sltTruongGrid').val(),$('select#sltLopGrid').val(), $('#txtSearchProfile').val());


        $('#ckbNQ57').on('keydown',function(e){
          e.preventDefault();
          var self = $(this);
          $.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase());
          if($.browser.chrome){
            if(e.keyCode == 32){
                // if(self.is(":checked")){
                //   self.prop('checked', false);
                // }else{
                //   self.prop('checked', true);
                // }
                if(self.val() != 1){
                  self.attr("checked",true);
                  self.val(1);
                  $('.badge').css("text-indent","0");
                }else{
                  self.prop('checked', false);
                  self.val(0);
                  $('.badge').css("text-indent","-999999px");
                }
            }
          }

        });
        $('#ckbNQ57').on('click',function(e){
          e.preventDefault();
          var self = $(this);
          if(self.val() != 1){
                  self.attr("checked",true);
                  self.val(1);
                  $('.badge').css("text-indent","0");
          }else{
                  self.prop('checked', false);
                  self.val(0);
                  $('.badge').css("text-indent","-999999px");
          }

        });
        autocompleteSearch("txtSearchProfile");
      });

      var close = true;
      function viewMoreProfile(){
        if(close){
          $('#tableMoreProfile').removeAttr('hidden');
          close = false;
        }else{
          $('#tableMoreProfile').attr('hidden','hidden');
          close = true;
        }
      }

      function openModalUpdate(){
        $('#saveProfile').html("Cập nhật");
        $('.modal-title').html('Cập nhật hồ sơ học sinh năm học <b>'+$('#sltNamHoc').val()+'</b>');
        $('#nextChangeSubject').html('Thay đổi đối tượng');
        $('#nextSite').html('Thay đổi hộ khẩu');

        $("#myModalProfile").modal("show");
      }
       function openModalUpto(){
          loadComboxLop($('#drSchoolUpto').val(),'drClassUpto',function(){
              $('#drClassUpto').removeAttr('disabled');
              $('#drClassUpto').selectpicker('refresh');
          });
          $('#drClassNext').val('').selectpicker('refresh');

            $('#dateOutProfile').hide();
            $('#StlClassNext').selectpicker('hide');
            $('#drClassBack').selectpicker('hide');
          var t =  $('#uptoClass').DataTable().clear().draw().destroy();
          $('#upClass-select-all').prop('checked', false);
          //loading();

          $("#myModalUpto").modal("show");
       }

        function openModalHistory(){
        $("#myHistory").modal("show");
       }
       importExcelProfile = function(){
      $("#uploadexcel").filestyle('clear');
      $('#myModalImport').modal('show');
        };
   </script>
   <div class="modal fade" id="myModalImport" role="dialog">
      <div class="modal-dialog modal-md" style="width: 60%;margin: 30px auto;">
         <!-- Modal content-->
         <div class="modal-content box">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title" id="title_email">Nhập liệu học sinh</h4>
            </div>
            <form class="form-horizontal" action="">
               <input type="hidden" class="form-control" id="txtIdRoleGroup">
               <div class="modal-body">
                  <div class="row" id="changepass_message" style="padding-left: 10%;padding-right: 10%"></div>
                  <div class="box-body">
                     <input type="hidden" class="form-control" id="txtid" >
                     <div class="form-group">
                        <div class="col-sm-12">
                           <label  class="col-sm-2 control-label">Nhập dữ liệu</label>
                           <div class="col-sm-5">
                              <input type="file" class="form-control bn_upload_pro" id="uploadexcel" >
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="col-sm-12">
                           <div class="col-sm-2"></div>
                           <div class="col-sm-8">
                              <span>- Tải file mẫu nhập danh sách học sinh </span> <a href="javascript:void(0)" onclick="downloadDemo()"> Tại đây</a><br/>
                              <span>- Danh sách lớp, dân tộc :<font style="color: red;font-weight: 600"> Yêu cầu nhập đúng như trong mẫu và tối đa 50 học sinh một file </font></span><br/>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <div class="row text-center">
                     <button type="button" data-loading-text="Đang tải dữ liệu.Xin mời đợi" class="btn btn-primary" id ="btnImportHS">Nhập dữ liệu</button>
                     <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="modal fade" id="myHistory" role="dialog">
      <div class="modal-dialog modal-md" style="width: 80%;margin: 30px auto;">
         <!-- Modal content-->
         <div class="modal-content box">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title-up-to">Lịch sử học sinh </h4>
            </div>
            <br>
            <div id="" style="margin-left: 5px;margin-right: 5px;">
               <div class="box-body" style="font-size: 12px">
                  <table id="HistoryTable" class="table table-striped table-bordered table-hover dataTable no-footer">
                     <thead>
                        <tr class="success">
                           <th class="text-center" style="vertical-align:middle">STT</th>
                           <th class="text-center" style="vertical-align:middle">Lớp học</th>
                           <th class="text-center" style="vertical-align:middle">Năm học</th>
                           <th class="text-center" style="vertical-align:middle">Trạng thái</th>
                           <th class="text-center" style="vertical-align:middle">Bắt đầu</th>
                           <th class="text-center" style="vertical-align:middle">Kết thúc</th>
                        </tr>
                     </thead>
                     <tbody id="contentPopupUpto">
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="modal-footer">
               <div class="row text-center">
                  <button type="button" class="btn btn-primary" id="btnClosePopupUpto" data-dismiss="modal">Đóng</button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="modal fade" id="myModalUpto" role="dialog">
      <div class="modal-dialog modal-md" style="width: 90%;margin: 30px auto;">
         <!-- Modal content-->
         <div class="modal-content box">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title-up-to">Chức năng lên lớp - học lại - nghỉ học</h4>
            </div>
            <form class="form-horizontal" action="">
               <div class="modal-body" style="font-size: 12px;padding: 5px;">
                  <div class="row" id="message_Upto" style="padding-left: 10%;padding-right: 10%">
                     <div id="messageDanger"></div>
                  </div>
                  <div class="box-body">
                     <div class="form-group">
                        <div class="col-sm-3" style="padding-left: 0">
                           <label  class="col-sm-12 ">Trường học <font style="color: red">*</font></label>
                           <div class="col-sm-12">
                              <select name="drSchoolUpto" id="drSchoolUpto" class="form-control selectpicker" @if(count(explode('-',Auth::user()->truong_id)) > 1) data-live-search="true" @endif style="width: 100% !important">
                              <?php
                                 if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                                   $qlhs_schools = DB::table('qlhs_schools')->where('schools_active','=',1)->whereIn('schools_id',explode('-',Auth::user()->truong_id))->select('schools_id','schools_name')->get();
                                   if(count(explode('-',Auth::user()->truong_id)) > 1) {
                                     ?>
                              <option value="" selected="selected">-- Chọn trường học --</option>
                              <?php
                                 }
                                 }else{
                                 $qlhs_schools = DB::table('qlhs_schools')->where('schools_active','=',1)->select('schools_id','schools_name')->get();
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
                        <div class="col-sm-2" style="padding-left: 0">
                           <label  class="col-sm-12">Lớp học hiện tại <font style="color: red">*</font></label>
                           <div class="col-sm-12" >
                              <select name="drClassUpto" disabled="disabled" id="drClassUpto" class="form-control selectpicker" data-live-search="true" style="width: 100% !important">
                                 <!-- <option value="">--Chọn lớp--</option> -->
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-2" style="padding-left: 0">
                           <label  class="col-sm-12">Năm học <font style="color: red">*</font></label>
                           <div class="col-sm-12" >
                              <select name="drYearUpto" disabled="disabled" id="drYearUpto"  class="form-control selectpicker" data-live-search="true" >
                              <?php
                                 $history_year = DB::table('qlhs_profile_history')
                                 ->select('history_year')
                                 ->groupBy('history_year')
                                 ->orderBy('history_year')->get();
                                 $dt = Carbon\Carbon::now()->format('Y');
                                 if(Carbon\Carbon::now()->format('m') >= 1 && Carbon\Carbon::now()->format('m') <= 8){
                                   $str_date = ((int)$dt-1)."-".$dt;
                                 }else {
                                   $str_date = $dt."-".((int)$dt+1);
                                 }
                                 ?>

                              @foreach($history_year as $val)
                                @if(trim($val->history_year) == trim($str_date))
                                  <option value="{{$val->history_year}}" selected="selected">{{$val->history_year}}</option>
                                @else
                                  <option value="{{$val->history_year}}">{{$val->history_year}}</option>
                               @endif

                              @endforeach
                              </select>
                              <input type="hidden" name="timenow" id="timenow" value="{{trim($str_date)}}">
                           </div>
                        </div>
                        <div class="col-sm-2" style="padding-left: 0">
                           <label  class="col-sm-12 ">Chức năng <font style="color: red">*</font></label>
                           <div class="col-sm-12" >
                              <select name="drClassNext" disabled="disabled" id="drClassNext" class="form-control selectpicker" data-live-search="true" style="width: 100% !important">
                                 <option value="" selected="selected">-- Chức năng --</option>
                                 <option value="1">-- Lên lớp  --</option>
                                 <option value="2">-- Nghỉ học --</option>
                                 <option value="3">-- Học lại  --</option>
                                 <option value="4">-- Chuyển lớp  --</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-2" style="padding-left: 0">
                           <label id="labelClassBack" hidden="hidden" class="col-sm-12 ">Lớp học lại - chuyển lớp<font style="color: red">*</font></label>
                           <div class="col-sm-12" >
                              <select name="drClassBack" hidden="hidden" disabled="disabled" id="drClassBack" class=" form-control selectpicker" data-live-search="true" style="width: 100% !important">
                              </select>
                           </div>
                           <label id="labelClassNext" hidden="hidden" class="col-sm-12 ">Lên lớp <font style="color: red">*</font></label>
                           <div class="col-sm-12" >
                              <select name="StlClassNext" hidden="hidden"  disabled="disabled" id="StlClassNext" class=" form-control selectpicker" data-live-search="true" style="width: 100% !important">
                              </select>
                           </div>
                           <label id="labelOutProfile" hidden="hidden" class="col-sm-12 ">Ngày nghỉ học </label>
                           <div class="col-sm-12" >
                              <input type="text"  name="dateOutProfile" disabled="disabled" placeholder="ngày-tháng-năm"  id="dateOutProfile" class="form-control" hidden="hidden">
                           </div>
                           <label id="labelChangeProfile" hidden="hidden" class="col-sm-12 ">Tháng chuyển lớp học </label>
                           <div class="col-sm-12" >
                              <input type="text"  name="dateChangeProfile" disabled="disabled" placeholder="tháng-năm"  id="dateChangeProfile" class="form-control" style="display:none;">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <div class="row text-center">
                     <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnUpto">Thực hiện</button>
                     <!--            <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnRevert">Hoàn tác</button> -->
                     <button type="button" class="btn btn-primary" id="btnClosePopupUpto" data-dismiss="modal">Đóng</button>
                  </div>
               </div>
            </form>
            <br>
            <div class="box-body">
               <div class="col-sm-12">
                  <div class="checkbox">
                     <label>
                     <input type="checkbox" id="ckbHSNghiHoc"> Hiển thị học sinh nghỉ học
                     </label>
                  </div>
               </div>
               <table id="uptoClass" class="table table-striped table-bordered table-hover dataTable no-footer">
                  <thead>
                     <tr class="success">
                        <th class="text-center" style="vertical-align:middle">STT</th>
                        <th class="text-center" style="vertical-align:middle"><input name="select_all" value="1" id="upClass-select-all" type="checkbox"></th>
                        <!-- <th class="text-center" style="vertical-align:middle">Mã học sinh</th> -->
                        <th class="text-center" style="vertical-align:middle">Họ và tên</th>
                        <th class="text-center" style="vertical-align:middle">Năm sinh</th>
                        <th class="text-center" style="vertical-align:middle">Dân tộc</th>
                        <th class="text-center" style="vertical-align:middle">Hộ khẩu thường trú</th>
                        <th class="text-center" style="vertical-align:middle">Cha mẹ - người giám hộ</th>
              <!--           <th class="text-center" style="vertical-align:middle">Tình trạng</th> -->
                     </tr>
                  </thead>
                  <tbody id="contentPopupUpto">
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="modal fade" id="myModalProfile" role="dialog">
      <div class="modal-dialog modal-md" style="width: 80%;margin: 30px auto;">
         <!-- Modal content-->
         <div class="modal-content box">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Thêm mới học sinh</h4>
            </div>
            <form class="form-horizontal">
               <input type="hidden" id="txtHistoryId" name="txtHistoryId">
               <input type="hidden" id="txtProfileId" name="txtProfileId">
               <input type="hidden" id="txtHistoryYear" name="txtHistoryYear">
               <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>
               <div class="box-body">
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Họ và tên <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtNameProfile" placeholder="Nhập tên học sinh">
                     </div>
                  </div>
                  <div class="col-sm-6" style="padding-left: 0">
                     <label  class="col-sm-12 ">Trường <font style="color: red" id="messSchool">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
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
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Lớp học <font style="color: red" id="messClass">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltLop" disabled="disabled" id="sltLop" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           <option value="">--Chọn lớp--</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Ngày sinh <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtBirthday" placeholder="ngày-tháng-năm" onblur="validBirthday(this)">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Năm nhập học <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtYearProfile" name="txtYearProfile" placeholder="tháng-năm" onblur="validYearProfile(this)">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Dân tộc <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltDantoc" id="sltDantoc" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           <?php
                              $qlhs_nationals = DB::table('qlhs_nationals')->where('nationals_active',1)
                                ->select('nationals_id','nationals_name')->get();

                              ?>
                           @foreach($qlhs_nationals as $val)
                           <option value="{{$val->nationals_id}}" >{{$val->nationals_name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Chủ hộ</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtParent" placeholder="Chủ hộ" autocomplete="on">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Cha mẹ/người giám hộ</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtGuardian" placeholder="Cha mẹ hoặc người giám hộ" >
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">STT DS hộ nghèo</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtSTTHoNgheo" placeholder="Số TT DS Hộ nghèo" >
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Tỉnh/thành <font style="color: red">*</font><span id="url_hokhau"></span></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" name="sltTinh" id="sltTinh" class="form-control" disabled="disabled">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Quận/huyện <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" name="sltQuan" id="sltQuan" class="form-control" disabled="disabled">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Phường/xã <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" name="sltPhuong" id="sltPhuong" class="form-control" disabled="disabled">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Thôn xóm</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" name="txtThonxom" id="txtThonxom" class="form-control" disabled="disabled">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Chế độ 116 </label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltBantru" id="sltBantru" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           <option value="-1" selected="selected">-- Chọn --</option>
                           <option value="0">Ở ngoài trường</option>
                           <option value="1">Ở trong trường</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 " >Nhà ở xa trường</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtKhoangcach" placeholder="Nhập số km">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">GT cách trở, đi lại khó khăn</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="drGiaoThong" placeholder="Nhập số km">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Ở trong trường </label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <label for="ckbNQ57" class="btn btn-default">Nghị Quyết 57 <input  type="checkbox" id="ckbNQ57" class="badgebox"><span class="badge">&check;</span></label>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0" id="dirDoiTuong">
                     <label  class="col-sm-12 ">Thuộc đối tượng <span id="url_hocsinh"></span></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltDoituong" id="sltDoituong" multiple="multiple" style="width: 100%;" class="selectpicker form-control" data-actions-box="true" data-live-search="true" multiple data-selected-text-format="count">
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Đối tượng ăn</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltDoiTuongAn" id="sltDoiTuongAn" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           <?php
                              $truongId = Auth::user()->truong_id;
                              $qlhs_doituongan = DB::select('(SELECT id,name FROM bao_an.targets where school_id='.$truongId.')');
                              ?>
                           <option value="">--Chọn Đối tượng ăn--</option>
                           @foreach($qlhs_doituongan as $val)
                           <option value="{{$val->id}}">{{$val->name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>

                  <div class="col-sm-3" style="padding-left: 0"  id="divKyHoc">
                     <label  class="col-sm-12 ">Kỳ học</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltHocky" id="sltHocky" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           <option value="" selected="selected">-- Chọn kỳ học --</option>
                           <option value="HK1">Học kỳ 1</option>
                           <option value="HK2">Học kỳ 2</option>
                           <option value="CA">Cả năm</option>
                        </select>
                     </div>
                  </div>
               </div>

               <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%">
                  <div id="messageDangersdivKyHoc"></div>
               </div>
               <div class=" box box-body" id="tbMoney" hidden="hidden" style="font-size: 12px;overflow: auto;">
                  <table   class="table table-striped table-bordered table-hover dataTable no-footer">
                     <thead>
                        <tr class="success">
                           <th class="text-center" style="vertical-align:middle;width: 1%">STT</th>
                           <th class="text-left" style="vertical-align:middle;width: 20%">Chế độ/ chính sách được hưởng </th>
                           <th class="text-center" style="vertical-align:middle;width: 5%">Số tiền </th>
                        </tr>
                     </thead>
                     <tbody id="tbMoneyContent">
                     </tbody>
                  </table>
               </div>
               <div class="col-sm-3" style="text-decoration: underline;">
                  <a id="attachHS" onclick="viewMoreProfile()" style="cursor: pointer;"><i class="glyphicon glyphicon-paperclip"></i> Đính kèm thêm tài liệu</a>
               </div>
               <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%">
                  <div id="messageDangersQD"></div>
               </div>
               <div class=" box box-body" id="tableMoreProfile" hidden="hidden" style="font-size: 12px;overflow: auto;">
                  <table   class="table table-striped table-bordered table-hover dataTable no-footer">
                     <thead>
                        <tr class="success">
                           <th class="text-center" style="vertical-align:middle;width: 1%">STT</th>
                           <th class="text-center" style="vertical-align:middle;width: 1%">X</th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Loại quyết định <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Mã quyết định <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Số/kí hiệu <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Cơ quan xác nhận <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Ngày xác nhận <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Đính kèm<font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 10%">File </th>
                        </tr>
                     </thead>
                     <tbody id="tbDecided">
                        <tr id="trContent">
                        </tr>
                     </tbody>
                  </table>
                  <div class="col-sm-2">
                     <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="btnAddNewRow">Thêm tài liêu</button>
                  </div>
                  <div class="col-sm-3">
                     <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="clearFile">Xóa tệp file chọn</button>
                  </div>
               </div>
               <div class="modal-footer">
                  <div class="row text-center">
                     <button type="button" data-loading-text="Đang tải dữ liệu" class="btn btn-primary" id ="saveProfileNew">Lưu</button>
                     <button type="button" class="btn btn-primary"  data-dismiss="modal">Đóng</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="box-header">
      <h3 class="box-title"><b> Hồ sơ </b> /
         <small> Tạo mới hồ sơ</small>
      </h3>
      <!-- tools box -->
      <div class="pull-right box-tools" id="addnew-export-profile">
         <!--  <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
            <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
            <i class="fa fa-times"></i></button> -->
      </div>
      <!-- /. tools -->
   </div>
   <!-- <div class="panel panel-default">
      <div class="panel-heading" id="addnew-export-profile">

      </div>
      </div> -->
   <!--  <div class="box"> -->
   <div class="box box-primary">
      <!-- <div class="form-group " style="margin-bottom: 0px"> -->
      <div class="box-header col-sm-3">
         <label  class="col-sm-6">Trường: </label>
         <div class="col-sm-12">
            <select name="sltTruongGrid" id="sltTruongGrid" class="form-control selectpicker" @if(count(explode('-',Auth::user()->truong_id)) > 1) data-live-search="true" @endif>
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
      <div class="box-header col-sm-2">
         <label  class="col-sm-6">Lớp: </label>
         <div class="col-sm-12">
            <select name="sltLopGrid" id="sltLopGrid" class="form-control selectpicker"  data-live-search="true"></select>
         </div>
      </div>
      <div class="box-header col-sm-2">
         <label  class="col-sm-6" >Năm học: </label>
         <div class="col-sm-12">
            <select name="sltNamHoc" id="sltNamHoc" class="form-control selectpicker"  data-live-search="true">

            @foreach($history_year as $val)
            <option value="{{$val->history_year}}" @if(trim($val->history_year) == trim($str_date)) selected @endif>{{$val->history_year}}</option>
            @endforeach
            </select>
         </div>
      </div>
      <div class="col-sm-3">
         <label  class="col-sm-6" >Tìm kiếm: </label>
         <div class="box-header col-sm-12">
            <div class="has-feedback">
               <input id="txtSearchProfile" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
               <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
         </div>
      </div>
      <!--  </div> -->
      <div  style="font-size: 12px">
         <table class="table table-striped table-bordered table-hover dataTable no-footer">
            <thead id="dataHeadProfile">
            </thead>
            <tbody id="dataProfile">
            </tbody>
         </table>
      </div>
      <div class="box-footer clearfix">
         <div class="row">
            <div class="col-md-2">
               <label class="text-right col-md-9 control-label">Tổng </label>
               <label class="col-md-3 control-label g_countRowsPaging">0</label>
            </div>
            <div class="col-md-2">
               <label class="col-md-6 control-label text-right">Trang </label>
               <div class="col-md-6">
                  <select class="form-control input-sm g_selectPaging selectpicker">
                     <option value="0">0 / 20 </option>
                  </select>
               </div>
            </div>
            <div class="col-md-2">
               <label  class="col-md-6 control-label text-right">Hiển thị: </label>
               <div class="col-md-5">
                  <select name="viewTableProfile" id="viewTableProfile"  class="selectpicker form-control input-sm pagination-show-row">
                     <option value="10">10</option>
                     <option value="20" selected="selected">20</option>
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
   <!-- /.box -->
   </div>
   <!-- /.col -->
   </div>
   <!-- /.row -->
</section>
@endsection

