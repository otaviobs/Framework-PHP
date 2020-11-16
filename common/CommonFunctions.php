<?php
########################CommonFunctions########################################
#############################DB################################################
function sql_debug($sql_string, array $params = null) {
    if (!empty($params)) {
        $indexed = $params == array_values($params);
        foreach($params as $k=>$v) {
            if (is_object($v)) {
                if ($v instanceof DateTime) $v = $v->format('Y-m-d H:i:s');
                else continue;
            }
            elseif (is_string($v)) $v="'$v'";
            elseif ($v === null) $v='NULL';
            elseif (is_array($v)) $v = implode(',', $v);
            
            if ($indexed) {
                $sql_string = preg_replace('/\?/', $v, $sql_string, 1);
            }
            else {
                if ($k[0] != ':') $k = ':'.$k; //add leading colon if it was left out
                $sql_string = str_replace($k,$v,$sql_string);
            }
        }
    }
    $sql_string =  preg_replace("/(\r\n|\n|\r){2,}/", "$1",$sql_string);
    $sql_string = str_replace("\t"," ",$sql_string);
    return $sql_string;
}

function columnFetcher($db, $query,$par = array() ){
$query = queryDBToPG($query);
$res=array();
$ex = pg_query_params($db, $query, $par);
if($ex) {
    while($row = pg_fetch_assoc($ex)) {
        $rowUpper = upperKey($row);
        array_push($res, array_shift($rowUpper));
    }
}
return $res;
}

function upperKey($array = array()){
    foreach($array as $k => $v){
        $upper[strtoupper($k)] = $v;
    }
    return $upper;
}

function queryDBToPG($query){
    $qt = substr_count($query,'?');
    $from = '/'.preg_quote('?', '/').'/';
    if($qt>0){
        for ($i = 1; $i <= $qt; $i++){
             $to = preg_quote('$'.$i, '/');
             $query = preg_replace($from, $to, $query,1);
        }
        if(substr_count($query,';') > 0){
                $query = str_replace(";","",$query);
        }
    }

    return $query;
}

function fetcher($db, $query,$par = array()){
$query = queryDBToPG($query);
$res=array();
$ex = pg_query_params($db, $query, $par);
if($ex) {
    try{
        while($row = pg_fetch_assoc($ex)) {
            $rowUpper = upperKey($row);
            array_push($res, $rowUpper);
        }
    }catch(Exception $e){echo($e->getMessage());}
}
return $res;
}

function getLastedModelWithCDIR($db, $cdir){
	$query = "SELECT DISTINCT sg.MODEL_ID, m.PUBLISHEDON FROM PUBLIC.SERVERGROUP sg
	              INNER JOIN PUBLIC.ACCOUNT a ON sg.ACCOUNT_ID=a.ID
		      INNER JOIN PUBLIC.MODEL m ON m.ID=sg.MODEL_ID WHERE m.PUBLISHED=? AND a.cdirid=? ORDER BY m.PUBLISHEDON DESC";
	return fetcher($db, $query, array("Y",$cdir));
}

function grabAccounts($db){
## Grab from DB accounts : [ACC, NAME, PLANCOUNT]
$query = "SELECT r.ACC, a.name, COUNT(*) as PLANCOUNT 
	FROM PUBLIC.PUSH_REPORT1 r 
	INNER JOIN PUBLIC.ACCOUNT a ON a.DISPATCHNAME = r.acc 
	GROUP BY ACC, name ORDER BY ACC";
return fetcher($db, $query);
}

function selectedAccShortBySGID($db,$sgid){
 	$acc = fetcher($db,
			"SELECT distinct a.dispatchname as DISPATCHNAME
	FROM PUBLIC.SERVERGROUP SG 
	INNER JOIN PUBLIC.ACCOUNT A ON A.ID=sg.ACCOUNT_ID 
	WHERE sg.ID=? "
		,array($sgid));
 	return $acc[0]['DISPATCHNAME'];
}

