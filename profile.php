<?php
session_start();

include('php/conn.php');

if (isset($_SESSION['user'])) {
    // Если пользователь залогинен, то выводим его профиль
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
</head>
<body>
    <section class="profile">
        <div class="container">
            <div class="navigation">
                <h1>Профиль</h1>
                <p>Логин: <?php echo $_SESSION['user'];?></p>
                <a href="?logout=1">Выйти</a>
            </div>
            <div class="orders">
                
            </div>
        </div>
    </section>
</body>
</html>
<?php
} else {
    // Если пользователь не залогинен, то перенаправляем его на страницу авторизации
    header('Location: index.php');
    exit;
}

// Обработка выхода пользователя
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}
?>