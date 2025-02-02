<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Registration</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<style>
    .error-message {
        color: red;
        font-size: 0.9rem;
        margin-top: 5px;
    }
</style>
<body>
<?php include 'views/include/nav.php'; ?>
<div class="container mt-5">
    <div class="card card-custom">
        <div class="event-header text-center">
            <h3>Registration Form</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Event Details (Tabular Format) -->
                <div class="col-md-6 mt-1">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th colspan="2" class="bg-light">Event Details</th>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td><?php echo htmlspecialchars($event_info['title']); ?></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td><?php echo date('F j, Y', strtotime($event_info['event_date'])); ?></td>
                        </tr>
                        <tr>
                            <th>Time</th>
                            <td><?php echo date('g:i A', strtotime($event_info['event_time'])); ?></td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td><?php echo htmlspecialchars($event_info['location']); ?></td>
                        </tr>
                        <tr>
                            <th>Slots Available</th>
                            <td><?php echo htmlspecialchars($event_info['total_slot']); ?></td>
                        </tr>
                        <tr>
                            <th>Registration Deadline</th>
                            <td><?php echo date('F j, Y', strtotime($event_info['reg_last_date'])); ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="mt-3 text-muted"><strong>Description:</strong> <?php echo htmlspecialchars($event_info['description']); ?></p>
                </div>

                <!-- Registration Form -->
                <div class="col-md-6">
                    <form id="registrationForm">
                        <input type="hidden" name="event_id" value="<?php echo $encryptedId; ?>">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <div class="error-message" id="nameError"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control">
                            <div class="error-message" id="emailError"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                            <div class="error-message" id="phoneError"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="error-message" id="genderError"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" id="age" class="form-control">
                            <div class="error-message" id="ageError"></div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Submit Registration</button>
                        <div id="responseMessage" class="mt-3 text-center"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $("#registrationForm").on("submit", function(e) {
            e.preventDefault();
            let isValid = true;
            $(".error-message").text("");
            let name = $("#name").val().trim();
            if (name === "") {
                $("#nameError").text("Full Name is required.");
                isValid = false;
            }
            let email = $("#email").val().trim();
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === "") {
                $("#emailError").text("Email is required.");
                isValid = false;
            } else if (!emailPattern.test(email)) {
                $("#emailError").text("Enter a valid email address.");
                isValid = false;
            }

            let phone = $("#phone").val().trim();
            let phonePattern = /^[0-9]{10,15}$/;
            if (phone === "") {
                $("#phoneError").text("Phone number is required.");
                isValid = false;
            } else if (!phonePattern.test(phone)) {
                $("#phoneError").text("Enter a valid phone number.");
                isValid = false;
            }

            let gender = $("#gender").val();
            if (gender === "") {
                $("#genderError").text("Please select a gender.");
                isValid = false;
            }

            let age = $("#age").val().trim();
            if (age === "") {
                $("#ageError").text("Age is required.");
                isValid = false;
            } else if (age < 1) {
                $("#ageError").text("Enter a valid age.");
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: "register-event-update",
                    type: "POST",
                    data: $("#registrationForm").serialize(),
                    beforeSend: function() {
                        $("button[type='submit']").prop("disabled", true).text("Processing...");
                    },
                    success: function(response) {
                        let jsonResponse = JSON.parse(response);
                        if (jsonResponse.status === "error") {
                            alert(jsonResponse.message);
                        } else {
                            alert("Your event registration has been successfully updated.");
                            $("#registrationForm")[0].reset();
                            location.reload();
                        }
                    },
                    error: function() {
                        alert("Error!\nSomething went wrong. Please try again!");
                    },
                    complete: function() {
                        $("button[type='submit']").prop("disabled", false).text("Submit Registration"); // Enable submit button after completion
                    }
                });
            }
        });
    });
</script>

</body>
</html>
