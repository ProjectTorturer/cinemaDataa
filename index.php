<?php
//Подключение DLL
require(__DIR__ . '/function/connection.php');
$groupCinema = R::getAll('SELECT * FROM groupcinema');
echo false;
//var_dump($memcache->get("dataCinema")) ;
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Cinema</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?t=<?php echo(microtime(true) . rand()); ?>">
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
</head>

<body>

<div>
<script src="/JS/tableUpdate.js"></script>
    <h3>
        <?php
        $i = 1;
        foreach ($groupCinema as $key => $item) {
            echo "<a Class=Car{$i}> {$item['group_name']}</a>" . (!empty($groupCinema[++$key])? " / " : " ");
            $i++;
        }
        ?>
    </h3>
    <?php require(__DIR__ . '/forms/tableForm.php'); ?>
</div>
</body>
</html>