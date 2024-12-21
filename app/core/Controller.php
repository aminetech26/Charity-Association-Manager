<?php 
//defined('ROOTPATH') OR exit('Access Denied!');
Trait Controller
{

	public function view($viewName)
	{
		$filename = "../app/views/".$viewName.".view.php";
		if(file_exists($filename))
		{
			require $filename;
		}else{
			$filename = "../app/views/404.view.php";
			require $filename;
		}
	}

	public function model($modelName)
    {
        $filename = "../app/models/" . ucfirst($modelName) . ".model.php";
        require_once $filename;
    }

}