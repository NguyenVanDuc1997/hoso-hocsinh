<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
<link rel="stylesheet" href="<?php echo asset('/dist/css/check-radio.css'); ?>">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="<?php echo asset('bootstrap/css/bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('css/toastr.css'); ?>">
<script src="<?php echo asset('/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>
<!-- datepicker -->
<script src="<?php echo asset('/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<?php echo asset('js/select2.min.js'); ?>"></script>
<script src="<?php echo asset('js/toastr.js'); ?>"></script>
<script src="<?php echo asset('js/utility.js'); ?>"></script>
<script src="<?php echo asset('/mystyle/js/styleProfile.js'); ?>"></script>
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
                    utility.messagehide('group_message',"Năm nhập học lớn hơn năm hiện tại.!",0,5000);
                  }
                  $(val).val(key);  
                }else{
                  $("#txtYearProfile").focus();
                  utility.messagehide('group_message',"Không đúng định dạng ngày-tháng-năm!",1,5000);
                }  
              }
              
          }      
        }
      
       
        $('#checkboxactive_73').change(function(e){
          e.preventDefault();
            var self = $(this);
            if(self.is(":checked")){
                $('#checkboxactive_41').attr('disabled','disabled');
                $('#valid_41').html('(Cảnh báo: Đã sử dụng hộ nghèo)');
            }else{
                $('#checkboxactive_41').removeAttr('disabled');
                $('#valid_41').html('');
            }
        });
        $('#checkboxactive_41').on("change",function(e){
            e.preventDefault();
            var self = $(this);
            if(self.is(":checked")){
                $('#checkboxactive_73').attr('disabled','disabled');
                $('#valid_73').html('(Cảnh báo: Đã sử dụng hộ cận nghèo)');
            }else{
                $('#checkboxactive_73').removeAttr('disabled');
                $('#valid_73').html('');
            }
        });//
        $('#ckbNQ57').on('keydown',function(e){
          e.preventDefault();
          var self = $(this);
          $.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase());
          if($.browser.chrome){
            if(e.keyCode == 32){
              self.click();
                // if(self.is(":checked")){
                //   self.prop('checked', false);  
                // }else{
                //   self.prop('checked', true);  
                // }
            }else if (e.keyCode == 9) {
               $(".checkboxactive_0").focus();
              // $("#ckbNQ57CP").focus();
            }
          }
        });

        $('#ckbNQ57CP').on('keydown',function(e){
          e.preventDefault();
          var self = $(this);
          $.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase());
          if($.browser.chrome){
            if(e.keyCode == 32){
              self.click();
                // if(self.is(":checked")){
                //   self.prop('checked', false);  
                // }else{
                //   self.prop('checked', true);  
                // }
            }else if (e.keyCode == 9) {
              $(".checkboxactive_0").focus();
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
        $('#ckbNQ57CP').on('click',function(e){
          e.preventDefault();
          var self = $(this);
          if(self.val() != 1){
                  self.attr("checked",true);
                  self.val(1);
                  $('.badge57').css("text-indent","0");
          }else{
                  self.prop('checked', false);
                  self.val(0);
                  $('.badge57').css("text-indent","-999999px");
          }
      
        });
      
        $("input[name='checkboxactive']").on('keydown',function(e){
          e.preventDefault();
          var self = $(this);
          $.browser.chrome = /chrome/.test(navigator.userAgent.toLowerCase());
          if($.browser.chrome){
            if(e.keyCode == 32){
              if(self.is(":checked")){
      
                self.prop('checked', false);  
              }else{
                self.prop('checked', true);  
              }
              self.trigger('change');
            }  
          }
          
          form = self.parents('table');
          focusable =   form.find("input[type=checkbox]").filter(':visible');
          if (e.keyCode == 40) {
            next = focusable.eq(focusable.index(this)+1);
            if(next.is(":disabled")){
              next = focusable.eq(focusable.index(this)+2);
              if(next.is(":disabled")){
                next = focusable.eq(focusable.index(this)+3);
              }
            }
            if (next.length) {
              next.focus();
            }else{
              $('#checkboxactive_26').focus();
            }
          }else if(e.keyCode == 38){
            prev = focusable.eq(focusable.index(this)-1);
            if(prev.is(":disabled")){
              prev = focusable.eq(focusable.index(this)-2);
              if(prev.is(":disabled")){
                prev = focusable.eq(focusable.index(this)-3);
              }
            }
            if (prev.length) {
              prev.focus();
            }
          }else if(e.keyCode == 9){
            next = focusable.eq(focusable.index(this)+1);
            if(next.is(":disabled")){
              next = focusable.eq(focusable.index(this)+2);
              if(next.is(":disabled")){
                next = focusable.eq(focusable.index(this)+3);
              }
            }
            if (next.length) {
              next.focus();
            }else{
              $('#sltHockyNew').focus();
            }
          }
         });
        $('#btnCloseMessage').click(function(){
            $('#tbMessage').attr('hidden','hidden');
            $('.chuho_id').remove();
        });
      //autocompleteGiamHo();
      loadComboxDantoc(null);
      if(parseInt($('#school-per').val()) != 0 && ($('#school-per').val()+'').split('-').length == 1){
                loadComboxLop($('#school-per').val(),'sltLop',function(){
                    $('select#sltLop').removeAttr('disabled');
                    $('#sltLop').selectpicker('refresh');
                });
            }
    $("#sltHockyNew").change(function () {
        var namnhaphoc = $('#txtYearProfile').val();
        var str = "";
        $check3449 = 0;
        $check7441 = 0;
        $("input[name='checkboxactive']:checked").each(function() {
              str += $(this).val() + ",";
              if(parseInt($(this).val()) == 34 || parseInt($(this).val()) == 49){
                  $check3449++;
              }
              if(parseInt($(this).val()) == 74 || parseInt($(this).val()) == 41){
                  $check7441++;
              }
              // if(parseInt($(this).val()) == 74 || parseInt($(this).val()) == 41){
              //     $check7441++;
              // }

              // if(parseInt($(this).val()) == 73 || parseInt($(this).val()) == 41){
              //     check7341 = true;
              //     check46 = false;
              // }else if(parseInt($(this).val()) == 46){
              //     check7341 = false;
              //     check46 = true;
              // }else{
              //     check7341 = false;
              //     check46 = false;
              // }
        });
            if($check3449 == 2){
                str += "101,";
            }
            if($check7441 == 2){
                str += "100,";
            }

              if(parseInt($('input[name="school-type"]').val()) == 4){
                  str += "70,";
              }
              str = str.substring(0, str.length - 1);
      
            // if(str == ""){
            //        utility.messagehide('messageDangersdivKyHoc',"Xin mời chọn đối tượng!",1,5000);
            //        $('#sltHockyNew').val('').selectpicker('refresh');
            // }else{
                if($('#sltTruong').val() == ""){
                   utility.messagehide('messageDangersdivKyHoc',"Xin mời chọn trường học!",1,5000);
                   $('#sltTruong').val('');
                   return;
                }
                if($('#sltLop').val() == ""){
                   utility.messagehide('messageDangersdivKyHoc',"Xin mời chọn lớp học!",1,5000);
                   $('#sltLop').val('');
                   return;
                }
                if($('#sltPhuong').val() == ""){
                   utility.messagehide('messageDangersdivKyHoc',"Xin mời chọn phường xã!",1,5000);
                   $('#sltPhuong').val('');
                   return;
                }
                if(namnhaphoc == null || namnhaphoc == ''){
                    utility.messagehide('messageDangersdivKyHoc',"Nhập năm nhập học!",1,5000);
                   $('#formtest').focus();
                   $('#sltHockyNew').val('').selectpicker('refresh');
                   $('#txtYearProfile').focus();
                }else{
                    var namhoc = namnhaphoc;
                    namhoc = namhoc.substr(3, namhoc.length);
                    var hocky = $(this).val();
                    var truongId = $('#sltTruong').val();
                    var arrSubID = [];
                    var lopId = $('#sltLop').val();
                    var xaId = $('#sltPhuong').val();
                    var bantru = $('#sltBantru').val();
                    var o = {
                        SCHOOLID: truongId,
                        NQ57: $('#ckbNQ57').val(),
                        CLASSID: lopId,
                        XAID: xaId,
                        YEAR: namhoc,
                        HOCKY: hocky,
                        BANTRU: bantru,
                        ARRSUBJECT: str
                    };
      
                    loadMoneybySubject(o);
                    }
               // }
            });
      
        // $('#sltHocky').on("select2:select", function(e) { 
        //     e.preventDefault();
            
        // var namnhaphoc = $('#txtYearProfile').val();
        // var str = "";
        // if($('#sltHocky').val() == "0"){
        //       $('#sltHocky').focus();
        // }else{
        //     $("input[name='checkboxactive']:checked").each(function() {
        //         str += $(this).val() + ",";
        //     });
        //     if(str.split(',').length <= 1){
        //        utility.messagehide('messageDangersdivKyHoc',"Xin mời chọn đối tượng!",1,5000);
        //        $(".checkboxactive_0").focus();
        //     }else{
        //         if(namnhaphoc == null || namnhaphoc == ''){
        //           utility.messagehide('messageDangersdivKyHoc',"Nhập năm nhập học!",1,5000);
        //              $('#txtYearProfile').focus();     
        //         }else{
        //           str = str.substring(0, str.length - 1);
        //               var namhoc = namnhaphoc;
        //               namhoc = namhoc.substr(3, namhoc.length);
        //               // console.log(namhoc);
        //               var hocky = $(this).val();
        //               var truongId = $('#sltTruong').val();
        //               var arrSubID = [];
        //               var lopId = $('#sltLop').val();
        //               var xaId = $('#sltPhuong').val();
                      
        //               var o = {
        //                   SCHOOLID: truongId,
        //                   CLASSID: lopId,
        //                   XAID: xaId,
        //                   YEAR: namhoc,
        //                   HOCKY: hocky,
        //                   ARRSUBJECT: str
        //               };
      
        //               loadMoneybySubject(o);
        //             }
        //         }
        //     }
        // });
       
       
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
      
   </script>
   <div class="panel panel-default" style="margin-bottom: 0">
      <div class="panel-heading" >
         <a href="/ho-so/hoc-sinh/danh-sach"><b> Hồ sơ </b></a> / Tạo mới hồ sơ
      </div>
   </div>
   <!--  <div class="box"> -->
   <section class="content">
      <div class="row">
         <!-- left column -->
         <div class="col-md-8">
            <!-- general form elements -->
            <div  class="  box ">
               <form class="form-horizontal">
                  <input type="hidden" id="txtWardID" name="">
                  <input type="hidden" name="school-type">
                  <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>
                  <div class="box-body">
                     <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12">Họ và tên <font style="color: red">*</font></label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <input type="text" class="form-control" id="txtNameProfile" placeholder="Nhập tên học sinh" >
                        </div>
                     </div>
                     <div class="col-sm-6" style="padding-left: 0">
                        <label  class="col-sm-12 ">Trường <font style="color: red">*</font></label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <select name="sltTruong" id="sltTruong"  class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                              <?php 
                                 if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                                   $qlhs_schools = DB::table('qlhs_schools')->where('schools_active','=',1)->whereIn('schools_id',explode('-',Auth::user()->truong_id))->select('schools_id','schools_name')->get();
                                 }else{
                                   $qlhs_schools = DB::table('qlhs_schools')->where('schools_active','=',1)->select('schools_id','schools_name')->get();
                                 }
                                 ?>
                              <option value="">-- Chọn trường học --</option>
                              <?php $__currentLoopData = $qlhs_schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($val->schools_id); ?>" <?php if($val->schools_id == Auth::user()->truong_id): ?> selected <?php endif; ?>><?php echo e($val->schools_name); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12">Lớp học <font style="color: red">*</font></label>
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
                           <select name="sltDantoc" id="sltDantoc" class="form-control selectpicker"  data-live-search="true"  style="width: 100% !important">
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12 ">Chủ hộ</label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <input type="text" class="form-control" id="txtParent" placeholder="Chủ hộ">
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
                        <label  class="col-sm-12">Tỉnh/thành <font style="color: red">*</font></label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <select name="sltTinh" id="sltTinh" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                              <?php 
                                 $qlhs_site = DB::table('qlhs_site')->where('site_active','=',1)->where('site_level',1)->select('site_id','site_name')->get();
                                 ?>
                              <option value="">--Chọn tỉnh--</option>
                              <?php $__currentLoopData = $qlhs_site; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($val->site_id); ?>" <?php if($val->site_id == 95): ?> selected <?php endif; ?>><?php echo e($val->site_name); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12">Quận/huyện <font style="color: red">*</font></label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <select name="sltQuan" id="sltQuan" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                              <?php 
                                 $qlhs_site = DB::table('qlhs_site')->where('site_active','=',1)->where('site_level',2)->where('site_parent_id',95)->select('site_id','site_name')->get();
                                 ?>
                              <option value="">--Chọn quận/huyện--</option>
                              <?php $__currentLoopData = $qlhs_site; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($val->site_id); ?>"><?php echo e($val->site_name); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12">Phường/xã <font style="color: red">*</font></label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <select name="sltPhuong" disabled="disabled" id="sltPhuong"  class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12">Thôn xóm</label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <select name="txtThonxom" disabled="disabled" id="txtThonxom"  class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12 ">Chế độ 116 </label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <select name="sltBantru" id="sltBantru" class="form-control selectpicker"  data-live-search="true">
                              <option value="-1" selected="selected">-- Chọn chế độ --</option>
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
                        <label  class="col-sm-12 ">GT cách trở,đi lại khó khăn</label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <input type="text" class="form-control" id="drGiaoThong" placeholder="Nhập số km">
                           <!--  <select name="sltBantru" id="drGiaoThong"  class="form-control ">
                              <option value="0">Không</option>
                              <option value="1">Có</option>
                              </select> -->
                        </div>
                     </div>
                     <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12 ">Hỗ trợ học sinh </label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <label for="ckbNQ57" style="margin-top: 0;" class="btn btn-default">NQ57/2016/NQ-HĐND <input  type="checkbox" id="ckbNQ57" value="0" class="badgebox"><span class="badge">&check;</span></label>
                        </div>
                     </div>
<!--                      <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12 ">Hỗ trợ học sinh </label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <label for="ckbNQ57CP" style="margin-top: 0;" class="btn btn-default">NQ57/2017/NQ-CP <input  type="checkbox" id="ckbNQ57CP" value="0" class="badgebox"><span class="badge57">&check;</span></label>
                        </div>
                     </div> -->
                     <div class="col-sm-3" style="padding-left: 0"  id="divKyHoc">
                        <label  class="col-sm-12 ">Kỳ học</label>
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <select name="sltHockyNew" id="sltHockyNew"  class="form-control selectpicker"  data-live-search="true">
                              <option value="0" selected="selected">-- Chọn kỳ học --</option>
                              <option value="HK1">Học kỳ 1</option>
                              <option value="HK2">Học kỳ 2</option>
                              <option value="CA">Cả năm</option>
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
                              <?php $__currentLoopData = $qlhs_doituongan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($val->id); ?>"><?php echo e($val->name); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-3" style="padding-left: 0">
                        <label  class="col-sm-12 create_alert" ></label>   
                        <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                           <span  style="color:red;font-size: 10px;font-style: italic;" id="valid_bantru"></span>
                        </div>
                     </div>
                  </div>
                  <div class=" box box-body" id="tbMessage" hidden="hidden" style="font-size: 12px;overflow: auto;">
                     <table   class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                           <tr class="success">
                              <th class="text-center" style="vertical-align:middle;width: 10%">Thông tin</th>
                              <th class="text-center" style="vertical-align:middle;width: 30%">Thông báo </th>
                              <th class="text-center" style="vertical-align:middle;width: 30%">Nội dung</th>
                              <th class="text-center" style="vertical-align:middle;width: 10%"><button type="button"  class="btn btn-danger btn-xs editor_remove" id="btnCloseMessage"><i class="glyphicon glyphicon-remove"></i> </button></th>
                           </tr>
                        </thead>
                        <tbody id="tbMessageContent">
                        </tbody>
                     </table>
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
                  <div class="form-group">
                     <div class="col-sm-3" style="text-decoration: underline;">
                        <a id="attachHS" onclick="viewMoreProfile()" style="cursor: pointer;"><i class="glyphicon glyphicon-paperclip"></i> Đính kèm thêm tài liệu</a>
                     </div>
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
                        <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="btnAddNewRow">Thêm tài liệu</button>
                     </div>
                     <div class="col-sm-3"> 
                        <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="clearFile">Xóa tệp file chọn</button>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <div class="row text-center">
                        <button type="button" data-loading-text="Đang tải dữ liệu" class="btn btn-primary" id ="saveProfileNew">Thêm mới</button>
                        <a  class="btn btn-primary" href="/ho-so/hoc-sinh/danh-sach">Trở lại</a>
                     </div>
                  </div>
               </form>
            </div>
            <!-- /.box -->
         </div>
         <!--/.col (left) -->
         <!-- right column -->
         <div class="col-md-4" style="padding-left: 0;">
            <!-- Horizontal Form -->
            <div class="box box-info">
               <!-- /.box-header -->
               <table class="table table-striped table-bordered table-hover dataTable no-footer">
                  <thead>
                     <tr class="success" id="cmisGridHeader">
                        <th class="text-center" style="vertical-align:middle">X</th>
                        <!-- <th  class="text-center" style="vertical-align:middle"><input type="checkbox" name="checkedAllChedo" id="checkedAllChedo"></th> -->
                        <th class="text-center" style="vertical-align:middle">Nhóm đối tượng</th>
                     </tr>
                  </thead>
                  <tbody id="dataDoituong">
                     <?php 
                        $qlhs_subject = DB::table('qlhs_subject')
                        ->where('subject_active',1)
                        ->where('key','<>',0)
                        ->select('subject_id','subject_name')
                        ->get();
                        ?>
                     <?php $__currentLoopData = $qlhs_subject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <tr>
                        <td class='text-center' style='vertical-align:middle;'>
                           <input onfocus="onFocus(<?php echo e($val->subject_id); ?>)" type='checkbox' value='<?php echo e($val->subject_id); ?>' id='checkboxactive_<?php echo e($val->subject_id); ?>' name='checkboxactive' class='checkboxactive_<?php echo e($key); ?>' onblur="onBlur(<?php echo e($val->subject_id); ?>)" />
                        </td>
                        <td style='vertical-align:middle;' class="activeCheckbox" id="checkboxfocus_<?php echo e($val->subject_id); ?>">
                           <label style='font-weight: 500' for='checkboxactive_<?php echo e($val->subject_id); ?>'><?php echo e($val->subject_name); ?> <span style="color: red;font-size: 10px;font-style:italic" id="valid_<?php echo e($val->subject_id); ?>"></span></label>
                        </td>
                     </tr>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </tbody>
               </table>
            </div>
            <!-- /.box -->
            <!-- /.box -->
         </div>
         <!--/.col (right) -->
      </div>
      <!-- /.row -->
   </section>
   <!-- /.box -->
   </div>
   <!-- /.col -->
   </div>
   <!-- /.row -->
</section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>