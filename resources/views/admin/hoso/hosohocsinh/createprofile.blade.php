@extends('layouts.front')

@section('content')
<link rel="stylesheet" href="{!! asset('dist/css/bootstrap-multiselect.css') !!}">
<link rel="stylesheet" href="{!! asset('css/select2.min.css') !!}">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="{!! asset('/dist/css/check-radio.css') !!}"> -->
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="{!! asset('bootstrap/css/bootstrap.min.css') !!}"> 

<link rel="stylesheet" href="{!! asset('css/toastr.css') !!}">

<script src="{!! asset('/plugins/jQuery/jquery-2.2.3.min.js') !!}"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<!-- datepicker -->
<script src="{!! asset('/plugins/datepicker/bootstrap-datepicker.js') !!}"></script>
<script src="{!! asset('js/select2.min.js') !!}"></script>
<script src="{!! asset('js/toastr.js') !!}"></script>
<script src="{!! asset('js/utility.js') !!}"></script>
<script src="{!! asset('/mystyle/js/styleProfile.js') !!}"></script>

<section class="content">
  <!-- <style type="text/css">
  input[type="radio"] {
    display: none;
  }
   
  input[type="radio"] + label {
    color: #292321;
    font-family: Arial, sans-serif;
    font-size: 14px;
  }
   
  input[type="radio"] + label span {
    display: inline-block;
    width: 19px;
    height: 19px;
    margin: -1px 4px 0 0;
    vertical-align: middle;
    cursor: pointer;
    -moz-border-radius: 50%;
    border-radius: 50%;
  }
   
  input[type="radio"] + label span {
    background-color: #292321;
  }
   
  input[type="radio"]:checked + label span {
    background-color: #CC3300;
  }
   
  input[type="radio"] + label span,
  input[type="radio"]:checked + label span {
    -webkit-transition: background-color 0.4s linear;
    -o-transition: background-color 0.4s linear;
    -moz-transition: background-color 0.4s linear;
    transition: background-color 0.4s linear;
  }
  </style> -->
<style>
.ui-menu .ui-menu-item a.ui-corner-all:hover, .ui-menu .ui-menu-item a.ui-corner-all:focus, .ui-menu .ui-menu-item a.ui-corner-all:active {
    background:#ff8a00!important;
    color:#000;
    border-radius:0;
}
 .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
            /* add padding to account for vertical scrollbar */
            padding-right: 20px;
        } 
    ul.ui-autocomplete {
        z-index: 99999999;
    }
