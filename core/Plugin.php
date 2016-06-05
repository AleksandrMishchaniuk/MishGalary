<?php

namespace MishGallery;

class Plugin {
    
    public static function run() {
        add_shortcode(MISHGALLERY_SHORTCODE, array(__CLASS__, 'showGallery'));
        add_action( 'wp_enqueue_scripts', array(__CLASS__, 'includeScripts') );
        add_action( 'wp_enqueue_scripts', array(__CLASS__, 'includeStyles') );
    }
    
    public static function install() {
        $query = "CREATE TABLE IF NOT EXISTS `".MISHGALLERY_DB_TABLE."` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `title` varchar(255) NOT NULL,
                    `description` text NOT NULL,
                    `images` text NOT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        self::getDb()->query($query);
//        add_option(MISHGALLERY_PAGE_NAME.'_notice');
    }
    
    public static function uninstall() {
        $query = "DROP TABLE IF EXISTS `".MISHGALLERY_DB_TABLE."`;";
        self::getDb()->query($query);
//        delete_option(MISHGALLERY_PAGE_NAME.'_notice');
    }

    public static function includeScripts() {
        wp_enqueue_script( 'galleria', MISHGALLERY_PLUGIN_URL.'public/galleria/galleria-1.4.2.min.js', array('jquery') );
        wp_enqueue_script( MISHGALLERY_PLUGIN_NAME.'_main', MISHGALLERY_PLUGIN_URL.'public/js/main.js', array('galleria') );
    }
    
    public static function includeStyles() {
        wp_enqueue_style( MISHGALLERY_PLUGIN_NAME.'_plugin', MISHGALLERY_PLUGIN_URL.'public/css/plugin.css');
    }
    
    /**
     * Обработчик шорткода
     * @param array $param  парасетры из шорткода
     * @return type
     */
    public static function showGallery($param) {
        $id = (int) $param['id'];
        $gallery = Gallery::get($id);
        if( $gallery->id ){
            ob_start();
                include (MISHGALLERY_PLUGIN_DIR.'view/gallery.php');
                $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
    }
    
}