function getAccountsByModelID($db,$modelId){
	## Grab from DB accounts : [ACC, NAME] by Model_ID
$query = "SELECT distinct a.dispatchname as ACC, a.name as NAME 
	FROM PUBLIC.SERVERGROUP GA 
	INNER JOIN PUBLIC.ACCOUNT A ON A.ID=GA.ACCOUNT_ID 
	WHERE MODEL_ID=? group by a.dispatchname, a.name ORDER BY a.name ";
return fetcher($db, $query,array($modelId));
}

function grabHosts($db,$sgid){
## Grab from DB hosts by $sgid : [SERVER_ID, HOSTNAME,MODEL_ID]
$query = "SELECT SERVER_ID, HOSTNAME,MODEL_ID 
	FROM public.lookup_groupserver where SERVERGROUP_ID= ? ";

	return fetcher($db, $query,array($sgid));
}

####################### GET parameters ########################################

#### If $getPar is in $possibleValues array -> returns $getPar else returns default
function checkGetParameter($getPar,$default,$possibleValues){
if ((!isset($_GET[$getPar]))||!in_array($_GET[$getPar],$possibleValues))
	return $default;
else
	return $_GET[$getPar];
}

function verifyAllGetParameters(){
    if (!isset($_GET)) return true;
    
    if (isset($_GET['m']) &&
        !preg_match("/^[a-z0-9]{1,4}$/", $_GET['m']) )
        return false;
        if (isset($_GET['sgid']) &&
            !preg_match("/^[0-9]{4,8}$/", $_GET['sgid']) )
            return false;
            if (isset($_GET['acc']) &&
                !preg_match("/^[A-Z0-9]{1,7}$/", $_GET['acc']) )
                return false;
                if (isset($_GET['t']) &&
                    !preg_match("/^(psp)|(ip)|(o)|(cg)|(as)$/", $_GET['t']) )
                    if (isset($_GET['f']) &&
                        !preg_match("/^(psp)|(ip)|(o)|(cg)|(as)$/", $_GET['f']) )
                        if (isset($_GET['from']) &&
                            !preg_match("/^(psp)|(ip)|(o)|(cg)|(as)$/", $_GET['from']) )
                            if (isset($_GET['p']) &&
                                !preg_match("/^(na)|(access)$/", $_GET['p']) )
                                if (isset($_GET['type']) &&
                                    !preg_match("/^(server-group)|(server-report)|(analysedserver-report)|(linkedticket-report)$/", $_GET['type']) )
                                    return false;
                                    if (isset($_GET['r'])
                                        && is_numeric($_GET['r']))
                                        return false;
                                        if (isset($_GET['sid'])
                                            && !is_numeric($_GET['sid']))
                                            return false;
                                            //TODO: _GET['a'] - improvement-plan [NOK] "add servers"
                                            //TODO: _GET['out'] - login [OK] "logging out"
                                            //TODO: _GET['rd'] - login [NOK] "redirect"
                                            //TODO: _GET['err'] - account-summary, analyzed-servers [NOK] "used to change title and header"
                                            //TODO: _GET['title'] account-summary, analyzed-servers [NOK] "used to change title and header"
                                            return true;
}

#### Breaks get parameter $getString like "id=1&txt='something'" 
## into array with key-value pairs 
## $getString - get parameters after "someScript.php?"
function splitGetParametersString($getString){
	$VARS=array();
	$pr = explode("&",$getString);
	foreach ($pr as &$kv){## Get key-value pairs (get_parameter=value)
		$kvp = explode("=",$kv);
		if(count($kvp)==2)## Check if no error occured while parsing - expecting 2 entries.
			$VARS[$kvp[0]]=$kvp[1];
	}
	return $VARS;
}



###########################User rights management##############################

function in_arrayi($needle, $haystack) {
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}
/*function in_arrayi($needle, $haystack) {
    return in_array(strtolower($needle), $haystack);
}*/

