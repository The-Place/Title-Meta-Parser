<?php 

$file_data = file_get_contents('data.txt');


$page_url =  explode("\n",$file_data);

$data = array();
$counter = 0;
foreach ($page_url as $url) {

	$data[$counter]['url'] = $url;

	$returned_values = get_data($url);

	$data[$counter]['title'] = $returned_values['title'];
	$data[$counter]['keywords'] = $returned_values['keywords'];
	$data[$counter]['description'] = $returned_values['description'];
	
	$counter++;
}

$fp = fopen('data.csv', 'w');
	
	$heading = array('URL', 'Title', 'Keywords', 'Description');
	fputcsv($fp, $heading);

foreach ($data as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);

echo "Completed";

function get_data($url) {



	$url = trim($url);

//  Initiate curl
$ch = curl_init();
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);

	$data['title'] = $title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $result, $matches) ? $matches[1] : null;	
	$data['description'] = preg_match('/<meta[^>]*name=[\"|\']description[\"|\'][^>]*content=[\"]([^\"]*)[\"][^>]*>/i',$result,$desc_matches) ? $desc_matches[1] : null;

	$data['keywords'] = preg_match('/<meta[^>]*name=[\"|\']keywords[\"|\'][^>]*content=[\"]([^\"]*)[\"][^>]*>/i',$result,$key_matches) ? $key_matches[1] : null;

	return $data;
}
?>