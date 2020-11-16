<?php
require_once("common/accesscontrol.php");

//var_dump($_SESSION);//die();

try{
  if(isset($_GET['page']) && isset($_GET['action'])){
    if($_POST['stoken'] != $_SESSION['user']->getTokenCSFR()){
      returnJSONError(500, 'Invalid request.');
    }
    if($_GET['page'] == 'mcproperties'){
  // ************ SECTION EDIT RECORD ************ 
      if($_GET['action'] == 'new'){
          if (!isset($_POST['newMachineClass'])) {
            returnJSONError(500, "No machine class defined");
          }
          elseif(!isset($_POST['newTad'])) {
            returnJSONError(500, "No TAD defined");
          }elseif(!isset($_POST['newArchitecture'])) {
            returnJSONError(500, "No architecture defined");
          }
          $mcProperties = MCProperties::save($_POST['newMachineClass'], $_POST['newTad'], $_POST['newArchitecture']);
          if($mcProperties){
            returnJSONSuccess(array("message" => "The property created.", $mcProperties));
          }
      }

  // ************ SECTION NEW RECORD ************ 
      elseif($_GET['action']=='edit'){
        if (!isset($_POST['editId']) || !is_numeric($_POST['editId'])) {
          returnJSONError(500, "No id property defined");
        }
        if (!isset($_POST['editMachineClass'])) {
          returnJSONError(500, "No machine class defined");
        }
        if (!isset($_POST['editTad'])) {
          returnJSONError("No TAD defined");
        }
        if (!isset($_POST['editArchitecture'])) {
          returnJSONError("No architecture defined");
        }
        try {
          $oldRecord = MCProperties::loadId($_POST['editId']);
          if($oldRecord->getMachineClass() == $_POST['editMachineClass']){
            if($oldRecord->getTad() == $_POST['editTad']){
              if($oldRecord->getArchitecture() == $_POST['editArchitecture']){
                die(
                  returnJSONError(200, "No records update")
                );
              }
            }
          }
          
          $result = MCProperties::edit($_POST['editId'], $_POST['editMachineClass'], $_POST['editTad'], $_POST['editArchitecture']);
          returnJSONSuccess(array("message" => "The record update success."));
        } catch (Exception $e) {
          returnJSONError(500, $e->getMessage());
        }
      }

  // ************ SECTION DELETE RECORD ************ 
      elseif($_GET['action']=='delete'){
        if (!isset($_POST['id'])) {
          returnJSONError(500, "No property defined");
        }

        try{
          $mcProperties = MCProperties::delete($_POST['id']);
          if($mcProperties){
            $loadRecords = MCProperties::loadAll();
            returnJSONSuccess(array("message" => "The property was deleted"));
          }
        } catch (Exception $e){
          returnJSONError(500, $e->getMessage());
        }
      }

  // ************ SECTION LOAD RECORDS ************ 
      elseif($_GET['action']=='load'){
        if (isset($_POST['search'])) {
          $tags = trim(strip_tags($_POST['search']));
          $result = MCProperties::loadWithPagination($_POST['nPage'], $_POST['limit'], $tags);
          // var_dump($result);die();
        }
        elseif (isset($_POST['data']) && $_POST['data'] == 'all') {
          $result = MCProperties::loadAll();
        }
        // var_dump($result);die();
        returnJSONSuccess($result);
      }
      elseif($_GET['action']=='pagination'){
        if (isset($_POST['limit']) && isset($_POST['nPage'])) {
          $tags = trim(strip_tags($_POST['search']));
          $result = MCProperties::loadWithPagination($_POST['nPage'], $_POST['limit'], $tags);
        }
        returnJSONSuccess($result);
      }
      else
        returnJSONError(500, 'Invalid action.');

// -------------------------------------------------------------------------------------------------------------
// ------------------------------------------------ OS CLASS ---------------------------------------------------
// -------------------------------------------------------------------------------------------------------------

    }
    elseif($_GET['page'] == 'osclass'){
    // ************ SECTION NEW RECORD ************ 
      if($_GET['action'] == 'new'){
        if (!isset($_POST['newOSProvider'])) {
          returnJSONError(500, "No OS Provider defined");
        }
        elseif(!isset($_POST['newOSName'])) {
          returnJSONError(500, "No OS Name defined");
        }
        elseif(!isset($_POST['newOSVersion'])) {
          returnJSONError(500, "No OS Version defined");
        }
        elseif(!isset($_POST['newOOSDate'])) {
          returnJSONError(500, "No Out of Support Date defined");
        }
        $class = OSClass::save($_POST['newOSProvider'], $_POST['newOSName'], $_POST['newOSVersion'], $_POST['newOOSDate']);
        if($class){
          returnJSONSuccess(array("message" => "The OS Class was created.", $class));
        }
    }

  // ************ SECTION EDIT RECORD ************ 
      elseif($_GET['action']=='edit'){
        if (!isset($_POST['editId']) || !is_numeric($_POST['editId'])) {
          returnJSONError(500, "No record defined");
        }
        if (!isset($_POST['editOSProvider'])) {
          returnJSONError(500, "No OS Provider defined");
        }
        if (!isset($_POST['editOSName'])) {
          returnJSONError("No OS Name defined");
        }
        if (!isset($_POST['editOSVersion'])) {
          returnJSONError("No OS Version defined");
        }
        if (!isset($_POST['editOOSDate'])) {
          returnJSONError("No Out of Support Date defined");
        }
        $oldRecord = OSClass::loadId($_POST['editId']);
        if($oldRecord->getOSProvider() == $_POST['editOSProvider']){
          if($oldRecord->getOSName() == $_POST['editOSName']){
            if($oldRecord->getOSVersion() == $_POST['editOSVersion']){
              die(
                returnJSONError(200, "No records update")
              );
            }
          }
        }
        
        $result = OSClass::edit($_POST['editId'], $_POST['editOSProvider'], $_POST['editOSName'], $_POST['editOSVersion'], $_POST['editOOSDate']);
        returnJSONSuccess(array("message" => "The record update success."));
      
      }

  // ************ SECTION DELETE RECORD ************ 
      elseif($_GET['action']=='delete'){
        if (!isset($_POST['id'])) {
          returnJSONError(500, "No property defined");
        }
        $mcProperties = OSClass::delete($_POST['id']);
        if($mcProperties){
          // $loadRecords = OSClass::loadAll();
          returnJSONSuccess(array("message" => "The property was deleted"));
        }
      }

  // ************ SECTION LOAD RECORDS ************ 
      elseif($_GET['action']=='load'){
        if (isset($_POST['search'])) {
          $tags = trim(strip_tags($_POST['search']));
          $result = OSClass::loadWithPagination($_POST['nPage'], $_POST['limit'], $tags);
          // var_dump($result);die();
        }
        elseif (isset($_POST['data']) && $_POST['data'] == 'all') {
          $result = OSClass::loadAll();
        }
        returnJSONSuccess($result);
      }
      elseif($_GET['action']=='pagination'){
        if (isset($_POST['limit']) && isset($_POST['nPage'])) {
          $tags = trim(strip_tags($_POST['search']));
          $result = OSClass::loadWithPagination($_POST['nPage'], $_POST['limit'], $tags);
        }
        returnJSONSuccess($result);
      }
      else
        returnJSONError(500, 'Invalid action.');
	
// -------------------------------------------------------------------------------------------------------------
// ------------------------------------------------ ACCOUNTS ---------------------------------------------------
// -------------------------------------------------------------------------------------------------------------

    }
    elseif($_GET['page'] == 'accounts'){
	  
	  // ************ SECTION NEW RECORD ************ 
      if($_GET['action'] == 'new'){
        if (!isset($_POST['newName'])) {
          returnJSONError(500, "No Name defined");
        }
        elseif(!isset($_POST['newShortcode'])) {
          returnJSONError(500, "No Shortcode defined");
        }
        elseif(!isset($_POST['newCdirid'])) {
          returnJSONError(500, "No CDIRID defined");
        }
        $class = Account::save($_POST['newName'], $_POST['newShortcode'], $_POST['newCdirid']);
        if($class){
          returnJSONSuccess(array("message" => "The Account was created.", $class));
        }
	  }
	  
	  // ************ SECTION EDIT RECORD ************ 
      elseif($_GET['action']=='edit'){
        if (!isset($_POST['editId']) || !is_numeric($_POST['editId'])) {
          returnJSONError(500, "No record defined");
        }
        elseif (!isset($_POST['editName'])) {
          returnJSONError(500, "No Name defined");
        }
        elseif(!isset($_POST['editShortcode'])) {
          returnJSONError(500, "No Shortcode defined");
        }
        elseif(!isset($_POST['editCdirid'])) {
          returnJSONError(500, "No CDIRID defined");
        }
        $oldRecord = Account::loadId($_POST['editId']);
        if($oldRecord->getName() == $_POST['editName']){
          if($oldRecord->getShortcode() == $_POST['editShortcode']){
            if($oldRecord->getCdirId() == $_POST['editCdirid']){
              die(
                returnJSONError(200, "No records update")
              );
            }
          }
        }
        
        $result = Account::edit($_POST['editId'], $_POST['editName'], $_POST['editShortcode'], $_POST['editCdirid']);
        returnJSONSuccess(array("message" => "The record update success."));
      
      }

	  // ************ SECTION DELETE RECORD ************ 
      elseif($_GET['action']=='delete'){
        if (!isset($_POST['id'])) {
          returnJSONError(500, "No property defined");
        }
        $accountDelete = Account::delete($_POST['id']);
        if($accountDelete){
          returnJSONSuccess(array("message" => "The property was deleted"));
        }
      }
	  
	  // ************ SECTION LOAD RECORDS ************ 
      elseif($_GET['action']=='load'){
        if (isset($_POST['search'])) {
          $tags = trim(strip_tags($_POST['search']));
          $result = Account::loadWithPagination($_POST['nPage'], $_POST['limit'], $tags);
        }
        elseif (isset($_POST['data']) && $_POST['data'] == 'all') {
          $result = Account::loadAll();
        }
        returnJSONSuccess($result);
      }
      elseif($_GET['action']=='pagination'){
        if (isset($_POST['limit']) && isset($_POST['nPage'])) {
          $tags = trim(strip_tags($_POST['search']));
          $result = Account::loadWithPagination($_POST['nPage'], $_POST['limit'], $tags);
        }
        returnJSONSuccess($result);
      }	  
      else
        returnJSONError(500, 'Invalid action.');
	
// -------------------------------------------------------------------------------------------------------------
// -------------------------------------- QUALITY METRICS THRESHOLDS -------------------------------------------
// -------------------------------------------------------------------------------------------------------------
	}elseif($_GET['page'] == 'quality-metrics-thresholds'){
	  
	  // ************ SECTION NEW RECORD ************ 
      if($_GET['action'] == 'new'){
		
		//Clean Server
        if (!isset($_POST['newCleanServerMin'])) {
          returnJSONError(500, "No Clean Server Min defined");
        }
        elseif(!isset($_POST['newCleanServerMinRatio'])) {
          returnJSONError(500, "No Clean Server Min Ratio defined");
        }
		elseif(!isset($_POST['newCleanServerTarget'])) {
          returnJSONError(500, "No Clean Server Target defined");
        }
		
		//Ticket Linkage
		elseif(!isset($_POST['newTicketLinkageMin'])) {
          returnJSONError(500, "No Ticket Linkage Min defined");
        }
		elseif(!isset($_POST['newTicketLinkageMinRatio'])) {
          returnJSONError(500, "No Ticket Linkage Min Ratio defined");
        }
		elseif(!isset($_POST['newTicketLinkageTarget'])) {
          returnJSONError(500, "No Ticket Linkage Target defined");
        }
		
		//SU Tickets
/*		elseif(!isset($_POST['newSuTicketMin'])) {
          returnJSONError(500, "No Su Ticket Min defined");
        }
		elseif(!isset($_POST['newSuTicketMinRatio'])) {
          returnJSONError(500, "No Su Ticket Min Ratio defined");
        }
		elseif(!isset($_POST['newSuTicketTarget'])) {
          returnJSONError(500, "No Su Ticket Target defined");
        }*/
		
		//Labeled Problematic Server
		elseif(!isset($_POST['newLabeledProblematicServerMin'])) {
          returnJSONError(500, "No Labeled Problematic Server Min defined");
        }
		elseif(!isset($_POST['newLabeledProblematicServerMaxRatio'])) {
          returnJSONError(500, "No Labeled Problematic Server Max Ratio defined");
        }
		elseif(!isset($_POST['newLabeledProblematicServerMinRatio'])) {
          returnJSONError(500, "No Labeled Problematic Server Min Ratio defined");
        }
		elseif(!isset($_POST['newLabeledProblematicServerTarget'])) {
          returnJSONError(500, "No Labeled Problematic Server Target defined");
        }
		
		//Predicted Problematic Server
/*		elseif(!isset($_POST['newPredictedProblematicServerMin'])) {
          returnJSONError(500, "No Predicted Problematic Server Min defined");
        }
		elseif(!isset($_POST['newPredictedProblematicServerMaxRatio'])) {
          returnJSONError(500, "No Predicted Problematic Server Max Ratio defined");
        }
		elseif(!isset($_POST['newPredictedProblematicServerMinRatio'])) {
          returnJSONError(500, "No Predicted Problematic Server Min Ratio defined");
        }
		elseif(!isset($_POST['newPredictedProblematicServerTarget'])) {
          returnJSONError(500, "No Predicted Problematic Server Target defined");
        }*/
        $class = QualityMetrics::save(
		//Clean Server
		$_POST['newCleanServerMin'],$_POST['newCleanServerMinRatio'],$_POST['newCleanServerTarget'],
		
		//Ticket Linkage
		$_POST['newTicketLinkageMin'],$_POST['newTicketLinkageMinRatio'],$_POST['newTicketLinkageTarget'],
		
		//SU Tickets
/*		$_POST['newSuTicketMin'],$_POST['newSuTicketMinRatio'],$_POST['newSuTicketTarget'],*/
		
		//Labeled Problematic Server
		$_POST['newLabeledProblematicServerMin'],$_POST['newLabeledProblematicServerMinRatio'],$_POST['newLabeledProblematicServerMaxRatio'],$_POST['newLabeledProblematicServerTarget']
		
		//Predicted Problematic Server
/*		$_POST['newPredictedProblematicServerMin'],$_POST['newPredictedProblematicServerMinRatio'],$_POST['newPredictedProblematicServerMaxRatio'],$_POST['newPredictedProblematicServerTarget']*/);

        if($class){
          returnJSONSuccess(array("message" => "The Quality Metrics was created.", $class));
        }
	  }
	  
	  // ************ SECTION ORDENATION ************ 
      if($_GET['action'] == 'ordenation'){
		  if (isset($_POST['order_column']) && isset($_POST['limit']) && isset($_POST['nPage'])) {	  
          $result = QualityMetrics::loadWithOrdenation($_POST['nPage'], $_POST['limit'], $_POST['order_column']);
        }
        returnJSONSuccess($result);
	  }    
	  elseif($_GET['action']=='pagination'){
        if (isset($_POST['limit']) && isset($_POST['nPage'])) {
          $tags = trim($_POST['search']);
          $result = QualityMetrics::loadWithPagination($_POST['nPage'], $_POST['limit'], $tags);
        }
        returnJSONSuccess($result);
      }	  
      else
        returnJSONError(500, 'Invalid action.');
	  
	
// -------------------------------------------------------------------------------------------------------------
// ----------------------------------------------- PRODUCT -----------------------------------------------------
// -------------------------------------------------------------------------------------------------------------
	}elseif($_GET['page'] == 'product'){

	  // ************ SECTION NEW RECORD ************ 
    if($_GET['action'] == 'new'){

      if (!isset($_POST['newName'])) {
        returnJSONError(500, "No Name defined");
      }
      elseif(!isset($_POST['newPrice'])) {
        returnJSONError(500, "No Price defined");
      }

      $prod = Product::save($_POST['newName'], $_POST['newPrice']);

      if($prod){
        returnJSONSuccess(array("message" => "The Product was created.", $prod));
      }

	  }
  
    // ************ SECTION EDIT RECORD ************ 
    elseif($_GET['action']=='edit'){
      if (!isset($_POST['editId']) || !is_numeric($_POST['editId'])) {
        returnJSONError(500, "No record defined");
      }
      if (!isset($_POST['editName'])) {
        returnJSONError(500, "No product name defined");
      }
      if (!isset($_POST['editPrice'])) {
        returnJSONError(500, "No product price defined");
      }
      $oldRecord = Product::loadId($_POST['editId']);
      if($oldRecord->getName() == $_POST['editName']){
        if($oldRecord->getPrice() == $_POST['editPrice']){
          die(
            returnJSONError(200, "No records update")
          );
        }
      }
      
      $result = Product::edit($_POST['editId'], $_POST['editName'], $_POST['editPrice']);
      returnJSONSuccess(array("message" => "The product was update success."));
    
    }

	  // ************ SECTION LOAD RECORDS ************ 
    elseif($_GET['action']=='load'){
      if (isset($_POST['search'])) {
        $tags = trim(strip_tags($_POST['search']));
        $result = Product::loadWithPagination($_POST['nPage'], $_POST['limit'], $tags);
      }
      elseif (isset($_POST['data']) && $_POST['data'] == 'all') {
        $result = Product::loadAll();
      }
      returnJSONSuccess($result);
    }
	  elseif($_GET['action']=='pagination'){
      if (isset($_POST['limit']) && isset($_POST['nPage'])) {
        $result = Product::loadWithPagination($_POST['nPage'], $_POST['limit'], $_POST['search']);
      }
      returnJSONSuccess($result);
	  }

	  // ************ SECTION DELETE RECORD ************ 
    elseif($_GET['action']=='delete'){
      if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        returnJSONError(500, "No record defined");
      }
      if (!isset($_POST['deleteComment'])) {
        returnJSONError(500, "No Comment defined");
      }
      
      $result = Product::delete($_POST['id']);
      returnJSONSuccess(array("message" => "The record was deleted."));
    
    }
	  
	  else
      returnJSONError(500, 'Invalid action.');
	}
	else
	  returnJSONError(404, 'Page not found.');
  }
} catch (Exception $e){
  returnJSONError(500,$e->getMessage());
}

  
?>