</style>
<script type="text/javascript">
  $(function () {
    $('#btnCloseMessage').click(function(){
        $('#tbMessage').attr('hidden','hidden');
        $('.chuho_id').remove();
    });
autocompleteGiamHo();
    permission(function(){
      var html_view  = '<b> Hồ sơ </b> / Tạo mới hồ sơ';

      if(check_Permission_Feature('1')){
        html_view += '<a onclick="openModalAdd()" style="margin-left: 10px" class="btn btn-success btn-xs pull-right"> <i class="glyphicon glyphicon-plus"></i> Tạo mới </a >';
        html_view += '<a onclick="openModalUpto()" style="margin-left: 10px" class="btn btn-success btn-xs pull-right"> <i class="glyphicon glyphicon-plus"></i> Chức năng </a >';
      }
      if(check_Permission_Feature('4')){
          html_view += '<a onclick="exportExcelProfile()" class="btn btn-success btn-xs pull-right" href="#"> <i class="glyphicon glyphicon-print"></i> Xuất excel </a>';
      }
      $('#addnew-export-profile').html(html_view);
    }, 8);

    GET_INITIAL_NGHILC();
    loaddataProfile($('select#viewTableProfile').val(),$('select#sltTruongGrid').val(),$('select#sltLopGrid').val(), $('#txtSearchProfile').val());
    loadComboxTruongHocSingle("sltTruongGrid",function(){
      closeLoading();
      $("#sltTruongGrid").select2({
        placeholder: "-- Chọn trường học --",
        allowClear: true
      }).trigger('change');
    },$('#school-per').val());
$("#sltHocky").change(function () {
    var namnhaphoc = $('#txtYearProfile').val();
    var str = "";
        var $el = $(".multiselect-container");
        $el.find('li.active input').each(function(){
          str += $(this).val() + ",";
        });
  if(str.split(',').length <= 1){
           utility.messagehide('messageDangersdivKyHoc',"Xin mời chọn đối tượng!",1,5000);
           $('#sltHocky').val('').selectpicker('refresh');
  }else{
  if(namnhaphoc == null || namnhaphoc == ''){
     utility.messagehide('messageDangersdivKyHoc',"Nhập năm nhập học!",1,5000);
      // utility.alert("Thông báo","Nhập năm nhập học!",function(){
           $('#formtest').focus();
           $('#sltHocky').val('').selectpicker('refresh');
           $('#txtYearProfile').focus();
      // });
     
  }else{
        var namhoc = namnhaphoc;
        namhoc = namhoc.substr(3, namhoc.length);
        // console.log(namhoc);
        var hocky = $(this).val();
        var truongId = $('#sltTruong').val();
        var arrSubID = [];
        var lopId = $('#sltLop').val();
        var xaId = $('#sltPhuong').val();
        var bantru = $('#sltBantru').val();
        
        var o = {
            SCHOOLID: truongId,
            CLASSID: lopId,
            XAID: xaId,
            YEAR: namhoc,
            HOCKY: hocky,
            BANTRU: bantru,
            ARRSUBJECT: str
        };

        // str = hocky + '-' + namhoc + '-' + truongId + '-' + str;
        // console.log(str.substr(0,str.length-1));
        // console.log("ABC");
        loadMoneybySubject(o);
      }
      }
    });
    
    
    $("#sltLopGrid").select2({
      placeholder: "-- Chọn lớp học --",
      allowClear: true
    });
    $("#sltDantoc").select2({
        placeholder: "-- Chọn dân tộc --",
        allowClear: true,
        
    });

    $("#sltTruong").select2({
      placeholder: "-- Chọn trường --",
      allowClear: true
    });

    $("#sltLop").select2({
      placeholder: "-- Chọn lớp --",
      allowClear: true
    });

    $("#sltTinh").select2({
      placeholder: "-- Chọn tỉnh/ thành phố --",
      allowClear: true
    });

    $("#sltQuan").select2({
      placeholder: "-- Chọn huyện/ quận --",
      allowClear: true
    });

    $("#sltPhuong").select2({
      placeholder: "-- Chọn xã/ phường --",
      allowClear: true
    });//
    $("#txtThonxom").select2({
      placeholder: "-- Chọn thôn/ xóm --",
      allowClear: true
    });
    $("#drSchoolUpto").select2({
      placeholder: "-- Chọn trường --",
      allowClear: true
    });
    $("#drClassUpto").select2({
      placeholder: "-- Chọn trường --",
      allowClear: true
    });
    
    autocompleteSearch("txtSearchProfile");
    $('#sltDantoc').on("select2:select", function(e) { 
        $('#txtParent').focus();
    });
  });

  var close = true;
  function viewMoreProfile(){
    if(close){
      $('#tableMoreProfile').removeAttr('hidden');
      close = false;
    }else{
      $('#tableMoreProfile').attr('hidden','hidden');
      close = true;
    }
  }
  function openModalAdd(){
   // $('#sltDoituong').removeAttr('disabled');
    $('#nextChangeSubject').html('');
   // insertUpdate(1);
   loadComboxTinhThanh(0,'sltTinh', function(){
      loadComboxTinhThanh(95,'sltQuan', function(){closeLoading();});
        $('select#sltQuan').removeAttr('disabled');
        $('select#sltPhuong').attr('disabled','disabled');
        $('select#sltPhuong').html('<option value="">--Chọn danh mục--</option>');
   },95); // 95 mặc định tỉnh yên bái
  
   //$('#sltDantoc').focus().select();
   loading();
    $('#saveProfile').html("Thêm mới");
    $('#sltDoituong').multiselect({
      nonSelectedText:'-- Chọn đối tượng --',
      buttonWidth: '100%',
      enableFiltering: true,
      filterPlaceholder: 'Tìm kiếm ...',
      enableCaseInsensitiveFiltering: true,
    });
    $('#sltDoituong').multiselect('enable');
   resetControl();
   loadComboxTruongHoc("sltTruong",function(){
     closeLoading();
     
        if(parseInt($('#school-per').val()) != 0 && ($('#school-per').val()+'').split('-').length == 1){
            loadComboxLop($('#school-per').val(),'sltLop',function(){
                $('select#sltLop').removeAttr('disabled');
            });
        }
      
   },$('#school-per').val());
    //$("#sltDoituong").multiselect('clearSelection');
    $("#sltDoituong").val("").multiselect("clearSelection");
    $("#sltDoituong").multiselect("refresh");
    
    //loadComboxDantoc(null);
   
    $("#myModalProfile").modal("show");
    $('#sltDantoc').change(function(){
      $('#tbMessage').removeAttr('hidden');
        $('.dan-toc-thieu-so').remove();
      if(parseInt($(this).val()) != 31 ){
        
        var html_show = '';
                html_show += '<tr class="dan-toc-thieu-so text-center"><td>Dân tộc</td>';
                html_show += '<td>'+$("#sltDantoc option:selected").text();+'</td>';
                html_show += '<td colspan="2">Dân tộc thiểu số</td></tr>';
            $('#tbMessageContent').append(html_show);
      }
    });

    //  $('#sltTruong').change(function(){
    //   $('#tbMessage').removeAttr('hidden');
    //     $('.dan-toc-thieu-so').remove();
      
    // });


       //$('#txtParent').focus();

//     $(document).on('keyup', '.select2-search__field', function (e) {
//     if (e.which === 13) {
//        // alert('Pressed enter!');
//        $('#txtParent').focus();
//     }
// });
    // $(document).on('focus', '.select2', function() {
    //     $(this).siblings('select').select2('open');
    //     $('.select2-search__field').focus();
    // });
    // $('input').keyup(function(e){
    //  // if($(this).val().length==$(this).attr('maxlength'))
    //  if(e.keyCode==13)
    //     $(this).next(':input').focus()
    // })
    
  };
 
  function openModalUpdate(){
    $('#saveProfile').html("Cập nhật");
    $('.modal-title').html('Sửa hồ sơ học sinh');
    $('#nextChangeSubject').html('Thay đổi đối tượng');
   // insertUpdate(1);
    
   //$("#sltDoituong").multiselect('clearSelection');
    //loadComboxDantoc();
    $('#sltDoituong').multiselect({
      nonSelectedText:'-- Chọn đối tượng --'
    });
     
  //    $("#sltDoituong option:selected").prop("selected", false);
    //   $("#sltDoituong").multiselect('refresh');
    $("#myModalProfile").modal("show");
   }
   function openModalUpto(){
    loadComboxTruongHocSingle("drSchoolUpto",function(){
      closeLoading();
      $('#drSchoolUpto').trigger('change');
    },$('#school-per').val());
    var t =  $('#uptoClass').DataTable().clear().draw().destroy();
    resetControl();
    $('#upClass-select-all').prop('checked', false);
    loading();
    
    $("#myModalUpto").modal("show");
   }

    function openModalHistory(){    
    $("#myHistory").modal("show");
   }
