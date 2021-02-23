<?php

    # handle responses returned to the user by the Omok web service
    class Response {
        public $response;

        function __construct($response) {
            $this->response = $response;
        }

        static function reason($reason) { # respond with reason why there was an error
            $instance = new self(false);
            $instance->reason = $reason;
            return $instance;
        }

        static function pid($pid) { # respond with the pid for the created game
            $instance = new self(true);
            $instance->pid = $pid;
            return $instance;
        }

        static function move($acknowledgeMove) {
            $instance = new self(true);
            $instance->acknowledgeMove = $acknowledgeMove;
            return $instance;
        }
    }

?>