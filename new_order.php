<?php
session_start();

include('php/conn.php');

if (isset($_SESSION['user'])) {
    $user_login = $_SESSION['user_id'];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $number_auto = $_POST['number_auto'];
        $description = $_POST['description'];

        $sql = "INSERT INTO orders (number_auto, user_id, status_id, description)  VALUES ('$number_auto','$user_login','1','$description')";
        $result = $conn->query($sql);
        
        if ($result === TRUE) {
            echo "Заявление успешно создано!";
            header('Location: profile.php');
        } else {
            echo "Ошибка " . $sql . "<br>" . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новое заявление</title>
</head>
<body>
    <section class="order">
        <div class="container">
            <h1>Новое заявление</h1>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="number_auto">Номер авто:</label>
                <input type="text" id="number_auto" name="number_auto" required>

                <label for="description">Описание:</label>
                <input type="text" id="description" name="description" required>

                <input type="submit" value="Создать заявление">
            </form>
        </div>
    </section>
</body>
</html>

<?php
} else {
    header('Location: index.php');
    exit;
}
?>