<?php
/**
 * @package db_size
 * @author Chris Rabiet
 * @version 1.0
 */
/*
Plugin Name: DB Size
Plugin URI: http://wordpress.org/extend/plugins/db-size/
Description: This simple plugin just shows your DataBase size under the header of the admin control panel.
Author: Chris Rabiet
Version: 1.0
Author URI: http://rabiet.fr/
*/

//  Alert level. Database size is shown in red if greater than this value, else in green.
//  You can adjust this value (in MB) to your conveniance  in the line below. 
//  Default value is : 4 MB.

define("alertlevel",4);

// Size Categories
function file_size_info($filesize) {
	$bytes = array('KB', 'KB', 'MB', 'GB', 'TB');

# values are always displayed
	if ($filesize < 1024) $filesize = 1;

# in at least kilobytes.
	for ($i = 0; $filesize > 1024; $i++) $filesize /= 1024;

	$file_size_info['size'] = round($filesize,3);

	$file_size_info['type'] = $bytes[$i];

return $file_size_info; } 

// Calculate DB size by adding table size + index size:
// This just echoes the db size, we'll position it later

function db_size(){
	
	$rows = mysql_query("SHOW table STATUS"); $dbsize = 0;

	while ($row = mysql_fetch_array($rows)) 
		{$dbsize += $row['Data_length'] + $row['Index_length']; } 
	
	if ($dbsize > alertlevel * 1024 * 1024) {
	$color = "red";}
	else {
	$color = "green";}
		$dbsize = file_size_info($dbsize); 
		echo "<p id='dbsize'><font color=$color>DataBase size is: {$dbsize ['size']} {$dbsize['type']}.</font></p>"; 
}

// Set that function up to execute when the admin_footer action is called
add_action('admin_footer', 'db_size');

//  CSS to position the paragraph

function db_size_css() {
		echo "
		<style type='text/css'>
		#dbsize {
		position: absolute;
		top: 4.5em;
		margin: 0;
		padding: 0;
		left: 225px;
		font-size: 11px;
		}
		</style>
		";	
}

add_action('admin_head', 'db_size_css');

?>
