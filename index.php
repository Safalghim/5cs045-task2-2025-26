<?php
include 'db.php';

$genreFilter = "";
if (isset($_GET['genre']) && $_GET['genre'] !== "") {
    $genreFilter = $_GET['genre'];
    $stmt = $mysqli->prepare("SELECT * FROM movies WHERE genre = ? ORDER BY movie_name");
    $stmt->bind_param("s", $genreFilter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $mysqli->query("SELECT * FROM movies ORDER BY movie_name");
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>MovieDB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navigation.html'; ?>

<div class="container">
<h2>All Movies</h2>

<!-- GENRE FILTER -->
<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <select name="genre" class="form-select">
                <option value="">Filter by Genre</option>
                <?php
                $genres = $mysqli->query("SELECT DISTINCT genre FROM movies ORDER BY genre");
                while ($g = $genres->fetch_assoc()) {
                    $selected = ($genreFilter == $g['genre']) ? "selected" : "";
                    echo "<option $selected>" . htmlspecialchars($g['genre']) . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Apply</button>
        </div>
    </div>
</form>

<table class="table table-bordered">
<tr>
<th>Movie Name</th><th>Movie Description</th><th>Genre</th><th>£Price</th><th>Date of Release</th><th>Actions</th>
</tr>

<?php
while($movie = $result->fetch_assoc()) {
    echo "<tr>
        <td>".htmlspecialchars($movie['movie_name'])."</td>
        <td>".htmlspecialchars($movie['movie_description'])."</td>
        <td>".htmlspecialchars($movie['genre'])."</td>
        <td>".htmlspecialchars($movie['price'])."</td>
        <td>".htmlspecialchars($movie['date_of_release'])."</td>
        <td>
            <a href='view.php?id={$movie['movie_id']}' class='btn btn-info btn-sm'>View</a>
            <a href='edit.php?id={$movie['movie_id']}' class='btn btn-warning btn-sm'>Edit</a>
            <a href='delete.php?id={$movie['movie_id']}' class='btn btn-danger btn-sm'>Delete</a>
        </td>
    </tr>";
}
?>
</table>
</div>
</body>
</html>
