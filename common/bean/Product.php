<?php

class Product {

	private $id;
	private $name;
	private $price;
	private $createddate;
	private $modifieddate;

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getPrice() {
		return $this->price;
	}
	
	public function getCreatedDate() {
		return $this->createddate;
	}

	public function getModifiedDate() {
		return $this->modifieddate;
	}

	public function __construct(array $fields) {
		$this->id = $fields['ID'];
		$this->name = $fields['NAME'];
		$this->price = $fields['PRICE'];
		$this->createddate = $fields['CREATEDDATE'];
		$this->modifieddate = $fields['MODIFIEDDATE'];
	}

	public static function loadAll() {
		global $db;

		$fields = fetcher($db,
			"SELECT 
				ID,
				NAME,
				REPLACE(PRICE::TEXT, '$', '') AS PRICE,
				TO_CHAR(CREATEDDATE, 'dd/MM/yyyy') AS CREATEDDATE,
				TO_CHAR(MODIFIEDDATE, 'dd/MM/yyyy') AS MODIFIEDDATE
			FROM PUBLIC.PRODUCT ORDER BY ID DESC",
			array());
		if (count($fields)==0) {
			throw new Exception("No product found");
		}
		
		return $fields;
	}

	public static function loadWithPagination($currentpage, $limit, $search=null) {
		global $db;

		if($search){
			$search = "%$search%";
			$query = "SELECT 
							COUNT(*) AS TOTAL
						FROM PUBLIC.PRODUCT
						WHERE 
							NAME LIKE $1";
					
			$totalRow = fetcher($db, $query, array($search));
		}
		else{
			$query = "SELECT 
							COUNT(*) AS TOTAL
						FROM PUBLIC.PRODUCT";

			$totalRow = fetcher($db, $query, array());
		}
		
		$totalPages = ceil($totalRow[0]['TOTAL'] / $limit);
		$offset = (($currentpage - 1) * $limit);
		$start = $offset + 1;
		$end = min(($offset + $limit), $totalRow[0]['TOTAL']);
		$offsetend = $end - 1;
		
		if($search){
			$query = "SELECT 
							ID,
							NAME,
							REPLACE(PRICE::TEXT, '$', '') AS PRICE,
							TO_CHAR(CREATEDDATE, 'dd/MM/yyyy') AS CREATEDDATE,
							TO_CHAR(MODIFIEDDATE, 'dd/MM/yyyy') AS MODIFIEDDATE
						FROM PUBLIC.PRODUCT
						WHERE 
							NAME LIKE $1
						ORDER BY ID DESC
						LIMIT $2 OFFSET $3";
			$fields = fetcher($db, $query, array($search, $limit, $offset));
		}
		else{
			$fields = fetcher($db,
			"SELECT 
				ID,
				NAME,
				REPLACE(PRICE::TEXT, '$', '') AS PRICE,
				TO_CHAR(CREATEDDATE, 'dd/MM/yyyy') AS CREATEDDATE,
				TO_CHAR(MODIFIEDDATE, 'dd/MM/yyyy') AS MODIFIEDDATE
			FROM PUBLIC.PRODUCT ORDER BY ID DESC
			LIMIT $1 OFFSET $2",
			array($limit, $offset));
		}
		
		$result = array("records" => $fields, "endPage" => $totalPages);

		return $result;
	}
	
	public static function loadId($id) {
		global $db;

		$record = fetcher($db,
			"SELECT 
				*
			FROM PUBLIC.PRODUCT
			WHERE ID = $1",
			array($id));

		if (count($record)==0) {
			throw new Exception("No property found with ID $id");
		}
		
		return new Product($record[0]);
	}

	public static function save($name, $price){
		global $db;
		
		// db2_autocommit($db, false);

		$query = 'INSERT INTO PUBLIC.PRODUCT (
								NAME,
								PRICE,
								CREATEDDATE
							) VALUES
							(
								$1, $2, CURRENT_TIMESTAMP
							)';

		$stmt = pg_query_params($db, $query, array($name, $price));
		if (!$stmt) {
			// db2_rollback($db);
			throw new Exception('Saving the product did not work out. Please try again.');
		}else {
			if(pg_affected_rows($stmt) !== 1){
				throw new Exception('Saving the product did not work out. Please try again.');
			}
		}
			return true;
	}

	public static function edit($id, $name,	$price){
		global $db;		

		$query = 'UPDATE PUBLIC.PRODUCT SET
								NAME = $1,
								PRICE = $2,
								MODIFIEDDATE = CURRENT_TIMESTAMP
							WHERE ID = $3
						';

		$stmt = pg_query_params($db, $query, array($name, $price, $id));
			if (!$stmt) {
				throw new Exception('Editing the product did not work out. Please try again.');
			}else {
				if(pg_affected_rows($stmt) !== 1){
					throw new Exception('Editing the product did not work out. Please try again.');
				}
			}
		return true;
	}

	public static function delete($id){
		global $db;
		$query = 'DELETE FROM PUBLIC.PRODUCT WHERE ID = $1';
		
		$stmt = pg_query_params($db, $query, array($id));
		if ($stmt) {
				throw new Exception('Deleting the product did not work out. Please try again.');
			}
				return true;
	}

}

?>
