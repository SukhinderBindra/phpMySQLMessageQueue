<?php

    // Return the status of the message in the queue
    //   P = Pending
    //   S = Success
    //   F = Fail
    //   X = Invalid Input Message ID
    function queuePublishGetStatus($messageId) {
      
        global $conn;

        $sql = "SELECT status
                FROM message_queue
                WHERE id = $messageId;";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row=$result->fetch_assoc();
            return($row['status']);
        } else { 
            return('X');
        }
    }

    function queuePublishGetResponse($messageId) {
      
        global $conn;

        $sql = "SELECT response
                FROM message_queue
                WHERE id = $messageId;";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row=$result->fetch_assoc();
            return($row['response']);
        } else {
            return('');
        }
    }

    function  queuePublishNoWait( $request ) {
      
        global $conn;
 
        $messageId = 0;

        $sql = "INSERT INTO message_queue(status, request, create_date)
                            VALUES ('P', '$request', current_timestamp);";

        if ($conn->query($sql) === TRUE) {
            $messageId =  $conn->insert_id;
        }

        if ( $messageId > 0 ) {
            $sql = "INSERT INTO message_queue_pending(id) VALUES ($messageId);";
            $conn->query($sql);
        }

        return $messageId;
    }

    function publishRequestWait($request ) {
      
        global $conn;

        $messageId = queuePublishNoWait($request );

        if ( $messageId > 0 ) {

            $status = 'P';
            while ( $status == 'P' ) {
                usleep(50000);
                $status = queuePublishGetStatus($messageId);
            }

            if ( $status != 'X' ) {
                $response = queuePublishGetResponse($messageId);
                return($response);
            }
        }
    }

?>

