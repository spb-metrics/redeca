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

class ResourcePermission
{
	/**
	 * Retorna true caso o usuário possa modificar a confidencialidade
	 * padrão do atendimento;
	 * 
	 * throws UserNotLoggedException, InvalidResourceForThisOperation
	 * 
	 */
	public static function allowChangeDefaultConfidentiality(Zend_Controller_Request_Abstract $request)
	{
		$controller 	= $request->controller;	
		return AuthBusiness::allowChangeDefaultConfidentiality($controller);
	}
	
	/**
	 * Considerando o grupo do usuário, retorna a confidencialidade 
	 * padrão para o atendimento;
	 * 
	 * throws UserNotLoggedException, InvalidResourceForThisOperation
	 * 
	 * Vide <i>allowChangeDefaultConfidentiality()</i> 
	 * Vide <i>models/auth_/GroupResource.php</i>
	 * Constants: CONFIDENTIALITY_ENTITY, CONFIDENTIALITY_PROFILE e CONFIDENTIALITY_PRIVATE
	 * 
	 */
	public static function getDefaultConfidentiality(Zend_Controller_Request_Abstract $request)
	{
		$controller 	= $request->controller;	
		return AuthBusiness::getDefaultConfidentiality($controller);
	}
	
	/**
	 * Compatível apenas para as funcionalidades do tipo E (entidade);
	 * Serve para verificar se deve exibir botões do tipo 'EDITAR', 'SALVAR', etc;
	 * 
	 * throws UserNotLoggedException, InvalidResourceForThisOperation
	 * 
	 */
	public static function isResourceReadOnly(Zend_Controller_Request_Abstract $request)
	{
		$controller 	= $request->controller;	
		return AuthBusiness::isResourceReadOnly($controller);
	}
	
	/**
	 * Recupera todas as funcionalidades (resources) que o usuário
	 * possui permissão;
	 * 
	 * throws UserNotLoggedException
	 * 
	 */
	public static function getResources()
	{
		return AuthBusiness::getResources();
	}
	
	/**
	 * Booleano que indica se deve exibir os containers de criança selecionada;
	 * 
	 */
	public static function showContainers(Zend_Controller_Request_Abstract $request)
	{
		$controller 	= $request->controller;
		return AuthBusiness::showContainers($controller);
	}
	
	/**
	 * Verifica se o usuário tem permissão de acesso para o nome do controller
	 * informado (utilizado para montar os menus);
	 * 
	 */
	public static function isAllowResource($controllerName)
	{
		return AuthBusiness::isAllowResource($controllerName);
	}
	
	/**
	 * Retorna o resource dado o nome do controller;
	 * 
	 */
	public static function getResource($controllerName)
	{
		return AuthBusiness::getResource($controllerName);
	}
}