<?php
/**
 * submit task details from form
 * sanitize inputs
 * store tasks in file ( text file )
 * display tasks on page
 * add priority indicators
 * mark as done
 */

 $myTaskFile = "myTaskFile.txt";
 $allTasks = [];

 if(!file_exists($myTaskFile)){
    $openedFile = fopen($myTaskFile, "w");
    fclose($openedFile);
 } else {
    $allTasks = file($myTaskFile);
 }

 if($_SERVER["REQUEST_METHOD"] === "POST")
 {
    $taskTitle = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
    $priority = filter_input(INPUT_POST, "priority", FILTER_SANITIZE_STRING);

    if(!$taskTitle || !$priority){
        echo "All fields are required";
    } else {
        $openedFile = fopen($myTaskFile,'a');
        fwrite($openedFile, "[ ] - " . $taskTitle . " - " . $priority . "\n");
        fclose($openedFile);
        $_POST = [];
        header("Location: " . $_SERVER["PHP_SELF"]);
    }

 }


 if(isset($_GET["done"])){
    $indx = $_GET["done"];
    $tasksList = file($myTaskFile, FILE_IGNORE_NEW_LINES);

    // check if the current done value is a valid index in the array
    if(isset($tasksList[$indx])){
        // mark the [ ] as [ x ] creating a toggle effect 
        $tasksList[$indx] =  str_contains($tasksList[$indx], "[ ]") ? str_replace("[ ]", "[ x ]", $tasksList[$indx]) : str_replace("[ x ]", "[ ]", $tasksList[$indx]);
        //update the tasks list with the new value
        $updatedList = implode("\n", $tasksList); // implode joins each item in the list with a new line
        file_put_contents($myTaskFile, $updatedList . "\n"); // file put contents will write the entire data to file
        header("Location: " . $_SERVER["PHP_SELF"]); // refresh the page to remove the values from the urkl
    }
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo list</title>
    <style>
        .container{
            max-width: 700px;
            margin: auto;
            display: flex;
            align-items: center;
            flex-direction: column;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        select,input {
            width: 100%;
        }
        .task-container{
            margin-top: 20px;
        }
        .task-item{
            display: flex;
            flex-direction:row;
            justify-content:space-between;
            font-size: 20px;
            padding: 10px 0;
            margin-bottom: 10px;
            width: 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>What did you have planned for today?</h2>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <input type="text" name="title" placeholder="Enter a task">
            <select name="priority" id="">
                <option value=""></option>
                <option value="low">Low</option>
                <option value="high">High</option>
            </select>
            <button>Submit</button>
        </form>
        <div class="task-container">
            <div class="tasks-info"><?= count($allTasks)?> Tasks Added </div>
            <?php if(count($allTasks) > 0) :?>
                <ul>     
                    <?php foreach($allTasks as $key => $value) :?>
                        <?php
                            $newValue = explode("-", $value);
                        ?>
                        <li class="task-item"> <a href="?done=<?=$key?>"><?= $newValue[0]?></a> <?= $newValue[1]?> <b><?= $newValue[2]?></b></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>