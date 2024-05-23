<?php
session_start();

include('php/conn.php');

if (isset($_SESSION['user'])) {
    header('Location: profile.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $fio = $_POST['fio'];

        // Проверяем, существует ли пользователь с таким же логином
        $sql = "SELECT * FROM users WHERE login = '$login'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "Такой пользователь уже существует.";
        } else {
            // Хэшируем пароль
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Вставляем пользователя в таблицу users
            $sql_users = "INSERT INTO users (login, password, isAdmin) 
                VALUES ('$login', '$hashed_password', 0)";
            if ($conn->query($sql_users) === TRUE) {
                $user_id = $conn->insert_id;

                // Вставляем личные данные пользователя в таблицу lichn_dann
                $sql_lichn_dann = "INSERT INTO lichnie_dannie (id_user, fio, tel, email) 
                    VALUES ('$user_id', '$fio', '$tel', '$email')";
                if ($conn->query($sql_lichn_dann) === TRUE) {
                    $_SESSION['user'] = $login;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['fio'] = $fio;
                    $_SESSION['tel'] = $tel;
                    $_SESSION['email'] = $email;
                    header('Location: profile.php');
                    exit;
                } else {
                    echo "Ошибка при регистрации: " . $conn->error;
                }
            } else {
                echo "Ошибка при регистрации: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/register.css">
    <title>Регистрация</title>
</head>
<body>
    <div class="register">
        <div class="container">
        <h1>Регистрация</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input class="input_form" type="text" name="fio" placeholder="ФИО" required>
            <input class="input_form" type="tel" name="tel" id="phone" placeholder="Телефон" required>
            <input class="input_form" type="text" name="login" placeholder="Логин" required>
            <input class="input_form" type="email" name="email" placeholder="Почта" required>
            <input class="input_form" type="password" name="password" placeholder="Пароль" required>
            <input class="button" type="submit" name="register" value="Зарегистрироваться">
            <a class="link" href="./auth.php">Есть аккаунт? Войти</a>
        </form>
        </div>
    </div>
</body>
</html>