</script>

<div class="modal fade" id="myHistory" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title-up-to">Lịch sử học sinh </h4>
        </div>
         
          <br>
        <div id="">
            <div class="box-body" style="font-size: 12px">
              <table id="HistoryTable" class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle">STT</th>
                  <th class="text-center" style="vertical-align:middle">Lớp học</th>
                  <th class="text-center" style="vertical-align:middle">Năm học</th>
                  <th class="text-center" style="vertical-align:middle">Trạng thái</th>
                  <th class="text-center" style="vertical-align:middle">Người thực hiện</th>
                  <th class="text-center" style="vertical-align:middle">Ngày thực hiện</th>
                </tr>
                </thead>
                <tbody id="contentPopupUpto">
                
                </tbody>
              </table>
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
<div class="modal fade" id="myModalUpto" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title-up-to">Chức năng lên lớp - học lại - nghỉ học</h4>
        </div>
          <form class="form-horizontal" action="">                 
            <div class="modal-body" style="font-size: 12px;padding: 5px;">
                    <div class="row" id="message_Upto" style="padding-left: 10%;padding-right: 10%">
                      <div id="messageDanger"></div>
                    </div>
                    <div class="box-body">
                
                      <div class="form-group">
                        
                        <div class="col-sm-4" style="padding-left: 0">
                          <label  class="col-sm-12 ">Trường học <font style="color: red">*</font></label>

                          <div class="col-sm-12">
                            <select name="drSchoolUpto" id="drSchoolUpto" class="form-control" style="width: 100% !important">
                              <option value="">--Chọn trường--</option>
                          </select>
                          </div>
                        </div>
                        <div class="col-sm-4" style="padding-left: 0">
                          <label  class="col-sm-6 ">Lớp học hiện tại <font style="color: red">*</font></label>

                          <div class="col-sm-12" >
                            <select name="drClassUpto" disabled="disabled" id="drClassUpto" class="form-control" style="width: 100% !important">
                                <option value="">--Chọn lớp--</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-4" style="padding-left: 0">
                          <label  class="col-sm-6 ">Năm học <font style="color: red">*</font></label>

                          <div class="col-sm-12" >
                            <select name="drYearUpto" disabled="disabled" id="drYearUpto"  class="form-control" style="width: 100% !important">
                                <option value="">--Chọn năm học--</option>
                            </select>
                          </div>
                        </div>
                       
                      </div>
                <div class="form-group">
                   <div class="col-sm-4" style="padding-left: 0">
                          <label  class="col-sm-12 ">Chức năng <font style="color: red">*</font></label>

                          <div class="col-sm-12" >
                            <select name="drClassNext" disabled="disabled" id="drClassNext" class="form-control" style="width: 100% !important">
                                <option value="">-- Chức năng --</option>
                                <option value="1">-- Lên lớp  --</option>
                                <option value="2">-- Nghỉ học --</option>
                                <option value="3">-- Học lại - chuyển lớp --</option>

                            </select>
                          </div>
                        </div>
                        <div class="col-sm-4" style="padding-left: 0">
                         <label id="labelClassBack" hidden="hidden" class="col-sm-12 ">Lớp học lại - chuyển lớp<font style="color: red">*</font></label>
                          <div class="col-sm-12" >
                            <select name="drClassBack" hidden="hidden" disabled="disabled" id="drClassBack" class="form-control" style="width: 100% !important">
                    
                            </select>
                          </div>

                          <label id="labelClassNext" hidden="hidden" class="col-sm-12 ">Lên lớp <font style="color: red">*</font></label>
                          <div class="col-sm-12" >
                            <select name="StlClassNext"  disabled="disabled" id="StlClassNext" class="hidden form-control" style="width: 100% !important">
                    
                            </select>
                          </div>

                          <label id="labelOutProfile" hidden="hidden" class="col-sm-12 ">Ngày nghỉ học </label>

                          <div class="col-sm-12" >
                            <input type="text"  name="dateOutProfile" disabled="disabled" placeholder="ngày-tháng-năm"  id="dateOutProfile" class="form-control" hidden="hidden">
                          </div>
                        </div>
                        <div class="col-sm-4" style="padding-left: 0">
                         
                        </div>
                </div>
                    </div>
                    </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnUpto">Lên lớp</button>
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnRevert">Hoàn tác</button>
                        <button type="button" class="btn btn-primary" id="btnClosePopupUpto" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
          </form>
          <br>
        <div id="">
            <div class="box-body" style="font-size: 12px">
              <table id="uptoClass" class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle">STT</th>
                  <th class="text-center" style="vertical-align:middle"><input name="select_all" value="1" id="upClass-select-all" type="checkbox"></th>
                  <!-- <th class="text-center" style="vertical-align:middle">Mã học sinh</th> -->
                  <th class="text-center" style="vertical-align:middle">Họ và tên</th>
                  <th class="text-center" style="vertical-align:middle">Năm sinh</th>
                  <th class="text-center" style="vertical-align:middle">Dân tộc</th>
                 
                  <th class="text-center" style="vertical-align:middle">Hộ khẩu thường trú</th>
                  <th class="text-center" style="vertical-align:middle">Cha mẹ - người giám hộ</th>
                  <th class="text-center" style="vertical-align:middle">Trường học</th>
  <!--                 <th class="text-center" style="vertical-align:middle">Trạng thái lớp học</th>
                  <th class="text-center" style="vertical-align:middle">Năm học</th> -->
                </tr>
                </thead>
                <tbody id="contentPopupUpto">
                
                </tbody>
              </table>
            </div>
           
        </div>
      </div>
      
    </div>
