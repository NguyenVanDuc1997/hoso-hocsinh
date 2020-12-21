<?php $__env->startSection('title', 'Quên mật khẩu'); ?>
<?php $__env->startSection('description', 'Quên mật khẩu đăng nhập phần mềm.'); ?>

<?php $__env->startSection('content'); ?>
	<div class="container">
		<div class="card-container text-center">
			<?php if(session('status')): ?>
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<?php echo e(session('status')); ?>

				</div>
			<?php endif; ?>
			<?php if($errors->has('email')): ?>
				<div class="alert alert-danger fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<?php echo e($errors->first('email')); ?>

				</div>
			<?php endif; ?>
		</div>
        <div class="card card-container">
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" method="POST" action="<?php echo e(url('/password/email')); ?>">
                <?php echo e(csrf_field()); ?>

                <input type="email" id="email" name="email" class="form-control" placeholder="Địa chỉ email" value="<?php echo e(old('email')); ?>" required autofocus>
				<?php if($errors->has('email')): ?>
					<span class="help-block">
						<strong><?php echo e($errors->first('email')); ?></strong>
					</span>
				<?php endif; ?>

                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Gửi</button>
                 <button class="btn btn-lg btn-primary btn-block btn-signin" type="button" onclick="document.location.replace('/login');">Đăng nhập
                 </button>
                
        
            </form><!-- /form -->
           
        </div><!-- /card-container -->
		
    </div><!-- /container -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>