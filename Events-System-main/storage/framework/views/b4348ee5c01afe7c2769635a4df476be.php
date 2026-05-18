<?php $__env->startSection('title', 'Digital ID'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1>Digital ID</h1>
            <div class="actions">
                <a class="btn" href="<?php echo e(route('events.participants.show', [$participant->event, $participant])); ?>">Back</a>
                <a class="btn btn-primary" href="<?php echo e(route('participants.digital-id.download', $participant)); ?>">Download QR PNG</a>
            </div>
        </div>

        <p><strong>Participant:</strong> <?php echo e($participant->name); ?></p>
        <p><strong>Event:</strong> <?php echo e($participant->event->title); ?></p>
        <p>
            <strong>Token:</strong>
            <span id="digital-id-token"><?php echo e($participant->digital_id_token); ?></span>
            <button type="button" class="btn" id="copy-token-btn">Copy Token</button>
            <span id="copy-token-status"></span>
        </p>

        <div><?php echo $qrSvg; ?></div>

        <p><strong>QR Payload:</strong></p>
        <pre><?php echo e($payload); ?></pre>
    </div>

    <script>
        (function () {
            var button = document.getElementById('copy-token-btn');
            var tokenEl = document.getElementById('digital-id-token');
            var statusEl = document.getElementById('copy-token-status');

            if (!button || !tokenEl || !statusEl) {
                return;
            }

            button.addEventListener('click', async function () {
                try {
                    await navigator.clipboard.writeText(tokenEl.textContent.trim());
                    statusEl.textContent = 'Copied';
                    setTimeout(function () {
                        statusEl.textContent = '';
                    }, 1500);
                } catch (e) {
                    statusEl.textContent = 'Copy failed';
                }
            });
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/participants/digital-id.blade.php ENDPATH**/ ?>