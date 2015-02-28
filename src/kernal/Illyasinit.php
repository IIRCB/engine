<?php 
/**
* Author: Najeem M Illyas

 * Its similar to bootstrap
 */
include 'Illyas.php';
$parts = $_SERVER['REQUEST_URI'];
if(DEBUG == true)
{
print "<u><strong>Debug Info:</u></strong> (You can turn this off in index.php)<br />";
print "DEBUG:". DEBUG."<br />";
print "MODULAR:". MODULAR."<br />";
print "ROUTES_ENABLED:". ROUTES_ENABLED."<br />";
print "<u>REQUEST_URI:".$parts."</u><br />";
}

if(ROUTES_ENABLED == true)
{
	include "/../../box/routes.php";
	$rout = Routes::aliases();
	if(DEBUG == true)
	{
	print "<u>All routes from routes.php files</u><br />";
	print "<pre>";
	print_r($rout);
	print "</pre>";
	}
	//print_r($_SERVER);
	$segment = ltrim(@$_SERVER['REDIRECT_QUERY_STRING'], '/');
	
	if(array_key_exists($segment, $rout))
	{
		$parts = $rout[$segment];
		$segments = explode("/", $parts);
		//echo $parts;
	}
	else
	{
		//check if part one alone existing.
		$seg = explode("/", $segment);
		if(DEBUG == true)
		{
		print "<u>REQUEST_URI Segments</u><br />";
		print "<pre>";
		print_r($seg);
		}
		if(!empty($segment))
		{
			if(array_key_exists(@$seg[0].'/'.@$seg[1], $rout))
			{
				$parts = $rout[$seg[0].'/'.$seg[1]];
				$segments = explode("/", $parts);
				//echo $parts; 
			}
			elseif(array_key_exists($seg[0], $rout))
			{
				$parts = $rout[$seg[0]];
				$segments = explode("/", $parts);
				//echo $parts; 
			}
			else
			{
				print "Requested page is not found. 404<br />";
				//exit;
			}
		}
		else
		{
			if(empty($rout['homepage']))
			{
				echo "You must set a default rout for your homepage<br />";
				exit;
			}
			$parts = @$rout['homepage'];
			$segments = explode("/", $parts);
			//echo $parts;
		}
		
		
	}
}
else
{

$seg = explode("/", $parts);
unset($seg[0]);
$segments = array_values($seg);
}
if(DEBUG == true)
{
print "<u>ACTUAL URI:".$parts."</u><br />";
}


if(!empty($segments))
{
	if(DEBUG == true)
	{
	print "<u>ACTUAL URI Segments</u><br />";
		
	print '<pre>';
	print_r($segments);
	print '</pre>';
	}
	if(MODULAR == true)
	{
	
		$module = $segments[0]?$segments[0]:'';
		$controller = $segments[1];
		$action = $segments[2]?$segments[2]:'index';
	}
	else
	{
		$module = '';
		$controller = $segments[0];
		$action = $segments[1]?$segments[1]:'index';
		
		
	}

	$class = ucfirst($controller).'Controller';

	function __autoload($class) 
	{
	global $module;
		
		if (preg_match("/Model/i", $class))
		{
			if(MODULAR == true)
			{
			$filename = "/../../box/modules/".$module."/model/".$class.".php";
			//print "<u>Loaded model:</u>". $filename.'<br />';
			}
			else
			{
		 	$filename = "/../../box/model/".$class.".php";
			}
				if(DEBUG == true)
				{
				print "<u>Loaded model:</u>". $filename.'<br />';
				}

		}
		elseif (preg_match("/Controller/i", $class))
		{
			if(MODULAR == true)
			{
			$filename = "/../../box/modules/".$module."/controller/".$class.".php";
			}
			else
			{
		 	$filename = "/../../box/controller/".$class.".php";
		 	}
				if(DEBUG == true)
				{
				print "<u>Loaded controller:</u>". $filename.'<br />';
				}

		}
		elseif (preg_match("/View/i", $class))
		{
			if(MODULAR == true)
			{
			$filename = "/../../box/modules/".$module."/view/".$class.".php";
			}
			else
			{
		 	$filename = "/../../box/view/".$class.".php";
			}
				if(DEBUG == true)
				{
				print "<u>Loaded view:</u>". $filename.'<br />';
				}
		}
	    include $filename;
	}
	$obj = new $class();

	$m1 = ucfirst($action).'Action';
	$obj->$m1();
				if(DEBUG == true)
				{
				print "<u>Loaded Action:</u>". $m1.'<br />';
				}
	
}

?>
