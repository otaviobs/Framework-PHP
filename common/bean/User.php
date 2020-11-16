<?php

class User {

    private static $userIdName = null;

    private $id = null;
    private $name = null;
    private $login = null;
    private $lastAccessedTime = null;
	private $role = null;
	private $accounts = null;
    private $sessionDirectory = null;
    private $tokenCSFR;
    private $last_login_timestamp = null;
	
    public function __construct($login) {
        global $db;
        
        $this->lastAccessedTime = time();
		$this->login = $login;

        $query = 
        "SELECT 
            u.ID, u.USER_NAME, u.ROLE
        FROM 
            PUBLIC.USER u
        WHERE 
            u.EMAIL = $1";
        
        $user = fetcher($db, $query, array($this->login));
        
        if (count($user)==0) {
            throw new Exception(-1);
        }

        $this->id = $user[0]['ID'];
        $this->name = $user[0]['USER_NAME'];
        $this->role = trim($user[0]['ROLE']);


		if ($this->role!='ADMIN') {
            throw new Exception(-2);         
        }

        $d=new DateTime();
        $dateCode = $d->format('YmdHis');     
        $this->sessionDirectory = $this->getShortName().'-'.$dateCode.'-'.session_id();
        $this->tokenCSFR = md5($d->format('YmdHis'));
        $this->last_login_timestamp = time();
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getFirstName() {
        return substr($this->name, 0, strpos($this->name,' '));
    }

    public function getShortName() {
        return substr($this->login, 0, strpos($this->login,'@'));
    }

    public function getLogin() {
        return $this->login;
    }

	public function isSuperUser() {
        return $this->role=='ADMIN';
    }
	
    public function getAccount() {
        return $this->accounts;
    }
    
    public function setLastAccessedTime($lastAccessedTime) {
        $this->lastAccessedTime = $lastAccessedTime;
    }

    public function getLastAccessedTime() {
        return $this->lastAccessedTime;
    }

    public function getSessionDir() {
        return $this->sessionDirectory;
    }

    public function createSessionDirIfNotExists() {
        exec("mkdir -p -m 777 users/$this->sessionDirectory");
        return $this->sessionDirectory;
    }

    public function getTokenCSFR() {
        return $this->tokenCSFR;
    }

    public static function getUserNameBy($userId) {
        global $db;
        if (User::$userIdName==null) {
            $query = 
                "SELECT u.ID, u.USER_NAME 
                from public.user u ";
            User::$userIdName = fetcher($db, $query);
        }

        foreach (User::$userIdName as &$user) {
            if ($user['ID']==$userId)
                return $user['USER_NAME'];
        }
        return 'Deleted User';
    }

    public static function logOut() {
        session_destroy();
        header("HTTP/1.0 503 Service Unavailable");
        header("Location: index.php");
        header("Connection: close");
    }
        
    public function isLoggedIn() {
        //Check if user is still loggedin
        if((time() - $this->last_login_timestamp) > 1800){ // 30 minutes
            $this->last_login_timestamp = 0;
            User::logOut();
        }else{
            $this->last_login_timestamp = time();
        }
    }

}
?>