@extends('layouts.front')

@section('content')
<link rel="stylesheet" href="{!! asset('dist/css/bootstrap-multiselect.css') !!}">
<link rel="stylesheet" href="{!! asset('css/select2.min.css') !!}">
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
<script type="text/javascript">
  $(function () {

    $('#drPagingDanhsach').change(function() {
      GET_INITIAL_NGHILC();
      loadBaoCaoCongVan($('#sltSchools').val(),$('#txtSearchProfileLapdanhsach').val());
    });

    $('#sltSchools').change(function() {
      GET_INITIAL_NGHILC();
      loadBaoCaoCongVan($(this).val(),$('#txtSearchProfileLapdanhsach').val());
    });

    GET_INITIAL_NGHILC();
    loadBaoCaoCongVan($('#sltSchools').val(),$('#txtSearchProfileLapdanhsach').val()); 
    

    $('#sltYear').change(function() {
        GET_INITIAL_NGHILC();
        loadBaoCaoCongVan($('#sltSchools').val(),$('#txtSearchProfileLapdanhsach').val());
    });

    $('#sltLoaiCV').change(function() {
      GET_INITIAL_NGHILC();
      loadBaoCaoCongVan($('#sltSchools').val(),$('#txtSearchProfileLapdanhsach').val());
    });
    autocompleteSearchDenghidalap('txtSearchProfileLapdanhsach',0);
    var pageing = $('#drPagingDanhsachtonghop').val();
      
    permission(function(){
          var html_view  = '';
          var html_view_header  = '<b> Hồ sơ </b> / Chọn danh sách';
          
          if(check_Permission_Feature('5')){
            html_view += '<button type="button" data-loading-text="Đang tải dữ liệu" class="btn btn-success" id ="btnLoadDataPhong"><i class="glyphicon glyphicon-search"></i> Xem </button>';
          }
          
          
          if(check_Permission_Feature('1')){
            
            html_view += '<button type="button" onclick="openPopupLapTHCD()" class="btn btn-success" id =""><i class="glyphicon glyphicon-pushpin"></i> Lập danh sách </button>';

            html_view += '<button type="button" onclick="approvedAll(2)" class="btn btn-success" id =""><i class="glyphicon glyphicon-pencil"></i> Phê duyệt toàn bộ</button>';

            html_view += '<button type="button" onclick="approvedUnAll(2)" class="btn btn-success" id =""><i class="glyphicon glyphicon-pencil"></i> Hủy phê duyệt toàn bộ</button>';
          }
      }, 95);
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
<div class="row" id="txtNoteHSBC" style="padding-left: 10%;padding-right: 10%">Nội dung: <b>-</b></div>
                  </div>   
                </div>
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
    <div class="panel-heading" id="title-page-Danhsach">
       <!-- <a href="/ho-so/lap-danh-sach/list"><b> Hồ sơ </b></a> / Danh sách hỗ trợ -->
    </div>
</div>
    

            <div class="box box-primary">
              <div class="col-sm-3 box-header">
                   <label  class="col-sm-3 control-label text-right">Trường học</label>
                      <div class="col-sm-8">
                        <select name='sltSchools' class="selectpicker form-control" id='sltSchools' @if(count(explode('-',Auth::user()->truong_id)) > 1) data-live-search="true" @endif>
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
                         
                         @foreach($qlhs_schools as $val)
                            <option value="{{$val->schools_id}}" >{{$val->schools_name}}</option>
                         @endforeach
                         </select>
                      </div>
                  </div> 
<div class="col-sm-3 box-header">
                   <label  class="col-sm-4 control-label text-right">Năm học</label>
                      <div class="col-sm-8">
                        <select name='sltYear' class="selectpicker form-control" id='sltYear'>
                          <?php 
                              $namhoc = DB::table('qlhs_years')->orderBy('code','asc')->get();
                              $str_date = Carbon\Carbon::now()->format('Y')-1;
                          ?>
                          @foreach($namhoc as $nam)
                            <option  value="{{$nam->code}}" @if(trim($nam->code) == trim($str_date)) selected @endif>{{$nam->name}}</option>

                          @endforeach
                          
                         </select>
                      </div>
                  </div> 
                  <div class="col-sm-3 box-header">
                    
                      <div class="col-sm-12">
                        <select name='sltLoaiCV' class="selectpicker form-control" id='sltLoaiCV'>
                            <option value='' selected="selected">Chọn loại công văn</option>
                                <option value='1' >Công văn từ trường</option>
                                <option value='2'>Công văn từ phòng giáo dục</option>
                                <option value='3'>Công văn từ phòng tài chính</option>
                                <option value='4'>Công văn từ sở tài chính</option>
                         </select>
                      </div>
                  </div>
                      <div class="col-sm-3 box-header">
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
                            <th class="text-center"  style="vertical-align:middle;width: 10%">Trường học</th>
                            <th class="text-center"  style="vertical-align:middle;width: 10%">Số công văn</th>
                            <th class="text-center"  style="vertical-align:middle;width: 20%">Tên công văn</th>
                            
                            <th class="text-center"  style="vertical-align:middle;width: 25%">Chế độ</th>
                            <th class="text-center"  style="vertical-align:middle;width: 10%">Loại công văn</th>
                            <th class="text-center"  style="vertical-align:middle;width: 10%">Tổng số</th>
                            <th class="text-center"  style="vertical-align:middle;width: 5%">Chi tiết</th>
                            <th class="text-center"  style="vertical-align:middle;width: 2%">Excel</th>
                          </tr>
                 
                      </thead>
                        <tbody id="dataLapDanhsachHS">                     
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

@endsection