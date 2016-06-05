<?php
namespace MishGallery;

class App {
    
    protected static $render_page = MISHGALLERY_PLUGIN_DIR.'view/index.php';
    protected static $params = array();

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
        wp_enqueue_style( MISHGALLERY_PLUGIN_NAME.'_admin_page', MISHGALLERY_PLUGIN_URL.'public/css/admin-page.css');
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
            $controller = new AdminPageController;
            $action_name = (isset($_GET['action']))? $_GET['action']: 'index';
            $action = $action_name . 'Action';
            $result = $controller->$action();
            if(isset($result['view'])){
                self::$render_page = MISHGALLERY_PLUGIN_DIR.'view/'.$result['view'];
            }
            if(isset($result['params'])){
                self::$params = $result['params'];
            }
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
    
    public static function showNotice() {
        if(isset($_SESSION['mish_gallery_notice'])){
            extract($_SESSION['mish_gallery_notice']);
            unset($_SESSION['mish_gallery_notice']);
            include_once (MISHGALLERY_PLUGIN_DIR.'view/notice.php');
        }
    }
    
}
