

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
    $('#sltLoaiChedo_fill').change(function(){
      GET_INITIAL_NGHILC();
        danhsachkinhphi($('#sltTruong_fill').val(),$('#sltYear_fill').val(),$('#user_id_fill').val());
    });
    $('#sltUserBy').change(function(){
      GET_INITIAL_NGHILC();
        danhsachkinhphi($('#sltTruong_fill').val(),$('#sltYear_fill').val(),$('#user_id_fill').val());
    });
    $('#LuuCapKinhPhi').click(function(){
      themmoicapkinhphi();
  });
    $('#expense').keyup(function(){
      tmp = this.value.replaceAlls('.','');
      this.value = formatter(tmp);
    });
    $(":file").filestyle({
      buttonText : ' Đính kèm quyết định',
      buttonName : 'btn-info'
    });
    danhsachkinhphi($('#sltTruong_fill').val(),$('#sltYear_fill').val(),$('#user_id_fill').val());
    $('#sltYear_fill').change(function(){
        var _value = $(this).val();
        if(_value != ""){
          GET_INITIAL_NGHILC();
          danhsachkinhphi($('#sltTruong_fill').val(),_value,$('#user_id_fill').val());
        }
    });

    $('#sltTruong_fill').change(function(){
        var _value = $(this).val();
        if(_value != ""){
          GET_INITIAL_NGHILC();
          danhsachkinhphi(_value,$('#sltYear_fill').val(),$('#user_id_fill').val());
        }
    });

    $('#user_id_fill').change(function(){
        var _value = $(this).val();
        if(_value != ""){
          GET_INITIAL_NGHILC();
          danhsachkinhphi($('#sltTruong_fill').val(),$('#sltYear_fill').val(),_value);
        }
    });

    $('#btnCapKinhPhi').click(function(){
        // if($('#sltTruong').val() == '' && $('#user_id').val() == ''){
        //     utility.messagehide("messageValidate", "Xin mời chọn cấp trường / cấp bậc", 1, 5000);
        //     $('#sltTruong').focus();
        //     $('#user_id').focus();
        //     return;
        // }
        //user_id
        if($('#sltYear').val() == ''){
            utility.messagehide("messageValidate", "Xin mời chọn năm", 1, 5000);
            $('#sltYear').focus();
            return;
        }
        $('.title').html($('#sltTruong option:selected').text()+' - '+ $('#sltYear').val());
        $('#expense').val('');
        $('#txtId').val('');
        $('#using').selectpicker('val',0).attr('disabled','disabled').selectpicker('refresh');
        $('#note').val('');
        $(":file").filestyle('clear');
        $('#myModalDetail').modal('show');
    });

    permission(function(){
      var html_view  = '';
      var html_view_header  = '<b>Quản lý dự toán chi trả </b> / Cấp kinh phí';
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
              <!-- /.mailbox-controls -->
             <!--  <div class="mailbox-read-message" style="margin-top: 10px;"> -->
              <form class="form-horizontal" action="">         
                <div class="modal-body">
                    <div class="row" id="group_message_approved" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                      <div class="box box-primary">
                    <div class="box-body" style="font-size:12px;">
                      <?php if(Auth::user()->level != 1): ?>
                    <?php if(Auth::user()->level == 4 || Auth::user()->level == 3): ?>
                    <div class="col-sm-6">
                      <label  class="col-sm-12">Cấp bậc nhận<font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <select class="form-control selectpicker" name="user_id" id="user_id">
                          <?php 
                              $ptc = DB::table('users')->where('level',(Auth::user()->level - 1))
                              ->get();
                          ?>
                          <option value="" selected="selected">-- Chọn cấp --</option>
                          <?php $__currentLoopData = $ptc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val->id); ?>" ><?php echo e($val->username); ?> - <?php echo e($val->last_name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </div>
                    </div>
                      <?php endif; ?>
                      <?php if(Auth::user()->level == 2 || Auth::user()->level == 3): ?>
                      <div class="col-sm-6">
                          <label  class="col-sm-12">Cấp trường nhận</label>
                        <div class="col-sm-12">
                          <select name="sltTruong" id="sltTruong"  class="form-control selectpicker"  <?php if(count(explode('-',Auth::user()->truong_id)) > 1): ?> data-live-search="true" <?php endif; ?> style="width: 100% !important">
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
                         
                         <?php $__currentLoopData = $qlhs_schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val->schools_id); ?>" <?php if($val->schools_id == Auth::user()->truong_id): ?> selected <?php endif; ?>><?php echo e($val->schools_name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    </div>
                    </div>
                    <?php endif; ?>
                  
                  <?php endif; ?>
                  <div class="col-sm-6">
                      <label class="text-left col-md-12">Kinh phí</label>
                          <div class="col-md-12">
                            <input type="text" class="form-control" name="expense" id="expense" value="">
                          </div>
                    </div>
                  <div class="col-sm-3">
                    <label  class="col-sm-12">Năm cấp kinh phí<font style="color: red">*</font></label>
                    <div class="col-sm-12">
                        <select name='sltYear' class="form-control selectpicker"  data-live-search="true" id='sltYear'>
                            <?php 
                              $getYear = DB::table('qlhs_years')->select('name','code')->get();
                              $year = Carbon\Carbon::now()->format('Y');
                              if(Carbon\Carbon::now()->format('m') >= 1 && Carbon\Carbon::now()->format('m') <= 8){
                                $year = $year + 1;
                              }
                              
                            ?>
                            <?php $__currentLoopData = $getYear; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($val->code); ?>" <?php if($year == $val->code): ?> selected="selected" <?php endif; ?>><?php echo e($val->code); ?></option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                  </div>
                    
                    <div class="col-sm-3">
                      <label class="text-left col-md-12">Trạng thái</label>
                          <div class="col-md-12">
                            <select class="form-control selectpicker" name="using" id="using" >
                                <option value="1" selected>Đã cấp</option>
                                <option value="2">Đã thay đổi</option>
                            </select>
                          </div>
                    </div>
                    <div class="col-sm-6">
                    <label  class="col-sm-12">Chế độ cấp kinh phí</label>
                    <div class="col-sm-12">
                      <select name="sltLoaiChedo" id="sltLoaiChedo" class="form-control selectpicker" multiple>
                        <!-- <option value="" selected>--- Chọn chế độ ---</option> -->
                        <option value="1">Miễn giảm học phí</option>
                        <option value="2">Chi phí học tập</option>
                        <option value="3">Hỗ trợ ăn trưa trẻ em mẫu giáo</option>
                        <option value="4">Hỗ trợ học sinh bán trú</option>
                        <option value="5">Hỗ trợ học sinh khuyết tật</option>
                        <option value="6">Hỗ trợ ăn trưa học sinh theo NQ57</option>
                        <option value="7">Hỗ trợ học sinh dân tộc thiểu số</option>
                        <option value="8">Hỗ trợ học bổng cho học sinh dân tộc nội trú</option>
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
            <!-- /.box-header -->
            <!-- form start -->
            <form id="formdutoan">
            <div class="row" id="messageValidate" style="padding-left: 10%;padding-right: 10%"></div> 
              <div class="box-body">

                <div class="form-group">
                  <?php if(Auth::user()->level != 1): ?>
                    <?php if(Auth::user()->level == 4 || Auth::user()->level == 3): ?>
                    <div class="col-sm-3">
                      <label  class="col-sm-12">Cấp bậc nhận<font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <select class="form-control selectpicker" name="user_id_fill" id="user_id_fill">
                          <?php 
                              $ptc = DB::table('users')->where('level',(Auth::user()->level - 1))
                              ->get();
                          ?>
                          <option value="" selected="selected">-- Chọn cấp --</option>
                          <?php $__currentLoopData = $ptc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val->id); ?>" ><?php echo e($val->username); ?> - <?php echo e($val->last_name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </div>
                    </div>
                      <?php endif; ?>
                      <?php if(Auth::user()->level == 2 || Auth::user()->level == 3): ?>
                      <div class="col-sm-3">
                          <label  class="col-sm-12">Cấp trường nhận</label>
                        <div class="col-sm-12">
                          <select name="sltTruong_fill" id="sltTruong_fill"  class="form-control selectpicker"  <?php if(count(explode('-',Auth::user()->truong_id)) > 1): ?> data-live-search="true" <?php endif; ?> style="width: 100% !important">
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
                         
                         <?php $__currentLoopData = $qlhs_schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val->schools_id); ?>" <?php if($val->schools_id == Auth::user()->truong_id): ?> selected <?php endif; ?>><?php echo e($val->schools_name); ?></option>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    </div>
                    </div>
                    <?php endif; ?>
                  
                  <?php endif; ?>
                  <div class="col-sm-2">
                    <label  class="col-sm-12">Năm cấp kinh phí<font style="color: red">*</font></label>
                    <div class="col-sm-12">
                        <select name='sltYear_fill' class="form-control selectpicker"  data-live-search="true" id='sltYear_fill'>
                            <?php 
                              $getYear = DB::table('qlhs_years')->select('name','code')->get();
                              $year = Carbon\Carbon::now()->format('Y');
                              if(Carbon\Carbon::now()->format('m') >= 1 && Carbon\Carbon::now()->format('m') <= 8){
                                $year = $year + 1;
                              }
                              
                            ?>
                            <?php $__currentLoopData = $getYear; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($val->code); ?>" <?php if($year == $val->code): ?> selected="selected" <?php endif; ?>><?php echo e($val->code); ?></option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <label  class="col-sm-12">Lọc dữ liệu</label>
                    <select class="form-control selectpicker" id="sltUserBy">
                        <?php if(Auth::user()->level == 4 || Auth::user()->level == 3): ?>
                        <option value="4" <?php if(Auth::user()->level == 4): ?> selected <?php endif; ?>>Cấp Sở</option>
                        <option value="3" <?php if(Auth::user()->level == 3): ?> selected <?php endif; ?>>Phòng tài chính</option>
                        <option value="2">Phòng giáo dục</option>
                        <option value="1">Cấp trường</option>
                        <?php endif; ?>
                        <?php if(Auth::user()->level == 2): ?>
                        <option value="3">Phòng tài chính</option>
                        <option value="2" selected>Phòng giáo dục</option>
                        <option value="1">Cấp trường</option>
                        <?php endif; ?>
                        <?php if(Auth::user()->level == 1): ?>
                        <option value="3">Phòng tài chính</option>
                        <option value="2">Phòng giáo dục</option>
                       <!--  <option value="1" selected>Cấp trường</option> -->
                        <?php endif; ?>
                        
                    </select>
                  </div>
                  <?php if(Auth::user()->level != 1): ?>
                  <div class="col-sm-2">
                    <label  class="col-sm-12">Thực hiện</label>
                    <button type="button" class="btn btn-success" id="btnCapKinhPhi"><i class="glyphicon glyphicon-plus"></i> Cấp mới</button>
                  </div>
                  <?php endif; ?>
                  <div class="col-sm-3">
                    <label  class="col-sm-12">Chế độ cấp kinh phí</label>
                    <div class="col-sm-12">
                      <select name="sltLoaiChedo_fill" id="sltLoaiChedo_fill" class="form-control selectpicker" multiple="">
                        <!-- <option value="">--- Chọn chế độ ---</option> -->
                        <option value="1">Miễn giảm học phí</option>
                        <option value="2">Chi phí học tập</option>
                        <option value="3">Hỗ trợ ăn trưa trẻ em mẫu giáo</option>
                        <option value="4">Hỗ trợ học sinh bán trú</option>
                        <option value="5">Hỗ trợ học sinh khuyết tật</option>
                        <option value="6">Hỗ trợ ăn trưa học sinh theo NQ57</option>
                        <option value="7">Hỗ trợ học sinh dân tộc thiểu số</option>
                        <option value="8">Hỗ trợ học bổng cho học sinh dân tộc nội trú</option>
                      </select>
                    </div>
                  </div>
                </div>
             
                </div>
            </form>
          </div>

            <div class="box box-primary">

                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                      <thead id="dataHeaderDetailKP">
                        <tr class="success">
                          <th class="text-center"  style="vertical-align:middle;width: 3%">STT</th>
                          <th class="text-center"  style="vertical-align:middle;width: 15%">Cấp nhận</th>
                          <th class="text-center"  style="vertical-align:middle;width: 8%">Kinh phí</th>
                          <th class="text-center"  style="vertical-align:middle;width: 7%">Năm học</th>
                          <th class="text-center"  style="vertical-align:middle;width: 8%">Trạng thái</th>
                          <th class="text-center"  style="vertical-align:middle;width: 8%">Chế độ</th>
                          <th class="text-center"  style="vertical-align:middle;width: 22%">Quyết định</th>
                          <th class="text-center"  style="vertical-align:middle;width: 22%">Nội dung</th>
                          <th class="text-center"  style="vertical-align:middle;width: 8%">Ngày cấp</th>
                          <?php if(Auth::user()->level != 1): ?>
                          <th class="text-center"  style="vertical-align:middle;width: 7%" colspan="2">Chức năng</th>
                          <?php endif; ?>
                        </tr>
                      </thead>
                        <tbody id="dataBodyDetailKP">                     
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
                                        <label  class="col-md-6 control-label text-right">Hiển thị: </label>
                                        <div class="col-md-6">
                                          <select name="drPagingDanhsach" id="drPagingDanhsach"  class="selectpicker form-control input-sm pagination-show-row">
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
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>