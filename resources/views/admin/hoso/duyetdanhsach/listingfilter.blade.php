@extends('layouts.front')

@section('content')
<link rel="stylesheet" href="{!! asset('dist/css/bootstrap-multiselect.css') !!}">
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="{!! asset('bootstrap/css/bootstrap.min.css') !!}"> 

<link rel="stylesheet" href="{!! asset('css/toastr.css') !!}">

<script src="{!! asset('/plugins/jQuery/jquery-2.2.3.min.js') !!}"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>
<!-- datepicker -->
<script src="{!! asset('js/toastr.js') !!}"></script>
<script src="{!! asset('js/utility.js') !!}"></script>
<script src="{!! asset('/mystyle/js/styleProfile.js') !!}"></script>

<section class="content">
  
<script type="text/javascript">
  $(function () {
    var type = "{{$type}}";
    $('#event-thcd').hide();
    $('.event-thcd').hide();
    $('#event-thcd-thuhoi').hide();
    $('#sltLoaiCongvan').val(type);
      GET_INITIAL_NGHILC();
       loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(),$('#drSchoolTHCD').val(), $('#sltLoaiCongvan').val(), $('#txtSearchProfilePhongSo').val());

    $('#sltCongvan').change(function() {
        $('.name-congvan').html($(this).val());
        GET_INITIAL_NGHILC();
        loadDanhSachByPhongSo($('#drPagingDanhsachtonghop').val(),$('#drSchoolTHCD').val(),$('#sltLoaiCongvan').val());
        
    });

    //loadComboboxHocky(3, function(){});

    $('#drPagingDanhsachtonghop').change(function() {
         GET_INITIAL_NGHILC();
         loadDanhSachHSDaLap($(this).val(), $('#txtSearchProfilePhongSo').val(), $('#sltTrangthai').val());
    });

    var pageing = $('#drPagingDanhsachtonghop').val();
      
    permission(function(){
          var html_view  = '';
          var html_view_thuhoi  = '';
          var html_view_title  = '<b> Hồ sơ </b> / <a href="/ho-so/lap-danh-sach/danh-sach-de-nghi"> Danh sách đề nghị </a> / Tìm kiếm';
          $('#addnew-export-profile').html(html_view_title);
          
          if(check_Permission_Feature('1')){
            
            html_view += '<button type="button" onclick="openModalLapDS()" class="btn btn-success" id =""><i class="glyphicon glyphicon-pushpin"></i> Lập danh sách </button>';
            html_view_thuhoi += '<button type="button" onclick="thuhoicongvanlap()" class="btn btn-success" id =""><i class="glyphicon glyphicon-pushpin"></i> Đồng ý thu hồi </button>';
          }

          $('#event-thcd').html(html_view);
          $('#event-thcd-thuhoi').html(html_view_thuhoi);
      }, 97);
    autocompleteSearchDenghidalap('txtSearchCongVanDanhSach',1);
});
</script>

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
                      <label  class="col-sm-6" >Chọn trường <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                         <select disabled="disabled" name="drSchoolTHCD" id="drSchoolTHCD" class="form-control selectpicker" @if(count(explode('-',Auth::user()->truong_id)) > 1) data-live-search="true" @endif>
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
                            @if($id_truong == $val->schools_id)
                              <option value="{{$val->schools_id}}" selected="selected">{{$val->schools_name}}</option>
                           @else
                            <option value="{{$val->schools_id}}" >{{$val->schools_name}}</option>
                             @endif
                         @endforeach
                       </select>
                         <input type="hidden" name="SchoolByCongVan" id="SchoolByCongVan" value="{{$id_truong}}">
                      </div>
                    </div>
                    
                    <input type="hidden" name="sltLoaiCongvan" id="sltLoaiCongvan">
                    <div class="col-sm-2">
                      <label  class="col-sm-6 ">Năm học <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <select name='sltYear' disabled="disabled" class="form-control selectpicker"  data-live-search="true" id='sltYear'>
                            <?php 
                              $getYear = DB::table('qlhs_years')->select('name','code')->get();
                              $year = Carbon\Carbon::now()->format('Y');
                              if(Carbon\Carbon::now()->format('m') >= 1 && Carbon\Carbon::now()->format('m') <= 8){
                                $year = $year - 1;
                              }
                              
                            ?>
                            @foreach($getYear as $val)
                                
                                  <option value="{{$val->code}}" @if($year == $val->code) selected="selected" @endif>{{$val->name}}</option>

                            @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-2">
                      <label  class="col-sm-6">Số công văn <font style="color: red">*</font></label>
                      <div class="col-sm-12">
                        <select name='sltCongvan' class="form-control selectpicker"  data-live-search="true" id='sltCongvan' >
                            @if($congvan != null && $congvan != '')
                              <?php 
                              $getData = DB::table('qlhs_hosobaocao')
                                  ->where('report_id_truong', $id_truong)
                                  ->where('report_cap_status', $status);
                                if($type == 5){
                                    $getData = $getData->where('report_cap_gui',1);
                                }else if($type == 6){
                                    $getData = $getData->where('report_cap_gui',2);
                                }else if($type == 7){
                                    $getData = $getData->where('report_cap_gui',3);
                                }else if($type == 8){
                                    $getData = $getData->where('report_cap_gui',4);
                                }
                                  $getData = $getData->select('report_name', 'report_name as report_value')
                                  ->orderBy('report_date', 'desc')->get();
                              ?>
                              @foreach($getData as $val)
                                @if($congvan == $val->report_value)
                                  <option value="{{$val->report_value}}" selected="selected">{{$val->report_name}}</option>
                               @else
                                <option value="{{$val->report_value}}" >{{$val->report_name}}</option>
                                 @endif
                             @endforeach
                            @endif
                        </select>
                        <input type="hidden" name="ByCongVan" id="ByCongVan" value="{{$congvan}}">
                      </div>
                    </div>
                    <div class="col-sm-2" >
                      <label  class="col-sm-6 event-thcd">Chức năng</label>
                      <div class="col-sm-12" id="event-thcd">

                      </div>
                    </div>
                </div>

                 <div class="modal-footer">
                       <!--  <div class="row text-center" id="event-thcd">

                        </div> -->
                        <div class="row text-center" id="event-thcd-thuhoi">
                            <!-- <button type="button" onclick="" class="btn btn-success" id =""><i class="glyphicon glyphicon-pencil"></i> Đồng ý thu hồi</button> -->
                        </div>
                    </div>
                  </div>
            </form>
          </div>

            <div class="box box-primary">
              <div class="col-sm-9">
                    <div class="box-header with-border">
                      <h3 class="box-title name-congvan" >{{$congvan}}</h3>
                    </div>
                  </div>
                      <div class="box-header col-sm-3">
                          <div class="has-feedback">
                            <input id="txtSearchCongVanDanhSach" type="text" class="form-control input-sm" placeholder="Tìm kiếm ">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                          </div>
                        </div>
                <div class="box-body" style="font-size:12px;">
                 
                  
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                      <thead id="cmisGridHeader">
                        
                         
                        </tr>
                 
                      </thead>
                        <tbody id="tbListDanhSach">                     
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

@endsection