<?php

include_once "move.php";

class Game {

    public $board;
    public $strategy;

    function __construct($strategy) {
        $this->board = array(
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
            array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)
        );
        $this->strategy = $strategy;
    }

    static function getGame($pid) {
        $path = "../writable/games/$pid.txt"; # path where game data will be retrieved from

        $file = fopen($path, 'rb') or die("Cannot open game file: " . $path);
        $contents = fread($file, filesize($path));
        fclose($file);

        $saved = json_decode($contents); # deserialize

        $instance = new self($saved->strategy);
        $instance->board = json_decode(json_encode($saved->board), true);
        return $instance; # return stored game that has been deserialized
    }

    function completeMove($player, $move) {
        $this->board[$move[0]][$move[1]] = ($player) ? 1 : 2; # check if player or computer

        $result = $this->checkForWinningMove($move);

        # $x, $y, $isWin, $isDraw, $row syntax 
        if ($result == 0) { # move was neither a win or draw
            return new Move($move[0], $move[1], false, false, array());
        } else if ($result == 2) { # move resulted in a draw
            return new Move($move[0], $move[1], false, true, array());
        } else { # move resulted in a win
            return new Move($move[0], $move[1], true, false, $result);
        }
    }
 
    function checkForWinningMove($move) {
        $row = array();

		$myMove = $this->board[$move[0]][$move[1]];
		$startIndexHorizontal = ($move[0]<4)? 0 : $move[0]-4;
		$endIndexHorizontal = ($move[0]>10)? 14 : $move[0]+4;
		$startIndexVertical = ($move[1]<4)? 0 : $move[1]-4;
		$endIndexVertical = ($move[1]>10)? 14 : $move[1]+4;
		$countHorizontal = $countVertical = 0;
		$count1 = $count2 = $count3 = $count4 = 0;
		
		# check for winning move in the horizontal direction
		for ($i = $startIndexHorizontal; $i <= $endIndexHorizontal; $i++) {
			if ($this->board[$i][$move[1]] == $myMove) {
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
		# check for a winning move in the vertical direction
		for ($i = $startIndexVertical; $i <= $endIndexVertical; $i++) {
			if ($this->board[$move[0]][$i] == $myMove) {
				$countVertical++;
				$row[] = $move[0];
				$row[] = $i;
				if ($countVertical == 5) {
					return $row;
				}
			} else {
				$countVertical = 0;
			}
		}
		
		$row = array();
		# check for winning move in either diagonal direction
		for ($x = 4; $x < 15; $x++) {
			for ($i = 0; $i <= $x; $i++) {
				if ($this->board[$x-$i][$i] == $myMove) {
					$count1++;
					$row[] = $x-$i;
					$row[] = $i;
					if($count1 == 5){
						return $row;
					}
				} else {
					$count1 = 0;
				}
				if ($this->board[14-$x+$i][14-$i] == $myMove) {
					$count2++;
					$row[] = 14-$x+$i;
					$row[] = 14-$i;
					if($count2 == 5){
						return $row;
					}
				} else {
					$count2 = 0;
				}
				
				if ($this->board[14-$x+$i][$i] == $myMove) {
					$count3++;
					$row[] = 14-$x+$i;
					$row[] = $i;
					if($count3 == 5){
						return $row;
					}
				} else {
					$count3 = 0;
				}
				if ($this->board[$i][14-$x+$i] == $myMove) {
					$count4++;
					$row[] = $i;
					$row[] = 14-$x+$i;
					if ($count4 == 5) {
						return $row;
					}
				} else {
					$count4 = 0;
				}
			}
		}

		# check for a draw 
		# if there is a space that has not been placed (filled) by the player
		# or the computer, then the loop will break
		for ($i = 0; $i < 15; $i++) {
			for($j = 0; $j < 15; $j++){
				if ($this->board[$i][$j] === 0) {
					return 0;
				}
			}
		}
		
		return 2;
    }
}

?>