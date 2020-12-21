

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo asset('dist/css/bootstrap-multiselect.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('css/select2.min.css'); ?>">
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
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>
<script src="<?php echo asset('/mystyle/js/styleProfile.js'); ?>"></script>

<section class="content">
<script type="text/javascript">
  $(function () {
    $('#txtstart_year').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
    validDate = function(val){
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
                  $(val).val(key); 
                }else{
                  
                  utility.messagehide('modal_message',"Ngày tháng không đúng định dạng!",1,5000);
                  $('#txtstart_year').focus();
                }
            }else{
                utility.messagehide('modal_message',"Ngày tháng không đúng định dạng!",1,5000);
                $('#txtstart_year').focus();
                
            }
            
        }      
    };
    checkDT73 = function(data){
       var self = $(data);
      if(self.val() == 73){
          if(self.is(":checked")){
              $('#checkboxactive_41').attr('disabled','disabled');
              $('#valid_41').html('(Cảnh báo: Đã sử dụng hộ nghèo)');
          }else{
              $('#checkboxactive_41').removeAttr('disabled');
              $('#valid_41').html('');
          }
      }else if(self.val() == 41){
        if(self.is(":checked")){
            $('#checkboxactive_73').attr('disabled','disabled');
            $('#valid_73').html('(Cảnh báo: Đã sử dụng hộ cận nghèo)');
        }else{
            $('#checkboxactive_73').removeAttr('disabled');
            $('#valid_73').html('');
        }
      }
        
    }

    autocomUpdateSubProfile('txtSearchProfile');

    $('#btnUpdateSubject').click(function(){

        var subject_id = '';
        var un_subject_id = '';
        $("input.checkboxactive").each(function() {
            if($(this).is(':checked')){
              subject_id += $(this).val() + "-";
            }else{
              un_subject_id += $(this).val() + "-";
            }
        });
        subject_id = subject_id.substring(0, subject_id.length - 1);
        un_subject_id = un_subject_id.substring(0, un_subject_id.length - 1);
        var o = {
          start_time : $('#txtStart_time').val(),
          end_time : $('#txtEnd_time').val(),
          profile_id : $('#txtProfileID').val(),
          start_year : $('#txtstart_year').val(),
          end_year : $('#end_year').val(),
          subject : subject_id,
          un_subject : un_subject_id
        };
        PostToServer('/ho-so/updateSubject/profile',o,function(dataget){
          if(dataget.success != null || dataget.success != undefined){
                $("#ModalSubjectProfile").modal("hide");
                    utility.message("Thông báo",dataget.success,null,3000)
                  //  GET_INITIAL_NGHILC();
                    loadDataSubject($('#txtSearchProfile').val());
                }else if(dataget.error != null || dataget.error != undefined){
                    utility.message("Thông báo",dataget.error,null,3000,1)
            }
        },function(result){
          console.log('updateByProfile changeSubject: '+ result);
        },"btnUpdateSubject","","");
      
    });
    $('#btnChangeSubject').click(function(){
        var subject_id = '';
      //  var un_subject_id = '';
        $("input.checkboxactive").each(function() {
            if($(this).is(':checked')){
              subject_id += $(this).val() + "-";
            }
        });

        if ($('#txtstart_year').val() === null || $('#txtstart_year').val() === "") {
          utility.messagehide("group_message_popupchange", "Vui lòng nhập ngày hiệu lực", 1, 5000);
          return;
        }

        if (subject_id === null || subject_id === "") {
          utility.messagehide("group_message_popupchange", "Vui lòng chọn đối tượng", 1, 5000);
          return;
        }

        subject_id = subject_id.substring(0, subject_id.length - 1);
        var o = {
          profile_id : $('#txtProfileID').val(),
          subject : subject_id,
          start_year : $('#txtstart_year').val(),
          start_year_cur : $('#start_year').val()
        }
        PostToServer('/ho-so/hoc-sinh/subject/insertByProfile/profile',o,function(dataget){
            if(dataget.success != null || dataget.success != undefined){
                $("#ModalSubjectProfile").modal("hide");
                    utility.message("Thông báo",dataget.success,null,3000)
                    //GET_INITIAL_NGHILC();
                    loadDataSubject($('#txtSearchProfile').val());
                }else if(dataget.error != null || dataget.error != undefined){
                    utility.message("Thông báo",dataget.error,null,3000,1)
            }
        },function(result){
          console.log('updateByProfile changeSubject: '+ result);
        },"btnUpdateSubject","","");
    });
    $('select#drSchoolTHCD').change(function() {
      if($(this).val() != null && $(this).val() != "" && parseInt($(this).val()) != 0){
            loading();
        loadComboxLop($(this).val(),'sltLopGrid',function(){
                closeLoading();
            });
        $('select#sltLopGrid').removeAttr('disabled');

      }else{
        $('select#sltLopGrid').html('<option value="">--Chọn lớp--</option>');
        $('select#sltLopGrid').attr('disabled','disabled');
      }
      GET_INITIAL_NGHILC();
      loadDataSubject($('#txtSearchProfile').val());
    });
    if($('#txtSchool_id').val() == '' || $('#txtClass').val() == '' || $('#txtName').val() == ''){
        loadComboxTruongHoc("drSchoolTHCD", function(){
            $('select#sltLopGrid').removeAttr('disabled');
            GET_INITIAL_NGHILC();
            loadDataSubject($('#txtSearchProfile').val());
       
      }, $('#school-per').val());
    }else{
        $('#txtSearchProfile').val($('#txtName').val());
        loadComboxTruongHoc("drSchoolTHCD", function(){
            $('select#sltLopGrid').removeAttr('disabled');
            GET_INITIAL_NGHILC();
            loadDataSubject($('#txtSearchProfile').val());
      },$('#txtSchool_id').val());
    }
      
  // $("#drSchoolTHCD").select2({
  //     placeholder: "-- Chọn trường học --",
  //     allowClear: true,
  //   });
