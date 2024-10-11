<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Project PHP Basic Calculator</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" >
        <input type="number" name="num-1" id="">
        <input type="number" name="num-2" id="" step="any">
        <select name="operation" id="">
            <option value="Subtraction">Subtraction</option>
            <option value="Addition">Addition</option>
            <option value="Division">Division</option>
            <option value="Multiplication">Multiplication</option>
        </select>
        <button type="submit">Submit</button>
    </form>
 <?php if (isset($operation) && $operation == "Subtraction") echo "selected"; ?>
    <div class="">
        

        <?php
            $num1 = $num2 = $operation = $op_result = "";
            
            if($_SERVER["REQUEST_METHOD"] === "POST"){
                $num1 = filter_input(INPUT_POST, "num-1", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $num2 = filter_input(INPUT_POST, "num-2", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $operation = $_POST["operation"];
                switch($_POST["operation"]){
                    case "Subtraction":
                        $op_result = $num1 - $num2;
                        break;
                    case "Addition":
                        $op_result = $num1 + $num2;
                        break;
                    case "Multiplication":
                        $op_result = $num1 * $num2;
                        break;
                    case "Division":
                        if($num2 == 0){
                            $op_result = "Error: Division by zero";
                        } else{
                            $op_result = $num1 / $num2;
                        }
                        break;
                }
            }
        ?>

        <h3>Operation results:</h3>
        
        <?php 
        
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            if(!empty($op_result)){
                echo "<p>Result: " . htmlspecialchars($op_result) . "</p>";
            }
        }
        // echo $op_result;
        // echo isset($op_result);
        ?>
        <?php
        echo '<pre>';
            print_r($_SERVER);
        echo '</pre>';
        echo '<pre>';
            print_r($_POST);
        echo '</pre>';
        ?>
    </div>
</body>
</html>