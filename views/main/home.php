<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Management</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">    <style>
        body {
            background-color: #f4f4f4;
        }
        .event-card {
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            padding: 20px;
            background: white;
            transition: transform 0.3s ease-in-out;
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .event-card:hover {
            transform: translateY(-5px);
        }
        .event-date {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #ff5722;
            color: white;
            padding: 7px 15px;
            border-radius: 20px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .slot-info {
            color: #28a745;
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }
        .search-filter {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php include 'views/include/nav.php' ?>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-12 search-filter">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Search events...">
                </div>
                <div class="col-md-6">
                    <select class="form-select">
                        <option value="">Filter by category</option>
                        <option value="tech">Technology</option>
                        <option value="business">Business</option>
                        <option value="education">Education</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mb-4 text-center text-primary">Upcoming Events</h2>
    <div class="row">
        <?php foreach ($event_list as $event): ?>
            <div class="col-md-4 mb-4">
                <div class="card event-card">
                    <span class="event-date"><?php echo date('F j, Y', strtotime($event['event_date'])); ?></span>
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($event['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                        <div class="slot-info">Slots Available: <?php echo $event['total_slot']; ?></div>
                        <div class="mt-2">Event Time: <?php echo date('g:i A', strtotime($event['event_time'])); ?></div>
                        <div class="mt-2">Location: <?php echo $event['location']; ?></div>
                        <div class="mt-2">Registration Deadline: <?php echo date('F j, Y', strtotime($event['reg_last_date'])); ?></div>
                        <a href="/register/<?php echo $event['id']; ?>" class="btn btn-primary w-100 mt-2">Register</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-md-12 text-center">
            <nav>
                <ul class="pagination justify-content-center">
                    <!-- Previous Button -->
                    <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" <?php echo $currentPage <= 1 ? 'tabindex="-1"' : ''; ?>>Previous</a>
                    </li>

                    <!-- Page Number Buttons -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" <?php echo $currentPage >= $totalPages ? 'tabindex="-1"' : ''; ?>>Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

</body>
<script src="assets/js/bootstrap.bundle.min.js"></script>
</html>
