<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Event Management System'); ?></title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px;
        }

        .top-nav {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #111827;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
        }

        .btn-danger {
            background: #dc2626;
            border-color: #dc2626;
            color: #ffffff;
        }

        .btn-secondary {
            background: #4b5563;
            border-color: #4b5563;
            color: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        .field {
            margin-bottom: 14px;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .alert-success {
            background: #ecfdf5;
            border: 1px solid #10b981;
            color: #065f46;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
        }

        .pagination {
            margin-top: 12px;
        }

        @media (max-width: 640px) {
            .container {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="top-nav">
        <a class="btn" href="<?php echo e(route('participants.public.events')); ?>">Participant Events</a>
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->isAdmin()): ?>
                <a class="btn" href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a>
                <a class="btn" href="<?php echo e(route('events.index')); ?>">Events</a>
                <a class="btn" href="<?php echo e(route('admin.analytics.events')); ?>">Event Analytics</a>
                <a class="btn" href="<?php echo e(route('admin.digital-id.verify.form')); ?>">Verify Digital ID</a>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(auth()->guard()->check()): ?>
            <form action="<?php echo e(route('logout')); ?>" method="POST" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button class="btn" type="submit">Logout (<?php echo e(auth()->user()->role); ?>)</button>
            </form>
        <?php else: ?>
            <a class="btn" href="<?php echo e(route('login')); ?>">Login</a>
        <?php endif; ?>
    </div>

    <?php if(session('status')): ?>
        <div class="alert-success"><?php echo e(session('status')); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert-error">
            <strong>Please fix the following:</strong>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
</div>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>