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
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">Phòng ..............................</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;"><?php echo e($school); ?></h1>
        <br/>
        <h1 class="search-title clearfix" style="font-size: 15px;text-align: center;text-transform: uppercase;"><?php echo e($title); ?></h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-align: center;font-weight: 400"><?php echo e($hocky); ?> Năm học: <?php echo e($namhoc); ?></h1>
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
            <?php if(!empty($data)): ?>
                <?php $total = 0; ?>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $val = Round($value->nhu_cau * $value->count_month/1000)*1000; $total = $total + $val; ?>
                    <tr>
                        <td class='text-center' style='vertical-align:middle'><?php echo e(($key + 1)); ?></td>
                        <td style='vertical-align:middle'><?php echo e($value->profile_name); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e(\Carbon\Carbon::parse($value->profile_birthday)->format('d-m-Y')); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e(($value->class_name)); ?></td>
                        <td class='text-right' style='vertical-align:middle'><?php echo e(number_format($value->nhu_cau)); ?></td>
                        <td style='vertical-align:middle'><?php echo e($value->subject_name); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->count_month); ?></td>
                        <td class='text-right' style='vertical-align:middle'><?php echo e(number_format($val)); ?></td>
                        <td class='text-center' style='vertical-align:middle'></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'>Tổng</th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-right' style='vertical-align:middle'><?php echo e(number_format($total)); ?></th>
                        <th class='text-center' style='vertical-align:middle'></th>
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