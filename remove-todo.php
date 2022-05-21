<?php

$_GET = filter_input_array(INPUT_GET, FILTER_VALIDATE_INT, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
if ($id) {
    $todos = json_decode(file_get_contents(__DIR__ . '/data/todo.json'), true);
    $todoIndex = array_search($id, array_column($todos, 'id'));
    array_splice($todos, $todoIndex, 1);
    file_put_contents(__DIR__ . '/data/todo.json', json_encode($todos));
}

header('Location: /');
