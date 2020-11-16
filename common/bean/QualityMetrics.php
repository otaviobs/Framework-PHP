<?php

class QualityMetrics {

	private $id;
	private $createdDate;
	private $updatedDate;
	private $minCleanServerRatio;
	private $targetCleanServerRatio;
	private $minCleanServer;
	private $minTicketLinkageRatio;
	private $targetTicketLinkageRatio;
	private $minTicketLinkage;
	private $minLabeledProblematicServerRatio;
	private $maxLabeledProblematicServerRatio;
	private $targetLabeledProblematicServerRatio;
	private $minLabeledProblematicServer;
	private $minPredictedProblematicServerRatio;
	private $maxPredictedProblematicServerRatio;
	private $targetPredictedProblematicServerRatio;
	private $minPredictedProblematicServer;
	private $minSuTicketsRatio;
	private $targetSuTickets_ratio;
	private $minSuTickets;
	private $startDate;
	private $endDate;
	private $createdBy;

	public function getId() {
		return $this->id;
	}

	public function getCreatedDate()
	{
		return $this->createdDate;
	}

	public function getUpdatedDate()
	{
		return $this->updatedDate;
	}

	public function getMinCleanServerRatio()
	{
		return $this->minCleanServerRatio;
	}

	public function getTargetCleanServerRatio()
	{
		return $this->targetCleanServerRatio;
	}

	public function getMinCleanServer()
	{
		return $this->minCleanServer;
	}

	public function getMinTicketLinkageRatio()
	{
		return $this->minTicketLinkageRatio;
	}

	public function getTargetTicketLinkageRatio()
	{
		return $this->targetTicketLinkageRatio;
	}

	public function getMinTicketLinkage()
	{
		return $this->minTicketLinkage;
	}

	public function getMinLabeledProblematicServerRatio()
	{
		return $this->minLabeledProblematicServerRatio;
	}

	public function getMaxLabeledProblematicServerRatio()
	{
		return $this->maxLabeledProblematicServerRatio;
	}

	public function getTargetLabeledProblematicServerRatio()
	{
		return $this->targetLabeledProblematicServerRatio;
	}

	public function getMinLabeledProblematicServer()
	{
		return $this->minLabeledProblematicServer;
	}

	public function getMinPredictedProblematicServerRatio()
	{
		return $this->minPredictedProblematicServerRatio;
	}

	public function getMaxPredictedProblematicServerRatio()
	{
		return $this->maxPredictedProblematicServerRatio;
	}

	public function getTargetPredictedProblematicServerRatio()
	{
		return $this->targetPredictedProblematicServerRatio;
	}

	public function getMinPredictedProblematicServer()
	{
		return $this->minPredictedProblematicServer;
	}

	public function getMinSuTicketsRatio()
	{
		return $this->minSuTicketsRatio;
	}

	public function getTargetSuTickets_ratio()
	{
		return $this->targetSuTickets_ratio;
	}

	public function getMinSuTickets()
	{
		return $this->minSuTickets;
	}

	public function getStartDate()
	{
		return $this->startDate;
	}

	public function getEndDate()
	{
		return $this->endDate;
	}
	
	public function getCreatedBy()
	{
		return $this->createdBy;
	}
		
	public function __construct(array $fields) {
		$this->id = $fields['ID'];
		$this->createdDate = $fields['CREATED_DATE'];
		$this->updatedDate = $fields['UPDATED_DATE'];
		$this->minCleanServerRatio = $fields['MIN_CLEAN_SERVER_RATIO'];
		$this->targetCleanServerRatio = $fields['TARGET_CLEAN_SERVER_RATIO'];
		$this->minCleanServer = $fields['MIN_CLEAN_SERVER'];
		$this->minTicketLinkageRatio = $fields['MIN_TICKET_LINKAGE_RATIO'];
		$this->targetTicketLinkageRatio = $fields['TARGET_TICKET_LINKAGE_RATIO'];
		$this->minTicketLinkage = $fields['MIN_TICKET_LINKAGE'];
		$this->minLabeledProblematicServerRatio = $fields['MIN_LABELED_PROBLEMATIC_SERVER_RATIO'];
		$this->maxLabeledProblematicServerRatio = $fields['MAX_LABELED_PROBLEMATIC_SERVER_RATIO'];
		$this->targetLabeledProblematicServerRatio = $fields['TARGET_LABELED_PROBLEMATIC_SERVER_RATIO'];
		$this->minLabeledProblematicServer = $fields['MIN_LABELED_PROBLEMATIC_SERVER'];
		$this->minPredictedProblematicServerRatio = $fields['MIN_PREDICTED_PROBLEMATIC_SERVER_RATIO'];
		$this->maxPredictedProblematicServerRatio = $fields['MAX_PREDICTED_PROBLEMATIC_SERVER_RATIO'];
		$this->targetPredictedProblematicServerRatio = $fields['TARGET_PREDICTED_PROBLEMATIC_SERVER_RATIO'];
		$this->minPredictedProblematicServer = $fields['MIN_PREDICTED_PROBLEMATIC_SERVER'];
		$this->minSuTicketsRatio = $fields['MIN_SU_TICKETS_RATIO'];
		$this->targetSuTickets_ratio = $fields['TARGET_SU_TICKETS_RATIO'];
		$this->minSuTickets = $fields['MIN_SU_TICKETS'];
		$this->startDate = $fields['START_DATE'];
		$this->endDate = $fields['END_DATE'];
		$this->createdBy = $fields['CREATED_BY'];
	}

