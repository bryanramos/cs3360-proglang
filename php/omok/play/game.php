<?php

# Coded By Bryan Ramos

include_once "move.php";

class Game {

    public $board;
    public $strategy;
	public static $boardSize = 15;
	public static $initialPlaceValue = 0;
	public static $player = 1;
	public static $computer = 2;
	public static $knownStrategies = ["Random", "Smart"];
	public static $winningCount = 5;

    function __construct($strategy) {
        $this->board = $this->createBoard(Game::$boardSize, Game::$initialPlaceValue);
        $this->strategy = $strategy;
    }

	function createBoard($size, $initialPlaceValue) {
		$generatedBoard = array();
		for ($i = 0; $i < $size; $i++) {
			array_push($generatedBoard, array_fill(0, $size, $initialPlaceValue)); 
		}
		return $generatedBoard;
	}

	static function isFull($board) {
		$isFull = true;
	
		for ($x = 0; $x < Game::$boardSize; $x++) {
			for ($y = 0; $y < Game::$boardSize; $y++) {
				if (empty($board[$x][$y])) {
					$isFull = false;
				}
			}
		}
		
		return $isFull;
	}

	# return stored game that has been deserialized
    static function getGame($pid) {
        $gamePath = "../writable/games/$pid.txt"; 

        $file = fopen($gamePath, 'rb') or die("Cannot open game file: " . $gamePath);
        $contents = fread($file, filesize($gamePath));
        fclose($file);

        $game = json_decode($contents); # deserialize

        $instance = new self($game->strategy);
        $instance->board = json_decode(json_encode($game->board), true);
        return $instance;
    }

    function completeMove($player, $move) {
        $this->board[$move[0]][$move[1]] = ($player) ? Game::$player : Game::$computer; # check if player or computer

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
		$endIndexHorizontal = ($move[0]>10)? (Game::$boardSize - 1) : $move[0]+4;
		$startIndexVertical = ($move[1]<4)? 0 : $move[1]-4;
		$endIndexVertical = ($move[1]>10)? (Game::$boardSize - 1) : $move[1]+4;
		$countHorizontal = $countVertical = 0;
		$count1 = $count2 = $count3 = $count4 = 0;
		
		# check for winning move in the horizontal direction
		for ($i = $startIndexHorizontal; $i <= $endIndexHorizontal; $i++) {
			if ($this->board[$i][$move[1]] == $myMove) {
				$countHorizontal++;
				$row[] = $i;
				$row[] = $move[1];
				if ($countHorizontal == Game::$winningCount) {
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
				if ($countVertical == Game::$winningCount) {
					return $row;
				}
			} else {
				$countVertical = 0;
			}
		}
		
		$row = array();
		# check for winning move in either diagonal direction
		for ($x = 4; $x < Game::$boardSize; $x++) {
			for ($i = 0; $i <= $x; $i++) {
				if ($this->board[$x-$i][$i] == $myMove) {
					$count1++;
					$row[] = $x-$i;
					$row[] = $i;
					if($count1 == Game::$winningCount){
						return $row;
					}
				} else {
					$count1 = 0;
				}
				if ($this->board[(Game::$boardSize - 1)-$x+$i][(Game::$boardSize - 1)-$i] == $myMove) {
					$count2++;
					$row[] = (Game::$boardSize - 1)-$x+$i;
					$row[] = (Game::$boardSize - 1)-$i;
					if($count2 == Game::$winningCount){
						return $row;
					}
				} else {
					$count2 = 0;
				}
				
				if ($this->board[(Game::$boardSize - 1)-$x+$i][$i] == $myMove) {
					$count3++;
					$row[] = (Game::$boardSize - 1)-$x+$i;
					$row[] = $i;
					if($count3 == Game::$winningCount){
						return $row;
					}
				} else {
					$count3 = 0;
				}
				if ($this->board[$i][(Game::$boardSize - 1)-$x+$i] == $myMove) {
					$count4++;
					$row[] = $i;
					$row[] = (Game::$boardSize - 1)-$x+$i;
					if ($count4 == Game::$winningCount) {
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
		for ($i = 0; $i < Game::$boardSize; $i++) {
			for($j = 0; $j < Game::$boardSize; $j++){
				if ($this->board[$i][$j] === 0) {
					return 0;
				}
			}
		}
		
		return 2;
    }
}

?>