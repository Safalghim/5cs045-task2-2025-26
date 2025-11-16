<?php
include 'db.php';

$movie_name = trim($_GET['movie_name'] ?? '');
$movie_description = trim($_GET['movie_description'] ?? '');
$genre = trim($_GET['genre'] ?? '');
$price = $_GET['price'] ?? '';
$date_of_release = $_GET['date_of_release'] ?? '';

$sql = "SELECT * FROM movies WHERE 1=1";
$params = [];
$types = "";

if ($movie_name) { $sql .= " AND movie_name LIKE ?"; $params[] = "%$movie_name%"; $types .= "s"; }
if ($movie_description) { $sql .= " AND movie_description LIKE ?"; $params[] = "%$movie_description%"; $types .= "s"; }
if ($genre) { $sql .= " AND genre LIKE ?"; $params[] = "%$genre%"; $types .= "s"; }
if ($price !== '') { $sql .= " AND price = ?"; $params[] = $price; $types .= "d"; }
if ($date_of_release) { $sql .= " AND date_of_release = ?"; $params[] = $date_of_release; $types .= "s"; }

$sql .= " ORDER BY movie_name ASC";

$stmt = $mysqli->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$results = $result->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Search Movies</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navigation.html'; ?>
<div class="container">
<h2>Search Movies</h2>
<form method="GET" class="mb-3">
<div class="row">
<div class="col"><input type="text" name="movie_name" placeholder="Movie Name" class="form-control" value="<?=htmlspecialchars($movie_name)?>"></div>
<div class="col"><input type="text" name="movie_description" placeholder="Movie description" class="form-control" value="<?=htmlspecialchars($movie_description)?>"></div>
<div class="col"><input type="text" name="genre" placeholder="Genre" class="form-control" value="<?=htmlspecialchars($genre)?>"></div>
<div class="col"><input type="number" step="0.01" name="price" placeholder="Price" class="form-control" value="<?=htmlspecialchars($price)?>"></div>
<div class="col"><input type="date" name="date_of_release" class="form-control" value="<?=htmlspecialchars($date_of_release)?>"></div>
<div class="col"><button class="btn btn-primary" type="submit">Search</button></div>
</div>
</form>

<?php if($results): ?>
<table class="table table-bordered">
<tr><th>Movie Name</th><th>Movie description</th><th>Genre</th><th>Price</th><th>Date of Release</th></tr>
<?php foreach($results as $movie): ?>
<tr>
<td><?=htmlspecialchars($movie['movie_name'])?></td>
<td><?=htmlspecialchars($movie['movie_description'])?></td>
<td><?=htmlspecialchars($movie['genre'])?></td>
<td><?=htmlspecialchars($movie['price'])?></td>
<td><?=htmlspecialchars($movie['date_of_release'])?></td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>No movies found.</p>
<?php endif; ?>
</div>
</body>
</html>