	public static function loadAll() {
		global $db;

		$fields = fetcher($db,
			"SELECT 
				*
			FROM PUBLIC.QUALITY_METRICS_THRESHOLDS ORDER BY ID DESC",
			array());
		if (count($fields)==0) {
			throw new Exception("No record found");
		}
		
		//Formatting dates
		foreach ($fields as $array_key => $array) {
			
			foreach ($array as $key => $val) {
				
				if($key == "CREATED_DATE" || $key == "UPDATED_DATE" || $key == "START_DATE" || $key == "END_DATE")
					$array[$key] = date("Y-m-d", strtotime($val));
				
				if($key == "END_DATE" && empty($val))
					$array[$key] = "Actual";
				
				$fields[$array_key] = $array;
				
			}

		}
		
		return $fields;
	}

	public static function loadWithPagination($currentpage, $limit, $order) {
		global $db;

		switch ($order) {
			case 1:
				$table = "min_clean_server";
				break;
			case 2:
				$table = "min_ticket_linkage";
				break;
			case 3:
				$table = "min_labeled_problematic_server";
				break;
			case 4:
				$table = "start_date";
				break;
			case 5:
				$table = "end_date";
				break;
			default:
				throw new Exception('Invalid option for order column.');
		}
		
		$query = "SELECT 
						COUNT(*) AS TOTAL
					FROM PUBLIC.QUALITY_METRICS_THRESHOLDS";

		$totalRow = fetcher($db, $query, array());
		
		$totalPages = ceil($totalRow[0]['TOTAL'] / $limit);
		$offset = (($currentpage - 1) * $limit);
		$start = $offset + 1;
		$end = min(($offset + $limit), $totalRow[0]['TOTAL']);
		$offsetend = $end - 1;
		
		$fields = fetcher($db,
		"SELECT 
			*
		FROM PUBLIC.QUALITY_METRICS_THRESHOLDS ORDER BY $table DESC
		LIMIT ? OFFSET ?",
		array($limit, $offset));

		//Formatting dates
		foreach ($fields as $array_key => $array) {
			
			foreach ($array as $key => $val) {
				
				if($key == "CREATED_DATE" || $key == "UPDATED_DATE" || $key == "START_DATE" || $key == "END_DATE")
					$array[$key] = date("Y-m-d", strtotime($val));
				
				if($key == "END_DATE" && empty($val))
					$array[$key] = "Actual";
				
				$fields[$array_key] = $array;
				
			}

		}
		
		// if (count($fields)==0) {
		// 	throw new Exception("No properte found");
		// }
		$result = array("records" => $fields, "endPage" => $totalPages);

		return $result;
	}
	
	public static function loadWithOrdenation($currentpage, $limit, $order) {
		global $db;
		
		switch ($order) {
			case 1:
				$table = "min_clean_server";
				break;
			case 2:
				$table = "min_ticket_linkage";
				break;
			case 3:
				$table = "min_labeled_problematic_server";
				break;
			case 4:
				$table = "start_date";
				break;
			case 5:
				$table = "end_date";
				break;
			default:
				throw new Exception('Invalid option for order column.');
		}
		
		$query = "SELECT 
						COUNT(*) AS TOTAL
					FROM PUBLIC.QUALITY_METRICS_THRESHOLDS";

		$totalRow = fetcher($db, $query, array());
		
		$totalPages = ceil($totalRow[0]['TOTAL'] / $limit);
		$offset = (($currentpage - 1) * $limit);
		$start = $offset + 1;
		$end = min(($offset + $limit), $totalRow[0]['TOTAL']);
		$offsetend = $end - 1;
		
		$fields = fetcher($db,
		"SELECT 
			*
		FROM PUBLIC.QUALITY_METRICS_THRESHOLDS ORDER BY $table DESC
		LIMIT ? OFFSET ?",
		array($limit, $offset));
		
		//Formatting dates
		foreach ($fields as $array_key => $array) {
			
			foreach ($array as $key => $val) {
				
				if($key == "CREATED_DATE" || $key == "UPDATED_DATE" || $key == "START_DATE" || $key == "END_DATE")
					$array[$key] = date("Y-m-d", strtotime($val));
				
				if($key == "END_DATE" && empty($val))
					$array[$key] = "Actual";
				
				$fields[$array_key] = $array;
				
			}

		}
		
		$result = array("records" => $fields, "endPage" => $totalPages);

		return $result;
	}
	
