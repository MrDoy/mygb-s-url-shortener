<?php
class libDb{
	
	private $ressource;
	private $host;
	private $user;
	private $pass;
	private $base;
	private $shortidpattern;
	private $owner=1;
	
	public function libDb($host='',$user='',$pass='',$base=''){
		include('config.php');
		if($host == '' && $user == '' && $pass == '' && $base == ''){
			$this->host = $config['db']['host'];
			$this->user = $config['db']['user'];
			$this->pass = $config['db']['pass'];
			$this->base = $config['db']['base'];
		}else{
			$this->host = $host;
			$this->user = $user;
			$this->pass = $pass;
			$this->base = $base;
		}
		$this->connect();
		$this->shortidpattern = $config['shortid']['pattern'];
	}
	private function connect(){
		try{
			$this->ressource = mysql_connect($this->host,$this->user,$this->pass);
			mysql_select_db($this->base);
		}catch(Exception $e){
			echo 'Couldn\'t connect to database';	
		}
	}
	public function getUrl($shortid,$properties){
		if(is_array($properties)){
			if(preg_match($this->shortidpattern,$shortid)){
				if($this->ressource!=FALSE){
					try{
						$query = mysql_query('SELECT idtourl.id,idtourl.url FROM idtourl LEFT JOIN keyapi ON keyapi.id=idtourl.keyid LEFT JOIN service ON service.id=keyapi.service WHERE shortid = "'.$shortid.'" AND service.host="'.mysql_real_escape_string($properties['HTTP_HOST']).'"')or die(mysql_error());
						$url = mysql_fetch_assoc($query);
						if(!empty($url)){
							
							include('config.php');
							if($config['params']['allowstats']==true){
								$this->stats($properties,$url['id']);
							}
							return $url['url'];
						}else{
							return 0;
						}
					}catch(Exception $e){
						return -1;
					}
				}else{
					return -1;
				}
			}else{
				return -2;
			}
		}else{
			return -3;
		}
	}
	private function stats($properties,$id){
		if(is_array($properties)){
			$stats = array();
			$query = 'INSERT INTO stats (idurl,ip,useragent,referer,accept) VALUES('.$id.',"';
			
			if(!empty($properties['HTTP_X_FORWARDED_FOR'])){
				$query.= mysql_real_escape_string($properties['HTTP_X_FORWARDED_FOR']);
			}else{
				$query.= mysql_real_escape_string($properties['REMOTE_ADDR']);
			}
			$query.='","';
			
			if(!empty($properties['HTTP_USER_AGENT'])){
				$query.= mysql_real_escape_string($properties['HTTP_USER_AGENT']);
			}else{
			}
			$query.='","';
			if(!empty($properties['HTTP_REFERER'])){
				$query.= mysql_real_escape_string($properties['HTTP_REFERER']);
			}else{
			}			
			$query.='","';
			if(!empty($properties['HTTP_ACCEPT'])){
				$query.= mysql_real_escape_string($properties['HTTP_ACCEPT']);
			}
			if(!empty($properties['HTTP_ACCEPT_LANGUAGE'])){
				$query.= ' | '.mysql_real_escape_string($properties['HTTP_ACCEPT_LANGUAGE']);
			}
			if(!empty($properties['HTTP_ACCEPT_ENCODING'])){
				$query.= ' | '.mysql_real_escape_string($properties['HTTP_ACCEPT_ENCODING']);
			}
			if(!empty($properties['HTTP_ACCEPT_CHARSET'])){
				$query.= ' | '.mysql_real_escape_string($properties['HTTP_ACCEPT_CHARSET']);
			}
			
			// Ajoutez user-agent !
			$query.='")';
			mysql_query($query)or die(mysql_error());
		}
	}
	public function addUrl($keyapi='',$url='',$client='',$shortid=''){
		$keyapi = htmlspecialchars($keyapi);
		$url = htmlspecialchars(urldecode($url));
		$client = htmlspecialchars($client);
		
		include('config.php');
		
		if($_SESSION['logued']==0 && $config['params']['mode']==1 &&  $config['params']['mode']==true){
			return 4;
		}
		
		$query = 'SELECT keyapi.id AS keyid,keyapi.service,service.id,service.host,service.length,keyapi.allowcustomid FROM keyapi LEFT JOIN service ON service.id=keyapi.service WHERE keyapi.keytext="'.$keyapi.'" AND keyapi.state=0';
		
		if($keyapi!='web'){
			$query.=' AND keyapi.owner='.$_SESSION['logued'];
		}
		
		$keyapisdb = mysql_query($query)or die(mysql_error());
		if(mysql_num_rows($keyapisdb)>=1){
			
			if(!preg_match('#^https?:\/\/[A-Za-z0-9\.\-_]+\.[A-Za-z0-9]+.*$#isU',$url)){
				return 1;
			}
			if(!preg_match('#[A-Za-z0-9]*#isU',$client)){
				return 2;
			}
			/*if(!preg_match($config['shortid']['pattern'],$shortid)){ // '#[A-Za-z0-9\.\-]*#isU'
				return 3;
			}*/
			
			while($keyapitab = mysql_fetch_assoc($keyapisdb)){
				
				if(empty($keyapitab['length'])){
					$length = -1;
				}else{
					$length = $keyapitab['length'];
				}
				
				$addand=false;
				$querybase = 'SELECT idtourl.shortid FROM idtourl LEFT JOIN keyapi ON keyapi.id=idtourl.keyid WHERE';
				if($config['params']['multipleaddings']==false){
					$querybase.=' keyapi.owner='.$_SESSION['logued'];
					$addand=true;
				}
				$bypass=!$config['params']['allowcustomshortid'];
				do{
					if((!empty($shortid) && $bypass==1) || $keyapitab['allowcustomid']==false || empty($shortid) || !preg_match($config['shortid']['pattern'],$shortid)){
						$shortid = createShortId($length);
					}
					$bypass=1;
					if($addand==true){
						$queryadd = ' AND';
					}else{
						$queryadd = '';
					}
					$query = $querybase.$queryadd.' idtourl.shortid="'.$shortid.'"';
					$urlsdb2 = mysql_query($query)or die(mysql_error());
				}while(mysql_num_rows($urlsdb2)!=0);
				
				$urlsdb = mysql_query('SELECT idtourl.url,idtourl.shortid,keyapi.owner,service.host FROM idtourl LEFT JOIN keyapi ON keyapi.id=idtourl.keyid LEFT JOIN service ON service.id=keyapi.service WHERE idtourl.url="'.$url.'" AND keyapi.owner='.$_SESSION['logued'].' AND service.id='.$keyapitab['service'])or die(mysql_error());
				if(mysql_num_rows($urlsdb)==0 || ($_SESSION['logued']==0 && $config['params']['allowstats']==true)){
					$query2 = 'INSERT INTO idtourl (keyid,shortid,url,ip,client) VALUES('.$keyapitab['keyid'].',"'.$shortid.'","'.$url.'","'.$_SERVER['REMOTE_ADDR'].'","'.$client.'")';
					if($_SESSION['logued']==0){
						if(empty($_SESSION['publicid'])){
							mysql_query('INSERT INTO owner (sessionid) VALUES("'.time().' - '.$_SERVER['REMOTE_ADDR'].'")')or die(mysql_error());
							$_SESSION['publicid']=mysql_insert_id();
						}
						
						$query2 = 'INSERT INTO idtourl (keyid,shortid,url,ip,client,publicowner) VALUES('.$keyapitab['keyid'].',"'.$shortid.'","'.$url.'","'.$_SERVER['REMOTE_ADDR'].'","'.$client.'","'.$_SESSION['publicid'].'")';
					}
					mysql_query($query2)or die(mysql_error());
					echo 'http://'.$keyapitab['host'].'/'.$shortid.'
';
				}else{
					$urltab = mysql_fetch_assoc($urlsdb);
					echo 'http://'.$urltab['host'].'/'.$urltab['shortid'].'
';
				}
				
				if($config['params']['multipleaddings']==false){
					break;
				}
				
			}
		}else{
			return 0;
		}
		return -1;
	}
	public function addToken(){
		
	}
	
