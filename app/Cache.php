<?php
namespace App;

class Cache {
	
	const CACHE_LIFETIME = 60 * 5;
	
	protected $path;
	
	public function __construct(string $path = null) {
		$this->path = $path ?? __DIR__ . '/../cache';
	}
	
	public function set(string $file, array $data) {
		$cache = [
			'ts' => time(),
			'data' => $data,
		];
		
		file_put_contents($this->filePath($file), json_encode($cache));
	}
	
	public function get(string $file) {
		$path = $this->filePath($file);
		if(file_exists($path)) {
			$contents = file_get_contents($path);
			$data = json_decode($contents, true);
			if(!is_array($data) || !array_key_exists('ts', $data)) {
				return false;
			}
			
			return $this->compare($data['ts']) ? $data['data'] : false;
		}
		
		return false;
	}
	
	protected function compare(int $ts) {
		return time() < $ts + self::CACHE_LIFETIME;
	}
	
	protected function filePath(string $file) {
		return $this->path . '/' . $file;
	}
}
