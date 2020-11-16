<?php

class AccountCredits {

	private $id;
	private $analysis_id;
    private $created_by;
    private $created_date;
	private $cancel_by;
	private $cancel_date;
	private $account_id;
	private $analysis_date;
	private $expiration_date;
	private $comments;
	private $account_name;
	private $status;

	public function getId() {
		return $this->id;
	}
	public function getAnalysisId() {		
			return $this->analysisId;
	}				
	public function getCreatedBy() {		
			return $this->createdBy;
	}				
	public function getCreatedDate() {		
			return $this->createdDate;
	}				
	public function getCancelBy() {		
			return $this->cancelBy;
	}				
	public function getCancelDate() {		
			return $this->cancelDate;
	}				
	public function getAccountId() {		
			return $this->accountId;
	}				
	public function getAnalysisDate() {		
			return $this->analysisDate;
	}				
	public function getExpirationDate() {		
			return $this->expirationDate;
	}				
	public function getComments() {		
			return $this->comments;
	}
	public function getAccountName() {		
			return $this->account_name;
	}
	public function getStatus() {		
			return $this->status;
	}	

	public function __construct(array $fields) {
		$this->id 			  = $fields['ID'];
		$this->analysisId	  = $fields['ANALYSIS_ID'];
		$this->createdBy	  = $fields['CREATED_BY'];
		$this->createdDate	  = $fields['CREATED_DATE'];
		$this->cancelBy	  	  = $fields['CANCEL_BY'];
		$this->cancelDate	  = $fields['CANCEL_DATE'];
		$this->accountId	  = $fields['ACCOUNT_ID'];
		$this->analysisDate	  = $fields['ANALYSIS_DATE'];
		$this->expirationDate = $fields['EXPIRATION_DATE'];
		$this->comments	 	  = $fields['COMMENTS'];
		$this->status	 	  = $fields['STATUS'];
		$this->account_name	  = $fields['ACCOUNT_NAME'];
		
	}

	public static function getAllAccounts() {
		global $db;

		$fields = fetcher($db,
			"SELECT A.ID, A.NAME AS ACCOUNT_NAME,
				SUM(CASE WHEN (AC.ANALYSIS_ID IS NULL AND AC.EXPIRATION_DATE > CURRENT_TIMESTAMP AND AC.CANCEL_BY IS NULL) THEN 1 ELSE 0 END) BALANCE
			FROM PUBLIC.ACCOUNT A
			LEFT JOIN PUBLIC.ACCOUNT_CREDIT AC ON AC.ACCOUNT_ID=A.ID
			GROUP BY A.ID, A.NAME
			ORDER BY  A.NAME",
			array());
		if (count($fields)==0) {
			throw new Exception("No property found");
		}
		
		return $fields;
	}
	
	public static function loadAll() {
		global $db;

		$fields = fetcher($db,
			"SELECT 
				AC.ID, AC.ANALYSIS_ID, AC.CREATED_DATE, AC.CANCEL_DATE, AC.ANALYSIS_DATE, AC.EXPIRATION_DATE, AC.COMMENTS, A.NAME AS ACCOUNT_NAME, UC.USER_NAME as CREATED_BY, UR.USER_NAME as CANCEL_BY,
			 CASE
				WHEN (AC.CANCEL_BY IS NOT NULL) THEN 'CANCELLED'
				WHEN (AC.EXPIRATION_DATE < CURRENT_TIMESTAMP) THEN 'EXPIRED'
				ELSE 'ACTIVE'
			END STATUS,
			CASE
				WHEN (AC.ANALYSIS_ID IS NULL) THEN 'NO ANALYSIS'
				ELSE CAST(AC.ANALYSIS_ID as varchar)
			END ANALYSIS_ID
			FROM PUBLIC.ACCOUNT_CREDIT AC
				INNER JOIN PUBLIC.ACCOUNT A ON AC.ACCOUNT_ID=A.ID
				LEFT JOIN PUBLIC.USER UC ON AC.CREATED_BY=UC.ID
				LEFT JOIN PUBLIC.USER UR ON AC.CANCEL_BY=UR.ID
			ORDER BY ID DESC",
			array());
		if (count($fields)==0) {
			throw new Exception("No property found");
		}
		
		return $fields;
	}

