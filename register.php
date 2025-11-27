<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Only check username
    $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $error = "Username already exists!";
    } else {
        // Provide dummy email to satisfy NOT NULL in DB
        $dummy_email = $username . "@example.com";

        $stmt = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $dummy_email);
        $stmt->execute();

        // Redirect to login page
        header("Location: login.php");
        exit;
    }
}
?>
<!doctype html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navigation.html'; ?>

<div class="container mt-4">
<h2>Register</h2>

<?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST">

<label class="form-label">Username</label>
<input type="text" name="username" class="form-control" required>

<label class="form-label mt-2">Password</label>
<input type="password" name="password" class="form-control" required>

<button class="btn btn-primary mt-3">Register</button>

</form>

</div>
</body>
</html>
