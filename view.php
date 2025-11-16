<?php
include 'db.php';

// Get movie ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $mysqli->prepare("SELECT * FROM movies WHERE movie_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    exit("Movie not found!");
}

// Optional image field (if you create one later)
$image = $movie['image'] ?? 'default.jpg';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>View Movie</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navigation.html'; ?>

<div class="container mt-4">
    <h2><?= htmlspecialchars($movie['movie_name']) ?></h2>

    <div class="row">
        <div class="col-md-4">
            <img src="uploads/<?= htmlspecialchars($image) ?>" class="img-fluid img-thumbnail" alt="Movie Image"
                 onerror="this.src='https://via.placeholder.com/300x400?text=No+Image';">
        </div>

        <div class="col-md-8">
            <table class="table table-striped">
                <tr><th>Movie Name</th><td><?= htmlspecialchars($movie['movie_name']) ?></td></tr>
                <tr><th>Description</th><td><?= htmlspecialchars($movie['movie_description']) ?></td></tr>
                <tr><th>Genre</th><td><?= htmlspecialchars($movie['genre']) ?></td></tr>
                <tr><th>Price</th><td>£<?= htmlspecialchars($movie['price']) ?></td></tr>
                <tr><th>Date of Release</th><td><?= htmlspecialchars($movie['date_of_release']) ?></td></tr>
            </table>

            <a href="edit.php?id=<?= $movie['movie_id'] ?>" class="btn btn-warning">Edit</a>
            <a href="delete.php?id=<?= $movie['movie_id'] ?>" class="btn btn-danger">Delete</a>
            <a href="index.php" class="btn btn-secondary">Back</a>

            <hr>

            <h4>Extra Tools</h4>
            <a href="bootstrap-ajax-modal.html" class="btn btn-primary">Open Movies Modal</a>
            <a href="bootstrap-ajax-dropdown.html" class="btn btn-info">Open Movies Dropdown</a>
        </div>
    </div>
</div>

</body>
</html>
