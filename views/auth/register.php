<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
<main>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Register</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($_SESSION['errors'] as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php unset($_SESSION['errors']); ?>
                        <?php endif; ?>
                        <form action="/event-management/register" method="POST" onsubmit="return validateForm(event)">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                                <div id="nameError" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                                <div id="emailError" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                <div id="passwordError" class="text-danger"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                <div id="passwordConfirmationError" class="text-danger"></div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block">Register</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p class="mb-0">Already have an account? <a href="/event-management/login" class="text-primary">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="assets/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("name").addEventListener("blur", validateName);
        document.getElementById("email").addEventListener("blur", validateEmail);
        document.getElementById("password").addEventListener("blur", validatePassword);
        document.getElementById("password_confirmation").addEventListener("blur", validatePasswordConfirmation);
    });

    function validateName() {
        const name = document.getElementById("name").value;
        const nameError = document.getElementById("nameError");
        const nameRegex = /^[a-zA-Z\s-]+$/;

        if(name === ''){
            nameError.textContent = "Name required";
        }
        else if (!nameRegex.test(name)) {
            nameError.textContent = "Invalid name format";
        } else {
            nameError.textContent = "";
        }
    }

    function validateEmail() {
        const email = document.getElementById("email").value;
        const emailError = document.getElementById("emailError");
        const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

        if (!emailRegex.test(email)) {
            emailError.textContent = "Please enter a valid email.";
        } else {
            emailError.textContent = "";
        }
    }

    function validatePassword() {
        const password = document.getElementById("password").value;
        const passwordError = document.getElementById("passwordError");

        if (password.length < 6) {
            passwordError.textContent = "Password must be at least 6 characters.";
        } else {
            passwordError.textContent = "";
        }
    }

    function validatePasswordConfirmation() {
        const password = document.getElementById("password").value;
        const passwordConfirmation = document.getElementById("password_confirmation").value;
        const passwordConfirmationError = document.getElementById("passwordConfirmationError");

        if (password !== passwordConfirmation) {
            passwordConfirmationError.textContent = "Passwords do not match.";
        } else {
            passwordConfirmationError.textContent = "";
        }
    }

    function validateForm(event) {
        validateName();
        validateEmail();
        validatePassword();
        validatePasswordConfirmation();

        if (document.querySelector(".text-danger").textContent !== "") {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>


</body>
</html>
