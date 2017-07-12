<?php

include_once 'dbConnect.php';

$function = isset($_POST['function']) ? $_POST['function'] : '';
$todo_id = isset($_POST['todo_id']) ? $_POST['todo_id'] : '';
$todo_name = isset($_POST['todo_name']) ? $_POST['todo_name'] : '';
$todo_desc = isset($_POST['todo_desc']) ? $_POST['todo_desc'] : '';


if ($function == 'todoInsert') {
	todoInsert($todo_name, $todo_desc);
} else if ($function == 'todoUpdate') {
	todoUpdate($todo_id, $todo_name, $todo_desc);
} else if ($function == 'todoDelete') {
	todoDelete($todo_id);
} 

/*
 * Call the store procedure for todo insert
 */
function todoInsert($todo_name, $todo_desc) {
	$sqlString = 'CALL spa_todo_insert("' . $todo_name . '","' . $todo_desc . '")';
	postData($sqlString);
}

/*
 * Call the store procedure for todo update
 */
function todoUpdate($todo_id, $todo_name, $todo_desc) {
	$sqlString = 'CALL spa_todo_update(' . $todo_id . ',"' . $todo_name . '","' . $todo_desc . '")';
	postData($sqlString);
}

/*
 * Call the store procedure for todo delete
 */
function todoDelete($todo_id) {
	$sqlString = 'CALL spa_todo_delete(' . $todo_id . ')';
	postData($sqlString);
}

/*
 * Trigger the procedure
 */
function postData($sql) {     
    $link = connectDatabase();
	$link->query($sql);
}
?>