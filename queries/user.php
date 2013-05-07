<?php
class user
{
	private $db_name = "Users";

	function __construct($fn, $ln, $email, $pw)
	{
		$this->fn = $fn;
		$this->ln = $ln;
		$this->email = $email;
		$this->create_hash($pw);
	}

	private function create_hash($pw)
	{
		$this->salt = md5(uniqid(rand(), true));
		$this->hash = hash('sha256', $this->salt.$pw);
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

	function add($db)
	{
		$query = "
		INSERT INTO $this->db_name (first_name, last_name, email, hash, salt)
		VALUES (\"$this->fn\",\"$this->ln\",\"$this->email\",\"$this->hash\",\"$this->salt\")";
		
		$db->query($query);
	}
}


?>