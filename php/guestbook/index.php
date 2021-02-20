<?php
    $note = array_key_exists("note", $_POST) ? $_POST["note"] : null;
?>
<html>
<head>
    <title>Guestbook</title>
</head>
<body>
    <h1>Welcome to My Guestbook</h1>
    <h2>Please leave me a short note below</h2>
    <form method="POST">
        <textarea name="note" id="note" cols="40" rows="5"></textarea>
        </p>
        <input type="submit" value="Send it" />
    </form>
    <?php
        $file = 'notes.txt';
        if (!file_exists($file)) {
            $fp = fopen($file, "w") or die(); # create file if it doesn't exist
        }
        addNote($note);
    ?>

    <h2>The entries so far:</h2>
    <?php showNotes(); ?>

    <?php 

        function addNote($note) {
            global $file;
            if (!empty($note)) {
                $fp = fopen($file, 'a'); // append mode
                fputs($fp, nl2br($note) . "</br>\n");
                fclose($fp);
            }
        }

        function showNotes() {
            global $file;
            $fp = fopen($file, "r") or die("Unable to open file!");
            while (!feof($fp)) {
                echo fgets($fp);
            }
            fclose($fp);
        }

    ?>

</body>
</html>