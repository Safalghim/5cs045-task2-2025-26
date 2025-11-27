<?php
session_start();
include 'db.php';

$error = "";

// Generate captcha only on first load (GET request)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $A = rand(1, 10);
    $B = rand(1, 10);
    $_SESSION['captcha_answer'] = $A + $B;
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $captcha_input = $_POST['captcha'] ?? '';

    // Check captcha
    if ($captcha_input != $_SESSION['captcha_answer']) {
        $error = "Incorrect CAPTCHA!";
    } else {
        // Verify username and password
        $stmt = $mysqli->prepare("SELECT user_id, password FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $username;

            // Redirect to index.php after login
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    }

    // Regenerate captcha after POST, regardless of success, to prevent reuse
    $A = rand(1, 10);
    $B = rand(1, 10);
    $_SESSION['captcha_answer'] = $A + $B;
}

?>
<!doctype html>
<html>
<head>
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navigation.html'; ?>

<div class="container mt-4">
<h2>Login</h2>

<?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST">

<label class="form-label">Username</label>
<input type="text" name="username" class="form-control" required>

<label class="form-label mt-2">Password</label>
<input type="password" name="password" class="form-control" required>

<label class="form-label mt-2">What is <?php echo $A . " + " . $B; ?> ?</label>
<input type="text" name="captcha" class="form-control" required>

<button class="btn btn-primary mt-3">Login</button>

</form>

</div>
</body>
</html>
