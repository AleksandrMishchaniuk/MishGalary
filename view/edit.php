<?php
    $submit_btn = ($pagetype === 'edit')? 'Сохранить': 'Создать';
    $header_text = ($pagetype === 'edit')? 'Редактирование': 'Создание';
    $index = 0;
?>
<h1><?= MISHGALARY_PLUGIN_NAME ?></h1>
<h2><?= $header_text ?> галереи</h2>
<hr/>

<form method="POST">
    <input type="text" name="title" placeholder="Название галереи" value="<?= $galary->title; ?>"/><br/>
    <textarea style="width: 400px;" name="description" placeholder="Краткое описание"><?= $galary->description; ?></textarea><br/>
    
    <div class="div_mg_imgs">
        
        <div class="div_mg_img" id="div_mg_img_template" style="display: none; float: left;">
            <div style="width: 200px; height: 200px">
                <img src="" style="max-height: 100%; max-width: 100%;"/>
            </div>
            <div class="div_mg_img_buttons">
                <button type="button" class="change_img">Изменить</button>
                <button type="button" class="delete_img">Удалить</button>
            </div>
            <input type="hidden"/>
        </div>
        
        <?php if(!empty($galary->images)): ?>
            <?php foreach($galary->images as $image_id): ?>
                <?php
                    $image_attributes = wp_get_attachment_image_src( $image_id );
                    $src = $image_attributes[0];
                ?>
                <div class="div_mg_img" style="float: left;">
                    <div style="width: 200px; height: 200px">
                        <img src="<?= $src; ?>" style="max-height: 100%; max-width: 100%;"/>
                    </div>
                    <div class="div_mg_img_buttons">
                        <button type="button" class="change_img">Изменить</button>
                        <button type="button" class="delete_img">Удалить</button>
                    </div>
                    <input type="hidden" name="img_<?= $index; ?>" value="<?= $image_id; ?>"/>
                </div>
                <?php $index++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        
    </div>
    <div class="div_mg_buttons" style="clear: both;">
        <button type="button" class="add_img">Добавить изображение</button><br/>

        <button type="submit" name="submit" value="true"><?= $submit_btn; ?></button>
        <button type="submit" name="submit" value="false">Отменить</button>
    </div>
</form>

<script>
jQuery(function($){
    var index = +<?= $index; ?>;
    $('.add_img').click(addImgAction);
    $('.change_img').click(changeImgAction);
    $('.delete_img').click(deleteImgAction);
    
    function addImgAction(){
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        wp.media.editor.send.attachment = function(props, attachment){
            var div = $('#div_mg_img_template').clone();
            div.css({display: 'block'}).removeAttr('id');
            $('img', div.get(0)).attr('src', attachment.url);
            $('[type="hidden"]', div.get(0)).val(attachment.id).attr('name', 'img_'+index);
            $('.change_img', div.get(0)).click(changeImgAction);
            $('.delete_img', div.get(0)).click(deleteImgAction);
            $('.div_mg_imgs').append(div);
            wp.media.editor.send.attachment = send_attachment_bkp;
        }
        wp.media.editor.open(button);
        index++;
        return false;
    }
    
    function changeImgAction(){
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        wp.media.editor.send.attachment = function(props, attachment){
            var div = button.parents('.div_mg_img');
            $('img', div.get(0)).attr('src', attachment.url);
            $('[type="hidden"]', div.get(0)).val(attachment.id);
            wp.media.editor.send.attachment = send_attachment_bkp;
        }
        wp.media.editor.open(button);
    };
    
    function deleteImgAction(){
        $(this).parents('.div_mg_img').remove();
    }
});
</script>