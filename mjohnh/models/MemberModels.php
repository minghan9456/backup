<?php
class MemberModel {
	static $memoDB;

  public static function linkMemoDB() {
		$dblink = 'mysql:host='.DB_HOST.';dbname=memo;charset=utf8';
		MemberModel::$memoDB = new PDO($dblink, DB_USERNAME, DB_PASSWORD);
	}

	public static function login($fbid, $name, $email, $_now) {
		$rtn = false;
		$sql = "SELECT * FROM member WHERE fbid = ?";

		$statement = MemberModel::$memoDB->prepare($sql);
		$status = $statement->execute(array($fbid));
		$errorInfo = json_encode($statement->errorInfo());

		if ($status !== false && ($ret = $statement->fetch(PDO::FETCH_ASSOC))) {
			$rtn = true;	
		}
		else {
			if (!($insert_ret = MemberModel::insert($fbid, $name, $email, $_now))) $rtn = true;
		}

		return $rtn;
	}

	public static function insert($fbid, $name, $email, $_now) {
		$rtn = "";
		$sql = "INSERT INTO member (fbid, name, email, created_at) VALUE (?, ?, ?, ?)";
		$inputarr = array(
			$fbid,
			$name,
			$email,
			$_now
		);
		$statement = MemberModel::$memoDB->prepare($sql);
		$status = $statement->execute($inputarr);

		if ($status !== false) {
			$rtn = DBERR_NONE;
		}
		else {
			$errorInfo = $statement->errorInfo();
			if ($errorInfo[0] == "23000") {
				$rtn = DBERR_DUPLICATE;
			}
			else {
				$rtn = DBERR_UNKNOWN;
			}
		}
		
		return $rtn;
	}

	public static function update() {
	}

	public static function randomGetWord() {
		$sql = "SELECT count(*) FROM english_words";
		$ret = MemberModel::$memoDB->query($sql)->fetch(PDO::FETCH_ASSOC);
		$total = (int)$ret['count(*)'];
		$rand_num = rand(0, $total - 1);

		$sql = "SELECT * FROM english_words LIMIT 1 OFFSET {$rand_num}";

		$statement = MemberModel::$memoDB->prepare($sql);
		$status = $statement->execute();
		if ($status !== false) {
			$rtn = $statement->fetch(PDO::FETCH_ASSOC);
			$rtn['means'] = json_decode($rtn['means'], true);
		}
		else {
			$rtn = false;
		}

		return $rtn;
	}
}
