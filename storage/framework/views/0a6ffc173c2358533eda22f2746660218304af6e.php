<?php if(Session::has('message')): ?>
    <div class="alert alert-<?php echo e(Session::get('status')); ?> status-box">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <?php echo e(Session::get('message')); ?>

    </div>
<?php endif; ?>