</div>

<div class="modal fade" id="myModalProfile" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thêm mới học sinh</h4>
        </div>
        <form class="form-horizontal" action="" id="formtest">  
        <input type="hidden" class="form-control" id="txtIdProfile">          
                <div class="modal-body" style="font-size: 12px;padding: 5px;">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%">
                      <div id="messageDangers"></div>
        
                    </div>
                    <div class="box-body">
                <div class="form-group">
                  <!-- <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6">Mã học sinh <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtCodeProfile" placeholder="Nhập mã học sinh" autofocus="true">
                    </div>
                  </div> -->
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Họ và tên <font style="color: red">*</font></label>

                  <div class="col-sm-12">
                    <input type="text" class="form-control" id="txtNameProfile" placeholder="Nhập tên học sinh">
                  </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Ngày sinh <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtBirthday" placeholder="ngày-tháng-năm">
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Dân tộc <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <select name="sltDantoc" id="sltDantoc"  class="form-control" style="width: 100% !important">
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-12 ">Chủ hộ<font style="color: red">*</font></label>
    
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtParent" placeholder="Nhập cha mẹ hoặc người giám hộ" autocomplete="on">
                    </div>
                  </div>
                  
                </div>
                <div class="form-group">
                  
                  
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-12 ">Trường <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <select name="sltTruong" id="sltTruong"  class="form-control" style="width: 100% !important">
                        <option value="">--Chọn trường--</option>
                    </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Lớp học <font style="color: red">*</font></label>

                    <div class="col-sm-12" >
                      <select name="sltLop" disabled="disabled" id="sltLop"  class="form-control" style="width: 100% !important">
                          <option value="">--Chọn lớp--</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0" id="dirDoiTuong">
                    <label  class="col-sm-12 ">Thuộc đối tượng <span id="url_hocsinh"></span></label>

                    <div class="col-sm-12">
                      <select name="sltDoituong" id="sltDoituong" multiple="multiple" class="form-control">

                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Năm nhập học <font style="color: red">*</font></label>
                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="txtYearProfile" name="txtYearProfile" placeholder="tháng-năm">
                    </div>
                  </div>
                </div>
                <div class="form-group">

                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Tỉnh/thành <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <select name="sltTinh" id="sltTinh" class="form-control" style="width: 100% !important">
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Quận/huyện <font style="color: red">*</font></label>

                    <div class="col-sm-12">
                      <select name="sltQuan" disabled="disabled" id="sltQuan"  class="form-control" style="width: 100% !important">
                          
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Phường/xã</label>

                    <div class="col-sm-12">
                      <select name="sltPhuong" disabled="disabled" id="sltPhuong"  class="form-control" style="width: 100% !important">
                       
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Thôn xóm</label>

                    <div class="col-sm-12">
                      <select name="txtThonxom" disabled="disabled" id="txtThonxom"  class="form-control" style="width: 100% !important">
                       
                      </select>
                        <!-- <input type="text" class="form-control" id="txtThonxom" placeholder="Nhập thôn xóm"> -->
                    </div>
                  </div>
                   
                </div>
                 <div class=" box box-body" id="tbMessage" hidden="hidden" style="font-size: 12px;overflow: auto;">
              <table   class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle;width: 10%">Thông tin</th>
                  <th class="text-center" style="vertical-align:middle;width: 30%">Thông báo </th>
                  <th class="text-center" style="vertical-align:middle;width: 30%">Nội dung</th>
                  <th class="text-center" style="vertical-align:middle;width: 10%"><button type="button"  class="btn btn-danger btn-xs editor_remove" id="btnCloseMessage"><i class="glyphicon glyphicon-remove"></i> </button></th>
                  
                </tr>
                </thead>
                <tbody id="tbMessageContent">
                
                </tbody>
              </table>
              
            </div>
                  <div class="modal-header">
          <h4>Học sinh được hưởng chế độ 116</h4>
        </div>

     <div class="form-group">
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 " style="width: 80% !important">Trường hợp nhà ở xa trường</label>

                    <div class="col-sm-12" style="padding-right: 5px !important;">
                        <input type="text" class="form-control" id="txtKhoangcach" placeholder="Nhập số km">
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-12 ">Giao thông cách trở, đi lại khó khăn</label>

                    <div class="col-sm-12" style="padding-left: 5px !important; padding-right: 5px !important;">
                      <input type="text" class="form-control" id="drGiaoThong" placeholder="Nhập số km">
                       <!--  <select name="sltBantru" id="drGiaoThong"  class="form-control ">
                          <option value="0">Không</option>
                          <option value="1">Có</option>
                    </select> -->
                    </div>
                  </div>
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-12 ">Ở trong trường/ ngoài trường <font style="color: red">*</font></label>

                    <div class="col-sm-12" style="padding-left: 5px !important; padding-right: 5px !important;">
                        <select name="sltBantru" id="sltBantru"  class="form-control ">
                          <option value="">-- Chọn --</option>
                          <option value="0">Ở ngoài trường</option>
                          <option value="1">Ở trong trường</option>
                        </select>
                    </div>
                  </div>

                  <!--  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-12 ">Ở trong trường/ ngoài trường <font style="color: red">*</font></label>

                    <div class="col-sm-12" style="padding-left: 5px !important; padding-right: 5px !important;">
                    <label class="Form-label--tick">
  <input type="radio" value="1" name="SomeRadio" class="Form-label-radio" checked>
  <span class="Form-label-text">Không</span>
