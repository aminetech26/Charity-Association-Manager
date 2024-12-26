<?php 
//defined('ROOTPATH') OR exit('Access Denied!');
Trait Controller
{

	public function view($viewName, $returnContent = false)
	{
		$filename = "../app/views/" . $viewName . ".view.php";
		if (file_exists($filename)) {
			if ($returnContent) {
				ob_start();
				require $filename;
				return ob_get_clean();
			} else {
				require $filename;
			}
		} else {
			$filename = "../app/views/404.view.php";
			if ($returnContent) {
				ob_start();
				require $filename;
				return ob_get_clean();
			} else {
				require $filename;
			}
		}
	}

	public function model($modelName)
    {
        $filename = "../app/models/" . ucfirst($modelName) . ".model.php";
        require_once $filename;
    }

}