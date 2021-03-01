<?php

    # Coded By Bryan Ramos

    include_once "../play/common.php";
    include_once "../play/game.php";
    include_once "../play/response.php";

    define("STRATEGY", "strategy");

    if (!array_key_exists(STRATEGY, $_GET)) { # strategy parameter was not specified in the GET call
        toJson(Response::reason("Strategy not specified"));
        exit;
    } else {
        $strategy = $_GET[STRATEGY];

        if (in_array($strategy, Game::$knownStrategies)) { # strategy parameter value must match known strategy
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