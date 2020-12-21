@extends('layouts.front')

@section('title', 'This is a blank page')
@section('description', 'This is a blank page that needs to be implemented')

@section('content')
<link rel="stylesheet" href="{!! asset('dist/css/bootstrap-multiselect.css') !!}">
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<script type="text/javascript" src="{!! asset('mystyle/js/styleRole.js') !!}"></script>
<section class="content">
  <style type="text/css">
    .multiselect-container{
      width: 100%;
      max-height: 300px;
    }
  </style>
<script type="text/javascript">
$(function () {
$('#viewUser').change(function(){
    GET_INITIAL_NGHILC();
    loadTableUser();
});
    GET_INITIAL_NGHILC();
    loadTableUser();
    loadTreePhongBan();
//alert($('#school-per').val());
    // loadComboxTruongHoc('sltSchool',function(){
    //   $('#sltSchool').selectpicker('refresh');
    //     //  $('#sltSchool').multiselect({
    //     //     nonSelectedText:'-- Chọn trường học --',
    //     //     nSelectedText: ' trường đã chọn',
    //     //     buttonWidth: '100%',
    //     //     includeSelectAllOption: true,
    //     //     selectAllText: 'Chọn tất cả',
    //     //     enableFiltering: true,
    //     //     filterPlaceholder: 'Tìm kiếm ...',
    //     //     enableCaseInsensitiveFiltering: true,
    //     // });
    // },$('#school-per').val());
    loadComboxNhomQuyen();
    autocompleteSearchs("searchUser");

    // $('select#viewUser').change(function() {
    //    GET_INITIAL_NGHILC();
    //    loadTableUser();
    // });
    
    
});
	// function test(){
	//  $("#myModal").modal("show");
	//  }
 //   function testPhanquyen(){

 //    $("#myModalPhanQuyen").modal("show");
 //   }
