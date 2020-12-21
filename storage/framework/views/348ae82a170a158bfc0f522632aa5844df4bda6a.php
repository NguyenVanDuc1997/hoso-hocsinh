<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo asset('dist/css/bootstrap-multiselect.css'); ?>">
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
    $('#btnPrint').click(function(){
        if($('#sltStatistic').val() == ""){
            utility.message("Thông báo","Xin mời chọn chế độ!",null,5000,1);
            $('select#sltStatistic').focus();
            return;
        }
        $('#btnPrint').button('loading');
          var o = {
            // truong_id : $('#sltSchool').val(),
            // hocky : $('#sltYear').val(),
            chedo : $('#sltStatistic').val(),
            // khoilop : $('#sltKhoiDt').val(),
            // tentruong : $('#sltSchool option:selected').text()
        };
        $.ajax('/ho-so/lap-danh-sach/ds-de-nghi/<?php echo e($congvan); ?>', {
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
            $('#btnPrint').button('reset');
          },error: function (val) {
              $('#btnPrint').button('reset');
          }
      });
    })
      $('#sltChedo').change(function(){
          var l = $(this).val();
          var toChoise = false;
          if(l != null && l.length > 1 && this.value){
            for (var i = 0; i < l.length; i++) {
                if(l[i] == 9 || l[i] == '9'){
                    utility.messagehide('group_message_THCD',"Chế độ hỗ trợ người nấu ăn lập riêng đề nghị.",1,5000);
                }
            }
          }
      });
    //Tổng hợp công văn 
      $('#btnTongHopCongVan').click(function(){
        
            if($('#txtTenCV').val() == "" || $('#txtTenCV').val() == null){
                utility.messagehide('group_message_TongHop',"Xin mời nhập tên công văn",1,5000);
                $('#txtTenCV').focus();
                return;
            }
            if($('#txtSoCV').val() == "" || $('#txtSoCV').val() == null){
                utility.messagehide('group_message_TongHop',"Xin mời nhập số công văn",1,5000);
                $('#txtSoCV').focus();
                return;
            }
            if($('#sltChedoTH').val() == "" || $('#sltChedoTH').val() == null){
                utility.messagehide('group_message_TongHop',"Xin mời chọn chế độ",1,5000);
                $('#sltChedoTH').focus();
                return;
            }
            if($('#sltCVGoc').val() == ""){
                utility.messagehide('group_message_TongHop',"Xin mời chọn công văn gốc",1,5000);
                $('#sltCVGoc').focus();
                return;
            }
            if($('#sltCVDieuChinh').val() == ""){
                utility.messagehide('group_message_TongHop',"Xin mời chọn công văn gốc",1,5000);
                $('sltCVDieuChinh').focus();
                return;
            }
            
            tonghopcongvan($('#txtTenCV').val(),$('#drSchoolTHCD').val(),$('#sltCVGoc').val(),$('#sltCVDieuChinh').val(),$('#sltChedoTH').val(),$('#sltYear').val(),$('#txtGhiChuTongHop').val(),$('#txtSoCV').val());
      });
    $('#sltChedoTH').change(function(){
      loadCongVanTongHop("sltCVGoc","sltCVDieuChinh",$('#drSchoolTHCD').val(),$('#sltYear').val(),$(this).val());
    });
    $('#event-thcd').hide();
   // $('.event-thcd').hide();
    $('#event-thcd-thuhoi').hide();
   // getUnitAll();
   // 
    
    var cvan = "<?php echo e($congvan); ?>";
    if(cvan === null || cvan === ''){
      $('.socongvan').attr('hidden','hidden');
      $('#sltLoaiCongvan').removeAttr('disabled');
      $('#sltCongvan').removeAttr('disabled');
      $('#drSchoolTHCD').removeAttr('disabled');
      $('#sltYear').removeAttr('disabled');
      // $('#event-thcd-all').html('<button type="button" onclick="openPopupTongHopCV()" class="btn btn-success" id =""><i class="glyphicon glyphicon-zoom-in"></i> Tổng hợp công văn</button>');
      autocompleteSearchDenghidalap('txtSearchCongVanDanhSach',1);
      GET_INITIAL_NGHILC();
      loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(),$('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());
    }else{

      $('.socongvan').removeAttr('hidden');
      autocompleteSearchDenghidalap('txtSearchCongVanDanhSach',2);
    //  autocompleteSearch("txtSearchProfilePhongSo", 3);
      $('#sltLoaiCongvan').attr('disabled','disabled');
      $('#sltCongvan').attr('disabled','disabled');
      // $('#drSchoolTHCD').attr('disabled','disabled');
      $('#sltYear').attr('disabled','disabled');
      loadLoaiCongVan($('#sltLoaiCongvan').val());
      GET_INITIAL_NGHILC();
      loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
    }
    $('#drSchoolTHCD').change(function() {
        GET_INITIAL_NGHILC();
        if(cvan === null || cvan === ''){
          loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(), $(this).val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());
        }else{
          loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
        }
    });
    
    $('#sltLoaiCongvan').change(function() {
       GET_INITIAL_NGHILC();
      if ($('#drSchoolTHCD').val() !== null && $('#drSchoolTHCD').val() !== "") {

        loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(),$('#drSchoolTHCD').val(),$(this).val());
      }
      else {
        loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(),0,$(this).val());
      }

        //loadLoaiCongVan($(this).val());
    });

    $('#sltYear').change(function() {
       GET_INITIAL_NGHILC();
      if ($('#drSchoolTHCD').val() !== null && $('#drSchoolTHCD').val() !== "") {

        loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(),$('#drSchoolTHCD').val(),$('#sltLoaiCongvan').val());
      }
      else {
        loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(),0,$('#sltLoaiCongvan').val());
      }

        //loadLoaiCongVan($(this).val());
    });

    $('#sltCongvan').change(function() {
      //loadLoaiCongVan($('#sltLoaiCongvan').val());
        GET_INITIAL_NGHILC();
        if($(this).val() != null && $(this).val() != ''){
            loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val());
        }else{
            loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(),$('#drSchoolTHCD').val(),$('#sltLoaiCongvan').val());
        }
        
    });

    // $("#fileAttack").filestyle({
    //   buttonText : 'Đính kèm',
    //   buttonName : 'btn-info'
    // });
    
    //loadComboboxHocky(3, function(){});

    $('#drPagingDanhsachtonghop').change(function() {
         GET_INITIAL_NGHILC();
        if($('#sltCongvan').val() != null && $('#sltCongvan').val() != ''){
            loadDanhSachHSDaLap($(this).val(), $('#txtSearchProfilePhongSo').val(),$('#sltTrangthai').val());
        }else{
            loadDanhSachByPhongSo($(this).val(),$('#drSchoolTHCD').val(),$('#sltLoaiCongvan').val());
        }
    });

    $('#sltTrangthai').change(function() {
         GET_INITIAL_NGHILC();
         loadDanhSachHSDaLap($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfilePhongSo').val(), $(this).val());
    });

   
    permission(function(){
          var html_view  = '';
          var html_view_thuhoi  = '';
          var html_view_title  = '';
          if(cvan === null || cvan === ''){
            html_view_title  = '<b> Hồ sơ </b> / Danh sách đề nghị';
          }else{
            html_view_title  = '<b> Hồ sơ </b> /<a href="/ho-so/lap-danh-sach/danh-sach-de-nghi"> Danh sách đề nghị </a> / <b>'+cvan + '</b> ';
          }
          $('#addnew-export-profile').html(html_view_title);
          
          if(check_Permission_Feature('1')){
            html_view += '<button type="button" onclick="openModalLapDS()" class="btn btn-success" id ="btnLapCongVan" title="Lập công văn gửi cấp trên"><i class="glyphicon glyphicon-upload"></i> Lập danh sách </button>';
          }

          $('#event-thcd').html(html_view);
      }, 97);

    

    $('#checkedAllApproved').change(function() {
        if ($('#checkedAllApproved').prop('checked'))
            $('[id*="chilApproveCheck"]').prop('checked', true);
        else
            $('[id*="chilApproveCheck"]').prop('checked', false);
    });
    openPopupTongHopCV = function(){
        if($('#drSchoolTHCD').val() == ""){
            utility.messagehide("messageValidateForm", "Xin mời chọn trường cần tổng hợp", 1, 5000);
            $('#drSchoolTHCD').focus();
            return;
        }
        if($('#sltYear').val() == ""){
            utility.messagehide("messageValidateForm", "Xin mời chọn năm học cần tổng hợp", 1, 5000);
            $('#sltYear').focus();
            return;
        }
        $('#sltChedoTH').selectpicker('deselectAll').selectpicker('refresh');
        $('#myModalTongHopCongVan').modal('show');
    }
    $('#checkedAllReverted').change(function() {
        if ($('#checkedAllReverted').prop('checked'))
            $('[id*="chilRevertCheck"]').prop('checked', true);
        else
            $('[id*="chilRevertCheck"]').prop('checked', false);
    });

  });

  function openModalUpdate(){
    $('#saveProfile').html("Cập nhật");
    $('.modal-title').html('Sửa hồ sơ học sinh');

     
    $("#myModalProfile").modal("show");
   }
  function openModalLapDS(){
    $('#sltChedo').selectpicker('deselectAll').selectpicker('refresh');
    $('#sltLoaiCVPopup').selectpicker('val',"1");
    $('#txtTenCVGui').val("");
    $('#txtGhiChuCVGui').val("");

    $("#myModalLapDanhSachPhongSo").modal("show");
  };

  

