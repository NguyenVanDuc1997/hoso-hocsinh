@extends('layouts.front')

@section('title', 'This is a blank page')
@section('description', 'This is a blank page that needs to be implemented')

@section('content')
<link rel="stylesheet" href="{!! asset('css/select2.min.css') !!}">
<link rel="stylesheet" href="{!! asset('dist/css/bootstrap-multiselect.css') !!}">
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="{!! asset('/js/myScript.js') !!}"></script>

<section class="content">
<script type="text/javascript">
$(function () {
    $('#view-tonghopdanhsach').change(function(){
        if($('#sltYear').val() == ""){
            utility.message("Thông báo","Xin mời chọn học kỳ!",null,5000,1);
            $('select#sltYear').focus();
            return;
        }
        if($('#sltSchool').val() == ""){
            utility.message("Thông báo","Xin mời chọn trường!",null,5000,1);
            $('select#sltSchool').focus();
            return;
        }
        GET_INITIAL_NGHILC();
        loadStatisticTotal();
        
    });
    $('#sltYear').change(function(){
        if($('#sltStatistic').val() != ""){
          GET_INITIAL_NGHILC();
          loadStatisticTotal();
            // utility.message("Thông báo","Xin mời chọn chế độ!",null,5000,1);
            // $('select#sltStatistic').focus();
            // return;
        }
        
        
    });

    $('#sltKhoiDt').change(function(){
        if($('#sltStatistic').val() != ""){
          GET_INITIAL_NGHILC();
          loadStatisticTotal();
            // utility.message("Thông báo","Xin mời chọn chế độ!",null,5000,1);
            // $('select#sltStatistic').focus();
            // return;
        }
        
        
    });
    $('input[name="checkAll"]').click(function () {
        $("input[name='report_name[]']").not(this).prop('checked', this.checked);
    });
    $('#sltStatistic').change(function(){
        if($('#sltYear').val() == ""){
            utility.message("Thông báo","Xin mời chọn học kỳ!",null,5000,1);
            $('select#sltYear').focus();
            return;
        }
        if($('#sltSchool').val() == ""){
            utility.message("Thông báo","Xin mời chọn trường!",null,5000,1);
            $('select#sltSchool').focus();
            return;
        }
        $('input[name="checkAll"]').prop('checked', false);
        GET_INITIAL_NGHILC();
        loadStatisticTotal();

    });
    $("#btnprn").click(function(){
        var values = "["; 
        $.each($("input[name='report_name[]']:checked"), function(index,value) {
            var data = $(this).parents('tr:eq(0)');
            if(index > 0){
              values += ",";
            }
            
            values += '{"s1":"'+$(data).find('td:eq(2)').text()+'","s2":"'+$(data).find('td:eq(3)').text()+'","s3":"'+$(data).find('td:eq(4)').text()+'","s4":"'+$(data).find('td:eq(5)').text()+'"}';
        });
        values += "]"; 
        $.ajax('/bao-cao/statistic-by-total-view', {
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            method: 'POST',
            methodType: 'HTML',
            data: {data:values,chedo:$('#sltStatistic').val(),hocky : $('#sltYear').val()},
            success: function (data) { 
                var left = (screen.width) / 4;
                var top = (screen.height) / 8; 
                newWin= window.open("/",'name','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=1, resizable=no, copyhistory=no, width='+(screen.width)+', height='+(screen.height)+', top=' + top + ', left=' + left);
                newWin.document.write(data);
                newWin.print();
                newWin.close();
                $('#btnprn').button('reset');
          },error: function (val) {
                $('#btnprn').button('reset');
          }
      });               
    });
    //$('.btnprn').printPage();
});

function loadStatisticTotal(){
    $('#dataTongHopHoSo').html("<tr><td colspan='50' class='text-center'>Đang tải dữ liệu</td></tr>");
    var o = {
        start: (GET_START_RECORD_NGHILC()),
        limit: 100,
        hocky : $('#sltYear').val(),
        chedo : $('#sltStatistic').val()
    };
    PostToServer('/bao-cao/statistic-by-total',o,function(result){
        SETUP_PAGING_NGHILC(result, function () {
            loadStatisticTotal();
        });
        data = result.data;
        var html_show = "";
        var html_header = "<tr class='success'>";
        if(data.length>0){
            for (var i = 0; i < data.length; i++) {
              html_show += "<tr><td class='text-center' style='vertical-align:middle'>"+(i + 1 + (GET_START_RECORD_NGHILC() * 100))+"</td>";
              html_show += "<td class='text-center' style='vertical-align:middle'><input name='report_name[]' type='checkbox' value='"+data[i].report_name+"' /></td>";
              html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].schools_name)+"</td>";
              html_show += "<td style='vertical-align:middle'>"+ConvertString(data[i].report_name)+"</td>";
              html_show += "<td class='text-center' style='vertical-align:middle'>"+ConvertString(data[i].report_total)+"</td>";
              html_show += "<td class='text-right' style='vertical-align:middle'>"+formatter(data[i].bckp_nhucau)+"</td>";
              html_show += "</tr>";
          }
        }else{
           html_show += "<tr><td colspan='50' class='text-center'>Không tìm thấy dữ liệu</td></tr>";
        }
        $('#dataTongHopHoSo').html(html_show);
  },function(result){
      console.log("loadStatisticTotal");
      console.log(result);
  },"btnViewThongKe","","");
}

