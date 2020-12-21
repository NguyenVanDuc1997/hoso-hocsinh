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
 <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">{{$school}}</h1>
        <br/>
        <h1 class="search-title clearfix" style="font-size: 15px;text-align: center;text-transform: uppercase;">{{$title}}</h1>
        @if(!empty($data))
        <?php 
            $nam = explode('-',$data[0]->hsbc_HK); 
            $title = '';
        
            if($nam[0] == 'HK1'){
                $title = '(TỪ THÁNG 09 ĐẾN THÁNG 12 NĂM '.$nam[1].') KỲ I NĂM HỌC '.($nam[1]).'-'.($nam[1] + 1);
            }else if($nam[0] == 'HK2'){
                $title = '(TỪ THÁNG 01 ĐẾN THÁNG 05 NĂM '.($nam[0] + 1).') KỲ II NĂM HỌC '.($nam[0]).'-'.($nam[0] + 1);
            }
        ?>
        <h1 class="search-title clearfix" style="font-size: 13px;text-align: center;font-weight: 400">{{$title}}</h1>
        @endif
        <table class=" table-bordered" style="border: 1px solid #ddd;font-size: 13px">
            <thead>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 3%'>STT</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 17%'>Tên trường</th>
                    <th class='text-center' colspan="3" style='vertical-align:middle;'>Số học sinh ăn bán trú tại trường</th>
                    <th class='text-center' colspan="3" style='vertical-align:middle;'>Số nhân viên dinh dưỡng</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'>Số NVDD HĐ 68 đã bố trí</th>
                    <th class='text-center' colspan="3" style='vertical-align:middle;'>Tổng kinh phí hỗ trợ</th>
                </tr>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Tổng số</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Sử dụng KP TW</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Sử dụng KP ĐP</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Tổng số</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>TW (tối đa 5)</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>ĐP (tối đa 10)</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Tổng số</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>TW (tối đa 5)</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>ĐP (tối đa 10)</th>
                </tr>
                
            </thead>
            <tbody>
            @if(!empty($data))
                <?php 
       
                $total = 0; ?>
                @foreach($data as $key => $value)

                    <tr>
                        <td class='text-center' style='vertical-align:middle'>{{($key + 1)}}</td>
                        <td style='vertical-align:middle'>{{($value->schools_name)}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{($value->hsbc_amount)}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{($value->hsbc_HSTW)}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{($value->hsbc_HSDP)}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{($value->hsbc_Total)}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{($value->hsbc_TW)}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{($value->hsbc_DP)}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{($value->hsbc_68)}}</td>
                        <td class='text-right' style='vertical-align:middle'>{{number_format($value->hsbc_amount_TW + $value->hsbc_amount_DP)}}</td>
                        <td class='text-right' style='vertical-align:middle'>{{number_format($value->hsbc_amount_TW)}}</td>
                        <td class='text-right' style='vertical-align:middle'>{{number_format($value->hsbc_amount_DP)}}</td>
                       
                    </tr>
                @endforeach
                 
            @else
                <p class="text-center text-danger">Không có dữ liệu</p>
            @endif
            </tbody>
        </table>
    </div>

</body>
</html>