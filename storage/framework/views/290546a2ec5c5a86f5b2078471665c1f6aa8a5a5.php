<?php $__env->startSection('title', 'This is a blank page'); ?>
<?php $__env->startSection('description', 'This is a blank page that needs to be implemented'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo asset('css/select2.min.css'); ?>">
<link rel="stylesheet" href="<?php echo asset('dist/css/bootstrap-multiselect.css'); ?>">
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?php echo asset('/js/myScript.js'); ?>"></script>

<section class="content">
<script type="text/javascript">
$(function () {
    $('#view-tonghopdanhsach').change(function(){
        if($('#sltYear').val() == ""){
            utility.message("Thông báo","Xin mời chọn học kỳ!",null,5000,1);
            $('select#sltYear').focus();
            return;
        }
        if($('#sltSchool').val() == ""){
            utility.message("Thông báo","Xin mời chọn trường!",null,5000,1);
            $('select#sltSchool').focus();
            return;
        }
        GET_INITIAL_NGHILC();
        loadStatisticTotal();
        
    });
    $('#sltYear').change(function(){
        if($('#sltStatistic').val() != ""){
            // utility.message("Thông báo","Xin mời chọn chế độ!",null,5000,1);
            // $('select#sltStatistic').focus();
            // return;
            GET_INITIAL_NGHILC();
            loadStatisticTotal();
        }
        
        
    });

    $('#sltKhoiDt').change(function(){
        // $('#sltStatistic').val('').selectpicker('refresh');
        if($('#sltStatistic').val() != ""){
            // utility.message("Thông báo","Xin mời chọn chế độ!",null,5000,1);
            // $('select#sltStatistic').focus();
            // return;
            GET_INITIAL_NGHILC();
            loadStatisticTotal();
        }
        
        
    });

    $('#sltStatistic').change(function(){
        if($('#sltYear').val() == ""){
            utility.message("Thông báo","Xin mời chọn học kỳ!",null,5000,1);
            $('select#sltYear').focus();
            return;
        }
        if($('#sltSchool').val() == ""){
            utility.message("Thông báo","Xin mời chọn trường!",null,5000,1);
            $('select#sltSchool').focus();
            return;
        }
        GET_INITIAL_NGHILC();
        loadStatisticTotal();

    });

    //$('.btnprn').printPage();
    $('#btnprn').click(function(){
      
        if($('#sltStatistic').val() == ""){
            utility.message("Thông báo","Xin mời chọn chế độ!",null,5000,1);
            $('select#sltStatistic').focus();
            return;
        }
        $('#btnprn').button('loading');
          var o = {
            truong_id : $('#sltSchool').val(),
            hocky : $('#sltYear').val(),
            chedo : $('#sltStatistic').val(),
            khoilop : $('#sltKhoiDt').val(),
            tentruong : $('#sltSchool option:selected').text()
        };
        $.ajax('/bao-cao/statistic-by-subject-view', {
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        },
          method: 'POST',
          methodType: 'HTML',
          data: o,
          success: function (data) { 
            var left = (screen.width) / 4;
            var top = (screen.height) / 8;  // for 25% - devide by 4  |  for 33% - devide by 3
            //newWin= window.open();
            newWin= window.open("/",'name','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=1, resizable=no, copyhistory=no, width='+(screen.width)+', height='+(screen.height)+', top=' + top + ', left=' + left);
            newWin.document.write(data);
            newWin.print();
            newWin.close();
            $('#btnprn').button('reset');
          },error: function (val) {
              $('#btnprn').button('reset');
          }
      });
    })
});

function loadStatisticTotal(){
    $('#dataTongHopHoSo').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: $('#view-tonghopdanhsach').val(),
        truong_id : $('#sltSchool').val(),
        hocky : $('#sltYear').val(),
        chedo : $('#sltStatistic').val(),
        khoilop : $('#sltKhoiDt').val()
    };
    PostToServer('/bao-cao/statistic-by-subject',o,function(result){
        SETUP_PAGING_NGHILC(result, function () {
            loadStatisticTotal();
        });
        data = result.data;
        var html_show = "";
        var html_header = "<tr class='success'>";
        if(data.length>0){
            for (var i = 0; i < data.length; i++) {
              html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * $('#view-tonghopdanhsach').val()))+"</td>";
              html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].profile_name)+"</td>";
              html_show += "<td class='text-center' style='vertical-align:middle'>"+formatDates(data[i].profile_birthday)+"</td>";
              html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertString(data[i].class_name)+"</td>";
              html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(data[i].nhu_cau)+"</td>";
              html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].subject_name)+"</td>";
              html_show += "<td class='text-center' style='vertical-align:middle'>"+formatter(data[i].count_month)+"</td>";
              html_show += "<td class='text-center' style='vertical-align:middle'>"+formatter(Math.round(data[i].nhu_cau * data[i].count_month/1000)*1000)+"</td></tr>";
          }
        }else{
           html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataTongHopHoSo').html(html_show);
  },function(result){
      console.log("loadStatisticTotal");
      console.log(result);
  },"btnViewThongKe","","");
}