</script>

<div class="modal fade" id="myModalTongHopCongVan" role="dialog">
    <div class="modal-dialog modal-md" style="margin: 30px auto; width: 80%;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="">Tổng hợp công văn<label id=""></label></h4>
        </div>
<form class="form-horizontal" action="" id="frmPopupTHCD">  
        <input type="hidden" class="form-control" id="txtIdDS">          
                <div class="modal-body" style="font-size: 12px;padding: 5px;">
                    <div class="row" id="group_message_TongHop" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                      <div class="form-group">
                  <div class="col-sm-6" style="padding-left: 0">
                    <label  class="col-sm-12">Tên công văn</label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtTenCV" placeholder="Nhập tên công văn">
                    </div>
                  </div>
                  <div class="col-sm-6" style="padding-left: 0">
                    <label  class="col-sm-12">Số công văn</label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtSoCV" placeholder="Nhập số công văn">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4" style="padding-left: 0">
                    <label  class="col-sm-6 ">Chọn chế độ tổng hợp <font style="color: red">*</font></label>
                    <div class="col-sm-12">
                      <select name="sltChedoTH" id="sltChedoTH"  multiple="multiple" style="width: 100%;" class="selectpicker form-control" data-actions-box="true" data-live-search="true" multiple data-selected-text-format="count">
                        <!-- <option value="0">Chọn tất cả</option> -->
                        <option value="1">Miễn giảm học phí</option>
                        <option value="2">Chi phí học tập</option>
                        <option value="3">Hỗ trợ ăn trưa trẻ em mẫu giáo</option>
                        <option value="4">Hỗ trợ học sinh bán trú</option>
                        <option value="5">Hỗ trợ học sinh khuyết tật</option>
                        <option value="6">Hỗ trợ ăn trưa học sinh theo NQ57</option>
                        <option value="7">Hỗ trợ học sinh dân tộc thiểu số</option>
                        <option value="8">Hỗ trợ học bổng cho học sinh dân tộc nội trú</option>
                        <option value="9">Hỗ trợ người nấu ăn</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4" style="padding-left: 0">
                    <label  class="col-sm-12">Công văn gốc <font style="color: red">*</font></label>
                    <div class="col-sm-12" style="padding-right: 5px !important;">
                      <select name="sltCVGoc" id="sltCVGoc" style="width: 100%;" class="selectpicker form-control"  data-live-search="true" >
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4" style="padding-left: 0">
                    <label  class="col-sm-12">Công văn điều chỉnh<font style="color: red">*</font></label>
                    <div class="col-sm-12" style="padding-right: 5px !important;">
                      <select name="sltCVDieuChinh" id="sltCVDieuChinh" style="width: 100%;" class="selectpicker form-control"  data-live-search="true" >

                      </select>
                    </div>
                  </div>
                
                  
                </div>
                
                <div class="form-group">
                  <div class="col-sm-12" style="padding-left: 0">
                    <label  class="col-sm-6">Ghi chú </label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtGhiChuTongHop" placeholder="Nhập ghi chú">
                    </div>
                  </div>
                </div>
              
          </div></div>

                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnTongHopCongVan">Tổng hợp</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form> 
      </div>
      
    </div>
