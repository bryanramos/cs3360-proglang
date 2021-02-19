<?php

    class Game {
        public $response;
        public $reason;
        public $pid;
    }

    $game = new Game();

    define("STRATEGY", 'strategy');
    $strategies = ["Smart", "Random"];

    if (!array_key_exists(STRATEGY, $_GET)) { # didn't specify strategy in GET call
        $game->response = false;
        $game->reason = "Strategy not specified";

        $gameArr = array(
            "response" => $game->response,
            "reason" => $game->reason
        );

        echo json_encode($gameArr);
        exit;

    } else {
        $strategy = $_GET[STRATEGY];

        if (in_array($strategy, $strategies)) { # known strategies
            $game->response = true;
            $game->pid = uniqid();

            $gameArr = array(
                "response" => $game->response,
                "pid" => $game->pid
            );

            echo json_encode($gameArr);
            exit;

        } else {
            $game->response = false;
            $game->reason = "Unknown strategy";

            $gameArr = array(
                "response" => $game->response,
                "reason" => $game->reason
            );

            echo json_encode($gameArr);
            exit;
        }
    }
?>