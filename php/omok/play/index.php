<?php

    include_once "../common/response.php";

    define("PID", "pid"); # constants
    define("MOVE", "move");

    if (!array_key_exists(PID, $_GET)) { # pid not specified in GET call
        toJson(Response::reason("Pid is not specified"));
        exit;
    } else {
        $pid = $_GET[PID];
        $file = "../writable/games/$pid.txt";

        if (!file_exists($file)) {
            toJson(Response::reason("Unknown pid"));
            exit;
        }
    }

    if (!array_key_exists(MOVE, $_GET)) { # move not specified in GET call
        toJson(Response::reason("Move not specified"));
        exit;
    } else {
        $requestedMove = $_GET[MOVE];
        $move_arr = explode(",", $requestedMove); # split by comma

        # array of tokens should be two for x,y
        if (count($move_arr) != 2) {
            toJson(Response::reason("Move not well-formed"));
            exit;
        }
        # array should only contain numeric values
        if (!checkIfNumeric($move_arr)) {
            toJson(Response::reason("Move not well-formed"));
            exit;
        }
    }

    # check if the values of the move tokens array are both numeric
    function checkIfNumeric($array) {
        foreach($array as $value) {
            if (!is_numeric($value)) {
                return false;
            } 
        }
        return true;
   }

    function toJson($response) {
        echo json_encode($response);
    }

?>