</div>
<div class="modal fade" id="myModalTHCD" role="dialog">
    <div class="modal-dialog modal-md" style="margin: 30px auto; width: 80%;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="">Thông tin chế độ của học sinh<label id="txtProfileNameThongtin"></label></h4>
        </div>
        <div class="box-body no-padding">
              
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message" style="margin-top: 10px;">
              <form class="form-horizontal" action="">         
                <div class="modal-body">
                    <div class="box-body">

                      <div class="box box-primary">

                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr class="success" id="">
                          <th  class="text-center" style="vertical-align:middle; width: 5%;">STT</th>
                          <th  class="text-center" style="vertical-align:middle">Tên chế độ</th>
                          <th  class="text-center" style="vertical-align:middle">Nhóm đối tượng</th>

                        </tr>
                 
                      </thead>
                        <tbody id="viewDanhsachCheDo">                     
                        </tbody>
                    </table>
                </div>       
            </div>



                  </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>

              </div>
                
            </div>
      </div>
      
    </div>
</div>


<div class="modal fade" id="myModalApproved" role="dialog">
    <div class="modal-dialog modal-md" style="margin: 30px auto; width: 80%;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="">Chọn chế độ duyệt cho học sinh <label id="txtProfileName"></label></h4>
        </div>
        <div class="box-body no-padding">
              
              <!-- /.mailbox-controls -->
              <!-- <div class="mailbox-read-message" style="margin-top: 10px;"> -->
              <form class="form-horizontal" action="">         
                <!-- <div class="modal-body"> -->
                    <div class="row" id="group_message_approved" style="padding-left: 10%;padding-right: 10%"></div>
                    <div class="box-body">

                      <div class="box box-primary">

                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr class="success" id="">
                          <th  class="text-center" style="vertical-align:middle; width: 5%;">STT</th>
                          <th  class="text-center" style="vertical-align:middle; width: 5%;"><input type="checkbox" name="checkedAllApproved" id="checkedAllApproved"></th>
                          <th  class="text-center" style="vertical-align:middle">Tên chế độ</th>
                          <th  class="text-center" style="vertical-align:middle">Nhóm đối tượng</th>

                        </tr>
                 
                      </thead>
                        <tbody id="dataListApproveCheDo">                     
                        </tbody>
                    </table>
                </div>       
            </div>
                <div class="row" id="txtNoteProfileCheDo" style="padding-left: 10%;padding-right: 10%">
                  </div>
                <div class="form-group">
                  <div class="col-sm-12" style="padding-left: 0">
                    <label  class="col-sm-6">Ghi chú </label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtGhiChuTHCD" placeholder="Nhập ghi chú">
                    </div>
                  </div>
                </div>


                  </div>   
             <!--    </div> -->
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnApprovedCheDoPhongSo">Lưu</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>

          <!--     </div> -->
                
            </div>
      </div>
      
    </div>
