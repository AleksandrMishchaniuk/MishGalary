<?php

namespace MishGallery;

/**
 * Description of AdminPageController
 *
 * @author Aleksandr
 */
class AdminPageController {
    
    /**
     * Подготовка данных для отображения на главной странице плагина,
     * т.е. список галерей
     */
    public function indexAction() {
        return array(
            'params' => array(
                'galleries' => Gallery::getAll(),
            ),
            'view' => 'index.php',
        );
    }
    
    /**
     * Создание новой галереи.
     */
    public function createAction() {
        return array(
            'params' => array(
                'gallery' => new Gallery,
                'pagetype' => 'create',
            ),
            'view' => 'edit.php',
        );
    }
    
    /**
     * Редактирование галереи.
     */
    public function editAction() {
        if(isset($_GET['id'])){
            $id = (int) $_GET['id'];
            $gallery = Gallery::get($id);
            return array(
                'params' => array(
                    'gallery' => $gallery,
                    'pagetype' => 'edit',
                ),
                'view' => 'edit.php',
            );
        }
    }
    
    /**
     * Сохранение галереи.
     */
    public function saveAction() {
        if(isset($_POST['submit']) && $_POST['submit']==='true'){
            $data = $this->preparePost($_POST);
            $gallery = new Gallery;
            $gallery->exchangeArray($data);
            Gallery::save($gallery);
            
            $this->setNotice(
                    'Галерея сохранена',
                    'updated'
            );
            
            wp_redirect('?page='.MISHGALLERY_PAGE_NAME); die();
        }elseif (isset($_POST['submit']) && $_POST['submit']==='false') {
            wp_redirect('?page='.MISHGALLERY_PAGE_NAME); die();
        }
    }    
    
    /**
     * Удаление галереи.
     * Вызывается маршрутизатором
     */
    public function deleteAction() {
        if(isset($_GET['id'])){
            $id = (int) $_GET['id'];
            Gallery::delete($id);
            $this->setNotice(
                    'Галерея удалена',
                    'updated'
            );
            wp_redirect('?page='.MISHGALLERY_PAGE_NAME); die();
        }
    }
    
    protected function preparePost($post) {
        $data['id'] = isset($post['id'])? (int)$post['id']: NULL;
        $data['title'] = htmlentities($post['title']);
        $data['description'] = htmlentities($post['description']);
        $data['images'] = array();
        foreach ($post as $key => $value) {
            if(strstr($key, 'img_')){
                array_push($data['images'], $value);
            }
        }
        return $data;
    }
    
    protected function setNotice($text, $css_class) {
        $_SESSION['mish_gallery_notice'] = array(
            'text' => $text,
            'css_class' => $css_class,
        );
    }
}
