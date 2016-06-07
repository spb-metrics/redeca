<style>
.destaque {
  border: 2px solid #c6f;
  background-color: #9f6;
}

#basico {
  border: 2px solid #fcc;
  background-color: #cff;
  display:block;
  text-align:left;
}

#avancado {
  border: 2px solid #fc9;
  background-color: #9cf;
  display:none;
  text-align:left;
}
#passo1data {
  color:blue;
}

.bigbox {
  background-color:#bbb;
  border:1px solid black;
  text-align:center;
  width:705px;
}
.bigbox h1 {
  background-color:#ffe;
  border-bottom:1px solid black;
  font-size:11pt;
  padding-top:3px;
  padding-bottom:5px;
}
.bigbox h2 {
  background-color:#ddd;
  font-size:10pt;
  padding-top:3px;
  padding-bottom:5px;

}
.bigbox h3 {
  background-color:#ccc;
  font-size:10pt;
  padding-top:3px;
  padding-bottom:5px;

}

.box {
  width:350px;
  height:150px;
  float:left;
}

.fail {
  color:red;
}
.ok {
  color:green;
}
</style>
<script>

function trocaTipo() {
  if( tipoS == 'basico' ) {
      tipoS = 'avancado';
      tipoA.style.display = 'block';
      tipoB.style.display = 'none';
  } else {
      tipoS = 'basico';
      tipoB.style.display = 'block';
      tipoA.style.display = 'none';
  }
}

function trocaNovoBanco() {
  if( nbanco.checked == true ) {
      passo1data.style.display = 'block';
  } else {
      passo1data.style.display = 'none';
  }

}
</script>
<center>
<div class=bigbox>
<h1>REDECA</h1>
<p>
  Bem-vindo ao Instalador do Redeca. Este pequeno programa visa simplificar o processo de instala&ccedil;&atilde;o deste software.</p><p> Note que depois que o processo de  instala&ccedil;&atilde;o for executado com sucesso, n&atilde;o ser&aacute; poss&iacute;vel acessar esta p&aacute;gina novamente.
