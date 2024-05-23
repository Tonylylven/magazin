<?php
session_start();

include('php/conn.php');

if (isset($_SESSION['user'])){
    header('Location: profile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['auth'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE login = '$login'";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            //Проверка пароля
            if(password_verify($password, $hashed_password)){
                $_SESSION['user'] = $login;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['is_admin'] = $row['isAdmin'];
                header('Location: profile.php');
                exit;
            } else {
                echo "Неверный логин или пароль";
            }
        }
    } else {
        echo "Неверный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/auth.css">
    <title>Авторизация</title>
</head>
<body>
    <section class="auth">
        <div class="container">
            <h1>Авторизация</h1>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input class="input_form" type="text" name="login" placeholder="Логин" required>
                <input class="input_form" type="password" name="password" placeholder="Пароль" required>
                <input class="button" type="submit" name="auth" value="Войти">
                <a href="./register.php">Нет аккаунта? Зарегистрироваться</a>
            </form>
        </div>
    </section>
</body>
</html>