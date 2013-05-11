<?php
class teaches
{
	private $table_name = "Teaches";
	public $user = false;
	public $class = false;

	function __construct($uid, $cid, $level='')
	{
		$this->uid = $uid;
		$this->cid = $cid;
		$this->level = $level;
	}

	function lookup_data($db)
	{
		$query = "
		SELECT *
		FROM $this->table_name
		WHERE uid = \"$this->uid\"
		AND cid = \"$this->cid\"
		LIMIT 1";

		$result = $db->query($query);

		if ($result->num_rows == 1)
		{
			$teach = $result->fetch_assoc();

			$this->level = $teach["teach"];
		}
	}

	static function get_classes_by_uid($db, $uid)
	{
		$query = "
		SELECT *
		FROM Teaches INNER JOIN Class
		ON Teaches.cid = Class.cid
		WHERE uid = \"$uid\"";

		$result = $db->query($query);

		$arr = array();
		while ($row = $result->fetch_assoc())
		{
			$t = new teaches($row["uid"], $row["cid"], $row["level"]);

			$cl = new course($row["cid"]);
			$cl->set_data(
				$row["name"],
				$row["number"],
				$row["department"],
				$row["semester"],
				$row["inactive"]);

			$t->class = $cl;

			$arr[] = $t;
		}

		return $arr;
	}

}

?>