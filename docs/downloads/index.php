<?
//docs root
$root = "../";

//folder download location codes
$downloadLocations = array(
	"t" => "gallery/_downloads/"
);


//make sure we got all the things
if (!isset($_GET['t']) || !isset($_GET['f']) ||
(trim($_GET['t'])=="" || trim($_GET['f'])=="") ||
!isset($downloadLocations[$_GET['t']])){
	throw403();
}

//check if file exists
$realFileName = base64_url_decode($_GET['f']);
$file = $root.$downloadLocations[$_GET['t']].$realFileName;
if (!file_exists($file))
	throw404();


$fp = fopen($file, 'rb');

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=$realFileName");
header("Content-Length: " . filesize($file));
fpassthru($fp);

function base64_url_decode($input) {
 return base64_decode(strtr(urldecode($input), '-_,', '+/='));
}

function throw403($extra=""){
	header('HTTP/1.0 403 Forbidden'); 
    die('You are not allowed to access this file. '.$extra); 
}

function throw404($extra=""){
	header("HTTP/1.0 404 Not Found");
	 die('File Not found. '.$extra); 
}
?>