</label>
<label class="Form-label--tick">
  <input type="radio" value="2" name="SomeRadio" class="Form-label-radio">
  <span class="Form-label-text">Ở trong trường</span>
</label>
<label class="Form-label--tick">
  <input type="radio" value="3" name="SomeRadio" class="Form-label-radio">
  <span class="Form-label-text">Ở ngoài trường</span>
</label>
                    </div>
                  </div> -->
                 
                  <div class="col-sm-3" style="padding-left: 0">
                     <label  class="col-sm-12 ">Ở trong trường </label>

                    <div class="col-sm-12" style="padding-left: 5px !important; padding-right: 5px !important;">
                        <label for="ckbNQ57" class="btn btn-default">Nghị Quyết 57 <input type="checkbox" id="ckbNQ57" class="badgebox"><span class="badge">&check;</span></label>
                    </div>
   
                  </div>                  
                </div>
                             
                  <div class="form-group" id="divKyHoc">
                  <div class="col-sm-3" style="padding-left: 0">
                    <label  class="col-sm-6 ">Kỳ học</label>

                    <div class="col-sm-12">
                        <select name="sltHocky" id="sltHocky"  class="form-control ">
                          <option value="">-- Chọn kỳ học --</option>
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
               <!--  <div class="form-group">
    <label>
     You may also allow to clear the selection! 
     <span id="clear" class="btn btn-default btn-xs">
      Clear the file name
     </span>
    </label>
      <input type="file" id="cleardemo">
