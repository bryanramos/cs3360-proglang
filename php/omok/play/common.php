
<?php

    function storeGame($pid, $game) {
        $path = "../writable/games/$pid.txt"; # path where game data will be stored

        $file = fopen($path, 'w') or die("Cannot open game file: " . $path);
        fwrite($file, json_encode($game));
        fclose($file);
    }
    
?>