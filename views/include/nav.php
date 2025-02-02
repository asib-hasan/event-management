<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/event-management">Event Management</a>
        <div class="ms-auto">
            <?php if ($this->checkLoginStatus()): ?>
                <a href="logout" class="btn btn-outline-light me-2">Log Out</a>
                <a href="my-events" class="btn btn-light">My Events</a>
            <?php else: ?>
                <a href="login" class="btn btn-outline-light me-2">Log In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>