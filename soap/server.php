<?php
require('lib/nusoap.php');

$server = new soap_server();

$server->configureWSDL('testserver', 'urn:testservice');

$server->wsdl->addComplexType(
    'ValoresIniciales',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'VT' => array('name' => 'VT', 'type' => 'xsd:int'),
        'ALI' => array('name' => 'ALI', 'type' => 'xsd:double'),
        'LRI' => array('name' => 'LRI', 'type' => 'xsd:double'),
        'SI' => array('name' => 'SI', 'type' => 'xsd:double'),
        
    ));

$server->wsdl->addComplexType(
    'Tratamiento',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'estado' => array('name' => 'estado', 'type' => 'xsd:string'),
        'volumen_retirar' => array('name' => 'volumen_retirar', 'type' => 'xsd:int'),
        'aviso_volumen_retirar' => array('name' => 'aviso_volumen_retirar', 'type' => 'xsd:string'),
        'aviso_sal_inicial' => array('name' => 'aviso_sal_inicial', 'type' => 'xsd:string'),
        'aviso_acido' => array('name' => 'aviso_acido', 'type' => 'xsd:string'),
        'acido_total' => array('name' => 'acido_total', 'type' => 'xsd:int'),
        'acido_clorhidrico' => array('name' => 'acido_clorhidrico', 'type' => 'xsd:int'),
        'acido_citrico' => array('name' => 'acido_citrico', 'type' => 'xsd:int'),
        'acido_lactico' => array('name' => 'acido_lactico', 'type' => 'xsd:int'),
        'aviso_salmuera' => array('name' => 'aviso_salmuera', 'type' => 'xsd:string'),
        'aviso_salmuera_blanca' => array('name' => 'aviso_salmuera_blanca', 'type' =>'xsd:string'),
        'sal' => array('name' => 'sal', 'type' => 'xsd:int'),
        'incompleto' => array('name' => 'incompleto', 'type' => 'xsd:string')
    ));
    
$server->wsdl->addComplexType(
    'ValoresFinales',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'ALF' => array('name' => 'ALF', 'type' => 'xsd:double'),
        'LRF' => array('name' => 'LRF', 'type' => 'xsd:double'),
        'SF' => array('name' => 'SF', 'type' => 'xsd:double'),
        'ALFMI' => array('name' => 'ALFMI', 'type' => 'xsd:double'),
        'ALFMA' => array('name' => 'ALFMA', 'type' => 'xsd:double'),
        'LRFMI' => array('name' => 'LRFMI', 'type' => 'xsd:double'),
        'LRFMA' => array('name' => 'LRFMA', 'type' => 'xsd:double'),
        'SFMI' => array('name' => 'SFMI', 'type' => 'xsd:double'),
        'SFMA' => array('name' => 'SFMA', 'type' => 'xsd:double')
    ));


$server->register("getTestService",
                array('nombre' => 'xsd:string'),
                array('return' => 'xsd:string'),
                'urn:testservice',
                'urn:testservice#getTestService');
				
$server->register("getTratamiento",
                array('VT' => 'tns:ValoresIniciales'),
                array('return' => 'tns:Tratamiento'),
                'urn:testservice',
                'urn:testservice#getTratamiento');
                
$server->register("getValoresFinales",
				array(),
                array('return' => 'tns:ValoresFinales'),
                'urn:testservice',
                'urn:testservice#getTratamiento');
                
                
/*
function doAuthenticate()
{
    $sSoapRequest = file_get_contents('php://input');
    if(isset($sSoapRequest))
    {
        $sUsername = hookTextBetweenTags($sSoapRequest, 'Username');
        $sPassword = hookTextBetweenTags($sSoapRequest, 'Password');
        $sSignature = hookTextBetweenTags($sSoapRequest, 'Signature');
        if($sUsername=='testuser' && $sPassword=='test123456' && $sSignature=='5595610031002')
            return true;
        else
            return false;
    }
}
function hookTextBetweenTags($string, $tagname) {
    $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
    preg_match($pattern, $string, $matches);
    return $matches[1];
}


*/                
                
                
                
                

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)
                      ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

