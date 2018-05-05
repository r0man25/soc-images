<?php include_once "script/script.php"?>

<?php
session_start();
if (isset($_POST['from']) && isset($_POST['to'])) {
    $_SESSION['from'] = $_POST['from'];
    $_SESSION['to'] = $_POST['to'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Routes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <div class="map">
        <h2>Map:</h2>
        <img src="/test-route/img/routes.png" alt="routes">
    </div>

    <div class="search-form">
        <h3>Search route</h3>
        <form method="post">

            <label class="label">From:</label>
            <select class="turnintodropdown" name="from">
                <?php foreach (Route::getFroms() as $from): ?>
                    <option value="<?= $from ?>" <?= (isset($_SESSION['from']) && $_SESSION['from'] == $from) ? " selected" : "" ?>>
                        <?= $from ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>To:</label>
            <select name="to" id="">
                <?php foreach (Route::getTos() as $to): ?>
                    <option value="<?= $to ?>"<?= (isset($_SESSION['to']) && $_SESSION['to'] == $to) ? " selected" : "" ?>>
                        <?= $to ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Search">
        </form>
    </div>

    <br>

    <?php if (isset($_POST['from']) && isset($_POST['to'])): ?>

        <?php $_SESSION['routes'] = Route::getFindRoutes($_POST['from'], $_POST['to']); ?>

        <?php Route::printResult(); ?>

    <?php endif; ?>


    <?php
        if (isset($_POST['sortByDistance'])) {
            Route::sortByDistance($_SESSION['routes']);
            Route::printResult();
        }

        if (isset($_POST['sortByTime'])) {
            Route::sortByTime($_SESSION['routes']);
            Route::printResult();
        }
    ?>

</div>

</body>
</html>