<?php
namespace MishGalary;

class AdminPage {
    
    protected $db;
    protected $render_page;
    protected $params;

    public function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->render_page = MISHGALARY_PLUGIN_DIR.'view/index.php';
        $this->params = array();
    }

    public function run() {
        add_action('init', array($this, 'router'));
        add_action('admin_menu', array($this, 'addPage'));
        add_action('admin_notices', array($this, 'showNotice'));
    }
            
    public function addPage() {
        $page_hook_suffix = add_menu_page('MishGalary: Настройки', 'MishGalary', 8, 
                                            MISHGALARY_PAGE_NAME, array($this, 'render'));
        add_action('admin_print_scripts-' . $page_hook_suffix, array($this, 'includeScripts'));
        
    }
    
    public function includeScripts() {
        wp_enqueue_media();
    }
    
    public function render() {
        extract($this->params);
        include_once ($this->render_page);
    }

    
    public function router() {
        if(isset($_GET['page']) && $_GET['page']===MISHGALARY_PAGE_NAME){
            $action = (isset($_GET['action']))? $_GET['action']: NULL;
            switch ($action) {
                case 'create':
                    $this->createAction();
                    break;
                case 'edit':
                    $this->editAction();
                    break;
                case 'delete':
                    $this->deleteAction();
                    break;
                default:
                    $this->indexAction();
            }
        }
    }
    
    
    public function indexAction() {
        $this->params['galaries'] = $this->db->get_results('SELECT id, title FROM '.MISHGALARY_DB_TABLE);
        $this->render_page = MISHGALARY_PLUGIN_DIR.'view/index.php';
    }
    
    
    public function createAction() {
        if(isset($_POST['submit']) && $_POST['submit']==='true'){
            $title = htmlentities($_POST['title']);
            $descr = htmlentities($_POST['description']);
            $images = array();
            foreach ($_POST as $key => $value) {
                if(strstr($key, 'img_')){
                    array_push($images, $value);
                }
            }
            
            $this->db->insert(
                MISHGALARY_DB_TABLE,
                array(
                    'title' => $title,
                    'description' => $descr,
                    'images' => serialize($images),
                ),
                array('%s', '%s', '%s')
            );
            
            $this->setNotice(
                    'Галерея добавлена',
                    'updated'
            );
            
            wp_redirect('?page='.MISHGALARY_PAGE_NAME);
        }elseif (isset($_POST['submit']) && $_POST['submit']==='false') {
            wp_redirect('?page='.MISHGALARY_PAGE_NAME);
        }
        $this->params['galary'] = array(
            'id' => 0,
            'title' => '',
            'description' => '',
            'images' => array(),
        );
        $this->params['pagetype'] = 'create';
        $this->render_page = MISHGALARY_PLUGIN_DIR.'view/edit.php';
    }
    
    
    public function editAction() {
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
            
            $this->db->update(
                MISHGALARY_DB_TABLE,
                array(
                    'title' => $title,
                    'description' => $descr,
                    'images' => serialize($images),
                ),
                array('id' => $id),
                array('%s', '%s', '%s'),
                array('%d')
            );
            
            $this->setNotice(
                    'Галерея обновлена',
                    'updated'
            );
            
            wp_redirect('?page='.MISHGALARY_PAGE_NAME);
        }elseif (isset($_POST['submit']) && $_POST['submit']==='false') {
            wp_redirect('?page='.MISHGALARY_PAGE_NAME);
        }
        
        if(isset($_GET['id'])){
            $id = (int) $_GET['id'];
            $galary = $this->db->get_row($this->db->prepare(
                    "SELECT * FROM ".MISHGALARY_DB_TABLE. " WHERE id = %d;", $id)
            );
            $galary->images = unserialize($galary->images);
            $this->params['galary'] = $galary;
            $this->params['pagetype'] = 'edit';
            $this->render_page = MISHGALARY_PLUGIN_DIR.'view/edit.php';
        }
    }
    
    public function deleteAction() {
        if(isset($_GET['id'])){
            $id = (int) $_GET['id'];
            $this->db->delete(
                MISHGALARY_DB_TABLE,
                array('id' => $id,),
                array('%d')
            );
            $this->setNotice(
                    'Галерея удалена',
                    'updated'
            );
            wp_redirect('?page='.MISHGALARY_PAGE_NAME);
        }
    }
    
    protected function setNotice($text, $css_class) {
        $_SESSION['mish_galary_notice'] = array(
            'text' => $text,
            'css_class' => $css_class,
        );
    }
    
    public function showNotice() {
        if(isset($_SESSION['mish_galary_notice'])){
            extract($_SESSION['mish_galary_notice']);
            unset($_SESSION['mish_galary_notice']);
            include_once (MISHGALARY_PLUGIN_DIR.'view/notice.php');
        }
    }
    
}