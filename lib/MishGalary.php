<?php


class MishGalary {
    
    public function run() {
        add_action( 'admin_enqueue_scripts', array($this, 'includeMedia'));
        add_action('admin_menu', array($this, 'addPage'));
    }
    
    public function includeMedia() {
        wp_enqueue_media();
    }
    
    public function addPage() {
        add_menu_page('MishGalary: Настройки', 'MishGalary', 8, 
                                MISHGALARY_PAGE_NAME, array($this, 'router'));
        
    }

    public function router() {
        $action = (isset($_GET['action']))? $_GET['action']: NULL;
        
        switch ($action) {
            case 'create':
                $this->createAction();
                break;
            
            case 'edite':
                
                break;
            
            default:
                $this->indexAction();
                break;
        }
    }
    
    public function indexAction() {
        include_once (MISHGALARY_PLUGIN_DIR.'view/index.php');
    }
    
    public function createAction() {
        include_once (MISHGALARY_PLUGIN_DIR.'view/create.php');
    }
    
}
