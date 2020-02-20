<?php

require 'cetaXajax.php';
$cetaxajax = new CetaXajax();

/*

require_once 'Ceta.class.php';

$cetaxajax = new CetaXajax();

echo '<h1>Mill Ceta PHP API</h1>';
echo '<h2>CetaProject</h2>';
echo '<h4>fetchProjectIdByJnum</h4>';

// CETAPROJECT //
$cetaproject = new CetaProject();
$cetaproject->debug = true;
$res = $cetaproject->fetchProjectIdByJnum('75910');
echo '<pre>'.print_r($res, TRUE).'</pre>';

// CETAMEDIA //
echo '<h2>CetaMedia</h2>';
echo '<h4>fetchTapeDetails</h4>';

$cetamedia = new CetaMedia();
$cetamedia->debug = true;
$res = $cetamedia->fetchTapeDetails('SRHDL0325603LD');
echo '<pre>'.print_r($res, TRUE).'</pre>';

*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<TITLE>Mill Ceta PHP API</TITLE>
<style>
h2 {
    background-color: #CCCCCC;
    margin-top: 2px;
    margin-bottom: 2px;
    padding-left: 5px;
}
h4 {
    margin-top: 2px;
    margin-bottom: 2px;
}
</style>
<script type="text/javascript" src="/mill/xajax/xajax_js/xajax_core.js"></script>
<script src="/mill/lib/javascript/prototype.js" type="text/javascript"></script>
<script src="/mill/lib/javascript/scriptaculous.js" type="text/javascript"></script>
<script>

function fetchProjectIdByJnum() {
    var value = $('input_fetchProjectIdByJnum').value;
    res = xajax.call('xajaxfetchProjectIdByJnum', {parameters:[value],mode:'synchronous'});
    $('fetchProjectIdByJnum').innerHTML = "<pre>" + res + "</pre>";
}

function fetchTapeDetails() {
    var value = $('input_fetchTapeDetails').value;
    res = xajax.call('xajaxfetchTapeDetails', {parameters:[value],mode:'synchronous'});
    $('fetchTapeDetails').innerHTML = "<pre>" + res + "</pre>";
}

function fetchQuotesFromProjectID() {
    var value = $('input_fetchQuotesFromProjectID').value;
    res = xajax.call('xajaxfetchQuotesFromProjectID', {parameters:[value],mode:'synchronous'});
    $('fetchQuotesFromProjectID').innerHTML = "<pre>" + res + "</pre>";
}

function fetchDetailsByUsername() {
    var value = $('input_fetchDetailsByUsername').value;
    res = xajax.call('xajaxfetchDetailsByUsername', {parameters:[value],mode:'synchronous'});
    $('fetchDetailsByUsername').innerHTML = "<pre>" + res + "</pre>";
}

function clearResults(myel) {
    while ($(myel).firstChild) {
        $(myel).removeChild($(myel).firstChild);
    }
}

</script>
</HEAD>
<BODY>
<h1>Mill Ceta PHP API</h1>
<h2>Options</h2>
setLimit - Number of returned records
<pre>
$cetamedia->setLimit(5);
</pre>

setOrderBy - Field to sort by
<pre>
$cetamedia->setOrderBy('cdate');
</pre>

setDirection - Sort direction
<pre>
$cetamedia->setDirection('asc');
</pre>

setOffset - Record offset
<pre>
$cetamedia->setOffset('5');
</pre>

<h2>CetaProject</h2>
<h4>fetchProjectIdByJnum</h4>
<p>
<pre>
$cetaproject = new CetaProject();
$res = $cetaproject->fetchProjectIdByJnum('75910');
</pre>
<input id="input_fetchProjectIdByJnum" value="75910"><input type="button" onClick="fetchProjectIdByJnum()" value="try">
<input type="button" onClick="clearResults('fetchProjectIdByJnum')" value="clear">
<div id="fetchProjectIdByJnum"></div>

<h2>CetaMedia</h2>
<h4>fetchTapeDetails</h4>
<pre>
$cetamedia = new CetaMedia();
$res = $cetamedia->fetchTapeDetails('SRHDL0325603LD');
</pre>
<input id="input_fetchTapeDetails" value="SRHDL0325603LD"><input type="button" onClick="fetchTapeDetails()" value="try">
<input type="button" onClick="clearResults('fetchTapeDetails')" value="clear">
<div id="fetchTapeDetails"></div>

<h2>CetaQuote</h2>
<h4>fetchQuotesFromProjectID</h4>
<pre>
$cetamedia = new CetaMedia();
$res = $cetamedia->fetchQuotesFromProjectID('97960');
</pre>
<input id="input_fetchQuotesFromProjectID" value="97960"><input type="button" onClick="fetchQuotesFromProjectID()" value="try">
<input type="button" onClick="clearResults('fetchQuotesFromProjectID')" value="clear">
<div id="fetchQuotesFromProjectID"></div>

<h2>CetaUser</h2>
<h4>fetchDetailsByUsername</h4>
<pre>
$cetauser = new CetaUser();
$res = $cetamedia->fetchDetailsByUsername('neilb');
</pre>
<input id="input_fetchDetailsByUsername" value="neilb"><input type="button" onClick="fetchDetailsByUsername()" value="try">
<input type="button" onClick="clearResults('fetchDetailsByUsername')" value="clear">
<div id="fetchDetailsByUsername"></div>
</BODY>
