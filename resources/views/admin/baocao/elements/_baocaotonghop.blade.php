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
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">UBND Quận/ huyện ..............................</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">Phòng giáo dục & đào tạo: {{$school}}</h1>
        <br/>
        <h1 class="search-title clearfix" style="font-size: 15px;text-align: center;text-transform: uppercase;">{{$title}}</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-align: center;font-weight: 400">Năm học: {{$namhoc}}</h1>
        <table class=" table-bordered" style="border: 1px solid #ddd;font-size: 13px;width: 100%">
            <thead>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' style='vertical-align:middle;width: 5%'>STT</th>
                    <th class='text-center' style='vertical-align:middle;width: 25%'>Tên trường</th>
                    <th class='text-center' style='vertical-align:middle;width: 15%'>Số công văn</th>
                    <th class='text-center' style='vertical-align:middle;width: 10%'>Số lượng học sinh</th>
                    <th class='text-center' style='vertical-align:middle;width: 20%'>Kinh phí hỗ trợ</th>
                    <th class='text-center' style='vertical-align:middle;width: 25%'>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
            @if(!empty($data))
            <?php $tong = 0; ?>
                @foreach($data as $key => $value)
                    <tr>
                        <td class='text-center' style='vertical-align:middle'>{{($key + 1)}}</td>
                        <td style='vertical-align:middle'>{{$value->s1}}</td>
                        <td style='vertical-align:middle'>{{$value->s2}}</td>
                            
                        <td class='text-center' style='vertical-align:middle'>{{$value->s3}}</td>
                        <td class='text-right' style='vertical-align:middle'>{{number_format(str_replace('.','',$value->s4))}}</td>
                        <td></td>
                    </tr>
                    <?php $tong = $tong + floor(str_replace('.','',$value->s4));  ?>
                @endforeach
                    <tr>
                        <td></td>
                        <th style='vertical-align:middle'>Tổng</th>
                        <td></td>
                        
                        <td></td>
                        <th class='text-right' style='vertical-align:middle'>{{number_format($tong)}}</th>
                        <td></td>
                    </tr>
                
            @else
                <p class="text-center text-danger">Không có dữ liệu</p>
            @endif
            </tbody>
        </table>
    </div>
    <div style="float: right;width: 40%;text-align: center;"> 
        <h1 class="search-title clearfix" style="font-size: 13px;font-weight: 300;font-style: italic">............., ngày.........tháng.........năm.........</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;">{{$level_name}}</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;font-weight: 300;font-style: italic">(Ký, ghi rõ họ tên,đóng dấu)</h1>
    </div>
</body>
</html>