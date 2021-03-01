<?php

# Coded By Bryan Ramos

include_once "game.php";

class RandomStrategy {

	static function pickSlot($board) {

		if (Game::isFull($board)) {
            toJson(Response::reason("Board is full"));
            exit;
        }

		for(;;){

			$move[0] = rand(0, Game::$boardSize-1);
			$move[1] = rand(0, Game::$boardSize-1);

			# if board slot if 0, its a valid slot to make a move
			if (empty($board[$move[0]][$move[1]])) {
				return $move;
			}
		}

	}
}

class SmartStrategy {

	static function pickSlot($bool, $board, $move) {

		if (Game::isFull($board)) {
            toJson(Response::reason("Board is full"));
            exit;
        }

		$playerMove = $board[$move[0]][$move[1]];
		$startIndexHorizontal= ($move[0]<4)? 0 : $move[0]-4;
		$endIndexHorizontal = ($move[0]>10)? (Game::$boardSize - 1) : $move[0]+4;
		$startIndexVertical = ($move[1]<4)? 0 : $move[1]-4;
		$endIndexVertical = ($move[1]>10)? (Game::$boardSize - 1) : $move[1]+4;
		$countHorizontal = $countVertical = 0;
		$temp = 0;
		$tempValue = array(0,0);	
		
		for($i = $startIndexHorizontal; $i < $endIndexHorizontal; $i++){
			if($board[$i][$move[1]] == $playerMove){
				$countHorizontal++;
			} else if($countHorizontal > $temp){
				$temp = $countHorizontal;
				if($temp >= 2 && $board[$i][$move[1]] == 0){
					$tempValue[0] = $i;
					$tempValue[1] = $move[1];
					break;
				} else if($temp > 1 && $board[$i][$move[1]] == 2){
					$tempValue[0] = $move[0]-1;
					$tempValue[1] = $move[1];
					break;
				} else {
					$tempValue = RandomStrategy::pickSlot($board);
				}
			}
		}
		
		for ($i = $startIndexVertical; $i < $endIndexVertical; $i++) {
			if ($board[$move[0]][$i] == $playerMove){
				$countVertical++;
			} else if ($countVertical > $temp){
                $temp = $countVertical;
                if ($temp >= 2 && $board[$move[0]][$i] == 0) {
                    $tempValue[0] = $move[0];
                    $tempValue[1] = $i;
                    break;
                } else if ($temp > 1 && $board[$move[0]][$i] == 2) {
                    $tempValue[0] = $move[0];
                    $tempValue[1] = $move[1]-1;
                    break;
                } else {
                    $tempValue = RandomStrategy::pickSlot($board);
                }
            }
        }

		return $tempValue;
	}
}
?>
	
