<?php
define('BASE_DIR', dirname(__FILE__));
if (file_exists(BASE_DIR . '/core/autoload.php')) include_once BASE_DIR . '/core/autoload.php';

$id = intval($_GET['id']);
$pdo = Core_Class::getPDO();

$result = $pdo->prepare('SELECT * FROM content WHERE id = :id');
$result->execute(['id' => $id]);

$post = $result->fetch(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title><?php echo $post->title;?></title>
</head>
<body>
<div class="container py-5">
    <?php if(isset($post->image)):?><img class="img-thumbnail img-fluid mb-3 w-100" src="<?php echo $post->image;?>"><?php endif;?>

    <h1><?php echo $post->title;?></h1>
    <div class="mb-2">Опубликовано: <span class="text-muted"><?php echo date('d.m.Y H:i', strtotime($post->date));?></span></div>

    <?php echo $post->content;?>
    <?php if(isset($post->author)):?><div>Автор: <span class="text-muted"><?php echo $post->author;?></span></div><?php endif;?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>