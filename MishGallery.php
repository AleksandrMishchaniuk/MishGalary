<?php
/*
Plugin Name: MishGallery
Plugin URI: https://github.com/AleksandrMishchaniuk/mishGalary.git
Description: Simple gallery for Wordpress
Version: 1.0.0
Author: Aleksandr Mishchaniuk
Author URI: https://github.com/AleksandrMishchaniuk
*/

/*  Copyright 2008  Jenyay  (email : jenyay.ilin {at} gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
namespace MishGallery;

define( 'MISHGALLERY_PLUGIN_NAME', 'MishGallery' );
define( 'MISHGALLERY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MISHGALLERY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MISHGALLERY_PAGE_NAME', 'mish_gallery' );
define( 'MISHGALLERY_DB_TABLE', 'wp_mish_gallery' );
define( 'MISHGALLERY_SHORTCODE', 'mish_gallery' );

spl_autoload_register(function($class_name){
    $class_name = str_replace(__NAMESPACE__.'\\', '', $class_name);
    $dirs = array('core', 'controller', 'model');
    foreach ($dirs as $dir){
        $path = MISHGALLERY_PLUGIN_DIR . $dir .'/'. $class_name .'.php';
        if(file_exists($path)){
            include_once $path;
            break;
        }
    }
});

if(is_admin()){
    App::run();
}
Plugin::run();

register_activation_hook( __FILE__, array( __NAMESPACE__.'\Plugin', 'install' ) );
register_uninstall_hook( __FILE__, array( __NAMESPACE__.'\Plugin', 'uninstall' ) );