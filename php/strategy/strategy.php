<?php

    class RandomStrategy {
        static function pickSlot($board) {

            for(;;) {
                $move[0] = rand(0, 14);
                $move[1] = rand(0, 14);

                # if board slot contains a value of 0, its a valid slot to make a move
                if (!$board[$move[0]][$move[1]]) {
                    return $move;
                }
            }
        }
    }

?>