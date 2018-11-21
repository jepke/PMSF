<?php
include('config/config.php');
global $map, $fork;

if ( $noSearch === true ) {
    http_response_code( 401 );
    die();
}
$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
if (preg_match("/curl|libcurl/", $useragent)) {
    http_response_code(400);
    die();
}
$data = array();
$term = ! empty( $_POST['term'] ) ? $_POST['term'] : '';
$action = ! empty( $_POST['action'] ) ? $_POST['action'] : '';
$lat = ! empty( $_POST['lat'] ) ? $_POST['lat'] : '';
$lon = ! empty( $_POST['lon'] ) ? $_POST['lon'] : '';

$dbname = '';
if (strtolower($map) === "rdm") {
    if (strtolower($fork) === "default") {
	if ( $action === "pokestops" ) {
	    $dbname = "pokestop";
	} elseif ( $action === "forts" ) {
	    $dbname = "gym";
	} elseif ( $action === "reward" ) {
	    $dbname = "pokestop";
	} elseif ( $action === "nests" ) {
	    $dbname = "nests";
	} elseif ( $action === "portals" ) {
	    $dbname = "ingress_portals";
	}
        $search = new \Search\RDM();
    }
}
if ($action === "reward") {
    $data["reward"] = $search->search_reward($lat, $lon, $term);
}
if ($action === "nests") {
    $data["nests"] = $search->search_nests($lat, $lon, $term);
} 
if ($action === "portals") {
    $data["portals"] = $search->search($dbname, $lat, $lon, $term);
}
if ($action === "pokestops") {
    $data["pokestops"] = $search->search($dbname, $lat, $lon, $term);
}
if ($action === "forts") {
$data["forts"] = $search->search($dbname, $lat, $lon, $term);
}
$jaysson = json_encode($data);
echo $jaysson;
