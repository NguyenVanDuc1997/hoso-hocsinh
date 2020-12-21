<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trang Chủ</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <style>
        .card {
            
            font-size: 20px;
            text-transform: uppercase;
            box-shadow: 1px 1px 14px 2px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }

        .card:hover {
            transition: 0.3s;
            transform: scale(1.03);
        }
        a:hover {
            text-decoration: none !important;
        }
        .card .card-header {
            min-height: 120px;
        }
        .card-body {
            align-items: center;
            text-align:center;
        }
        .card-body img {
            margin: auto;
        }

        .page-header {
            background: rgba(90, 228, 244, 0.15);
            box-shadow: 0px 1px 13px 1px rgba(0,0,0,0.2);
            color: #1200ff;
        }
        body {
            background: url("<?php echo e(asset('images/bk.jpg')); ?>") no-repeat fixed center;
        }
    </style>

</head>
<body class="skin-blue sidebar-mini">
<header>
    <div class="page-header">
        <div style="text-align:center; margin: 40px; font-size: 32px;">
            Hệ thống phần mềm quản lý trường học
        </div>
    </div>
</header>
<div class="container">
    <div class="row" style="margin-top: 8%;"></div>
    <div class="row">
      <div class = "col-lg-6">
      <a href = "<?php echo e(url('/')); ?>">
        <div class = "card">
            <div class = "card-header">Quản lý phê duyệt và chi trả chế độ chính sách đối với học sinh</div>
            <div class = "card-body">
                <img src="<?php echo e(asset('images/pm1.png')); ?>"/>
            </div>
        </div>
      </a>
      </div>          

      <div class = "col-lg-6">
      <a href = "http://thuxahoihoa.revosoft.vn">
        <div class = "card">
            <div class = "card-header">Quản lý các khoản thu và ăn bán trú đối với học sinh</div>
            <div class = "card-body">
                <img src="<?php echo e(asset('images/pm2.png')); ?>"/>
            </div>
        </div>
      </a>
      </div>          



    </div>
</div>

</body>
</html>

