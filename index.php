<?php
/*
Plugin Name: Dog Name Generator
Plugin URI: https://github.com/jiggawattjoseph/dng
Description: Generates dog names using form-based user input
Version: 1.0
Author: Joseph Bengtson
Author URI: http://www.abilitymultimedia.com
*/

/*  
Copyright 2016  Joseph Bengtson  (email : joseph@abilitymultimedia.com)

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
 
// function to create the database	

				
function create_dng_database() {
	global $wpdb;
	global $dng_db;  	
 
 	$dng_db = $wpdb->prefix . 'dng_db';
 
	// Create database table
	if($wpdb->get_var("SHOW TABLES LIKE " . $dng_db) != $dng_db) 
	{
		$sql = "CREATE TABLE " . $dng_db . " (
		`id` mediumint(9) NOT NULL AUTO_INCREMENT,
		`gender` mediumtext NOT NULL,
		`pedigree` mediumtext NOT NULL,
		`size` mediumtext NOT NULL,
		`color` mediumtext NOT NULL,
		`characteristic` mediumtext NOT NULL,
		`company` mediumtext NOT NULL,
		`activity` mediumtext NOT NULL,
		`name` mediumtext NOT NULL,
		UNIQUE KEY (id)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
	
	// Import CSV data file to populate database table
	$sql_query = "LOAD DATA LOCAL INFILE '".WP_PLUGIN_DIR."/dng/namedata.csv' 
	 	INTO TABLE ". $dng_db ."  
		FIELDS TERMINATED BY ',' 
        LINES TERMINATED BY '\n'
		(id, gender, pedigree, size, color, characteristic, company, activity, name)";
	
	$wpdb->query($sql_query);
	
			 
}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__,'create_dng_database');

function dng_ui() { ?>
	<form action='<?php  (esc_attr($_SERVER['REQUEST_URI'])) ?>' method='post'>
        <table width="350" border="0" cellspacing="3" cellpadding="2">
          <tr>
            <td>Gender:</td>
            <td>
                <select name="gender">
                    <option>Female</option>
                    <option>Male</option>	
                </select>
            </td>
          </tr>
          <tr>
            <td>Pedigree:</td>
            <td>
                <select name="pedigree">
                    <option>Mutt</option>
                    <option>Purebred</option>	
                </select>
            </td>
          </tr>
          <tr>
            <td>Size:</td>
            <td>
                <select name="size">
                    <option>Large</option>
                    <option>Medium</option>
                    <option>Small</option>		
                </select>
            </td>
          </tr>
          <tr>
            <td>Color:</td>
            <td>
                <select name="color">
                    <option>Black</option>
                    <option>Brown</option>
                    <option>Red</option>
                    <option>White</option>		
                </select>
            </td>
          </tr>
          <tr>
            <td>Notable Characteristic:</td>
            <td>
                <select name="characteristic">
                    <option>Four legs/tail</option>
                    <option>Giant ears</option>
                    <option>Tiny head</option>
                    <option>Underbite</option>		
                </select>
            </td>
          </tr>
          <tr>
            <td>Preferred Company:</td>
            <td>
                <select name="company">
                    <option>Dogs</option>
                    <option>People</option>		
                </select>
            </td>
          </tr>
          <tr>
            <td>Favorite Activity:</td>
            <td>
                <select name="activity">
                    <option>Fetching a ball</option>
                    <option>Gnawing on chew toy</option>
                    <option>Sleeping</option>
                    <option>Chasing other dogs</option>		
                </select>
            </td>
          </tr>
          <tr>
            <td><input type="submit"></form></td>
            <td></td>
          </tr>
          </table><?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		global $wpdb;
		$results = $wpdb->get_var( "SELECT name FROM `wp_dng_db` WHERE 
			gender = '".($_POST['gender'])."' AND
			pedigree = '".($_POST['pedigree'])."' AND
			size = '".($_POST['size'])."' AND
			color = '".($_POST['color'])."' AND
			characteristic = '".($_POST['characteristic'])."' AND
			company = '".($_POST['company'])."' AND
			activity = '".($_POST['activity'])."';");

		echo ("<tr><td>Name Suggestion:</td>");
        echo ("<td><div id='output'> <strong>". $results ."</strong></div></td>");
       	echo ("</tr></table>");
	}
	
}

add_shortcode('dng', 'dng_ui');
?>