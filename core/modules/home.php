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
|| File: /core/modules/home.php
|| File Version: v0.2.0a
|| $Id$
\*==================================================*/

/*	TODO
	- Change permissions to use the catPermissions and boardPermissions functions
	- On/off buttons
*/

class home {
	private $cats = array();

	public function init(){
		global $yakbb;

		$yakbb->loadLanguageFile("home");
		$yakbb->smarty->assign("page_title", $yakbb->getLang("page_title"));

		$this->loadCats();
		$this->loadBoards();

		$yakbb->smarty->assign("cats", $this->cats);

		$yakbb->loadTemplate("home.tpl");
	}

	private function loadCats(){
		global $yakbb;

		$cats = $yakbb->db->cacheQuery("
			SELECT
				*
			FROM
				yakbb_categories
			ORDER BY
				`order` ASC
		", "categories");

		$this->cats = array();
		// Can view category? Delete from array if not able to
		foreach($cats as $k => $v){
			$perms = unserialize($v["permissions"]);
			if(!isset($perms[$yakbb->user["group"]]) || $perms[$yakbb->user["group"]]["view"] == false){
				unset($cats[$k]);
			} else {
				$v["boards"] = array();
				$this->cats[$v["id"]] = $v;
			}
		}
	}

	private function loadBoards(){
		global $yakbb;

		// Assemble category ids list
		$catids = array(0 => 0); // Can view null category always

		foreach($this->cats as $k => $v){
			$catsids[] = $v["id"];
		}

		$boards = $yakbb->db->query("
			SELECT
				b.*,
				v.id AS viewid,
				u.username AS lpusername, u.displayname AS lpdisplay, u.group AS lpgroup,
				t.name AS lpthreadname
			FROM
				yakbb_boards b
			LEFT JOIN
				yakbb_boards_views v
				ON (b.id = v.boardid AND v.userid = '".$yakbb->user["id"]."')
			LEFT JOIN
				yakbb_users u
				ON (u.id = b.lastpostuserid)
			LEFT JOIN
				yakbb_threads t
				ON (t.id = b.lastpostthreadid)
			WHERE
				b.parenttype = 'c'
				AND b.hidden = '0'
				AND b.parentid IN (".implode($catsids, ",").")
			ORDER BY
				b.parentorder ASC
		");

		while($b = $yakbb->db->fetch()){
			$bperms = unserialize($b["permissions"]);
			if(!isset($bperms[$yakbb->user["group"]]) || $bperms[$yakbb->user["group"]]["view"] == false){
				continue;
			}
			$b["link"] = link_board($b["id"], $b["name"]);
			$b["url"] = url_board($b["id"], $b["name"]);
			$b["permissions"] = $bperms[$yakbb->user["group"]];
			$b["lpdate"] = makeDate($b["lastposttime"]);
			$b["lplink"] = link_thread($b["lastpostthreadid"], $b["lpthreadname"]);
			$b["lpuserlink"] = link_user($b["lastpostuserid"], $b["lpusername"], $b["lpdisplay"], $b["lpgroup"]);
			$this->cats[$b["parentid"]]["boards"][] = $b;
		}
	}
}

?>