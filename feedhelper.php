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



class FeedHelper
{
	private $plugins=array();

	//Going to switch to use WordPress function get_plugins(); 
	private function getPlugins()
	{
		foreach(glob('./*', GLOB_ONLYDIR) as $dir) 
		{
			$dir = str_replace('./', '', $dir);
			array_push($this->$plugins,$dir);
		}
	}
	
	public function loopThroughFiles()
	{
		foreach($plugins as $plugin)
		{
			$dir = new DirectoryIterator(__DIR__ . "/" . $plugin . "/");
			foreach ($dir as $fileinfo) 
			{
				if (strpos($fileinfo->getFilename(),".php")) 
				{
					if (findNewline($fileinfo->getpathName()))
					{
						echo "Found newline in" . $fileinfo->getpathName() . ".\n";
					}	
				}
			}
		}	
	}
	
	protected function findNewline($pluginfile)
	{
		$file=file_get_contents($pluginfile);
		$lines = preg_split("(\\n|\\r)", $file, NULL, PREG_SPLIT_DELIM_CAPTURE);

		if ($lines[count($lines)-1]=="")
		{
			return true;
		}
		return false;
	}

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

function feedhelper_load_scripts($hook) {
 
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
	Press start to identify newlines at the end of PHP files. 
	<?php
	$all_plugins = get_plugins();
	print_r( $all_plugins, true ) 
	?>
</p>
<input type="button" name="feedhelper_go" id="feedhelper_go" class="button button-primary" value="Start">
</div>
<?php } ?>
