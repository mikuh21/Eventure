<?php $__env->startSection('title', 'Submissions'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1>Submissions for <?php echo e($participant->name); ?></h1>
            <div class="actions">
                <a class="btn" href="<?php echo e(route('events.participants.show', [$participant->event, $participant])); ?>">Back</a>
                <a class="btn btn-primary" href="<?php echo e(route('participants.submissions.create', $participant)); ?>">Upload Submission</a>
            </div>
        </div>

        <?php if($submissions->count() === 0): ?>
            <p>No submissions found.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($submission->title); ?></td>
                        <td><?php echo e($submission->status); ?></td>
                        <td><a class="btn" href="<?php echo e(asset('storage/'.$submission->file_path)); ?>" target="_blank">Open File</a></td>
                        <td>
                            <div class="actions">
                                <a class="btn" href="<?php echo e(route('participants.submissions.show', [$participant, $submission])); ?>">View</a>
                                <a class="btn" href="<?php echo e(route('participants.submissions.edit', [$participant, $submission])); ?>">Edit</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div class="pagination"><?php echo e($submissions->links()); ?></div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/submissions/index.blade.php ENDPATH**/ ?>