<?php

class RandomStrategy {

    static function getMove($board) {
        for(;;) {
            $move[0] = rand(0, 14);
            $move[1] = rand(0, 14);

            if ($board[$move[0]][$move[1]]) {
                return $move;
            }
        }
    }

}

class SmartStrategy {
    static function getMove($bool, $board, $move) {
        $playerMove = $board[$move[0]][$move[1]];
		$startIndexHorizontal = ($move[0]<4)? 0 : $move[0]-4;
		$endIndexHorizontal = ($move[0]>10)? 14 : $move[0]+4;
		$startIndexVertical = ($move[1]<4)? 0 : $move[1]-4;
		$endIndexVertical = ($move[1]>10)? 14 : $move[1]+4;
		$countHorizontal = $countVertical = 0;
		$count1 = $count2 = $count3 = $count4 = $temp = 0;
		$tempValue = array(0,0);

        # check for winning move in the horizontal direction
        for ($i = $startIndexHorizontal; $i < $endIndexHorizontal; $i++) {
            if($board[$i][$move[1]] == $playerMove){
				$countHorizontal++;
            } else if($countHorizontal > $temp){
				$temp = $counth;
				if ($temp >= 2 && $board[$i][$move[1]] == 0){
					//echo json_encode($temp);
					$tempValue[0] = $i;
					$tempValue[1] = $move[1];
					break;
				} else if ($temp > 1 && $board[$i][$move[1]] == 2){
					$tempValue[0] = $move[0]-1;
					$tempValue[1] = $move[1];
					break;
				} else {
					//if($temp > 3){echo json_encode($temp);}
					$tempValue = RandomStrategy::getMove($board);
				}
			}
        }

        # check for winning move in the vertical direction
        for ($i = $startIndexVertical; $i < $endIndexVertical; $i++) {
            if($board[$move[0]][$i] == $playerMove){
				$countVertical++;
            } else if($countVertical > $temp){
                $temp = $countVertical;
                if($temp >= 2 && $board[$move[0]][$i] == 0){
                    //echo json_encode($temp);
                    $tempValue[0] = $move[0];
                    $tempValue[1] = $i;
                    break;
                } else if($temp > 1 && $board[$move[0]][$i] == 2){
                    $tempValue[0] = $move[0];
                    $tempValue[1] = $move[1]-1;
                    break;
                } else {
                    //if($temp > 3){echo json_encode($temp);}
                    $tempValue = RandomStrategy::getMove($board);
                }
            }
        }

        return $tempValue;
    }
}

?>