<?php
session_start();

require_once 'config.php';

$name = $_POST['name'];
$pass = $_POST['pass'];

$sql = "SELECT * FROM user WHERE name='$name' AND password='$pass'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    //print_r($result->fetch_assoc());
    $row = $result->fetch_assoc();
    //print_r($row['name']);
    $_SESSION['name'] = $row['name'];
    $_SESSION['id'] = $row['id'];

    header('location: index.php');


} else {

    header('location: login/index.php');

    echo "0 results";
}
$conn->close();

