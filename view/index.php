<?php
    $href_to_create = '?page='.MISHGALLERY_PAGE_NAME.'&action=create';
    $href_to_edit = '?page='.MISHGALLERY_PAGE_NAME.'&action=edit&id=';
    $href_to_delete = '?page='.MISHGALLERY_PAGE_NAME.'&action=delete&id=';
?>

<h1><?= MISHGALLERY_PLUGIN_NAME ?></h1>
<strong><a href="<?= $href_to_create ?>">Создать новую галерею</a></strong>
<h2>Доступные галереи</h2>
<hr/>
<br/>
<table>
    <?php foreach ($galaries as $gallery): ?>
    <tr>
        <td>
            <?= $gallery->id; ?>
        </td>
        <td>
            <?= $gallery->title; ?>
        </td>
        <td>
            <?= '['.MISHGALLERY_SHORTCODE.' id='.$gallery->id.']'; ?>
        </td>
        <td>
            <a href="<?= $href_to_edit.$gallery->id ?>">Редактировать</a>
            <a href="<?= $href_to_delete.$gallery->id ?>">Удалить</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>