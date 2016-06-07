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
 * Saulo Esteves Rodrigues  - W3S		   			05/01/2008	                       Create file 
 * 
 */
?>
<?php
require_once("AuthBusiness.php");

class UserLogged
{
	/**
	 * Returna true caso o usuário esteja logado;
	 * False em caso negativo.
	 * 
	 */
	public static function isLogged()
	{
		return AuthBusiness::isLogged();
	}
	
	/**
	 * Verifica se o usuário logado é do tipo Administrador;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 * 
	 */
	public static function isAdministrator()
	{
		return AuthBusiness::isAdministrator();
	}
	
	/**
	 * Verifica se o usuário logado é do tipo Administrador da Rede;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 * 
	 */
	public static function isManager()
	{
		return AuthBusiness::isManager();
	}
	
	/**
	 * Verifica se o usuário logado é do tipo Coordenador da Entidade;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 * 
	 */
	public static function isCoordinator()
	{
		return AuthBusiness::isCoordinator();
	}
	
	/**
	 * Verifica se o usuário logado é do tipo Técnico;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 * 
	 */
	public static function isTechnician()
	{
		return AuthBusiness::isTechnician();
	}
	
	/**
	 * Verifica se o usuário logado é do tipo Operador;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 * 
	 */
	public static function isOperator()
	{
		return AuthBusiness::isOperator();
	}
	
	/**
	 * Returna um array com os perfis associados ao usuário;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 * 
	 */
	public static function getProfiles()
	{
		return AuthBusiness::getProfiles();
	}
	
	/**
	 * Recupera o id do usuário logado;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 * 
	 */
	public static function getUserId()
	{
		return AuthBusiness::getUserId();
	}
	
	/**
	 * Recupera o id do grupo do usuário logado;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 * 
	 */
	public static function getGroupId()
	{
		return AuthBusiness::getGroupId();
	}

	/**
	 * Recupera o login do usuário logado;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 * 
	 */
	public static function getUserLogin()
	{
		return AuthBusiness::getUserLogin();
	}

	/**
	 * Recupera o nome do usuário logado;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 */	
	public static function getUserName()
	{
		return AuthBusiness::getUserName();
	}
	
	/**
	 * Recupera o id da entidade relacionada ao usuário logado;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 */
	public static function getEntityId()
	{
		return AuthBusiness::getEntityId();
	}
	
	/**
	 * Recupera o id do tipo do usuário logado;
	 * 
	 * throws UserNotLoggedException
	 * (utilize <i>isLogged()</i> antes)
	 */
	public static function getUserRoleId()
	{
		return AuthBusiness::getUserRoleId();
	}
}