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
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 3%'>STT</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 10%'>Họ và tên</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Ngày sinh</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Lớp học</th>

                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'>Dân tộc</th>
                    <th class='text-center' colspan="3" style='vertical-align:middle;width: 8%'>Hộ khẩu thường trú</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'>Họ tên chủ hộ trong sổ hộ khẩu của học sinh</th>
                    <th class='text-center' colspan="4" style='vertical-align:middle;'>Đối tượng</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'> Kinh phí </th>
                </tr>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Thôn xóm</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Phường xã</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Quận huyện</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Có cha, mẹ thường trú tại các xã, vùng đặc biệt khó khăn </th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Không có nguồn nuôi dưỡng được quy định tại khoản 1 Điều 5 NĐ 136/2013/NĐ-CP</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Là nhân khẩu trong gia đình thuộc diện hộ nghèo </th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Là nhân khẩu trong gia đình thuộc diện hộ cận nghèo</th>
                </tr>
                
            </thead>
            <tbody>
            @if(!empty($data))
                <?php 
                $v34 = 0;
                $v38 = 0;
                $v73 = 0;
                $v41 = 0;
           
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
                        @foreach ($chedo as  $cd) 
                            <?php $c = explode('|',$cd); ?>
                            
                            @if($check && ($c[0] == 93))
                                <?php $check = false; ?>
                                @if(isset($c[3]) && $c[3] == 34)
                                <?php $v34++; ?>
                                <td class='text-center' style='vertical-align:middle'>x</td>
                                @else
                                <td class='text-center' style='vertical-align:middle'></td>
                                @endif
                                @if(isset($c[3]) && $c[3] == 38)
                                <?php $v38++; ?>
                                <td class='text-center' style='vertical-align:middle'>x</td>
                                @else
                                <td class='text-center' style='vertical-align:middle'></td>
                                @endif
                                @if(isset($c[3]) && $c[3] == 73)
                                <?php $v73++; ?>
                                <td class='text-center' style='vertical-align:middle'>x</td>
                                @else
                                <td class='text-center' style='vertical-align:middle'></td>
                                @endif

                                @if(isset($c[3]) && $c[3] == 41)
                                <?php $v41++; ?>
                                <td class='text-center' style='vertical-align:middle'>x</td>
                                @else
                                <td class='text-center' style='vertical-align:middle'></td>
                                @endif
                          
                            @endif
                        @endforeach
                        <?php $total = $total +  $value->HTAT?>
             <!--            <td class='text-right' style='vertical-align:middle'>-</td> -->
                        <td class='text-right' style='vertical-align:middle'>{{number_format($value->HTAT)}}</td>
                    </tr>
                @endforeach
                  <tr>
                        <th class='text-center' colspan="9" style='vertical-align:middle'>Tổng</th>
                       
                        <th class='text-center' style='vertical-align:middle'>{{$v34}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v38}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v73}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v41}}</th>
                       
                        <th class='text-right' style='vertical-align:middle'>{{number_format($total)}}</th>
                    </tr>
            @else
                <p class="text-center text-danger">Không có dữ liệu</p>
            @endif
            </tbody>
        </table>
    </div>

</body>
</html>