</p>
<?php
  if( !isset($_REQUEST['submit']) )
  {
?>
<h2>Verifica&ccedil;&otilde;es B&aacute;sicas</h2>
<div style='text-align:left;'>
<?php
  /* 
   * 1. Verifica algumas permissões e requisitos
   */
  // funcao para acertar permissão de leitura
  function permissao($filename)
  {
     // comeca do mais especifico para o + generico
     // owner. 
     if( !@chmod($filename, 0755) ) return false;
     if( is_writable($filename) )  return true;

     if( !@chmod($filename, 0775) ) return false;
     if( is_writable($filename) )  return true;

     // others
     if( !@chmod($filename, 0777) ) return false;
     if( is_writable($filename) )  return true;


     return false;
  }

  //
  // Testa as permissões
  // 

  // array com callback.
  $arrFileChecks = array( 
 	 './application/dbconfig.ini' => 'permissao'
	,'./logs' => 'permissao'
	,'./.htaccess' => 'permissao'
	);

  $checkfail= false;
  foreach( $arrFileChecks as $k => $v )
  {
      if( is_writable($k) )
          echo "<p class=ok>[v] Permiss&atilde;o ok ao '$k'</p>";
      else {
          echo "<p class=fail>[ ] Erro de permiss&atilde;o com '$k': n&atilde;o &eacute; poss&iacute;vel escrever</p>";
          if( !$v($k) ) {
		echo '<p class=fail>-- X Tentei arrumar mas n&atilde;o consegui.</p>';
		$checkfail=true;
	  } else
		echo '<p class=ok>-- OK Consegui arrumar.</p>';
      }
  }

  // modulo rewrite

  if( function_exists('apache_get_modules') )
  if( FALSE === array_search('mod_rewrite', apache_get_modules()) ) {
        echo "<p class=fail>[X] M&oacute;dulo Rewrite do Apache N&Atilde;O est&aacute; instalado</p>";
	$checkfail = true;
  } else
        echo "<p class=ok>[v] M&oacute;dulo Rewrite do Apache Ok</p>";

  ?>
</div>
<?php

  // ---------
  // Para o processo se houve erros irrecuperaveis
  //
  if( $checkfail == true ) {
	echo '<p>Por favor, corrija os erros apresentados acima antes de tentar proceder com instala&ccedil;&atilde;o</p></div>';
	return;
  }
  /* * * * * * * * * * * * * * * *
   * 2. Processo de Instalação
   */
?>
<h2>Instala&ccedil;&atilde;o</h2>
<form name=install method=post>
<div class=box>
  <h3>Qual o tipo de sistema a ser instalado?</h3>
  <p class=destaque><input type=radio name=modo value=producao checked=true >Produ&ccedil;&atilde;o <input type=radio name=modo value=demo>Demo</p>
</div>
<div class=box>
  <h3>Forma de instala&ccedil;&atilde;o</h3>
  <p class=destaque><input type=radio name=tipo value=basico checked=true onclick="javascript:trocaTipo();">B&aacute;sico <input type=radio name=tipo value=avancado onclick="javascript:trocaTipo();">Avan&ccedil;ado
</div>
<div id=basico>
     <p>No m&eacute;todo b&aacute;sico, voc&ecirc; ter&aacute; uma instala&ccedil;&atilde;o simples, ideal para quem quer testar apenas o sistema, sem se preocupar muito com outras configura&ccedil;&otilde;es ou seguran&ccedil;a</p>
     <p>Usu&aacute;rio administrador do MySQL: <input size=10 type=input name=b.admin value=root> Senha: <input size=10 type=input name=b.adminpass value=></p>

</div>
<div id=avancado>
    <p>No m&eacute;todo avan&ccedil;ado, voc&ecirc; ter&aacute; um maior controle sobre o que ser&aacute; criado, ideal para quem far&aacute; a instala&ccedil;&atilde;o em ambientes mais restritos ou hospedados em terceiros </p>
    <ol>
      <li> Nome do banco de dados <br><input type=input name=a.dbname value=db_network> <input type=checkbox id=passo1 name=passo1 checked onclick="javascript:trocaNovoBanco();"> Criar novo</li>
      <p id=passo1data>Admin do MySQL: <input size=10 type=input name=a.admin value=root> Senha: <input size=10 type=input name=a.adminpass value=></p>
      <li>
        <table>
	<tr><td>Usu&aacute;rio do MySQL: </td><td> <input size=10 type=input name=a.user value=root> </td></tr>
	<tr><td>Senha: </td><td><input size=10 type=input name=a.password value=></td></tr>
	<tr><td>Host:  </td><td><input size=18 type=input name=a.host value=localhost></td></tr>
	</table>
      </li>
      <li><input type=checkbox name=passo3 checked> Popular com dados pr&eacute;-configurados (Essencial: n&atilde;o desmarque a menos que saiba o que faz)</li>
      <li><input type=checkbox name=passo4 checked> Criar CSS padr&atilde;o. Voc&ecirc; pode rapidamente customizar as cores do layout com as op&ccedil;&otilde;es abaixo, arrastando as cores da "Paleta" para os campos abaixo.
      <p>
	<!-- incluindo a Paleta -->
        <?php include('./install/paleta.html'); ?>
      </p>
      </li>
    </ol>
</div>
<div>
 <p><b>Informa&ccedil;&otilde;es do rodap&eacute;:</b></p>
 <p>Institui&ccedil;&atilde;o: <input name=footer_name value="Fundacao"></p>
 <p>S&iacute;tio da internet: <input name=footer_url value="http://www.ft.com.br"></p>
 <p>Texto b&aacute;sico: <input name=footer_tel_text value="Telefone para contato"></p>
 <p>Telefone para contato: <input name=footer_tel_number value="(11) 1111-0000"></p>
</div>
  <input type=submit name=submit value="Instalar o software">
</form>
<script>
tipoS = 'basico';
tipoB = document.getElementById('basico');
tipoA = document.getElementById('avancado');

passo1data = document.getElementById('passo1data');
nbanco     = document.getElementById('passo1');

</script>
<?php

  } else {

  echo '<h2>Instalando</h2><pre align=left>';
  /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
   * 3. Processamento do formulário e a instalação de fato.
   */

  // pre-definidos
  $admin='';
  $_REQUEST['host']      = 'localhost';
  $_REQUEST['user']      = 'root';
  $_REQUEST['password']  = '';
  $_REQUEST['dbname']    = 'db_network';
  $_REQUEST['adminuser'] = 'root';
  $_REQUEST['adminpass'] = '';

define('DATABASE_NEW',1);
define('DATABASE_PREPARE',2);
define('DATABASE_FAKEDATA',4);
define('CSS_NEW',8);

  //
  // acerta as variaveis necessárias
  //
  if( $_REQUEST['tipo'] == 'avancado' ) {
	  $_REQUEST['host']      = $_REQUEST['a_host'];
	  $_REQUEST['user']      = $_REQUEST['a_user'];
	  $_REQUEST['password']  = $_REQUEST['a_password'];
	  $_REQUEST['dbname']    = $_REQUEST['a_dbname'];
	  $_REQUEST['adminuser'] = $_REQUEST['a_admin'];
	  $_REQUEST['adminpass'] = $_REQUEST['a_adminpass'];

          $_REQUEST['action']=0;

          if( $_REQUEST['passo1']=='on' ) 
		$_REQUEST['action'] |= DATABASE_NEW;

          if( $_REQUEST['passo3']=='on' ) 
		$_REQUEST['action'] |= DATABASE_NEW;

          if( $_REQUEST['modo'] == 'producao' ) 
		$_REQUEST['action'] |= DATABASE_PREPARE;
	  else
		$_REQUEST['action'] |= DATABASE_FAKEDATA;

          if( $_REQUEST['passo4']=='on' ) 
		$_REQUEST['action'] |= CSS_NEW;
  } else {
          $_REQUEST['adminuser'] = $_REQUEST['user']     = $_REQUEST['b_admin'];
          $_REQUEST['adminpass'] = $_REQUEST['password'] = $_REQUEST['b_adminpass'];
          $_REQUEST['action']    = (DATABASE_NEW|CSS_NEW)|($_REQUEST['modo']=='producao'?DATABASE_PREPARE:DATABASE_FAKEDATA);
  }

  /*
   * chama o script de instalação propriamente dito
   */
  include('install/installer.php');

  /*
   * Acerta o .htaccess para evitar novo acesso ao instalador
   */
  $fd = @fopen('./.htaccess', 'w');
  if( $fd )  {
      fwrite($fd,'RewriteEngine on
RewriteRule .* index.php
');


      // Modo CGI pode dar problema com essas linhas
      if( function_exists('apache_get_modules') )
      if( FALSE !== array_search('mod_php5',apache_get_modules()) )
          fwrite($fd,'
php_flag magic_quotes_gpc off
php_flag register_globals off
'); 
      fclose($fd);

      echo "\n.htaccess modificado. OK";

  } else
      echo "\nERRO tentando modificar o .htaccess";
      echo '</pre><a href=/>Clique aqui para acessar o sistema</a>';
  }

  $fd = @fopen('./application/config.ini', 'a');
  if( $fd )  {
      fwrite($fd,"
; ###FOOTER
name.text = \"{$_REQUEST['footer_name']}\"
link.text = \"{$_REQUEST['footer_url']}\"
phone.text = \"{$_REQUEST['footer_tel_text']}\"
phone.number = \"{$_REQUEST['footer_tel_number']}\"
");
  }

?>
</div>
</center>
