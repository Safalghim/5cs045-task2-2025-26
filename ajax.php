<?php
include 'db.php';
$result = $mysqli->query("SELECT * FROM movies ORDER BY movie_name");
$movies = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($movies);
?>
