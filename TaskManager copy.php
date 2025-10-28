<?php

$filename = "data.json";
$filesize = filesize($filename);
$fp       = fopen($filename, "r+");

initialize();
$argv[0];




function initialize(){
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
        default:
            // Devolver mensaje con los parametros posibles
            break;
    }

}

function addTask(){
    global $fp;
    global $filesize;
    global $fp;
    global $argv;

    if (flock($fp, LOCK_EX))
    {   
        $data = json_decode(fread($fp, $filesize), true);
        $last_item    = end($data);
        $last_item_id = $last_item['id'];

        $data[] = array(
            'id' => ++$last_item_id,
            'title' => $argv[2],
            'description' => $argv[3],
            'dueDate' => $argv[4],
            'completed' => false
        );

        $data = array_values($data);
        fseek($fp, 0);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    }else{
        echo "Unable to lock file";
    }
    fclose($fp);
    echo "Has introducido la tarea" ;
}

function deleteTask(){
    global $fp;
    global $filesize;
    global $fp;
    global $argv;

    
    echo "Task deleted";

    if (flock($fp, LOCK_EX))
    {   
        $data = json_decode(fread($fp, $filesize), true);
    
        unset($data[$argv[2]-1]);

        $data = array_values($data);
        fseek($fp, 0);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    }else{
        echo "Unable to lock file";
    }
    fclose($fp);
}

function editTask(){
    global $fp;
    global $filesize;
    global $fp;
    global $argv;

    if (flock($fp, LOCK_EX))
    {   
        $data = json_decode(fread($fp, $filesize), true);

        foreach($data as $item){
            if($item['id'] == $argv[2]){
                $data['id'] = $argv[2];
                $data['title'] = $argv[3];
                $data['description'] = $argv[4];
                $data['dueDate'] = $argv[5];
                $data['completed'] = false;
            }
        }
        
        $data = array_values($data);
        fseek($fp, 0);
        ftruncate($fp, 0);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    }else{
        echo "Unable to lock file";
    }
    fclose($fp);
    echo "Has modificado la tarea" ;
}

function listTask(){
     
}

?>