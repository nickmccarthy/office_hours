<?php
class user
{
	private $db_name = "Users";

	function __construct($email)
	{
		$this->email = $email;
	}

	// Manually set user data
	function set_data($fn, $ln, $pw)
	{
		$this->fn = $fn;
		$this->ln = $ln;
		$this->create_hash($pw);
	}

	private function create_hash($pw)
	{
		$this->salt = md5(uniqid(rand(), true));
		$this->hash = hash('sha256', $this->salt.$pw);
	}

	// Look up user data in db
	function lookup_data($db)
	{
		$query = "
		SELECT *
		FROM $this->db_name
		WHERE email = \"$this->email\"
		LIMIT 1";

		$result = $db->query($query);

		if ($result->num_rows == 1)
		{
			$user = $result->fetch_assoc();

			$this->fn = $user["first_name"];
			$this->ln = $user["last_name"];
			$this->salt = $user["salt"];
			$this->hash = $user["hash"];
		}
	}

	function exists($db)
	{
		$query = "
		SELECT 1
		FROM $this->db_name
		WHERE email = \"$this->email\"";

		$result = $db->query($query);
		return $result->num_rows == 1;
	}

	// requires a call of set data before
	function add($db)
	{
		$query = "
		INSERT INTO $this->db_name (first_name, last_name, email, hash, salt)
		VALUES (\"$this->fn\",\"$this->ln\",\"$this->email\",\"$this->hash\",\"$this->salt\")";
		
		$db->query($query);
	}
}


?>