<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Events</title>
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
                <button class="nav-link active">List View</button>
            </li>
            <li class="nav-item">
                <a href="create-event" class="text-decoration-none">
                    <button class="nav-link">Create Event</button>
                </a>
            </li>
        </ul>
        <div class="tab-content" id="eventTabsContent">
            <div class="tab-pane fade show active" id="list" role="tabpanel">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th style="background: #ddd">ID</th>
                            <th style="background: #ddd">Title</th>
                            <th style="background: #ddd">Date</th>
                            <th style="background: #ddd">Slots Available</th>
                            <th style="background: #ddd">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $index = ($page - 1) * $perPage + 1;
                        foreach ($managedEvents as $event): ?>
                            <tr>
                                <td><?php echo $index++; ?></td>
                                <td><?php echo htmlspecialchars($event['title']); ?></td>
                                <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                                <td><?php echo htmlspecialchars($event['total_slot']); ?></td>
                                <td>
                                    <?php $encryptedId = ($this->encrypt_decrypt('encrypt', $event['id'])); ?>
                                    <a href="edit-event?id=<?php echo $encryptedId; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="event-details?id=<?php echo $encryptedId; ?>" class="btn btn-info btn-sm">Details</a>
                                    <a href="delete-event?id=<?php echo $encryptedId; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($managedEvents)): ?>
                            <tr>
                                <td colspan="5" class="text-danger">No records</td>
                            </tr>
                        <?php else: ?>
                        </tbody>

                    </table>
                    <ul class="pagination float-end">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</html>
