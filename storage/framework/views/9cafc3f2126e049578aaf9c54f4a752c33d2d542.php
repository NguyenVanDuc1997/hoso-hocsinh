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
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 6%'>Ngày sinh</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 6%'>Lớp học</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 6%'>Dân tộc</th>
                    <th class='text-center' colspan="3" style='vertical-align:middle;'>Hộ khẩu thường trú</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 6%'>Họ tên chủ hộ trong sổ hộ khẩu của học sinh</th>
                    <th class='text-center' colspan="2" style='vertical-align:middle;'>Điều kiện xác định học sinh không thể đi đến trường và trở về trong ngày (đơn vị km)</th>
                    <th class='text-center' rowspan="2" style='vertical-align:middle;width: 6%'>Tổng kinh phí hỗ trợ</th>
                    <th class='text-center' colspan="3" style='vertical-align:middle;'>Trong đó</th>

                </tr>
                <tr class="success" style="background-color: #ddd;">
                    <th class='text-center' style='vertical-align:middle;width: 6%'>Thôn xóm</th>
                    <th class='text-center' style='vertical-align:middle;width: 6%'>Phường xã</th>
                    <th class='text-center' style='vertical-align:middle;width: 6%'>Quận huyện</th>
                    <th class='text-center' style='vertical-align:middle;width: 6%'>Trường hợp nhà ở xa trường</th>
                    <th class='text-center' style='vertical-align:middle;width: 6%'>Trường hợp giao thông cách trở, giao thông đi lại khó khăn</th>
                    <th class='text-center' style='vertical-align:middle;width: 6%'>Hỗ trợ tiền ăn</th>
                    <th class='text-center' style='vertical-align:middle;width: 6%'>Hỗ trợ tiền ở</th>
                    <th class='text-center' style='vertical-align:middle;width: 6%'>Tiền VHTT, tủ thuốc</th>
                </tr>
                
            </thead>
            <tbody>
            <?php if(!empty($data)): ?>
                <?php 
       
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
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->profile_km); ?></td>
                        <td class='text-center' style='vertical-align:middle'><?php echo e($value->profile_giaothong); ?></td>
                        <td class='text-right' style='vertical-align:middle'><?php echo e(number_format($value->HTBT_TA + $value->HTBT_TO + $value->HTBT_VHTT)); ?></td>
                        <td class='text-right' style='vertical-align:middle'><?php echo e(number_format($value->HTBT_TA)); ?></td>
                        <td class='text-right' style='vertical-align:middle'><?php echo e(number_format($value->HTBT_TO)); ?></td>
                        <td class='text-right' style='vertical-align:middle'><?php echo e(number_format($value->HTBT_VHTT)); ?></td>
                        
                        <?php $total = $total +  $value->HTBT_TA + $value->HTBT_TO + $value->HTBT_VHTT;?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                        <th class='text-center' colspan="11" style='vertical-align:middle'>Tổng</th>
                       <th class='text-right' style='vertical-align:middle'><?php echo e(number_format($total)); ?></th>
                        <th class='text-center' style='vertical-align:middle'></th>
                        <th class='text-center' style='vertical-align:middle'></th>
                       
                        
                    </tr>
            <?php else: ?>
                <p class="text-center text-danger">Không có dữ liệu</p>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>