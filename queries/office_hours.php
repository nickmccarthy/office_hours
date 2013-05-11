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

	function set_data($location, $end_time, $repeat_tag = -1)
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

	function add($db)
	{
		$query = "
		INSERT INTO OfficeHours (cid, uid, date, location, start_time, end_time, repeat_tag)
		VALUES (\"$this->cid\",\"$this->uid\",\"$this->date\",\"$this->location\",\"$this->start_time\",\"$this->end_time\",\"$this->repeat_tag\")";
		
		$db->query($query);
	}

	function update($db, $new_start_time)
	{
		$query = "
		UPDATE OfficeHours
		SET location = \"$this->location\",
		start_time = \"$new_start_time\",
		end_time = \"$this->end_time\",
		repeat_tag = \"$this->repeat_tag\"
		WHERE cid = \"$this->cid\"
		AND uid = \"$this->uid\"
		AND date = \"$this->date\"
		AND start_time = \"$this->start_time\"";

		$db->query($query);
		$this->start_time = $new_start_time;
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

	static function find_info_repeating($db, $repeat_tag)
	{
		$query = "
		SELECT *
		FROM OfficeHours
		WHERE repeat_tag = \"$repeat_tag\"
		LIMIT 1";

		$result = $db->query($query);
		$row = $result->fetch_assoc();

		return $row;
	}

	static function delete_repeating($db, $repeat_tag)
	{
		$query = "
		DELETE FROM OfficeHours
		WHERE repeat_tag = \"$repeat_tag\"";

		$db->query($query);
	}

	static function add_repeating($db, $roh)
	{
		if (strtotime($roh->end_date) < strtotime($roh->start_date))
		{
			// Print an error to user of some type?
			return;
		}

		$date = $roh->start_date;
		if (date('l', strtotime($roh->start_date)) != $roh->day_of_week)
		{
			$date = date('Y-m-d', strtotime("next $roh->day_of_week", strtotime($date)));
		}
		
		while ($date <= $roh->end_date)
		{			
			$oh = new office_hours($roh->cid, $roh->uid, $date, $roh->start_time);
			$oh->set_data($roh->location, $roh->end_time, $roh->repeat_tag);
			$oh->add($db);

			$date = date('Y-m-d', strtotime("next $roh->day_of_week", strtotime($date)));

		}
		


	}

}

class repeating_office_hours
{
	function __construct($repeat_tag = -1)
	{
		$this->repeat_tag = $repeat_tag;
	}

	function set_data($start_date, $end_date, $start_time, $end_time, $day_of_week, $location, $uid, $cid)
	{
		$this->start_date = $start_date;
		$this->end_date = $end_date;
		$this->start_time = $start_time;
		$this->end_time = $end_time;
		$this->day_of_week = $day_of_week;
		$this->location = $location;
		$this->uid = $uid;
		$this->cid = $cid;
	}

	function lookup_data($db)
	{
		$query = "
		SELECT *
		FROM Repeating
		WHERE repeat_tag = \"$this->repeat_tag\"";

		$result = $db->query($query);
		$row = $result->fetch_assoc();

		$info = office_hours::find_info_repeating($db, $row["repeat_tag"]);
		$this->set_data(
			$row["start_date"],
			$row["end_date"],
			$info["start_time"],
			$info["end_time"],
			date('l', strtotime($info["date"])),
			$info["location"],
			$info["uid"],
			$info["cid"]);
	}

	function add_or_update($db)
	{
		if ($this->repeat_tag == -1){
			$this->add($db);
		} else {
			$this->update($db);
		}
	}

	function add($db)
	{
		$query = "
		INSERT INTO Repeating (start_date, end_date)
		VALUES (\"$this->start_date\",\"$this->end_date\")";

		$db->query($query);
		$this->repeat_tag = $db->insert_id;
	}

	function delete($db)
	{
		$query = "
		DELETE FROM Repeating
		WHERE repeat_tag = \"$this->repeat_tag\"";
		$db->query($query);

		office_hours::delete_repeating($db, $this->repeat_tag);
	}

	function update($db)
	{
		$query = "
		UPDATE Repeating
		SET start_date = \"$this->start_date\", end_date = \"$this->end_date\"
		WHERE repeat_tag = \"$this->repeat_tag\"";
		$db->query($query);
	}




	static function find_repeating_hours($db, $uid, $cid)
	{
		$query = "
		SELECT *
		FROM Repeating
		WHERE repeat_tag
		IN (
			SELECT DISTINCT repeat_tag
			FROM OfficeHours
			WHERE uid = \"$uid\"
			AND cid = \"$cid\"
			AND repeat_tag >= 0
			) 
ORDER BY start_date";

$result = $db->query($query);

$arr = array();
while ($row = $result->fetch_assoc())
{
	$roh = new repeating_office_hours($row["repeat_tag"]);
	$info = office_hours::find_info_repeating($db, $row["repeat_tag"]);
	$roh->set_data(
		$row["start_date"],
		$row["end_date"],
		$info["start_time"],
		$info["end_time"],
		date('l', strtotime($info["date"])),
		$info["location"],
		$uid,
		$cid);
	$arr[] = $roh;
}

return $arr;
}
}


?>