</div> -->
                <div class="form-group">
                  <div class="col-sm-3" style="text-decoration: underline;">
                    <a onclick="viewMoreProfile()" style="cursor: pointer;"><i class="glyphicon glyphicon-paperclip"></i> Đính kèm thêm tài liệu</a>
                  </div>
                 
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
            <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="btnAddNewRow">Thêm tài liêu</button></div> 
            <div class="col-sm-2"> 
              <button style="margin-top: 5px" type="button" class="btn btn-block btn-default" id="clearFile">Xóa tệp file chọn</button></div>
            </div> </div></div>
          
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang tải dữ liệu" class="btn btn-primary" id ="saveProfile">Lưu</button>
                        <button type="button" class="btn btn-primary" id="btnClosePopupProfile" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-profile">
        
    </div>
    </div>
         <!--  <div class="box"> -->
            <div class="box box-primary form-horizontal" style="font-size: 12px;">
            <div class="form-group " style="margin-top: 10px;margin-bottom: 0px">
                    <div class="col-sm-4">
                      <label  class="col-sm-4 control-label">Trường</label>
                      <div class="col-sm-8">
                       <select name="sltTruongGrid" id="sltTruongGrid"  class="form-control "></select>
                      </div>
                    </div>
                     <div class="col-sm-4">
                      <label  class="col-sm-4 control-label">Lớp</label>
                      <div class="col-sm-8">
                        <select name="sltLopGrid" disabled="disabled" id="sltLopGrid"  class="form-control "></select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                       <label  class="col-sm-4 control-label">Tìm kiếm</label>
                      <div class="box-tools col-sm-8">
                          <div class="has-feedback">
                            <input id="txtSearchProfile" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                          </div>
                        </div>
                    </div>

                     
                </div>
            <div class="box-body" style="font-size: 12px">
              <table id="example" class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle">STT</th>
                  <!-- <th class="text-center" style="vertical-align:middle">Mã học sinh</th> -->
                  <th class="text-center" style="vertical-align:middle">Họ và tên</th>
                  <th class="text-center" style="vertical-align:middle">Năm sinh</th>
                  <th class="text-center" style="vertical-align:middle">Dân tộc</th>
                 
                   <th class="text-center" style="vertical-align:middle">Hộ khẩu thường trú</th>
                    <th class="text-center" style="vertical-align:middle">Cha mẹ - người giám hộ</th>
                     <th class="text-center" style="vertical-align:middle">Lớp học hiện tại</th>
                     <th class="text-center" style="vertical-align:middle">Năm học hiện tại</th>
                     <th class="text-center" style="vertical-align:middle">Năm nhập học</th>
                     <th class="text-center" style="vertical-align:middle">Nghỉ học</th>
                  <th class="text-center" colspan="2" style="vertical-align:middle">Chức năng</th>
                </tr>
                </thead>
                <tbody id="dataProfile">
                
                </tbody>
              </table>
            </div>
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
                        <select name="viewTableProfile" id="viewTableProfile"  class="form-control input-sm pagination-show-row">
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
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

@endsection