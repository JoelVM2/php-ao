<?php

$filename = "data.json";
$filesize = filesize($filename);
$fp       = fopen($filename, "r+");

initialize();
$argv[0];

function initialize()
{
    global $fp;
    global $filesize;
    global $fp;
    global $argv;

    switch ($argv[1]) {
        case 'add':
            addTask();
            break;
        case 'delete';
            deleteTask();
            break;
        case 'edit';
            editTask();
            break;
        case 'list';
            listTask();
            break;
        case 'complete';
            taskCompleted();
            break;
        default:
            // Devolver mensaje con los parametros posibles
            break;
    }
}

function addTask()
{
    global $fp;
    global $filesize;
    global $fp;
    global $argv;

    if (flock($fp, LOCK_EX)) {
        $data = json_decode(fread($fp, $filesize), true);
        $last_item    = end($data);
        $last_item_id = $last_item['id'];

        $data['tasks'] = array(
            'id' => ++$last_item_id,
            'title' => $argv[2],
            'description' => $argv[3],
            'dueDate' => $argv[4],
            'completed' => false
        );
        echo "Task created.";
        $data = array_values($data);
        fseek($fp, 0);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    } else {
        echo "Unable to lock file";
    }
    fclose($fp);
    
}

function deleteTask()
{
    global $fp;
    global $filesize;
    global $fp;
    global $argv;

    if (flock($fp, LOCK_EX)) {
        $data = json_decode(fread($fp, $filesize), true);

        $count = 0;
       
        foreach ($data as $item) {
            if ($item['id'] == $argv[2]) {
                unset($data[$count]);
                echo 'Task deleted.';
            }
            if($argv[2]=='all'){
                unset($data[$count]);
                echo 'All tasks deleted.';
            }
            $count++;
        }

        $data = array_values($data);
        fseek($fp, 0);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    } else {
        echo "Unable to lock file";
    }
    fclose($fp);
}

function editTask()
{
    global $fp;
    global $filesize;
    global $fp;
    global $argv;

    if (flock($fp, LOCK_EX)) {
        $data = json_decode(fread($fp, $filesize), true);

        foreach ($data as &$item) {
            if ($item['id'] == $argv[2]) {
                $item['id'] = (int)$argv[2];
                $item['title'] = $argv[3];
                $item['description'] = $argv[4];
                $item['dueDate'] = $argv[5];
                $item['completed'] = false;
            }
        }
        fseek($fp, 0);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    } else {
        echo "Unable to lock file";
    }
    fclose($fp);
    echo "Task modified";
}

function listTask()
{
    global $fp;
    global $filesize;
    global $fp;
    global $argv;
    $data = json_decode(fread($fp, $filesize), true);

    if($argv[2] == '--completed'){
        echo 'hola';
        foreach ($data as $item) {
            if ($item['completed'] == true) {
                listJson($item);
            }
        }
        return;
    }

    if (!$argv[2] == null) {
       
        foreach ($data as $item) {
            if ($item['id'] == $argv[2]) {
                listJson($item);
            }
        }
        return;
    }
 
    foreach ($data as $item) {
        listJson($item);
    }
   
}


function listJson($item)
{
    $status = $item['completed'] ? 'true' : 'false';
    echo  PHP_EOL . 'Task with Id: ' . $item['id'] . PHP_EOL .
        'Title: ' . $item['title'] . PHP_EOL .
        'Description: ' . $item['description'] . PHP_EOL .
        'Date: ' . $item['dueDate'] . PHP_EOL .
        'Status: ' . $status . PHP_EOL;
}


function taskCompleted(){
    global $fp;
    global $filesize;
    global $fp;
    global $argv;

    if (flock($fp, LOCK_EX)) {
        $data = json_decode(fread($fp, $filesize), true);
        foreach ($data as &$item) {
            if ($item['id'] == $argv[2]) {
                $item['completed'] = true;
            }
        }
        echo 'Task completed!';
        $data = array_values($data);
        fseek($fp, 0);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    } else {
        echo "Unable to lock file";
    }
    fclose($fp);
}