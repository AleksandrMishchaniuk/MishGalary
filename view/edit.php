<?php
    $submit_btn = ($pagetype === 'edit')? 'Сохранить': 'Создать';
    $header_text = ($pagetype === 'edit')? 'Редактирование': 'Создание';
    $index = 0;
?>
<h1><?= MISHGALLERY_PLUGIN_NAME ?></h1>
<h2><?= $header_text ?> галереи</h2>
<hr/>

<form method="POST" action="?page=mish_gallery&action=save">
    <label for="title"><strong>Название галереи</strong></label><br/>
    <input type="text" name="title" placeholder="Название галереи" value="<?= $gallery->title; ?>"/><br/>
    <label for="description"><strong>Описание галереи</strong></label><br/>
    <textarea name="description" placeholder="Краткое описание"><?= $gallery->description; ?></textarea><br/>
    
    <button type="button" class="add_img">Добавить изображение</button><br/>
    
    <div class="mishGallery_admin_page_images_div">
        
        <div class="mishGallery_admin_page_image_div" id="div_mg_img_template" style="display: none;">
            <div class="mishGallery_admin_page_image_wrap">
                <img src="" style="max-height: 100%; max-width: 100%;" class="mishGallery_admin_page_image"/>
            </div>
            <div class="mishGallery_admin_page_image_buttons">
                <button type="button" class="change_img">Изменить</button>
                <button type="button" class="delete_img">Удалить</button>
            </div>
            <input type="hidden"/>
        </div>
        
        <?php if(!empty($gallery->images)): ?>
            <?php for($i=0; $i<count($gallery->images); $i++): ?>
                <div class="mishGallery_admin_page_image_div">
                    <div class="mishGallery_admin_page_image_wrap">
                        <img src="<?= $gallery->images_src[$i]; ?>" class="mishGallery_admin_page_image"/>
                    </div>
                    <div class="mishGallery_admin_page_image_buttons">
                        <button type="button" class="change_img">Изменить</button>
                        <button type="button" class="delete_img">Удалить</button>
                    </div>
                    <input type="hidden" name="img_<?= $index; ?>" value="<?= $gallery->images[$i]; ?>"/>
                </div>
                <?php $index++; ?>
            <?php endfor; ?>
        <?php endif; ?>
        
    </div>
    <div class="mishGallery_admin_page_buttons" style="clear: both;">
        <button type="button" class="add_img">Добавить изображение</button><br/>
        <br/>
        <button type="submit" name="submit" value="true"><?= $submit_btn; ?></button>
        <button type="submit" name="submit" value="false">Отменить</button>
        <?php if($pagetype === 'edit'): ?>
            <input type="hidden" name="id" value="<?= $gallery->id; ?>"/>
        <?php endif; ?>
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
            $('.mishGallery_admin_page_images_div').append(div);
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
            var div = button.parents('.mishGallery_admin_page_image_div');
            $('img', div.get(0)).attr('src', attachment.url);
            $('[type="hidden"]', div.get(0)).val(attachment.id);
            wp.media.editor.send.attachment = send_attachment_bkp;
        }
        wp.media.editor.open(button);
    };
    
    function deleteImgAction(){
        $(this).parents('.mishGallery_admin_page_image_div').remove();
    }
});
</script>