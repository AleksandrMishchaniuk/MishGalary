<?php
    $href_to_create = '?page='.MISHGALARY_PAGE_NAME.'&action=create';
?>
<h1><?= MISHGALARY_PLUGIN_NAME ?></h1>
<h2>Создание галереи</h2>
<hr/>

<form method="POST">
    <input type="text" name="title" placeholder="Название галереи"/><br/>
    <textarea style="width: 400px;" name="description" placeholder="Краткое описание"></textarea><br/>
    
    <div class="div_mg_imgs">
        
        <div class="div_mg_img" id="div_mg_img_template" style="display: none;">
            <div style="width: 200px; height: 200px">
                <img src="" style="max-height: 100%; max-width: 100%;"/>
            </div>
            <div class="div_mg_img_buttons">
                <button type="button" class="change_img">Изменить</button>
                <button type="button" class="delete_img">Удалить</button>
            </div>
            <input type="hidden"/>
        </div>
        
    </div>
    <div class="div_mg_buttons" style="clear: both;">
        <button type="button" class="add_img">Добавить изображение</button><br/>

        <button type="submit" name="submit" value="create">Создать</button>
        <button type="submit" name="submit" value="cancel">Отменить</button>
    </div>
</form>

<script>
jQuery(function($){
    var index = 1;
    $('.add_img').click(function(){
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        wp.media.editor.send.attachment = function(props, attachment){
            var div = $('#div_mg_img_template').clone();
            div.css({display: 'block'}).removeAttr('id').css({float: 'left'});
            $('img', div.get(0)).attr('src', attachment.url);
            $('[type="hidden"]', div.get(0)).val(attachment.id).attr('name', 'img_'+index);
            $('.div_mg_imgs').append(div);
            
            wp.media.editor.send.attachment = send_attachment_bkp;
        }
        
        wp.media.editor.open(button);
        index++;
        return false;
        
//        var send_attachment_bkp = wp.media.editor.send.attachment;
//        var button = $(this);
//        wp.media.editor.send.attachment = function(props, attachment) {
//            $(button).parent().prev().attr('src', attachment.url);
//            $(button).prev().val(attachment.id);
//            wp.media.editor.send.attachment = send_attachment_bkp;
//        }
//        wp.media.editor.open(button);
//        return false;    
    });
});
</script>