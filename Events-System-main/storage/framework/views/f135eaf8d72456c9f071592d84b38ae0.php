<?php $__env->startSection('title', 'Event Analytics'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1>Event Analytics</h1>
            <a class="btn" href="<?php echo e(route('admin.dashboard')); ?>">Back to Dashboard</a>
        </div>

        <form method="GET" action="<?php echo e(route('admin.analytics.events')); ?>">
            <div class="field">
                <label for="period">Filter Scope</label>
                <select id="period" name="period" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                    <option value="overall" <?php echo e($analytics['period'] === 'overall' ? 'selected' : ''); ?>>Overall</option>
                    <option value="month" <?php echo e($analytics['period'] === 'month' ? 'selected' : ''); ?>>Per Month</option>
                    <option value="year" <?php echo e($analytics['period'] === 'year' ? 'selected' : ''); ?>>Per Year</option>
                </select>
            </div>

            <div class="field">
                <label for="month">Month</label>
                <input id="month" name="month" type="number" min="1" max="12" value="<?php echo e($analytics['month']); ?>">
            </div>

            <div class="field">
                <label for="year">Year</label>
                <input id="year" name="year" type="number" min="2000" max="2100" value="<?php echo e($analytics['year']); ?>">
            </div>

            <button class="btn btn-primary" type="submit">Apply Filter</button>
        </form>
    </div>

    <div class="card">
        <h2>Summary</h2>
        <table>
            <tbody>
            <tr>
                <th>Scope</th>
                <td><?php echo e(ucfirst($analytics['period'])); ?></td>
            </tr>
            <tr>
                <th>Total Participants</th>
                <td><?php echo e($analytics['total_participants']); ?></td>
            </tr>
            <tr>
                <th>Total Evaluations</th>
                <td><?php echo e($analytics['total_evaluations']); ?></td>
            </tr>
            <tr>
                <th>Participants - Evaluations</th>
                <td><?php echo e($analytics['participants_minus_evaluations']); ?></td>
            </tr>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/event-analytics.blade.php ENDPATH**/ ?>