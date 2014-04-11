<?php 
define('CACHE_PATH',dirname(__FILE__).'/cache/');

class Cache {

    private $duration; // lifetime of the cache in days

    public function __construct($duration = 5) {
        $this->setDuration($duration);
    }
    
    public function get($key) {
        $filePath = $this->getCachePath($key);
        if(file_exists($filePath)) {
            $pastlife = (time() - filemtime($filePath)) / ( 60 * 60 * 24);
            if ($pastlife < $this->getDuration()) {
                return unserialize(file_get_contents($filePath));     
            }
        }
        return false;
    }

    public function set($key,$data) {
        $data = serialize($data);
        $filePath = $this->get_cache_path($key);
        file_put_contents($filePath,$data);
    }

    private function getCachePath($key) {
        $file = md5($key).'.cache'; 
        $path = CACHE_PATH.$file;
        return $path;
    }
    
    public function getDuration() {
    	return $this->duration;
    }
    
    public function setDuration($duration) {
    	$this->duration = $duration;
    }
}