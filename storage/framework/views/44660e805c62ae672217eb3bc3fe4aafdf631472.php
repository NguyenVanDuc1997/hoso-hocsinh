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
<script src="<?php echo asset('/mystyle/js/styleProfile.js'); ?>"></script>

<section class="content">
<script type="text/javascript">
  $(function () {
    //loaddutoan($('#sltYear').val());
    $('#expense').keyup(function(){
      tmp = this.value.replaceAlls('.','');
      this.value = formatter(tmp);
    });
    $('#update_expense').keyup(function(){
      tmp = this.value.replaceAlls('.','');
      this.value = formatter(tmp);
    });
    permission(function(){
      var html_view  = '';
      var html_view_header  = '<b>Quản lý dự toán chi trả </b> / Chi trả';
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

</script>
<div class="modal fade" id="myModalDieuChinh" role="dialog">
  <div class="modal-dialog modal-md" style="width: 60%;margin: 10px auto;">
    <div class="modal-content box">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="">Cập nhật chi trả<b class="title"></b></h4>
      </div>
      <div class="box-body no-padding">
        
        <input type="hidden" name="txtIDDieuChinh" id="txtIDDieuChinh">
        <form class="form-horizontal" action="">   
          <div class="modal-body">
            <div class="row" style="padding-left: 10%;padding-right: 10%"></div>  
            <div class="box-body">
              <div class="box box-primary">
                <span class="lblChiTra"></span>
                <div class="box-body" style="font-size:12px;">
                    <div class="col-sm-4">
                      <label class="text-left col-md-12">Kinh phí</label>
                          <div class="col-md-12">
                            <input type="text" class="form-control" name="update_expense" id="update_expense" value="">
                          </div>
                    </div>
                    <div class="col-sm-4">
                      <label class="text-left col-md-12">Trạng thái</label>
                          <div class="col-md-12">
                            <select class="form-control selectpicker" name="update_using" id="update_using" >
                                <option value="1" selected="selected">Điều chỉnh tăng</option>
                                <option value="-1">Điều chỉnh giảm</option>
                            </select>
                          </div>
                    </div>
                    <div class="col-sm-4">
                      <label class="text-left col-md-12">Số lần cấp</label>
                          <div class="col-md-12">
                            <select class="form-control selectpicker" name="update_times" id="update_times" >
                                <option value="1" selected="selected">Lần 1</option>
                                <option value="2">Lần 2</option>
                                <option value="3">Lần 3</option>
                                <option value="4">Lần 4</option>
                                <option value="5">Lần 5</option>
                            </select>
                          </div>
                    </div>
                </div>
            </div>

                  </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                       <button type="button" class="btn btn-primary" id="btnUpdateDieuChinh">Cập nhật</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
 
            </div>

      </div>
      
    </div>
</div>
<div class="modal fade" id="myModalDetail" role="dialog">
    <div class="modal-dialog modal-md" style="width: 60%;margin: 10px auto;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="">Cấp kinh phí <b class="title"></b></h4>
        </div>
        <div class="box-body no-padding">
              <input type="hidden" name="txtId" id="txtId">
              <input type="hidden" name="txtCount" id="txtCount">
              <!-- /.mailbox-controls -->
             <!--  <div class="mailbox-read-message" style="margin-top: 10px;"> -->
              <form class="form-horizontal" action="">         
                <div class="modal-body">
                    <div class="row" id="group_message_approved" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                      <div class="box box-primary">
                <div class="box-body" style="font-size:12px;">
                    <div class="col-sm-4">
                      <label class="text-left col-md-12">Kinh phí</label>
                          <div class="col-md-12">
                            <input type="text" class="form-control" name="expense" id="expense" value="">
                          </div>
                    </div>
                    <div class="col-sm-4">
                      <label class="text-left col-md-12">Trạng thái</label>
                          <div class="col-md-12">
                            <select class="form-control selectpicker" name="using" id="using" >
                                <option value="1" selected>Đã cấp</option>
                                <option value="2">Đã thay đổi</option>
                            </select>
                          </div>
                    </div>
                    <div class="col-sm-4">
                      <label class="text-left col-md-12">Số lần cấp</label>
                          <div class="col-md-12">
                            <select class="form-control selectpicker" name="times" id="times" >
                                <option value="1" selected="selected">Lần 1</option>
                                <option value="2">Lần 2</option>
                                <option value="3">Lần 3</option>
                                <option value="4">Lần 4</option>
                                <option value="5">Lần 5</option>
                            </select>
                          </div>
                    </div>
                    <div class="col-sm-12">
                      <label class="text-left col-md-12">Nội dung</label>
                      <div class="col-md-12">
                            <input type="text" class="form-control" name="note" id="note" value="">
                          </div>
                    </div>
                    <div class="col-sm-12">
                      <label class="text-left col-md-12">Đính kèm</label>
                      <div class="col-md-12">
                            <input type="file" name="fileQuyetDinh[]" value="" class="form-control" multiple="multiple"/>
                          </div>
                    </div>
                </div>
            </div>

                  </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                       <button type="button" class="btn btn-primary" id="LuuCapKinhPhi">Lưu</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
 
            </div>

      </div>
      
    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-profile">
       <!-- <a href="/ho-so/lap-danh-sach/list"><b> Hồ sơ </b></a> / Danh sách hỗ trợ -->
    </div>
</div>
<div class="box box-info">
    <div class="row" id="messageValidate" style="padding-left: 10%;padding-right: 10%"></div> 
    <div class="box-body">
      <div class="form-group">
        <div class="col-sm-4">
          <label  class="col-sm-12 ">Trường</label>
          <div class="col-sm-12">
            <select name="sltTruong" id="sltTruong"  class="form-control selectpicker"  <?php if(count(explode('-',Auth::user()->truong_id)) > 1): ?> data-live-search="true" <?php endif; ?> style="width: 100% !important">
              <?php
              if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                $qlhs_schools = DB::table('qlhs_schools')->where('schools_active',1)->whereIn('schools_id',explode('-',Auth::user()->truong_id))->select('schools_id','schools_name')->get();
                if(count(explode('-',Auth::user()->truong_id)) > 1){
                  ?>
                  <option value="" selected="selected">-- Chọn trường học --</option>
                  <?php }
                      }else{
                        $qlhs_schools = DB::table('qlhs_schools')->where('schools_active',1)->select('schools_id','schools_name')->get();
                             ?>
                        <option value="" selected="selected">-- Chọn trường học --</option>
                  <?php } ?>
                    <?php $__currentLoopData = $qlhs_schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val->schools_id); ?>" <?php if($val->schools_id == Auth::user()->truong_id): ?> selected <?php endif; ?>><?php echo e($val->schools_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
        </div>
        <div class="col-sm-2">
          <label  class="col-sm-12">Lớp học </label>
          <div class="col-sm-12">
            <select name="sltLop" disabled="disabled" id="sltLop" class="form-control selectpicker"  data-live-search="true">
              <option value="">--Chọn lớp--</option>
            </select>
          </div>
        </div>
        <div class="col-sm-2">
          <label  class="col-sm-12">Năm học</label>
          <div class="col-sm-12">
            <select name='sltYear' class="selectpicker form-control" id='sltYear'>
              <?php
                  $namhoc = DB::table('qlhs_years')
                            ->orderBy('code','asc')->get();
              ?>
                <option value="" selected="">-- Chọn năm học --</option>
              <?php $__currentLoopData = $namhoc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($nam->code); ?>"><?php echo e($nam->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
        </div>
        <div class="col-sm-4">
          <label  class="col-sm-12">Chọn chế độ chi trả</label>
          <div class="col-sm-12">
            <select name="sltLoaiChedo" id="sltLoaiChedo" class="form-control selectpicker">
              <option value="" selected>--- Chọn chế độ ---</option>
              <option value="1">Miễn giảm học phí</option>
              <option value="2">Chi phí học tập</option>
              <option value="3">Hỗ trợ ăn trưa trẻ em mẫu giáo</option>
              <option value="4">Hỗ trợ học sinh bán trú - tiền ăn</option>
              <option value="5">Hỗ trợ học sinh bán trú - tiền ở</option>
              <option value="6">Hỗ trợ học sinh bán trú - VHTT</option>
              <option value="7">Hỗ trợ học sinh khuyết tật - học bổng</option>
              <option value="8">Hỗ trợ học sinh khuyết tật - DD học tập</option>
              <option value="10">Hỗ trợ ăn trưa học sinh theo NQ57</option>
              <option value="9">Hỗ trợ học sinh dân tộc thiểu số</option>
              <option value="11">Hỗ trợ học bổng cho học sinh dân tộc nội trú</option>
            </select>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-4">
          <label  class="col-sm-12">Số công văn</label>
          <div class="col-sm-12">
            <select class="form-control selectpicker" id="sltCongVan" disabled="disabled">
              <option value="">-- Chọn công văn chi trả --</option>
            </select>
          </div>
        </div>
        <div class="col-sm-4">
          <label  class="col-sm-12">Tổng chi phí / chi trả</label>
          <div class="col-sm-12">
            <input type="text" name="txtTotal" id="txtTotal" class="form-control" disabled="disabled" readonly>
          </div>
        </div>

        <div class="col-sm-4">
          <label  class="col-sm-12">Thực hiện</label>
          <div class="col-sm-12">
            <button class="btn btn-success" type="button" id="btnView">Xem danh sách</button>
            <button class="btn btn-success" type="button" id="btnChiTra">Danh sách chi trả</button>
            <?php if(Auth::user()->level == 1): ?>
              <button class="btn btn-success" type="button" id="btnPopup">Chi trả</button>
            <?php endif; ?>
            
          </div>
        </div>

      </div>
    </div>
</div>
<div class="box box-primary">
  <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
    <table class="table table-striped table-bordered table-hover dataTable no-footer">
      <thead id="headChiTra">
        
      </thead>
      <tbody id="dataChiTra"> 

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
                        <select  id="viewTable"  class="selectpicker form-control input-sm pagination-show-row">
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
</section>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
  $('#btnUpdateDieuChinh').click(function(){
      var id = $('#txtIDDieuChinh').val();
      var expense = $('#update_expense').val().replaceAlls('.','');
      var type = $('#update_using').val();
      var times = $('#update_times').val();
      GetFromServer('/du-toan-chi-tra/cap-nhat-chi-tra/update?id='+id+'&expense='+expense+'&type='+type+'&times='+times,function(data){
          load_chi_tra_done(null);
          $('#myModalDieuChinh').modal('hide');
          if (data['success'] != null && data['success'] != undefined) {
                utility.message("Thông báo",data['success'],null,3000,0);
                $(":file").filestyle('clear');
          }else if (data['error'] != null && data['error'] != undefined) {
                utility.message("Thông báo",data['error'],null,3000,1);
          }
      },function(data){
          console.log("btnUpdateDieuChinh");
          console.log(data);
      },"","","");
  });
  $('#btnChiTra').click(function(){
      load_chi_tra_done(null);
  });

  $('#LuuCapKinhPhi').click(function(){
      themmoichitra();
  });
  $('#btnPopup').click(function(){
        if($('#sltTruong').val() == ''){
            utility.messagehide("messageValidate", "Xin mời chọn trường", 1, 5000);
            $('#sltTruong').focus();
            return;
        }
        //user_id
        if($('#sltYear').val() == ''){
            utility.messagehide("messageValidate", "Xin mời chọn năm", 1, 5000);
            $('#sltYear').focus();
            return;
        }
        $('.title').html($('#sltTruong option:selected').text()+' - '+ $('#sltYear').val());
        $('#expense').val('');
        $('#txtId').val('');
        $('#using').selectpicker('val',0).selectpicker('refresh');
        $('#note').val('');
        $(":file").filestyle('clear');
        $('#myModalDetail').modal('show');
    });
  loadComboxLop($('#sltTruong').val(),'sltLop',function(){
    $('select#sltLop').removeAttr('disabled');
    $('select#sltLop').selectpicker('refresh');
  });
  $('#btnView').click(function(){
      if($('#sltTruong').val() == ""){
          utility.messagehide("messageValidate", "Xin mời chọn trường", 1, 5000);
            $('#sltTruong').focus();
      }else{
          if($('#sltLop').val() == ""){
              utility.messagehide("messageValidate", "Xin mời chọn lớp học", 1, 5000);
                $('#sltLop').focus();
          }else{
              if($('#sltYear').val() == ""){
                  utility.messagehide("messageValidate", "Xin mời chọn năm học", 1, 5000);
                    $('#sltYear').focus();
              }else{
                  if($('#sltCongVan').val() == ""){
                    utility.messagehide("messageValidate", "Xin mời chọn công văn", 1, 5000);
                      $('#sltCongVan').focus();
                }else{
                    load_chi_tra($('#sltCongVan').val());
                }
              }
          }
      }
  });
  $('#sltCongVan').change(function(){
      if($('#sltTruong').val() == ""){
          utility.messagehide("messageValidate", "Xin mời chọn trường", 1, 5000);
            $('#sltTruong').focus();
      }else{
          if($('#sltLop').val() == ""){
              utility.messagehide("messageValidate", "Xin mời chọn lớp học", 1, 5000);
                $('#sltLop').focus();
          }else{
              if($('#sltYear').val() == ""){
                  utility.messagehide("messageValidate", "Xin mời chọn năm học", 1, 5000);
                    $('#sltYear').focus();
              }else{
                  load_chi_tra($(this).val());
              }
          }
      }
  });
  $('#sltLoaiChedo').change(function(){
      if($('#sltTruong').val() == ""){
          utility.messagehide("messageValidate", "Xin mời chọn trường", 1, 5000);
            $('#sltTruong').focus();
      }else{
          if($('#sltLop').val() == ""){
              utility.messagehide("messageValidate", "Xin mời chọn lớp học", 1, 5000);
                $('#sltLop').focus();
          }else{
              if($('#sltYear').val() == ""){
                  utility.messagehide("messageValidate", "Xin mời chọn năm học", 1, 5000);
                    $('#sltYear').focus();
              }else{
                  var o = {
                      school_id : $('#sltTruong').val(),
                      class_id : $('#sltLop').val(),
                      years : $('#sltYear').val(),
                      type : $('#sltLoaiChedo').val(),
                  };
                  PostToServer('/du-toan-chi-tra/chi-tra/cong-van',o,function(data){
                      var html_show = "";
                      html_show += '<option value="">-- Chọn công văn chi trả --</option>';
                      if(data.length > 0){
                        for (var i = 0; i < data.length; i++) {
                          var scv = data[i].report_name;//+'.'+data[i].number;
                          html_show += '<option value="'+data[i].report_name+'||'+data[i].report_cap_nhan+'">'+scv+'</option>';
                        }
                        $('#sltCongVan').removeAttr('disabled');
                      }
                      $('#sltCongVan').html(html_show);
                      $('#sltCongVan').selectpicker('val',"").selectpicker('refresh');
    
                  },function(data){
                      console.log("sltLoaiChedo");
                      console.log(data);
                  },"","");
                  
              }
          }
      }
  });

  function load_chi_tra(control){
      var html_show = "";
      var html_header = "";
      html_header += '<tr class="info">';
      html_header += '<th class="text-center" style="vertical-align: middle;width: 5%">STT</th>';
      html_header += '<th class="text-center" style="vertical-align: middle;width: 20%">Họ và tên</th>';
      html_header += '<th class="text-center" style="vertical-align: middle;width: 10%">Lớp học</th>';
      html_header += '<th class="text-center" style="vertical-align: middle;width: 50%">Số tiền</th></tr>';
        
      $('#headChiTra').html(html_header);
      var o = {
          school_id : $('#sltTruong').val(),
          class_id : $('#sltLop').val(),
          years : $('#sltYear').val(),
          type : $('#sltLoaiChedo').val(),
          start: (GET_START_RECORD_NGHILC()),
          limit: $('#viewTable').val(),
          value: control
      };
      PostToServer('/du-toan-chi-tra/chi-tra/load_data',o,function(data){
          SETUP_PAGING_NGHILC(data, function () {
              load_chi_tra(control);
          });
          //
          $('#txtCount').val(data.totalRows);
          var dataget = data.data;
          var total = 0;
          if(dataget.length > 0){
            
            for (var i = 0; i < dataget.length; i++) {
                total +=  parseInt(dataget[i].chiphi);
                html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * $('#viewTable').val()))+"</td>";
                html_show += "<td>"+dataget[i].profile_name+"</td>";  
                html_show += "<td class='text-center'>"+dataget[i].class_name+"</td>";  
                html_show += "<td  class='text-right'>"+formatter(dataget[i].chiphi)+"</td></tr>";
            }
          }else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
          }
          $('#dataChiTra').html(html_show);
          $('#txtTotal').val(formatter(total));
      },function(data){
          console.log("load_chi_tra");
          console.log(data);
      },"","");
  }
  function themmoichitra(){
    var formData = new FormData();
        var len =  $("input[name*='fileQuyetDinh']")[0].files.length;
        for (var x = 0; x < len; x++) {
          formData.append('file[]', $("input[name*='fileQuyetDinh']")[0].files[x]);
        }
        formData.append('expense', $("#expense").val().replaceAlls('.',''));
        formData.append('using', $("#using").val());

        if($("#sltTruong").val() != '' && $("#sltTruong").val() != undefined){
            formData.append('school_id', $("#sltTruong").val());
        }

        if($("#txtId").val() != '' && $("#txtId").val() != undefined){
            formData.append('id', $("#txtId").val());
        }

        formData.append('note', $("#note").val());
        
        formData.append('year', $("#sltYear").val());

        formData.append('class_id', $("#sltLop").val());
        formData.append('type', $("#sltLoaiChedo").val());
        formData.append('start', (GET_START_RECORD_NGHILC()));
        formData.append('limit', $('#viewTable').val());
        formData.append('total', $('#txtCount').val());
        formData.append('times', $('#times').val());
        formData.append('value', $('#sltCongVan').val());
        
        // if(parseInt($("#expense").val().replaceAlls('.','')) == parseInt($("#txtTotal").val().replaceAlls('.',''))){
        //   formData.append('value', 0);
        // }else{
        //   formData.append('value', 1);
        // }
        
        PostToServerFormData('/du-toan-chi-tra/cap-nhat-chi-tra/insert',formData,function(data){
            if (data['success'] != null && data['success'] != undefined) {
                utility.message("Thông báo",data['success'],null,3000,0);
                $(":file").filestyle('clear');
            }else if (data['error'] != null && data['error'] != undefined) {
                utility.message("Thông báo",data['error'],null,3000,1);
            }
            load_chi_tra_done(data['id']);
           
            $('#myModalDetail').modal('hide');
        },function(data){
            console.log('LuuCapKinhPhi.click');
            console.log(data);
        },"","","");
}
function load_chi_tra_done(id){
  GET_INITIAL_NGHILC();
      var html_show = "";
      $('#headChiTra').html("");
            var html_header = "";
            html_header += '<tr class="info">';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 3%">STT</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 15%">Họ và tên</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 8%">Lớp học</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 8%">Chế độ</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 8%">Chi trả lần 1</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 8%">Chi trả lần 2</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 8%">Chi trả lần 3</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 8%">Chi trả lần 4</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 8%">Chi trả lần 5</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 8%">Tổng chi trả</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 10%">Trạng thái</th>';
            html_header += '<th class="text-center" style="vertical-align: middle;width: 10%">Hành động</th></tr>';
              
            $('#headChiTra').html(html_header);
      var o = {
          school_id : $('#sltTruong').val(),
          class_id : $('#sltLop').val(),
          years : $('#sltYear').val(),
          type : $('#sltLoaiChedo').val(),
          start: (GET_START_RECORD_NGHILC()),
          limit: $('#viewTable').val(),
          id: id,
      };
      PostToServer('/du-toan-chi-tra/chi-tra/load_data_ct',o,function(data){
          SETUP_PAGING_NGHILC(data, function () {
              load_chi_tra_done(id);
          });
          //
          $('#txtCount').val(data.totalRows);
          var dataget = data.data;
          var total = 0;
          if(dataget.length > 0){
            
            for (var i = 0; i < dataget.length; i++) {
                total +=  parseInt(dataget[i].expense);
                html_show += "<tr><td class='text-center'>"+(i + 1 + (GET_START_RECORD_NGHILC() * $('#viewTable').val()))+"</td>";
                html_show += "<td>"+dataget[i].profile_name+"</td>";  
                html_show += "<td class='text-center'>"+dataget[i].class_name+"</td>";  
                html_show += "<td  class='text-right'>"+formatter(dataget[i].real_expense)+"</td>";
                html_show += "<td  class='text-right'>"+formatter(dataget[i].expense_1)+"</td>";
                html_show += "<td  class='text-right'>"+formatter(dataget[i].expense_2)+"</td>";
                html_show += "<td  class='text-right'>"+formatter(dataget[i].expense_3)+"</td>";
                html_show += "<td  class='text-right'>"+formatter(dataget[i].expense_4)+"</td>";
                html_show += "<td  class='text-right'>"+formatter(dataget[i].expense_5)+"</td>";
                html_show += "<td  class='text-right'><b>"+formatter(dataget[i].expense)+"</b></td>";
                var val = 0;
                if(parseInt(dataget[i].real_expense) > parseInt(dataget[i].expense)){
                   val = parseInt(dataget[i].real_expense) - parseInt(dataget[i].expense);
                    html_show += "<td  class='text-center' style='color: red'>Chưa đủ chi phí</td>";
                    html_show += '<td class="text-center"><button onclick="capnhatchitra('+dataget[i].id+',\'' + dataget[i].profile_name + '\',2,'+val+');" class="btn btn-info btn-xs" id="editor_editss" title="Cập nhật"><i class="glyphicon glyphicon-edit"></i> </button></td></tr>';
                }else if(parseInt(dataget[i].real_expense) < parseInt(dataget[i].expense)){
                   val = parseInt(dataget[i].expense) - parseInt(dataget[i].real_expense);
                    html_show += "<td  class='text-center' style='color: blue'>Thừa chi phí</td>";
                    html_show += '<td class="text-center"><button onclick="capnhatchitra('+dataget[i].id+',\'' + dataget[i].profile_name + '\',1,'+val+');" class="btn btn-info btn-xs" id="editor_editss" title="Cập nhật"><i class="glyphicon glyphicon-edit"></i> </button></td></tr>';
                }else{
                    html_show += "<td  class='text-center' style='color: green'>Hoàn tất</td>";
                    html_show += '<td class="text-center"><button onclick="capnhatchitra('+dataget[i].id+',\'' + dataget[i].profile_name + '\',0,0);" class="btn btn-info btn-xs" id="editor_editss" title="Cập nhật"><i class="glyphicon glyphicon-edit"></i> </button></td></tr>';
                }
                
               // html_show += "<td>-</td></tr>";  
            }
          }else {
            html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
          }
          $('#dataChiTra').html(html_show);
          $('#txtTotal').val(formatter(total));
      },function(data){
          console.log("load_chi_tra");
          console.log(data);
      },"","");
  }

  function capnhatchitra(id,name,type,val){
      if($('#sltTruong').val() == ''){
            utility.messagehide("messageValidate", "Xin mời chọn trường", 1, 5000);
            $('#sltTruong').focus();
            return;
        }
        //user_id
        if($('#sltYear').val() == ''){
            utility.messagehide("messageValidate", "Xin mời chọn năm", 1, 5000);
            $('#sltYear').focus();
            return;
        }
        $('.title').html(' '+name);
        if(type == 1){
          $('.lblChiTra').html('Thừa: <b>'+formatter(val)+'</b>');  
        }else if(type == 2){
          $('.lblChiTra').html('Thiếu: <b>'+formatter(val)+'</b>');  
        }
        
        $('#update_expense').val('');
        $('#txtIDDieuChinh').val(id);
        $('#update_using').selectpicker('val',0).selectpicker('refresh');
        $('#myModalDieuChinh').modal('show');
  }
</script>



<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>