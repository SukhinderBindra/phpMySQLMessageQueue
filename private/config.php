<?php
    $server = 'localhost';
    $user   = '<<DB_USER_NAME_USER>>';
    $pass   = '<<DB_PASSWORD_WHERE>>';
    $db     = '<<DATABASE_NAME_HERE>>';

    $conn = new mysqli($server, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
