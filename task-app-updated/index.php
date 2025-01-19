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
        fwrite($openedFile, $taskTitle . " - priority : $priority" . "\n");
        fclose($openedFile);
        $_POST = [];
        header("Location: " . $_SERVER["PHP_SELF"]);
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
                        <li><?= $value ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>