<?php 
//check if the task file exists
$taksFile = "tasks-file.txt";
$tasks_data = [];
$errors = [];

if(!file_exists($taksFile)){
    $newFile = @fopen($taksFile, 'w');
    fclose($newFile);
} else {
    $tasks_data  = file($taksFile);
}





if($_SERVER["REQUEST_METHOD"] === "POST"){
    //clean up inputs
    $title = filter_input(INPUT_POST, 'title');
    $date = filter_input(INPUT_POST, 'date');
    $priority = filter_input(INPUT_POST, 'priority');
    
    if($title && $priority && $date){
        $openedTaskFile = fopen($taksFile, 'a');
        fwrite($openedTaskFile, "[ ] * $title * $priority * $date" . "\n");
        fclose($openedTaskFile);
        $_POST = [];
        header("Location: " . $_SERVER["PHP_SELF"]);
    } else{
        echo "All fields are required";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create your tasks</title>
</head>
<body>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <input type="text" name="title" value="<?= $_POST["title"] ?? "" ?>">
        <input type="date" value = "<?= $_POST["date"] ?? "" ?>" name="date">
        <select name="priority" id="">
            <option <?= ($_POST["priority"] ?? "") == "high" ? "selected" : ""  ?>  value="high">High</option>
            <option <?= ($_POST["priority"] ?? "") == "medium" ? "selected" : ""  ?> value="medium">Medium</option>
            <option <?= ($_POST["priority"] ?? "") == "low" ? "selected" : ""  ?> value="low">Low</option>
        </select>
        <button>Add task</button>
    </form>
    <br>
    <h3>All tasks</h3>
    <?php if(count($tasks_data) < 1): ?>
        <i>No tasks available</i>
    <?php else: ?>
        <p><?= count($tasks_data) ?> Added Tasks</p>
        <ul>
            <?php foreach($tasks_data as $key => $value): ?>
                <?php 
                    $currentTask = explode("*", $value); 
                    // print_r($currentTask);
                ?>
                <li><?= "$currentTask[0] $currentTask[1] created at $currentTask[3] " ?> <button><?= $currentTask[2] ?></button></li>
            <?php endforeach;?>
        </ul>
            
    <?php endif; ?>
</body>
</html>