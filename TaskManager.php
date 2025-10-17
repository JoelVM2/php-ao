<?php

$file = file_get_contents("data.json");
$data = json_decode($file, true);

$last_item    = end($data);
$last_item_id = $last_item['id'];

?>