function getTestService($nombre) {
 	return 'Hola '. $nombre;	
}

function getValoresFinales() {
	return array(
	'ALF'=>0.65,
	'LRF'=>0.06,
	'SF'=>8.3,
	'ALFMI'=>0.58,
	'ALFMA'=>0.7,
	'LRFMI'=>0.04,
	'LRFMA'=>0.07,
	'SFMI'=>8,
	'SFMA'=>8.8);
}

function getTratamiento($VT, $ALI, $LRI, $SI) {
	
	$valores=getValoresFinales();
	
	$ALF=$valores['ALF'];
	$LRF=$valores['LRF'];
	$SF=$valores['SF'];	
	$ALFMI=$valores['ALFMI'];
	$ALFMA=$valores['ALFMA'];
	$LRFMI=$valores['LRFMI'];
	$LRFMA=$valores['LRFMA'];
	$SFMI=$valores['SFMI'];
	$SFMA=$valores['SFMA'];
	
	//Estado
	if ($ALI<$ALFMI || $ALI>$ALFMA ||
    	$LRI<$LRFMI || $LRI>$LRFMA ||
    	$SI<$SFMI || $SI>$SFMA) $resultado ['estado']='TRATAR';
	else {
		$resultado['estado']='DISPONIBLE';
		return $resultado;
	}
	
	$result['aviso_salmuera_blanca']='Añadir salmura blanca a 8,5 ºBé';
	
	//Volumen a retirar
	$volumen_retirar=round($VT*(9*($LRI-$LRF)+($ALI-$ALF))/(9*$LRI+$ALI));
	
	if ($volumen_retirar>0) {
		$resultado['volumen_retirar']=$volumen_retirar;
		if ($volumen_retirar>3600) $resultado['aviso_volumen_retirar']='IGNORAR. Excesivo volumen a retirar.';
	}
	else {
		$resultado['volumen_retirar']=0;
		$volumen_retirar=0;
		$resultado['aviso_volumen_retirar']='No es necesario retirar volumen.';
		$resultado['aviso_salmuera_blanca']='';
	}
	
	//Advertencia sal inicial
	//if ($SI>$SFMA) $resultado['aviso_sal_inicial']='Retirar salmuera y diluir con agua. Atención a los otros parámetros';
	
	//Acidos
	$acido=round($VT*(($LRI*$ALF)-($LRF*$ALI))/(10*(9*$LRI+$ALI)));
	$resultado['acido_total']=$acido;
	if ($acido<0) 
	{
		$resultado['aviso_acido']='Retirar más salmuera y no acidificar.';
		$resultado['acido_total']='';
	}
	else {
		//Clorhidrico
		$resultado['acido_clorhidrico']=(($acido<25)? $acido : 25);
		//Citrico
		if ($acido>25 && $acido<=35) $resultado['acido_citrico']=$acido-25;
		else if ($acido>35) $resultado['acido_citrico']=10;
		else $resultado['acido_citrico']=0;
		//Lactico
		$resultado['acido_lactico']=(($acido>35)? round(($acido-35)/0.8) : 0);
	}
	
	//Advertencia cambio salmuera
	if ($LRI<0.036) 
	{
		$resultado['aviso_salmuera']='Cambiar 4.000 litros de salmuera por salmuera de color con mas Lejia Residual y dejar equilibrar. ANALIZAR';
		$resultado['aviso_acido']='';
		$resultado['aviso_volumen_retirar']='';
		$resultado['aviso_salmuera_blanca']='';
	}
	//Sal
	if ($volumen_retirar>0) {
		$prov=(($SF*$VT)-(($VT-$volumen_retirar)*$SI))/$volumen_retirar;
		if ($prov>8.6) $resultado['sal']=round(((($volumen_retirar*$prov)-($volumen_retirar*8.5))/3600)/0.2);
	}
	
	//Tratamiento incompleto
	if ($volumen_retirar>3600) $resultado['incompleto']='SE  RECOMIENDA  APLICAR  UN TRATAMIENTO  INCOMPLETO  (En preparación).';	
		
	return $resultado;
}
