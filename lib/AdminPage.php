<?php
namespace MishGallery;

class AdminPage {
    
    protected static $db = NULL;
    protected static $render_page = MISHGALLERY_PLUGIN_DIR.'view/index.php';
    protected static $params = array();

    public static function getDb() {
        if(!self::$db){
            global $wpdb;
            self::$db = $wpdb;
        }
        return self::$db;
    }

    public static function run() {
        add_action('init', array(__CLASS__, 'router'));
        add_action('admin_menu', array(__CLASS__, 'addPage'));
        add_action('admin_notices', array(__CLASS__, 'showNotice'));
    }
    
    /**
     * Добавление страницы плагина в меню авминки
     */
    public static function addPage() {
        $page_hook_suffix = add_menu_page('MishGallery: Настройки', 'MishGallery', 8, 
                                            MISHGALLERY_PAGE_NAME, array(__CLASS__, 'render'));
        add_action('admin_print_scripts-' . $page_hook_suffix, array(__CLASS__, 'includeScripts'));
        add_action('admin_print_styles-' . $page_hook_suffix, array(__CLASS__, 'includeStyles'));
        
    }
    
    public static function includeScripts() {
        wp_enqueue_media();
    }
    
    public static function includeStyles() {
        wp_enqueue_style( MISHGALLERY_PLUGIN_NAME.'_admin_page', MISHGALLERY_PLUGIN_URL.'css/admin-page.css');
    }
    
    /**
     * Отображение страницы плагина в меню авминки
     */
    public static function render() {
        extract(self::$params);
        include_once (self::$render_page);
    }

    /**
     * Маршрутизатор
     * (выполняется во время добавления страницы в меню админки, НО перед ее отображением)
     */
    public static function router() {
        if(isset($_GET['page']) && $_GET['page']===MISHGALLERY_PAGE_NAME){
            $action = (isset($_GET['action']))? $_GET['action']: NULL;
            switch ($action) {
                case 'create':
                    self::createAction();
                    break;
                case 'edit':
                    self::editAction();
                    break;
                case 'delete':
                    self::deleteAction();
                    break;
                default:
                    self::indexAction();
            }
        }
    }
    
    /**
     * Подготовка данных для отображения на главной странице плагина,
     * т.е. список галерей
     * Вызывается маршрутизатором
     */
    public static function indexAction() {
        self::$params['galleries'] = self::getDb()->get_results('SELECT id, title FROM '.MISHGALLERY_DB_TABLE);
        self::$render_page = MISHGALLERY_PLUGIN_DIR.'view/index.php';
    }
    
    /**
     * Создание новой галереи.
     * Если в теле запроса нет данных для создания, то отображает страницу создания галереи.
     * Если данные есть, то обрабатывает их и перенаправляет на главную страницу
     * Вызывается маршрутизатором
     */
    public static function createAction() {
        if(isset($_POST['submit']) && $_POST['submit']==='true'){
            $title = htmlentities($_POST['title']);
            $descr = htmlentities($_POST['description']);
            $images = array();
            foreach ($_POST as $key => $value) {
                if(strstr($key, 'img_')){
                    array_push($images, $value);
                }
            }
            
            self::getDb()->insert(
                MISHGALLERY_DB_TABLE,
                array(
                    'title' => $title,
                    'description' => $descr,
                    'images' => serialize($images),
                ),
                array('%s', '%s', '%s')
            );
            
            self::setNotice(
                    'Галерея добавлена',
                    'updated'
            );
            
            wp_redirect('?page='.MISHGALLERY_PAGE_NAME);
        }elseif (isset($_POST['submit']) && $_POST['submit']==='false') {
            wp_redirect('?page='.MISHGALLERY_PAGE_NAME);
        }
        self::$params['gallery'] = array(
            'id' => 0,
            'title' => '',
            'description' => '',
            'images' => array(),
        );
        self::$params['pagetype'] = 'create';
        self::$render_page = MISHGALLERY_PLUGIN_DIR.'view/edit.php';
    }
    
    /**
     * Редактирование галереи.
     * Если в теле запроса нет данных для редактирования, то отображает страницу редактирования галереи.
     * Если данные есть, то обрабатывает их и перенаправляет на главную страницу
     * Вызывается маршрутизатором
     */
    public static function editAction() {
        if(isset($_POST['submit']) && $_POST['submit']==='true'){
            $id = (int) $_POST['id'];
            $title = htmlentities($_POST['title']);
            $descr = htmlentities($_POST['description']);
            $images = array();
            foreach ($_POST as $key => $value) {
                if(strstr($key, 'img_')){
                    array_push($images, $value);
                }
            }
            
            self::getDb()->update(
                MISHGALLERY_DB_TABLE,
                array(
                    'title' => $title,
                    'description' => $descr,
                    'images' => serialize($images),
                ),
                array('id' => $id),
                array('%s', '%s', '%s'),
                array('%d')
            );
            
            self::setNotice(
                    'Галерея обновлена',
                    'updated'
            );
            
            wp_redirect('?page='.MISHGALLERY_PAGE_NAME);
        }elseif (isset($_POST['submit']) && $_POST['submit']==='false') {
            wp_redirect('?page='.MISHGALLERY_PAGE_NAME);
        }
        
        if(isset($_GET['id'])){
            $id = (int) $_GET['id'];
            $gallery = self::getDb()->get_row(self::getDb()->prepare(
                    "SELECT * FROM ".MISHGALLERY_DB_TABLE. " WHERE id = %d;", $id)
            );
            $gallery->images = unserialize($gallery->images);
            self::$params['gallery'] = $gallery;
            self::$params['pagetype'] = 'edit';
            self::$render_page = MISHGALLERY_PLUGIN_DIR.'view/edit.php';
        }
    }
    
    /**
     * Удаление галереи.
     * Вызывается маршрутизатором
     */
    public static function deleteAction() {
        if(isset($_GET['id'])){
            $id = (int) $_GET['id'];
            self::getDb()->delete(
                MISHGALLERY_DB_TABLE,
                array('id' => $id,),
                array('%d')
            );
            self::setNotice(
                    'Галерея удалена',
                    'updated'
            );
            wp_redirect('?page='.MISHGALLERY_PAGE_NAME);
        }
    }
    
    
    /**
     * Следующие методы служат для передачи сообщений между страницами.
     * Оба варианта (через сессию и через настройки WP) не срабатывают.
     * Причина: перед редиректом выполняется метод showNotice() и сообщение затирается
     */
    
    
//    protected function setNotice($text, $css_class) {
//        $notice = serialize(array(
//            'text' => $text,
//            'css_class' => $css_class,
//        ));
//        update_option(MISHGALLERY_PAGE_NAME.'_notice', $notice);
//    }
//    
//    public function showNotice() {
//        $notice = get_option(MISHGALLERY_PAGE_NAME.'_notice');
//        if(!empty($notice)){
//            $notice = unserialize($notice);
//            extract($notice);
//            update_option(MISHGALLERY_PAGE_NAME.'_notice', '');
//            include_once (MISHGALLERY_PLUGIN_DIR.'view/notice.php');
//        }
//    }
    
    protected static function setNotice($text, $css_class) {
        $_SESSION['mish_gallery_notice'] = array(
            'text' => $text,
            'css_class' => $css_class,
        );
    }
    
    public static function showNotice() {
        if(isset($_SESSION['mish_gallery_notice'])){
            extract($_SESSION['mish_gallery_notice']);
            unset($_SESSION['mish_gallery_notice']);
            include_once (MISHGALLERY_PLUGIN_DIR.'view/notice.php');
        }
    }
    
}
