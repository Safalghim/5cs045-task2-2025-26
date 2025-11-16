<?php
include 'db.php';
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
<table class="table table-bordered">
<tr>
<th>Movie Name</th><th>Movie Description</th><th>Genre</th><th>£Price</th><th>Date of Release</th><th>Actions</th>
</tr>
<?php
$result = $mysqli->query("SELECT * FROM movies ORDER BY movie_name");
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
