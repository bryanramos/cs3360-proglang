<?php

    include_once "../play/game.php";
    include_once "../play/response.php";
    include_once "../play/common.php";

    define("STRATEGY", "strategy");
    $knownStrategies = ["Smart", "Random"];

    if (!array_key_exists(STRATEGY, $_GET)) { # strategy parameter not specified in GET call
        toJson(Response::reason("Strategy not specified"));
        exit;
    } else {
        $strategy = $_GET[STRATEGY];

        # strategy parameter value must match a known strategy
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
        storeGame($pid, $game);
        toJson(Response::pid($pid));
    }

    # encode to json
    function toJson($response) { 
        echo json_encode($response);
    }