<?php $__env->startSection('title', 'Reset Password'); ?>
<?php $__env->startSection('description', 'Reset Your Password'); ?>

<?php $__env->startSection('content'); ?>
	<div class="container">
		<div class="card-container text-center">
			<h1>Reset Password</h1>
			<h2>Please provide your email address</h2>
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
            <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
            <img id="profile-img" class="profile-img-card" src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" method="POST" action="<?php echo e(url('/password/reset')); ?>">
                <?php echo e(csrf_field()); ?>

				<input type="hidden" name="token" value="<?php echo e($token); ?>">
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo e(isset($email) ? $email : old('email')); ?>" required autofocus>
				<?php if($errors->has('email')): ?>
					<span class="help-block">
						<strong><?php echo e($errors->first('email')); ?></strong>
					</span>
				<?php endif; ?>
				<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
				<?php if($errors->has('password')): ?>
					<span class="help-block">
						<strong><?php echo e($errors->first('password')); ?></strong>
					</span>
				<?php endif; ?>
				<input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
				<?php if($errors->has('password_confirmation')): ?>
					<span class="help-block">
						<strong><?php echo e($errors->first('password_confirmation')); ?></strong>
					</span>
				<?php endif; ?>
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Thay đổi</button>
            </form><!-- /form -->
        </div><!-- /card-container -->
		
    </div><!-- /container -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>