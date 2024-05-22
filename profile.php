<?php
session_start();

include('php/conn.php');

if(isset($_SESSION['is_admin']) == 1) {
    header('Location: admin.php');
}

if (isset($_SESSION['user'])) {
    // Если пользователь залогинен, то выводим его профиль

    // Получаем логин пользователя из сессии
    $user_login = $_SESSION['user_id'];

    // Делаем запрос к базе данных для получения заказов пользователя
    $sql = "SELECT * FROM orders WHERE user_id ='$user_login'";
    $result = $conn->query($sql);
    
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
                <?php
                // Проверяем, есть ли заказы
                if ($result->num_rows > 0) {
                    echo "<h2>Ваши заказы:</h2>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Номер авто</th><th>Статус</th><th>Описание</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        $status_id = $row['status_id'];

                        // Получаем название статуса по id
                        $sql_status = "SELECT name FROM status WHERE id = $status_id";
                        $result_status = $conn->query($sql_status);
                        $status_name = $result_status->fetch_assoc()['name'];

                        echo "<tr>";
                        echo "<td>".$row['id']."</td>";
                        echo "<td>".$row['number_auto']."</td>";
                        echo "<td>".$status_name."</td>";
                        echo "<td>".$row['description']."</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>У вас пока нет заявлений.</p>";
                }
                ?>
                <a href="./new_order.php">Оставить новое заявление</a>
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