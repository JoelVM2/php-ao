<?php
$filename = "data.json";
$filesize = filesize($filename);
$fp       = fopen($filename, "r+");

// Inicia el gestor de tareas
initialize();

/**
 * Función principal que determina la acción a ejecutar
 * según el argumento recibido por consola.
 */
function initialize()
{
    global $argv;

    switch ($argv[1]) {
        case 'add':
            addTask();
            break;
        case 'delete':
            deleteTask();
            break;
        case 'edit':
            editTask();
            break;
        case 'list':
            listTask();
            break;
        case 'complete':
            taskCompleted();
            break;
        case 'help':
            argumentsList();
            break;
        default:
            argumentsList();
            break;
    }
}

/**
 * Añade una nueva tarea al archivo JSON.
 * 
 * Usa los argumentos:
 *  $argv[2] = título
 *  $argv[3] = descripción
 *  $argv[4] = fecha de vencimiento
 * 
 * Crea un nuevo objeto con un ID autoincrementado
 * y lo guarda en el archivo data.json.
 */
function addTask()
{
    global $fp;
    global $filesize;
    global $argv;

    if (flock($fp, LOCK_EX)) {
        $data = json_decode(fread($fp, $filesize), true);
        $last_item    = end($data);
        $last_item_id = $last_item['id'];
        // Crea una nueva tarea
        $data[] = array(
            'id' => ++$last_item_id,
            'title' => $argv[2],
            'description' => $argv[3],
            'due_date' => $argv[4],
            'completed' => false
        );
        echo "Task created.";
        // Guarda el archivo con el nuevo contenido
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
/**
 * Elimina una tarea del archivo JSON.
 * 
 * Usa los argumentos:
 *  $argv[2] = id de la tarea o la palabra 'all'
 * 
 * Si el argumento es 'all', elimina todas las tareas.
 * Si es un número, elimina solo la tarea con ese ID.
 */
function deleteTask()
{
    global $fp;
    global $filesize;
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
        // Guarda los cambios
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
/**
 * Edita una tarea existente.
 * 
 * Usa los argumentos:
 *  $argv[2] = id de la tarea
 *  $argv[3] = nuevo título
 *  $argv[4] = nueva descripción
 *  $argv[5] = nueva fecha de vencimiento
 * 
 * Modifica los datos de la tarea especificada y guarda los cambios.
 */
function editTask()
{
    global $fp;
    global $filesize;
    global $argv;

    if (flock($fp, LOCK_EX)) {
        $data = json_decode(fread($fp, $filesize), true);

        foreach ($data as &$item) {
            if ($item['id'] == $argv[2]) {
                $item['id'] = (int)$argv[2];
                $item['title'] = $argv[3];
                $item['description'] = $argv[4];
                $item['due_date'] = $argv[5];
                $item['completed'] = false;
            }
        }
        // Guarda los cambios
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
/**
 * Lista las tareas almacenadas en el archivo JSON.
 * 
 * Puede recibir los siguientes argumentos:
 *  - Sin argumentos: lista todas las tareas
 *  - <id>: muestra solo la tarea con ese ID
 *  - --completed: muestra solo las tareas completadas
 */
function listTask()
{
    global $fp;
    global $filesize;
    global $argv;
    $data = json_decode(fread($fp, $filesize), true);
    // Listar solo completadas
    if($argv[2] == '--completed'){
        echo 'hola';
        foreach ($data as $item) {
            if ($item['completed'] == true) {
                listJson($item);
            }
        }
        return;
    }
    // Listar por ID específico
    if (!empty($argv[2])) {
       
        foreach ($data as $item) {
            if ($item['id'] == $argv[2]) {
                listJson($item);
            }
        }
        return;
    }
     // Listar todas
    foreach ($data as $item) {
        listJson($item);
    }
   
}

/**
 * Muestra una tarea formateada en texto legible.
 * 
 * @param array $item La tarea a mostrar.
 */
function listJson($item)
{
    $status = $item['completed'] ? 'true' : 'false';
    echo  PHP_EOL . 'Task with Id: ' . $item['id'] . PHP_EOL .
        'Title: ' . $item['title'] . PHP_EOL .
        'Description: ' . $item['description'] . PHP_EOL .
        'Date: ' . $item['due_date'] . PHP_EOL .
        'Status: ' . $status . PHP_EOL;
}

/**
 * Marca una tarea como completada.
 * 
 * Usa el argumento:
 *  $argv[2] = id de la tarea a marcar como completada
 * 
 * Cambia el campo "completed" a true en el archivo JSON.
 */
function taskCompleted(){
    global $fp;
    global $filesize;
    global $argv;

    if (flock($fp, LOCK_EX)) {
        $data = json_decode(fread($fp, $filesize), true);
        foreach ($data as &$item) {
            if ($item['id'] == $argv[2]) {
                $item['completed'] = true;
            }
        }
        echo 'Task completed!';
        // Guarda el archivo actualizado
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

/**
 * Muestra una guía de uso del programa.
 * 
 * Explica los diferentes comandos disponibles
 * y su sintaxis.
 */
function argumentsList(){
    echo 'Use: ' . PHP_EOL . ' add <title> <description> <due_date> to add a new task' . PHP_EOL .PHP_EOL . ' edit <id> <title> <description> <due_date> to edit a task' . PHP_EOL .PHP_EOL . 
    ' delete <id> to delete per id / delete all to remove all the tasks' . PHP_EOL .PHP_EOL . ' list to list all the tasks / list <id> to list a task per id / list --completed to list completed tasks' 
    . PHP_EOL .PHP_EOL . ' complete <id> to complete a task'.PHP_EOL;
}