<?php
class Configuration {
	private static $branch=array('test','dev','release');
	public static function getConfig($keyV, $default="") {
		$result = "";
		// get value from Host-specific config file(config-hostname.ibm.com.xml)
		// get host name
		$hostname = $_SERVER["SERVER_NAME"];
		$uri=self::detect_uri();
		if(!empty($uri)&&$hostname!='localhost')
			$hostname=$hostname.'-'.$uri;

		$filename = __DIR__."/../config/config-".$hostname.".xml";

//		var_dump($filename);
		// check if file exists
		if (file_exists($filename)) {			
//			libxml_disable_entity_loader(false);
			// load xml file into an object
//			$xml = simplexml_load_file($filename);
			$xml=simplexml_load_string(file_get_contents($filename));
			// check if xml document is well-formed
			if ($xml) {
				// get value by key
				foreach ($xml as $key => $value) {
					if ($value->attributes() == $keyV) {
						$result = $value;
						break;
					}
				}
				if ($result != "") {
					return $result;
				}
			}
		}
		
		// get value from Default config file(config.xml)
		// get config file name
		$filename = __DIR__."/../config/config.xml";
		// check if file exists
		if (file_exists($filename)) {
			// load xml file into an object
//			$xml = simplexml_load_file($filename);
			$xml=simplexml_load_string(file_get_contents($filename));
			// check if xml document is well-formed
			if ($xml) {
				// get value by key
				foreach ($xml as $key => $value) {
					if ($value->attributes() == $keyV) {
						$result = $value;
						break;
					}
				}
				if ($result != "") {
					return $result;
				}
			}
		}
		
		
		// get default value if the parameter is available
		if (isset($default) && $default != "") {
			$result = $default;
			return $result;
		}
		
		// cannot get any value from xml files, return empty string
		return $result;
	}
	public static function detect_uri() {

		if ( ! isset($_SERVER['REQUEST_URI']) OR ! isset($_SERVER['SCRIPT_NAME'])) {
			return '';
		}

		$uri = $_SERVER['REQUEST_URI'];


		if ($uri == '/' || empty($uri)) {
			return '';
		}

		$subUri=explode('/',str_replace(array('//', '../'), '/', trim($uri, '/')));
		return in_array($subUri[0],self::$branch)?$subUri[0]:'';
	}
}
?>
