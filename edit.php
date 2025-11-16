<?php
include 'db.php';

// Get movie ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch existing movie
$stmt = $mysqli->prepare("SELECT * FROM movies WHERE movie_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    exit("Movie not found!");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $movie_name = trim($_POST['movie_name'] ?? '');
    $movie_description = trim($_POST['movie_description'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $price = $_POST['price'] ?? 0;
    $date_of_release = $_POST['date_of_release'] ?? '';

    if ($movie_name !== '') {

        $stmt = $mysqli->prepare(
            "UPDATE movies 
             SET movie_name=?, movie_description=?, genre=?, price=?, date_of_release=? 
             WHERE movie_id=?"
        );

        // FINAL FIX — correct variables & type string
        $stmt->bind_param(
            "sssdsi",
            $movie_name,
            $movie_description,
            $genre,
            $price,
            $date_of_release,
            $id
        );

        $stmt->execute();

        header("Location: index.php");
        exit;

    } else {
        $error = "Movie Name is required!";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Movie</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navigation.html'; ?>
<div class="container mt-4">
<h2>Edit Movie</h2>

<?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST">
<div class="mb-3">
    <label>Movie Name</label>
    <input type="text" name="movie_name" class="form-control" value="<?=htmlspecialchars($movie['movie_name'])?>" required>
</div>

<div class="mb-3">
    <label>Description</label>
    <textarea name="movie_description" class="form-control" rows="3"><?=htmlspecialchars($movie['movie_description'])?></textarea>
</div>

<div class="mb-3">
    <label>Genre</label>
    <input type="text" name="genre" class="form-control" value="<?=htmlspecialchars($movie['genre'])?>">
</div>

<div class="mb-3">
    <label>Price</label>
    <input type="number" step="0.01" name="price" class="form-control" value="<?=htmlspecialchars($movie['price'])?>">
</div>

<div class="mb-3">
    <label>Date of Release</label>
    <input type="date" name="date_of_release" class="form-control" value="<?=htmlspecialchars($movie['date_of_release'])?>">
</div>

<button type="submit" class="btn btn-primary">Update Movie</button>
<a href="index.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</body>
</html>
