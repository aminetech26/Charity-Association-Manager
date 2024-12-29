<?php 
//defined('ROOTPATH') OR exit('Access Denied!');
function show($stuff)
{
	echo "<pre>";
	print_r($stuff);
	echo "</pre>";
}

function esc($str)
{
	return htmlspecialchars($str);
}


function redirect($path)
{
	header("Location: " . ROOT.$path);
	die;
}

function clean_string($string, $force_lowercase = true, $anal = false, $trunc = 0) {
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "â€\"", "â€\"", ",", "<", ">", "/", "?");
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean);
    $clean = ($force_lowercase) ? strtolower($clean) : $clean;
    
    if($trunc > 0) {
        $clean = substr($clean, 0, $trunc);
    }
    
    $clean = preg_replace('/-+/', '-', $clean);
    
    $clean = trim($clean, '-');
    
    $clean = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $clean);
    
    return $clean;
}

function sanitize_filename($filename) {
    $filename = basename($filename);
    
    return clean_string($filename);
}

function generate_unique_filename($original_filename, $extension = '') {
    if(empty($extension)) {
        $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
    }
    
    $base = clean_string(pathinfo($original_filename, PATHINFO_FILENAME));
    return $base . '_' . uniqid() . '.' . $extension;
}

function handleFileUpload($file, $relativeDirectory) {
    $absoluteDirectory = '../public/' . $relativeDirectory;

    if (!is_dir($absoluteDirectory)) {
        mkdir($absoluteDirectory, 0777, true);
    }

    $filename = generate_unique_filename($file['name']);
    $destination = $absoluteDirectory . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Error uploading file");
    }

    return $destination;
}