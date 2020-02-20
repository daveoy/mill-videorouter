<?php

class CetaContact extends Ceta {

  function fetchContactById($contactId) {

    $cdata = array('Object' => 'contact',
                 'SearchField' => 'contactID',
                 'Criteria' => $contactId,
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
