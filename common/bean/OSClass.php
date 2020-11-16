<?php

class OSClass {

	private $id;
	private $osProvider;
	private $osName;
	private $osVersion;
	private $oOsDate;

	public function getId() {
		return $this->id;
	}

	public function getOSProvider() {
		return $this->osProvider;
	}

	public function getOSName() {
		return $this->osName;
	}

	public function getOSVersion() {
		return $this->osVersion;
	}
	
	public function getOOSDate() {
		return $this->oOsDate;
	}
	
	public function __construct(array $fields) {
		$this->id = $fields['ID'];	
		$this->osProvider = $fields['OSPROVIDER'];
		$this->osName = $fields['OSNAME'];
		$this->osVersion = $fields['OSVERSION'];
		$this->oOsDate = $fields['OOSDATE'];
	}

	public static function loadAll() {
		global $db;

		$fields = fetcher($db,
			"SELECT 
				*
			FROM PUBLIC.OSCLASS ORDER BY ID DESC",
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
						FROM PUBLIC.OSCLASS
						WHERE 
							UPPER(OSPROVIDER) LIKE UPPER(?) OR 
							UPPER(OSNAME) LIKE UPPER(?) OR
							UPPER(OSVERSION) LIKE UPPER(?) OR
							UPPER(OOSDATE) LIKE UPPER(?)";
					
			$totalRow = fetcher($db, $query, array($search, $search, $search, $search));
		}
		else{
			$query = "SELECT 
							COUNT(*) AS TOTAL
						FROM PUBLIC.OSCLASS";

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
						FROM PUBLIC.OSCLASS
						WHERE 
							UPPER(OSPROVIDER) LIKE UPPER(?) OR 
							UPPER(OSNAME) LIKE UPPER(?) OR
							UPPER(OSVERSION) LIKE UPPER(?) OR
							UPPER(OOSDATE) LIKE UPPER(?)
						ORDER BY ID DESC
						LIMIT ? OFFSET ?";
			$fields = fetcher($db, $query, array($search, $search, $search, $search, $limit, $offset));
		}
		else{
			$fields = fetcher($db,
			"SELECT 
				*
			FROM PUBLIC.OSCLASS ORDER BY ID DESC
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
			FROM PUBLIC.OSCLASS
			WHERE ID = ?",
			array($id));

		if (count($record)==0) {
			throw new Exception("No property found with ID $id");
		}
		
		return new OSClass($record[0]);
	}

	public static function save($osProvider,	$osName,	$osVersion, $oosDate){
		global $db;
		
		// db2_autocommit($db, false);

		$query = 'INSERT INTO PUBLIC.OSCLASS (
								OSPROVIDER,
								OSNAME,
								OSVERSION,
								OOSDATE,
								CREATEDDATE
							) VALUES
							(
								?, ?, ?, ?, CURRENT_TIMESTAMP
							)';
		// var_dump(sql_debug($query, array(
		// 			$osProvider,
		// 			$osName,
		// 			$osVersion,
		// 			$oosDate
		// 		)));die();
		$stmt = db2_prepare($db,$query);
		if ($stmt) {
			$ex = db2_execute($stmt, 
				array(
					$osProvider,
					$osName,
					$osVersion,
					$oosDate
				)
			);
			if (!$ex) {
				// db2_rollback($db);
				throw new Exception('Saving the property did not work. Please try again.');
			}
			else {
				// db2_commit($db);
				// db2_autocommit($db, true);
				return true;
			}
		}
	}

	public static function edit($id, $osprovider,	$osname,	$osversion,	$oosdate){
		global $db;		

		$query = 'UPDATE PUBLIC.OSCLASS SET
								OSPROVIDER = ?,
								OSNAME = ?,
								OSVERSION = ?,
								OOSDATE = ?,
								MODIFIEDDATE = CURRENT_TIMESTAMP
							WHERE ID = ?
						';
		// var_dump(sql_debug($query, array($machineclass,
		// $tad,
		// $architecture,$id)));die();
		$stmt = db2_prepare($db,$query);
		if ($stmt) {
			$ex = db2_execute($stmt, 
				array(
					$osprovider,
					$osname,
					$osversion,
					$oosdate,
					$id
				)
			);
			if (!$ex) {
				// db2_rollback($db);
				throw new Exception('Editing the record did not work. Please try again.');
			}
			else {
				// db2_commit($db);
				// db2_autocommit($db, true);
				return true;
			}
		}
	}

	public static function delete($id){
		global $db;
		$query = 'DELETE FROM PUBLIC.OSCLASS WHERE ID = ?';
		
		$stmt = db2_prepare($db,$query);
		if ($stmt) {
			$ex = db2_execute($stmt, array($id));
			if (!$ex) {
				// db2_rollback($db);
				throw new Exception('Deleting the record did not work. Please try again.');
			}
			else {
				// db2_commit($db);
				// db2_autocommit($db, true);
				return true;
			}
		}
	}
}

?>
