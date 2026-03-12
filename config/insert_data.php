<?php

require "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из формы
    $title = htmlspecialchars($_POST['title']);
    $description = $_POST['description'];
    $category = $_POST['category']; // Получаем категорию из формы

    $title = mysqli_real_escape_string($connection, $title);
    $description = mysqli_real_escape_string($connection, $description);
    $category = mysqli_real_escape_string($connection, $category); // Защищаем от инъекций

    $thumbnail = null;

    // Загрузка изображения
    if (isset($_FILES['image'])) {
        $target_dir = "../images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Проверка, является ли файл изображением
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Допустимые форматы файлов
            $allowed_formats = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageFileType, $allowed_formats)) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                $response = [
                    'status' => 'error',
                    'message' => 'Недопустимый формат файла. Разрешены только JPG, JPEG, PNG и GIF.'
                ];
            }
        } else {
            $uploadOk = 0;
            $response = [
                'status' => 'error',
                'message' => 'Файл не является изображением.'
            ];
        }

        // Загрузка файла
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $thumbnail = $target_file;
            }
        }
    }

    // Добавление записи в базу данных
    if ($uploadOk == 1) {
        $sql = "INSERT INTO news (title, description, image, date, category) VALUES (?, ?, ?, now(), ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssss", $title, $description, $thumbnail, $category);

        if ($stmt->execute()) {
            $response = [
                'status' => 'success',
                'message' => 'Запись успешно добавлена'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Ошибка при добавлении записи'
            ];
        }

        $stmt->close();
    }

    $connection->close();

    // Возвращаем JSON-ответ
    header('Content-Type: application/json');
    echo json_encode($response);
}