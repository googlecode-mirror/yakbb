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
|| File: /library/classes/post_parser.class.php
|| File Version: v0.1.0a
|| $Id: global.php 64 2008-04-14 15:32:04Z cddude229 $
\*==================================================*/

/*	TODO
	- Create cache functions to cache...
		- Custom UBBC
		- Smilies
		- Censored words list
	- Finish coding UBBC, smilies, and censored words list functions.
*/

class post_parser {
	// Define the variables
	private $customUBBC = array();
	private $smilies = array();
	private $censors = array();

	// Public functions
	public function __construct(){
		// We will pre-cache custom UBBC, smilies, and the censored words list here later.
	}

	public function censor($str){
		// Censors all censored words

		return $str;
	}

	public function parse($str, $ubbc=true, $smilies=true, $br=true){
		// This function initiates the standard parsing used everywhere.
		// @param	Type	Description
		// $str	String	The data to be parsed.
		// $ubbc	Boolean	Whether or not to parse UBBC.
		// $smilies	Boolean	Whether or not to parse smilies.
		// $br		Boolean	Whether or not to parse line breaks into <br>'s.
		// Return	Return	Returns the parsed data.
		
		if($ubbc === true){
			$str = $this->ubbc($str);
		}
		if($smilies === true){
			$str = $this->smilies($str);
		}
		if($br === true){
			$str = preg_replace("/\n/", "<br />", $str);
		}
		return $str;
	}


	// Private functions
	private function ubbc($str){
		// Parses the UBBC code.

		// NOUBBC
		while(preg_match("/\[noubbc\].*?[\[\]].*?\[\/noubbc\]/si", $str)){
			$str = preg_replace("/\[noubbc\](.*?)\[(.*?)\[\/noubbc\]/si", "[noubbc]$1&#91;$2[/noubbc]", $str);
			$str = preg_replace("/\[noubbc\](.*?)\](.*?)\[\/noubbc\]/si", "[noubbc]$1&#93;$2[/noubbc]", $str);
		}
		$str = preg_replace("/\[noubbc\](.*?)\[\/noubbc\]/si", "$1", $str);

		// PHP
		while(preg_match("/\[php\].*?[\[\]].*?\[\/php\]/si", $str)){
			$str = preg_replace("/\[php\](.*?)\[(.*?)\[\/php\]/si", "[php]$1&#91;$2[/php]", $str);
			$str = preg_replace("/\[php\](.*?)\](.*?)\[\/php\]/si", "[php]$1&#93;$2[/php]", $str);
		}
		$str = preg_replace_callback("/\[php\](.*?)\[\/php\]/si", array($this, "ubbc_php"), $str);

		// Code
		while(preg_match("/\[code\].*?[\[\]].*?\[\/code\]/si", $str)){
			$str = preg_replace("/\[code\](.*?)\[(.*?)\[\/code\]/si", "[code]$1&#91;$2[/code]", $str);
			$str = preg_replace("/\[code\](.*?)\](.*?)\[\/code\]/si", "[code]$1&#93;$2[/code]", $str);
		}
		$str = preg_replace("/\[code\](.*?)\[\/code\]/si", "<b>Code:</b><br /><table cellpadding=\"3\" cellspacing=\"1\" class=\"border\"><tr><td class=\"cell1\">$1</td></tr></table>", $str);

		// Quote
		while(preg_match("/\[quote\].+?\[\/quote\]/si", $str)){
			$str = preg_replace_callback("/\[quote\](.*?)\[\/quote\]/si", array($this, "ubbc_quote"), $str);
		}

		// Simple UBBCs
		$search = array(
			"/\[(b|i|u|s|su[pb]|tt|blockquote)\](.*?)\[\/\\1\]/si"
				=> "<$1>$2</$1>",
			"/\[align=(center|right|left)\](.*?)\[\/\\1\]/si"
				=> "<p align=\"$1\">$2</p>",
			"/\[center\](.*?)\[\/center\]/si"
				=> "<p align=\"center\">$2</p>",
			"/\[color=([0-9A-F]{6})\](.*?)\[\/color\]/si"
				=> "<span style=\"color: $1\">$2</span>",
			"/\[size=(\d{1,2})\](.*?)\[\/size\]/si"
				=> "<span style=\"font-size: $1px\">$2</span>",
			"/\[font=([A-Z\s]+)\](.*?)\[\/font\]/si"
				=> "<span style=\"font-family: $1\">$2</span>",
			"/\[hr\]/si"
				=>"<hr size=\"1\" class=\"hr\" />",
		);

		// Instead of using the preg_replace array feature, we use this because preg_match only accepts strings.
		foreach($search as $k => $v){
			while(preg_match($k, $str)){
				$str = preg_replace($k, $v, $str);
			}
		}

		return $str;
	}

	private function ubbc_php($m){
		// Parses the PHP UBBC tag

		return "<b>PHP:</b><br /><table cellpadding=\"3\" cellspacing=\"1\" class=\"border php\"><tr><td class=\"cell1\">".highlight_string(html_entity_decode($m[1]), true)."</td></tr></table>";
	}

	private function ubbc_quote($m){
		// Parses the quote UBBC tag

		return "<b>Quote:</b><br /><table cellpadding=\"3\" cellspacing=\"1\" class=\"border quote\"><tr><td class=\"cell1\">".$m[1]."</td></tr></table>";
	}

	private function smilies($str){
		// Parses the smilies code.

		return $str;
	}
}

$parser = new post_parser();
?>