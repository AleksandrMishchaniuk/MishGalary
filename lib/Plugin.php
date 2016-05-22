<?php

namespace MishGallery;

class Plugin {
    
    protected $db;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
    }
    
    public function run() {
        add_shortcode(MISHGALLERY_SHORTCODE, array($this, 'showGallery'));
        add_action( 'wp_enqueue_scripts', array($this, 'includeScripts') );
        add_action( 'wp_enqueue_scripts', array($this, 'includeStyles') );
    }
    
    public function includeScripts() {
        wp_enqueue_script( 'galleria', MISHGALLERY_PLUGIN_URL.'galleria/galleria-1.4.2.min.js', array('jquery') );
        wp_enqueue_script( MISHGALLERY_PLUGIN_NAME.'_main', MISHGALLERY_PLUGIN_URL.'js/main.js', array('galleria') );
    }
    
    public function includeStyles() {
        wp_enqueue_style( MISHGALLERY_PLUGIN_NAME.'_plugin', MISHGALLERY_PLUGIN_URL.'css/plugin.css');
    }
    
    public function showGallery($param) {
        $id = (int) $param['id'];
        $gallery = $this->db->get_row($this->db->prepare(
                "SELECT * FROM ".MISHGALLERY_DB_TABLE. " WHERE id = %d;", $id),
                ARRAY_A
        );
        if($gallery){
            $gallery['images'] = unserialize($gallery['images']);
            foreach($gallery['images'] as $key => $val){
                $image_attributes = wp_get_attachment_image_src( $val, 'full' );
                if($image_attributes){
                    $gallery['images'][$key] = $image_attributes[0];
                }else{
                    unset($gallery['images'][$key]);
                }
            }
            ob_start();
                include (MISHGALLERY_PLUGIN_DIR.'view/gallery.php');
                $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
    }
    
}
