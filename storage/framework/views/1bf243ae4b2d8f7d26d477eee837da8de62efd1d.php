<?php $__env->startSection('title', 'This is a blank page'); ?>
<?php $__env->startSection('description', 'This is a blank page that needs to be implemented'); ?>

<?php $__env->startSection('content'); ?>

<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('mystyle/js/jsDanhMuc.js'); ?>"></script>
<section class="content">
<script type="text/javascript">
  $(function () {

      $('#txtClassCode').focus();

      // $("#sltKhoiDt").attr("disabled", true);
      $("#drLevel").attr("disabled", true);

      getUnitAll();
      // $("#sltTruongDt").change(function(){
      //   $("#sltKhoiDt").attr("disabled", false);
      //   getUnitbySchoolID($(this).val());
      // });

      $("#sltKhoiDt").change(function(){
        $("#drLevel").attr("disabled", false);
        getLevelbyUnitID($(this).val());
      });

      module = 68;
      permission(function(){
        var html_view  = '<b> Danh mục </b> / Lớp';
        
        if(check_Permission_Feature('1')){
            html_view += '<a data-toggle="modal" data-target="#myModal" style="margin-left: 10px" class="btn btn-success btn-xs pull-right" onclick="popupAddnewClass()" > <i class="glyphicon glyphicon-plus"></i> Tạo mới </a > ';
        }
        // if(check_Permission_Feature('4')){
        //     html_view += '<a class="btn btn-success btn-xs pull-right" href="#" onclick="exportExcelClass()"> <i class="glyphicon glyphicon-print"></i> Xuất excel </a>';
        // }
        $('#addnew-export-class').html(html_view);
      });

      GET_INITIAL_NGHILC();
      loaddataClass($('#drPagingClass').val(), $('#txtSearchClass').val());

      $('#drPagingClass').change(function() {
        GET_INITIAL_NGHILC();
        loaddataClass($(this).val(), $('#txtSearchClass').val());
      });

     //loadComboxTruongHocSingle('sltTruongDt', function(){}, $('#school-per').val());

      autocompleteSearch("txtSearchClass", "CLASS");

      // $("#sltTruongDt").select2({
      //   placeholder: "-- Chọn trường --",
      //   allowClear: true,
      //   focus: open
      // });
  });

    function popupAddnewClass(){
      $('.modal-title').html('Thêm mới lớp');
      // $('#txtClassCode').attr('readonly', false);
      // $("#sltKhoiDt").attr("disabled", true);
      $("#drLevel").attr("disabled", true);
      $("#drLevel").selectpicker('refresh');
      class_id = "";
      $('#txtClassCode').val("");
      $('#txtClassName').val("");
      $("#sltTruongDt").selectpicker('refresh');
     // loadComboxTruongHocSingle('sltTruongDt', function(){}, $('#school-per').val());
      $('#sltKhoiDt').selectpicker('refresh');
      $('#drLevel').selectpicker('refresh');
      $('#drClassActive').val(1).selectpicker('refresh');
      $('#sltDiemTruong').val('').selectpicker('refresh');
      //$('#modalAddNew').modal('show').find('input:text')[0].focus();
      $('#modalAddNew').on('shown.bs.modal', function (e) {
          $('#txtClassName').focus();
      }).modal('show');

    }
    function popupUpdateClass(){
      $('.modal-title').html('Sửa lớp');
      // $('#txtClassCode').attr('readonly', true);
      $("#sltKhoiDt").attr("disabled", false);
      $("#drLevel").attr("disabled", false);
      $('#btnInsertClass').html('Lưu');
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
          <h4 class="modal-title">Xóa lớp</h4>
        </div>
        <div class="modal-footer">
          <div class="row text-center">
            <h2>Bạn có thực sự muốn xóa không?</h2>
            <input type="button" value="Xác nhận" id="btnConfirmDeleteClass" class="btn btn-primary">
            <input type="button" value="Hủy" id="btnCancelDelete" class="btn btn-primary" data-dismiss="modal">
          </div>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="modalAddNew" role="dialog">
    <div class="modal-dialog modal-md" style="width: 80%;margin: 10px auto;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thêm mới lớp</h4>
        </div>
        <form class="form-horizontal" action="">  
        <input type="hidden" class="form-control" id="txtIdRoleGroup">          
                <div class="modal-body">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                <div class="form-group">
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Mã lớp<font style="color: red">*</font></label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="txtClassCode" placeholder="Nhập mã lớp" autofocus="true">
                  </div> -->

                  <label for="txtClassName" class="col-sm-2 control-label">Tên lớp<font style="color: red">*</font></label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="txtClassName" placeholder="Nhập tên lớp">
                  </div>

                  <label for="sltTruongDt" class="col-sm-2 control-label">Chọn trường<font style="color: red">*</font></label>
                  <div class="col-sm-3">
                    <select name='sltTruongDt' class=" form-control selectpicker" data-live-search="true" id='sltTruongDt' style="width: 100% !important">
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

                <div class="form-group">
                  <label for="sltKhoiDt" class="col-sm-2 control-label">Chọn khối<font style="color: red">*</font></label>
                  <div class="col-sm-3">
                    <select name='sltKhoiDt' class="form-control" id='sltKhoiDt' class=" form-control selectpicker" data-live-search="true">
                      
                    </select>
                  </div>

                  <label for="drLevel" class="col-sm-2 control-label">Chọn khối lớp<font style="color: red">*</font></label>
                  <div class="col-sm-3">
                    <select id="drLevel" class="form-control" class=" form-control selectpicker" data-live-search="true">
                   
                    </select>
                  </div>
                </div>

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Trạng thái</label>
                  <div class="col-sm-3">
                   <select id="drClassActive" class=" form-control selectpicker" data-live-search="true">
                      <option value="1" selected="selected">Kích hoạt</option>
                      <option value="0">Chưa kích hoạt</option>
                    </select>
                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Điểm trường</label>
                  <div class="col-sm-3">
                   <select id="sltDiemTruong" class=" form-control selectpicker" data-live-search="true">
                      <option value="" selected="selected">Chọn điểm trường</option>
                      <?php 
                          if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                            $warehouses = DB::table('warehouses')
                            ->whereIn('school_id',explode('-',Auth::user()->truong_id))
                            ->select('id','name')->get();
                          }else{
                            $warehouses = DB::table('warehouses')
                            ->select('id','name')->get();
                          }
                      ?>
                      <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($value->id); ?>"><?php echo e($value->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
              </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnInsertClass">Lưu</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnCloseClass">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-class">
       
        
        
    </div>
    </div>
          <div class="box">
                <div class="form-group " style="margin-top: 10px;margin-bottom: 0px">
                       <div class="box-header with-border">
              <h3 class="box-title"></h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input id="txtSearchClass" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
                    <!--  <div class="col-sm-6">
                      <label  class="col-sm-4 control-label">Tìm kiếm</label>
                      <div class="col-sm-8">
                        <input type="text" id="txtSearchClass" class="form-control" style=" width: 70%; height: 30px; margin-bottom: 10px;">
                      </div>
                    </div> -->
                </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle">STT</th>
                  <th class="text-center" style="vertical-align:middle">Tên trường</th>
                  <th class="text-center" style="vertical-align:middle">Tên khối</th>
                  <th class="text-center" style="vertical-align:middle">Khối lớp</th>
                  <th class="text-center" style="vertical-align:middle">Tên lớp</th>
                  <th class="text-center" style="vertical-align:middle">Điểm trường</th>
                  <th class="text-center" style="vertical-align:middle">Trạng thái</th>
                  <th class="text-center" style="vertical-align:middle">Ngày sửa</th>
                  <th class="text-center" style="vertical-align:middle">Chức năng</th>
                </tr>
                </thead>
                <tbody id="dataClass">
                
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
                        <select name="drPagingClass" id="drPagingClass"  class="selectpicker form-control input-sm pagination-show-row">
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