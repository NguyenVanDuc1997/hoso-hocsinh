<?php $__env->startSection('title', 'This is a blank page'); ?>
<?php $__env->startSection('description', 'This is a blank page that needs to be implemented'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo asset('css/select2.min.css'); ?>">

<script src="<?php echo asset('js/select2.min.js'); ?>"></script>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>
<script type="text/javascript" src="<?php echo asset('mystyle/js/jsDanhMuc.js'); ?>"></script>
<section class="content">
<script type="text/javascript">
 $(function () {
    $('#sltTruongDt').change(function(){
        loadSLNNA();
    });

    input_alias = function(e){
      var data = replaceAll(($(e).val().trim()+""),' ','');
      $(e).val(change_alias(data));
    }
    $('button#btnCongVanNGNA').click(function(){
        $('#btnCongVanNGNA').button('loading');
        var message = validatePopupTongHopCheDo();
        if (message !== "") {
          utility.messagehide("group_message_THCD", message, 1, 5000);
          $('#btnCongVanNGNA').button('reset');
          return;
        }
        if($('#txtTenCongVan').val() == "" ){
          utility.messagehide("group_message_THCD", "Xin mời nhập tên công văn", 1, 5000);
          $('#btnCongVanNGNA').button('reset');
          return;
        }
        if($('#txtSoCongVan').val() == "" ){
          utility.messagehide("group_message_THCD", "Xin mời nhập số công văn", 1, 5000);
          $('#btnCongVanNGNA').button('reset');
          return;
        }             
        var form_datas = new FormData();   
        form_datas.append('SCHOOLID', $('#drSchoolTHCD').val());
        form_datas.append('YEAR', $('#sltYear').val());
        form_datas.append('UNITNAME', $('#sltKhoiDt').val());//Khối lớp
        form_datas.append('ARRCHEDO', $('#sltChedo').val());//Chế độ lập công văn
        form_datas.append('NAME', $('#txtTenCongVan').val());//tên công văn
        form_datas.append('NUMBERCV', $('#txtSoCongVan').val());//số công văn
        form_datas.append('NOTE', $('#txtGhiChuTHCD').val());//Ghi chú
        form_datas.append('CAPNHAN', $('#sltCapNhan').val());// Cấp nhận
        form_datas.append('LOAICONGVAN', $('#idNGNA').val());// Loại công văn

        lapdanhsachDanhSachNauAn(form_datas);
    });
    $('#sltYearNA').change(function(){
        loadSLNNA();
    });
    $('#sltKhoiLop').change(function(){
        loadSLNNA();
    });
    $('#drSchoolTHCD,#sltKhoiLopNA,#sltYear').change(function(){
        GET_INITIAL_NGHILC();
        loaddataNguoinauan($('#drPagingNguoinauan').val(), $('#txtSearchNgna').val());
    });
       module = 101;
      permission(function(){
        var html_view  = '<b> Danh mục </b> / Quản lý số lượng người nấu ăn';
        
        if(check_Permission_Feature('1')){
            html_view += '<a data-toggle="modal" data-target="#myModal" style="margin-left: 10px" class="btn btn-success btn-xs pull-right" onclick="popupAddnewNgna()" ><i class="glyphicon glyphicon-plus"></i> Tạo mới </a >';
        }
        // if(check_Permission_Feature('4')){
        //     html_view += '<a class="btn btn-success btn-xs pull-right" href="#" onclick="exportExcelNation()"> <i class="glyphicon glyphicon-print"></i> Xuất excel </a>';
        // }
        $('#addnew-ngna').html(html_view);
      });

      GET_INITIAL_NGHILC();
      loaddataNguoinauan($('#drPagingNguoinauan').val(), $('#txtSearchNgna').val());

      $('#drPagingNguoinauan').change(function() {
        GET_INITIAL_NGHILC();
        loaddataNguoinauan($(this).val(), $('#txtSearchNgna').val());
      });

  });

    function popupAddnewNgna(){
      $('.modal-title').html('Thêm mới số lượng người nấu ăn');
      //$('#sltTruongDt').selectpicker('val','');
      $('#txtHSBT').val(0);
      $('#txtNVTW').val(0);
      $('#txtNVDP').val(0);
      $('#txtNV68').val(0);
      $('#sltYearNA').selectpicker('val','');
      $('#sltKhoiLop').selectpicker('val','');
      $('#txtHSTW').val(0);
      $('#txtHSDP').val(0);
      $('#btnInsertNgna').html('Thêm mới');
      $("#modalAddNew").modal("show");
    }

    function popupUpdateNgna(){
      $('.modal-title').html('Sửa số lượng người nấu ăn');
      $('#sltTruongDt').selectpicker('refresh');
      $('#btnInsertNgna').html('Lưu');
      $("#modalAddNew").modal("show");
    }

    function popupConfirmDelete(){
      $("#modalDelete").modal("show");
    }

   function testPhanquyen(){

    $("#myModalPhanQuyen").modal("show");
   }

   function openPopupLapTHCD(string,khoi=""){
        //var hk = parseInt(string.split('-')[1]) + 1;
        var hk = $('#sltYear option:selected').text();
        $('#titleCVTruong').html(hk);
        $('#txtTenCongVan').val("");
        $('#txtSoCongVan').val("");
        $('#sltCapNhan').selectpicker('val',"");
        $('#sltKhoiDt').selectpicker('val',khoi);
        $('#txtGhiChuTHCD').val("");
        $('#idNGNA').val(string);
        $("#myModalLapDanhSachTHCD").modal("show");
    }

   function loadSLNNA(){
      var o = {
          schools_id : $('#sltTruongDt').val(),
          years : $('#sltYearNA').val(),
          units : $('#sltKhoiLop').val()
      }
      PostToServer('/danh-muc/nguoinauan/loadDataUpdateInsert',o,function(dataget){
            $('#txtHSBT').val(dataget.tongso);
            $('#txtHSTW').val(dataget.KPTW);
            $('#txtHSDP').val(dataget.KPDP);
            var kptw = Math.round(parseFloat(dataget.KPTW)/30) <= 5 ? Math.round(parseFloat(dataget.KPTW)/30) : 5;
            var kpdp = Math.round(parseFloat(dataget.KPDP)/50) <= 10 ? Math.round(parseFloat(dataget.KPDP)/50) : 10;
            $('#txtNVTW').val(kptw);
            $('#txtNVDP').val(kpdp);
            
      },function(dataget){
          console.log("loadSLNNA");
          console.log(dataget);
      },"btnInsertNgna","","");
   }
</script>
<div class="modal fade" id="myModalLapDanhSachTHCD" role="dialog">
        <div class="modal-dialog modal-md" style="margin: 10px auto;">
            <!-- Modal content-->
            <div class="modal-content box">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="">Lập danh sách đề nghị <b id="titleCVTruong"></b></h4>
                </div>
                <form class="form-horizontal" action="" id="frmPopupTHCD">
                    <input type="hidden" class="form-control" id="idNGNA">
                    <input type="hidden" class="form-control" value="9" id="sltChedo">
                    <div class="modal-body" style="font-size: 12px;padding: 5px;">
                        <div class="row" id="group_message_THCD" style="padding-left: 10%;padding-right: 10%"></div>
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-6" style="padding-left: 0">
                                    <label class="col-sm-12">Tên công văn</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="txtTenCongVan" placeholder="Nhập tên công văn">
                                    </div>
                                </div>
                                <div class="col-sm-6" style="padding-left: 0">
                                    <label class="col-sm-12">Số công văn <font style="color: red">(Viết liền không dấu)</font></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="txtSoCongVan" placeholder="Nhập số công văn" onkeyup="input_alias(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3" style="padding-left: 0;display: none;">
                                    <label class="col-sm-12">Loại công văn</label>
                                    <div class="col-sm-12" style="padding-right: 5px !important;">
                                        <select name="sltTypeCV" id="sltTypeCV" class="form-control selectpicker">
                                            <option value="1" selected="selected">-- Công văn bình thường --</option>
                                            <option value="2">-- Công văn điều chỉnh --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6" style="padding-left: 0">
                                    <label class="col-sm-12">Cấp nhận <font style="color: red">*</font></label>
                                    <div class="col-sm-12" style="padding-right: 5px !important;">
                                        <select name="sltCapNhan" id="sltCapNhan" class="form-control selectpicker">
                                            <option value="2" selected="selected">Phòng Giáo Dục</option>
                                            <option value="3">Phòng Tài Chính</option>
                                        </select>
                                    </div>
                                </div>

                             
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12" style="padding-left: 0">
                                    <label class="col-sm-6">Ghi chú </label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="txtGhiChuTHCD" placeholder="Nhập ghi chú">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row text-center">
                            <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id="btnCongVanNGNA"><i class="glyphicon glyphicon-globe"></i> Lập công văn</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
            <input type="button" value="Xác nhận" id="btnConfirmDeleteNgna" class="btn btn-primary">
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
          <h4 class="modal-title">Thêm mới số người nấu ăn</h4>
        </div>
        <form class="form-horizontal" action="">  
        <input type="hidden" class="form-control" id="txtIdRoleGroup">          
                <div class="modal-body">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                <div class="form-group">
                    <div class="col-sm-4">
                      <label  class="col-sm-6">Chọn trường <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <select name='sltTruongDt' class="form-control selectpicker" data-live-search="true" id='sltTruongDt' style="width: 100% !important">
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
                    <div class="col-sm-4">
                        <label  class="col-sm-6">Năm học<font style="color: red">*</font></label>
                        <div class="col-sm-12">
                            <select name='sltYearNA' class="selectpicker form-control" id='sltYearNA'>
                                        <?php 
                                              $namhoc = DB::table('qlhs_years')->orderBy('code','asc')->get();
                                              $dt = Carbon\Carbon::now()->format('Y') -1;
                                              if(Carbon\Carbon::now()->format('m') >= 1 && Carbon\Carbon::now()->format('m') <= 8){
                                                $str_date = "HK2-".$dt;
                                              }else{
                                                $str_date = "HK1-".$dt;
                                              }
                                              ?>
                                            <?php $__currentLoopData = $namhoc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php 
                                              $hocky = DB::table('qlhs_hocky')
                                              ->where('qlhs_hocky_code',$nam->code)
                                              ->where('qlhs_hocky_value','NOT LIKE','CA%')
                                              ->orderBy('qlhs_hocky_order','asc')->get();
                                              if(count($hocky) > 0){
                                              ?>
                                                <optgroup label='Năm học <?php echo e($nam->name); ?>'>
                                                    <?php $__currentLoopData = $hocky; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($hk->qlhs_hocky_value); ?>" <?php if(trim($hk->qlhs_hocky_value) == trim($str_date)): ?> selected <?php endif; ?>><?php echo e($hk->qlhs_hocky_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                                <?php }?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                        </div>
                    </div>
                        <div class="col-sm-4">
                            
                        </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                      <label  class="col-sm-6">Số học sinh ăn bán trú<font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <input type="text" name="txtHSBT" id="txtHSBT" class="form-control" readonly="readonly">                       
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <label  class="col-sm-6">Số HS sử dụng KP TW<font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <input type="text" name="txtHSTW" id="txtHSTW" class="form-control" readonly="readonly">                       
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <label  class="col-sm-6">Số HS sử dụng KP DP<font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <input type="text" name="txtHSDP" id="txtHSDP" class="form-control" readonly="readonly">                       
                      </div>
                    </div>
                    
                </div>
                <div class="form-group">
                 <div class="col-sm-4">
                      <label  class="col-sm-6">Số lượng nhân viên theo TW<font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <input type="text" name="txtNVTW" id="txtNVTW" class="form-control" readonly="readonly">                       
                      </div>
                    </div>
                  <div class="col-sm-4">
                      <label  class="col-sm-6">Số lượng nhân viên theo ĐP<font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <input type="text" name="txtNVDP" id="txtNVDP" class="form-control" readonly="readonly">                       
                      </div>
                    </div>
                  <div class="col-sm-4">
                      <label  class="col-sm-12">Số lượng nhân viên theo HĐ 68<font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <input type="text" name="txtNV68" id="txtNV68" class="form-control" value="0" placeholder="Nhập số người">                       
                      </div>
                    </div>
                </div>
              </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnInsertNgna">Thêm mới</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btncloseNGNA">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading" id="addnew-ngna">
       
        
        
    </div>
    </div>
          <div class="box">
                <div class="form-group " style="margin-top: 10px;margin-bottom: 0px;">
                <div class="box-header with-border">
              <div class="col-sm-2">
                        <label class="col-sm-6">Chọn trường <font style="color: red">*</font></label>
                        <div class="col-sm-12">
                            <select name='drSchoolTHCD' class="selectpicker form-control" id='drSchoolTHCD'>
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
                    
                            <div class="col-sm-2">
                                <label class="col-sm-6">Năm học <font style="color: red">*</font></label>
                                <div class="col-sm-12">
                                    <select name='sltYear' class="selectpicker form-control" id='sltYear'>
                                        <?php 
        $namhoc = DB::table('qlhs_years')->orderBy('code','asc')->get();
        $dt = Carbon\Carbon::now()->format('Y') -1;
        if(Carbon\Carbon::now()->format('m') >= 1 && Carbon\Carbon::now()->format('m') <= 8){
          $str_date = "HK2-".$dt;
        }else{
          $str_date = "HK1-".$dt;
        }
        ?>
                                            <?php $__currentLoopData = $namhoc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php 
        $hocky = DB::table('qlhs_hocky')
        ->where('qlhs_hocky_code',$nam->code)
        ->where('qlhs_hocky_value','NOT LIKE','CA%')
        ->orderBy('qlhs_hocky_order','asc')->get();
        if(count($hocky) > 0){
        ?>
                                                <optgroup label='Năm học <?php echo e($nam->name); ?>'>
                                                    <?php $__currentLoopData = $hocky; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($hk->qlhs_hocky_value); ?>" <?php if(trim($hk->qlhs_hocky_value) == trim($str_date)): ?> selected <?php endif; ?>><?php echo e($hk->qlhs_hocky_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </optgroup>
                                                <?php }?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

            </div>
                     
                </div>

            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" rowspan="2" style="vertical-align:middle">STT</th>
                  <th class="text-center" rowspan="2" style="vertical-align:middle;">Tên trường</th>
     <!--              <th class="text-center" rowspan="2" style="vertical-align:middle">Cấp học</th> -->
                  <th class="text-center" rowspan="2" style="vertical-align:middle">Năm học</th>
                  <th class="text-center" rowspan="2" style="vertical-align:middle">Tổng học sinh</th>
                  <th class="text-center" colspan="3" style="vertical-align:middle">Số lượng nhân viên</th>
                  <th class="text-center" colspan="3" style="vertical-align:middle">Nhu cầu kinh phí</th>
                  <th class="text-center" rowspan="2" style="vertical-align:middle">Lập danh sách</th>
                  <th class="text-center" rowspan="2" colspan="3" style="vertical-align:middle">Chức năng</th>
                </tr>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle">TW</th>
                  <th class="text-center" style="vertical-align:middle">ĐP</th>
                  <th class="text-center" style="vertical-align:middle">HĐ 68</th>
                  <th class="text-center" style="vertical-align:middle">TW</th>
                  <th class="text-center" style="vertical-align:middle">ĐP</th>
                  <th class="text-center" style="vertical-align:middle">Tổng</th>
                </tr>
                </thead>
                <tbody id="dataTableNGNA">
                
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
                        <select name="drPagingNguoinauan" id="drPagingNguoinauan"  class="form-control input-sm pagination-show-row selectpicker">
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