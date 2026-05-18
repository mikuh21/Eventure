<?php $__env->startSection('title', 'Verify Digital ID'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1>Verify Digital ID</h1>
            <a class="btn" href="<?php echo e(route('admin.dashboard')); ?>">Back to Dashboard</a>
        </div>

        <form action="<?php echo e(route('admin.digital-id.verify.check')); ?>" method="GET">

            <div class="field">
                <label for="token">Token (recommended)</label>
                <input id="token" name="token" type="text" value="<?php echo e(old('token', $submittedToken ?? '')); ?>" placeholder="Paste token from QR payload">
            </div>

            <div class="field">
                <label for="payload">Full QR payload JSON (optional)</label>
                <textarea id="payload" name="payload" rows="4" placeholder='{"participant_id":1,"event_id":1,"token":"..."}'><?php echo e(old('payload', $submittedPayload ?? '')); ?></textarea>
            </div>

            <button class="btn btn-primary" type="submit">Verify</button>
        </form>
    </div>

    <?php if(isset($verificationAttempted)): ?>
        <div class="card">
            <?php if($participant): ?>
                <h2>Valid Digital ID</h2>
                <p><strong>Participant:</strong> <?php echo e($participant->name); ?></p>
                <p><strong>Email:</strong> <?php echo e($participant->email); ?></p>
                <p><strong>Event:</strong> <?php echo e($participant->event->title); ?></p>
                <p><strong>Participant ID:</strong> <?php echo e($participant->id); ?></p>
            <?php else: ?>
                <h2>Invalid Digital ID</h2>
                <p>No participant matched the provided token or payload.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/digital-id-verify.blade.php ENDPATH**/ ?>