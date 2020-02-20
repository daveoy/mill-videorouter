<?php

class CetaCompany extends Ceta {

  function fetchCompanyById($companyid) {

    $cdata = array('Object' => 'company',
                   'SearchField' => 'companyID',
                   'Criteria' => $companyid,
                   'Options' => '');

    $ares = json_decode($this->cetaRead($cdata), TRUE);

    if (isset($ares['warning'])) {
      return $ares;
    } else {
      return $ares[0];
    }
  }   

}

?>
