@extends('layouts.front')

@section('title', 'This is a blank page')
@section('description', 'This is a blank page that needs to be implemented')

@section('content')
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<script type="text/javascript" src="{!! asset('mystyle/js/jsDanhMuc.js') !!}"></script>
<section class="content">
<script type="text/javascript">
  $(function () {
    //loadComboxTruongHocSingle('sltTruongDt', function(){}, $('#school-per').val());

    // $("#sltTruongDt").change(function(){
    //   $("#sltKhoiDt").attr("disabled", false);
    //   getUnitbySchoolID($(this).val());
    // });

   // getUnitAll();

    module = 24;
    permission(function(){
        var html_view  = '<b> Danh mục </b> / Khối lớp';
        
        if(check_Permission_Feature('1')){
            html_view += '<a data-toggle="modal" data-target="#myModal" style="margin-left: 10px" class="btn btn-success btn-xs pull-right" onclick="popupAddnewKhoilop()" > <i class="glyphicon glyphicon-plus"></i> Tạo mới </a >';
        }
        // if(check_Permission_Feature('4')){
        //     html_view += '<a class="btn btn-success btn-xs pull-right" href="#" onclick="exportExcelKhoilop()"> <i class="glyphicon glyphicon-print"></i> Xuất excel </a>';
        // }
        $('#addnew-export-Khoilop').html(html_view);
      });

      GET_INITIAL_NGHILC();
      loaddataKhoilop($('#drPagingKhoilop').val(), $('#txtSearchKhoilop').val());

      $('#drPagingKhoilop').change(function() {
        GET_INITIAL_NGHILC();
        loaddataKhoilop($(this).val(), $('#txtSearchKhoilop').val());
      });

      autocompleteSearch("txtSearchKhoilop", "Khoilop");
  });

    function popupAddnewKhoilop(){
      $('.modal-title').html('Thêm mới khối lớp');
      $('#txtTenkhoilop').val("");
      $('#drLevel').selectpicker('val',"");
      // $('#sltTruongDt').val("");
      $('#sltKhoiDt').selectpicker('val',"");
      Khoilop_id = "";
      $("#modalAddNew").modal("show");
    }

    function popupUpdateKhoilop(){
      $('.modal-title').html('Sửa khối lớp');
      $("#modalAddNew").modal("show");
    }

    function popupConfirmDelete(){
      $("#modalDelete").modal("show");
    }
</script>

  <div class="modal fade" id="modalDelete" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Xóa khối lớp</h4>
        </div>
        <div class="modal-footer">
          <div class="row text-center">
            <h2>Bạn có thực sự muốn xóa không?</h2>
            <input type="button" value="Xác nhận" id="btnConfirmDeleteKhoilop" class="btn btn-primary">
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
          <h4 class="modal-title">Khối lớp</h4>
        </div>
        <form class="form-horizontal" action="">  
        <input type="hidden" class="form-control" id="txtIdRoleGroup">          
                <div class="modal-body">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tên khối lớp<font style="color: red">*</font></label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="txtTenkhoilop" placeholder="Nhập tên khối lớp">
                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Chọn khối lớp<font style="color: red">*</font></label>
                  <div class="col-sm-3">
                    <select id="drLevel" class="form-control selectpicker" data-live-search="true">
                      <option value='' selected="selected">-- Chọn khối lớp --</option>
                      <option value='1'>1 tuổi</option>
                      <option value='2'>2 tuổi</option>
                      <option value='3'>3 tuổi</option>
                      <option value='4'>4 tuổi</option>
                      <option value='5'>5 tuổi</option>
                      <option value='6'>Lớp 1</option>
                      <option value='7'>Lớp 2</option>
                      <option value='8'>Lớp 3</option>
                      <option value='9'>Lớp 4</option>
                      <option value='10'>Lớp 5</option>
                      <option value='11'>Lớp 6</option>
                      <option value='12'>Lớp 7</option>
                      <option value='13'>Lớp 8</option>
                      <option value='14'>Lớp 9</option>
                      <option value='15'>Lớp 10</option>
                      <option value='16'>Lớp 11</option>
                      <option value='17'>Lớp 12</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Chọn trường<font style="color: red">*</font></label>
                  <div class="col-sm-3">
                    <select name='sltTruongDt' class="form-control" id='sltTruongDt'>
                    </select>
                  </div> -->

                  <label for="inputEmail3" class="col-sm-2 control-label">Chọn khối<font style="color: red">*</font></label>
                  <div class="col-sm-3">
                    <select name='sltKhoiDt' class="form-control selectpicker" data-live-search="true" id='sltKhoiDt'>
                      <option value=''>-- Chọn khối --</option>
                      <?php 
                        $arrUnit = DB::table('qlhs_unit')->select('unit_id','unit_name')->get();
                      ?>
                      @foreach($arrUnit as $val)
                            <option value="{{$val->unit_id}}">{{$val->unit_name}}</option>
                         @endforeach
                    </select>
                  </div>
                </div>
              </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnInsertKhoilop">Lưu</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnCloseKhoilop">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-Khoilop">
        
    </div>
    </div>
          <div class="box">
                <div class="form-group " style="margin-top: 10px;margin-bottom: 0px">
                <div class="box-header with-border">
              <h3 class="box-title"></h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input id="txtSearchKhoilop" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
                     <!-- <div class="col-sm-6">
                      <label  class="col-sm-3 control-label">Hiển thị: </label>
                      <div class="col-sm-3">
                        <select id="drPagingKhoilop"  class="form-control input-sm">
                          <option value="5">5</option>
                          <option value="10">10</option>
                          <option value="15">15</option>
                          <option value="20">20</option>
                    </select>
                      </div>
                    </div> -->
              <!--        <div class="col-sm-6">
                      <label  class="col-sm-4 control-label">Tìm kiếm</label>
                      <div class="col-sm-8">
                        <input type="text" id="txtSearchKhoilop" class="form-control" style=" width: 70%; height: 30px; margin-bottom: 10px;">
                      </div>
                    </div> -->
                </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle; width: 3%">STT</th>
                  <!-- <th class="text-center" style="vertical-align:middle">Tên trường</th> -->
                  <th class="text-center" style="vertical-align:middle">Tên khối</th>
                  <th class="text-center" style="vertical-align:middle">Tên khối lớp</th>
                  <th class="text-center" style="vertical-align:middle; width: 20%">Ngày sửa</th>
                  <!-- <th class="text-center" style="vertical-align:middle">Người sửa</th> -->
                  <th class="text-center" colspan="2" style="vertical-align:middle; width: 10%">Chức năng</th>
                </tr>
                </thead>
                <tbody id="dataKhoilop">
                
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
                        <select name="drPagingKhoilop" id="drPagingKhoilop"  class="selectpicker form-control input-sm pagination-show-row">
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
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

@endsection