function userHasRightsForAccount($accDispatchname,$modelId){
try{
	if(!isset($_SESSION))session_start();
		$usr = $_SESSION["user"];
		if($usr->isSuperUser()) return true;
		$accounts=$usr->getAccounts();

		// 1st check: from session data. if fails, refresh authorization data.
		if (!in_array($accDispatchname, getColumn($accounts,'DISPATCHNAME')) || !in_array($modelId, getColumn($accounts,'MODEL_ID'))) {
			$usr->refreshAllowedAccounts();
			$accounts=$usr->getAccounts();
			return false;
		}
		else {
		    return true;
		}
		
		// 2nd check: return result as-is
		// return in_array($accDispatchname, getColumn($accounts,'DISPATCHNAME'));

	} catch(Exception $e){
		#echo 'Message: ' .$e->getMessage();
		return false;
	}
}


function getColumn($matrix, $cname){
$res=array();
    foreach($matrix as &$row) {
		array_push($res, $row[$cname]);
	} 
return $res;
}




function filterForAllowedAccounts($accs){
try{
	if(!isset($_SESSION))session_start();
		$usr = $_SESSION["user"];
		if($usr->isSuperUser()) return $accs;
		## If not super user - find appropriate accounts
		$uaccs = $usr->getAccounts();
		$res=array();
		 foreach ($accs as &$row){ 
			  if (in_array($row['ACC'],$uaccs)){ 
				array_push($res, $row); 
			  } 
			}
		return($res);
	}catch(Exception $e){
		#echo 'Message: ' .$e->getMessage();
		return array();
	}
}

function dateToIso(DateTime $t) {
	return $t->format("Y-m-d");
}


function timeToIso(DateTime $t) {
	return $t->format("Y-m-d H:i:s");
}

function timeFormatForTicket(DateTime $t) {
	return $t->format("m/d/Y H:i");
}

function dateToLocaleAware(DateTime $t) {
	//TODO
	return date_format();
}


function timeToLocaleAware(DateTime $t) {
	//TODO
	return date_format();
}

function dateToRFC(DateTime $t) {
	return $t->format("M d, Y");
}


function dateToRelative(DateTime $t)
{
	$ts=$t->getTimestamp();
    if(!ctype_digit($ts))
        $ts = strtotime($ts);

    $diff = time() - $ts;
    if($diff == 0)
        return 'now';
    elseif($diff > 0)
    {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 60) return 'just now';
            if($diff < 120) return '1 minute ago';
            if($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if($diff < 7200) return '1 hour ago';
            if($diff < 86400) return floor($diff / 3600) . ' hours ago';
        }
        if($day_diff == 1) return 'Yesterday';
        if($day_diff < 7) return $day_diff . ' days ago';
        if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
        if($day_diff < 60) return 'last month';
        return date('F Y', $ts);
    }
    else
    {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0)
        {
            if($diff < 120) return 'in a minute';
            if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
            if($diff < 7200) return 'in an hour';
            if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
        }
        if($day_diff == 1) return 'Tomorrow';
        if($day_diff < 4) return date('l', $ts);
        if($day_diff < 7 + (7 - date('w'))) return 'next week';
        if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
        if(date('n', $ts) == date('n') + 1) return 'next month';
        return date('F Y', $ts);
    }
}

function fixEmptyElems($array) {
	$newArray=array();
	foreach ($array as $val) {
		if ($val=="") $newArray[]="EMPTY";
		else $newArray[]=$val;
	}
	return $newArray;
}

function sanitizeText($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'utf-8');
}

# use array_multisort function to sort by a 2-D array
function array_orderby() {
    $args = func_get_args();
    $data = array_shift($args);
    $sortArgs = array();
    $tempArr = array();
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tempArr[] = array();
            foreach ($data as $key => $row) {
               $tempArr[count($tempArr)-1][$key] = $row[$field];
            }
            $sortArgs[] = &$tempArr[count($tempArr)-1];
            $sortArgs[] = &$args[$n+1];
            //unset(&$tmp);
        }
    }
    $sortArgs[] = &$data;
    call_user_func_array('array_multisort', $sortArgs);
    //array_multisort($args);
    return $data;
}

