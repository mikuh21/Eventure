<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1>System Dashboard</h1>
            <a class="btn" href="<?php echo e(route('admin.analytics.events')); ?>">Open Analytics</a>
        </div>

        <table>
            <tbody>
            <tr>
                <th>Total Events</th>
                <td><?php echo e($stats['total_events']); ?></td>
            </tr>
            <tr>
                <th>Total Participants</th>
                <td><?php echo e($stats['total_participants']); ?></td>
            </tr>
            <tr>
                <th>Total Evaluations (Survey Count)</th>
                <td><?php echo e($stats['total_evaluations']); ?></td>
            </tr>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>