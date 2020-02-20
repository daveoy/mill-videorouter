<?php

require_once THIRD_PARTY . 'lib-ad/AD_LDAP.php';

class Mill_AD
{
	public function construct()
	{}

	public function getSecurityGroups($location)
	{
		$ou = "Security Groups - Global";

		$ad = new AD_LDAP();
		$res = $ad->adSearchDirect('(cn=*)',"OU=$ou,DC=mill,DC=co,DC=uk");
		$cns = array();
		foreach($res as $cn)
		{
			if($cn['cn'][0])
				array_push($cns, $cn['cn'][0]);
		}
		return $cns;
	}

	public function getUserSecurityGroups($username)
	{
		$ad = new AD_LDAP();
		$filter = "(&(objectCategory=user)(samaccountname=".$this->modFilter($username)."))";
		$res = $ad->adSearchDirect($filter);
		$sgs = array();
		foreach($res[0]['memberof'] as $member)
		{
		if(preg_match("/CN=(.*),OU=/", $member, $matches))
			array_push($sgs,$matches[1]);		
		}
		return $sgs;
	}

  public function getUserOU($username)
  {
    $ad = new AD_LDAP();
    $login = $ad->adSearchUserID($username);
    $userdetails = explode(',',$login[0]['dn']);
    $ou = substr($userdetails[1],3);
    return $ou;
  }

  public function adSearchUserID($username)
  {
      $ad = new AD_LDAP();
      $filter = "(&(objectCategory=user)(samaccountname=" . $this->modFilter($username)."))";
      $res = $ad->adSearchDirect($filter);
      return $res[0]['cn'][0];
  }


  public function modFilter($filter)
  {
    return preg_replace(array("/\(/","/\)/"),array("\\(","\\)"),$filter);
  }   
}