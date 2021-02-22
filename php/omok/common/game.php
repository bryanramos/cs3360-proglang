<?php

class Game {

    public $board;
    public $size = 15; # 15x15 board
    public $strategy;

    function __construct($strategy) {
        $this->board = array();
        for ($i = 0; $i < $this->size; $i++) { # create 15x15 game board
            array_push($this->board, array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0));
        }
        $this->strategy = $strategy;
    }

    function storeGame($pid, $game) {
        $path = "../writable/games/$pid.txt"; # path where game metadata will be stored

        $file = fopen($path, 'w') or die("Cannot open game file: " . $path);
        fwrite($file, json_encode($game));
        fclose($file);
    }
}

?>