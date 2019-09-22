<?php
define('BASE_DIR', dirname(__FILE__));
if (file_exists(BASE_DIR . '/core/autoload.php')) include_once BASE_DIR . '/core/autoload.php';

$tables = [
        "content" => 'CREATE TABLE `content` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(255) NOT NULL,
 `content` text NOT NULL,
 `date` datetime DEFAULT NULL,
 `image` varchar(255) DEFAULT NULL,
 `author` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8',
        "parse" => 'CREATE TABLE `parse` (
 `content_id` int(11) NOT NULL,
 `link` varchar(40) NOT NULL,
 `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8'
];

$pdo = Core_Class::getPDO();
$result = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_UNIQUE);
$i = 1;

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Настройка БД</title>
</head>
<body>
<div class="container py-5">
    <h1>Настройка Базы данных</h1>

    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Таблица</th>
            <th scope="col">Статус</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tables as $table => $sql):?>
            <?php
            if(array_key_exists($table, $result))
            {
                $status = "Таблица уже есть";
            }
            else
            {
                $pdo->query($sql);
                $status = "Создали таблицу";
            }
            ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $table;?></td>
                <td><?php echo $status;?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>