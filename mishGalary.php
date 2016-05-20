<?php
/*
Plugin Name: mishGalary
Plugin URI: 
Description: Simple galary for Wordpress
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
namespace MishGalary;

define( 'MISHGALARY_PLUGIN_NAME', 'MishGalary' );
define( 'MISHGALARY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MISHGALARY_PAGE_NAME', 'mish_galary' );
define( 'MISHGALARY_DB_TABLE', 'wp_mish_galary' );
define( 'MISHGALARY_SHORTCODE', 'mish_galary' );

if(is_admin()){
    require_once( MISHGALARY_PLUGIN_DIR . 'lib/MishGalary.php' );
    $adminPage = new AdminPage();
    $adminPage->run();
}
