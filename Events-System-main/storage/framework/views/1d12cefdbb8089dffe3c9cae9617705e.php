<?php $__env->startSection('title', 'Registration Confirmation'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <h1>Registration Confirmed</h1>
        <p><strong>Participant:</strong> <?php echo e($participant->name); ?></p>
        <p><strong>Email:</strong> <?php echo e($participant->email); ?></p>
        <p><strong>Event:</strong> <?php echo e($participant->event->title); ?></p>

        <div class="actions">
            <a class="btn btn-primary" href="<?php echo e(route('participants.digital-id.show', $participant)); ?>">Open Digital ID</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/participants/confirmation.blade.php ENDPATH**/ ?>