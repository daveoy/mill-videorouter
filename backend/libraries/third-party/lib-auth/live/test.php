<?php

include 'MillAuth.php';

$m = new MillAuthContainer();

print $m->getAuthServer()."\n";

$res = $m->fetchData("jon","xxxxxx");

if ($res)
{
   print "OK\n";
}
else
{
   print "FAILED\n";
}

?>
