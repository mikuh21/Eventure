<?php $__env->startSection('title', 'Participants'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1>Participants for <?php echo e($event->title); ?></h1>
            <div class="actions">
                <a class="btn" href="<?php echo e(route('events.show', $event)); ?>">Back to Event</a>
                <a class="btn btn-primary" href="<?php echo e(route('events.participants.create', $event)); ?>">Add Participant</a>
            </div>
        </div>

        <?php if($participants->count() === 0): ?>
            <p>No participants yet.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Attended</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($participant->name); ?></td>
                        <td><?php echo e($participant->email); ?></td>
                        <td><?php echo e(ucfirst($participant->status ?? 'pending')); ?></td>
                        <td><?php echo e($participant->attended ? 'Yes' : 'No'); ?></td>
                        <td><?php echo e($participant->created_at); ?></td>
                        <td>
                            <div class="actions">
                                <a class="btn" href="<?php echo e(route('events.participants.show', [$event, $participant])); ?>">View</a>
                                <a class="btn" href="<?php echo e(route('participants.submissions.index', $participant)); ?>">Submissions</a>
                                <a class="btn" href="<?php echo e(route('participants.evaluations.index', $participant)); ?>">Evaluations</a>
                                <a class="btn btn-secondary" href="<?php echo e(route('participants.digital-id.show', $participant)); ?>">Digital ID</a>
                                <?php if(($participant->status ?? 'pending') !== 'approved'): ?>
                                    <form action="<?php echo e(route('events.participants.approve', [$event, $participant])); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-primary" type="submit">Approve</button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('events.participants.destroy', [$event, $participant])); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this participant?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php echo e($participants->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/participants/index.blade.php ENDPATH**/ ?>