</div>

<div class="modal fade" id="myModalRevert" role="dialog">
    <div class="modal-dialog modal-md" style="margin: 30px auto; width: 80%;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="">Chọn chế độ trả lại của học sinh <label id="txtProfileNameTraLai"></label></h4>
        </div>
        <div class="box-body no-padding">
              
              <form class="form-horizontal" action="">         
                    <div class="row" id="group_message_approved" style="padding-left: 10%;padding-right: 10%"></div>
                    <div class="box-body">

                      <div class="box box-primary">

                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr class="success" id="">
                          <th  class="text-center" style="vertical-align:middle; width: 5%;">STT</th>
                          <th  class="text-center" style="vertical-align:middle; width: 5%;"><input type="checkbox" name="checkedAllReverted" id="checkedAllReverted"></th>
                          <th  class="text-center" style="vertical-align:middle">Tên chế độ</th>
                          <th  class="text-center" style="vertical-align:middle">Nhóm đối tượng</th>

                        </tr>
                 
                      </thead>
                        <tbody id="dataListRevertCheDo">                     
                        </tbody>
                    </table>
                </div>       
            </div>
<div class="row" id="txtNoteProfileTraLai" style="padding-left: 10%;padding-right: 10%">
                  </div>
                <div class="form-group">
                  <div class="col-sm-12" style="padding-left: 0">
                    <label  class="col-sm-6">Ghi chú </label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtGhiChuTHCDTraLai" placeholder="Nhập ghi chú">
                    </div>
                  </div>
                </div>


                  </div>   

                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnRevertedCheDoPhongSo">Lưu</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
            </div>
      </div>
      
    </div>
</div>

<div class="modal fade" id="myModalCheDoIn" role="dialog">
    <div class="modal-dialog modal-md" style="margin: 30px auto;">
        <div class="modal-content box">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="">Chọn báo cáo đối tượng</h4>
            </div>
            <div class="box-body no-padding">
                <form class="form-horizontal" action="">         
                    <div class="row" id="group_message_print" style="padding-left: 10%;padding-right: 10%"></div>
                    <div class="box-body">
                        <div class="box box-primary">
                            <div class="box-body" style="font-size:12px;max-width: 100%">
                                <label  class="col-sm-12">Loại báo cáo</label>
                                <div class="col-sm-12">
                                  <select name='sltStatistic' class="form-control selectpicker" id='sltStatistic'>
                                    <?php if(isset($report_type) && $report_type != 'NGNA'): ?>
                                    <option value="" selected="selected">-- Chọn loại báo cáo --</option>
                                    <option value="1">Miễn giảm học phí</option>
                                    <option value="2">Chi phí học tập</option>
                                     <option value="3">Hỗ trợ ăn trưa trẻ em mẫu giáo</option>
                                    <option value="4">Hỗ trợ học sinh bán trú</option> 
                                    <option value="5">Hỗ trợ học sinh khuyết tật</option>
                                     <option value="6">Hỗ trợ ăn trưa học sinh theo NQ57</option>
                                     <?php else: ?>
                                     <option value="7" selected>Hỗ trợ kinh phí thuê khoán nấu ăn</option>
                                     <?php endif; ?>
                                    <!--<option value="7">Hỗ trợ học sinh dân tộc thiểu số</option>
                                    <option value="8">Hỗ trợ học bổng cho học sinh dân tộc nội trú</option> -->
                                  </select>
                              </div>
                            </div>       
                        </div>
                    </div>                     
                    <div class="modal-footer">
                        <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnPrint">In</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>

              <!-- </div> -->
                
            </div>
      </div>
      
    </div>
