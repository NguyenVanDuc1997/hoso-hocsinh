<!DOCTYPE html>
<html>
<head>
    <style type="text/css" media="print">
      @page  { size: landscape; }
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
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">Đơn vị: <?php echo e($school); ?></h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">Mã QHNS: .........................</h1>
        <br/>
        <h1 class="search-title clearfix" style="font-size: 15px;text-align: center;text-transform: uppercase;"><?php echo e($title); ?></h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-align: center;font-weight: 400">Ngày .... tháng .... năm .....</h1>
        <table class=" table-bordered" style="border: 1px solid #ddd;font-size: 13px;width: 100%">
            <thead id="dataHeaderDetailDT">
                <tr class="success">
                    <th class="text-center" rowspan="2">Thời điểm</th>
                    <th class="text-center" colspan="11">Hỗ trợ</th>
                    <th class="text-center" rowspan="2">Tổng</th>
                </tr>
                <tr class="success">
                    <th class="text-center">MGHP</th>
                    <th class="text-center">CPHT</th>
                    <th class="text-center">AT TEMG</th>
                    <th class="text-center">BT TA</th>
                    <th class="text-center">BT TO</th>
                    <th class="text-center">BT VHTT</th>
                    <th class="text-center">TA HS</th>
                    <th class="text-center">HSKT HB</th>
                    <th class="text-center">HSKT DDHT</th>
                    <th class="text-center">HSDTNT</th>
                    <th class="text-center">HSDTTS</th>
                </tr>
            </thead>
            <tbody>
            <?php if(!empty($data)): ?>
                <?php  $dt_1 = $data['dutoan_1']; ?>
                <tr>
                    <td class='text-center'>Từ 01/<?php echo e($nam); ?> đến 05/<?php echo e($nam); ?></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['MGHP'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['CPHT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTAT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTBT_TA'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTBT_TO'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTBT_VHTT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTATHS'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HSKT_HB'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HSKT_DDHT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HBHSDTNT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HSDTTS'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['TONG'])); ?></b></td>
                </tr>
                <?php  $dt_2 = $data['dutoan_2']; ?>
                <tr>
                    <td class='text-center'>Từ 09/<?php echo e($nam); ?> đến 12/<?php echo e($nam); ?></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['MGHP'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['CPHT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['HTAT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['HTBT_TA'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['HTBT_TO'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['HTBT_VHTT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['HTATHS'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['HSKT_HB'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['HSKT_DDHT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['HBHSDTNT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['HSDTTS'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_2['TONG'])); ?></b></td>
                </tr>
                <tr>
                    <td class='text-center'>Dự toán</td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['MGHP'] + $dt_2['MGHP'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['CPHT'] + $dt_2['CPHT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTAT'] + $dt_2['HTAT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTBT_TA'] + $dt_2['HTBT_TA'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTBT_TO'] + $dt_2['HTBT_TO'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTBT_VHTT'] + $dt_2['HTBT_VHTT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HTATHS'] + $dt_2['HTATHS'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HSKT_HB'] + $dt_2['HSKT_HB'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HSKT_DDHT'] + $dt_2['HSKT_DDHT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HBHSDTNT'] + $dt_2['HBHSDTNT'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['HSDTTS'] + $dt_2['HSDTTS'])); ?></b></td>
                    <td class='text-right'><b><?php echo e(number_format($dt_1['TONG'] + $dt_2['TONG'])); ?></b></td>
                </tr>
            <?php else: ?>
                <p class="text-center text-danger">Không có dữ liệu</p>
            <?php endif; ?>
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