</script>



<div class="box-header">
    <div class="panel-heading">
       <a href="/ho-so/lap-danh-sach/list"><b> Báo cáo</b></a> / Thống kê học sinh theo chế độ
    </div>
    <div class="pull-right box-tools" >
        <button id="btnprn" type="button" class="btn btn-info pull-right" data-loading-text="Đang tải dữ liệu" style="margin:5px"><i class="fa fa-print" aria-hidden="true"></i> In</button>
    </div>
</div>
    <div class="box box-info">
            <!-- /.box-header -->
            <!-- form start -->
            <form id="formmiengiamhocphi">
            <div class="row" id="warning_msg" style="padding-left: 10%;padding-right: 10%"></div>
              <div class="box-body">
              
                <div class="form-group">
                    <div class="col-sm-3">
                      <label  class="col-sm-12 control-label">Năm học</label>
                      <div class="col-sm-12">
                          <select name='sltYear' class="selectpicker form-control" id='sltYear'>
                            <?php 
                              $getYear = DB::table('qlhs_years')->select('name','code')->get();
                              $year = Carbon\Carbon::now()->format('Y');
                              if(Carbon\Carbon::now()->format('m') >= 1 && Carbon\Carbon::now()->format('m') <= 8){
                                $year = $year - 1;
                              }
                              
                            ?>
                            <option value="" selected="">-- Chọn năm học --</option>
                            @foreach($getYear as $val)
                              <option value="{{$val->code}}" @if($year == $val->code) selected="selected" @endif>{{$val->name}}</option>
                            @endforeach
                          </select>
                      </div>
                    </div>
                     <div class="col-sm-3">
                      <label  class="col-sm-12 control-label">Loại thống kê</label>
                      <div class="col-sm-12">
                        <select name='sltStatistic' class="form-control selectpicker" id='sltStatistic'>
                          <option value="" selected="selected">-- Chọn loại báo cáo --</option>
                          <option value="mghp">Hỗ trợ miễn giảm học phí</option>
                          <option value="cpht">Hỗ trợ chi phí học tập</option>
                          <option value="htat">Hỗ trợ ăn trưa cho trẻ em mẫu giáo</option>
                          <option value="htbt_tong">Hỗ trợ học sinh bán trú</option>
                          <option value="htkt_tong">Hỗ trợ học sinh khuyết tật</option>
                          <option value="htaths">Hỗ trợ tiền ăn học sinh theo NQ57</option>
                          <option value="hsdtts">Hỗ trợ học sinh dân tộc thiểu số</option>
                          <option value="hbhsdtnt">Hỗ trợ học sinh nội trú</option>
                        </select>
                      </div>
                    </div>
 
              </div>
            </form>
          </div>

            <div class="box box-primary">

                <div class="box-body" style="font-size:12px">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                          @if(\Auth::user()->level == 2)
                          <tr class="success">
                            <th class='text-center' style='vertical-align:middle'>STT</th>
                            <th class='text-center' style='vertical-align:middle'><input type="checkbox" name="checkAll"></th>
                            <th class='text-center' style='vertical-align:middle'>Tên trường</th>
                            <th class='text-center' style='vertical-align:middle'>Số công văn</th>
                            <th class='text-center' style='vertical-align:middle'>Số lượng học sinh</th>
                            <th class='text-center' style='vertical-align:middle'>Kinh phí hỗ trợ</th>
                          </tr>
                          @elseif(\Auth::user()->level == 3)
                          <tr class="success">
                            <th class='text-center' style='vertical-align:middle'>STT</th>
                            <th class='text-center' style='vertical-align:middle'><input type="checkbox" name="checkAll"></th>
                            <th class='text-center' style='vertical-align:middle'>Tên trường</th>
                            <th class='text-center' style='vertical-align:middle'>Số công văn</th>
                            <th class='text-center' style='vertical-align:middle'>Số lượng học sinh</th>
                            <th class='text-center' style='vertical-align:middle'>Kinh phí hỗ trợ</th>
                          </tr>
                         <!--  <tr class="success">
                            <th class='text-center' style='vertical-align:middle'>STT</th>
                            <th class='text-center' style='vertical-align:middle'>x</th>
                            <th class='text-center' style='vertical-align:middle'>Tên cập bậc</th>
                            <th class='text-center' style='vertical-align:middle'>Kinh phí hỗ trợ</th>
                          </tr> -->
                          @endif
                        </thead>
                        <tbody id="dataTongHopHoSo">
                        </tbody>
                    </table>
                </div>       
            </div>
          
</div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

@endsection