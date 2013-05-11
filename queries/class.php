<?php
class course
{
	private $table_name = "Class";

	function __construct($cid = -1)
	{
		$this->cid = $cid;
	}

	function set_data($name, $number, $department, $semester, $inactive)
	{
		$this->name = $name;
		$this->number = $number;
		$this->department = $department;
		$this->semester = $semester;
		$this->inactive = $inactive;
	}

	function add($db)
	{
		$query = "
		INSERT INTO $this->table_name (name, number, department, semester, inactive)
		VALUES (\"$this->name\",\"$this->number\",\"$this->department\",\"$this->semester\",\"$this->inactive\")";
		
		$db->query($query);
		$this->cid = $db->insert_id;

	}

	function lookup_data($db)
	{
		$query = "
		SELECT *
		FROM $this->table_name
		WHERE cid = \"$this->cid\"
		LIMIT 1";

		$result = $db->query($query);

		if ($result->num_rows == 1)
		{
			$class = $result->fetch_assoc();

			$this->set_data(
				$class["name"],
				$class["number"],
				$class["department"],
				$class["semester"],
				$class["inactive"]);
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
			$cl = new course($row["cid"]);
			$cl->set_data(
				$row["name"],
				$row["number"],
				$row["department"],
				$row["semester"],
				$row["inactive"]);
			$arr[] = $cl;
		}

		return $arr;
	}


	function department_number()
	{
		return $this->department." ".$this->number;
	}
}

?>