<html>
<head>
    <title>Results</title>
</head>
<body>
    <h1>Iteration Results</h1>
    <b>Here are 10 iterations of the formula:</br>y = x <sup>2</sup></b>
    </p>
    <?php
        $num = 0;

        if (!empty($_POST)) {
            $num = $_POST['data'];

            echo 'Initial value of x: <strong>' . $num . '</strong></p>';

            $i = 1;
            while ($i <= 10) {
                $num *= $num;
                echo $i . ". " . $num . "</br>";
                $i++;
            }
        } else {
            echo "No value of x provided. Please submit the form <a href='./iter.php'>here</a>.";
        }
    ?>
</body>
</html>