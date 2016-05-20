<?php
    $href_to_create = '?page='.MISHGALARY_PAGE_NAME.'&action=create';
    $href_to_edit = '?page='.MISHGALARY_PAGE_NAME.'&action=edit&id=';
    $href_to_delete = '?page='.MISHGALARY_PAGE_NAME.'&action=delete&id=';
?>

<h1><?= MISHGALARY_PLUGIN_NAME ?></h1>
<strong><a href="<?= $href_to_create ?>">Создать новую галерею</a></strong>
<h2>Доступные галереи</h2>
<hr/>
<br/>
<table>
    <?php foreach ($galaries as $galary): ?>
    <tr>
        <td>
            <?= $galary->id; ?>
        </td>
        <td>
            <?= $galary->title; ?>
        </td>
        <td>
            <?= '['.MISHGALARY_SHORTCODE.' id='.$galary->id.']'; ?>
        </td>
        <td>
            <a href="<?= $href_to_edit.$galary->id ?>">Редактировать</a>
            <a href="<?= $href_to_delete.$galary->id ?>">Удалить</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>