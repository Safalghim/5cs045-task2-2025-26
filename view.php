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
            <!-- Image placeholder; will be updated by JS -->
            <img id="gameCover" src="https://via.placeholder.com/300x400?text=Loading..." class="img-fluid img-thumbnail" alt="Movie Image">
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const img = document.getElementById('gameCover');
    const title = encodeURIComponent("<?= addslashes($movie['movie_name']); ?>");

    // 1️⃣ Try OMDb API first (free demo key)
    fetch(`https://www.omdbapi.com/?t=${title}&apikey=thewdb`)
    .then(res => res.json())
    .then(data => {
        if (data.Poster && data.Poster !== "N/A") {
            img.src = data.Poster;
        } else {
            // 2️⃣ Fallback to Google Books API
            return fetch(`https://www.googleapis.com/books/v1/volumes?q=intitle:${title}`)
                .then(res => res.json())
                .then(bookData => {
                    if (bookData.items && bookData.items[0]?.volumeInfo?.imageLinks?.thumbnail) {
                        img.src = bookData.items[0].volumeInfo.imageLinks.thumbnail.replace(/^http:\/\//i, 'https://');
                    } else {
                        img.src = 'https://via.placeholder.com/300x400?text=No+Image';
                    }
                });
        }
    })
    .catch(() => {
        img.src = 'https://via.placeholder.com/300x400?text=No+Image';
    });
});
</script>

</body>
</html>
