 <?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<section class="content">
    <script type="text/javascript">
        $(function() {
            // getUnitAll();
            $('#sltKhoiLop').change(function() {
                GET_INITIAL_NGHILC();
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            });

            //autocompleteGiamHo();

            $('#drPagingDanhsachtonghop').change(function() {
                GET_INITIAL_NGHILC();
                loaddataDanhSachTongHop($(this).val(), $('#txtSearchProfile').val());
            });
            //  status: $('#sltStatus').val(),
            $('#sltStatus').change(function() {
                GET_INITIAL_NGHILC();
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $(this).val());
            });

            var pageing = $('#drPagingDanhsachtonghop').val();

            permission(function() {
                var html_view = '';
                var html_view_header = '<b> Hồ sơ </b> / Xét duyệt học sinh';

                if (check_Permission_Feature('5')) {
                    html_view += '<button type="button" data-loading-text="Đang tải dữ liệu" class="btn btn-success" id ="btnLoadDataTruong"><i class="glyphicon glyphicon-search"></i> Xem </button>';
                    // html_view += '<button type="submit" class="btn btn-info" id ="btnSendNGNA"><i class="glyphicon glyphicon-send"></i> Gửi danh sách</button>';
                }

                if (check_Permission_Feature('1')) {
                    // html_view_header += '<a onclick="openModalAdd()" style="margin-left: 10px" class="btn btn-success btn-xs pull-right"> <i class="glyphicon glyphicon-plus"></i> Tạo mới </a >';

                    html_view += '<button type="button" onclick="openPopupLapTHCD()" class="btn btn-success" id =""><i class="glyphicon glyphicon-ok"></i> Lập danh sách </button>';

                    // html_view += '<button type="button" onclick="loaddataBaocaoTongHop(10)" class="btn btn-success" id =""><i class="glyphicon glyphicon-search"></i> Xem danh sách</button>';

                    // html_view += '<button type="button" onclick="approvedAll(1)" class="btn btn-success" id =""><i class="glyphicon glyphicon-pencil"></i> Duyệt toàn bộ</button>';

                    // html_view += '<button type="button" onclick="approvedUnAll(1)" class="btn btn-success" id =""><i class="glyphicon glyphicon-pencil"></i> Hủy duyệt toàn bộ</button>';
                }
                $('#addnew-export-profile').html(html_view_header);
                // $('#event-thcd').html(html_view);
            }, 91);

            autocompleteSearch("txtSearchProfile", 1);

            if (($('#drSchoolTHCD').val() !== null && $('#drSchoolTHCD').val() !== "" && $('#drSchoolTHCD').val() > 0) && ($('#sltYear').val() !== null && $('#sltYear').val() !== "")) {
                loadDataDSDNPD();
            } else {
                $('#dataDanhsachTonghop').html("<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>");
            }

            $('#drSchoolTHCD').change(function() {
                loadDataDSDNPD();
            });

            $('#sltYear').change(function() {
                var dt = parseInt($(this).val().split('-')[1]);
                $('#sltNamHoc').val(dt+'-'+(dt+1));
                GET_INITIAL_NGHILC();
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val(), $('#sltTrangthai').val());
            });

            $('#checkedAllChedo').click(function() {
                if ($(this).is(':checked')) {
                    $('.chilCheck').prop('checked', true);
                    $('.chilCheck').removeAttr('disabled');
                    $('#UnCheckProfile').attr('disabled', 'disabled');
                    $('#UnCheckProfile').prop('checked', false);
                } else {
                    $('.chilCheck').prop('checked', false);
                    $('.chilCheck').removeAttr('disabled');
                    $('#UnCheckProfile').removeAttr('disabled');
                    $('#UnCheckProfile').prop('checked', false);
                }

            });

            // check all lập đề nghị

            btnAllCheDoHocKy = function() {
                if (!$('#cbxAllChedoHK').prop('checked')) {
                    $('input#cbxAllChedoHK').prop('checked', true);
                    $('.css-label').attr("title", "Bỏ xử lý tất cả");
                    approvedAll(1);
                } else {
                    $('input#cbxAllChedoHK').prop('checked', false);
                    $('.css-label').attr("title", "Xử lý tất cả");
                    approvedUnAll(1);
                }
                GET_INITIAL_NGHILC();
                loaddataDanhSachTongHop($('#drPagingDanhsachtonghop').val(), $('#txtSearchProfile').val());
            };
        });

        var close = true;

        function viewMoreProfile() {
            if (close) {
                $('#tableMoreProfile').removeAttr('hidden');
                close = false;
            } else {
                $('#tableMoreProfile').attr('hidden', 'hidden');
                close = true;
            }
        }
        function openModalUpdate() {
            $('#saveProfile').html("Cập nhật");
            $('.modal-title').html('Sửa hồ sơ học sinh');
            $('#nextChangeSubject').html('Thay đổi đối tượng');
            $('#nextSite').html('Thay đổi hộ khẩu');
            $("#myModalProfile").modal("show");
        }

        function openModalUpto() {
            var t = $('#uptoClass').DataTable().clear().draw().destroy();
            resetControl();
            $('#upClass-select-all').prop('checked', false);
            loading();
            loadComboxTruongHoc("drSchoolUpto", function() {
                closeLoading();
            }, $('#school-per').val());
            $("#myModalUpto").modal("show");
        }

        function openModalHistory() {
            $("#myHistory").modal("show");
        }
    </script>
    <!-- /// Xem thông tin học sinh /// -->
    <div class="modal fade" id="myModalProfile" role="dialog">
        <div class="modal-dialog modal-md" style="width: 80%;margin: 30px auto;">
         <!-- Modal content-->
         <div class="modal-content box">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Thêm mới học sinh</h4>
            </div>
            <form class="form-horizontal">
               <input type="hidden" id="txtHistoryId" name="txtHistoryId">
               <input type="hidden" id="txtProfileId" name="txtProfileId">
               <input type="hidden" id="txtHistoryYear" name="txtHistoryYear">
               <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>
               <div class="box-body">
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Họ và tên <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtNameProfile" placeholder="Nhập tên học sinh">
                     </div>
                  </div>
                  <div class="col-sm-6" style="padding-left: 0">
                     <label  class="col-sm-12 ">Trường <font style="color: red" id="messSchool">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
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
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Lớp học <font style="color: red" id="messClass">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltLop" disabled="disabled" id="sltLop" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           <option value="">--Chọn lớp--</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Ngày sinh <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtBirthday" placeholder="ngày-tháng-năm" onblur="validBirthday(this)">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Năm nhập học <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtYearProfile" name="txtYearProfile" placeholder="tháng-năm" onblur="validYearProfile(this)">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Dân tộc <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltDantoc" id="sltDantoc" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           <?php 
                              $qlhs_nationals = DB::table('qlhs_nationals')->where('nationals_active',1)
                                ->select('nationals_id','nationals_name')->get();
                              
                              ?>
                           <?php $__currentLoopData = $qlhs_nationals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <option value="<?php echo e($val->nationals_id); ?>" ><?php echo e($val->nationals_name); ?></option>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Chủ hộ</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtParent" placeholder="Chủ hộ" autocomplete="on">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Cha mẹ/người giám hộ</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtGuardian" placeholder="Cha mẹ hoặc người giám hộ" >
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">STT DS hộ nghèo</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtSTTHoNgheo" placeholder="Số TT DS Hộ nghèo" >
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Tỉnh/thành <font style="color: red">*</font><span id="url_hokhau"></span></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" name="sltTinh" id="sltTinh" class="form-control" disabled="disabled">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Quận/huyện <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" name="sltQuan" id="sltQuan" class="form-control" disabled="disabled">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Phường/xã <font style="color: red">*</font></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" name="sltPhuong" id="sltPhuong" class="form-control" disabled="disabled">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12">Thôn xóm</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" name="txtThonxom" id="txtThonxom" class="form-control" disabled="disabled">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Chế độ 116 </label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltBantru" id="sltBantru" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           <option value="-1" selected="selected">-- Chọn --</option>
                           <option value="0">Ở ngoài trường</option>
                           <option value="1">Ở trong trường</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 " >Nhà ở xa trường</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="txtKhoangcach" placeholder="Nhập số km">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">GT cách trở, đi lại khó khăn</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <input type="text" class="form-control" id="drGiaoThong" placeholder="Nhập số km">
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Ở trong trường </label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <label for="ckbNQ57" class="btn btn-default">Nghị Quyết 57 <input  type="checkbox" id="ckbNQ57" class="badgebox"><span class="badge">&check;</span></label>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0" id="dirDoiTuong">
                     <label  class="col-sm-12 ">Thuộc đối tượng <span id="url_hocsinh"></span></label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltDoituong" id="sltDoituong" multiple="multiple" style="width: 100%;" class="selectpicker form-control" data-actions-box="true" data-live-search="true" multiple data-selected-text-format="count">
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0"  id="divKyHoc">
                     <label  class="col-sm-12 ">Kỳ học</label>
                     <div class="col-sm-12" style="padding-right: 0;padding-left: 0;">
                        <select name="sltHocky" id="sltHocky" class="form-control selectpicker"  data-live-search="true" style="width: 100% !important">
                           <option value="" selected="selected">-- Chọn kỳ học --</option>
                           <option value="HK1">Học kỳ 1</option>
                           <option value="HK2">Học kỳ 2</option>
                           <option value="CA">Cả năm</option>
                        </select>
                     </div>
                  </div>
               </div>

               <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%">
                  <div id="messageDangersdivKyHoc"></div>
               </div>
               <div class=" box box-body" id="tbMoney" hidden="hidden" style="font-size: 12px;overflow: auto;">
                  <table   class="table table-striped table-bordered table-hover dataTable no-footer">
                     <thead>
                        <tr class="success">
                           <th class="text-center" style="vertical-align:middle;width: 1%">STT</th>
                           <th class="text-left" style="vertical-align:middle;width: 20%">Chế độ/ chính sách được hưởng </th>
                           <th class="text-center" style="vertical-align:middle;width: 5%">Số tiền </th>
                        </tr>
                     </thead>
                     <tbody id="tbMoneyContent">
                     </tbody>
                  </table>
               </div>
               <div class="col-sm-3" style="text-decoration: underline;">
                  <a id="attachHS" onclick="viewMoreProfile()" style="cursor: pointer;"><i class="glyphicon glyphicon-paperclip"></i> Đính kèm thêm tài liệu</a>
               </div>
               <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%">
                  <div id="messageDangersQD"></div>
               </div>
               <div class=" box box-body" id="tableMoreProfile" hidden="hidden" style="font-size: 12px;overflow: auto;">
                  <table   class="table table-striped table-bordered table-hover dataTable no-footer">
                     <thead>
                        <tr class="success">
                           <th class="text-center" style="vertical-align:middle;width: 1%">STT</th>
                           <th class="text-center" style="vertical-align:middle;width: 1%">X</th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Loại quyết định <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Mã quyết định <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Số/kí hiệu <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Cơ quan xác nhận <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Ngày xác nhận <font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 15%">Đính kèm<font style="color: red">*</font></th>
                           <th class="text-center" style="vertical-align:middle;width: 10%">File </th>
                        </tr>
                     </thead>
                     <tbody id="tbDecided">
                        <tr id="trContent">
                        </tr>
                     </tbody>
                  </table>
                  <div class="col-sm-2"> 
                     <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="btnAddNewRow">Thêm tài liêu</button>
                  </div>
                  <div class="col-sm-3"> 
                     <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="clearFile">Xóa tệp file chọn</button>
                  </div>
               </div>
               <div class="modal-footer">
                  <div class="row text-center">
                     <button type="button" data-loading-text="Đang tải dữ liệu" class="btn btn-primary" id ="saveProfileNew">Lưu</button>
                     <button type="button" class="btn btn-primary"  data-dismiss="modal">Đóng</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
    <!-- /// kết thúc phần thông tin học sinh /// -->
    <!-- ////////////////////////////////////////////////////////////////Phần hồ sơ///////////////////////////////////////////////////////////// -->
    <div class="modal fade" id="myModalApproved" role="dialog">
        <div class="modal-dialog modal-md" style="margin: 30px auto; width: 80%;">
            <!-- Modal content-->
            <div class="modal-content box">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="">Chọn chế độ được hưởng cho học sinh <label id="txtProfileName"></label></h4>
                </div>
                <div class="box-body no-padding">
                    <!-- /.mailbox-controls -->
                    <!-- <div class="mailbox-read-message" style="margin-top: 10px;"> -->
                    <form class="form-horizontal" action="">
                        <!--  <div class="modal-body"> -->
                        <div class="row" id="group_message_approved" style="padding-left: 10%;padding-right: 10%"></div>
                        <div class="box-body">
                            <div class="box box-primary">
                                <div class="box-body" style="font-size:12px">
                                    <table class="table table-striped table-bordered table-hover dataTable no-footer" id="DanhsachCheDo">
                                        <thead>
                                            <tr class="success" id="cmisGridHeader">
                                                <th class="text-center" style="vertical-align:middle">STT</th>
                                                <th class="text-center" style="vertical-align:middle">
                                                    <input type="checkbox" name="checkedAllChedo" id="checkedAllChedo">
                                                </th>
                                                <th class="text-center" style="vertical-align:middle">Tên chế độ</th>
                                                <th class="text-center" style="vertical-align:middle">Nhóm đối tượng</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dataDanhsachCheDo">
                                        </tbody>
                                    </table>
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
                        <!--   </div> -->
                        <div class="modal-footer">
                            <div class="row text-center">
                                <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id="btnApprovedTHCD">Lưu</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </form>
                    <!--     </div> -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalTHCD" role="dialog">
        <div class="modal-dialog modal-md" style="width: 80%;margin: 30px auto;">
            <!-- Modal content-->
            <div class="modal-content box">
                <div class="modal-header" style="padding: 5px">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Thông tin chế độ được hưởng : <label id="lblProfileName"></label></h4>
                </div>
                <div class="box-body no-padding">
                    <!-- /.mailbox-controls -->
                    <div class="mailbox-read-message">
                        <form class="form-horizontal" action="">
                            <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>
                            <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                                <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                    <thead>
                                        <tr class="success" id="cmisGridHeader">
                                            <th class="text-center" style="vertical-align:middle">STT</th>
                                            <th class="text-center" style="vertical-align:middle">Tên chế độ</th>
                                            <th class="text-center" style="vertical-align:middle">Nhóm đối tượng</th>
                                        </tr>
                                    </thead>
                                    <tbody id="viewDanhsachCheDo">
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <div class="row text-center">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                            <!--                 <div class="box-body" id="contentBox">
                                <div class="form-group" style="margin: 0;">
                                  <div class="col-sm-12" style="padding-left: 0">
                                     <label style="padding-top: 0px;" class="col-sm-4 control-label">Chế độ:</label>

                                      <div class="col-sm-8">
                                        <p>Nhóm đối tượng</p>
                                      </div>
                                  </div>  
                                </div>
                                </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalLapDanhSachTHCD" role="dialog">
        <div class="modal-dialog modal-md" style="width: 70%;margin: 10px auto;">
            <!-- Modal content-->
            <div class="modal-content box">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="">Lập danh sách đề nghị <b id="titleCVTruong"></b></h4>
                </div>
                <form class="form-horizontal" action="" id="frmPopupTHCD">
                    <input type="hidden" class="form-control" id="txtIdDS">
                    <div class="modal-body" style="font-size: 12px;padding: 5px;">
                        <div class="row" id="group_message_THCD" style="padding-left: 10%;padding-right: 10%"></div>
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-6" style="padding-left: 0">
                                    <label class="col-sm-6">Tên công văn</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="txtTenCongVan" placeholder="Nhập tên công văn">
                                    </div>
                                </div>
                                <div class="col-sm-6" style="padding-left: 0">
                                    <label class="col-sm-6">Số công văn</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="txtSoCongVan" placeholder="Nhập số công văn">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3" style="padding-left: 0">
                                    <label class="col-sm-12">Loại công văn</label>
                                    <div class="col-sm-12" style="padding-right: 5px !important;">
                                        <select name="sltTypeCV" id="sltTypeCV" class="form-control selectpicker">
                                            <option value="1" selected="selected">-- Công văn bình thường --</option>
                                            <option value="2">-- Công văn điều chỉnh --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3" style="padding-left: 0">
                                    <label class="col-sm-12">Cấp nhận <font style="color: red">*</font></label>
                                    <div class="col-sm-12" style="padding-right: 5px !important;">
                                        <select name="sltCapNhan" id="sltCapNhan" class="form-control selectpicker">
                                            <option value="2" selected="selected">Phòng Giáo Dục</option>
                                            <option value="3">Phòng Tài Chính</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6" style="padding-left: 0">
                                    <label class="col-sm-12">Chọn chế độ <font style="color: red">*</font></label>
                                    <div class="col-sm-12">
                                        <select name="sltChedo" id="sltChedo" multiple="multiple" style="width: 100%;" class="selectpicker form-control" data-actions-box="true" data-live-search="true" multiple data-selected-text-format="count">
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
                                <?php 
                                    $liencap = DB::table('qlhs_schools')
                                    ->where('schools_id',Auth::user()->truong_id)
                                    ->select('LienCap')->first();
                                    if($liencap->LienCap != null){
                                       $data = explode('-', $liencap->LienCap);
                                    ?>
                                    <div class="col-sm-3" style="padding-left: 0">
                                        <label class="col-sm-6">Cấp học</label>
                                        <div class="col-sm-12">
                                            <select name='sltKhoiDt' class="selectpicker form-control" id='sltKhoiDt'>
                                                <option value="">-- Tất cả --</option>
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
                            <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id="btnInsertTHCD"><i class="glyphicon glyphicon-globe"></i> Lập công văn</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////Phần hồ sơ///////////////////////////////////////////////////////////// -->
    <div class="modal fade" id="modalDanhsachBaocao" role="dialog">
        <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
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
                                    <label class="col-sm-6">Số công văn <font style="color: red">*</font></label>
                                    <div class="col-sm-12">
                                        <select name="sltCongvan" id="sltCongvan" style="width: 100%;" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4" style="padding-left: 0">
                                    <label class="col-sm-6 ">Chọn chế độ </label>
                                    <div class="col-sm-12">
                                        <select name="sltLoaiChedo" id="sltLoaiChedo" class="form-control">
                                            <option value="">--- Chọn chế độ ---</option>
                                            <option value="MGHP">Miễn giảm học phí</option>
                                            <option value="CPHT">Chi phí học tập</option>
                                            <option value="HTAT">Hỗ trợ ăn trưa trẻ em mẫu giáo</option>
                                            <option value="HTBT">Hỗ trợ học sinh bán trú</option>
                                            <option value="HSKT">Hỗ trợ học sinh khuyết tật</option>
                                            <option value="HTATHS">Hỗ trợ ăn trưa học sinh theo NQ57</option>
                                            <option value="HSDTTS">Hỗ trợ học sinh dân tộc thiểu số</option>
                                            <option value="HBHSDTNT">Hỗ trợ học bổng cho học sinh dân tộc nội trú</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div id="">
                                    <div class="box-body" style="font-size: 12px">
                                        <table id="HistoryTable" class="table table-striped table-bordered table-hover dataTable no-footer">
                                            <thead>
                                                <tr class="success">
                                                    <th class="text-center" style="vertical-align:middle">STT</th>
                                                    <th class="text-center" style="vertical-align:middle">Tên học sinh</th>
                                                    <th class="text-center" style="vertical-align:middle">Ngày sinh</th>
                                                    <th class="text-center" style="vertical-align:middle">Dân tộc</th>
                                                    <th class="text-center" style="vertical-align:middle">Bố mẹ</th>
                                                    <th class="text-center" style="vertical-align:middle">Hộ khẩu</th>
                                                    <th class="text-center" style="vertical-align:middle">Trường</th>
                                                    <th class="text-center" style="vertical-align:middle">Lớp</th>
                                                    <th class="text-center" style="vertical-align:middle">Năm học</th>
                                                    <th class="text-center" style="vertical-align:middle">Tiền ăn</th>
                                                    <th class="text-center" style="vertical-align:middle">Tiền ở</th>
                                                    <th class="text-center" style="vertical-align:middle">Tiền văn hóa, tủ thuốc</th>
                                                    <th class="text-center" style="vertical-align:middle">Chức năng</th>
                                                </tr>
                                            </thead>
                                            <tbody id="contentPopupModalDanhsach">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row text-center">
                            <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id="btnInsertTHCD">Lập danh sách</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="" role="dialog">
        <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
            <!-- Modal content-->
            <div class="modal-content box">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title-up-to">
                        Công văn đã lập
                        <p id="currentDate"></p>
                    </h4>
                </div>
                <br>
                <div class="form-group">
                    <div class="col-sm-4" style="padding-left: 0">
                        <label class="col-sm-6">Số công văn </label>
                        <div class="col-sm-12">
                            <select name="sltCongvan" id="sltCongvan" style="width: 100%;" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4" style="padding-left: 0">
                        <label class="col-sm-6">Loại chế độ </label>
                        <div class="col-sm-12">
                            <select name="sltLoaichedo" id="sltLoaichedo" style="width: 100%;" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" class="btn btn-primary" id="btnClosePopupUpto" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default" style="margin-bottom: 0">
        <div class="panel-heading" id="addnew-export-profile">
            <!-- <a href="/ho-so/lap-danh-sach/list"><b> Hồ sơ </b></a> / Danh sách hỗ trợ -->
        </div>
    </div>
    <div class="box box-info">
        <form class="form-horizontal" id="formtonghopchedo">
            <div class="row" id="messageValidate" style="padding-left: 10%;padding-right: 10%"></div>
            <div class="box-body">
                <div class="form-group">
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
                    <?php 
        if($liencap->LienCap != null){
           $data = explode('-', $liencap->LienCap);
        ?>
                        <div class="col-sm-2">
                            <label class="col-sm-6">Cấp học</label>
                            <div class="col-sm-12">
                                <select name='sltKhoiLop' class="selectpicker form-control" id='sltKhoiLop'>
                                    <option value="">-- Tất cả --</option>
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
                            <div class="col-sm-2">
                                <label class="col-sm-6">Năm học <font style="color: red">*</font></label>
                                <div class="col-sm-12">
                                    <select name='sltYear' class="selectpicker form-control" id='sltYear'>
                                        <?php 
                                            $namhoc = DB::table('qlhs_years')->orderBy('code','asc')->get();
                                            $dt = Carbon\Carbon::now()->format('Y');
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

                            <input type="hidden" name="sltNamHoc" id="sltNamHoc" value="<?php echo e($dt); ?>-<?php echo e(($dt+1)); ?>">
                            <div class="col-sm-2">
                                <label class="col-sm-6">Trạng thái</label>
                                <div class="col-sm-12">
                                    <select name='sltStatus' class="selectpicker form-control" id='sltStatus'>
                                        <option value="">-- Tất cả --</option>
                                        <option value="0">Đã cập nhật</option>
                                        <option value="1">Đang cập nhật</option>
                                        <option value="2">Chưa xử lý</option>
                                        <option value="3">Đã xử lý</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2"></div>
                            <?php if(Auth::user()->level == 1): ?>
                            <div class="col-sm-2">
                                <label class="col-sm-6">Chức năng </label>
                                <div class="col-sm-12">
                                   <a href="/ho-so/hoc-sinh/lap-cong-van" class="btn btn-success" id=""><i class="glyphicon glyphicon-ok"></i> Lập danh sách </a>
                                </div>
                            </div>
                            <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
    <div class="box box-primary checkboxtable">
        <div class="col-sm-9">
            <div class="box-header with-border">
                <h3 class="box-title">Xét duyệt học sinh </h3>
            </div>
        </div>
        <div class="box-header col-sm-3">
            <div class="has-feedback">
                <input id="txtSearchProfile" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
        </div>
        <div class="box-body" style="font-size:12px;\">
            <input id="cbxAllChedoHK" hidden="hidden" type="checkbox" class="css-checkbox" />
            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead id="headerDanhSachTongHop">
                </thead>
                <tbody id="dataDanhsachTonghop">
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
                        <label class="col-md-6 control-label text-right">Hiển thị: </label>
                        <div class="col-md-6">
                            <select name="drPagingDanhsachtonghop" id="drPagingDanhsachtonghop" class="selectpicker form-control input-sm pagination-show-row">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="col-md-2 control-label"></label>
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