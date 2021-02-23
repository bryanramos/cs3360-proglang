<?php

    include_once "../common/response.php";
    include_once "../common/game.php";
    include_once "../common/strategy.php";

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

    makeMove($pid, $move_arr);
    
    function makeMove($pid, $move) {
        $game = Game::getGame($pid);
        $acknowledgeMove = $game->completeMove(true, $move);

        if ($acknowledgeMove->isWin || $acknowledgeMove->isDraw) {
            toJson(Response::move($acknowledgeMove));
        } else {
            if ($game->strategy === "Random") {
                $move = RandomStrategy::getMove($game->board);
            }
            $currentMove = $game->completeMove(false, $move);
            toJson(Response::moves($acknowledgeMove, $currentMove));
        }

        # save game again
        storeGame($pid, $game);
    }

    function storeGame($pid, $game) {
        $path = "../writable/games/$pid.txt"; # path where game metadata will be stored

        $file = fopen($path, 'w') or die("Cannot open game file: " . $path);
        fwrite($file, json_encode($game));
        fclose($file);
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