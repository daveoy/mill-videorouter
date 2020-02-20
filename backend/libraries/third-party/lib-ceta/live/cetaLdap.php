<?php

class cetaLdap {

    function __construct() {

        $this->ldap_server = 'ldap://mill-scm.mill.co.uk';
        $this->ldap_user = 'ad-web@mill.co.uk';
        $this->ldap_password = 'trumpet';

    }


    function doQuery($base_dn, $filter) {
        $ad = ldap_connect($this->ldap_server);
	ldap_set_option($ad,LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($ad,LDAP_OPT_REFERRALS,0);
	ldap_set_option($ad,LDAP_OPT_SIZELIMIT,15000);
        $bind = ldap_bind($ad,$this->ldap_user,$this->ldap_password);
        $result = ldap_search($ad,$base_dn,$filter);
	return ldap_get_entries($ad,$result);
    }

    function fetchMembersofDistributionGroup($group, $location) {

        $users = array();

	$base_dn = "CN=$group,OU=Distribution Lists - $location,DC=mill,DC=co,DC=uk";
	$filter = "objectCategory=group";

	$entries = $this->doQuery($base_dn, $filter);

	foreach ($entries[0]['member'] as $k => $v) {
	    if (!isset($v['memberof'])) {
	        continue;
	    }
	    if (preg_match("/^CN=(.*?),OU/", $v, $matches)) {
		$fullname = $matches[1];
		$filter = "objectCategory=user";
		$userdetails = $this->doQuery($v, $filter);
		array_push($users, $userdetails[0]["samaccountname"][0]);
	    }

	}

	return $users;

    }

    function fetchMembersofGroup($group, $location, $activeonly=0) {

        $users = array();

        $base_dn = "OU=$group,OU=$location,DC=mill,DC=co,DC=uk";
        $filter = "objectCategory=user";

	$entries = $this->doQuery($base_dn, $filter);

        foreach ($entries as $k => $v) {
            if (!isset($v['memberof'])) {
                continue;
            }

	    if ($activeonly == 1) {
	        if ($this->accountStatus($v) < 1) {
		    continue;
		}
	    }

            if (!in_array($v['samaccountname'][0], $users)) {
                array_push($users, $v['samaccountname'][0]);
            }   
        }

	return $users;

    }

    function convertToUnix($win_time) {   
        if ($win_time == 9223372036854775807 || $win_time == 0) {   
            return 0;
        } else {
            return (int)(($win_time - 116444736000000000)/10000000);
        }
    }

    function accountStatus($user) {		// 0 - Disabled || 1 - Active, Permanent || 2 - Active, Temporary 

        if (!in_array($user['useraccountcontrol'][0], array(512, 66048))) {
            return 0;
        }

        $expdate = $this->convertToUnix($user['accountexpires'][0]);
        if ($expdate == 0) {
            return 1;
        }

        if ($expdate > time()) {
            return 2;
        }

        return 0;
}


}
?>
