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
 * Saulo Esteves Rodrigues  - W3S		    		10/01/2008	                       Create file 
 * 
 */


require_once('mappings.php');

class BasicController extends Zend_Controller_Action
{
	/**
	 * Inicialização básica do controller
	 * 
	 */
	function init()
	{	
		
		//messageResources
		$validatorResources 	= Zend_Registry::get(VALIDATOR_RESOURCES);
		$labelResources 		= Zend_Registry::get(LABEL_RESOURCES);
		Zend_Loader::loadClass('Constants');
		Zend_Loader::loadClass('Utils');
		
		//disponibilizando informações aos templates
		$this->view->resources 	= $validatorResources;
		$this->view->labels 	= $labelResources;

		$this->view->user 		= Zend_Auth::getInstance()->getIdentity();//informações do usuário
		$this->view->baseUrl 	= $this->_request->getBaseUrl(); //basepath para os includes no template
		$this->view->request	= $this->_request;
		
		// Recupera o nome do controller
		$controller = $this->_request->getParam('controller');
		if(UserLogged::isLogged())
		{
			try
			{
				$isOperator = UserLogged::isOperator();
			}
			catch(UserNotLoggedException $e){
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$this->view->labels->notPermission.' - '.$e);
				trigger_error($this->view->labels->notPermission , E_USER_ERROR);
			}
			if(!$isOperator)
			{
				try
				{
					$resource = ResourcePermission::getResource($controller);
				}
				catch(InvalidResourceForThisOperation $e){
					$msg = $this->view->labels->invalid->controller . ' [Controller: '. $controller. ']';
					Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ". $msg .' - '.$e);
					trigger_error($this->view->labels->invalid->controller , E_USER_ERROR);
				}
				// Seta o ID do resource
				$this->view->resource_key = $resource->{ARC_ID_RESOURCE};  
			}
		}
	}
	
	public function getRequest()
	{
		return $this->_request;
	}
	
	/**
	 * Verifica se o usuário está autenticado
	 */
	function auth()
	{
		$auth 					= Zend_Auth::getInstance();
		if(!$auth->hasIdentity())
		{
			$this->_redirect(FWD_AUTH_LOGIN);
		}
	}
	
	/**
	 * Seta as informações necessária para o componente de paginação
	 * 
	 * $page : Página atual
	 * $total: Número total de registros
	 * $tpage: Quantidade de registros por página
	 */
	function navBar($page, $total, $tpage)
	{
		$this->view->navigation		= array('page' => $page, 'total' => $total, 'tpage' => $tpage);
	}
	
	/**
	 * Considerando a página atual, calcula o valor 'start'
	 * para a listagem dos registros no banco de dados
	 * 
	 */
	function pageToStart($page, $tpage)
	{
		if($page == 1)
		{
			$start 		= 0;
		}
		else
		{
			$start 		= ($page * $tpage) - $tpage;
		}
		return $start;
	}
	
	/**
	 * Recupera os textos em config e seta para os templates (variável controller)
	 * 
	 */
	function setControllerResources($resourceName, $resourceNameVar = null)
	{
		if($resourceNameVar == null)
		{
			$this->view->controller			= new Zend_Config_Ini('./application/resources/'.$resourceName.'_'.LOCALE.'.ini', 	'controller');	
		}
		else
		{
			$this->view->controller			= new Zend_Config_Ini('./application/resources/'.$resourceName.'_'.LOCALE.'.ini', 	 'controller');
			$this->view->parsecontroller	= new Zend_Config_Ini('./application/resources/'.$resourceNameVar.'_'.LOCALE.'.ini', 'parsecontroller');	
		}
	}
	
	/**
	 * Recupera os helps online do controller
	 * 
	 */
	function setControllerHelp($resourceName)
	{
		$help							= new Zend_Config_Xml('./application/help/'.$resourceName.'_'.LOCALE.'.xml', 'items', true);
		foreach($help as $k=>$v)
		{
			$help->$k = utf8_decode($v);
		}
		$this->view->helpcontroller 	= $help;
	}
	
	
}
