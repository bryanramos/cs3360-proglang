<?php

    # Coded By Bryan Ramos

    include_once "common.php";
    include_once "game.php";
    include_once "response.php";
    include_once "strategy.php";

    # constants
    define("PID", "pid"); 
    define("MOVE", "move");

    if (!array_key_exists(PID, $_GET)) { # pid not specified in GET call
        toJson(Response::reason("Pid is not specified"));
        exit;
    } else {
        $pid = $_GET[PID];
        $file = "../writable/games/$pid.txt";

        # check if a file with the pid specified exists
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
        $move_arr = explode(",", $requestedMove); # split by comma from x,y to 

        # array of tokens should be of length two for x,y
        if (count($move_arr) != 2) {
            toJson(Response::reason("Move not well-formed"));
            exit;
        }
        # array should only contain numeric values - no letters for example
        if (!checkIfNumeric($move_arr)) {
            toJson(Response::reason("Move not well-formed"));
            exit;
        }
        # check for invalid x coordinate
        if ($move_arr[0] < 0 || $move_arr[0] >= Game::$boardSize) {
            toJson(Response::reason("Invalid x coordinate, " . $move_arr[0]));
            exit;
        }
        # check for invalid y coordinate
        if ($move_arr[1] < 0 || $move_arr[1] >= Game::$boardSize) {
            toJson(Response::reason("Invalid y coordinate, " . $move_arr[1]));
            exit;
        }
    }

    makeMove($pid, $move_arr);
    
    function makeMove($pid, $move) {
        $game = Game::getGame($pid);

        if (Game::isFull($game->board)) {
            toJson(Response::reason("Board is full"));
            exit;
        }

        # check if the slot is non-empty
        # if its already filled, return a false response
        if (!empty($game->board[$move[0]][$move[1]])) {
            toJson(Response::reason("Already placed"));
            exit;
        }

        $acknowledgeMove = $game->completeMove(true, $move);

        if ($acknowledgeMove->isWin || $acknowledgeMove->isDraw) {
            toJson(Response::move($acknowledgeMove));
        } else {

            for (;;) {
                if ($game->strategy === "Random") {
                    $move = RandomStrategy::pickSlot($game->board);
                } else {
                    $move = SmartStrategy::pickSlot(false, $game->board, $move);
                }
    
                if (empty($game->board[$move[0]][$move[1]])) {
                    break;
                }
            }
            

            $current_move = $game->completeMove(false, $move);
            toJson(Response::moves($acknowledgeMove, $current_move));
        }

        # save game again
        storeGame($pid, $game);
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

?>