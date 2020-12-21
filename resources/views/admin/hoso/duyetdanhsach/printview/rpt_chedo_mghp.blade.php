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
                    <th class='text-center' rowspan="3" style='vertical-align:middle;width: 2%'>STT</th>
                    <th class='text-center' rowspan="3" style='vertical-align:middle;width: 9%'>Họ và tên</th>
                    <th class='text-center' rowspan="3" style='vertical-align:middle;width: 5%'>Ngày sinh</th>
                    <th class='text-center' rowspan="3" style='vertical-align:middle;width: 5%'>Lớp học</th>
                    <th class='text-center' rowspan="3" style='vertical-align:middle;width: 5%'>Dân tộc</th>
                    <th class='text-center' colspan="3" style='vertical-align:middle;'>Hộ khẩu thường trú</th>
                    <th class='text-center' rowspan="3" style='vertical-align:middle;'>Họ tên chủ hộ trong sổ hộ khẩu của học sinh</th>
                    <th class='text-center' colspan="6" style='vertical-align:middle;'>Đối tượng miễn 100%</th>
                    <th class='text-center' colspan="3" style='vertical-align:middle;'>Đối tượng giảm </th>
                  <!--   <th class='text-center' rowspan="3" style='vertical-align:middle;'> Mức thu học phí cấp có thẩm quyền</th> -->
                    <th class='text-center' rowspan="3" style='vertical-align:middle;width: 7%'> Kinh phí bù, miễn, giảm học phí </th>
                </tr>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Thôn xóm</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Phường xã</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Quận huyện</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Thân nhân người có công với cách mạng</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Không có nguồn nuôi dưỡng theo quy định tại khoản 1 Điều 5 NĐ 136.2013.NĐ-CP</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Con của hạ sĩ quan và binh sĩ, chiến sĩ trong LLVTND</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Hộ nghèo (thu nhập) và Nghèo đa chiều (thiếu hụt chỉ số về giáo dục)</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Mồ côi cả cha lẫn mẹ</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Bị tàn tật,khuyết tật , Hộ cận nghèo</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 5%'>Dân tộc thiểu số, Ở vùng đặc biệt khó khăn (Mức 70%)</th>
                    <th class='text-center' colspan="2" style='vertical-align:middle;width: 5%'>Mức 50%</th>
                </tr>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' style='vertical-align:middle;width: 5%'>Con CB, CNVC mà cha hoặc mẹ bị tai nan LĐ được hưởng trợ cấp thường xuyên</th>
                    <th class='text-center' style='vertical-align:middle;width: 5%'>Hộ cận nghèo và Nghèo đa chiều (thiếu hụt chỉ số DVXH khác)</th>
                </tr>
            </thead>
            <tbody>
            @if(!empty($data))
                <?php $v35 = 0;
                $v38 = 0;
                $v39 = 0;
                $v73 = 0;
                $v28 = 0;
                $v100 = 0;
                $v101 = 0;
                $v40 = 0;
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
                            @if($check && ($c[0] == 89 || $c[0] == 90 || $c[0] == 91))
                            <?php $check = false; ?>
                                @if(isset($c[3]) && $c[3] == 35)
                                <?php $v35++; ?>
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
                                @if(isset($c[3]) && $c[3] == 39)
                                <?php $v39++; ?>
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
                                @if(isset($c[3]) && $c[3] == 28)
                                <?php $v28++; ?>
                                <td class='text-center' style='vertical-align:middle'>x</td>
                                @else
                                <td class='text-center' style='vertical-align:middle'></td>
                                @endif
                                @if(isset($c[3]) && $c[3] == 100)
                                <?php $v100++; ?>
                                <td class='text-center' style='vertical-align:middle'>x</td>
                                @else
                                <td class='text-center' style='vertical-align:middle'></td>
                                @endif
                                @if(isset($c[3]) && $c[3] == 101)
                                <?php $v101++; ?>
                                <td class='text-center' style='vertical-align:middle'>x</td>
                                @else
                                <td class='text-center' style='vertical-align:middle'></td>
                                @endif
                                @if(isset($c[3]) && $c[3] == 40)
                                <?php $v40++; ?>
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
                            @else
                            <?php break; ?>
                            @endif
                        @endforeach
                        <?php $total = $total +  $value->MGHP?>
             <!--            <td class='text-right' style='vertical-align:middle'>-</td> -->
                        <td class='text-right' style='vertical-align:middle'>{{number_format($value->MGHP)}}</td>
                    </tr>
                @endforeach
                  <tr>
                        <th class='text-center' colspan="9" style='vertical-align:middle'>Tổng</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v35}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v38}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v39}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v73}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v28}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v100}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v101}}</th>
                        <th class='text-center' style='vertical-align:middle'>{{$v40}}</th>
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