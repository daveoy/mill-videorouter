<?php
require_once "Auth.php";
require_once "Auth/Container.php";

define('MILL_AUTH_COOKIE','mill-auth');
define('MILL_AUTH_KEY','asudbvkdgqea');
define('MILL_AUTH_TIMEOUT',60*60*8);

/*
** Extend the PEAR Auth class with extra functionality
*/
class MillAuth Extends Auth
{
   var $domainlist = array("themill.com","the-mill.com");

   function start()
   {
      $this->log('Auth::start() called.', AUTH_LOG_DEBUG);

      $this->setLoginCallback(array($this,"setAuthCookie"));

      if ($this->regenerateSessionId)
      {
         session_regenerate_id(true);
      }

      $this->assignData();
      if (!$this->checkAuth() && $this->allowLogin)
      {
         $result = $this->login();
      }
   }

   function checkAuth()
   {
      /*
      ** If Cookie isn't set, return false
      */
      if (!isset($_COOKIE[MILL_AUTH_COOKIE]))
      {
         return false;
      }
      else
      {
         /*
	 ** Found Cookie, check data
	 */
         $data = json_decode($this->decryptData($_COOKIE[MILL_AUTH_COOKIE]),true);

         /*
	 ** Couldn't find required variables, return false
	 */
	 if (!isset($data["username"]) || !isset($data["expires"]) || !isset($data["ip"]))
	 {
	    return false;
         }

#print "XXXX\n";
         /*
	 ** Registered IP doesn't match client IP, return false
	 */
	 if ($data["ip"] != $_SERVER['REMOTE_ADDR'])
	 {
	    return false;
         }

#print "HERE\n";
         /*
	 ** Check Cookie hasn't expired
	 */
	 $time_diff = $data["expires"] - time();
	 if ($time_diff <= MILL_AUTH_TIMEOUT)
	 {
#print "DDDD $time_diff\n";
	    $this->setAuthCookie($data["username"]);
	    return true;
         }
	 else
	 {
#print "YYYYY\n";
	    $this->destroyAuthCookie();
            return false;
         }
      }
   }

   /*
   ** Print Cookie Contents
   */
   function showAuthInfo()
   {
      if (isset($_COOKIE[MILL_AUTH_COOKIE]))
      {
         $data = json_decode($this->decryptData($_COOKIE[MILL_AUTH_COOKIE]),true);
	 print_r($data);
      }
   }

   /*
   ** Return Username Stored In Cookie
   */
   function getUsername()
   {
      if (isset($_COOKIE[MILL_AUTH_COOKIE]))
      {
         $data = json_decode($this->decryptData($_COOKIE[MILL_AUTH_COOKIE]),true);

	 return $data["username"];
      }
      else
      {
         return "";
      }
   }

   /*
   ** Logout
   */
   function logout()
   {
      if (isset($_COOKIE[MILL_AUTH_COOKIE]))
      {
         $this->destroyAuthCookie();
	 unset($_COOKIE[MILL_AUTH_COOKIE]);
      }
   }

   /*
   ** Destroy the cookie
   */
   function destroyAuthCookie()
   {
      if ($domain = $this->getDomain())
      {
         $expire = time() - (60*60*24*365*8);
         $path = "/";

         $result = setcookie(MILL_AUTH_COOKIE,"",$expire,$path,$domain);
     
         return $result;
      }
      else
      {
         return false;
      }
   }

   /*
   ** Create/Update Cookie
   */
   function setAuthCookie($username,$ref = null)
   {
      $value = $username;
      $expire = time() + MILL_AUTH_TIMEOUT;
      $path = "/";

      if ($domain = $this->getDomain())
      {
         $data = json_encode(array("username"=>$username,"expires"=>$expire,"ip"=>$_SERVER['REMOTE_ADDR']));

         $result = setcookie(MILL_AUTH_COOKIE,$this->encryptData($data),$expire,$path,$domain);

         if ($result == true)
         {
            $_COOKIE[MILL_AUTH_COOKIE] = $this->encryptData($data);
         }

         return $result;
      }
      else
      {
         return false;
      }
   }

   /*
   ** Encrypt Data
   */
   function encryptData($value)
   {
      if(!$value)
      {
         return false;
      }

      $text = $value;
      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
      $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
      $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,MILL_AUTH_KEY,$text,MCRYPT_MODE_ECB,$iv);
      return trim(base64_encode($crypttext)); //encode for cookie
   }

   /*
   ** Decrypt Data
   */
   function decryptData($value)
   {
      if(!$value)
      {
        return false;
      }

      $crypttext = base64_decode($value); //decode cookie
      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,MCRYPT_MODE_ECB);
      $iv = mcrypt_create_iv($iv_size,MCRYPT_RAND);
      $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,MILL_AUTH_KEY,$crypttext,MCRYPT_MODE_ECB,$iv);
      return trim($decrypttext);
   }

   /*
   ** Check for valid domain in hostname
   */
   function getDomain()
   {
      if (isset($_SERVER['HTTP_HOST']))
      {
         foreach ($this->domainlist as $d)
         {
            if (eregi($d,$_SERVER['HTTP_HOST']))
            {
               return $d;
            }
         } 
      }
      
      return false;
   }
}