// $("#sltNamHoc").select2({
//       placeholder: "-- Chọn trường học --",
//       allowClear: true,
//     });
//     $("#sltLopGrid").select2({
//       placeholder: "-- Chọn lớp học --",
//       allowClear: true
//     });
  $('select#sltLopGrid').change(function() {
      GET_INITIAL_NGHILC();
      loadDataSubject($('#txtSearchProfile').val());
  });
  $('#btnLoadDataSubject').click(function(){
      GET_INITIAL_NGHILC();
      loadDataSubject($('#txtSearchProfile').val());
  });
  $('#drPagingDanhsachtonghop').change(function(){
      GET_INITIAL_NGHILC();
      loadDataSubject($('#txtSearchProfile').val());
  });




  //  autocompleteSearch("txtSearchProfile", 1);


   
  });


 function openModalUpdate(){
    $('#saveProfile').html("Cập nhật");
    $('.modal-title').html('Sửa hồ sơ học sinh');
    $('#nextChangeSubject').html('Thay đổi đối tượng');
    $('#sltDoituong').multiselect({
      nonSelectedText:'-- Chọn đối tượng --'
    });
    $("#myModalProfile").modal("show");
   }
   
 
</script>
<div class="modal fade" id="myModalProfile" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thêm mới học sinh</h4>
        </div>
        <form class="form-horizontal" action="" id="formtest">  
        <input type="hidden" class="form-control" id="txtIdProfile">          
                <div class="modal-body" style="font-size: 12px;padding: 5px;">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%">
                      <div id="messageDangers"></div>
        
                    </div>
                    <div class="box-body">
                <div class="form-group">
                  <!-- <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6">Mã học sinh <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtCodeProfile" placeholder="Nhập mã học sinh" autofocus="true">
                    </div>
                  </div> -->
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Họ và tên <font style="color: red">*</font></label>

                  <div class="col-sm-12">
                    <input type="text" class="form-control" id="txtNameProfile" placeholder="Nhập tên học sinh">
                  </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Ngày sinh <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtBirthday" placeholder="ngày-tháng-năm">
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Dân tộc <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <select name="sltDantoc" id="sltDantoc"  class="form-control" style="width: 100% !important">
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-12 ">Chủ hộ<font style="color: red">*</font></label>
    
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtParent" placeholder="Nhập cha mẹ hoặc người giám hộ" autocomplete="on">
                    </div>
                  </div>
                  
                </div>
                <div class="form-group">
                  
                  
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-12 ">Trường <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <select name="sltTruong" id="sltTruong"  class="form-control" style="width: 100% !important">
                        <option value="">--Chọn trường--</option>
                    </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Lớp học <font style="color: red">*</font></label>

                    <div class="col-sm-12" >
                      <select name="sltLop" disabled="disabled" id="sltLop"  class="form-control" style="width: 100% !important">
                          <option value="">--Chọn lớp--</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0" id="dirDoiTuong">
                    <label  class="col-sm-12 ">Thuộc đối tượng <span id="url_hocsinh"></span></label>

                    <div class="col-sm-12">
                      <select name="sltDoituong" id="sltDoituong" multiple="multiple" class="form-control">

                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Năm nhập học <font style="color: red">*</font></label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtYearProfile" name="txtYearProfile" placeholder="tháng-năm">
                    </div>
                  </div>
                </div>
                <div class="form-group">

                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Tỉnh/thành <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <select name="sltTinh" id="sltTinh" class="form-control" style="width: 100% !important">
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Quận/huyện <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <select name="sltQuan" disabled="disabled" id="sltQuan"  class="form-control" style="width: 100% !important">
                          
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Phường/xã</label>

                    <div class="col-sm-12">
                      <select name="sltPhuong" disabled="disabled" id="sltPhuong"  class="form-control" style="width: 100% !important">
                       
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Thôn xóm</label>

                    <div class="col-sm-12">
                      <select name="txtThonxom" disabled="disabled" id="txtThonxom"  class="form-control" style="width: 100% !important">
                       
                      </select>
                        <!-- <input type="text" class="form-control" id="txtThonxom" placeholder="Nhập thôn xóm"> -->
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
                  <div class="modal-header">
          <h4>Học sinh được hưởng chế độ 116</h4>
        </div>

     <div class="form-group">
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 " style="width: 80% !important">Trường hợp nhà ở xa trường</label>

                    <div class="col-sm-12" style="padding-right: 5px !important;">
                        <input type="text" class="form-control" id="txtKhoangcach" placeholder="Nhập số km">
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-12 ">Giao thông cách trở, đi lại khó khăn</label>

                    <div class="col-sm-12" style="padding-left: 5px !important; padding-right: 5px !important;">
                      <input type="text" class="form-control" id="drGiaoThong" placeholder="Nhập số km">
                       <!--  <select name="sltBantru" id="drGiaoThong"  class="form-control ">
                          <option value="0">Không</option>
                          <option value="1">Có</option>
                    </select> -->
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-12 ">Ở trong trường/ ngoài trường <font style="color: red">*</font></label>

                    <div class="col-sm-12" style="padding-left: 5px !important; padding-right: 5px !important;">
                        <select name="sltBantru" id="sltBantru"  class="form-control ">
                          <option value="">-- Chọn --</option>
                          <option value="0">Ở ngoài trường</option>
                          <option value="1">Ở trong trường</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Ở trong trường </label>

                    <div class="col-sm-12" style="padding-left: 5px !important; padding-right: 5px !important;">
                        <label for="ckbNQ57" class="btn btn-default">Nghị Quyết 57 <input type="checkbox" id="ckbNQ57" class="badgebox"><span class="badge">&check;</span></label>
                    </div>
   
                  </div>                  
                </div>
                             
                  <div class="form-group" id="divKyHoc">
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Kỳ học</label>

                    <div class="col-sm-12">
                        <select name="sltHocky" id="sltHocky"  class="form-control ">
                          <option value="">-- Chọn kỳ học --</option>
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
               <!--  <div class="form-group">
    <label>
     You may also allow to clear the selection! 
     <span id="clear" class="btn btn-default btn-xs">
      Clear the file name
     </span>
    </label>
      <input type="file" id="cleardemo">
