<?php $__env->startSection('title', 'Add Participant'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1>Register for Event</h1>
            <a class="btn" href="<?php echo e(route('participants.public.events')); ?>">Back to Events</a>
        </div>

        <p><strong>Event:</strong> <?php echo e($event->title); ?></p>
        <p><strong>Registration Window:</strong> <?php echo e($event->start_registration); ?> to <?php echo e($event->end_registration); ?></p>
        <p><strong>Registration Status:</strong> <?php echo e($event->isRegistrationOpen() ? 'Open' : 'Closed'); ?></p>

        <form action="<?php echo e(route('events.participants.store', $event)); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="<?php echo e(old('name')); ?>" required>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required>
            </div>

            <button class="btn btn-primary" type="submit">Register</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/participants/create.blade.php ENDPATH**/ ?>