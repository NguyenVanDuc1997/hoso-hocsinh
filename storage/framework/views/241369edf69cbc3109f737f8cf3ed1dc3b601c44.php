

<?php $__env->startSection('title', 'This is a blank page'); ?>
<?php $__env->startSection('description', 'This is a blank page that needs to be implemented'); ?>

<?php $__env->startSection('content'); ?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>
<section class="content">
<script type="text/javascript">

	function test(){
	 $("#myModal").modal("show");
	 }
   function testPhanquyen(){

    $("#myModalPhanQuyen").modal("show");
   }
</script>

<div class="panel panel-default">
    <div class="panel-heading">
       <b> Quản lý hồ sơ </b> /  Thẩm định danh sách, duyệt cấp kinh phí
    </div>
</div>
    <div class="box">
            <!-- /.box-header -->
        <section class="content">

          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="inbox">
              <div class="info-box" >
                <span class="info-box-icon">
                <img src="../../../../images/Icon/add_Dispatch_Icon.png">
               <!--  <i class="glyphicon glyphicon-leaf"></i> -->
                </span>

                <div class="info-box-content" style="margin-left: 10px">
                  <span class="info-text" style="font-weight: bold;">Văn bản đến</span>
                  <span class="info-number">Hộp thư danh sách thẩm định</span>
                </div>
                <!-- /.info-box-content -->
              </div>
              </a>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="listing">
              <div class="info-box" >
                <span class="info-box-icon">
                <img src="../../../../images/Icon/add_Dispatch_Icon.png">
               <!--  <i class="glyphicon glyphicon-leaf"></i> -->
                </span>

                <div class="info-box-content" style="margin-left: 10px">
                  <span class="info-text" style="font-weight: bold;">Tổng hợp</span ><span class="info-number">Tổng hợp danh sách đã thẩm định</span>
                </div>
                <!-- /.info-box-content -->
              </div>
             </a>
            </div>
        </section>
            
    </div>
          <!-- /.box -->
</div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>