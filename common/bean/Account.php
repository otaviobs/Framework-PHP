<?php

class Account {

	private $id;
	private $name;
	private $shortcode;
	private $cdirId;

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getShortcode() {
		return $this->shortcode;
	}

	public function getCdirId() {
		return $this->cdirId;
	}
	
	public function __construct(array $fields) {
		$this->id = $fields['ID'];	
		$this->name = $fields['NAME'];
		$this->shortcode = $fields['SHORTCODE'];
		$this->cdirId = $fields['CDIRID'];
	}

	public static function loadAll() {
		global $db;

		$fields = fetcher($db,
			"SELECT 
				*
			FROM PUBLIC.ACCOUNT ORDER BY ID DESC",
			array());
		if (count($fields)==0) {
			throw new Exception("No record found");
		}
		
		return $fields;
	}

	public static function loadWithPagination($currentpage, $limit, $search=null) {
		global $db;

		if($search){
			$search = "%$search%";
			$query = "SELECT 
							COUNT(*) AS TOTAL
						FROM PUBLIC.ACCOUNT
						WHERE 
							UPPER(NAME) LIKE UPPER(?) OR 
							UPPER(SHORTCODE) LIKE UPPER(?) OR
							UPPER(CDIRID) LIKE UPPER(?)";
					
			$totalRow = fetcher($db, $query, array($search, $search, $search));
		}
		else{
			$query = "SELECT 
							COUNT(*) AS TOTAL
						FROM PUBLIC.ACCOUNT";

			$totalRow = fetcher($db, $query, array());
		}
		
		$totalPages = ceil($totalRow[0]['TOTAL'] / $limit);
		$offset = (($currentpage - 1) * $limit);
		$start = $offset + 1;
		$end = min(($offset + $limit), $totalRow[0]['TOTAL']);
		$offsetend = $end - 1;
		
		if($search){
			$query = "SELECT 
							*
						FROM PUBLIC.ACCOUNT
						WHERE 
							UPPER(NAME) LIKE UPPER(?) OR 
							UPPER(SHORTCODE) LIKE UPPER(?) OR
							UPPER(CDIRID) LIKE UPPER(?)
						ORDER BY ID DESC
						LIMIT ? OFFSET ?";
			$fields = fetcher($db, $query, array($search, $search, $search, $limit, $offset));
		}
		else{
			$fields = fetcher($db,
			"SELECT 
				*
			FROM PUBLIC.ACCOUNT ORDER BY ID DESC
			LIMIT ? OFFSET ?",
			array($limit, $offset));
		}
		
		// if (count($fields)==0) {
		// 	throw new Exception("No properte found");
		// }
		$result = array("records" => $fields, "endPage" => $totalPages);

		return $result;
	}
	
	public static function loadId($id) {
		global $db;

		$record = fetcher($db,
			"SELECT 
				*
			FROM PUBLIC.ACCOUNT
			WHERE ID = ?",
			array($id));

		if (count($record)==0) {
			throw new Exception("No property found with ID $id");
		}
		
		return new Account($record[0]);
	}

	public static function save($name, $shortcode, $cdirid){
		global $db;

		$query = 'INSERT INTO PUBLIC.ACCOUNT (
								NAME,
								SHORTCODE,
								CDIRID,
								CHIPID,
								CREATEDDATE
							) VALUES
							(
								?, ?, ?, ?, CURRENT_TIMESTAMP
							)';

		$stmt = db2_prepare($db,$query);

		if ($stmt) {
			$ex = db2_execute($stmt, 
				array(
					$name,
					$shortcode,
					$cdirid,
					$cdirid
				)
			);

			//Check if the response of execution works
			if (!$ex) {
				
				//Check which type of error
				if(db2_stmt_error($stmt) == "23505"){
					throw new Exception('CDIR ID or Shortcode already exists for another account, please review.');
				}else{					
					throw new Exception('Saving the property did not work. Please try again.');
				}
			}
			else {
				return true;
			}
		}
	}

	public static function edit($id, $name, $shortcode, $cdirid){
		global $db;

		$query = 'UPDATE PUBLIC.ACCOUNT SET
								NAME = ?,
								SHORTCODE = ?,
								CDIRID = ?,
								CHIPID = ?,
								MODIFIEDDATE = CURRENT_TIMESTAMP
							WHERE ID = ?
						';

		$stmt = db2_prepare($db,$query);
		if ($stmt) {
			$ex = db2_execute($stmt, 
				array(
					$name,
					$shortcode,
					$cdirid,
					$cdirid,
					$id
				)
			);
			
			//Check if the response of execution works
			if (!$ex) {
				
				//Check which type of error
				switch (db2_stmt_error($stmt)) {
					case 22001:
						throw new Exception('The value is too long, please review.');
						break;
					case 23505:
						throw new Exception('CDIR ID or Shortcode already exists for another account, please review.');
						break;
					default:
						throw new Exception('Saving the property did not work. Please try again.');
						break;
				}

			}
			else {
				return true;
			}
		}
	}

	public static function delete($id){
		global $db;
		$query = 'DELETE FROM PUBLIC.ACCOUNT WHERE ID = ?';
		
		$stmt = db2_prepare($db,$query);
		if ($stmt) {
			$ex = db2_execute($stmt, array($id));

			//Check if the response of execution works
			if (!$ex) {
				
				//Check which type of error
				if(db2_stmt_error($stmt) == "23001"){
					throw new Exception('Can not delete an account with uploaded data.');
				}else{					
					throw new Exception('Deleting the record did not work. Please try again.');
				}
			}
			else {
				return true;
			}
		}
	}
	
}

?>
