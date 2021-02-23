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

    function getGame($pid) {
        $path = "../writable/games/$pid.txt"; # path where game metadata will be stored

        $file = fopen($path, 'rb') or die("Cannot open game file: " . $path);
        $contents = fread($file, filesize($file));
        fclose($file);

        $saved = json_decode($contents);

        $instance = new self($saved->strategy);
        $instance->board = json_decode(json_decode($saved->board), true);
        return $instance; # return game
    }

    function makeMove($pid, $move) {
        $game = Game::getGame($pid);
        $acknowledgeMove = $game->completeMove(true, $move);
    }

    function completeMove($player, $move) {
        $this->board[$move[0]][$move[1]] = ($player) ? 1 : 2; # check if player or computer

        $result = $this->checkForWinningMove($move);
    }

    function checkForWinningMove($move) {
        $row = array();

        $currentMove = $this->board[$move[0]][$move[1]];
        $startIndexHorizontal = ($move[0]<4) ? 0 : $move[0]-4;
        $endIndexHorizontal = ($move[0]>10) ? 14 : $move[0]+4;
        $startIndexVertical = ($move[1]<4) ? 0 : $move[1]-4;
        $endIndexVertical = ($move[1]>10) ? 14 : $move[1]-4;

        $countHorizontal = $countVertical = 0;
        $count1 = $count2 = $count3 = $count4 = 0;

        # check for winning move in the horizontal direction
        for ($i = $startIndexHorizontal; $i <= $endIndexHorizontal; $i++) {

            if ($this->board[$i][$move[1]] == $currentMove) {
                $countHorizontal++;
                $row[] = $i;
                $row[] = $move[1];
                if ($countHorizontal == 5) {
                    return $row;
                }
            } else {
                $countHorizontal = 0;
            }
            
        }

        $row = array();

        # check for winning move in the vertical direction
        for ($i = $startIndexVertical; $i <= $endIndexVertical; $i++) {

            if ($this->board[$move[0]][$i] == $currentMove) {
                $countVertical++;
                $row[] = $i;
                $row[] = $move[0];
                if ($countVertical == 5) {
                    return $row;
                }
            } else {
                $countVertical = 0;
            }

        }

        $row = array();

        # check for winning move in both diagonal directions
        for ($x = 4; $x < $this->size; $x++) {
            for ($i = 0; $i <= $x; $i++) {

                if ($this->board[$x-$i][$i] == $move) {
                    $count1++;
                    $row[] = $x-$i;
					$row[] = $i;
					if($count1 == 5){
						return $row;
					}
                } else {
                    $count1 = 0;
                }
            }
        }
    }
}

?>