<?php
class MemoModel {
	static $memoDB;

  public static function linkMemoDB() {
		$dblink = 'mysql:host='.DB_HOST.';dbname=memo;charset=utf8';
		MemoModel::$memoDB = new PDO($dblink, DB_USERNAME, DB_PASSWORD);
	}

  public static function insert($input_eng_word, $input_chi_means, $create_at) {
		$rtn = "";
		$update_at = $create_at;
		$sql = "INSERT INTO english_words (word, means, updated_at, created_at) VALUE (?, ?, ?, ?)";
		$inputarr = array(
			$input_eng_word,
			json_encode($input_chi_means, JSON_UNESCAPED_UNICODE),
			$update_at,
			$create_at
		);
		$statement = MemoModel::$memoDB->prepare($sql);
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
		$ret = MemoModel::$memoDB->query($sql)->fetch(PDO::FETCH_ASSOC);
		$total = (int)$ret['count(*)'];
		$rand_num = rand(0, $total - 1);

		$sql = "SELECT * FROM english_words LIMIT 1 OFFSET {$rand_num}";

		$statement = MemoModel::$memoDB->prepare($sql);
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
