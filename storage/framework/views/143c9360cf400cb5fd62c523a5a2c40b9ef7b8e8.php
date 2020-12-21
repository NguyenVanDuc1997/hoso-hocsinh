

<?php $__env->startSection('title', 'This is a blank page'); ?>
<?php $__env->startSection('description', 'This is a blank page that needs to be implemented'); ?>

<?php $__env->startSection('content'); ?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('mystyle/js/jsDanhMuc.js'); ?>"></script>
<section class="content">
  <style type="text/css">
    input[type="checkbox"].css-checkbox + label.css-label {
      width: 100%;
    }
  </style>
<script type="text/javascript">

$(function () {
    $('#txtStartDate').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true
    });
      // $('#txtSchoolCode').focus();
      module = 25;
      permission(function(){
        var html_view  = '<b> Danh mục </b> / Trường';
        
        if(check_Permission_Feature('1')){
          html_view += '<a data-toggle="modal" data-target="#myModal" style="margin-left: 10px" class="btn btn-success btn-xs pull-right" onclick="popupAddnewSchool()"> <i class="glyphicon glyphicon-plus"></i> Tạo mới </a>';
        }
        // if(check_Permission_Feature('4')){
        //   var formName = "SCHOOL";
        //   html_view += '<a class="btn btn-success btn-xs pull-right" href="#" onclick="exportExcelSchool()"> <i class="glyphicon glyphicon-print"></i> Xuất excel </a>';
        // }
        $('#addnew-export-school').html(html_view);
      });
      
      GET_INITIAL_NGHILC();
      loaddataSchool($('#drPagingSchool').val(), $('#txtSearchSchool').val());

     // loadSchoolType();

      $('#drPagingSchool').change(function() {
        GET_INITIAL_NGHILC();
        loaddataSchool($(this).val(), $('#txtSearchSchool').val());
      });

      autocompleteSearch("txtSearchSchool", "SCHOOL");
  });
  function popupAddnewSchool(){
    $('.modal-title').html('Thêm mới trường');
    // $('#txtSchoolCode').attr('readonly', false);
    $('#txtStartDate').removeAttr('disabled');
    school_id = "";
    update_type = 0;
    school_his_id = 0;
    // $('#txtSchoolCode').val("");
    $('#txtSchoolName').val("");
    $('#cbxMCCOrTT').prop('checked', false);
    $('#drSchoolType').selectpicker('val','');
    $('#txtStartDate').val("");
    $('#drSchoolActive').selectpicker('val','1');
    $("#modalAddNew").modal("show");
  }
  function popupUpdateSchool(){
    
    // $('#txtSchoolCode').attr('readonly', true);
    $("#modalAddNew").modal("show");
  }

    function popupConfirmDelete(){
      $("#modalDelete").modal("show");
    }

   function testPhanquyen(){

    $("#myModalPhanQuyen").modal("show");
   }
</script>

  <div class="modal fade" id="modalDelete" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Xóa trường</h4>
        </div>
        <div class="modal-footer">
          <div class="row text-center">
            <h2>Bạn có thực sự muốn xóa không?</h2>
            <input type="button" value="Xác nhận" id="btnConfirmDeleteSchool" class="btn btn-primary">
            <input type="button" value="Hủy" id="btnCancelDelete" class="btn btn-primary" data-dismiss="modal">
          </div>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="modalAddNew" role="dialog">
    <div class="modal-dialog modal-md" style="width: 70%;margin: 10px auto;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thêm mới trường</h4>
        </div>
        <form class="form-horizontal" action="">  
        <input type="hidden" class="form-control" id="txtIdRoleGroup">          
                <div class="modal-body">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                <div class="form-group">
                  <label for="txtSchoolName" class="col-sm-2 control-label">Tên trường<font style="color: red">*</font></label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="txtSchoolName" placeholder="Nhập tên trường">
                  </div>

                  <label for="drSchoolType" class="col-sm-2 control-label">Chọn loại trường<font style="color: red">*</font></label>
                  <div class="col-sm-3">
                    <select id="drSchoolType" class="form-control selectpicker" data-live-search="true">
                      <option value="" selected="selected">--- Chọn loại trường ---</option>
                      <?php 
                       $getData = DB::table('qlhs_school_type')->orderBy('school_type_name', 'asc')->get();
                      ?>
                      <?php $__currentLoopData = $getData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val->school_type_id); ?>"><?php echo e($val->school_type_name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="txtStartDate" class="col-sm-2 control-label">Trường liên cấp</label>

                  <div class="col-sm-3">
                     <select id="sltUnit" style="width: 100%;" multiple="multiple"  class="selectpicker form-control" data-actions-box="true" data-live-search="true" multiple data-selected-text-format="count">
                        <?php 
                          $qlhs_unit = DB::table('qlhs_unit')->select('unit_id','unit_name')->get();
                         ?>
                         <?php $__currentLoopData = $qlhs_unit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val->unit_id); ?>"><?php echo e($val->unit_name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                  </div>

                  <label for="txtStartDate" class="col-sm-2 control-label">Ngày hiệu lực<font style="color: red">*</font></label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="txtStartDate" placeholder="Ngày-tháng-năm">
                  </div>
                  
                </div>
                <div class="form-group">
                     <div class="col-sm-5">
                     <div class="checkbox custom">
                        <input id="cbxMCCOrTT" class="css-checkbox" type="checkbox">
                        <label for="cbxMCCOrTT" class="css-label">Mù Căng Chải hoặc Trạm Tấu</label>
                      </div>
                  </div> 
                  <label for="drSchoolActive" class="col-sm-2 control-label">Trạng thái</label>
                  <div class="col-sm-3">
                   <select id="drSchoolActive" class="form-control selectpicker" data-live-search="true">
                      <option value="1" selected="selected">Kích hoạt</option>
                      <option value="0">Chưa kích hoạt</option>
                    </select>
                  </div>  
                </div>
              </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnInsertSchool">Thêm mới</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnCloseSchool">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-school">
       
        
        
    </div>
    </div>
          <div class="box">
                <div class="form-group " style="margin-top: 10px;margin-bottom: 0px">
                      <div class="box-header with-border">
              <h3 class="box-title"></h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input id="txtSearchSchool" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>

                </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle">STT</th>
                  <!-- <th class="text-center" style="vertical-align:middle">Mã trường</th> -->
                  <th class="text-center" style="vertical-align:middle">Tên trường</th>
                  <!-- <th class="text-center" style="vertical-align:middle">Tên khối</th> -->
                  <th class="text-center" style="vertical-align:middle">Loại trường</th>
                  <th class="text-center" style="vertical-align:middle">Ngày bắt đầu</th>
                  <th class="text-center" style="vertical-align:middle">Ngày kết thúc</th>
                  <th class="text-center" style="vertical-align:middle">Trạng thái</th>
                  <th class="text-center" style="vertical-align:middle">Ngày sửa</th>
                  <th class="text-center" style="vertical-align:middle" colspan="3">Chức năng</th>
                </tr>
                </thead>
                <tbody id="dataSchool">
                
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
            <div class="col-md-6">
                <select class="form-control input-sm g_selectPaging selectpicker">
                    <option value="0">0 / 20 </option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
                      <label  class="col-md-6 control-label">Hiển thị: </label>
                      <div class="col-md-6">
                        <select name="drPagingSchool" id="drPagingSchool"  class="selectpicker form-control input-sm pagination-show-row">
                          <option value="10">10</option>
                          <option value="15">15</option>
                          <option value="20">20</option>
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
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>