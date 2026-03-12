<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Новости</title>
    <meta name="keywords" content="новости,шоу-бизнес,общество,культура,экономика,политика,звёзды,скандалы">
    <meta name="description" content="Новости">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/mr_crabs.css" media="screen">
    <script src="js/jquery.min.js"></script>
    <script src="js/crabs_main.js"></script>
    <script src="https://api-maps.yandex.ru/2.0-stable/?load=package.standard&amp;lang=ru-RU" type="text/javascript"></script>
</head>

<body id="main">
<header>
    <div class="center-wrapper">
        <div class="content clearfix">
            <div class="logotype">
                <a href="/" class="logotype logo_link">СРОЧНЫЕ НОВОСТИ</a>
            </div>
            <nav></nav>
        </div>
    </div>
</header>

<main>
    <div class="center-wrapper">
        <div class="content" id="jsContentWrapper">
            <div class="container" id="app">
                <div id="br_autoload"></div>
                <section class="clearfix" id="scroll_indicator" style="text-align: center; padding: 20px 0;">
                    <h1>ПРОКРУТИТЕ СТРАНИЦУ ВНИЗ</h1>
                </section>
                <!-- Новости с категориями -->
                <div class="news-container">
                    <!-- Новость будет добавляться сюда динамически -->
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function () {
        var inProgress = false;
        var noMoreNews = false;

        function loadNews() {
            if (inProgress || noMoreNews) return;

            $.ajax({
                url: 'config/news_load.php',
                method: 'POST',
                beforeSend: function () {
                    inProgress = true;
                }
            }).done(function (data) {
                if (data.trim().length > 50) {
                    $(".news-container").append(data); // Добавляем новости с категориями
                    inProgress = false;

                    // Если контента мало и скролла все еще нет — догружаем
                    if ($(window).height() >= $(document).height()) {
                        loadNews();
                    }
                } else {
                    noMoreNews = true;
                    $("#scroll_indicator h1").text("НОВОСТЕЙ БОЛЬШЕ НЕТ");
                }
            }).fail(function() {
                inProgress = false;
            });
        }

        // Стартовая загрузка
        loadNews();

        // Загрузка при скролле
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 600 && !inProgress) {
                loadNews();
            }
        });
    });
</script>

<footer style="display:none">
    <div class="center-wrapper">
        <div class="content clearfix">
            <div class="column">
                <a href="/" class="logotype logo_link">СРОЧНЫЕ НОВОСТИ</a>
                <div class="copyright">Copyright © 2026</div>
            </div>
        </div>
    </div>
</footer>

<script src="js/buildall.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/crabs_best.js"></script>

</body>
</html>