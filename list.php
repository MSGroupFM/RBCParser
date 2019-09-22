<?php
define('BASE_DIR', dirname(__FILE__));
if (file_exists(BASE_DIR . '/core/autoload.php')) include_once BASE_DIR . '/core/autoload.php';

$pdo = Core_Class::getPDO();

$result = $pdo->query('SELECT * FROM content ORDER BY date DESC')->fetchAll(PDO::FETCH_OBJ | PDO::FETCH_UNIQUE);

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Список новостей</title>
</head>
<body>
<div class="container py-5">
    <h1>Новости с сайта RBC.RU</h1>
    <?php foreach ($result as $id => $post):?>
        <div class="card mb-4 shadow border-0">
            <?php if(isset($post->image)):?>
            <div class="row no-gutters">
                <div class="col-md-8">
                    <div class="card-body">
                        <a href="show.php?id=<?php echo $id;?>" class="text-dark"><h5 class="card-title"><?php echo $post->title;?></h5></a>
                        <span class="text-muted"><?php echo date('d.m.Y H:i', strtotime($post->date));?></span>
                        <p class="card-text"><?php echo mb_strimwidth(strip_tags($post->content), 0, 250, '...');?></p>
                        <a href="show.php?id=<?php echo $id;?>" class="btn btn-primary">Подробнее</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <a href="show.php?id=<?php echo $id;?>"><img class="card-img h-100" style="object-fit: cover;" src="<?php echo $post->image;?>"></a>
                </div>
            </div>
            <?php else:?>
                <div class="card-body">
                    <a href="show.php?id=<?php echo $id;?>" class="text-dark"><h5 class="card-title"><?php echo $post->title;?></h5></a>
                    <span class="text-muted"><?php echo date('d.m.Y H:i', strtotime($post->date));?></span>
                    <p class="card-text"><?php echo mb_strimwidth(strip_tags($post->content), 0, 200, '...');?></p>
                    <a href="show.php?id=<?php echo $id;?>" class="btn btn-primary">Подробнее</a>
                </div>
            <?php endif;?>
        </div>
    <?php endforeach;?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>