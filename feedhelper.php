<?php
/**
 * Plugin Name: Feed Helper
 * Plugin URI: http://jason.stallin.gs/projects/feedhelper/
 * Description: Finds newlines at the end of php files. 
 * Version: 0.1.0
 * Author: Jason Stallings
 * Author URI: http://jason.stallin.gs
 * License: A "Slug" license name e.g. GPL2
 */


add_action( 'wp_ajax_feedhelper_findnewlines', 'feedhelper_findnewlines' );

function feedhelper_findnewlines()
{
  
  echo loopThroughFiles();
  die();
}


	function loopThroughFiles()
	{
	  	$all_plugins = get_plugins();
		$plugins=array_keys($all_plugins);

	  	$plugin_dir = WP_CONTENT_DIR . '/plugins/';
	  	$found_lines="";
		foreach($plugins as $plugin)
		{ 
		  	$plugin_a=explode("/",$plugin);
		  	$plugin=$plugin_a[0];
			$dir = new RecursiveDirectoryIterator($plugin_dir . $plugin . "/");
			foreach (new RecursiveIteratorIterator($dir) as $filename => $file) 
			{

   				if (strpos($filename,".php")) 
				{
					if (findNewline($file->getpathName()))
					{
					  $found_lines = $found_lines . "Found newline at the end of /" . str_replace(ABSPATH,"",$file->getpathName()) . "<br>";
					}	
				}
			}	
		}
	  	return $found_lines;
	}

	function findNewline($pluginfile)
	{
		$data = file($pluginfile);
		$line = $data[count($data)-1];
	  
		if ($line=="\n" || $line=="\n\r")
		{
  			return true;
		}
		return false;
	}

// create custom plugin settings menu
add_action('admin_menu', 'feedhelper_create_menu');

function feedhelper_create_menu() {

	//create new top-level menu
	global $feedhelper_settings_page;
	$feedhelper_settings_page=add_submenu_page('tools.php', 'Feed Helper', 'Feed Helper', 'administrator', __FILE__, 'feedhelper_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_feedhelper_settings' );
}

function feedhelper_load_scripts($hook) 
{
 
	global $feedhelper_settings_page;
	if( $hook != $feedhelper_settings_page ) 
		return;

	wp_enqueue_script( 'feedhelper-js', plugins_url( '' , __FILE__ ) . '/js/feedhelper.js' , array( 'jquery'));
}
add_action('admin_enqueue_scripts', 'feedhelper_load_scripts');

function feedhelper_settings_page() 
{
?>
<div class="wrap">
<h2>Feed Helper</h2>
<p>
	Press start to identify newlines at the end of PHP files. <br>

  <div id="feedhelper_results"></div>
</p>
<input type="button" name="feedhelper_go" id="feedhelper_go" class="button button-primary" value="Start">
</div>
<?php } ?>