</div>
<div class="modal fade" id="myModalReload" role="dialog">
    <div class="modal-dialog modal-md" style="margin: 30px auto; width: 80%;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="">Chọn chế độ trả lại của học sinh <label id="txtProfileName"></label></h4>
        </div>
        <div class="box-body no-padding">
              
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message" style="margin-top: 10px;">
              <form class="form-horizontal" action="">         
                <div class="modal-body">
                    <div class="row" id="group_message_approved" style="padding-left: 10%;padding-right: 10%"></div>
                    <div class="box-body">

                      <div class="box box-primary">

                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr class="success" id="">
                          <th  class="text-center" style="vertical-align:middle; width: 5%;">STT</th>
                          <th  class="text-center" style="vertical-align:middle; width: 5%;"><input type="checkbox" name="checkedAllReload" id="checkedAllReload"></th>
                          <th  class="text-center" style="vertical-align:middle">Tên chế độ</th>
                          <th  class="text-center" style="vertical-align:middle">Nhóm đối tượng</th>

                        </tr>
                 
                      </thead>
                        <tbody id="dataListReloadCheDo">                     
                        </tbody>
                    </table>
                </div>       
            </div>

                <div class="form-group">
                  <div class="col-sm-12" style="padding-left: 0">
                    <label  class="col-sm-6">Ghi chú </label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtGhiChuTHCD" placeholder="Nhập ghi chú">
                    </div>
                  </div>
                </div>


                  </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnReloadCheDoPhongSo">Lưu</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>

              </div>
                
            </div>
      </div>
      
    </div>
</div>

<div class="modal fade" id="myModalThongtin" role="dialog">
    <div class="modal-dialog modal-md" style="margin: 30px auto; width: 80%;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="">Thông tin chế độ của học sinh <label id="txtProfileNameThongtin"></label></h4>
        </div>
        <div class="box-body no-padding">
              
              <!-- /.mailbox-controls -->
              <!-- <div class="mailbox-read-message" style="margin-top: 10px;"> -->
              <form class="form-horizontal" action="">         
                <!-- <div class="modal-body"> -->
                    <div class="box-body">

                      <div class="box box-primary">

                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                        <tr class="success" id="">
                          <th  class="text-center" style="vertical-align:middle; width: 5%;">STT</th>
                          <th  class="text-center" style="vertical-align:middle">Tên chế độ</th>
                          <th  class="text-center" style="vertical-align:middle">Nhóm đối tượng</th>

                        </tr>
                 
                      </thead>
                        <tbody id="dataListThongtinCheDo">                     
                        </tbody>
                    </table>
                </div>       
            </div>

                <!-- <div class="form-group">
                  <div class="col-sm-12" style="padding-left: 0">
                    <label  class="col-sm-6">Ghi chú </label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtGhiChuTHCD" placeholder="Nhập ghi chú">
                    </div>
                  </div>
                </div> -->
                <div class="row" id="txtNoteProfile" style="padding-left: 10%;padding-right: 10%">
                  </div>

                  </div> 
                    
              <!--   </div> -->
                <div class="modal-footer">
                    <div class="row text-center">
                        <!-- <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnRevertedCheDoPhongSo">Lưu</button> -->
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>

              <!-- </div> -->
                
            </div>
      </div>
      
    </div>
</div>

<div class="modal fade" id="myModalLapDanhSachPhongSo" role="dialog">
    <div class="modal-dialog modal-md" style="width: 80%;margin: 10px auto;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="">Lập danh sách đề nghị</h4>
        </div>
        <form class="form-horizontal" action="" id="frmPopupTHCD">  
        <input type="hidden" class="form-control" id="txtIdDS">          
                <div class="modal-body" style="font-size: 12px;padding: 5px;">
                    <div class="row" id="group_message_THCD" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                <div class="form-group">
                  <div class="col-sm-4" style="padding-left: 0">
                    <label  class="col-sm-12">Tên công văn</label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtTenCVGui" placeholder="Nhập tên công văn">
                    </div>
                  </div>
                  <div class="col-sm-4" style="padding-left: 0">
                      <label class="col-sm-12">Số công văn <font style="color: red">(Viết liền không dấu)</font></label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control" id="txtSoCongVan" placeholder="Nhập số công văn" onkeyup="input_alias(this)">
                      </div>
                  </div>
                  <div class="col-sm-4" style="padding-left: 0">
                    <label  class="col-sm-12">Chọn loại công văn lập <font style="color: red">*</font></label>
                    <div class="col-sm-12" style="padding-right: 5px !important;">
                      <select name="sltLoaiCVPopup" id="sltLoaiCVPopup" style="width: 100%;" class="selectpicker form-control" >