</div> -->
                <div class="form-group">
                  <div class="col-sm-3" style="text-decoration: underline;">
                    <a onclick="viewMoreProfile()" style="cursor: pointer;"><i class="glyphicon glyphicon-paperclip"></i> Đính kèm thêm tài liệu</a>
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
            <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="btnAddNewRow">Thêm tài liêu</button></div> 
            <div class="col-sm-2"> 
              <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="clearFile">Xóa tệp file chọn</button></div>
            </div> </div></div>
          
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang tải dữ liệu" class="btn btn-primary" id ="saveProfile">Lưu</button>
                        <button type="button" class="btn btn-primary" id="btnClosePopupProfile" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="modal fade" id="ModalSubjectProfile" role="dialog">
    <div class="modal-dialog modal-md" style="width: 80%;margin: 30px auto;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header" style="padding: 0px 10px">
          <button type="button" class="close" style="margin-top: 10px;" data-dismiss="modal">&times;</button>
          <h4 class="">Đối tượng</h4>
        </div><div style="margin-top: 5px;margin-bottom: 10px">
          <div class="row" id="modal_message" style="padding-left: 10%;padding-right: 10%"></div>
              <div class="col-sm-5" style="padding-left: 0">
                    <label  class="col-sm-5" style="margin-top: 5px;">Ngày bắt đầu hiều lực</label>

                  <div class="col-sm-6">
                    <input type="text"  class="form-control" name="txtstart_year" id="txtstart_year" placeholder="ngày-tháng-năm" onblur="validDate(this)">
                  </div>
                  </div>
                  <div class="col-sm-5" style="padding-left: 0">
                    <label  class="col-sm-4 hidden" style="margin-top: 5px;">Năm kết thúc </label>

                  <div class="col-sm-6">
                    <input type="hidden" class="form-control" disabled="disabled" name="txtend_year" id="txtend_year" >
                  </div>
                  </div> </div>
        <div class="box-body no-padding">
              
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message" style="margin-top: 10px;">
              <form class="form-horizontal" action=""> 
                      
                <div class="modal-body">
                    <div class="row" id="group_message_popupchange" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                      <input type="hidden" name="txtStart_time" id="txtStart_time">
                      <input type="hidden" name="txtEnd_time" id="txtEnd_time">
                      <input type="hidden" name="txtProfileID" id="txtProfileID">
                      <input type="hidden" name="start_year" id="start_year">
                      <input type="hidden" name="end_year" id="end_year">
                 
                      <div class="box box-primary">

                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr class="success" id="cmisGridHeader">
                <!--           <th  class="text-center" style="vertical-align:middle;width: 5%">STT</th> -->
                          <th  class="text-center" style="vertical-align:middle;width: 5%">X</th>
                          <th  class="text-center" style="vertical-align:middle;width: 50%">Tên đối tượng</th>
                         

                        </tr>
                 
                      </thead>
                        <tbody id="dataSubjectProfile">                     
                        </tbody>
                    </table>
                </div>       
            </div>

                


                  </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center" >
                        <button type="button"  data-loading-text="Đang cập nhật dữ liệu" class="btn btn-primary" id ="btnUpdateSubject">Cập nhật</button>
                        <button type="button" data-loading-text="Đang thay đổi dữ liệu" class="btn btn-primary" id ="btnChangeSubject">Thay đổi</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>

              </div>
                
            </div>
      </div>
      
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-profile">
       <a href="/"><b> Hồ sơ </b></a> / Cập nhật thay đổi đối tượng
    </div>
