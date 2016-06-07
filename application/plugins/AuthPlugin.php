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
 * Saulo Esteves Rodrigues  - W3S		   			17/03/2008	                       Create file 
 * 
 */

?>
<?php
class AuthPlugin extends Zend_Controller_Plugin_Abstract
{
	private $_auth;
	private $_acl;
	
	/**
	 * Action para redirect - sem permissão (access-denied)
	 * 
	 */
	private $_noacl = array
	(
		'module' 		=> 'default',
		'controller' 	=> 'access-denied',
		'action' 		=> 'index'
	);
	
	/**
	 * Action para redirect - sem autenticação (tela de login)
	 * 
	 */
	private $_noauth = array
	(
		'module' 		=> 'default',
		'controller' 	=> 'auth',
		'action' 		=> 'index'
	);
	
	/**
	 * Construtor
	 * 
	 */
	public function __construct($auth, $acl)
	{
		$this->_auth 	= $auth;
		$this->_acl 	= $acl;
	}
		
	/**
	 * Método chamado antes de processar as actions
	 * 
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$role 			= Constants::ZEND_ACL_ROLE;
		
		//permissionamento
		$controller 	= $request->controller;
		$action 		= $request->action;
		$module 		= $request->module;
		
		//usuário logado?
		$readOnly		= false;
		try
		{
			$readOnly	= AuthBusiness::isResourceReadOnly($controller);
		}
		catch(InvalidResourceForThisOperation $e)
		{
			;
		}
		catch(UserNotLoggedException $e)
		{
			;
		}
		


		//define para qual controller/action o usuário será encaminhado
		try
		{
			if(!$this->_acl->isAllowed($role, $controller, $action))
			{
				if(!$this->_auth->hasIdentity())
				{
					$module 		= $this->_noauth['module'];
					$controller 	= $this->_noauth['controller'];
					$action 		= $this->_noauth['action'];
				}
				else
				{
					$module 		= $this->_noacl['module'];
					$controller 	= $this->_noacl['controller'];
					$action 		= $this->_noacl['action'];
				}
			}
			
			//verificação somente-leitura
			if($readOnly)
			{
				if($action == DEFAULT_NEW_ACTION || $action == DEFAULT_ADD_ACTION || $action == DEFAULT_EDIT_ACTION || $action == DEFAULT_DROP_ACTION)
				{
					$module 		= $this->_noacl['module'];
					$controller 	= $this->_noacl['controller'];
					$action 		= $this->_noacl['action'];
				}
			}
		}
		catch(Zend_Acl_Exception $e)
		{
			$resources = Zend_Registry::get(LABEL_RESOURCES);
			trigger_error($resources->permission->proccess->fail, E_USER_ERROR);
		}
		
		$request->setModuleName($module);
		$request->setControllerName($controller);
		$request->setActionName($action);
	}
}
