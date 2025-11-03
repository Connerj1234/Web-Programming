<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP Exercises - CW05</title>
</head>
<body>
    <h1>PHP Exercises - CW05</h1>

    <h2>Exercise 1: Function</h2>
    <?php
    function hello_world() {
        echo "Hello world!<br>";
    }
    hello_world();
    ?>

    <h2>Exercise 2: Loops</h2>
    <?php
    for ($i = 1; $i <= 6; $i++) {
        for ($j = 1; $j <= $i; $j++) {
            echo "*";
        }
        echo "<br>";
    }
    ?>

    <h2>Exercise 3: Function</h2>
    <?php
    function triangle($size) {
        echo "<pre>";
        for ($i = 1; $i <= $size; $i++) {
            echo str_repeat(' ', $size - $i) . str_repeat('*', $i) . "\n";
        }
        echo "</pre>";
    }
    triangle(7);
    ?>

    <h2>Exercise 4: Function</h2>
    <?php
    function word_count($str) {
        $count = 0;
        $inWord = false;

        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] != ' ' && !$inWord) {
                $count++;
                $inWord = true;
            } elseif ($str[$i] == ' ') {
                $inWord = false;
            }
        }
        return $count;
    }

    echo "Word count: " . word_count("my name is conner jamison") . "<br>";
    ?>

    <h2>Exercise 5: Function</h2>
    <?php
    function countWords($str) {
        $str = strtolower($str);
        $words = preg_split("/[^a-z]+/", $str, -1, PREG_SPLIT_NO_EMPTY);
        $wordCounts = array();

        foreach ($words as $word) {
            if (isset($wordCounts[$word])) {
                $wordCounts[$word]++;
            } else {
                $wordCounts[$word] = 1;
            }
        }

        return $wordCounts;
    }

    $result = countWords("how many times does the word 'the' appear, i wonder.");
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    ?>

    <h2>Exercise 6: Function</h2>
    <?php
    function remove_all($str, $char) {
        $result = "";
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] != $char) {
                $result .= $str[$i];
            }
        }
        return $result;
    }

    echo remove_all("conner is my name", 'e');
    ?>
</body>
</html>
