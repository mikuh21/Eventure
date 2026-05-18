<?php $__env->startSection('title', 'Events'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1>Events</h1>
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isAdmin()): ?>
                    <a class="btn btn-primary" href="<?php echo e(route('events.create')); ?>">Create Event</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if($events->count() === 0): ?>
            <p>No events available right now.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Registration</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($event->title); ?></td>
                        <td><?php echo e(ucfirst($event->type)); ?></td>
                        <td><?php echo e($event->event_date); ?></td>
                        <td><?php echo e($event->isRegistrationOpen() ? 'Open' : 'Closed'); ?></td>
                        <td><?php echo e($event->location); ?></td>
                        <td>
                            <div class="actions">
                                <?php if(auth()->guard()->check()): ?>
                                    <?php if(auth()->user()->isAdmin()): ?>
                                        <a class="btn" href="<?php echo e(route('events.show', $event)); ?>">View</a>
                                        <a class="btn" href="<?php echo e(route('events.edit', $event)); ?>">Edit</a>
                                        <a class="btn btn-secondary" href="<?php echo e(route('events.participants.index', $event)); ?>">Participants</a>
                                        <form action="<?php echo e(route('events.destroy', $event)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this event?')">Delete</button>
                                        </form>
                                    <?php else: ?>
                                        <a class="btn btn-primary" href="<?php echo e(route('events.participants.create', $event)); ?>">Register</a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a class="btn btn-primary" href="<?php echo e(route('events.participants.create', $event)); ?>">Register</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php echo e($events->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/events/index.blade.php ENDPATH**/ ?>