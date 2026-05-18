<p>Hello <?php echo e($participant->name); ?>,</p>

<p>You are successfully registered for <strong><?php echo e($participant->event->title); ?></strong>.</p>

<p>Event details:</p>
<ul>
    <li>Event: <?php echo e($participant->event->title); ?></li>
    <li>Date: <?php echo e($participant->event->event_date); ?></li>
    <li>Location: <?php echo e($participant->event->location); ?></li>
</ul>

<p>Your digital gate pass:</p>
<p><a href="<?php echo e($digitalIdUrl); ?>">Open Digital ID</a></p>

<p>Please keep this link for entry verification.</p>
<?php /**PATH /var/www/html/resources/views/emails/participant-registered.blade.php ENDPATH**/ ?>