<?php

    include_once "../common/game.php";
    include_once "../common/response.php";

    define("STRATEGY", "strategy");
    $knownStrategies = ["Smart", "Random"];

    if (!array_key_exists(STRATEGY, $_GET)) { # strategy parameter not specified in GET call
        toJson(Response::reason("Strategy not specified"));
        exit;
    } else {
        $strategy = $_GET[STRATEGY];

        if (in_array($strategy, $knownStrategies)) {
            newGame($strategy);
        } else { # strategy specified is not a known strategy
            toJson(Response::reason("Unknown strategy"));
            exit;
        }
    }

    function newGame($strategy) {
        $pid = uniqid();
        $game = new Game($strategy);
        $game->storeGame($pid, $game);
        toJson(Response::pid($pid));
    }

    function toJson($response) {
        echo json_encode($response);
    }
