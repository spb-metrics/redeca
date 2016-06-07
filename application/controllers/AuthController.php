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
 * Saulo Esteves Rodrigues  - W3S		    		28/01/2008	                       Create file 
 * 
 */

require_once('BasicController.php');

class AuthController extends BasicController
{
	/**
	 * Inicialização
	 * 
	 */
	function init()
	{
		parent::init();

		Zend_Loader::loadClass('AuthValidator');
		Zend_Loader::loadClass('AuthBusiness');
		Zend_Loader::loadClass('AuthForm');
		Zend_Loader::loadClass('Logger');
		
		$frm				= new AuthForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
		parent::setControllerResources('Auth');
		parent::setControllerHelp('Auth');
	}
	
	/**
	 * Exibe o formulário de autenticação
	 * 
	 */
	function indexAction()
	{	
		;
	}
	
	/**
	 * Efetua a autenticação
	 * 
	 */
	function loginAction()
	{
		$errorMessages 				= AuthValidator::validateCredentials($this->view->form->getUsername(), $this->view->form->getPassword());
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages 	= $errorMessages;
			$this->view->message 		= $this->view->controller->login->failed;
			return;
		}

		$auth 	= AuthBusiness::auth($this->view->form->getUsername(), $this->view->form->getPassword());
		if($auth == false)
		{
			$this->view->message = $this->view->controller->login->failed;
			return;
		}
		if(UserLogged::isAdministrator() || UserLogged::isManager() || UserLogged::isCoordinator())
			$this->_redirect(ENTITY_CONTROLLER);

		//outros tipos de usuários redirecionam para a página de busca
		$this->_redirect(SEARCH_CONTROLLER);
	}
	
	/**
	 * Efetua a saída do sistema
	 * 
	 */
	function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect(FWD_HOME);
	}
}