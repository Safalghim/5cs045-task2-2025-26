<?php
include 'db.php';
$error='';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $movie_name = trim($_POST['movie_name'] ?? '');
    $description = trim($_POST['movie_description'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $price = $_POST['price'] ?? 0;
    $date_of_release = $_POST['date_of_release'] ?? '';

    if($movie_name !== ''){
        $stmt = $mysqli->prepare("INSERT INTO movies (movie_name, movie_description, genre, price, date_of_release) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssds",$movie_name,$description,$genre,$price,$date_of_release);
        $stmt->execute();
        header("Location: index.php");
        exit;
    } else { $error="Movie Name is required!"; }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Add Movie</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navigation.html'; ?>
<div class="container">
<h2>Add Movie</h2>
<?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
<form method="POST">
<div class="mb-3"><label>Movie Name</label><input type="text" name="movie_name" class="form-control" required></div>
<div class="mb-3"><label>Description</label><textarea name="movie_description" class="form-control"></textarea></div>
<div class="mb-3"><label>Genre</label><input type="text" name="genre" class="form-control"></div>
<div class="mb-3"><label>Price</label><input type="number" step="0.01" name="price" class="form-control"></div>
<div class="mb-3"><label>Date of Release</label><input type="date" name="date_of_release" class="form-control"></div>
<button type="submit" class="btn btn-success">Add Movie</button>
</form>
</div>
</body>
</html>
