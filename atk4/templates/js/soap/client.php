<?php
require_once('lib/nusoap.php');

$c = new nusoap_client('http://sagec.mrw.es/MRWEnvio.asmx?WSDL',
                               array ('encoding' => 'UTF-8'));

$result = $c->call('TransmEnvio',
              array('VT' => 12300, 'ALI'=>0.3, 'LRI'=>1, 'SI'=>8));

echo 'Resultado:';

print_r($result);