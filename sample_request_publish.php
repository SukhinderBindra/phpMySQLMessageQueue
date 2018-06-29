<?php

    include 'private/config.php';
    include 'private/publish_methods.php';

    $request = '{ "sample" : "message", "to" : "be", "added" : 2, "the" : "queue" }';

    $response = publishRequestWait($request );

    echo "$response\n";

?>
