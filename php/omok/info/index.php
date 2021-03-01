<?php

    # Coded By Bryan Ramos

    include_once "../play/common.php";
    include_once "../play/game.php";

    $gameInfo = new GameInfo(Game::$boardSize, Game::$knownStrategies);

    if (empty($_SERVER["REQUEST_METHOD"])) {
        json_encode("Uri not found");
    } else {
        toJson($gameInfo);
    }

    class GameInfo {
        public $size;
        public $strategies;

        public function __construct($size, $strategies) {
            $this->size = $size;
            $this->strategies = $strategies;
        }
    }
