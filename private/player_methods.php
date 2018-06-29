<?php




    function handleRequest($request) {

        //
        // This is a dummy fuction. 
        // Plug in your own login here.
        //

        $result = "Dummy Request Handler Called";

        $data=array("status"  => "success",
                    "result"  => $result
                   );

        return ($data);

    }




    function queueConsumeGetNextMessageId( $playerID, $playerMod ) {
     
        global $conn;

        $sql = "SELECT id
                FROM message_queue_pending
                WHERE mod(id, $playerID)  = $playerMod
                limit 1";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row=$result->fetch_assoc();
            return($row['id']);
        } else { 
            return('0');
        }
    }

    function queueConsumeGetNextRequest($messageId) {

        global $conn;

        $sql = "SELECT request
                FROM message_queue
                where id = $messageId ";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row=$result->fetch_assoc();
            return($row['request']);
        } else {
            return('');
        }
    }


    //
    // Status :
    //     S = Success 
    //     F = Failure 
    //
    function queueConsumeSetResponse($messageId, $status, $response) {

        global $conn;

        $sql = "update message_queue
                set status      = '$status',
                    response    = '$response',
                    update_date = current_timestamp
                WHERE id = $messageId;";
 
        $result = $conn->query($sql);

        $sql = "delete from message_queue_pending
                WHERE id = $messageId;";

        $result = $conn->query($sql);


        return(true);
    }


    function isValidPlayerId($playerID) {
        global $conn;

        $sql = "SELECT count(*) count
                FROM message_queue_players
                where player_id = $playerID ";

        $result = $conn->query($sql);

        $row=$result->fetch_assoc();
 
        $rowCount = $row["count"];

        if ( $rowCount > 0 ) {
            return(true);
        } else {
            return(false);
        }
    }

    function registerPlayer($playerID, $pid) {

        global $conn;

        $sql = "update message_queue_players
                set pid        = $pid,
                    heart_beat = current_timestamp
                WHERE player_id = $playerID;";

        $result = $conn->query($sql);

    }

    function getPlayerCount() {

        global $conn;

        $sql = "SELECT count(*) count
                FROM message_queue_players;";

        $result = $conn->query($sql);

        $row=$result->fetch_assoc();

        $rowCount = $row["count"];

        return($rowCount);
    }


?>

