<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">

    <title><?php echo $__env->yieldContent('title'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link rel="stylesheet" id="auth-css" href="<?php echo asset('assets/css/auth.css'); ?>" type="text/css" media="all">
	<link href="<?php echo e(asset('css/login.css')); ?>" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

</head>
<body id="body_login">
<div class="navbar navbar-default navbar-static-top" style="background-color: #3B5999;height: 80px">
     <div class="container">
                <div class="navbar-header" >

                    <!-- Branding Image -->
                    <a class="navbar-brand" style="color: white;font-size: x-large;font-weight: bold;margin-top: 15px;" href="<?php echo e(url('/login')); ?>">
                        <?php echo e(config('app.name', 'Laravelll')); ?>

                    </a>
                </div>
                </div>
     </div>
	<?php echo $__env->yieldContent('content'); ?>

</body>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</html>
