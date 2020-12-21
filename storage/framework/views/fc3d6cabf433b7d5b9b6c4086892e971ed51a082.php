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
   <h1 class="search-title clearfix" style="font-size: 13px;text-transform: uppercase;"><?php echo e($school); ?></h1>
        <br/>
        <h1 class="search-title clearfix" style="font-size: 15px;text-align: center;text-transform: uppercase;"><?php echo e($title); ?></h1>
        <h1 class="search-title clearfix" style="font-size: 13px;text-align: center;font-weight: 400"><?php echo e($namhoc); ?></h1>
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
                    <th class='text-center' colspan="2" style='vertical-align:middle;'>Đối tượng</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 8%'> Kinh phí </th>
                </tr>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Thôn xóm</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Phường xã</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Quận huyện</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Hộ nghèo (thu nhập) và Nghèo đa chiều (thiếu hụt chỉ số về giáo dục)</th>
                    <th class='text-center' style='vertical-align:middle;width: 8%'>Mồ côi cả cha lẫn mẹ</th>
                </tr>
                
            </thead>
            <tbody>
            <?php if(!empty($data)): ?>
                <?php 
                $v73 = 0;
                $v28 = 0;
                $total = 0; ?>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php 
                    $chedo = explode(';',$value->bckp_danhsach_chedo);
                    $check = true;
                ?>
                    <tr>
                        <td class='text-center' style='vertical-align:middle'><?php echo e(($key + 1)); ?></td>
                        <td style='vertical-align:middle'><?php echo e($value->profile_name); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e(Carbon\Carbon::parse($value->profile_birthday)->format('d-m-Y')); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->class_name); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->nationals_name); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->thon_name); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->phuong_name); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->quan_name); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->profile_parentname); ?></td>
                        <?php $__currentLoopData = $chedo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                            <?php $c = explode('|',$cd); ?>
                            
                            <?php if($check && ($c[0] == 92)): ?>
                                <?php $check = false; ?>
                                <?php if(isset($c[3]) && $c[3] == 73): ?>
                                <?php $v73++; ?>
                                <td class='text-center' style='vertical-align:middle'>x</td>
                                <?php else: ?>
                                <td class='text-center' style='vertical-align:middle'></td>
                                <?php endif; ?>
                                <?php if(isset($c[3]) && $c[3] == 28): ?>
                                <?php $v28++; ?>
                                <td class='text-center' style='vertical-align:middle'>x</td>
                                <?php else: ?>
                                <td class='text-center' style='vertical-align:middle'></td>
                                <?php endif; ?>
                                
                          
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php $total = $total +  $value->CPHT?>
             <!--            <td class='text-right' style='vertical-align:middle'>-</td> -->
                        <td class='text-right' style='vertical-align:middle'><?php echo e(number_format($value->CPHT)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                        <th class='text-center' colspan="9" style='vertical-align:middle'>Tổng</th>
                       
                        <th class='text-center' style='vertical-align:middle'><?php echo e($v73); ?></th>
                        <th class='text-center' style='vertical-align:middle'><?php echo e($v28); ?></th>
                       
                        <th class='text-right' style='vertical-align:middle'><?php echo e(number_format($total)); ?></th>
                    </tr>
            <?php else: ?>
                <p class="text-center text-danger">Không có dữ liệu</p>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
   
</body>
</html>