<!--                         <option value=''>-- Chọn loại công văn lập --</option>;
                        <option value='1'>Công văn phê duyệt</option>;
                        <option value='2'>Công văn trả lại</option>; -->
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-4" style="padding-left: 0">
                    <label  class="col-sm-6 ">Chọn chế độ <font style="color: red">*</font></label>
                    <div class="col-sm-12">
                      <select name="sltChedo" id="sltChedo"  multiple="multiple" style="width: 100%;" class="selectpicker form-control" data-actions-box="true" data-live-search="true" multiple data-selected-text-format="count">
                        <!-- <option value="0">Chọn tất cả</option> -->
                        <option value="1">Miễn giảm học phí</option>
                        <option value="2">Chi phí học tập</option>
                        <option value="3">Hỗ trợ ăn trưa trẻ em mẫu giáo</option>
                        <option value="4">Hỗ trợ học sinh bán trú</option>
                        <option value="5">Hỗ trợ học sinh khuyết tật</option>
                        <option value="6">Hỗ trợ ăn trưa học sinh theo NQ57</option>
                        <option value="7">Hỗ trợ học sinh dân tộc thiểu số</option>
                        <option value="8">Hỗ trợ học bổng cho học sinh dân tộc nội trú</option>
                        <option value="9">Hỗ trợ người nấu ăn</option>
                      </select>
                    </div>
                  </div>
                  
                </div>
                
                <div class="form-group">
                  <div class="col-sm-12" style="padding-left: 0">
                    <label  class="col-sm-6">Ghi chú </label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtGhiChuCVGui" placeholder="Nhập ghi chú">
                    </div>
                  </div>
                </div>
              
          </div></div>

                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnLapDSPhongSo">Lập danh sách</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="modal fade" id="myModalDetail" role="dialog">
    <div class="modal-dialog modal-md" style="width: 100%;margin: 10px auto;">

      <!-- Modal content-->
      <div class="modal-content box">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="">Tổng chi phí công văn</h4>
          </div>
          <div class="box-body no-padding">
              <form class="form-horizontal" action="">
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
                  <?php if($congvan != null && $report_type != 'NGNA'): ?>
                  <div class="box box-primary">
                      <div class="box-header with-border">
                          <h3 class="box-title">Tài liệu đính kèm</h3>
                      </div>
                      <div class="box-body" style="font-size:12px;">
                          <table class="table table-striped table-bordered table-hover dataTable no-footer">
                              <tbody id="option_container">
                              </tbody>
                          </table>
                      </div>
                  </div>
                  <?php endif; ?>
                  <div class="row" id="txtNoteHSBC" style="padding-left: 10%;padding-right: 10%">Nội dung: <b>-</b></div>
                  <!--       </div>   -->
                  <!--      </div> -->
                  <div class="modal-footer">
                      <div class="row text-center">
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
    <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" id="formtonghopchedo">
            <div class="row" id="messageValidateForm" style="padding-left: 10%;padding-right: 10%"></div> 
              <div class="box-body">
                <div class="form-group">
                  
                    <div class="col-sm-3">
                      <label  class="col-sm-6 ">Chọn trường <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                         <select name="drSchoolTHCD" id="drSchoolTHCD" class="form-control selectpicker" <?php if(count(explode('-',Auth::user()->truong_id)) > 1): ?> data-live-search="true" <?php endif; ?>>
                         <?php 
                          $sc = null;
                          if($congvan != null && $congvan != ''){
                              $sc =  explode(',',$id_truong);
                          }else{
                              $sc = explode('-',Auth::user()->truong_id);
                          }

                          $qlhs_schools = DB::table('qlhs_schools')
                          ->where('schools_active',1)
                          ->whereIn('schools_id',$sc)
                          ->select('schools_id','schools_name')->get();
                            if(count($sc) >= 1){
                              ?>
                                <option value="" selected="selected">-- Chọn trường học --</option>
                              <?php
                            }
                          
                         ?>
                         
                         <?php $__currentLoopData = $qlhs_schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($id_truong == $val->schools_id || (Auth::user()->level == 1 )): ?>
                              <option value="<?php echo e($val->schools_id); ?>" selected="selected"><?php echo e($val->schools_name); ?></option>
                            <?php else: ?>
                            <option value="<?php echo e($val->schools_id); ?>" ><?php echo e($val->schools_name); ?></option>
                             <?php endif; ?>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                       </select>
                         <input type="hidden" name="SchoolByCongVan" id="SchoolByCongVan" value="<?php echo e($id_truong); ?>">
                      </div>
                    </div>
                   
                    <div class="col-sm-3">
                      <label  class="col-sm-6">Loại công văn <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <select name='sltLoaiCongvan' class="form-control selectpicker" id='sltLoaiCongvan'>

                          <?php 
                            if($status == 2){
                             ?>
                              <option value=''>Tất cả</option>
                                <option value='1'>Công văn phê duyệt</option>
                                <option value='2'  selected="selected">Công văn trả lại</option>
                                <option value='3'>Công văn yêu cầu thu hồi</option>
                              <?php 
                            }else if($status == 1 || $status == 0 || $status == 4){
                             ?>
                                <option value=''>Tất cả</option>
                                <option value='1' selected="selected">Công văn phê duyệt</option>
                                <option value='2'>Công văn trả lại</option>
                                <option value='3'   >Công văn yêu cầu thu hồi</option>
                             <?php 
                            }else if($status == 3){
                             ?>
                                <option value=''>Tất cả</option>
                                <option value='1'>Công văn phê duyệt</option>
                                <option value='2'>Công văn trả lại</option>
                                <option value='3'   selected="selected">Công văn yêu cầu thu hồi</option>
                             <?php 
                            }else{
                               ?>
                                <option value='' selected="selected">Tất cả</option>
                                <option value='1' >Công văn phê duyệt</option>
                                <option value='2'>Công văn trả lại</option>
                                <option value='3'>Công văn yêu cầu thu hồi</option>
                          <?php 
                            }
                          ?>
                            
                        </select>
                        <input type="hidden" name="TypeByCongVan" id="TypeByCongVan" value="<?php echo e($status); ?>">
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <label  class="col-sm-6 ">Năm học <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <select name='sltYear' class="form-control selectpicker"  data-live-search="true" id='sltYear'>
                            <?php 
                              $getYear = DB::table('qlhs_years')->select('name','code')->get();
                              $year = Carbon\Carbon::now()->format('Y');
                              if(Carbon\Carbon::now()->format('m') >= 1 && Carbon\Carbon::now()->format('m') <= 8){
                                $year = $year - 1;
                              }
                              
                              if($congvan != null && $congvan != ''){
                                  $year = $report_year;
                              }
                            ?>
                            <?php $__currentLoopData = $getYear; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                  <option value="<?php echo e($val->code); ?>" <?php if($year == $val->code): ?> selected="selected" <?php endif; ?>><?php echo e($val->name); ?></option>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2 socongvan">
                      <label  class="col-sm-6">Số công văn <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <select name='sltCongvan' class="form-control selectpicker"  data-live-search="true" id='sltCongvan' >
                            <?php if($congvan != null && $congvan != ''): ?>
                              <?php 
                              $truong = explode(',', $id_truong);
                              $getData = DB::table('qlhs_hosobaocao')
                                    ->whereIn('report_id_truong', $truong)
                                    ->where('report_cap_status', $status)
                                    ->groupBy('report_name')
                                    ->select(
                                        DB::raw('GROUP_CONCAT(report_id_truong) as report_id_truong'),
                                        DB::raw('MAX(report_cap_status) as report_cap_status'),
                                        DB::raw('GROUP_CONCAT(report_name_text) as report_name_text'),
                                        'report_name', 'report_name as report_value',
                                        DB::raw('MAX(report_status) as report_status')
                                        )
                                    ->orderBy('report_date', 'desc')->get();

                              // $getData = DB::table('qlhs_hosobaocao')
                              //     ->where('report_id_truong', $id_truong)
                              //     ->where('report_cap_status', $status)
                              //    // ->where('report_cap_nhan', Auth::user()->level)
                              //     ->select('report_name', 'report_name as report_value')
                              //     ->orderBy('report_date', 'desc')->get();
                              ?>
                              <option value="" >-- Chọn công văn --</option>
                              <?php $__currentLoopData = $getData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($congvan == $val->report_value): ?>
                                  <option value="<?php echo e($val->report_value); ?>" selected="selected"><?php echo e($val->report_name); ?></option>
                               <?php else: ?>
                                <option value="<?php echo e($val->report_value); ?>" ><?php echo e($val->report_name); ?></option>
                                 <?php endif; ?>
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <input type="hidden" name="ByCongVan" id="ByCongVan" value="<?php echo e($congvan); ?>">
                      </div>
                    </div>
                    <?php if(Auth::user()->level != 1): ?>
                    <div class="col-sm-2" >
                      <label  class="col-sm-6 event-thcd">Chức năng</label>
                      <div class="col-sm-12" id="event-thcd">

                      </div>
                      <div class="col-sm-12" id="event-thcd-all">

                      </div>
                    </div>
                    <?php endif; ?>
                </div>
              
                  </div>
            </form>
          </div>
          
                <?php 
                    $text = explode(',',$report_name_text);
                    sort($text);
                    $report_text = '';
                    $data = '';
                    foreach ($text as $key => $value) {
                      if($data != trim($value)){
                        $report_text .= trim($value).',';
                        
                      }
                      $data = trim($value);
                    }
                    $report_text = substr($report_text,0,strlen($report_text) - 1); 
                ?>
          <?php if($report_status == 2 || $report_status == 0): ?>
          <div class="box box-primary">
              <div style="padding: 15px;">
                  <div class="box-header with-border" style="padding: 15px;border: 1px solid #ddd;border-radius: 5px;">
                    <p><?php if($report_status == 2): ?> <strong>Công văn bổ sung:</strong>  <?php elseif($report_status == 0): ?> <strong>Công văn: </strong> <?php else: ?> <strong>Danh sách công văn đề nghị: </strong> <?php endif; ?> <?php echo e($report_text); ?></p>
                    <?php if(isset($note) && $note != ''): ?><p><strong>Ghi chú:</strong> <?php echo e($note); ?></p><?php endif; ?>
                  </div>
              </div> 
          </div>
          <?php endif; ?>
          <div class="box box-primary">
            <?php if($report_status != 2 && $report_status != 0): ?>
                <div class="col-sm-7">
                  <div class="box-header with-border">
                    <p><strong>Danh sách công văn đề nghị</strong></p>
                  </div>

                </div> 
            <?php endif; ?>
            <div class="box-header col-sm-2 text-center" >
                  <?php if($congvan != null && $report_status != 2 && $status != 2): ?>
                  
                    <button class="btn btn-default"  onclick="openPopupDetail('<?php echo e($id_truong); ?>','<?php echo e($congvan); ?>',<?php echo e($status); ?>,1)" id="editor_editss" title="Thông tin chi tiết"><i class='glyphicon glyphicon-eye-open'></i> Chi tiết </button>
                    <button  type="button" class="btn btn-info" onclick="$('#myModalCheDoIn').modal('show');" ><i class="fa fa-print" aria-hidden="true"></i> In</button>
                  <?php endif; ?>
                  
                  </div>
                      <div class="box-header col-sm-3 pull-right">
                          <div class="has-feedback">
                            <input id="txtSearchCongVanDanhSach" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                          </div>
                        </div>
                <div class="box-body" style="font-size:12px; max-width: 100%">
                 
                  
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                      <thead id="cmisGridHeader">
                        
                         
                        </tr>
                 
                      </thead>
                        <tbody id="tbListDanhSach">                     
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                      <div class="row">
                       
                          <div class="col-md-3">
                            <?php if($congvan == null): ?>
                            <p class="menu-bottom ">
                            <i class="cvbt glyphicon glyphicon-flag"></i> : Công văn bình thường
                                </p>
                        <!--     <p class="menu-bottom">
                            <i class="cvdc glyphicon glyphicon-flag"></i> : Công văn điều chỉnh
                             </p> -->
            <!--                      <p class="menu-bottom">
                            <i class="cvth glyphicon glyphicon-flag"></i> : Công văn tổng hợp
                            </p> -->
                            <p class="menu-bottom">
                                <i class="fa fa-eye" aria-hidden="true"></i> : Báo cáo nhân viên dinh dưỡng
                            </p>
                            <?php else: ?>
                              <?php if($report_type != 'NGNA'): ?>
                                <?php if($status != 2 && ($report_status == 0 || $report_status == 1)): ?>
                                  <p class="menu-bottom ">
                                  <i class="duyet glyphicon glyphicon-star"></i> : Chế độ duyệt
                                      </p>
                                  <p class="menu-bottom">
                                  <i class="tralai glyphicon glyphicon-star"></i> : Chế độ trả lại
                                   </p>
                                 <?php elseif($report_status == 2): ?>
                                  <p class="menu-bottom ">
                                  <i class="bosung glyphicon glyphicon-star"></i> :HS Điều chỉnh bổ sung
                                      </p>
                                  <p class="menu-bottom">
                                  <i class="giam glyphicon glyphicon-star"></i> :HS Điều chỉnh giảm
                                   </p>
                                <?php endif; ?>
                              <?php endif; ?>
                            <?php endif; ?>
                          </div>
                    
                          <div class="col-md-1">
                              <label class="text-right col-md-6 control-label">Tổng </label>
                              <label class="col-md-4 control-label g_countRowsPaging">0</label>
                          </div>
                          <div class="col-md-2">
                              <label class="col-md-4 control-label text-right">Trang </label>
                              <div class="col-md-6">
                                  <select class="form-control input-sm g_selectPaging">
                                      <option value="0">0 / 20 </option>
                                  </select>
                              </div>
                          </div>
                          <div class="col-md-2">
                                        <label  class="col-md-4 control-label text-right">Hiển thị: </label>
                                        <div class="col-md-5">
                                          <select name="drPagingDanhsachtonghop" id="drPagingDanhsachtonghop"  class="selectpicker form-control input-sm pagination-show-row">
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