</script>
<div class="modal fade" id="myModalPhanQuyenUser" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Phân quyền</h4>
        </div>
          <form id="permission_user_form" action="">            
                <div class="modal-body">
                    <div class="row" id="permission_user_message" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="row" id="group_message_applyRole">
                        <div class="col-sm-12" id="TitleUser">

                        </div>
                        <input type ="text" class="hidden" id="UserId" name="UserId" value="">
                        <div class="col-sm-6">
                    <label  class="col-sm-4 control-label">Nhóm quyền</label>

                    <div class="col-sm-8">
                      <select class="form-control" id="sltRoleGroup" name="sltRoleGroup">
                        <option>-- Chọn nhóm quyền</option>
                      </select>
                    </div>
                  </div>
                    </div>   
                      
                    <div class="row" style="overflow: auto; height: 400px">
                        <div class="col-sm-12">                                                 
                            <div class="panel-body">
                                <div class="row">
                                    <table class="table table-striped table-bordered table-hover" style="width: 100%">                                   
                                        <thead>
                                            <tr class="success">
                                                <th class="text-center">Chức năng</th>
                                                <th class='text-center'>Xem<br/><span><input type='checkbox' id="CheckAllGroupGet"/></span></th>
                                                <th class='text-center'>Thêm<br/><span><input type='checkbox' id="CheckAllGroupAdd"/></span></th>
                                                <th class='text-center'>Sửa<br/><span><input type='checkbox' id="CheckAllGroupUpdate"/></span></th>
                                                <th class='text-center'>Xóa<br/><span><input type='checkbox' id="CheckAllGroupDelete"/></span></th>
                                                <th class='text-center'>Quản lý người dùng<br/><span><input type='checkbox' id="CheckAllGroupBusiness"/></span></th>
                                            </tr>                           
                                        </thead>
                                         <?php
                                        $data = \Illuminate\Support\Facades\DB::select('select * from qlhs_modules where module_view = :view and module_parentid = 0  order by module_order,module_id', ['view' => 1]);
                                        ?>
                                        <tbody id="permission_group_data">
                                         @foreach($data as $key=>$value)
                                         <tr>
                                    <td id="code"><a href="#">-- {{$value->module_name}}</a></td>
                                    <td class="text-center" id="name"><input class="apiCheck" value="{{$value->module_id}}" name="get" id="getCheck{{$value->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                    <td class="text-center" id="name"><input class="apiCheck" value="{{$value->module_id}}" name="add" id="addCheck{{$value->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                    <td class="text-center" id="name"><input class="apiCheck" value="{{$value->module_id}}" name="update" id="updateCheck{{$value->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                    <td class="text-center" id="name"><input class="apiCheck" value="{{$value->module_id}}" name="delete" id="deleteCheck{{$value->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                    <td class="text-center" id="name"><input class="apiCheck" value="{{$value->module_id}}" name="business" id="businessCheck{{$value->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                </tr>
                                          <?php
                                         $datachild = \Illuminate\Support\Facades\DB::select('select * from qlhs_modules where module_view = :view and module_parentid = :parent  order by module_order,module_id',  ['view' => 1,'parent' =>$value->module_id]);
                                         ?>
                                          @foreach($datachild as $key=>$value1)
                                            <tr>
                                                <td id="code"><a href="#">----- {{$value1->module_name}}</a></td>
                                                <td class="text-center" id="name"><input class="apiCheck" value="{{$value1->module_id}}" name="get" id="getCheck{{$value1->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                    <td class="text-center" id="name"><input class="apiCheck" value="{{$value1->module_id}}" name="add" id="addCheck{{$value1->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                    <td class="text-center" id="name"><input class="apiCheck" value="{{$value1->module_id}}" name="update" id="updateCheck{{$value1->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                    <td class="text-center" id="name"><input class="apiCheck" value="{{$value1->module_id}}" name="delete" id="deleteCheck{{$value1->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                    <td class="text-center" id="name"><input class="apiCheck" value="{{$value1->module_id}}" name="business" id="businessCheck{{$value1->module_id}}" onclick="checkEvent(this)" type="checkbox"></td>
                                            </tr>
                                          @endforeach           
                                @endforeach                                       
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>   
                    </div>
                    <div class="modal-footer">
                        <div class="row text-center">
                            <button type="submit" class="btn btn-primary" id ="saveApplyUserRole">Cập nhật phân quyền</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>   
            </form>  
      </div>
      
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md" style="width: auto;margin: 10px;">
    
      <!-- Modal content-->
      <div class="modal-content box">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thêm mới người dùng</h4>
        </div>
        <form class="form-horizontal" action="">  
        <input type="hidden" class="form-control" id="UsersID">          
                <div class="modal-body">
                    <div class="row" id="group_message" style="padding-left: 10%;padding-right: 10%"></div>   
                    <div class="box-body">
                <div class="form-group">
                  <div class="col-sm-6">
                    <label  class="col-sm-4 control-label">Tên tài khoản <font style="color: red">*</font></label>

                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="txtUsername" placeholder="Nhập tài khoản">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label  class="col-sm-4 control-label">Mật khẩu <font style="color: red">*</font></label>

                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="txtPassword" placeholder="Nhập mật khẩu">
                  </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-6">
                    <label  class="col-sm-4 control-label">Họ và tên <font style="color: red">*</font></label>

                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="txtLastname" placeholder="Nhập họ và tên">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label  class="col-sm-4 control-label">Số điện thoại </label>

                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="txtFirstname" placeholder="Nhập số điện thoại ">
                  </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label  class="col-sm-4 control-label">Email </label>

                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="txtEmail" placeholder="Nhập địa chỉ mail">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <label  class="col-sm-4 control-label">Cấp bậc </label>

                    <div class="col-sm-8">
                      <select class="form-control" id="sltCapbac" class="form-control selectpicker"  data-live-search="true">
                        <option value="1">Cơ sở - trường</option>
                        <option value="2">Phòng Giáo Dục</option>
                        <option value="3">Phòng Tài Chính</option>
                        <option value="4">Sở</option>
                      </select>
                    </div>
                  </div>
                </div>

              <div class="form-group">
                  <div class="col-sm-6">
                    <label  class="col-sm-4 control-label">Thuộc trường <font style="color: red">*</font></label>

                    <div class="col-sm-8">
                      <select id="sltSchool" style="width: 100%;" multiple="multiple"  class="selectpicker form-control" data-actions-box="true" data-live-search="true" multiple data-selected-text-format="count">
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
                  <div class="col-sm-6">
                    <label for="inputEmail3" class="col-sm-4 control-label">Phòng ban </label>

                  <div class="col-sm-8">
                    <div style="position: relative">
                    <input type="hidden" name="phongban_id" id="phongban_id">
                    <input type="text" class="form-control" name="phongban_name" id="phongban_name" value="Bấm vào để chọn phòng ban" readonly="readonly">                
                     <div style="position: absolute;z-index: 999;background-color:white; border:1px solid gray;width: 100%"  id="phongban_tree"></div>                
                    </div>
                      <!-- <select class="form-control" id="txtRoleCode" >
                        <option>-- Chọn phòng ban --</option>
                      </select> -->
                    </div>
                </div>
              </div> 
              </div>

                </div>
                <div class="modal-footer">
                    <div class="row text-center">
                        <button type="button" data-loading-text="Đang thêm mới dữ liệu" class="btn btn-primary" id ="btnUserSave">Thêm mới</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>   
      </div>
      
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
       <b> Hệ thống </b> / Danh sách người dùng
        <a style="margin-left: 10px" class="btn btn-success btn-xs pull-right"  id="btnInsertUser">
            <i class="glyphicon glyphicon-plus"></i> Tạo mới
        </a > 
        <a class="btn btn-success btn-xs pull-right"  href="#">
            <i class="glyphicon glyphicon-print"></i> Xuất excel
        </a>
    </div>
    </div>
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Quản lý người dùng</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Tìm kiếm người dùng" id="searchUser">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <div class="box-body">
              <table id="example" class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr class="success">
                  <th class="text-center" style="vertical-align:middle">STT</th>
                  <th class="text-center" style="vertical-align:middle">Tên đăng nhập</th>
                  <th class="text-center" style="vertical-align:middle">Họ và tên</th>
                  <th class="text-center" style="vertical-align:middle">Email</th>
                 
                   
                    <th class="text-center" style="vertical-align:middle">Ngày sửa</th>
                     <th class="text-center" style="vertical-align:middle">Người sửa</th>
                     <th class="text-center" style="vertical-align:middle">Kích hoạt</th>
                  <th class="text-center" style="vertical-align:middle">Chức năng</th>
                </tr>
                </thead>
                <tbody id="dataUsers">
                
                </tbody>
              </table>
            </div>
            <div class="box-footer clearfix">
              <div class="row">
                  <div class="col-md-2">
                      <label class="text-right col-md-9 control-label">Tổng </label>
                      <label class="col-md-3 control-label g_countRowsPaging" id="g_countRowsPaging">0</label>
                  </div>
                  <div class="col-md-3">
                      <label class="col-md-6 control-label text-right">Trang </label>
                      <div class="col-md-6">
                          <select class="form-control input-sm g_selectPagingUpto">
                              <option value="0">0 / 20 </option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-3">
                                <label  class="col-md-6 control-label">Hiển thị: </label>
                                <div class="col-md-6">
                                  <select name="viewUser" id="viewUser"  class="form-control input-sm pagination-show-row">
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