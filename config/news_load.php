<?php
// Подключение к базе данных
require_once 'db.php';
// Корректный путь к функциям из подпапки config
if (file_exists('../function.php')) {
    require_once '../function.php';
}

// Получаем уже просмотренные ID
$viewed_news = function_exists('getViewedNews') ? getViewedNews() : array();
$exclude_ids = !empty($viewed_news) ? implode(',', array_map('intval', $viewed_news)) : '0';

// Выбираем 8 случайных новостей, которых еще нет в списке просмотренных
$sql = "SELECT id, title, description, image, category FROM news WHERE id NOT IN ($exclude_ids) ORDER BY RAND() LIMIT 8";
$result = $connection->query($sql);

if ($result && $result->num_rows > 0) {
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $news_id = $row['id'];
        $news_title = $row['title'];
        $news_image = $row['image'];
        $news_category = $row['category'];  // Добавляем категорию

        // Добавляем в просмотренные
        if (function_exists('updateViewedNews')) {
            updateViewedNews($news_id);
        }

        if ($count % 4 == 0) {
            if ($count > 0) echo '</section>';
            echo '<section class="clearfix">';
        }
        
        // Выводим новость с категорией
        echo '<div class="column standard lt-1">';
        echo '<a href="view.php?id=' . $news_id  . '" class="mid-preview js-ad-block crabs_remain" target="_blank">';
        echo '<div class="image"><img src="images/' . $news_image . '" class="lazyload"></div>';
        echo '<div class="title">' . $news_title . '</div>';
        echo '<div class="category">' . $news_category . '</div>';  // Добавляем вывод категории
        echo '</a>';
        echo '</div>';

        $count++;
    }
    echo '</section>';
} else {
    // Сигнал для JS, что новости кончились
    echo ""; 
}

$connection->close();
?>