<?php

class office_hours
{
	function __construct($cid, $uid, $date, $start_time)
	{
		$this->cid = $cid;
		$this->uid = $uid;
		$this->date = $date;
		$this->start_time = $start_time;
	}

	function set_data($location, $end_time, $repeat_tag)
	{		
		$this->location = $location;
		$this->end_time = $end_time;
		$this->repeat_tag = $repeat_tag;
	}

	function lookup_data($db)
	{
		$query = "
		SELECT *
		FROM OfficeHours
		WHERE cid = \"$this->cid\"
		AND uid = \"$this->uid\"
		AND date = \"$this->date\"
		AND start_time = \"$this->start_time\"
		LIMIT 1";

		$result = $db->query($query);

		if ($result->num_rows == 1)
		{
			$row = $result->fetch_assoc();

			$this->set_data(
				$row["location"],
				$row["end_time"],
				$row["repeat_tag"]);
		}
	}

	static function find_hours_on_date($db, $uid, $cid, $date)
	{
		return office_hours::find_hours_in_range($db, $uid, $cid, $date, $date);
	}

	static function find_hours_in_week($db, $uid, $cid, $start_date)
	{
		$ed = new DateTime($start_date);
		$ed->add(new DateInterval('P7D'));
		return office_hours::find_hours_in_range($db, $uid, $cid, $start_date, $ed->format('Y-m-d'));
	}

	static function find_hours_in_range($db, $uid, $cid, $start_date, $end_date)
	{
		$query = "
		SELECT *
		FROM OfficeHours
		WHERE uid = \"$uid\"
		AND cid = \"$cid\"
		AND date >= \"$start_date\"
		AND date <= \"$end_date\"
		ORDER BY date, start_time";

		$result = $db->query($query);

		$arr = array();
		while ($row = $result->fetch_assoc())
		{
			$oh = new office_hours(
				$row["cid"],
				$row["uid"],
				$row["date"],
				$row["start_time"]);
			$oh->set_data(
				$row["location"],
				$row["end_time"],
				$row["repeat_tag"]);
			$arr[] = $oh;
		}

		return $arr;
	}
}


?>