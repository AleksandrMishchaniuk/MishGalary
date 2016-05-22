<?php
/*
Plugin Name: MishGallery
Plugin URI: 
Description: Simple gallery for Wordpress
Version: 1.0.0
Author: Aleksandr Mishchaniuk
Author URI: 
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

if(is_admin()){
    require_once( MISHGALLERY_PLUGIN_DIR . 'lib/AdminPage.php' );
    $mishGallery_adminPage = new AdminPage();
    $mishGallery_adminPage->run();
}
require_once( MISHGALLERY_PLUGIN_DIR . 'lib/Plugin.php' );
$mishGallery_plugin = new Plugin();
$mishGallery_plugin->run();