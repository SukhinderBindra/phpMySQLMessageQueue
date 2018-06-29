<?php

    //
    // Author  : Sukhinder Bindra
    // Purpose : Driver for message Queue
    //           One or more instances of this program are run in deamon mode.
    //           Each instance is passed a unique "Player ID" as input.
    //           Player ID range from 1 to N where is is the number of records 
    //           in message_queue_players table
    // Date    : 6/19/2018

    if ($argc < 2 ) {
        exit( "Error:  Invalid Player ID" );
    }

    $pid      = getmypid();
    $playerID = $argv[1];

    echo "Player ID = $playerID\n";
    echo "PID       = $pid\n";

    ini_set('max_execution_time', 600);

    include 'private/config.php';
    include 'private/publish_methods.php';


    $conn = dbConnectionStart();


    if ( !isValidPlayerId($playerID) ) {
        exit( "Error:  Invalid Player ID " . $playerID );
    }

    registerPlayer($playerID, $pid);

    $playerCount = getPlayerCount();

    $playerMod = ( $playerID % $playerCount );


    while ( true ) {

        $messageId = queueConsumeGetNextMessageId( $playerID, $playerMod);

        if ( $messageId != 0 )  {

            $request = queueConsumeGetNextRequest($messageId);

            $response = handleRequest($request);

            $status = 'F';
            if ( isset($response->{"status"}) && $response->{"status"} == "success" )  {
                $status = 'S';
            }

            queueConsumeSetResponse($messageId, $status, json_encode($response));
     
        } else {
            usleep(500000); // Sleep Half a Second
        }

    }

?>
