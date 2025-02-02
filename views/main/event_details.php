<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Details</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<?php include 'views/include/nav.php' ?>

<div class="container card mt-4 rounded-1 shadow-sm">
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
                <button class="nav-link active">Event Details</button>
            </li>
        </ul>
        <div class="tab-content" id="eventTabsContent">
            <div class="tab-pane fade show active" id="details" role="tabpanel">
                <table class="table table-bordered mt-3">
                    <tr>
                        <th colspan="2" style="background: #ddd">Event Information</th>
                    </tr>
                    <tr>
                        <td>Event Title</td>
                        <td><?php echo htmlspecialchars($event_info['title']); ?></td>
                    </tr>
                    <tr>
                        <td>Event Date</td>
                        <td><?php echo date('F j, Y', strtotime($event_info['event_date'])); ?></td>
                    </tr>
                    <tr>
                        <td>Event Time</td>
                        <td><?php echo date('g:i A', strtotime($event_info['event_time'])); ?></td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td><?php echo htmlspecialchars($event_info['location']); ?></td>
                    </tr>
                    <tr>
                        <td>Slots Available</td>
                        <td><?php echo htmlspecialchars($event_info['total_slot']); ?></td>
                    </tr>
                    <tr>
                        <td>Registration Deadline</td>
                        <td><?php echo date('F j, Y', strtotime($event_info['reg_last_date'])); ?></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><?php echo htmlspecialchars($event_info['description']); ?></td>
                    </tr>
                </table>
                <form action="download-attendees" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $encryptedId; ?>">
                    <button type="submit" class="btn btn-success">Download CSV</button>
                </form>
                <table class="table table-bordered table-hover mt-3">
                    <thead>
                    <tr>
                        <th colspan="6" style="background: #ddd">Attendees List</th>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Age</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($attendees_list)) :
                        foreach ($attendees_list as $attendee) : ?>
                            <tr>
                                <td><?php echo $attendee['id']; ?></td>
                                <td><?php echo htmlspecialchars($attendee['name']); ?></td>
                                <td><?php echo htmlspecialchars($attendee['email']); ?></td>
                                <td><?php echo htmlspecialchars($attendee['phone']); ?></td>
                                <td><?php echo htmlspecialchars($attendee['gender']); ?></td>
                                <td><?php echo htmlspecialchars($attendee['age']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center text-danger">No attendees registered yet.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
