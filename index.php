<?php

const ERROR_REQUIRE = 'Veuillez entrer une todo';
const ERROR_TOO_SHORT = 'Veuillez enter au moins 3 caractères';

$filename = __DIR__ . '/data/todo.json';
$error = '';
$todo = '';
$todos = [];

if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $todo = $_POST['todo'] ?? '';

    if (!$todo) {
        $error = ERROR_REQUIRE;
    } elseif (strlen($todo) < 3) {
        $error = ERROR_TOO_SHORT;
    }

    if (!$error) {
        $todos = [...$todos, [
            'name' => $todo,
            'done' => false,
            'id' => time()
        ]];

        file_put_contents($filename, json_encode($todos));
        header('Location: /');
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
    <?php require_once 'include/head.php'; ?>
<body>
    <div class="container">
        <?php require_once 'include/header.php'; ?>
        <div class="content">
            <div class="todo-container">
                <h2>Todo</h2>
                <form action="/" method="POST" class="todo-form">
                    <input value="<?= $todo ?>" type="text" name="todo" placeholder="Ajouter une tâche" class="todo-input">
                    <button class="btn btn-primary">Ajouter</button>
                </form>
                <?php  if ($error) : ?>
                <p class="text-danger"><?= $error ?></p>
                <?php endif; ?>
                <ul class="todo-list">
                    <?php foreach ($todos as $todo) : ?>
                    <li class="todo-item <?= $todo['done'] ? 'low-opacity' : '' ?>" >
                        <span class="todo-name">
                            <?= $todo['name'] ?>
                        </span>
                        <span class="todo-actions">
                            <a href="/edit-todo.php?id=<?= $todo['id'] ?>">
                                <button class="btn btn-primary" >
                                    <?= $todo['done'] ? 'Annuler' : 'Valider'?>
                                </button>
                            </a>
                        </span>
                        <span class="todo-action">
                            <a href="/remove-todo.php?id=<?= $todo['id'] ?>">
                                <button class="btn btn-danger" >
                                    Supprimer
                                </button>
                            </a>
                        </span>
                            
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php require_once 'include/footer.php'; ?>
    </div>
</body>
</html>