	public static function loadWithOrdenationOld($order) {
		global $db;

		switch ($order) {
			case 1:
				$table = "min_clean_server";
				break;
			case 2:
				$table = "min_ticket_linkage";
				break;
			case 3:
				$table = "min_labeled_problematic_server";
				break;
			case 4:
				$table = "start_date";
				break;
			case 5:
				$table = "end_date";
				break;
			default:
				throw new Exception('Invalid option for order column.');
		}

		$fields = fetcher($db,
		"SELECT 
			*
		FROM PUBLIC.QUALITY_METRICS_THRESHOLDS ORDER BY $table DESC");
		
		//Formatting dates
		foreach ($fields as $array_key => $array) {
			
			foreach ($array as $key => $val) {
				
				if($key == "CREATED_DATE" || $key == "UPDATED_DATE" || $key == "START_DATE" || $key == "END_DATE")
					$array[$key] = date("Y-m-d", strtotime($val));
				
				if($key == "END_DATE" && empty($val))
					$array[$key] = "Actual";
				
				$fields[$array_key] = $array;
				
			}

		}
		
		$result = array("records" => $fields);

		return $result;
	}
	
	public static function loadId($id) {
		global $db;

		$record = fetcher($db,
			"SELECT 
				*
			FROM PUBLIC.QUALITY_METRICS_THRESHOLDS
			WHERE ID = ?",
			array($id));

		if (count($record)==0) {
			throw new Exception("No quality metrics threshold found with ID $id");
		}
		
		return new Account($record[0]);
	}

	public static function save($minCleanServer,
		$minCleanServerRatio,
		$targetCleanServerRatio,

		$minTicketLinkage,
		$minTicketLinkageRatio,
		$targetTicketLinkageRatio,

		/*$minSuTickets,
		$minSuTicketsRatio,
		$targetSuTickets_ratio,*/

		$minLabeledProblematicServer,
		$minLabeledProblematicServerRatio,
		$maxLabeledProblematicServerRatio,
		$targetLabeledProblematicServerRatio

		/*$minPredictedProblematicServer,

		$minPredictedProblematicServerRatio,
		$maxPredictedProblematicServerRatio,
		$targetPredictedProblematicServerRatio*/){		
		
		global $db;
		
		$query_end_date = 'UPDATE public.QUALITY_METRICS_THRESHOLDS
					SET END_DATE = CURRENT_TIMESTAMP
					WHERE END_DATE IS NULL';
		
		$stmt_end_date = db2_prepare($db,$query_end_date);

		if ($stmt_end_date) {
			$ex = db2_execute($stmt_end_date);
		}
		
		$query = 'INSERT INTO PUBLIC.QUALITY_METRICS_THRESHOLDS (
								CREATED_DATE,
								MIN_CLEAN_SERVER_RATIO,
								TARGET_CLEAN_SERVER_RATIO,
								MIN_CLEAN_SERVER,
								MIN_TICKET_LINKAGE_RATIO,
								TARGET_TICKET_LINKAGE_RATIO,
								MIN_TICKET_LINKAGE,
								MIN_LABELED_PROBLEMATIC_SERVER_RATIO,
								MAX_LABELED_PROBLEMATIC_SERVER_RATIO,
								TARGET_LABELED_PROBLEMATIC_SERVER_RATIO,
								MIN_LABELED_PROBLEMATIC_SERVER,
								
								START_DATE,
								CREATED_BY
							) VALUES
							(
								CURRENT_TIMESTAMP, 
								?, 
								?, 
								?, 
								?, 
								?, 
								?,
								?, 
								?, 
								?, 
								?, 
								CURRENT_TIMESTAMP,
								?
							)';

		$stmt = db2_prepare($db,$query);

		if ($stmt) {
			$ex = db2_execute($stmt, 
				array(
					$minCleanServerRatio,
					$targetCleanServerRatio,
					$minCleanServer,
					$minTicketLinkageRatio,
					$targetTicketLinkageRatio,
					$minTicketLinkage,
					$minLabeledProblematicServerRatio,
					$maxLabeledProblematicServerRatio,
					$targetLabeledProblematicServerRatio,
					$minLabeledProblematicServer,
					$_SESSION['user']->getId()
				)
			);

			//Check if the response of execution works
			if (!$ex) {
				
				//Check which type of error
				if(db2_stmt_error($stmt) == "23505"){
					throw new Exception('ID already exists for another quality metrics, please review.');
				}else{					
					throw new Exception('Saving the quality metrics thresholds did not work. Please try again.');
				}
			}
			else {
				return true;
			}
		}
	}

}

?>