	public function listUrl(){
		include('config.php');
		if(!($config['params']['mode'] == 0 || ($config['params']['mode'] == 1 && $_SESSION['logued']!=0))){
			return 0;
		}
		
		$query = '
		SELECT 
			service.host,
			idtourl.shortid,
			idtourl.url,
			idtourl.ip,
			UNIX_TIMESTAMP(idtourl.date) AS date,
			idtourl.state,
			COUNT(DISTINCT stats.ip) AS countdistinct,
			COUNT(stats.ip) AS counttotal 
		FROM idtourl 
		LEFT JOIN keyapi 
			ON keyapi.id=idtourl.keyid
		LEFT JOIN service
			ON keyapi.service=service.id
		LEFT JOIN stats 
			ON stats.idurl=idtourl.id 
		LEFT JOIN owner
			ON owner.id=idtourl.publicowner
		WHERE keyapi.owner='.$_SESSION['logued'];
		
		if($_SESSION['publicid']!=0){
			$query.=' OR idtourl.publicowner='.$_SESSION['publicid'];
		}
		$query.= ' GROUP BY idtourl.id 
		ORDER BY idtourl.id ASC';
		$query2 = mysql_query($query)or die(mysql_error());
		$returnarray=array();
		while($result = mysql_fetch_assoc($query2)){
			$returnarray[] = $result;
		}
		return $returnarray;
	}
	public function listStats($id,$host){
		include('config.php');
		if(!($config['params']['mode'] == 0 || ($config['params']['mode'] == 1 && $_SESSION['logued']!=0))){
			return 0;
		}
		
		include('config.php');
		if(empty($id)){
			return 0;
		}
		if(!preg_match($config['shortid']['pattern'],$id)){
			return 1;
		}
		$query = '
		SELECT 
			service.host,
			idtourl.shortid,
			idtourl.url,
			UNIX_TIMESTAMP(idtourl.date) AS date,
			UNIX_TIMESTAMP(stats.date) AS dateclic,
			stats.ip,
			stats.useragent,
			stats.referer,
			stats.country
		FROM idtourl
		LEFT JOIN keyapi 
			ON keyapi.id=idtourl.keyid
		LEFT JOIN service
			ON keyapi.service=service.id
		LEFT JOIN stats
			ON stats.idurl=idtourl.id
		LEFT JOIN owner
			ON owner.id=idtourl.publicowner
		WHERE
			service.host="'.mysql_escape_string($host).'"
		AND shortid="'.mysql_escape_string($id).'"';
		
		if($config['params']['mode'] !=0){
			$query.='
		AND (keyapi.owner='.$_SESSION['logued'].' OR idtourl.publicowner='.$_SESSION['publicid'].')';
		}
		
		$query2 = mysql_query($query)or die(mysql_error());
		
		$returnarray=array();
		while($result = mysql_fetch_assoc($query2)){
			$returnarray[] = $result;
			if($config['params']['hideip'] == true){
				$ipexploded = explode('.',$returnarray[count($returnarray)-1]['ip']);
				if(count($ipexploded)==1){ // IPv6
					$ipexploded = explode(':',$returnarray[count($returnarray)-1]['ip']);
				}
				$returnarray[count($returnarray)-1]['ip'] = $ipexploded[0].'.xx';
			}
		}
		return $returnarray;
	}
	public function addUser($username,$password,$email){
		if(!preg_match('#[A-Za-z0-9\.\-_\+@]+@[A-Za-z0-9\.\-\_]+#',$email)){
			return 3;
		}
		$result = mysql_query('SELECT username FROM owner WHERE username="'.mysql_real_escape_string($username).'" OR email="'.addslashes($email).'"')or die(mysql_error());
		if(mysql_num_rows($result)!=0){
			return 4;
		}
		
		mysql_query('INSERT INTO owner (username,password,email,registerip,loginip,lastlogin) VALUES("'.mysql_real_escape_string(htmlentities($username)).'","'.sha1(md5($password)).'","'.mysql_real_escape_string($email).'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_ADDR'].'",NOW())')or die(mysql_error());
		$userid = mysql_insert_id();
		$_SESSION['logued']=$userid;
		$this->verifyLogin($username,$password);
		include('config.php');
		if($config['params']['allowautowebkey']){
			mysql_query('INSERT INTO keyapi (owner,service,keytext,allowcustomid,date) VALUES('.$userid.','.$config['params']['defaultservice'].',"web'.$userid.'",'.$config['params']['defaultcustomid'].',NOW()) ')or die(mysql_error());
		}
		/*if($_SESSION['publicid']!=0){
			mysql_query('INSERT INTO keyapi (owner,service,keytext,state,date) VALUES('.$userid.','.$config['params']['defaultservice'].',"web'.$userid.'",1,NOW()) ');
			mysql_query('UPDATE idtourl SET publicid=0, WHERE publicid="'.$_SESSION['publicid'].'"');
			$_SESSION['publicid']=0;
		}*/
	}
	public function verifyLogin($username,$password){
		$result = mysql_query('SELECT id FROM owner WHERE username="'.mysql_real_escape_string(htmlentities($username)).'" AND password="'.sha1(md5($password)).'"')or die(mysql_error());
		if(mysql_num_rows($result)!=0){
			$resultquery = mysql_fetch_assoc($result);
			$ownerid = $resultquery['id'];
			$_SESSION['logued']=$ownerid;
			if($_SESSION['publicid']!=0){
				$result2 = mysql_query('SELECT id FROM keyapi WHERE owner='.$ownerid)or die(mysql_error());
				$resultquery2 = mysql_fetch_assoc($result2);
				mysql_query('UPDATE idtourl SET publicowner=0, keyid='.$resultquery2['id'].' WHERE publicowner='.$_SESSION['publicid'])or die(mysql_error());
				$_SESSION['publicid']=0;
			}
			return -1;
		}else{
			return 6;
		}
	}
}
?>