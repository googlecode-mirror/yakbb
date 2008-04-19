<?php
/*==================================================*\
|| ___     ___  ___     _     __  ______    ______
|| \--\   /--/ /---\   |-|   /-/ |--___-\  |--___-\
||  \--\_/--/ /--_--\  |-|  /-/  |-|___\-| |-|___\-|
||   \_---_/ /--/_\--\ |--\/-/   |------<  |------<
||     |-|   |---_---| |-----\   |--____-\ |--____-\
||     |-|   |--/ \--| |--/\--\  |-|___/-| |-|___/-|
||     |_|   |_|   |_| |_|  |__| |______/  |_______/
||
||==================================================||
|| Program: YakBB v1.0.0
|| Author: Chris Dessonville
||==================================================||
|| File: /library/classes/plugin.class.php
|| File Version: v0.1.0a
|| $Id$
\*==================================================*/

/*	TODO
	__construct()
		- Make it load all the plugins into the system.
*/

if(!defined("SNAPONE")) exit;

class plugin {
	private $hooks = array(); // Holds the hooks to be executed
	public $loadedPlugins = array(); // List of loaded plugins. Publicly accessable

	function __construct(){
		$this->callhook("global_start");
	}

	function loadPlugin($name){
		// @param	Type	Description
		// $name	String	The plugin ID/name to be loaded. ($name.plugin.php)

	}

	function addHook($name, $func_call){
		// This approach may be dropped later for an auto lookup. Classes are the only thing. Both maybe?
		// @param		Type	Description
		// $name		String	The hook id/name.
		// $func_call 	Ref.	Reference to the function to be call. (Array with class or string)

		$this->hooks[$name][] = $func_call;
	}

	function callHook($name, $params=array()){
		// @param	Type	Description
		// $name	String	The name or ID of the hook to call.
		// $params	Array	An array of the parameters for the function.
		// Return	Return	Returns an array of the updated parameters.

		if(!isset($this->hooks[$name]) || count($this->hooks[$name]) == 0){
			return $params;
		}

		foreach($this->hooks[$name] as $k => $v){
			if(is_callable($v)){
				$returned = call_user_func_array($v, array($params));
				if($returned){
					$params = $returned;
				}
			}
		}

		return $params;
	}	
}

$plugins = new plugin();	

?>