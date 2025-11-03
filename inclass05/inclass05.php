<!DOCTYPE html>
<html lang="en">
<head>
    <title>In Class 05</title>
</head>
<body>

    <h2>Step 1: Create the Matrix</h2>
    <?php
    $matrix = array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      );
    echo "Matrix created successfully!<br>";
    echo "Matrix structure:<br>";
    echo "<pre>";
    print_r($matrix);
    echo "</pre>";
    ?>

    <h2>Step 2: Access Matrix Elements</h2>
    <?php
    echo "Element at [0][0]: " . $matrix[0][0] . "<br>";
    echo "Element at [1][1]: " . $matrix[1][1] . "<br>";
    echo "Element at [2][2]: " . $matrix[2][2] . "<br>";
    ?>

    <h2>Step 3: Modify Elements</h2>
    <?php
    $matrix[1][2] = 10;
    $matrix[2][1] = 15;
    ?>

    <h2>Step 4: Add New Elements</h2>
    <?php
    $matrix[2][] = 11; // Add to existing row
    $matrix[] = array(10, 11, 12, 13); // Add new row
    ?>

    <h2>Step 5: Count Matrix Dimensions</h2>
    <?php
    echo "Number of rows: " . count($matrix) . "<br>";
    echo "Elements in row 0: " . count($matrix[0]) . "<br>";
    echo "Elements in row 1: " . count($matrix[1]) . "<br>";
    echo "Elements in row 2: " . count($matrix[2]) . "<br>";
    ?>

    <h2>Step 6: Display Matrix as HTML Table</h2>
    <?php
    echo "<table border='1' cellpadding='10'>";
    foreach ($matrix as $row) {
      echo "<tr>";
      foreach ($row as $cell) {
        echo "<td>$cell</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
    ?>

    <h2>Bonus Challenge: Find Maximum Value    </h2>
    <?php
    $max = $matrix[0][0];
    foreach ($matrix as $row) {
      foreach ($row as $cell) {
        if ($cell > $max) {
          $max = $cell;
        }
      }
    }
    echo "Maximum value: $max";
    ?>

    <h2>   Knowledge Check </h2>
    <?php
    echo "Q1: b) Second row, third column <br><br>";

    echo "Q2: b) \$matrix[1][] = 10 <br><br>";

    echo "Q3: b) Number of rows in the matrix <br><br>";

    echo "Q$: b) \$matrix[1][1] <br><br>";
    ?>

</body>
</html>
