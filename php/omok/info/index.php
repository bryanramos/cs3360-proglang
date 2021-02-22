<?php

    $size = 15;
    $strategies = ["Smart", "Random"];

    $gameInfo = new GameInfo($size, $strategies);
    if (empty($_SERVER["REQUEST_METHOD"])) {
        json_encode("Uri not found");
    } else {
        $gameInfo->toJson();
    }

     class GameInfo {
         public $size;
         public $strategies;

         public function __construct($size, $strategies) {
             $this->size = $size;
             $this->strategies = $strategies;
         }

         public function toJson() { # convert object to json
             echo json_encode($this);
         }
     }
