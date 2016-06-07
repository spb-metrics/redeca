<?php
/* -----------------------------------------------------
 * Script para instalação e configuração do REDECA
 *
 * feito em php para ser rodado pelo navegador e no shell
 * Não tem como objetivo prover interface para os dados passados, e sim, 
 * executar o processo de instalação.
 *
 * licença: GPL
 *
 * Variáveis:
 * action => representa o tipo de ação a ser executada 
 *		# criar banco (se tiver permissão e arquivo dbconfig.ini)
		# alimentar banco (se houver falha de conexão, cria dbconfig.ini)
		# popular banco com dados demo
		# editar CSS
		Essa variável pode ser um conjunto de ações combinados, útil para scripts.
 *
 * Outras serão de acordo com a ação escolhida.
 * ----------------------------------------------------------------------------
 */

set_include_path('.'
.PATH_SEPARATOR.'./lib'
.PATH_SEPARATOR.'./application/models/'
.PATH_SEPARATOR.'./application/models/act_'
.PATH_SEPARATOR.'./application/models/ast_'
.PATH_SEPARATOR.'./application/models/auth_'
.PATH_SEPARATOR.'./application/models/con_'
.PATH_SEPARATOR.'./application/models/cov_'
.PATH_SEPARATOR.'./application/models/csg_'
.PATH_SEPARATOR.'./application/models/eas_'
.PATH_SEPARATOR.'./application/models/edu_'
.PATH_SEPARATOR.'./application/models/emp_'
.PATH_SEPARATOR.'./application/models/ent_'
.PATH_SEPARATOR.'./application/models/exp_'
.PATH_SEPARATOR.'./application/models/fam_'
.PATH_SEPARATOR.'./application/models/gas_'
.PATH_SEPARATOR.'./application/models/hlt_'
.PATH_SEPARATOR.'./application/models/per_'
.PATH_SEPARATOR.'./application/models/res_'
.PATH_SEPARATOR.'./application/models/sop_'
.PATH_SEPARATOR.'./application/models/sys_'
.PATH_SEPARATOR.'./application/validators/'
.PATH_SEPARATOR.'./application/forms/'
.PATH_SEPARATOR.'./application/utils/'
.PATH_SEPARATOR.'./application/utils/auth'
.PATH_SEPARATOR.'./application/business/'
.PATH_SEPARATOR.'./application/loggers/'
.PATH_SEPARATOR.'./application/acl/'
.PATH_SEPARATOR.'./application/plugins/'
.PATH_SEPARATOR.'./application/controllers/'
.PATH_SEPARATOR.'./application/report/'
.PATH_SEPARATOR.get_include_path());

include_once "Zend/Loader.php";
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Config_Ini');

// Valores para a variavel action
// Pode ser com combinação binária: 1 | 2 | 4 | 8 = 15 (todas as opções)
// mas será de utilidade prática apenas: 12 (producao) e 13 (demo)
define(DATABASE_NEW,1); 
define(DATABASE_PREPARE,2); 
define(DATABASE_FAKEDATA,4); 
define(CSS_NEW,8); 

if( $argc > 1 ) {
    for( $i=1; $i<$argc; $i++ ) {
       list($a,$b) = explode('=',$argv[$i]);
       $_REQUEST[$a] = $b;
    }
}
$action = (int)$_REQUEST['action']; 

$host = $_REQUEST['host'];
$user = $_REQUEST['user'];
$pass = $_REQUEST['password'];
$dbname = $_REQUEST['dbname'];

if( $action & DATABASE_NEW ) {
  $admin  = $_REQUEST['adminuser'];
  $apass  = $_REQUEST['adminpass'];

  echo "A0. Criar Novo banco";
  
  try {
    $db = Zend_Db::factory("PDO_MYSQL", array('host'=>$host,'username'=>$admin,'password'=>$apass,'dbname'=>'mysql'));
    $db->query('CREATE DATABASE '.$dbname.' CHARACTER SET UTF8');
  } catch(Zend_Exception $e) {
     echo "\nAVISO: ".$e->getMessage();
  }
  $db->closeConnection();
  unset($db);
}

