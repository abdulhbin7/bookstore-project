<?php

require_once 'config/database.php';

// Destroy session
session_destroy();

// Redirect to login
header('Location: index.php');
exit;
?>
