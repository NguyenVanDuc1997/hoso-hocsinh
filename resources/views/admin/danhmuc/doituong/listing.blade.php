@extends('layouts.front')

@section('title', 'This is a blank page')
@section('description', 'This is a blank page that needs to be implemented')

@section('content')
<!--   <link rel="stylesheet" href="{!! asset('dist/css/bootstrap-multiselect.css') !!}">
  <link rel="stylesheet" href="{!! asset('css/select2.min.css') !!}"> -->
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{!! asset('bootstrap/css/bootstrap.min.css') !!}"> 

<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<script type="text/javascript" src="{!! asset('mystyle/js/jsDanhMuc.js') !!}"></script>
<section class="content">

<script type="text/javascript">
  $(function () {
      module = 56;
    permission(function(){
        var html_view  = '<b> Danh mục </b> / Đối tượng';
        
        if(check_Permission_Feature('1')){
            html_view += '<a data-toggle="modal" data-target="#myModal" style="margin-left: 10px" class="btn btn-success btn-xs pull-right" onclick="popupAddnewSubject()"> <i class="glyphicon glyphicon-plus"></i> Tạo mới </a > ';
        }
        $('#addnew-export-subject').html(html_view);
      });
      
      GET_INITIAL_NGHILC();
      loaddataSubject($('#drPagingSubject').val(), $('#txtSearchSubject').val());

      $('#drPagingSubject').change(function() {
        GET_INITIAL_NGHILC();
        loaddataSubject($(this).val(), $('#txtSearchSubject').val());
      });

      autocompleteSearch("txtSearchSubject", "SUBJECT");
  });
   function testPhanquyen(){

    $("#myModalPhanQuyen").modal("show");
   }

    function popupAddnewSubject(){
      $('.modal-title').html('Thêm mới đối tượng');
      $("#drGroupSubject").selectpicker('deselectAll');
      $("#drGroupSubject").selectpicker('refresh');
      $('#txtSubjectName').val("");
      $('#drSubjectActive').selectpicker('val',1);
      subject_id = "";
      $("#modalAddNew").modal("show");
    }

    function popupUpdateSubject(){
      $('.modal-title').html('Sửa đối tượng');
      $("#modalAddNew").modal("show");
    }
</script>

  <div class="modal fade" id="modalDeleteSubject" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Xóa nhóm Đối tượng</h4>
        </div>
        <div class="modal-footer">
          <div class="row text-center">
            <h2>Bạn có thực sự muốn xóa không?</h2>
            <input type="button" value="Xác nhận" id="btnConfirmDeleteSubject" class="btn btn-primary" onclick="deleteDoiTuong()">
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
          <h4 class="modal-title">Thêm mới nhóm đối tượng</h4>
        </div>
        <form class="form-horizontal" action="">  
        <input type="hidden" class="form-control" id="txtIdRoleGroup">          
                <div class="modal-body">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                <!-- <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Mã nhóm đối tượng<font style="color: red">*</font></label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="txtSubjectCode" placeholder="Nhập mã đối tượng" autofocus="true">
                  </div>
                </div> -->
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tên nhóm đối tượng<font style="color: red">*</font></label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="txtSubjectName" placeholder="Nhập tên đối tượng">
                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Chọn chế độ</label>
                  <div class="col-sm-3">                    
                    <select id="drGroupSubject"  multiple="multiple"  class="selectpicker form-control" data-actions-box="true" data-live-search="true" multiple data-selected-text-format="count">

                      <?php 
                        $qlhs_group = DB::table('qlhs_group')->where('group_active','=',1)->select('group_id','group_name')->get();
                      ?>
                       @foreach($qlhs_group as $val)
                            <option value="{{$val->group_id}}">{{$val->group_name}}</option>
                         @endforeach
                    </select>                      
                  </div>

                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Trạng thái</label>
                  <div class="col-sm-3">
                    <select id="drSubjectActive" class="form-control selectpicker" data-live-search="true">
                      <option value="1" selected="selected">Kích hoạt</option>
                      <option value="0">Chưa kích hoạt</option>
                    </select>
                  </div>
                </div>
              </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnInsertSubject" onclick="insertupdateDoiTuong()">Thêm mới</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnCloseSubject">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-subject">
       
        
        
    </div>
    </div>
          <div class="box">
                <div class="form-group " style="margin-top: 10px;margin-bottom: 0px">
                     <div class="box-header with-border">
              <h3 class="box-title"></h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input id="txtSearchSubject" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
                    <!--  <div class="col-sm-6">
                      <label  class="col-sm-4 control-label">Tìm kiếm</label>
                      <div class="col-sm-8">
                        <input type="text" id="txtSearchSubject" class="form-control" style=" width: 70%; height: 30px; margin-bottom: 10px;">
                      </div>
                    </div> -->
                </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle">STT</th>
                  <!-- <th class="text-center" style="vertical-align:middle">Mã nhóm </th> -->
                  <th class="text-center" style="vertical-align:middle">Tên nhóm đối tượng</th>
                  <th class="text-center" style="vertical-align:middle">Chế độ</th>
                  <th class="text-center" style="vertical-align:middle">Trạng thái</th>
                  <th class="text-center" style="vertical-align:middle">Ngày sửa</th>
                  <th class="text-center" colspan="2" style="vertical-align:middle">Chức năng</th>
                </tr>
                </thead>
                <tbody id="dataSubject">
                  
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
                        <select name="drPagingSubject" id="drPagingSubject"  class="selectpicker form-control input-sm pagination-show-row">
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