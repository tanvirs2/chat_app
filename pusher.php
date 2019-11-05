<?php

require_once 'config.php';
//echo $_POST['name'];

require __DIR__ . '/vendor/autoload.php';

$pusher = new Pusher\Pusher("54b390630895bf04e224", "91877b641a0a5d06e5d8", "889009", array('cluster' => 'ap2'));

$msg = isset($_POST['name']) ? $_POST['name'] : '';

$pusher->trigger('my-channel', 'my-event', array('name' => $_SESSION['name'],'message' => $msg));



//puser_chat

$time = date('h:i a', time());

$sql = "INSERT INTO chat (user_id, msg, time)
VALUES ('".$_SESSION['id']."', '$msg', '$time')";

if ($conn->query($sql) === TRUE) {
    //echo "New record created successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();



