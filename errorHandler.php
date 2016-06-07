<?php
/*
 * This program is free software: you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation, either version 2 of the License, or 
 * (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details. 
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program.  If not, see <http://www.gnu.org/licenses/>. 
 * 
 * @copyright Fundação Telefônica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Araçatuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guarujá - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de São Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de Várzea Paulista - http://www.varzeapaulista.sp.gov.br 
 * 
 * @copyright Copyright (C) 2008
 * 
 * @license GNU General Public License (GPL) - http://www.gnu.org/licenses/gpl.html 
 * 
 * @author Consulting services for Social Networks Creation by Instituto Fonte para o Desenvolvimento Social  - < fonte@fonte.org.br> - http:// www.fonte.org.br 
 * 
 * @author Consulting services for Software Requirements  by WebUse - <webuse@webuse.com.br > - http://webuse.com.br 
 * 
 * @author Initial Software development by W3S Solutions - <w3s@w3s.com.br> - http://w3s.com.br 
 * 
 * Changelog
 * 
 * Author                                           Date                               History 
 * -----------------------------------------        ------------                       ------------------ 
 * Saulo Esteves Rodrigues  - W3S		   			28/01/2008	                       Create file 
 * 
 */

function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
{
	switch($errno)
	{
		case E_USER_WARNING:
		case E_USER_NOTICE:
		case E_WARNING:
		case E_NOTICE:
		case E_CORE_WARNING:
		case E_COMPILE_WARNING:
			break;//ignora warnings

		case E_USER_ERROR:
		case E_ERROR:
		case E_PARSE:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
			global $query;
			if (eregi('^(sql)$', $errstr))//erro mysql
			{
				$MYSQL_ERRNO = mysql_errno();
				$MYSQL_ERROR = mysql_error();
				$errstr = "MySQL error: $MYSQL_ERRNO : $MYSQL_ERROR";
			}
			else
			{
				$query = NULL;
			}
			
			$errorstring = "<p>$errstr</p>\n";
			
			//informações detalhadas
			$errorstringComplement = "";
			if ($query) $errorstringComplement .= "<p>SQL query: $query</p>\n";
			$errorstringComplement .= "<p>Error in line $errline of file '$errfile'.</p>\n";
			$errorstringComplement .= "<p>Script: '{$_SERVER['PHP_SELF']}'.</p>\n";
			
			if (isset($errcontext['this']))
			{
				if (is_object($errcontext['this']))
				{
					$classname = get_class($errcontext['this']);
					$parentclass = get_parent_class($errcontext['this']);
					$errorstringComplement .= "<p>Object/Class: '$classname', Parent Class: '$parentclass'.</p>\n";
				}
			}
			
			//mensagem impressa para o usuário
			echo "<html>";
			echo "<head>";
			echo "<title>.::Recriad::.</title>";
			echo "<meta http-equiv='Content-Type' content='text/html; charset=ISO-8859-1' />";
			echo "</head>";
			echo "<body>";
			echo "<table width='100%'>";
			echo "<tr>";
			echo "<td align='center'>";
			echo "<b><h2><font color='blue'>Recriad</b></font></h2>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "<table border='0' width='100%'>";
			echo "<tr>";
			echo "<td align='center'>";
			echo "<table border='1' width='50%' bgcolor='#CBCBB1' >";
			echo "<tr>";
			echo "<td align='center'>";
			echo "<b><h2><font color='red'>\n$errorstring\n</b></font></h2>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";			
			echo "</body>";
			echo "</html>";
			die();
			break;
		default:
			break;
	}
}
