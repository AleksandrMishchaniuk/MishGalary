<?php

namespace MishGalary;

class Plugin {
    
    protected $db;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
    }
    
    public function run() {
        add_shortcode(MISHGALARY_SHORTCODE, array($this, 'showGalary'));
        add_action( 'wp_enqueue_scripts', array($this, 'includeScripts') );
    }
    
    public function includeScripts() {
        wp_enqueue_script( 'galleria', MISHGALARY_PLUGIN_URL.'galleria/galleria-1.4.2.min.js', array('jquery') );
        wp_enqueue_script( 'myscript', MISHGALARY_PLUGIN_URL.'js/main.js', array('galleria') );
    }
    
    public function showGalary($param) {
        $id = (int) $param['id'];
        $galary = $this->db->get_row($this->db->prepare(
                "SELECT * FROM ".MISHGALARY_DB_TABLE. " WHERE id = %d;", $id),
                ARRAY_A
        );
        if($galary){
            $galary['images'] = unserialize($galary['images']);
            foreach($galary['images'] as $key => $val){
                $image_attributes = wp_get_attachment_image_src( $val );
                $galary['images'][$key] = $image_attributes[0];
            }
            ob_start();
                include (MISHGALARY_PLUGIN_DIR.'view/galary.php');
                $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
    }
    
}
