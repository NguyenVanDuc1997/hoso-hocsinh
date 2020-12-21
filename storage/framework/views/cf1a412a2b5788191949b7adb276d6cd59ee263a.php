

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
<!-- <script src="<?php echo asset('/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script> -->

<script src="<?php echo asset('js/select2.min.js'); ?>"></script>
<script src="<?php echo asset('js/toastr.js'); ?>"></script>
<script src="<?php echo asset('js/utility.js'); ?>"></script>
<script src="<?php echo asset('/mystyle/js/styleProfile.js'); ?>"></script>

<section class="content">
<script type="text/javascript">
  $(function () {
     setSinglePickerFormat('txtStartTime',moment().subtract(2, 'day').format("DD-MM-YYYY"),'left','DD-MM-YYYY');
     setSinglePickerFormat('txtEndTime',moment().format("DD-MM-YYYY"),'left','DD-MM-YYYY');
    
    // $('#txtEndTime, #txtStartTime').datepicker({
    //   format: 'dd-mm-yyyy',
    //   autoclose: true,
    //   setDate: new Date() 
    // });
    //$('#txtEndTime, #txtStartTime').datepicker("setDate", new Date());
    loadComboxTruongHocSingle("sltSchool", function(){
      $("#sltSchool").selectpicker(); 
      loadListMessage();
    },$('#school-per').val());
      
      $('#sltSchool').change(function(){
          loadListMessage();
      });
      
      $('#btnViewMessage').click(function(){
         loadListMessage();
      });  

      $('#sltTrangThai').change(function(){
          loadListMessage();
      });
  });
</script>


<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-profile">
       <a href="/"><b> Thông báo </b></a> / Danh sách thông báo
    </div>
</div>
    
            <div class="box box-primary">

                <div class="box box-primary form-horizontal" style="font-size: 12px;">
                      <div class="form-group " style="margin:10px">
                    <div class="col-sm-3">
                      <label  class="col-sm-5">Thời gian</label>
                      <div class="col-sm-12">
                      <input type="text" name="txtStartTime" id="txtStartTime" class="form-control">

                      </div>
                    </div>
                    <div class="col-sm-3">
                      <label  class="col-sm-5">Kết thúc</label>
                      <div class="col-sm-12">
                        <input type="text" name="txtEndTime" id="txtEndTime" class="form-control">
                      </div>
                    </div>
                    <div class="col-sm-3">
                      <label  class="col-sm-5">Trường</label>
                      <div class="col-sm-12">
                       <select name="sltSchool" id="sltSchool"  class="form-control selectpicker"></select>
                      </div>
                    </div>
                     
               <div class="col-sm-3">
                      <label  class="col-sm-5">Trạng thái</label>
                      <div class="col-sm-12">
                        <select name="sltTrangThai" id="sltTrangThai" class="form-control selectpicker">
                          <option value="0" selected="selected">Tất cả</option>
                          <option value="1">Đang thông báo</option>
                          <option value="2">Hết thông báo</option>
                        </select>
                      </div>
                    </div>
                   
                     
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang tìm kiếm" class="btn btn-primary" id ="btnViewMessage"><span class="glyphicon glyphicon-search"></span> Tìm kiếm</button>

                    </div>
                </div>
                  <div class="box-body" style="font-size: 12px">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr class="success">
                          <th  class="text-center" style="vertical-align:middle;width: 5%">STT</th>
                          <th  class="text-center" style="vertical-align:middle;width: 20%">Trường học</th>
                          <th  class="text-center" style="vertical-align:middle;width: 30%">Nội dung</th>
                          
                          <th  class="text-center" style="vertical-align:middle;width: 10%">Số công văn</th>
                          <th  class="text-center" style="vertical-align:middle;width: 15%">Thời gian</th>
                          <th  class="text-center" style="vertical-align:middle;width: 10%">Trạng thái</th>                         
                          <th  class="text-center" style="vertical-align:middle;width: 10%">Chức năng</th>
                        </tr>
                 
                      </thead>
                        <tbody id="dataMessage">                     
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
                                  <select class="form-control input-sm g_selectPaging">
                                      <option value="0">0 / 20 </option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-3">
                                        <label  class="col-md-6 control-label">Hiển thị: </label>
                                        <div class="col-md-6">
                                          <select name="drPagingMessage" id="drPagingMessage"  class="form-control input-sm pagination-show-row">
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
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>