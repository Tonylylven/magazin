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
            $sql_users = "INSERT INTO users (login, password) 
                VALUES ('$login', '$hashed_password')";
            if ($conn->query($sql_users) === TRUE) {
                $user_id = $conn->insert_id;

                // Вставляем личные данные пользователя в таблицу lichn_dann
                $sql_lichn_dann = "INSERT INTO lichn_dann (id_user, fio, tel, email) 
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
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2>Регистрация</h2>
        <input type="text" name="fio" placeholder="ФИО" required>
        <input type="tel" name="tel" placeholder="Телефон" required>
        <input type="text" name="login" placeholder="Логин" required>
        <input type="email" name="email" placeholder="Почта" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <input type="submit" name="register" value="Зарегистрироваться">
        <a href="./auth.php">Есть аккаунт? Войти</a>
    </form>
</body>
</html>
