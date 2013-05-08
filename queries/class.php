<?php
class course
{
	private $table_name = "Class";

	function __construct($cid)
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


	function name() {
		return $this->name;
	}
	function number() {
		return $this->number;
	}
	function department() {
		return $this->department;
	}
	function semester() {
		return $this->semester;
	}
	function inactive() {
		return $this->inactive;
	}
}

?>