</div>
    

         <!--    <div class="box box-primary"> -->

               <div class="box box-primary" style="font-size: 12px;">
                    <div class="col-sm-4 box-header">
                      <label  class="col-sm-2 control-label  text-right">Trường</label>
                      <div class="col-sm-10 ">
                       <select name="drSchoolTHCD" id="drSchoolTHCD" class="form-control selectpicker" data-live-search="true">
                         <?php 
                          if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                            $qlhs_schools = DB::table('qlhs_schools')
                            ->where('schools_active',1)
                            ->whereIn('schools_id',explode('-',Auth::user()->truong_id))
                            ->select('schools_id','schools_name')->get();
                          }else{
                            $qlhs_schools = DB::table('qlhs_schools')
                            ->where('schools_active',1)
                            ->select('schools_id','schools_name')->get();
                         }
                         ?>
                         <option value="">-- Chọn trường học --</option>
                         <?php $__currentLoopData = $qlhs_schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val->schools_id); ?>" <?php if($val->schools_id == Auth::user()->truong_id): ?> selected <?php endif; ?>><?php echo e($val->schools_name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                       </select>
                       <input type="hidden" id="txtSchool_id" value="<?php echo e($s_id); ?>">
                      </div>
                    </div>

                    <div class="col-sm-4"></div>
                     <div class="col-sm-4 box-header">
                          <div class="has-feedback">
                            <input id="txtSearchProfile" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            <input type="hidden" id="txtName" value="<?php echo e($p_id); ?>">
                          </div>
                    </div>
                    <div class="hidden">
                     <div class="row text-center" id="event-thcd">
                            <button type="button" data-loading-text="Đang tải dữ liệu" class="btn btn-success" id ="btnLoadDataSubject"><i class="glyphicon glyphicon-search"></i> Xem </button>
                          
                        </div>
                    </div>
                <div class="box-body" style="font-size: 12px">     
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead id="dataHeadSubject">
                        
                 
                        </thead>
                        <tbody id="dataSubject">                     
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
                                  <select class="form-control input-sm g_selectPaging selectpicker">
                                      <option value="0">0 / 20 </option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-3">
                                        <label  class="col-md-6 control-label text-right">Hiển thị: </label>
                                        <div class="col-md-3">
                                          <select name="drPagingDanhsachtonghop" id="drPagingDanhsachtonghop"  class="selectpicker form-control input-sm pagination-show-row">
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
        <!--   </div> -->
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>