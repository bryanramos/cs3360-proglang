<?php

    $strategies = array("Smart" => "SmartStrategy", "Random" => "RandomStrategy");
    $size = 15;

    $info = new GameInfo($size, array_keys($strategies));
    echo json_encode($info);

    class GameInfo {
        public $size;
        public $strategies;

        function __construct($size, $strategies) {
            $this->size = $size;
            $this->strategies = $strategies;
        }
    }

?>
