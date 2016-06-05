<?php

namespace MishGallery;

/**
 * Description of Gallery
 *
 * @author Aleksandr
 */
class Gallery {
    protected static $db = NULL;
    protected static $table = MISHGALLERY_DB_TABLE;
    public $id;
    public $title;
    public $description;
    public $images;
    public $img_src;

    public function __construct() {
        $this->id = 0;
        $this->title = '';
        $this->description = '';
        $this->images = array();
        $this->images_src = array();
    }
    
    protected static function getDb() {
        if(!self::$db){
            global $wpdb;
            self::$db = $wpdb;
        }
        return self::$db;
    }
    
    public static function get($id) {
        $row = self::getDb()->get_row(self::getDb()->prepare(
                "SELECT * FROM ".self::$table. " WHERE id = %d;", $id),
                ARRAY_A
        );
        $gallery = new self;
        if($row){
            $row['images'] = unserialize($row['images']);
            $gallery->exchangeArray($row);
        }
        return $gallery;
    }
    
    public static function getAll() {
        $rows = self::getDb()->get_results('SELECT * FROM '.self::$table, ARRAY_A);
        $galleries = array();
        foreach($rows as $row){
            $row['images'] = unserialize($row['images']);
            $gallery = new self;
            $galleries[] = $gallery->exchangeArray($row);
        }
        return $galleries;
    }
    
    public static function save(\MishGallery\Gallery $gallery) {
        $data = array(
            'title' => $gallery->title,
            'description' => $gallery->description,
            'images' => serialize($gallery->images),
        );
        if((int)$gallery->id){
            self::getDb()->update(
                self::$table,
                $data,
                array('id' => (int)$gallery->id),
                array('%s', '%s', '%s'),
                array('%d')
            );
        }else{
            self::getDb()->insert(
                self::$table,
                $data,
                array('%s', '%s', '%s')
            );
        }
    }
    
    public static function delete($id) {
        self::getDb()->delete(
            self::$table,
            array('id' => $id),
            array('%d')
        );
    }
    
    public function exchangeArray($row) {
        $this->id = isset($row['id'])? ($row['id']): $this->id;
        $this->title = isset($row['title'])? $row['title']: $this->title;
        $this->description = isset($row['description'])? $row['description']: $this->description;
        if(isset($row['images']) && $row['images']){
            $this->images = $row['images'];
            foreach($this->images as $image_id){
                $image_attributes = wp_get_attachment_image_src( $image_id, 'full' );
                $src = $image_attributes[0];
                if(!$src){
                    $src = MISHGALLERY_PLUGIN_URL.'img/default.gif';
                }
                $this->images_src[] = $src;
            }
        }
        return $this;
    }
    
}
