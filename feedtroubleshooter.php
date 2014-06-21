<?php



//This will list the plugins.
$plugins=array();
foreach(glob('./*', GLOB_ONLYDIR) as $dir) {
    $dir = str_replace('./', '', $dir);
    array_push($plugins,$dir);
}
echo json_encode($plugins) . "\n\n";

echo "\n" . __DIR__ . "\n\n";




foreach($plugins as $plugin)
{
	
	echo $plugin . "\n";
	$dir = new DirectoryIterator(__DIR__ . "/" . $plugin . "/");

	
	foreach ($dir as $fileinfo) 
	{
		//print_r($fileinfo);
    	if (strpos($fileinfo->getFilename(),".php")) 
		{

       		if (findnewline($fileinfo->getpathName()))
       		{
	       		echo "Find newline in" . $fileinfo->getpathName() . ".\n";
       		}	
	   	}
	}
}


//checks for blank lines at the end of files. 

function findnewline($pluginfile)
{
	$file=file_get_contents($pluginfile);
	$lines = preg_split("(\\n|\\r)", $file, NULL, PREG_SPLIT_DELIM_CAPTURE);

	if ($lines[count($lines)-1]=="")
	{
		return true;
	}
	return false;
}




?>