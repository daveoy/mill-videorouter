<?php

define('MILL_AUTH_AD','mill-dc.mill.co.uk');

/*
** Extend the PEAR Auth class with extra functionality
*/
class Auth
{
   /*
   ** Return the last successful authentication method
   */
   function start()
   {
      if (!$this->checkAuth())
      {
         $this->doAuthentication();
      }
   }

   function checkAuth()
   {
      if (isset($_COOKIE['mill_intra']))
      {
         return true;
      }
      else
      {
         return false;
      }
   }

   function getUsername()
   {
      if (isset($_COOKIE['mill_intra']))
      {
         return $_COOKIE['mill_intra'];
      }
      else
      {
         return false;
      }
   }

   function logout()
   {
      if (isset($_COOKIE['mill_intra']))
      {
	 $expire = 1;
	 $path = "/";
	 $domain = ".mill.co.uk";
         setcookie('mill_intra',"",$expire,$path,$domain);
      }
   }

   function doAuthentication()
   {
      if (isset($_POST['username']) && isset($_POST['password']))
      {
         $username = $_POST['username'];
         $password = $_POST['password'];

	 if ($this->fetchData($username,$password))
	 {
	    $value = $username;
	    $expire = 60*60*24;
	    $path = "/";
	    $domain = "the-mill.com";

	    print "XXXXXXX".setcookie('mill_intra',$value,$expire,$path,$domain);

	    return true;
	 }
	 else
	 {
	    return false;
         }
      }
      else
      {
         return false;
      }
   }

   function fetchData($username,$password)
   {
      if ($username == null || $password == null)
      {
         return false;
      }

      $base_dn = "DC=mill,DC=co,DC=uk";

      $ad = @ldap_connect("ldap://".MILL_AUTH_AD);

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
}

?>