	public static function loadWithPagination($currentpage, $limit, $account_id) {
		global $db;

		$query = "SELECT 
					COUNT(*) AS TOTAL
				FROM PUBLIC.ACCOUNT_CREDIT
				WHERE 
				ACCOUNT_ID = ?";
				
		$totalRow = fetcher($db, $query, array($account_id));

		$totalPages = ceil($totalRow[0]['TOTAL'] / $limit);
		$offset = (($currentpage - 1) * $limit);
		$start = $offset + 1;
		$end = min(($offset + $limit), $totalRow[0]['TOTAL']);
		$offsetend = $end - 1;

		$fields = fetcher($db,
			"SELECT AC.ID, VARCHAR_FORMAT(AC.CREATED_DATE, 'YYYY-MM-DD') CREATED_DATE, VARCHAR_FORMAT(AC.CANCEL_DATE, 'YYYY-MM-DD') CANCEL_DATE, AC.ACCOUNT_ID, VARCHAR_FORMAT(AC.ANALYSIS_DATE, 'YYYY-MM-DD') ANALYSIS_DATE, VARCHAR_FORMAT(AC.EXPIRATION_DATE, 'YYYY-MM-DD') EXPIRATION_DATE, AC.COMMENTS, A.NAME AS ACCOUNT_NAME, UC.USER_NAME as CREATED_BY, UR.USER_NAME as CANCEL_BY,
			 CASE
				WHEN (AC.CANCEL_BY IS NOT NULL) THEN 'CANCELLED'
				WHEN (AC.EXPIRATION_DATE < CURRENT_TIMESTAMP) THEN 'EXPIRED'
				ELSE 'ACTIVE'
			END STATUS,
			CASE
				WHEN (AC.ANALYSIS_ID IS NULL) THEN 'NO ANALYSIS'
				ELSE CAST(AC.ANALYSIS_ID as varchar)
			END ANALYSIS_ID
			FROM PUBLIC.ACCOUNT_CREDIT AC
				INNER JOIN PUBLIC.ACCOUNT A ON AC.ACCOUNT_ID=A.ID
				LEFT JOIN PUBLIC.USER UC ON AC.CREATED_BY=UC.ID
				LEFT JOIN PUBLIC.USER UR ON AC.CANCEL_BY=UR.ID
			WHERE 
				AC.ACCOUNT_ID = ?
			ORDER BY AC.ID DESC
			LIMIT ? OFFSET ?
			",
		array($account_id, $limit, $offset));
		
		$result = array("records" => $fields, "endPage" => $totalPages);

		return $result;
	}
	
	public static function loadId($id) {
		global $db;

		$record = fetcher($db,
			"SELECT 
				*
			FROM PUBLIC.ACCOUNT_CREDIT
			WHERE ID = ?",
			array($id));

		if (count($record)==0) {
			throw new Exception("No property found with ID $id");
		}
		
		return new AccountCredits($record[0]);
	}

	public static function save($account_id, $comment = null){
		global $db;
		
		// db2_autocommit($db, false);

		$query = 'INSERT INTO PUBLIC.ACCOUNT_CREDIT	(CREATED_BY, CREATED_DATE, ACCOUNT_ID, EXPIRATION_DATE, COMMENTS)
		VALUES
		(
			?, CURRENT_TIMESTAMP, ?, CURRENT_TIMESTAMP + 1 month, ?
		)';

		$stmt = db2_prepare($db,$query);
		if ($stmt) {
			$ex = db2_execute($stmt, 
				array(
					$_SESSION['user']->getId(),
					$account_id,
					$comment
				)
			);
			if (!$ex) {
				// db2_rollback($db);
				throw new Exception('Saving the property did not work out. Please try again.');
			}
			else {
				// db2_commit($db);
				// db2_autocommit($db, true);
				return true;
			}
		}
	}

	//The deletion of account credits is add a data at cancel by and cancel date
	public static function delete($id, $comment){
		global $db;		

		//Adjust string in order to add at table
		$user_login = explode("@", $_SESSION['user']->getLogin());

	$comment = "[Cancelled by ".$user_login[0]." on ".date("Y-m-d")."]: ".$comment;

		$query = 'UPDATE PUBLIC.ACCOUNT_CREDIT SET
					CANCEL_BY = ?, CANCEL_DATE = CURRENT_TIMESTAMP, COMMENTS = COMMENTS||chr(10)||?
					WHERE ID = ?';

		$stmt = db2_prepare($db,$query);
		if ($stmt) {
			$ex = db2_execute($stmt, 
				array(
					$_SESSION['user']->getId(),
					$comment,
					$id
				)
			);
			if (!$ex) {
				// db2_rollback($db);
				throw new Exception('Deleting the property did not work out. Please try again.');
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
