<?php $__env->startSection('title', 'Event Details'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1><?php echo e($event->title); ?></h1>
            <div class="actions">
                <a class="btn" href="<?php echo e(route('events.index')); ?>">Back</a>
                <a class="btn" href="<?php echo e(route('events.edit', $event)); ?>">Edit</a>
                <a class="btn btn-primary" href="<?php echo e(route('events.participants.create', $event)); ?>">Add Participant</a>
            </div>
        </div>

        <p><strong>Date:</strong> <?php echo e($event->event_date); ?></p>
        <p><strong>Type:</strong> <?php echo e(ucfirst($event->type)); ?></p>
        <p><strong>Registration Opens:</strong> <?php echo e($event->start_registration); ?></p>
        <p><strong>Registration Closes:</strong> <?php echo e($event->end_registration); ?></p>
        <p><strong>Registration Status:</strong> <?php echo e($event->registration_open ? 'Open' : 'Closed'); ?></p>
        <p><strong>Location:</strong> <?php echo e($event->location); ?></p>
        <?php if($event->type === 'conference'): ?>
            <p><strong>Conference Title:</strong> <?php echo e($event->conference_title); ?></p>
            <p><strong>Theme:</strong> <?php echo e($event->theme ?: 'N/A'); ?></p>
            <p><strong>Keywords:</strong> <?php echo e(is_array($event->keywords) ? implode(', ', $event->keywords) : 'N/A'); ?></p>
            <?php if($event->template_file_path): ?>
                <p><a class="btn" href="<?php echo e(route('events.template.download', $event)); ?>">Download Conference Template</a></p>
            <?php endif; ?>
        <?php else: ?>
            <p><strong>Event Title:</strong> <?php echo e($event->event_title); ?></p>
            <p><strong>Description:</strong> <?php echo e($event->description ?: 'No description provided.'); ?></p>
        <?php endif; ?>
        <?php if($event->poster_url): ?>
            <p><a class="btn" href="<?php echo e($event->poster_url); ?>" target="_blank">View Poster</a></p>
        <?php endif; ?>
        <p><strong>Total Participants:</strong> <?php echo e($event->participants_count); ?></p>
        <p><strong>Average Rating:</strong> <?php echo e($event->average_rating ?? 'N/A'); ?></p>
        <p>
            <a class="btn" href="<?php echo e(route('events.submissions.index', $event)); ?>">View All Event Submissions</a>
        </p>
    </div>

    <div class="card">
        <div class="header-row">
            <h2>Participants</h2>
            <a class="btn" href="<?php echo e(route('events.participants.index', $event)); ?>">Open Full List</a>
        </div>

        <?php if($participants->count() === 0): ?>
            <p>No participants yet.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $participants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($participant->name); ?></td>
                        <td><?php echo e($participant->email); ?></td>
                        <td><?php echo e($participant->created_at); ?></td>
                        <td>
                            <div class="actions">
                                <a class="btn" href="<?php echo e(route('events.participants.show', [$event, $participant])); ?>">View</a>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/events/show.blade.php ENDPATH**/ ?>