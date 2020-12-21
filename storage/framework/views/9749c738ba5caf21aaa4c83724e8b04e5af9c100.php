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
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">UBND Quận/ huyện ..............................</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;">Phòng giáo dục & đào tạo: <?php echo e($school); ?></h1>
        <br/>
        <h1 class="search-title clearfix" style="font-size: 15px;text-align: center;text-transform: uppercase;"><?php echo e($title); ?></h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-align: center;font-weight: 400">Năm học: <?php echo e($namhoc); ?></h1>
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
            <?php if(!empty($data)): ?>
            <?php $tong = 0; ?>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class='text-center' style='vertical-align:middle'><?php echo e(($key + 1)); ?></td>
                        <td style='vertical-align:middle'><?php echo e($value->s1); ?></td>
                        <td style='vertical-align:middle'><?php echo e($value->s2); ?></td>
                            
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->s3); ?></td>
                        <td class='text-right' style='vertical-align:middle'><?php echo e(number_format(str_replace('.','',$value->s4))); ?></td>
                        <td></td>
                    </tr>
                    <?php $tong = $tong + floor(str_replace('.','',$value->s4));  ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td></td>
                        <th style='vertical-align:middle'>Tổng</th>
                        <td></td>
                        
                        <td></td>
                        <th class='text-right' style='vertical-align:middle'><?php echo e(number_format($tong)); ?></th>
                        <td></td>
                    </tr>
                
            <?php else: ?>
                <p class="text-center text-danger">Không có dữ liệu</p>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div style="float: right;width: 40%;text-align: center;"> 
        <h1 class="search-title clearfix" style="font-size: 13px;font-weight: 300;font-style: italic">............., ngày.........tháng.........năm.........</h1>
        <h1 class="search-title clearfix" style="font-size: 13px;"><?php echo e($level_name); ?></h1>
        <h1 class="search-title clearfix" style="font-size: 13px;font-weight: 300;font-style: italic">(Ký, ghi rõ họ tên,đóng dấu)</h1>
    </div>
</body>
</html>