// atencao, vou sobreescrever o arquivo!!!
$fd = fopen('./application/dbconfig.ini','w');
if( !$fd ) { echo "oops error opening file dbconfig.ini"; return; }
fputs($fd, "[database]
db.adapter = PDO_MYSQL
db.config.host = $host
db.config.username = $user
db.config.password = $pass
db.config.dbname = $dbname

");
fclose($fd);

// Abre uma conexão com as configurações salvas. É uma forma de 
// garantir integridade
// 1. Se não for exclusivo CSS, não se importar.
if( $action != CSS_NEW ) {
if( !$dbconfig )
    $dbconfig = new Zend_Config_Ini('./application/dbconfig.ini','database');
try
{
    $db = Zend_Db::factory($dbconfig->db->adapter, $dbconfig->db->config->toArray());
    $db->query('SELECT 1 + 1');


    echo "\nA0.1 Apagando tabelas que existam'\n";
    $fd = fopen('./install/drop-tables.sql','r');
    if( !$fd ) { echo 'erro abrindo drop-tables.sql'; return; }
    $query = '';
    $cont  = 0;
    while( $s=fgets($fd) ) {
        $query .= $s;
        if( strpos($query,';') !== FALSE ) {
            //echo "\rS1. Registro $cont de 1831 (".(int)($cont/1831*100).'%)   ';
	    try {
		$db->query($query);
	    } catch(Zend_Exception $e) {
		//echo "\nE. Erro de preparação: ".$e->getMessage()."\n";
	    }
            $query = '';
	}
    }
    fclose($fd);

}
catch(Zend_Exception $e)
{
    echo "\nerro conectando na base: ".$e->getMessage()."\n\n";
    return;
}
}

// cria tabelas insere dados básicos
if( $action & DATABASE_PREPARE ) {
    echo "\nA1. Abrindo 'tabelas.sql'\n";
    $fd = fopen('./install/tables.sql','r');
    if( !$fd ) { echo 'erro abrindo tables.sql'; return; }
    $query = '';
    $cont = 0;
    while( $s=fgets($fd) ) {
        $query .= $s;
	$cont++;
        if( strpos($query,';') !== FALSE ) {
            //echo "\rS1. Registro $cont de 1831 (".(int)($cont/1831*100).'%)   ';
	    try {
		$db->query($query);
	    } catch(Zend_Exception $e) {
		echo "\nE. Erro de preparação: ".$e->getMessage()."\n";
	    }
            $query = '';
	}
    }
    fclose($fd);

    echo "\nA2. Abrindo 'Default Inserts'\n";
    $fd = fopen('./install/default inserts.sql','r');
    if( !$fd ) { echo 'erro abrindo "default inserts.sql"'; return; }
    $query = '';
    $cont = 0;
    while( $s=fgets($fd) ) {
        $query .= $s;
	$cont++;
        if( strpos($query,';') !== FALSE ) {
            //echo "\rS2. Registro $cont de 439 (".(int)($cont/439*100).'%)   ';
	    try {
		$db->query($query);
	    } catch(Zend_Exception $e) {
		echo "\nE. Erro de preparação: ($query): ".$e->getMessage()."\n";
	    }
            $query = '';
	}
    }
    fclose($fd);
}

if( $action & DATABASE_FAKEDATA ) {

    echo "\nA3. Inserindo dados DEMO\n";
    $fd = gzopen('./install/dump-demo.sql.gz','r');

    if( !$fd ) { echo 'erro abrindo "dump-demo.sql.gz"'; return; }

    $query = '';
    $cont  = 0;

    $ret = ini_set('memory_limit', '64M');
    if( !$ret ) echo "\nW. Isso pode dar problema... não deu para aumentar a memória máxima do PHP";

    $con = $db->getConnection();
    while( $s=gzgets($fd, 2000000) ) {
        if( $s{0} == '-' || $s{0} == '/' ) continue;
        $query .= $s;
	$cont++;
        if( strpos($query,';') !== FALSE ) {
            //echo "\rS3. Registro $cont de 1323 (".(int)($cont/1323*100).'%)    ';
	    try {
		// Tem q ser assim, senão estoura a memória devido ao tamanho das queries
		// desse jeito, o comando é passado direto pro banco.
		$con->exec($query);
	    } catch(PDOException $e) {
		echo "\nE. Erro em DEMO: ".$e->getMessage()."\n";
	    }
	    $query = '';
	}
    }

    gzclose($fd);
}

// ATENCAO! Vai sobreescrever!
if( $action & CSS_NEW ) {
    echo "\nA4. Preparando o CSS";
    $arrCores = array (
	 'BASICCOLOR_LINK' => 'orange'
	,'BASICCOLOR_TITLE'=>'#FEEDD3'
	,'BASICCOLOR_BACKGROUND'=>'#F5FFE6'
	,'BASICCOLOR_BUTTONS'=>'#F9ECCA'
	,'BASICCOLOR_BORDERCONTAINER'=>'#FFA600'
	,'BASICCOLOR_INNERCONTAINER'=>'#FFD480'
	);
    // obtem as cores submetidas.
    foreach( $arrCores as $k => $v ) {
	if(isset($_REQUEST[$k]))
            $arrCores[$k] = $_REQUEST[$k];
    }


    // vou fazer desse jeito, para q + pra frente consiga trocar um conjunto de cores padrões
    // agilizando a customização do layout.
    $fd = fopen('./public/styles/site.css.template','r');
    $fd2 = fopen('./public/styles/site.css','w');
    if( !$fd ) { echo 'erro abrindo "css.template"'; return; }
    if( !$fd2 ) { echo 'erro abrindo "css"'; return; }
    while( $s=fgets($fd) ) {
	// busca as cores no meio do texto e as substitui
        foreach( $arrCores as $k => $v ) {
	    if( strpos($s,$k) !== FALSE ) {
		$s = str_replace($k,$v,$s);
	    }
 	}
    	fwrite($fd2,$s);
    }
    fclose($fd);
    fclose($fd2);
    echo "\nS4. OK, sem erros";
}
?>

