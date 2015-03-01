<?php namespace Kernal;
/**
* Author: Najeem M Illyas

  */
use Box;
use Box\Controller;
class Illiyasinit
{ 
    function __construct()
    {
        echo "Illiyasinit";
        //include 'Illyas.php';
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
            //include "/../../box/routes.php";
            $r = new Box\Routes();

            $rout = $r->aliases();
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
                    echo "Module name - ".$module;
                    $controller = $segments[1];
                    $action = $segments[2]?$segments[2]:'index';
            }
            else
            {
                    $module = '';
                    $controller = $segments[0];
                    $action = $segments[1]?$segments[1]:'index';


            }

            $class = ucfirst($controller).'controller';

                //function __autoload($class) 
                //{
           // global $module;
            $module = ucfirst($module);

            if (preg_match("/Model/i", $class))
            {
                    if(MODULAR == true)
                    {
                    //$filename = "/../../box/modules/".$module."/model/".$class.".php";
                    $myclass = "Box\\Modules\\".$module."\\Model\\".$class;
                    //new $myclass;
                    print "<u>Loaded model:</u>". $myclass.'<br />';
                    }
                    else
                    {
                    //$filename = "/../../box/model/".$class.".php";
                    $myclass = "Box\\Model\\".$class;    
                    }
                            if(DEBUG == true)
                            {
                            print "<u>Loaded model:</u>". $myclass.'<br />';
                            }

            }
            elseif (preg_match("/Controller/i", $class))
            {
                    if(MODULAR == true)
                    {
                    //$filename = "/../../box/modules/".$module."/controller/".$class.".php";
                    $myclass = "Box\\Modules\\".$module."\\Controller\\".$class;
                    }
                    else
                    {
                    //$filename = "/../../box/controller/".$class.".php";
                    $myclass = "Box\\Controller\\".$class;    
                    }
                            if(DEBUG == true)
                            {
                            print "<u>Loaded controller:</u>". $myclass.'<br />';
                            }

            }
            elseif (preg_match("/View/i", $class))
            {
                    if(MODULAR == true)
                    {
                    //$filename = "/../../box/modules/".$module."/view/".$class.".php";
                    $myclass = "Box\\Modules\\".$module."\\View\\".$class;
                    }
                    else
                    {
                    //$filename = "/../../box/view/".$class.".php";
                    $myclass = "Box\\View\\".$class;
                    }
                            if(DEBUG == true)
                            {
                            print "<u>Loaded view:</u>". $myclass.'<br />';
                            }
            //}
            //include $filename;
            }
            //$myclass = ucfirst($myclass);
            $obj = new $myclass();

            $m1 = ucfirst($action).'Action';
            $obj->$m1();

            if(DEBUG == true)
            {
            print "<u>Loaded Action:</u>". $m1.'<br />';
            }

        }
    }
}
?>
