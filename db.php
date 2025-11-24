<?php
$mysqli = new mysqli("localhost","2406376","lafas#123456789","db2406376");
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();
}
?>