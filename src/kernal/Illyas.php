<?php namespace Kernal;
/*
 *  Illyas Controller - base controller
 */
class Illyas 
{
	public function model($model)
	{
		$mod = new $model();
		//var_dump($mod);
		return $mod;
	}
	public function view($view)
	{
		$v = new $view();
		//var_dump($mod);
		return $v;
	}
}
?>