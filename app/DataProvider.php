<?php
namespace App;

use GuzzleHttp\Client;

class DataProvider {
	
	protected $http;
	protected $cache;
	
	public function __construct() {
		$this->http = new Client();
		$this->cache = new Cache();
	}
	
	public function getData() {
		$data = $this->cache->get('episodes');
		if($data === false) {
			$data = $this->retrieve();
			if($data === null) {
				return [
					'status' => false,
				];
			}
			
			$this->cache->set('episodes', $data);
		}
		
		return [
			'status' => true,
			'data' => $data,
		];
	}
	
	protected function retrieve() {
		try {
			$res = $this->http->request('GET', 'http://3ev.org/dev-test-api/');
			$data = json_decode($res->getBody(), true);

			//Sort the episodes
			array_multisort(array_keys($data), SORT_ASC, SORT_STRING, $data);
		}
		catch(\GuzzleHttp\Exception\ServerException $e) {
			return null;
		}
		
		return $data;
	}
}
