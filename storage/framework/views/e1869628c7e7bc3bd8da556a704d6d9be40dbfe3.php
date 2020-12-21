

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
  // var ethnic_opt = '<tr class="option-group">' 
  //             + '<td class="text-center" style="width:40%">-</td>'     
  //             + '<td class="text-center" style="width:40%">'
  //             + '<input type="file" name="fileQuyetDinh[]" value="" class="form-control" multiple="multiple"/>'
  //             + '</td>'
  //             + '<td class="text-center" style="width:10%">'
  //             + '<button type="button" class="btn btn-xs btn-white remove-option" title="Xóa">'
  //             + '<i class="glyphicon glyphicon-trash"></i>'
  //             + '</button>'
  //             + '</td>'
  //             + '</tr>';
  $(function () {
    $(":file").filestyle({
      buttonText : ' Đính kèm quyết định',
      buttonName : 'btn-info'
    }); 
    // $("#add_ethnic").click(function(e) {
    //         e.preventDefault();
    //         $("#option_container").append(ethnic_opt); 
    //         // $(":file").filestyle({
    //         //   buttonText : 'Đính kèm',
    //         //   buttonName : 'btn-info'
    //         // });            
    //     });

    GET_INITIAL_NGHILC();
    loadTongHopCongVanBySchool(null,$('#txtSearchProfileLapdanhsach').val()); 

    $('#sltYear').change(function(){
        GET_INITIAL_NGHILC();
        loadTongHopCongVanBySchool(null,$('#txtSearchProfileLapdanhsach').val()); 
    });
    
    
    autocompleteSearchDenghidalap('txtSearchProfileLapdanhsach',0);

    $('#drPagingDanhsach').change(function() {
        GET_INITIAL_NGHILC();
        loadTongHopCongVanBySchool(null,$('#txtSearchProfileLapdanhsach').val()); 

    });
    
    
    permission(function(){
      var html_view  = '';
      var html_view_header  = '<b> Hồ sơ </b> / Danh sách đề nghị đã lập';
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
    <div class="modal-dialog modal-md" style="width: 100%;margin: 10px auto;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="">Tổng chi phí công văn</h4>
        </div>
        <div class="box-body no-padding">
              <input type="hidden" name="txtSoCongVan" id="txtSoCongVan">
              <!-- /.mailbox-controls -->
             <!--  <div class="mailbox-read-message" style="margin-top: 10px;"> -->
              <form class="form-horizontal" action="">         
                <div class="modal-body">
                    <div class="row" id="group_message_approved" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">

                      <div class="box box-primary">

                <div class="box-body" style="font-size:12px;">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead id="headerDetail">
                       
                      </thead>
                        <tbody id="tbodyrDetail">                     
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="box box-primary">
                <div class="box-header with-border">
                      <div class="col-sm-12">
                    <input type="file" name="fileQuyetDinh[]" value="" class="form-control" multiple="multiple"/>
                  </div>

                </div>
                <div class="box-body" style="font-size:12px;">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <tbody id="option_container">                     
                        </tbody>
                    </table>
                </div>
            </div>
<div class="row" id="txtNoteHSBC" style="padding-left: 10%;padding-right: 10%">Nội dung: <b>-</b></div>
                  </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                       <button type="button" class="btn btn-primary" id="saveFileQD">Lưu quyết đinh</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>

             <!--  </div> -->
                
            </div>

      </div>
      
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-profile">
       <!-- <a href="/ho-so/lap-danh-sach/list"><b> Hồ sơ </b></a> / Danh sách hỗ trợ -->
    </div>
</div>

            <div class="box box-primary">
              <div class="col-sm-6 box-header">
                   <label  class="col-sm-3 control-label text-right">Năm học</label>
                      <div class="col-sm-3">
                        <select name='sltYear' class="selectpicker form-control" id='sltYear'>
                          <?php 
                              $namhoc = DB::table('qlhs_years')->orderBy('code','asc')->get();
                              $str_date = Carbon\Carbon::now()->format('Y') - 1;
                          ?>
                          <?php $__currentLoopData = $namhoc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option  value="<?php echo e($nam->code); ?>" <?php if(trim($nam->code) == trim($str_date)): ?> selected <?php endif; ?>><?php echo e($nam->name); ?></option>

                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          
                         </select>
                      </div>
                  </div> 
                  <div class="col-sm-3"></div>
                      <div class="box-header col-sm-3">
                          <div class="has-feedback">
                            <input id="txtSearchProfileLapdanhsach" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                          </div>
                        </div>
                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                  
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                      <thead >
                          <tr class="success">
                            <th class="text-center"  style="vertical-align:middle;width: 3%">STT</th>
                            <th class="text-center"  style="vertical-align:middle;width: 10%">Số công văn</th>
                            <th class="text-center"  style="vertical-align:middle;width: 15%">Tên công văn</th>
                            <th class="text-center"  style="vertical-align:middle;width: 20%">Chế độ</th>
                            <th class="text-center"  style="vertical-align:middle;width: 15%">Ghi chú</th>
                            <th class="text-center"  style="vertical-align:middle;width: 10%">Trạng thái</th>
                            <th class="text-center"  style="vertical-align:middle;width: 10%">Ngày tạo</th>
                            <th class="text-center"  style="vertical-align:middle;width: 5%">Chi tiết</th>
                          </tr>
                 
                      </thead>
                        <tbody id="dataLapDanhsachHS">                     
                        </tbody>
                    </table>
                    <div class="box-footer clearfix" id="divPaging">
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