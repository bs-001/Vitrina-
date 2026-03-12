<?php
session_start();
require "config/db.php";
require "config/function.php";
$user_country = getUserCountry();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $sql = "SELECT title FROM news WHERE id = $id";
            $result = mysqli_query($connection, $sql);
            $row = mysqli_fetch_assoc($result);
            echo $row ? $row["title"] : "СРОЧНЫЕ НОВОСТИ";
        }
    ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="css/mr_crabs.css" media="screen">
    <script src="js/jquery.min.js"></script>
    <script src="js/crabs_main.js"></script>
    <style>
        /* 1. БАЗОВЫЕ СТИЛИ И ФОН */
        body { 
            background-color: #f0f2f5 !important; 
            margin: 0; 
            padding: 0; 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        /* ГЛАВНЫЙ КОНТЕЙНЕР (ФЛЕКСЫ) */
        .main-wrapper {
            max-width: 1240px;
            margin: 0 auto;
            display: flex;
            gap: 20px;
            padding: 20px;
            box-sizing: border-box;
        }

        /* ЛЕВЫЙ БЛОК */
        .left-content {
            flex: 1;
            min-width: 0; /* Фикс для флексов */
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e1e4e8;
        }

        /* ПРАВЫЙ БЛОК (САЙДБАР) */
        .right-sidebar {
            width: 340px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e1e4e8;
            height: fit-content;
        }

        /* ЗАГОЛОВКИ СЕКЦИЙ */
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 3px solid #244f8f;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            color: #244f8f;
            font-size: 18px;
        }

        /* КАРТОЧКИ С ОБЪЕМОМ */
        .card-item {
            display: block;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .card-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }
        .card-item img { width: 100%; height: 160px; object-fit: cover; display: block; }
        .card-item .inner-title { padding: 12px; font-size: 15px; color: #222; font-weight: 600; line-height: 1.4; }

        /* СЕТКИ */
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }

        /* АДАПТИВ ПОД МОБИЛУ */
        @media screen and (max-width: 1024px) {
            .main-wrapper {
                flex-direction: column; /* Колонки друг под другом */
                padding: 10px;
            }
            .right-sidebar {
                width: 100%;
                box-sizing: border-box;
            }
            .grid-4, .grid-3 {
                grid-template-columns: repeat(2, 1fr); /* По 2 в ряд на планшетах */
            }
        }

        @media screen and (max-width: 550px) {
            .left-content { padding: 15px; }
            .grid-4, .grid-3 {
                grid-template-columns: 1fr; /* По 1 в ряд на мелких мобилах */
            }
            h1 { font-size: 22px !important; }
            .card-item img { height: 200px; } /* Делаем картинку побольше в мобильном списке */
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <div class="left-content">
        <?php
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $sql = "SELECT * FROM news WHERE id = $id";
            $res = mysqli_query($connection, $sql);
            if ($row = mysqli_fetch_assoc($res)) {
        ?>
            <div class="main-news-block">
                <img src="images/<?php echo $row["image"]; ?>" style="width:100%; border-radius:8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h1 style="font-size: 28px; line-height: 1.3; margin: 20px 0; color: #111;"><?php echo $row["title"]; ?></h1>
                <div style="font-size: 18px; line-height: 1.8; color: #333; margin-bottom: 30px;">
                    <?php echo $row["description"]; ?>
                </div>
            </div>

            <div class="section-title">ЧИТАЙТЕ ТАКЖЕ:</div>
            <div class="grid-4">
                <?php
                $nq = mysqli_query($connection, "SELECT id, title, image FROM news WHERE id != $id ORDER BY RAND() LIMIT 2");
                $oq = mysqli_query($connection, "SELECT * FROM offers WHERE FIND_IN_SET('$user_country', offer_geo) > 0 ORDER BY RAND() LIMIT 2");
                $news = mysqli_fetch_all($nq, MYSQLI_ASSOC);
                $offs = mysqli_fetch_all($oq, MYSQLI_ASSOC);
                
                for($i=0; $i<2; $i++) {
                    if(isset($news[$i])) {
                        echo '<a href="view.php?id='.$news[$i]['id'].'" class="card-item"><img src="images/'.$news[$i]['image'].'"><div class="inner-title">'.$news[$i]['title'].'</div></a>';
                    }
                    if(isset($offs[$i])) {
                        $link = replace_macros($offs[$i]['offer_link'], $id, $offs[$i]['id']);
                        echo '<a href="'.$link.'" class="card-item offer-link" target="_blank" data-id="'.$offs[$i]['id'].'"><img src="images/offers/'.$offs[$i]['offer_image'].'"><div class="inner-title" style="color:#d32f2f;">'.$offs[$i]['offer_name'].'</div></a>';
                    }
                }
                ?>
            </div>

            <div class="section-title">АКТУАЛЬНО СЕГОДНЯ:</div>
            <div class="grid-3">
                <?php
                // 4 ряда: Новость - Тизер - Новость
                for($r=0; $r<4; $r++) {
                    $nq2 = mysqli_query($connection, "SELECT id, title, image FROM news WHERE id != $id ORDER BY RAND() LIMIT 2");
                    $oq2 = mysqli_query($connection, "SELECT * FROM offers WHERE FIND_IN_SET('$user_country', offer_geo) > 0 ORDER BY RAND() LIMIT 1");
                    
                    if($n1 = mysqli_fetch_assoc($nq2)) echo '<a href="view.php?id='.$n1['id'].'" class="card-item"><img src="images/'.$n1['image'].'"><div class="inner-title">'.$n1['title'].'</div></a>';
                    if($o1 = mysqli_fetch_assoc($oq2)) {
                        $l1 = replace_macros($o1['offer_link'], $id, $o1['id']);
                        echo '<a href="'.$l1.'" class="card-item offer-link" target="_blank" data-id="'.$o1['id'].'"><img src="images/offers/'.$o1['offer_image'].'"><div class="inner-title" style="color:#d32f2f;">'.$o1['offer_name'].'</div></a>';
                    }
                    if($n2 = mysqli_fetch_assoc($nq2)) echo '<a href="view.php?id='.$n2['id'].'" class="card-item"><img src="images/'.$n2['image'].'"><div class="inner-title">'.$n2['title'].'</div></a>';
                }
                ?>
            </div>
        <?php } } ?>
    </div>

    <div class="right-sidebar">
        <div class="section-title" style="margin-top:0;">ПОПУЛЯРНОЕ:</div>
        <?php
        // 6 новостей + 6 тизеров для выравнивания высоты
        $sqn = mysqli_query($connection, "SELECT id, title, image FROM news WHERE id != $id ORDER BY RAND() LIMIT 6");
        $sqo = mysqli_query($connection, "SELECT * FROM offers WHERE FIND_IN_SET('$user_country', offer_geo) > 0 ORDER BY RAND() LIMIT 6");
        for($i = 0; $i < 6; $i++) {
            if($sn = mysqli_fetch_assoc($sqn)) echo '<a href="view.php?id='.$sn['id'].'" class="card-item"><img src="images/'.$sn['image'].'"><div class="inner-title">'.$sn['title'].'</div></a>';
            if($so = mysqli_fetch_assoc($sqo)) {
                $sl = replace_macros($so['offer_link'], $id, $so['id']);
                echo '<a href="'.$sl.'" class="card-item offer-link" target="_blank" data-id="'.$so['id'].'"><img src="images/offers/'.$so['offer_image'].'"><div class="inner-title" style="color:#d32f2f;">'.$so['offer_name'].'</div></a>';
            }
        }
        ?>
    </div>
</div>

<script>
    $(document).on('click', '.offer-link', function() {
        var offId = $(this).data('id');
        $.post('config/update_clicks.php', {offer_id: offId});
    });
</script>
</body>
</html>