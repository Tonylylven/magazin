<?php
session_start();

include('php/conn.php');

// Проверка на авторизацию и роль пользователя
if (isset($_SESSION['user']) && $_SESSION['is_admin'] == 1) {
    // Обработка изменения статуса заявления
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
        $order_id = $_POST['order_id'];
        $new_status = $_POST['new_status'];

        $sql = "UPDATE orders SET status_id = '$new_status' WHERE id = '$order_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Статус заявления обновлен.";
        } else {
            echo "Ошибка при обновлении статуса заявления: " . $conn->error;
        }
    }

    // Получение всех заявлений
    $sql = "SELECT o.id, o.number_auto, o.description, o.status_id, s.name 
            FROM orders o
            JOIN status s ON o.status_id = s.id
            ORDER BY o.id DESC";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администраторская панель</title>
</head>
<body>
    <h1>Администраторская панель</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Номер авто</th>
                <th>Описание</th>
                <th>Статус</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['number_auto']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <select name="new_status">
                                <option value="1">Новая</option>
                                <option value="2">В работе</option>
                                <option value="3">Выполнено</option>
                                <option value="4">Отменено</option>
                            </select>
                            <input type="submit" name="update_status" value="Обновить">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="?logout=1">Выйти</a>
</body>
</html>

<?php
} else {
    // Если пользователь не авторизован или не является администратором
    header('Location: index.php');
    exit;
}
if (isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}
$conn->close();
?>