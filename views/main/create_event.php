<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage My Events</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"></head>
<body>
<?php include 'views/include/nav.php' ?>
<div class="container card mt-4 mb-4 rounded-1 shadow-sm">
    <div class="col-md-12 mt-3">
        <?php include 'views/include/alerts.php' ?>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="my-events" class="text-decoration-none">
                    <button class="nav-link">List View</button>
                </a>
            </li>
            <li class="nav-item">
                <button class="nav-link active">Create Event</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" role="tabpanel">
                <form action="create-event" class="form-prevent" method="POST">
                    <div class="mt-3 mb-3">
                        <label class="form-label">Event Title<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" value="<?php echo isset($_SESSION['old_input']['title']) ? $_SESSION['old_input']['title'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"><?php echo isset($_SESSION['old_input']['description']) ? $_SESSION['old_input']['description'] : ''; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="event_date" value="<?php echo isset($_SESSION['old_input']['date']) ? $_SESSION['old_input']['date'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="event_time" value="<?php echo isset($_SESSION['old_input']['time']) ? $_SESSION['old_input']['time'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="location" value="<?php echo isset($_SESSION['old_input']['location']) ? $_SESSION['old_input']['location'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Slot <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="total_slot" value="<?php echo isset($_SESSION['old_input']['slot']) ? $_SESSION['old_input']['slot'] : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="reg_last_date" value="<?php echo isset($_SESSION['old_input']['deadline_date']) ? $_SESSION['old_input']['deadline_date'] : ''; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success form-prevent-multiple-submit">Create Event</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const requiredFields = document.querySelectorAll('[required]');
        requiredFields.forEach(function (field) {
            const messageContainer = document.createElement('div');
            messageContainer.classList.add('invalid-feedback');
            field.parentNode.appendChild(messageContainer);

            field.addEventListener('blur', function () {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    messageContainer.textContent = 'This field is required.';
                } else {
                    field.classList.remove('is-invalid');
                    messageContainer.textContent = '';
                }
            });
        });
    });
</script>
<script>
    $('.form-prevent').on('submit', function () {
        $('.form-prevent-multiple-submit').attr('disabled', 'true');
        $('.form-prevent-multiple-submit').html('Processing...');
    });
</script>
</body>
</html>
