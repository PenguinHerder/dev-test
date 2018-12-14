<?php
require_once '../vendor/autoload.php';

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($loader, ['debug' => true]);

//Get the episodes from the API
$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'http://3ev.org/dev-test-api/');
$data = json_decode($res->getBody(), true);

//Sort the episodes
array_multisort(array_keys($data), SORT_ASC, SORT_STRING, $data);

$seasons = [];
foreach($data as $episode) {
	if(!in_array($episode['season'], $seasons)) {
		$seasons[] = $episode['season'];
	}
}

sort($seasons, SORT_NUMERIC);
$selected = (int)$_GET['season'];

if($selected > 0) {
	$data = array_filter($data, function($item) use($selected) {
		return $item['season'] == $selected;
	});
}

//Render the template
echo $twig->render('page.html', ["episodes" => $data, 'seasons' => $seasons, 'selected' => $selected]);
