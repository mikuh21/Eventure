<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card" style="max-width: 520px; margin: 0 auto;">
        <div class="header-row">
            <h1>Login</h1>
            <a class="btn" href="<?php echo e(route('participants.public.events')); ?>">Back</a>
        </div>

        <form action="<?php echo e(route('login')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div class="field" style="display:flex; align-items:center; gap:8px;">
                <input id="remember" name="remember" type="checkbox" value="1" style="width:auto;">
                <label for="remember" style="margin:0;">Remember me</label>
            </div>

            <button class="btn btn-primary" type="submit">Sign In</button>
        </form>

        <?php if(app()->environment(['local', 'testing'])): ?>
            <hr style="margin:16px 0; border:none; border-top:1px solid #e5e7eb;">
            <p style="margin:0 0 10px; font-size:14px; color:#4b5563;"><strong>Quick Test Login</strong></p>

            <div class="actions">
                <form action="<?php echo e(route('login.test-as')); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="role" value="admin">
                    <button class="btn btn-secondary" type="submit">Login as Admin</button>
                </form>

                <form action="<?php echo e(route('login.test-as')); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="role" value="user">
                    <button class="btn" type="submit">Login as User</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>