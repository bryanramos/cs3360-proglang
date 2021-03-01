
<?php

    # Coded By Bryan Ramos

    function storeGame($pid, $game) {
        $path = "../writable/games/$pid.txt"; # path where game data will be stored

        $file = fopen($path, 'w') or die("Cannot open game file: " . $path);
        fwrite($file, json_encode($game)); # serialize
        fclose($file);
    }
    
    function toJson($response) { 
        echo json_encode($response);
    }
    
?>