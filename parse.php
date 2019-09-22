<?php

use DiDom\Document;
use DiDom\Element;

define('BASE_DIR', dirname(__FILE__));

if (file_exists(BASE_DIR . '/core/autoload.php')) include_once BASE_DIR . '/core/autoload.php';

$document = new Document('https://www.rbc.ru/', true);

$links = [];
$i = 1;

foreach ($document->find('.js-news-feed-list')[0]->xpath('//a') as $link)
{
    $links[] = $link->getAttribute('href');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Парсим новости</title>
</head>
<body>
<div class="container py-5">
    <h1>Последние новости на сайте RBC.RU</h1>
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Название</th>
            <th scope="col">Ссылка</th>
            <th scope="col">Картинка</th>
            <th scope="col">Статус</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($links as $link): ?>
            <?php
            // Завершаем выполнение, если уже собрали 15 новостей
            if ($i > 15)
            {
                break;
            }

            $pdo = Core_Class::getPDO();

            // Ищем ссылку во вспомогательной таблице парсера
            $result = $pdo->prepare('SELECT content_id FROM parse WHERE link = :link');
            $result->execute(['link' => md5($link)]);

            // Если ссылка уже была ранее обработана, то забираем информацию о статье из таблицы со статьями
            if ($id = $result->fetchColumn())
            {
                $result = $pdo->prepare('SELECT * FROM content WHERE id = :id');
                $result->execute(['id' => $id]);

                $post = $result->fetch(PDO::FETCH_OBJ);
                $post->status = "Уже в базе";
            }
            // Иначе парсим данные со страницы
            else
            {
                $document = new Document($link, true);

                // Проверяем, есть ли на странице контент, который можно спарсить
                if (!$document->has('*[itemprop=articleBody]'))
                {
                    continue;
                }

                $post = new stdClass();
                $post->title = $document->find('*[itemprop=headline]')[0]->text();
                $post->date = $document->find('*[itemprop=datePublished]')[0]->getAttribute('content');

                $post->content = [];
                if ($document->has('.article__main-image__image'))
                {
                    $post->image = $document->find('.article__main-image__image')[0]->getAttribute('src');
                }
                if ($document->has('.article__text__overview'))
                {
                    if(trim($document->find('.article__text__overview')[0]->text()) != '')
                    {
                        $post->content[] = new Element('p', trim($document->find('.article__text__overview')[0]->text()));
                    }
                }
                if ($document->has('*[itemprop=author][itemtype=https://schema.org/Person]'))
                {
                    $post->author = $document->find('*[itemprop=author][itemtype=https://schema.org/Person]')[0]->getAttribute('content');
                }

                // В некоторых новостях последний абзац отделяется баннером и не входит в itemprop="articleBody"
                foreach ($document->find('.article__text') as $content)
                {
                    foreach ($content->xpath('//p') as $element)
                    {
                        if(trim($element->text()) != '')
                        {
                            $post->content[] = new Element('p', trim($element->text()));
                        }
                    }
                }

                $post->content = implode("\n", $post->content);

                $allowed = ["title", "content", "date", "image", "author"];
                $sql = "INSERT INTO content SET " . Core_Class::PDOSet($allowed, $values, (array)$post);
                $insert = $pdo->prepare($sql);

                if ($insert->execute($values))
                {
                    $parse = new stdClass();
                    $parse->link = md5($link);
                    $parse->content_id = $pdo->lastInsertId();

                    $allowed = ["content_id", "link"];
                    $sql = "INSERT INTO parse SET " . Core_Class::PDOSet($allowed, $values, (array)$parse);
                    $insert = $pdo->prepare($sql);

                    if ($insert->execute($values))
                    {
                        $post->status = "Добавлен в базу";
                    }
                    else
                    {
                        $post->status = "Не удалось добавить";
                    }
                }
                else
                {
                    $post->status = "Не удалось добавить";
                }
            }
            ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $post->title; ?></td>
                <td><?php echo $link; ?></td>
                <td style="width: 60px;"><?php if (isset($post->image)): ?><img src="<?php echo $post->image; ?>"
                                                                                class="rounded img-fluid"><?php endif; ?>
                </td>
                <td><?php echo $post->status; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>