class MillAuthContainer extends Auth_Container
{
   /*
   ** Do this all locally
   */
   public $millAuthLondon = 1;
   public $millAuthNewYork = 2;
   public $millAuthLosAngeles = 3;
   public $millAuthClipstone = 4;
   public $millAuthSingapore = 5;
   public $millAuthDocklands = 6;
   public $millAuthUnknown = 7;
   public $millAuthChicago = 8;

   public $authServer = 'mill-scm.mill.co.uk';

   /*
   ** Work out which AD server to use
   */
   function __construct()
   {
      $authServerList = array
      (
         $this->millAuthLondon     => 'mill-scm.mill.co.uk',
         $this->millAuthChicago    => 'dc-01-ny.mill-ny.com',
         $this->millAuthNewYork    => 'dc-01-ny.mill-ny.com',
         $this->millAuthLosAngeles => 'la-server.mill-la.com',
         $this->millAuthClipstone  => 'mill-scm.mill.co.uk',
         $this->millAuthSingapore  => 'mill-scm.mill.co.uk',
         $this->millAuthDocklands  => 'mill-scm.mill.co.uk',
         $this->millAuthUnknown    => 'mill-scm.mill.co.uk'
      );

      $loc = $this->locateMyServer();

      if ($loc == $this->millAuthLondon || $loc == $this->millAuthNewYork || $loc == $this->millAuthLosAngeles)
      {
         $this->authServer = $authServerList[$loc];
      }
      else
      {
         $this->authServer = $authServerList[$this->millAuthLondon];
      }
   }

   function getAuthServer()
   {
      return $this->authServer;
   }

   /*
   ** Check Username & Password with AD
   */
   function fetchData($username,$password)
   {
      if (!isset($_SERVER['REMOTE_ADDR']))
      {
         return false;
      }

      if ($username == null || $password == null)
      {
         return false;
      }

      $base_dn = "DC=mill,DC=co,DC=uk";

      $ad = @ldap_connect("ldap://".$this->authServer);

      @ldap_set_option($ad,LDAP_OPT_PROTOCOL_VERSION,3);
      @ldap_set_option($ad,LDAP_OPT_REFERRALS,0);

      $bind = @ldap_bind($ad,$username."@mill.co.uk",$password);

      if($bind != false)
      {
         @ldap_unbind($ad);
         $this->auth_status = "Active Directory";
         return true;
      }

      return false;
   }

   function fetchAuthType()
   {
      return $this->auth_status;
   }

   function locateMyServer()
   {
      if (isset($_SERVER['SERVER_ADDR']))
      {
         $ip = ip2long($_SERVER['SERVER_ADDR']);
      }
      else if (getenv('HOST'))
      {
         $h = getenv('HOST');
         $ip = ip2long(trim(`/usr/bin/dig $h A +short | /usr/bin/tail -1`));
      }
      else
      {
         $h = trim(exec("/bin/hostname"));
         $ip = ip2long(trim(`/usr/bin/dig $h A +short | /usr/bin/tail -1`));
      }
   
      $mask = ip2long('255.240.0.0');
      $london = ip2long('10.16.0.0');
      $new_york = ip2long('10.64.0.0');
      $chicago = ip2long('10.80.0.0');
      $los_angeles = ip2long('10.96.0.0');
   
      $location = $ip & $mask;
   
      if ($location == $london)
      {
         return $this->millAuthLondon;
      }
      else if ($location == $chicago)
      {
         return $this->millAuthChicago;
      }
      else if ($location == $new_york)
      {
         return $this->millAuthNewYork;
      }
      else if ($location == $los_angeles)
      {
         return $this->millAuthLosAngeles;
      }
      else
      {
         $mask = ip2long('255.255.0.0');
         $clipstone = ip2long('172.16.0.0');
   
         $location = $ip & $mask;
   
         if ($location == $clipstone)
         {
            return $this->millAuthClipstone;
         }
         else
         {
            $mask = ip2long('255.255.255.0');
	    $docklands = ip2long('192.168.15.0');
   
	    $location = $ip & $mask;
   
	    if ($location == $docklands)
	    {
	       return $this->millAuthDocklands;
            }
	    else
	    {
               return UNKNOWN;
            }
         }
      }
   }
}
   
?>
