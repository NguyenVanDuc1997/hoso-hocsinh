<!DOCTYPE html>
<html>
<head>
    <style type="text/css" media="print">
      @page { size: landscape; }
    </style>
    <style type="text/css">
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
        html {
          overflow-x: hidden;
          overflow-y: auto;
        }
        td,th{
            padding: 5px;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div style="padding: 10px;">
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">Phòng ..............................</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">{{$school}}</h1>
        <br/>
        <h1 class="search-title clearfix" style="font-size: 15px;text-align: center;text-transform: uppercase;">{{$title}}</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-align: center;font-weight: 400">{{$hocky}} Năm học: {{$namhoc}}</h1>
        <table class=" table-bordered" style="border: 1px solid #ddd;font-size: 13px">
            <thead>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' style='vertical-align:middle;width: 5%'>STT</th>
                    <th class='text-center' style='vertical-align:middle;width: 15%'>Họ và tên</th>
                    <th class='text-center' style='vertical-align:middle;width: 10%'>Năm sinh</th>
                    <th class='text-center' style='vertical-align:middle;width: 10%'>Lớp học</th>
                    <th class='text-center' style='vertical-align:middle;width: 10%'>Số tiền được hỗ trợ (tháng)</th>
                    <th class='text-center' style='vertical-align:middle;width: 25%'>Đối tượng</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Số tháng</th>
                    <th class='text-center' style='vertical-align:middle;width: 10%'>Kinh phí hỗ trợ (nghìn đồng)</th>
                    <th class='text-center' style='vertical-align:middle;width: 12%'>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
            @if(!empty($data))
                <?php $total = 0; ?>
                @foreach($data as $key => $value)
                <?php $val = Round($value->nhu_cau * $value->count_month/1000)*1000; $total = $total + $val; ?>
                    <tr>
                        <td class='text-center' style='vertical-align:middle'>{{($key + 1)}}</td>
                        <td style='vertical-align:middle'>{{$value->profile_name}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{\Carbon\Carbon::parse($value->profile_birthday)->format('d-m-Y')}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{($value->class_name)}}</td>
                        <td class='text-right' style='vertical-align:middle'>{{number_format($value->nhu_cau)}}</td>
                        <td style='vertical-align:middle'>{{$value->subject_name}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{$value->count_month}}</td>
                        <td class='text-right' style='vertical-align:middle'>{{number_format($val)}}</td>
                        <td class='text-center' style='vertical-align:middle'></td>
                    </tr>
                @endforeach
                    <tr>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'>Tổng</th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-right' style='vertical-align:middle'>{{number_format($total)}}</th>
                        <th class='text-center' style='vertical-align:middle'></th>
                    </tr>
            @else
                <p class="text-center text-danger">Không có dữ liệu</p>
            @endif
            </tbody>
        </table>
    </div>
    <div style="float: right;width: 40%;text-align: center;"> 
        <h1 class="search-title clearfix" style="font-size: 13px;font-weight: 300;font-style: italic">............., ngày.........tháng.........năm.........</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;">HIỆU TRƯỞNG</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;font-weight: 300;font-style: italic">(Ký, ghi rõ họ tên,đóng dấu)</h1>
    </div>
</body>
</html>