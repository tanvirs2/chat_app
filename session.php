<?php
session_start();

$_SESSION['name'] = null;

header('location: login/index.php');

