<?php
$host = 'localhost';
$username = 'root';
$password = '';
$db = 'demo_ekzamen';

$conn = new mysqli($host, $username, $password, $db);

if ($conn->connect_error) {
    die('Ошибка подключения к базе данных: ' . $conn->connect_error);
}

