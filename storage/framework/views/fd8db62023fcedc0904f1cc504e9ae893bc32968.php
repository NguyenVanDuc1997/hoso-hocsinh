

<?php $__env->startSection('title', 'This is a blank page'); ?>
<?php $__env->startSection('description', 'This is a blank page that needs to be implemented'); ?>

<?php $__env->startSection('content'); ?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<section class="content">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('mystyle/js/jsDanhMuc.js'); ?>"></script>
<style type="text/css">
    #using_json_2 a {
    white-space: normal !important;
    height: auto;
    padding: 1px 2px;
    font-size: 12px;
    width: 90%;
}
</style>
<script type="text/javascript">
$(function () {
    //loadComboWard(null);
    // $('#txtWardCode').focus();
    $('#using_json_2').jstree({
        'core' : {
            'data' : 
            {
                'url' : function (node) {
                    //console.log(node);
                return node.id === '#' ?
                    '/danh-muc/phanloaixa/deptwards' :
                    '/danh-muc/phanloaixa/childwards';
                },
                'data' : function (node) {
                    //alert(node.id);
                    //console.log(node);
                  return { 'id' : node.id };
                }
            }
        }
    });

    $('#using_json_2').on("changed.jstree", function (e, data) {
            var currentIdSelected = data.selected[0];
            
            var v_objJson = JSON.stringify({ WARDID: currentIdSelected });

            PostToServer('/danh-muc/phanloaixa/getWardbyID',{ WARDID: currentIdSelected },function(results){
              var level = 0;
                var item = '';
                $('#txtWardID').val(results[0]['wards_id']);
                // $('#txtWardCode').val(results[0]['wards_code']);
                $('#txtWardName').val(results[0]['wards_name']);
                $('#drWardActive').selectpicker('val',results[0]['wards_active']);

               // loadComboWard( function(data){
                    $('#drWardParent').selectpicker('val',results[0]['wards_parent_id']);
               // });

                $('#btnInsertWard').html('Lưu');
                $("#btnDeleteWard").attr("disabled", false);
            },function(results){
              console.log("phan loai xa");
              console.log(results);
            },"btnInsertWard","","");
    });
  
});
</script>



<div class="panel panel-default">
    <div class="panel-heading">
       <b> Danh mục </b> / Phân loại xã
        <!-- <a data-toggle="modal" data-target="#myModal" style="margin-left: 10px" class="btn btn-success btn-xs pull-right"  >
            <i class="glyphicon glyphicon-plus"></i> Tạo mới
        </a >  -->
        <!-- <a class="btn btn-success btn-xs pull-right" href="#" onclick="exportExcel('WARD')">
            <i class="glyphicon glyphicon-print"></i> Xuất excel
        </a> -->
    </div>
    </div>
          <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-5">
          <!-- general form elements -->
          <div id="changeTree" class="right-side strech row box box-primary" style="overflow: auto ; max-height: 600px">
            <div class="box-header with-border">
              <h3 class="box-title">Phân loại xã</h3>
            </div>
            <!-- /.box-header -->
            
   <div id="using_json_2" class="jstree jstree-1 jstree-default">
        
    </div>

          </div>
          <!-- /.box -->
        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-7">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Thông tin</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
            <input type="hidden" id="txtWardID" name="">
            <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>
              <div class="box-body">
                <!-- <div class="form-group">
                  <label class="col-sm-3 control-label">Mã loại xã<font style="color: red">*</font></label>

                  <div class="col-sm-9">
                      <input type="text" name="" id="txtWardCode" class="form-control" placeholder="Mã loại xã" accept="charset" autofocus="true">
                  </div>
                </div> -->
                <div class="form-group">
                  <label class="col-sm-3 control-label">Tên loại xã<font style="color: red">*</font></label>
                  <div class="col-sm-9">
                      <input type="text" name="" id="txtWardName" class="form-control" placeholder="Tên loại xã" accept="charset">
                    <!-- <img src="../../images/Image_valid.png" id="imgValidCode"><label id="lblValidCode" class="valid"></label> -->
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Trực thuộc</label>
                  <div class="col-sm-9">
                    <select id="drWardParent"  class="form-control selectpicker" data-live-search="true">
                      <option value="0" selected="selected">-- ROOT --</option>
                      <?php 
                        $wards = DB::table('qlhs_wards')->select('wards_id', 'wards_name', 'wards_level','wards_parent_id')->get();
                      ?>
                      <?php $__currentLoopData = $wards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val->wards_id); ?>"><?php echo e($val->wards_name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <!-- <img src="../../images/Image_valid.png" id="imgValidName"><label id="lblValidName" class="valid"></label> -->
                  </div>
                </div>
                 <div class="form-group">
                  <label class="col-sm-3 control-label">Trạng thái</label>

                  <div class="col-sm-9">
                    <select id="drWardActive"  class="form-control selectpicker" data-live-search="true">
                        <option value="1" selected="selected">Kích hoạt</option>
                        <option value="0">Chưa kích hoạt</option>
                    </select>
                  </div>
                </div>
              </div>

                 <div class="modal-footer">
                        <div class="row text-center">
                            <button type="button" class="btn btn-primary"  data-loading-text="Đang tải dữ liệu" id="btnInsertWard">Lưu</button>
                            <button type="button" class="btn btn-primary" id="btnDeleteWard" disabled="true">Xóa</button>
                            <button type="button" class="btn btn-primary" id="btnResetWard" >Làm mới</button>
                        </div>
                    </div>
            </form>
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