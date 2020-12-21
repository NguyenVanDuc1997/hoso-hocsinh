

<?php $__env->startSection('title', 'This is a blank page'); ?>
<?php $__env->startSection('description', 'This is a blank page that needs to be implemented'); ?>

<?php $__env->startSection('content'); ?>
<!-- <link rel="stylesheet" href="<?php echo asset('css/select2.min.css'); ?>"> -->

<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('mystyle/js/jsDanhMuc.js'); ?>"></script>
<section class="content">
<script type="text/javascript">
 	$(function () {

 		// $('#txtBirthday').datepicker({
	  //     	format: 'yyyy',
	  //     	autoclose: true
	  //   });
 		// var $dateDDMMYYYY = $('#txtBirthday').datepicker({
	  //     	format: 'dd-mm-yyyy',
	  //     	autoclose: true
	  //   });
    
      	permission(function(){
	        var html_view  = '<b> Danh mục </b> / Quản lý danh sách hộ nghèo';
	        
	        if(check_Permission_Feature('1')){
	            html_view += '<a data-toggle="modal" data-target="#myModal" style="margin-left: 10px" class="btn btn-success btn-xs pull-right" onclick="popupAddnewDSHN()" ><i class="glyphicon glyphicon-plus"></i> Tạo mới </a >';
	        }

	        // if(check_Permission_Feature('4')){
	        //     html_view += '<a class="btn btn-success btn-xs pull-right" href="#" onclick=""> <i class="glyphicon glyphicon-print"></i> Nhập excel </a>';
	        // }
	        $('#addnew-dshn').html(html_view);
      	});

      	$('#drPagingDSHN').change(function() {
	        GET_INITIAL_NGHILC();
	        loaddataDSHN($(this).val(), $('#txtSearchDSHN').val());
      	});

      	GET_INITIAL_NGHILC();
      	loaddataDSHN($('#drPagingDSHN').val(), $('#txtSearchDSHN').val());

      	autocompleteSearch("txtSearchDSHN", "DSHN");

     //  	$("#sltNations").select2({
	    //   	placeholder: "-- Chọn dân tộc --",
	    //   	allowClear: true,
	    //   	focus: open
	    // });

     //  	$("#sltSex").select2({
	    //   	placeholder: "-- Chọn giới tính --",
	    //   	allowClear: true,
	    //   	focus: open
	    // });

     //  	$("#sltRelationShip").select2({
	    //   	placeholder: "-- Chọn quan hệ --",
	    //   	allowClear: true,
	    //   	focus: open
	    // });

     //  	$("#sltSite2").select2({
	    //   	placeholder: "-- Chọn thôn --",
	    //   	allowClear: true,
	    //   	focus: open
	    // });

     //  	$("#sltSite1").select2({
	    //   	placeholder: "-- Chọn xã --",
	    //   	allowClear: true,
	    //   	focus: open
	    // });

     //  	$("#sltTypeName").select2({
	    //   	placeholder: "-- Chọn diện --",
	    //   	allowClear: true,
	    //   	focus: open
	    // });


      	//loadDataDantoc();
      	//loadDataSite();

      	$('#sltSite1').change(function() {
	        loadDataSiteByID($(this).val());
      	});
  	});

    function popupAddnewDSHN(){
      	$('.modal-title').html('Thêm mới danh sách hộ nghèo');
      	$('#btnInsertDSHN').html('Thêm mới');
      	$('#txtName').val('');
        $('#txtBirthday').val('');
        $("#sltSex").selectpicker('val','');
        // $("#sltSex").html('<option value="">-- Chọn giới tính --</option>');

        $("#sltNations").selectpicker('val','');
        $("#sltSite2").selectpicker('val','');
        
        // $("#sltNations").html('<option value="">-- Chọn dân tộc --</option>');

       // $("#sltTypeName").selectpicker('val','');
       // $("#sltTypeName option").removeAttr('selected');
        // $("#sltTypeName").html('<option value="">-- Chọn diện --</option>');

        $("#sltSite2").attr('disabled', 'disabled').selectpicker('refresh');

        // $("#sltSite2").html('<option value="">-- Chọn thôn --</option>');

        $("#sltSite1").selectpicker('val','');
      //  $("#sltSite1 option").removeAttr('selected');
        // $("#sltSite1").html('<option value="">-- Chọn xã --</option>');

        $('#txtIndex').val('');

        $("#sltRelationShip").selectpicker('val','1');
        $("#sltTypeName").selectpicker('val','1');
      	$("#modalAddNewDSHN").modal("show");
    }

    function popupUpdateDSHN(){
      	$('.modal-title').html('Sửa danh sách hộ nghèo');
      	$('#btnInsertDSHN').html('Lưu');
      	$("#modalAddNewDSHN").modal("show");
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
		          	<h4 class="modal-title">Xóa số người nấu ăn</h4>
		        </div>
		        <div class="modal-footer">
		          	<div class="row text-center">
			            <h2>Bạn có thực sự muốn xóa không?</h2>
			            <input type="button" value="Xác nhận" id="btnConfirmDeleteDSHN" class="btn btn-primary">
			            <input type="button" value="Hủy" id="btnCancelDelete" class="btn btn-primary" data-dismiss="modal">
		          	</div>
		        </div>
	      	</div>
	    </div>
  	</div>

<div class="modal fade" id="modalAddNewDSHN" role="dialog">
    <div class="modal-dialog modal-md" style="width: 80%;margin: 10px auto;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thêm mới số người nấu ăn</h4>
        </div>
        <form class="form-horizontal" action="">  
        <input type="hidden" class="form-control" id="txtIdRoleGroup">          
                <div class="modal-body">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                <div class="form-group">
                	<div class="col-sm-4">
                      	<label  class="col-sm-6">Họ và tên <font style="color: red">*</font></label>
                      	<div class="col-sm-12">
                        	<input type="text" name="txtName" id="txtName" class="form-control" placeholder="Nhập họ tên">
                      	</div>
                    </div>
                    <div class="col-sm-4">
                      	<label  class="col-sm-6">Năm sinh <font style="color: red">*</font></label>
                      	<div class="col-sm-12">
	                        <input type="text" class="form-control" id="txtBirthday" placeholder="Nhập năm sinh">
                      	</div>
                    </div>
                    <div class="col-sm-4">
                      	<label  class="col-sm-6">Giới tính <font style="color: red">*</font></label>
                      	<div class="col-sm-12">
                        	<select name="sltSex" id="sltSex"  class="form-control selectpicker" data-live-search="true" style="width: 100% !important">
		                        <option value="" selected="selected">-- Chọn giới tính --</option>
		                        <option value="Nam">Nam</option>
		                        <option value="Nữ">Nữ</option>
		                    </select>
                      	</div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                      <label class="col-sm-6">Dân tộc <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                          <select name="sltNations" id="sltNations"  class="form-control selectpicker" data-live-search="true" style="width: 100% !important">
                            <option value="">-- Chọn dân tộc --</option>
                            <?php 
                              $qlhs_nationals = DB::table('qlhs_nationals')->where('nationals_active','=',1)->select('nationals_id','nationals_name')->get();
                            ?>
                             <?php $__currentLoopData = $qlhs_nationals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val->nationals_id); ?>"><?php echo e($val->nationals_name); ?></option>
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
	                    <label class="col-sm-6">Quan hệ <font style="color: red">*</font></label>
	                    <div class="col-sm-12">
                        	<select name="sltRelationShip" id="sltRelationShip"  class="form-control selectpicker" data-live-search="true" style="width: 100% !important">
		                        <option value="" selected="selected">-- Chọn quan hệ --</option>
		                        <option value="1" selected>Chủ hộ</option>
		                    </select>
                      	</div>
                    </div>
                    
                    <div class="col-sm-4">
	                    <label  class="col-sm-6">Thuộc diện <font style="color: red">*</font></label>
	                    <div class="col-sm-12">
                        	<select name="sltTypeName" id="sltTypeName"  class="form-control selectpicker" data-live-search="true" style="width: 100% !important">
		                        <option value="" selected="selected">-- Chọn diện --</option>
		                        <option value="1">Hộ nghèo thu nhập dưới 700</option>
		                        <option value="2">Hộ nghèo thu nhập trên 700</option>
		                        <!-- <option value="3">Thuộc diện khác</option> -->
		                    </select>
                      	</div>
                    </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-4">
                      <label  class="col-sm-6">Xã <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                          <select name="sltSite1" id="sltSite1" class="form-control selectpicker" data-live-search="true" style="width: 100% !important">
                           <option value='' selected="selected">-- Chọn xã --</option>
                          <?php 
                              $rsSite = DB::table('qlhs_site')->where('site_level', '=', 2)->where('site_active', '=', 1)->select('site_name','site_id')->get();
                              
                          ?>
                          <?php $__currentLoopData = $rsSite; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                <optgroup label='<?php echo e($value->site_name); ?>'>
                                    <?php 
                                      $rsXa = DB::table('qlhs_site')->where('site_level', '=', 3)->where('site_parent_id',$value->site_id)->select('site_name','site_id')->where('site_active', '=', 1)->get();
                                    ?>
                                   <?php $__currentLoopData = $rsXa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                      <option value="<?php echo e($val->site_id); ?>"><?php echo e($val->site_name); ?></option>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                      <label  class="col-sm-6">Thôn <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                          <select name="sltSite2" id="sltSite2"  class="form-control selectpicker" data-live-search="true" style="width: 100% !important">
                            <option value='' selected="selected">-- Chọn thôn --</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label  class="col-sm-6">Số thứ tự hộ <font style="color: red">*</font></label>
                        <div class="col-sm-12">
                          <input type="text" name="txtIndex" id="txtIndex" class="form-control" placeholder="Nhập số thứ tự hộ">
                        </div>
                    </div>
                </div>
              </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnInsertDSHN">Thêm mới</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btncloseDSHN">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading" id="addnew-dshn">
       
        
        
    </div>
    </div>
          <div class="box">
                <div class="form-group " style="margin-top: 10px;margin-bottom: 0px;">
                <div class="box-header with-border">
              <h3 class="box-title"></h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input id="txtSearchDSHN" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
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
                  <th class="text-center" style="vertical-align:middle; width: 15%;">Họ và tên</th>
                  <th class="text-center" style="vertical-align:middle; width: 12%;">Ngày sinh</th>
                  <th class="text-center" style="vertical-align:middle">Giới tính</th>
                  <th class="text-center" style="vertical-align:middle">Dân tộc</th>
                  <th class="text-center" style="vertical-align:middle; width: 8%;">Quan hệ</th>
                  <th class="text-center" style="vertical-align:middle; width: 8%;">Số thứ tự hộ</th>
                  <th class="text-center" style="vertical-align:middle; width: 8%;">Thôn</th>
                  <th class="text-center" style="vertical-align:middle; width: 8%;">Xã</th>
                  <th class="text-center" style="vertical-align:middle; width: 15%;">Thuộc diện</th>
                  <th class="text-center" style="vertical-align:middle; width: 12%;">Chức năng</th>
                </tr>
                </thead>
                <tbody id="dataTableDSHN">
                
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
                        <select name="drPagingDSHN" id="drPagingDSHN"  class="selectpicker form-control input-sm pagination-show-row">
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
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>