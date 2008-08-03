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
|| File: /core/classes/Post_Parser.class.php
|| File Version: v0.2.0a
|| $Id: YakBB.class.php 120 2008-07-27 07:01:01Z cddude229 $
\*==================================================*/

defined("YAKBB") or die("Security breach.");

/*	TODO
	__construct()
		- Needs to preload the smilies and censored words
	parse()
		- Needs to auto url-ify links
		- Needs to parse line breaks into a <br> tag
	censor()
		- Needs to be fully implemented
	smilies()
		- Needs to be fully implemented
	ubbc()
		- Needs to allow multiple font faces at once (comma)
		- Needs to do additional tags I haven't added yet
	ubbc_url_link_email()
		- Make sure the link is valid and secure
		- Needs to trim the inside of [url]LINK[/url] for long links that would stretch the page regularly
	ubbc_img()
		- Make sure the image url is valid and is of an accepted type
		- Needs to be fully implemented
*/

// Define some constants
define("YAKBB_UBBC_BASIC",		1);
define("YAKBB_UBBC_ADVANCED",	2);

class Post_Parser {
	// Holds the data that will be looped through to be parsed
	private $smilies    = array(); // Holds the smilies for the current template only
	private $censors    = array(); // Holds the censors to be enforced

	// Core loading functions
	public function __construct(){
		// Will load all custom stuff here later
	}

	public function parse($str, $smilies=true, $ubbclevel=7, $autourl=true){
		// $smilies -> Whether or not to parse smilies
		// $ubbclevel -> See ubbc() for more description
		// $autourl -> Whether or not to autourl-ify links
		if($smilies === true){
			$str = $this->smilies($str);
		}
		if($ubbclevel != 0){
			$str = $this->ubbc($str, (int) $ubbclevel);
		}
		return $str;
	}

	public function censor($str){
		return $str;
	}

	private function smilies($str){
		return $str;
	}

	private function ubbc($str, integer $level){
		// $level -> which tags wil be parsed. This is based off bitwise testing with bitwise-and (&)
			// 0 -> Don't parse UBBC (this function shouldn't be called if that is the case)
			// 1 -> Parse only basic formatting UBBC (b, i, u, font, size, style)
			// 2 -> Parse more advanced formatting (url, img, code, php, quote)

		if($level & YAKBB_UBBC_ADVANCED){
			// More advanced formatting
			// Need to add: quote, pre, list
			// Questionable: youtube, spoiler, move, blockquote, table, user, flash

			$str = preg_replace_callback("/\[noubbc\](.*?)\[\/noubbc\]/is", array($this, "ubbc_noubbc"), $str);
			$str = preg_replace_callback("/\[php\](.*?)\[\/php\]/is", array($this, "ubbc_php"), $str);
			$str = preg_replace_callback("/\[code\](.*?)\[\/code\]/is", array($this, "ubbc_code"), $str);
			$str = preg_replace_callback("/\[(url|link|email)(=.+?)?\](.*?)\[\/\\1\]/is", array($this, "ubbc_url_link_email"), $str);
			$str = preg_replace_callback("/\[img(\s(width|height)=\d+)+\](.*?)\[\/img\]/is", array($this, "ubbc_img"), $str);
			$str = preg_replace("/\[hr\]/i", "<hr size='1' width='100%' />", $str);
			$str = preg_replace("/\[br\]/i", "<br />", $str);
			$str = preg_replace("/\[(move|marquee)\](.*?)\[\/\\1\]/is", "<marquee>$2</marquee>", $str);
			$str = preg_replace("/\[align=(left|right|center|justified)\](.*?)\[\/align\]/is", "<div align=\"$1\">$2</div>", $str);
			$str = preg_replace_callback("/\[list(=.*?)?\](.*?)\[\/list\]/is", array($this, "ubbc_list"), $str);
		}

		if($level & YAKBB_UBBC_BASIC){
			// Parse basic formatting
			// Need to add: colors

			$str = preg_replace("/\[(su[bp])\](.*?)\[\/\\1\]/is", "<$1>$2<\/$1>", $str);
			$str = preg_replace("/\[tt\](.*?)\[\/tt\]/is", "<tt>$1</tt>", $str);
			$str = preg_replace("/\[b\](.*?)\[\/b\]/is", "<b>$1</b>", $str);
			$str = preg_replace("/\[i\](.*?)\[\/i\]/is", "<i>$1</i>", $str);
			$str = preg_replace("/\[u\](.*?)\[\/u\]/is", "<u>$1</u>", $str);
			$str = preg_replace("/\[s\](.*?)\[\/s\]/is", "<s>$1</s>", $str);
			$str = preg_replace_callback("/\[size=([\d]+)\](.*?)\[\/size\]/is", array($this, "ubbc_font_size"), $str);
			$str = preg_replace("/\[(font|face)=([\w=+)\](.*?)\[\/\\1\]/is", "<font face=\"$2\">$1</font>", $str);
		}

		return $str;
	}

