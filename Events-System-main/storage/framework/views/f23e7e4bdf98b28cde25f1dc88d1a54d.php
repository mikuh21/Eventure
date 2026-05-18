<?php $__env->startSection('title', 'Create Event'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="header-row">
            <h1>Create Event</h1>
            <a class="btn" href="<?php echo e(route('events.index')); ?>">Back to Events</a>
        </div>

        <form action="<?php echo e(route('events.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="field">
                <label for="type">Event Type</label>
                <select id="type" name="type" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                    <option value="standard" <?php echo e(old('type', 'standard') === 'standard' ? 'selected' : ''); ?>>Standard</option>
                    <option value="conference" <?php echo e(old('type') === 'conference' ? 'selected' : ''); ?>>Conference</option>
                </select>
            </div>

            <div class="field" id="event-title-field">
                <label for="event_title">Event Title (Standard)</label>
                <input id="event_title" name="event_title" type="text" value="<?php echo e(old('event_title')); ?>">
            </div>

            <div class="field" id="conference-title-field">
                <label for="conference_title">Conference Title</label>
                <input id="conference_title" name="conference_title" type="text" value="<?php echo e(old('conference_title')); ?>">
            </div>

            <div class="field" id="conference-theme-field">
                <label for="theme">Theme</label>
                <input id="theme" name="theme" type="text" value="<?php echo e(old('theme')); ?>">
            </div>

            <div class="field" id="conference-keywords-field">
                <label for="keywords">Keywords (comma-separated)</label>
                <input id="keywords" name="keywords" type="text" value="<?php echo e(old('keywords')); ?>" placeholder="AI, Mechatronics, Robotics">
            </div>

            <div class="field" id="standard-description-field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?php echo e(old('description')); ?></textarea>
            </div>

            <div class="field">
                <label for="event_date">Event Date</label>
                <input id="event_date" name="event_date" type="date" value="<?php echo e(old('event_date')); ?>" required>
            </div>

            <div class="field">
                <label for="start_registration">Start Registration</label>
                <input id="start_registration" name="start_registration" type="datetime-local" value="<?php echo e(old('start_registration')); ?>" required>
            </div>

            <div class="field">
                <label for="end_registration">End Registration</label>
                <input id="end_registration" name="end_registration" type="datetime-local" value="<?php echo e(old('end_registration')); ?>" required>
            </div>

            <div class="field">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="<?php echo e(old('location')); ?>" required>
            </div>

            <div class="field">
                <label for="poster">Event Poster</label>
                <input id="poster" name="poster" type="file" accept="image/*">
            </div>

            <div class="field" id="template-file-field">
                <label for="template_file">Conference Template (DOC/PDF)</label>
                <input id="template_file" name="template_file" type="file" accept=".pdf,.doc,.docx">
            </div>

            <button class="btn btn-primary" type="submit">Save Event</button>
        </form>
    </div>

    <script>
        (function () {
            var typeInput = document.getElementById('type');
            var eventTitleField = document.getElementById('event-title-field');
            var conferenceTitleField = document.getElementById('conference-title-field');
            var conferenceThemeField = document.getElementById('conference-theme-field');
            var conferenceKeywordsField = document.getElementById('conference-keywords-field');
            var standardDescriptionField = document.getElementById('standard-description-field');
            var templateFileField = document.getElementById('template-file-field');

            var toggleByType = function () {
                var isConference = typeInput.value === 'conference';

                eventTitleField.style.display = isConference ? 'none' : 'block';
                standardDescriptionField.style.display = isConference ? 'none' : 'block';

                conferenceTitleField.style.display = isConference ? 'block' : 'none';
                conferenceThemeField.style.display = isConference ? 'block' : 'none';
                conferenceKeywordsField.style.display = isConference ? 'block' : 'none';
                templateFileField.style.display = isConference ? 'block' : 'none';
            };

            typeInput.addEventListener('change', toggleByType);
            toggleByType();
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/events/create.blade.php ENDPATH**/ ?>