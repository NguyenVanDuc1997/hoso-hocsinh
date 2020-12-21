<?php $__env->startSection('title', 'Resend Activation Key'); ?>
<?php $__env->startSection('description', 'Resend the activation key'); ?>

<?php $__env->startSection('content'); ?>
	<div class="container">
		<div class="card-container text-center">
			<h1>Resend Activation Key</h1>
			<h2>Please provide your registration email address</h2>
			<?php echo $__env->make('notifications.status_message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		    <?php echo $__env->make('notifications.errors_message', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>
        <div class="card card-container">
            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
            <img id="profile-img" class="profile-img-card" src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" method="POST" action="<?php echo e(url('/activation/resend')); ?>">
                <?php echo e(csrf_field()); ?>

                <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo e(old('email')); ?>" required autofocus>
				<?php if($errors->has('email')): ?>
					<span class="help-block">
						<strong><?php echo e($errors->first('email')); ?></strong>
					</span>
				<?php endif; ?>

                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Resend Activation Code</button>
            </form><!-- /form -->
            <a href="<?php echo e(url('/password/reset')); ?>" class="forgot-password">Forgot password?</a> or <a href="<?php echo e(url('/username/reminder')); ?>" class="forgot-password">Forgot username?</a>
        </div><!-- /card-container -->
		<div class="card-container text-center">
			<a href="<?php echo e(url('/register')); ?>" class="new-account">Create an account</a> or <a href="<?php echo e(url('/login')); ?>" class="new-account">Login</a>
		</div>
		
    </div><!-- /container -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>