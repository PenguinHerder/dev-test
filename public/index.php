<?php
require_once '../vendor/autoload.php';

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($loader, ['debug' => true]);

//Get the episodes from the API
$provider = new \App\DataProvider();
$data = $provider->getData();
//Render the template
echo $twig->render('page.html', ["episodes" => $data['data'], 'status' => $data['status']]);