	// Custom UBBC parsers
	private function ubbc_font_size($mat){
		// Parses the [size=#] tag

		$mat[1] = max(1, min($mat[1], 10)); // Bounds it between 10 and 1 for font sizes
		return "<font size=\"".$mat[1]."\">".$mat[2]."</font>";
	}

	private function ubbc_url_link_email($mat){
		// Parses the URL, LINK, and EMAIL tags
		// $mat[1] -> "url", "link", or "email"
		// $mat[2] -> the part including the = and the actual url (if added)
		// $mat[3] -> body between the two tags. May become the URL if not specified in $mat[2]

		// Let's get some variable set
		$type = strtolower($mat[1]); // I deal with only lowercase
		$target = ($type == "link"?"_self":"_blank");
		$url = (strpos("=", $mat[2])?substr($mat[2], 1):$mat[3]);
		$body = $mat[3];

		// We need to stop long links from breaking the page
		if($url == $body && strlen($body) > 80)){
			$body = substr($body, 0, 60)."...";
		}

		// Add appropriate prefixes if not given. This is more or less security reasons
		if($type == "email"){
			if(strpos("mailto:", $url) !== 0){ // Gotta be at the beginning or we'll add it
				$url = "mailto:".$url;
			}
		} else {
			if(!preg_match("/(https?|ftp):\/\//", $url)){ // Gotta have "http://" or another secure one at the begining or I'll be suspicious
				$url = "http://".$url;
			}
		}
		return "<a href=\"".$url."\" target=\"".$target."\">".$body."</a>";
	}

	private function ubbc_img($mat){
		// Parses the image tag
		// $mat[1] -> width and height parameters to be parsed (if supplied)
		// $mat[2] -> the url of the image tag

		return "Image tags coming soon.";
	}

	private function ubbc_code($mat){
		// $mat[1] -> code tag content
		$mat[1] = $this->ubbc_noubbc($mat); // Takes care of UBBC inside the code tag
		return "<b>Code:</b><br /><code>".$mat[1]."</code>";
	}

	private function ubbc_noubbc($mat){
		// Disable UBBC tags in the selected body
		$str = $mat[1];
		$str = preg_replace("/\[/", "&#91;", $str);
		$str = preg_replace("/\]/", "&#93;", $str);
		return $str;
	}

	private function ubbc_quote($mat){
		return "Quote here";
	}

	private function ubbc_php($mat){
		$mat[1] = highlight_string($this->ubbc_noubbc($mat), true);
		return "<b>PHP:</b><br /><code>".$mat[1]."</code>";
	}

	private function ubbc_list($mat){
		// $mat[1] -> May contain additional parameter type for ol vs ul
		// $mat[2] -> The body with list tags to parse

		$body = preg_replace("/\[\*\]/", "<li>", $mat[2]);
		$type = "ul";
		if(!empty($mat[1])){
			switch(substr($mat[1], 1)){
				case "ul":
				case "unordered":
				default:
					$type = "ul";
					break;
				case "ol":
				case "ordered":
					$type = "ol";
					break;
			}
		}
		return "<".$type.">".$body."</".$type.">";
	}
}

?>