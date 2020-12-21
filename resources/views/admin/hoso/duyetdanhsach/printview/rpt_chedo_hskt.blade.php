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
        <h1 class="search-title clearfix" style="font-size: 13px;text-align: center;font-weight: 400">{{$namhoc}}</h1>
        <table class=" table-bordered" style="border: 1px solid #ddd;font-size: 13px">
            <thead>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>STT</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 15%'>Họ và tên</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'>Ngày sinh</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'>Lớp học</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'>Dân tộc</th>
                    <th class='text-center' colspan="3" style='vertical-align:middle;width: 8%'>Hộ khẩu thường trú</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'>Họ tên chủ hộ trong sổ hộ khẩu của học sinh</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'>Tổng kinh phí hỗ trợ</th>
                    <th class='text-center' colspan="2" style='vertical-align:middle;'>Trong đó</th>

                </tr>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Thôn xóm</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Phường xã</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Quận huyện</th>
                    <th class='text-center' style='vertical-align:middle;width: 10%'>Học bổng, khuyết tật</th>
                    <th class='text-center' style='vertical-align:middle;width: 10%'>Mua sắm phương tiện, đồ dùng học tập</th>
                </tr>
                
            </thead>
            <tbody>
            @if(!empty($data))
                <?php 
       
                $total = 0; ?>
                @foreach($data as $key => $value)
                <?php 
                    $chedo = explode(';',$value->bckp_danhsach_chedo);
                    $check = true;
                ?>
                    <tr>
                        <td class='text-center' style='vertical-align:middle'>{{($key + 1)}}</td>
                        <td style='vertical-align:middle'>{{$value->profile_name}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{Carbon\Carbon::parse($value->profile_birthday)->format('d-m-Y')}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{$value->class_name}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{$value->nationals_name}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{$value->thon_name}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{$value->phuong_name}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{$value->quan_name}}</td>
                        <td class='text-center' style='vertical-align:middle'>{{$value->profile_parentname}}</td>
                        <td class='text-right' style='vertical-align:middle'>{{number_format($value->HSKT_HB + $value->HSKT_DDHT)}}</td>
                        <td class='text-right' style='vertical-align:middle'>{{number_format($value->HSKT_HB)}}</td>
                        <td class='text-right' style='vertical-align:middle'>{{number_format($value->HSKT_DDHT)}}</td>
                        
                        <?php $total = $total +  $value->HSKT_HB + $value->HSKT_DDHT;?>
                    </tr>
                @endforeach
                  <tr>
                        <th class='text-center' colspan="9" style='vertical-align:middle'>Tổng</th>
                       <th class='text-right' style='vertical-align:middle'>{{number_format($total)}}</th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'></th>
                       
                        
                    </tr>
            @else
                <p class="text-center text-danger">Không có dữ liệu</p>
            @endif
            </tbody>
        </table>
    </div>
    
</body>
</html>