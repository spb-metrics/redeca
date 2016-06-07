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
 * Jefferson Barros Lima  - W3S		   				29/02/2008	                       Create file 
 * 
 */
require_once("pt_metaphone/pt_metaphone.php");

class MetaPhoneClass
{
	public static function getMetaPhone($value)
	{
		if($value === 0 || $value === '0') return '0';
		if(!empty($value))
		{
			//instancia arquivo deconfiguração para carregar o delimitador que quebra a string metaname 
			$config = Zend_Registry::get(CONFIG);
			
			// Converte para letras maiúsculas(apesar do código de metaphone fazer isso, é necessário também neste ponto) 
			$value = strtoupper($value);
			// Relevante subtrair para obter uma boa pesquisa
			$value = ereg_replace(" DA ",' ',$value);
			$value = ereg_replace(" DE ",' ',$value);
			$value = ereg_replace(" DO ",' ',$value);
			$value = ereg_replace(" DAS ",' ',$value);
			$value = ereg_replace(" DOS ",' ',$value);
			// Substitui espaços duplicados por apenas um espaço
			$value = ereg_replace("[ ]+",' ',$value);

			return $config->metaname->delimiter . portuguese_metaphone($value) . $config->metaname->delimiter;
		}
		return NULL;
	}


//	public static function getMetaPhone($value)
//	{
//		if($value === 0 || $value === '0') return '0';
//		if(!empty($value))
//		{
//			//instancia arquivo deconfiguração para carregar o delimitador que quebra a string metaname 
//			$config = Zend_Registry::get(CONFIG);
//			
//			$str=strtoupper($value);
//			// Relevante subtrair para obter uma boa pesquisa - o metafone não sabe disso. 
//			$strArray = split("DA|DE|DO|DAS|DOS| ", $str); 
//			$meta="";
//			if(count($strArray) == 0)
//			{
//				$meta = metaphone($value);
//			}
//			else
//			{
//				foreach($strArray as $k => $v)
//				{
//				    if(!empty($v))
//				    	$meta .= $config->metaname->delimiter.metaphone($v);
////				    $meta .= metaphone($v);
//				} 
//			}
//			if(empty($meta)) return $value;
//			else return $meta.$config->metaname->delimiter;
////			else return $meta;
//			
//		}
//		return NULL;
//	}

}
