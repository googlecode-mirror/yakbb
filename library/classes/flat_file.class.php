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
|| File: /library/classes/flat_file.class.php
|| File Version: v0.1.0a
|| $Id: global.php 64 2008-04-14 15:32:04Z cddude229 $
\*==================================================*/

if(!defined("SNAPONE")) exit;

/*	NOTE
	- This is not FTP, just standard flat file.
	- This has not been fully converted to YakBB form yet.
*/

class flat_file {
	// DIRECTORY FUNCTIONS
	public function makeDir($path, $chmod=0777){
		// Creates a directory with specified path name and chmod. Attempts to perform recursion too.
		// @param	Type	Description
		// $path	String	Path to the folder to create. And the folder name.
		// $chmod	Number	The chmod of the file. Defaults to 0777.
		// Return	Return	Returns true on completion and false on failure.

		// Make sure the directory doesn't already exist and then create it.
		if(!is_dir($path)){
			return mkdir($path, $chmod, true);
		}
		return true;
	}

	public function deleteDir($path){
		// Deletes the specified directory.
		// @param	Type	Description
		// $path	String	Path to the folder to delete.
		// Return	Return	Returns true on success, false on failure.

		if(is_dir($path)){
			$this->clearDir($path, array(), true);
			return rmdir($path);
		}
		return true;
	}

	public function clearDir($path, $ft=array(), $folder=false){
		// Clears all files in the specified directory with an exception to an array of file type extensions.
		// @param	Type	Description
		// $path	String	The folder to be cleared.
		// $ft		Array	The array of file extensions (minus the period) to not delete. Auto adds htaccess
		// $folder	Boolean	Whether or not to delete sub-folders too. Defaults to false.
		// Return	Return	Returns an array of ignored files/folders on success, and returns false on failure.

		if(!is_dir($path)){
			return false;
		}

		$ft[] = "htaccess"; // Make sure not to delete a .htaccess file
		$ft[] = "svn"; // Don't delete svn folders/files.
		$i = array(); // Files that are ignored because of extensions.
		$f = $this->listDir($path);

		foreach($f as $x){
			if(!in_array(end(explode(".", $x)), $ft)){ // Get file extension and make sure it isn't in the list.
				if(preg_match("/\./", $x)){
					// Make sure it's a file. This catches the SVN folders too though. =/
					$this->deleteFile($path."/".$x);
				} else if($folder === true){
					// Check to see if we should delete folders.
					$this->deleteDir($path."/".$x);
				} else {
					$i[] = $x;
				}
			} else {
				$i[] = $x;
			}
		}

		// Returns 
		unset($f);
		return $i;
	}
	
	public function listDir($path, $subs=false, $fonly=false){
		// Returns a list of all files and sub-directories in the specified folder and possibly sub folders
		// @param	Type	Description
		// $path	String	The folder to return a list of items for.
		// $subs	Boolean	Whether or not to list the files of the sub-folders too. Defaults to false.
		// $fonly	Boolean	Whether or not to list the sub-folders only. Defaults to false.
		// Return	Return	Returns a list of the files on success, or false on error.
	

		// File list array.
		$f = array();

		// Make sure directory exists. If not, return false.
		if(!is_dir($path)){
			return false;
		}

		// Open directory
		$d = dir($path);
		while(false !== ($i = $d->read())){
			if($i != '.' && $i != '..'){
				if(is_dir($path."/".$i) && $subs === true){
					$f[] = array(
						$i,
						$this->listDir($path."/".$i, $subs, $fonly)
					);
				} else if(is_dir($path."/".$i)){
					$f[] = $i;
				} else if($fonly === false){
					$f[] = $i;
				}
			}
		}

		// Close directory
		$d->close();

		// Return results
		return $f;
	}

	public function moveDir($old, $new){
		// Moves a directory.
		// @param	Type	Description
		// $old		String	Current path to the folder including folder name.
		// $new		String	New path to the folder including folder name.
		// Return	Return	True on success, false on failure.

		// Make sure old directory exists.
		if(!is_dir($old)){
			return false;
		}

		// Make sure the path to the new one works.
		if(!is_dir(dirname($new))){
			$this->makeDir(dirname($new));
		}

		return rename($old, $new);
	}

	public function renameDir($path, $old, $new){
		// Renames the directory specified.
		// @param	Type	Description
		// $path	String	The path to the folder.
		// $old		String	The name of the folder currently.
		// $new		String	The new name of the folder.
		// Return	Return	True on success, false on failure

		return $this->moveDir($path.$old, $path.$new);
	}









	// FILE FUNCTIONS
	public function updateFile($path, $content){
		// Creates/updates a file. Will also create directory if directory doesn't exist. This does not append content, but replaces.
		// @param	Type	Description
		// $path	String	The path to the file and the name of the file.
		// $content	String	The content to be  put into the file.
		// Return	Return	Returns true on success or false on failure.

		// Make sure directory exists
		if(!is_dir(dirname($path))){
			if(!$this->makeDir(dirname($path))){
				return false; // No directory can be found or created for the file.
			}
		}

		// Open and then write to the file
		$fp = fopen($path, "w");
		flock($fp, LOCK_EX);
		fwrite($fp, $content);
		flock($fp, LOCK_UN);
		fclose($fp);

		return true;
	}

	public function deleteFile($path){
		// Deletes the specified file.
		// @param	Type	Description
		// $path	String	Path to the file.
		// Return	Return	Returns true on success of false on failure

		if(file_exists($path)){
			return unlink($path);
		}
		return true;
	}

	public function fileTime($path){
		// Find the last modified time of the file in a time stamp format. Returns 0 on error
		// @param	Type	Description
		// $path	String	Path to the file
		// Return	Return	Returns the time of the file's last modification. Returns 0 on error.

		if(file_exists($path)){
			return filemtime($path);
		}
		return 0;
	}

	public function moveFile($oldpath, $newpath, $file, $nfile=false){
		// Moves the specified file to the specified new location. Creates newpath if it doesn't exist.
		// @param	Type	Description
		// $oldpath	String	The old path to the file, not including the file name.
		// $newpath	String	The new path to the file, not including the file name.
		// $file	String	The name of the old file.
		// $nfile	String	The name of the new file if you want to rename it. Defaults to false. If false, it doesn't rename the file.
		// Return	Return	Returns true on success or false on failure.

		if(!file_exists($oldpath.$file)){ // File doesn't exist. Return false.
			return false;
		}

		if($nfile === false) $nfile = $file; // In case they don't want to rename the file also.

		if(!is_dir($newpath)){
			$this->makeDir($newpath);
		}

		rename($oldpath.$file, $newpath.$nfile);
		return true;
	}

	public function renameFile($path, $old, $name){
		// Renames the specified file.
		// @param	Type	Description
		// $path	String	The path and file name of the current file.
		// $old		String	The current name of the file.
		// $name	String	The new name of the file.
		// Return	Return	Returns true on success or false on failure.

		$this->moveFile($path, $path, $old, $name);
	}

	public function loadFile($path, $type=false){
		// Load the data of a file.
		// @param	Type	Description
		// $path	String	The path to the file.
		// $type	Boolean	Whether or not to return the result as a string or an array.
		// Return	Return	Returns the data on success of false on failure.

		if(!file_exists($path)){
			return false;
		}

		if($type === true){
			return file($path);
		} else {
			return file_get_contents($path);
		}
	}
}
?>