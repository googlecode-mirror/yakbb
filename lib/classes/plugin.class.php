<?php

if(!defined("SNAPONE")) exit;

class plugin {
	private $hooks = array(); // Holds the hooks to be executed
	public $loadedPlugins = array(); // List of loaded plugins. Publicly accessable

	function loadPlugin($name){
		// @param	Type	Description
		// $name	String	The plugin ID/name to be loaded. ($name.plugin.php)
	}

	function addHook($name, $func_call){
		// This approach may be dropped later for an auto lookup. Classes are the only thing. Both maybe?
		// @param	Type	Description
		// $name	String	The hook id/name.
		// $func_call Ref.	Reference to the function to be call. (Array with class or string)

		$hooks[$name][] = $func_call;
	}

	function callHook($name, $params=array()){
		// @param	Type	Description
		// $name	String	The name or ID of the hook to call.
		// $params	Array	An array of the parameters for the function.

		if(!isset($hooks[$name]) || count($hooks[$name]) == 0){
			return true;
		}

		foreach($hooks[$name] as $k => $v){
			if(is_callable($v)){
				call_user_func_array($v, $params);
			}
		}
	}	
}

$plugins = new plugin();	

?>