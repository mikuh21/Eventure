<?php $__env->startSection('title', 'Participant Details'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1><?php echo e($participant->name); ?></h1>
            <div class="actions">
                <a class="btn" href="<?php echo e(route('events.participants.index', $event)); ?>">Back</a>
                <a class="btn" href="<?php echo e(route('events.participants.edit', [$event, $participant])); ?>">Edit</a>
                <a class="btn" href="<?php echo e(route('participants.submissions.index', $participant)); ?>">Submissions</a>
                <a class="btn" href="<?php echo e(route('participants.evaluations.index', $participant)); ?>">Evaluations</a>
                <a class="btn btn-secondary" href="<?php echo e(route('participants.digital-id.show', $participant)); ?>">Digital ID</a>
            </div>
        </div>

        <p><strong>Email:</strong> <?php echo e($participant->email); ?></p>
        <p><strong>Event:</strong> <?php echo e($event->title); ?></p>
        <p><strong>Status:</strong> <?php echo e(ucfirst($participant->status ?? 'pending')); ?></p>
        <p><strong>Attended:</strong> <?php echo e($participant->attended ? 'Yes' : 'No'); ?></p>
        <p><strong>Total Submissions:</strong> <?php echo e($participant->submissions_count); ?></p>
        <p><strong>Total Evaluations:</strong> <?php echo e($participant->evaluations_count); ?></p>

        <?php if(($participant->status ?? 'pending') !== 'approved'): ?>
            <form action="<?php echo e(route('events.participants.approve', [$event, $participant])); ?>" method="POST" style="margin-bottom: 12px;">
                <?php echo csrf_field(); ?>
                <button class="btn btn-primary" type="submit">Approve Participant</button>
            </form>
        <?php endif; ?>

        <form action="<?php echo e(route('events.participants.destroy', [$event, $participant])); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this participant?')">Delete Participant</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/participants/show.blade.php ENDPATH**/ ?>