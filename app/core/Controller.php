<?php 
//defined('ROOTPATH') OR exit('Access Denied!');
Trait Controller
{

	public function view($viewName, $role, $returnContent = false)
	{
		switch($role){
			case 'admin':
				$base = '../app/views/admin/';
				break;
			case 'public':
				$base = '../app/views/public/';
				break;
			case 'membre':
				$base = '../app/views/membre/';
				break;
			case 'partenaire':
				$base = '../app/views/partenaire/';
				break;
			default:
				$base = '../app/views/';
				break;
		}
		$filename = $base . $viewName . ".view.php";
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