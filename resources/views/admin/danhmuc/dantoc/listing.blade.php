@extends('layouts.front')

@section('title', 'This is a blank page')
@section('description', 'This is a blank page that needs to be implemented')

@section('content')
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<script type="text/javascript" src="{!! asset('mystyle/js/jsDanhMuc.js') !!}"></script>
<section class="content">
<style>
input[type=checkbox] {
    display: none;
}

input[type=checkbox] + label {
    display: inline-block;
    position: relative;
    padding: 8px;
    background-color: white;
    border: 1px solid black;
    border-radius: 5px;
    width:25px;
    top:10px;
}

input[type=checkbox]:checked + label {
    background-color: #475F72;
    color: #A4B7C6;
}

input[type=checkbox]:checked + label:after {
    position: absolute;
    left: 8px;
    top: 0px;
    color: #fff; 
    content: '\2714'; 
    font-size: 10px;
} 
</style>
<script type="text/javascript">
 $(function () {
      
    // $('#cbxType').on('keydown',function(e){
    //   e.preventDefault();
    //   var self = $(this);
    //   if(e.keyCode == 32){
    //       if(self.is(":checked")){
    //         self.prop('checked', false);  
    //       }else{
    //         self.prop('checked', true);  
    //       }
    //   }
    // });
      module = 57;
      permission(function(){
        var html_view  = '<b> Danh mục </b> / Dân tộc';
        
        if(check_Permission_Feature('1')){
            html_view += '<a data-toggle="modal" data-target="#myModal" style="margin-left: 10px" class="btn btn-success btn-xs pull-right" onclick="popupAddnewNation()" ><i class="glyphicon glyphicon-plus"></i> Tạo mới </a >';
        }

        // if(check_Permission_Feature('4')){
        //     html_view += '<a class="btn btn-success btn-xs pull-right" href="#" onclick="exportExcelNation()"> <i class="glyphicon glyphicon-print"></i> Xuất excel </a>';
        // }
        $('#addnew-export-nation').html(html_view);
      });

      GET_INITIAL_NGHILC();
      loaddataNation($('#drPagingNation').val(), $('#txtSearchNational').val());

      $('#drPagingNation').change(function() {
        GET_INITIAL_NGHILC();
        loaddataNation($(this).val(), $('#txtSearchNational').val());
      });

      // $('#txtNationCode').focus();
      autocompleteSearch("txtSearchNational", "NATION");
  });

    function popupAddnewNation(){
      $('.modal-title').html('Thêm mới dân tộc');
      nation_id = "";
      // document.getElementById("txtNationCode").focus();
      // $('#txtNationCode').attr('disable', false);
      // $('#txtNationCode').val("");
      $('#cbxType').prop('checked', false);
      $('#txtNationName').val("");
      $('#drNationActive').selectpicker('val',1);
      $("#modalAddNew").modal("show");
    }

    function popupUpdateNation(){
      $('.modal-title').html('Sửa dân tộc');
      // $('#txtNationCode').attr('disable', true);
      $("#modalAddNew").modal("show");
    }

    function popupConfirmDelete(){
      $("#modalDelete").modal("show");
    }

   function testPhanquyen(){

    $("#myModalPhanQuyen").modal("show");
   }
</script>
  <div class="modal fade" id="modalDelete" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Xóa dân tộc</h4>
        </div>
        <div class="modal-footer">
          <div class="row text-center">
            <h2>Bạn có thực sự muốn xóa không?</h2>
            <input type="button" value="Xác nhận" id="btnConfirmDeleteNation" class="btn btn-primary">
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
          <h4 class="modal-title">Thêm mới dân tộc</h4>
        </div>
        <form class="form-horizontal" action="">  
        <input type="hidden" class="form-control" id="txtIdRoleGroup">          
                <div class="modal-body">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                <!-- <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Mã dân tộc<font style="color: red">*</font></label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="txtNationCode" placeholder="Nhập mã dân tộc" tabindex="" autofocus="true">
                  </div>
                </div> -->
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tên dân tộc<font style="color: red">*</font></label>

                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="txtNationName" placeholder="Nhập tên dân tộc" tabindex="">
                  </div>

                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Dân tộc thiểu số</label> -->

                  <div class="col-sm-2">
                    <input type="checkbox" id="cbxType" name="cbxType"   checked><label for="cbxType"></label> Dân tộc thiểu số
                 <!--    <label for="cbxType" class="btn btn-default">Dân tộc thiểu số <input type="checkbox" id="cbxType" class="badgebox"></label> -->
                  </div>

                  <label for="inputEmail3" class="col-sm-1 control-label">Trạng thái</label>

                  <div class="col-sm-3">
                    <select id="drNationActive" class="form-control selectpicker" data-live-search="true" tabindex="">
                      <option value="1" selected="selected">Kích hoạt</option>
                      <option value="0">Chưa kích hoạt</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  
                </div>
              </div>   
                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnInsertNation">Thêm mới</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btnCloseNation">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading" id="addnew-export-nation">
       
        
        
    </div>
    </div>
          <div class="box">
                <div class="form-group " style="margin-top: 10px;margin-bottom: 0px;">
                <div class="box-header with-border">
              <h3 class="box-title"></h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input id="txtSearchNational" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
                     <!-- <div class="col-sm-6">
                      <label  class="col-sm-3 control-label">Hiển thị: </label>
                      <div class="col-sm-3">
                        <select id="drPagingNation"  class="form-control input-sm">
                          <option value="5">5</option>
                          <option value="10">10</option>
                          <option value="15">15</option>
                          <option value="20">20</option>
                    </select>
                      </div>
                    </div> -->
                   <!--   <div class="col-sm-6">
                      <label  class="col-sm-4 control-label">Tìm kiếm</label>
                      <div class="col-sm-8">
                        <input type="text" id="txtSearchNational" class="form-control" style=" width: 70%; height: 30px; margin-bottom: 10px;">
                      </div>
                    </div> -->
                </div>

            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle; width: 3%">STT</th>
                  <!-- <th class="text-center" style="vertical-align:middle">Mã dân tộc</th> -->
                  <th class="text-center" style="vertical-align:middle">Tên dân tộc</th>
                  <th class="text-center" style="vertical-align:middle">Dân tộc thiểu số</th>
                  <th class="text-center" style="vertical-align:middle; width: 20%">Trạng thái</th>
                  <th class="text-center" style="vertical-align:middle; width: 20%">Ngày sửa</th>
                  <th class="text-center" colspan="2" style="vertical-align:middle; width: 10%">Chức năng</th>
                </tr>
                </thead>
                <tbody id="dataNation">
                
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
                        <select name="drPagingNation" id="drPagingNation"  class="selectpicker form-control input-sm pagination-show-row">
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

@endsection