function redirect($url){
    if (headers_sent()){
        die('<script type="text/javascript">window.location=\''.$url.'\';</script‌​>');
    }else{
        header('Location: ' . $url);
        die();
    }
}

function filter($value, $modes = array('sql', 'html', 'others'), $request){
    if (!is_array($modes)) {
        $modes = array($modes);
    }
    if (is_string($value)) {
        foreach ($modes as $type) {
            $value = doFilter($value, $type, $request);
        }
    } else {
        foreach ($modes as $type) {
            foreach ($value as $key => $valFilter) {
                if(is_string($valFilter)){
                    $value[$key] = doFilter($valFilter, $type, $request);
                }
                else{
                    $value[$key] = $valFilter;
                }
            }
        }
    }
    
    return $value;
}

function doFilter($value, $mode, $request) {
    $length = strlen($value);
    switch ($mode) {
        case 'html':
            //            $value = trim(strip_tags($value));
            //            $value = addslashes($value);
            $value = htmlspecialchars($value, ($request=='post')?ENT_NOQUOTES:ENT_QUOTES);
            break;
            
        case 'sql':
            $value = preg_replace("/(select |insert into |delete from | where |drop table |show tables |#|\*|–|\\\\)/i","",$value);
            // $value = preg_replace(sql_regcase('/(select |insert into |delete from | where |drop table |show tables |#|\*|\\\\)/'),'',$value);
            $value = trim($value);
            break;
            
        case 'others':
            $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
            $value = trim($value);
            break;
    }
    return $value;
}

// generate token with encrypt on the user id
function generateTokenWithUser($idUser){
    return md5(uniqid($idUser, true));
}

// validated CSV
function validateCSV($file) {
    $return = true;
    $csv_mimetypes = array(
        'text/csv',
        'text/plain',
        'application/csv',
        'text/comma-separated-values',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'text/anytext',
        'application/octet-stream',
        'application/txt',
    );
    
    //check for quantity files upload
    if (count($file['tmp_name'])>1)  {
        $return = false;
        throw new Exception("Only one file can be uploaded at a time.");
    }

    //check for file size
    // File php.ini | upload_max_filesize and post_max_size
    $maxFilesize = str_replace('K', '',ini_get('post_max_size'));
    // convert Kb to byte
    $maxFilesize *= 1048576;
    if ($file['size'] > $maxFilesize)  {
        $return = false;
        throw new Exception('File exceeds the limit of '.ini_get('post_max_size').'b. Please try again.');
    }
    
    // check for extension file
    if (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) != 'csv')  {
        throw new Exception("It is not CSV!");
    }
    
    // check for mimes types for csv
    $infMime = finfo_open(FILEINFO_MIME);
    $mimeDetected = finfo_file($infMime, $file['tmp_name']);
    preg_match('/(\w+)\/(\w+)/', $mimeDetected, $mimeDetectedCleaned);
    if (!in_array($mimeDetectedCleaned[0], $csv_mimetypes)){
        $return = false;
        throw new Exception("This type of file is not allowed.");
    }
    
    finfo_close($infMime);
    
    //check filename length
    if (strlen($file['name'])>150)  {
        $return = false;
        throw new Exception("File name is not validate because it is exceeding limit filename length!");
    }
    
    return $return;
}

function returnJSONError($status,$message) {
    header('Content-Type: application/json');
	die(json_encode(
		array(
			'status'=>$status,
			'message'=>$message
		)
	));
}

function returnJSONSuccess($dataArray){
    header('Content-Type: application/json');
	$successMessage=array_merge(array('status'=>'200'), $dataArray);
	die(json_encode($successMessage));
}
?>
