<?php

/*	TODO
	- Create cache functions to cache...
		- Custom UBBC
		- Smilies
		- Censored words list
	- Finish coding UBBC, smilies, and censored words list functions.
*/

class post_parser {
	public function __construct(){
		// We will pre-cache custom UBBC, smilies, and the censored words list here later.
	}

	public function parse($data, $ubbc=true, $smilies=true, $censor=true, $br=true){
		// This function initiates the standard parsing used everywhere.
		// @param	Type	Description
		// $data	String	The data to be parsed.
		// $ubbc	Boolean	Whether or not to parse UBBC.
		// $smilies	Boolean	Whether or not to parse smilies.
		// $censor	Boolean	Whether or not to censor the string.
		// $br		Boolean	Whether or not to parse line breaks into <br>'s.
		// Return	Return	Returns the parsed data.
		
		if($ubbc === true){
			$data = $this->ubbc($data);
		}
		if($smilies === true){
			$data = $this->smilies($data);
		}
		if($censor === true){
			$data = $this->censor($data);
		}
		if($br === true){
			$data = preg_replace("/\n/", "<br />", $data);
		}
		return $data;
	}

	private function ubbc($data){
		// Parses the UBBC code.

		return $data;
	}

	private function smilies($data){
		// Parses the smilies code.

		return $data;
	}

	private function censor($data){
		// Censors all censored words

		return $data;
	}
}

$parser = new post_parser();
?>