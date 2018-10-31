<?php
/**
 * 
 * +------------------------------------------------
 * remote curl request
 * +------------------------------------------------
 * @author gaosongwang <songwanggao@gmail.com>
 * +-------------------------------------------------
 * @version 2015/8/4
 * +-------------------------------------------------
 */

namespace Addcn\Model\Request;

class Curl {
	
	 protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1'; 
     protected $_url; 
     protected $_followlocation; 
     protected $_timeout; 
     protected $_maxRedirects; 
     protected $_cookieFileLocation = './cookie.txt'; 
     protected $_post; 
     protected $_postFields; 
     protected $_referer ="http://www.midnightvip.com"; 

     protected $_session; 
     protected $_webpage; 
     protected $_includeHeader; 
     protected $_noBody; 
     protected $_status; 
     protected $_binaryTransfer; 
     public    $authentication = 0; 
     public    $auth_name      = ''; 
     public    $auth_pass      = ''; 
     
     private $error ;
     private $error_no ;

     public function useAuth($use){ 
     	
     	if($use == true) $this->authentication = 1; 
     	
     } 

     public function setName($name){ 
       $this->auth_name = $name; 
     } 
     
     public function setPass($pass){ 
       $this->auth_pass = $pass; 
     } 

     public function __construct($url,$timeOut = 30) { 
     	
         $this->_url = $url; 
         $this->_timeout = $timeOut; 
         $this->setHeaders();
         $this->setRedirect();
         $this->setNobody();
         $this->setBinary();
         $this->_cookieFileLocation = dirname(__FILE__).'/cookie.txt'; 

     } 
     public function setRedirect($followLocation = true, $redirect = 4){
     	$this->_followlocation = $followLocation;
     	$this->_maxRedirects	= $redirect;
     }
     public function setHeaders($header = false){
     	$this->_includeHeader = $header;
     }
     public function setBinary($binary = false){
     	$this->_binaryTransfer = $binary;
     }
     public function setNobody($body = false){
     	$this->_noBody = $body;
     }
     public function setReferer($referer){ 
       $this->_referer = $referer; 
     } 
     public function setCookiFileLocation($path) { 
         $this->_cookieFileLocation = $path; 
     } 

     public function setPost ($postFields) { 
        $this->_post = true; 
        $this->_postFields = $postFields; 
     } 

     public function setUserAgent($userAgent) { 
         $this->_useragent = $userAgent; 
     } 

     public function send($url = 'nul') { 
     	
     	if($url != 'nul'){ 
          $this->_url = $url; 
        } 

        $s = curl_init(); 

        curl_setopt($s,CURLOPT_URL, $this->_url); 
        curl_setopt($s, CURLOPT_NOBODY, 0);
        curl_setopt($s, CURLOPT_HEADER, 0);
        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false); //https設定
        curl_setopt($s, CURLOPT_SSL_VERIFYHOST, false); //https設定
        curl_setopt($s,CURLOPT_HTTPHEADER,array('Expect:')); 
        curl_setopt($s,CURLOPT_TIMEOUT,$this->_timeout); 
        curl_setopt($s,CURLOPT_MAXREDIRS,$this->_maxRedirects); 
        curl_setopt($s,CURLOPT_RETURNTRANSFER,true); 
        curl_setopt($s,CURLOPT_FOLLOWLOCATION,$this->_followlocation); 
        curl_setopt($s,CURLOPT_COOKIEJAR,$this->_cookieFileLocation); 
        curl_setopt($s,CURLOPT_COOKIEFILE,$this->_cookieFileLocation); 
		

        
        if($this->authentication == 1){ 
        	curl_setopt($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass); 
        } 
        if($this->_post){ 
            curl_setopt($s,CURLOPT_POST,true); 
            curl_setopt($s,CURLOPT_POSTFIELDS,$this->_postFields); 
			
        } 

        if($this->_includeHeader) { 
        	curl_setopt($s,CURLOPT_HEADER,true); 
        } 

        if($this->_noBody) { 
        	curl_setopt($s,CURLOPT_NOBODY,true); 
        } 
        
         
        curl_setopt($s,CURLOPT_USERAGENT,$this->_useragent); 
        
        curl_setopt($s,CURLOPT_REFERER,$this->_referer); 

        $data = curl_exec($s); 
        
        if($data === false){
        	$this->error    = curl_error($s);
        	$this->error_no = curl_errno($s);
        }
        
        $this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE); 
        
        curl_close($s); 

		return $data;
     } 

    public function getHttpStatus() { 
        return $this->_status; 
    } 
	public function getError(){
		return $this->error;
	}
	
	public function getErrno(){
		return $this->error_no;
	}
	 
//   public function __tostring(){ 
//      return $this->_webpage; 
//   } 

}