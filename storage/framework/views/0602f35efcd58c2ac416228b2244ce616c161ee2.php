<?php $__env->startSection('content'); ?>
<style>
          /*  html, body {
                background-color: #fff;
                color: #636b6f;
                
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }*/

         /*   .full-height {
                height: 100vh;
            }
*/
       .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }
           /* .position-ref {
                position: relative;
            }
*/
           /* .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }*/

            .content {
                text-align: center;
            }

            .title {
                font-size: 50px;
                font-family: "Times New Roman", Times, serif;
                margin-top: 5%;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
/*
            .m-b-md {
                margin-bottom: 30px;
            }*/
        </style>
<section>
     <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    QUẢN LÝ PHÊ DUYỆT VÀ CHI TRẢ CHẾ ĐỘ CHÍNH SÁCH
					<br/>
					QUẢN LÝ CÁC KHOẢN THU ĐỐI VỚI HỌC SINH
                </div>
            </div>
        </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>