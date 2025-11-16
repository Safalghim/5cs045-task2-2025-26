<?php
include 'db.php';
$id = (int)$_GET['id'];

$stmt = $mysqli->prepare("SELECT * FROM movies WHERE movie_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();
if (!$movie) exit("Movie not found!");

if (isset($_POST['confirm'])) {
    $stmt = $mysqli->prepare("DELETE FROM movies WHERE movie_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Delete Movie</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navigation.html'; ?>
<div class="container">
<h2>Delete Movie</h2>
<p>Are you sure you want to delete "<strong><?=htmlspecialchars($movie['movie_name'])?></strong>"?</p>
<form method="POST">
<button type="submit" name="confirm" class="btn btn-danger">Yes, Delete</button>
<a href="index.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</body>
</html>
