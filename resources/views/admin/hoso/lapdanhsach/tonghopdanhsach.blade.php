@extends('layouts.front')

@section('title', 'This is a blank page')
@section('description', 'This is a blank page that needs to be implemented')

@section('content')
<link rel="stylesheet" href="{!! asset('css/select2.min.css') !!}">

<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<script type="text/javascript" src="{!! asset('mystyle/js/styleLapDanhSach.js') !!}"></script>
<section class="content">
<script type="text/javascript">
$(function () {
  $('select#view-tonghopdanhsach').change(function() {
       GET_INITIAL_NGHILC();
       loadTonghophoso($(this).val());
    });

  $('#sltSchool').change(function() {
    GET_INITIAL_NGHILC();
    loadTonghophoso($('#view-tonghopdanhsach').val());
  });

  $('#sltYear').change(function() {
    GET_INITIAL_NGHILC();
    loadTonghophoso($('#view-tonghopdanhsach').val());
  });

  $('#btnViewReport').hide();

  $("#exampleInputFile").filestyle({
    buttonText : 'Đính kèm',
    buttonName : 'btn-info'
  });
    
  permission(function(){
        var html_view  = '';
        
        if(check_Permission_Feature('5')){
          html_view += '<button type="button" onclick="loadTonghophoso('+$('#view-tonghopdanhsach').val()+')" class="btn btn-success" id =""><i class="glyphicon glyphicon-search"></i> Xem </button>';
          // html_view += '<button type="button" onclick="myModalLapDanhSach()" class="btn btn-success" id ="btnSave"><i class="glyphicon glyphicon-pushpin"></i> Tạo danh sách</button> ';
        }
        html_view += '<button type="button" class="btn btn-primary" onclick="reset()"><i class="glyphicon glyphicon-refresh"></i> Làm mới</button>';
        // if(check_Permission_Feature('5')){
        //     html_view += '<button type="submit" class="btn btn-info" id ="btnSave"><i class="glyphicon glyphicon-send"></i> Gửi danh sách</button>';
        // }
        // $('#ex-tonghophoso').html(html_view);
    });
 //loadComboxTruongHoc('sltSchool', function(){}, $('#school-per').val());
  loadComboxNamHoc();
  loadTonghophoso($('#view-tonghopdanhsach').val());

    $("#sltSchool").select2({
      placeholder: "-- Chọn trường học --",
      allowClear: true
    });
    
    $("#sltYear").select2({
      placeholder: "-- Chọn năm học --",
      allowClear: true
    });

    function openPopupSendDSTH(){

    }
});
   function myModalLapDanhSach(){
      if(parseInt($('#sltSchool').val()) != 0){
        if($('#sltYear').val() != ""){
          $('#txtNameDS').val('');
          $('#txtNguoiLap').val('');
          $('#txtNguoiKy').val('');
          $('#txtGhiChu').val('');
          $("#myModalLapDanhSach").modal("show");
        }else{
          //utility.message("Thông báo","Xin mời chọn năm học",null,2000)
          utility.messagehide('warning_msg','Xin mời chọn năm học',1,3000);
          $('#sltYear').focus();
        }
        
      }else{
        //utility.message("Thông báo","Xin mời chọn trường",null,2000)
        utility.messagehide('warning_msg','Xin mời chọn trường',1,3000);
        $('#sltSchool').focus();
      }    
   }
$('button.btnOpenPopupSendDSTH').click(function(){
    console.log($(this).data("id"));
});
  function openPopupSendTHDSHT(strId){
    $("#drNguoinhan").val('').select2({
      placeholder: "-- Chọn người nhận --",
      allowClear: true
    });
    $("#drCC").val('').select2({
      placeholder: "-- Chọn người nhận --",
      allowClear: true
    });

    listIDSend = strId;

    $("#myModalSendTHDSHT").modal("show");

    loadComboboxNguoi();

    // console.log(strId);
  }

</script>


<div class="panel panel-default">
    <div class="panel-heading">
       <a href="/ho-so/lap-danh-sach/list"><b> Hồ sơ </b></a> / Tổng hợp hỗ trợ chính sách
       <!-- <a onclick="export_file(0, 1)" class="btn btn-success btn-xs pull-right" href="#"> <i class="glyphicon glyphicon-print"></i> Xuất excel </a> -->
    </div>
</div>
    <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" id="formmiengiamhocphi" >
            <div class="row" id="warning_msg" style="padding-left: 10%;padding-right: 10%"></div>
              <div class="box-body">
                <div class="form-group">
                  <div class="col-sm-5">
                      <label  class="col-sm-4 control-label">Chọn trường <font style="color: red">*</font></label>
                      <div class="col-sm-8">
                        <select name='sltSchool' class="form-control" id='sltSchool'>
                          <?php 
                          if(Auth::user()->truong_id !=null && Auth::user()->truong_id != 0 && Auth::user()->truong_id != '0'){
                            $qlhs_schools = DB::table('qlhs_schools')->where('schools_active','=',1)->whereIn('schools_id',explode('-',Auth::user()->truong_id))->select('schools_id','schools_name')->get();
                          }else{
                            $qlhs_schools = DB::table('qlhs_schools')->where('schools_active','=',1)->select('schools_id','schools_name')->get();
                         }
                         ?>
                         <option value="">-- Chọn trường học --</option>
                         @foreach($qlhs_schools as $val)
                            <option value="{{$val->schools_id}}" @if($val->schools_id == Auth::user()->truong_id) selected @endif>{{$val->schools_name}}</option>
                         @endforeach
                        </select>
                      </div>
                    </div>
                     <div class="col-sm-5">
                      <label  class="col-sm-4 control-label">Năm học <font style="color: red">*</font></label>
                      <div class="col-sm-8">
                        <select name='sltYear' class="form-control" id='sltYear'>
                          
                         </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <button type="button" class="btn btn-success" id ="btnViewReport"><i class="glyphicon glyphicon-search"></i> Xem báo cáo </button>
                    </div>
                </div>
            
              </div>
            </form>
          </div>

            <div class="box box-primary">

                <div class="box-body" style="font-size:12px;overflow: auto ; max-width: 100%">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                          <tr class="success">
                            <th  class="text-center" style="vertical-align:middle">STT</th>
                            <th  class="text-center" style="vertical-align:middle">Danh sách MGHP</th>
                            <th  class="text-center" style="vertical-align:middle">Danh sách CPHT</th>
                            <th  class="text-center" style="vertical-align:middle">Danh sách HTAT trẻ em mẫu giáo</th>
                            <th  class="text-center" style="vertical-align:middle">Danh sách HTBT</th>
                            <th  class="text-center" style="vertical-align:middle">Danh sách HSKT</th>
                            <th  class="text-center" style="vertical-align:middle">Danh sách NGNA</th>
                            <th  class="text-center" style="vertical-align:middle">Danh sách HTAT học sinh</th>
                            <th  class="text-center" style="vertical-align:middle">Danh sách HSDTTS</th>
                            <th  class="text-center" style="vertical-align:middle">Danh sách HSDTNT học bổng</th>

                            <th  class="text-center" style="vertical-align:middle">Chức năng</th>
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
            <div class="col-md-6">
                <select class="form-control input-sm g_selectPaging">
                    <option value="0">0 / 20 </option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
                      <label  class="col-md-6 control-label">Hiển thị: </label>
                      <div class="col-md-6">
                        <select name="view-tonghopdanhsach" id="view-tonghopdanhsach"  class="form-control input-sm pagination-show-row">
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
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

@endsection