</script>



<div class="box-header">
    <div class="panel-heading">
       <a href="/ho-so/lap-danh-sach/list"><b> Báo cáo</b></a> / Thống kê học sinh theo chế độ
    </div>
    <div class="pull-right box-tools" >
        <button id="btnprn" type="button" class="btn btn-info pull-right" data-loading-text="Đang tải dữ liệu" style="margin:5px"><i class="fa fa-print" aria-hidden="true"></i> In</button>
    </div>
</div>
    <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <form id="formmiengiamhocphi">
            <div class="row" id="warning_msg" style="padding-left: 10%;padding-right: 10%"></div>
              <div class="box-body">
              
              <div class="form-group" >
                  <div class="col-sm-3">
                      <label  class="col-sm-12 control-label">Trường </label>
                      <div class="col-sm-12">
                        <select name="sltSchool" id="sltSchool" class="form-control selectpicker" <?php if(count(explode('-',Auth::user()->truong_id)) > 1 ): ?> data-live-search="true" <?php endif; ?>>
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
                    <?php 
                                    $liencap = DB::table('qlhs_schools')
                                    ->where('schools_id',Auth::user()->truong_id)
                                    ->select('LienCap')->first();
                                    if($liencap->LienCap != null){
                                       $data = explode('-', $liencap->LienCap);
                                    ?>
                                    <div class="col-sm-3" style="padding-left: 0">
                                        <label class="col-sm-12">Cấp học</label>
                                        <div class="col-sm-12">
                                            <select name='sltKhoiDt' class="selectpicker form-control" id='sltKhoiDt'>
                                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($val == 1): ?>
                                                <option value="1">-- Mầm non --</option>
                                                <?php elseif($val == 2): ?>
                                                <option value="2">-- Tiểu học --</option>
                                                <?php elseif($val == 3): ?>
                                                <option value="3">-- Trung học cơ sở --</option>
                                                <?php elseif($val == 33): ?>
                                                <option value="33">-- Khối THPT --</option>
                                                <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php 
                                    }
                                    ?>
                    <div class="col-sm-3">
                      <label  class="col-sm-12 control-label">Năm học</label>
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
                                            ->where('qlhs_hocky_value','LIKE','HK%')
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
                     <div class="col-sm-3">
                      <label  class="col-sm-12 control-label">Loại thống kê</label>
                      <div class="col-sm-12">
                        <select name='sltStatistic' class="form-control selectpicker" id='sltStatistic'>
                          <option value="" selected="selected">-- Chọn loại báo cáo --</option>
                          <option value="1">Hỗ trợ miễn giảm học phí</option>
                          <option value="2">Hỗ trợ chi phí học tập</option>
                          <option value="3">Hỗ trợ ăn trưa cho trẻ em mẫu giáo</option>
                          <option value="4">Hỗ trợ bán trú tiền ăn</option>
                          <option value="5">Hỗ trợ bán trú tiền ở</option>
                          <option value="6">Hỗ trợ bán trú tiền VHTT</option>
                          <option value="7">Hỗ trợ tiền ăn học sinh</option>
                          <option value="8">Hỗ trợ học sinh khuyết tật - học bổng</option>
                          <option value="9">Hỗ trợ học sinh khuyết tật - DDHT</option>
                          <option value="10">Hỗ trợ học sinh dân tộc thiểu số</option>
                          <option value="11">Hỗ trợ học sinh nội trú</option>
                        </select>
                      </div>
                    </div>
 
              </div>
            </form>
          </div>

            <div class="box box-primary">

                <div class="box-body" style="font-size:12px">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                          <tr class="success">
                            <th class='text-center' style='vertical-align:middle'>STT</th>
                            <th class='text-center' style='vertical-align:middle'>Họ và tên</th>
                            <th class='text-center' style='vertical-align:middle'>Năm sinh</th>
                            <th class='text-center' style='vertical-align:middle'>Lớp học</th>
                            <th class='text-center' style='vertical-align:middle'>Số tiền được hỗ trợ/tháng</th>
                            <th class='text-center' style='vertical-align:middle'>Đối tượng</th>
                            <th class='text-center' style='vertical-align:middle'>Số tháng</th>
                            <th class='text-center' style='vertical-align:middle'>Kinh phí hỗ trợ (nghìn đồng)</th>
                          </tr>
                        </thead>
                        <tbody id="dataTongHopHoSo">
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
                                  <select class="form-control input-sm g_selectPaging">
                                      <option value="0">0 / 20 </option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-3">
                                        <label  class="col-md-6 control-label text-right">Hiển thị: </label>
                                        <div class="col-md-3">
                                          <select name="view-tonghopdanhsach" id="view-tonghopdanhsach"  class="selectpicker form-control input-sm pagination-show-row">
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
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>