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
 * @copyright Funda��o Telef�nica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Ara�atuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guaruj� - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de S�o Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de V�rzea Paulista - http://www.varzeapaulista.sp.gov.br 
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
 * Lucas dos Santos Borges Corr�a  - W3S		   	05/05/2008	                       Create file 
 * 
 */
require_once('BasicController.php');

class ProfileController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources(CLS_AUTH_PROFILE);
		parent::setControllerHelp(CLS_AUTH_PROFILE);
		
		Zend_Loader::loadClass('ProfileForm');
		Zend_Loader::loadClass('ProfileBusiness');
		Zend_Loader::loadClass('Profile');
		Zend_Loader::loadClass('ProfileValidator');
		Zend_Loader::loadClass('Utils');
		
		$frm = new ProfileForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
	}
	
	/**
	 * Exibir formul�rio para o cadastro de um novo perfil e carrega todos perfis cadastrados no banco
	 */
	function indexAction()
	{
		//carrega todos os perfis cadastrados no banco de dados
		$this->view->profiles = ProfileBusiness::loadAll();
	}
	
	/**
	 * Salva um novo perfil (cadastro)
	 */
	function addAction()
	{	
		
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = ProfileValidator::validateProfileAdd($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega todos os perfis cadastrados no banco de dados
			$this->view->profiles = ProfileBusiness::loadAll();
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//converte vari�veis do form para objeto do tipo "profile" 
		$profile = $this->assembleFormToProfile($this->view->form);
		
		//persiste as informa��es do perfil na base de dados
		ProfileBusiness::save($profile);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(PROFILE_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe formul�rio para edi��o de um perfil cadastrado
	 */
	function viewAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = ProfileValidator::validateProfileId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega todos os perfis cadastrados no banco de dados
			$this->view->profiles = ProfileBusiness::loadAll();
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//recupera do banco de dados o objeto "Perfil" a ser editado, de acordo com o "id" informado
		$objectProfile = ProfileBusiness::load($this->view->form->getId());
				
		//converte o objeto "Perfil" retornado para vari�veis do form
		$this->assembleProfileToForm($objectProfile);
		
		//carrega novamente todos os perfis cadastrados na base de dados
		$this->view->profiles = ProfileBusiness::loadAll();
	}
	
	/**
	 * Carrega perfil (edi��o)
	 */
	function editAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = ProfileValidator::validateProfile($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega todos os perfis cadastrados no banco de dados
			$this->view->profiles = ProfileBusiness::loadAll();
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//converte vari�veis do form para objeto do tipo "profile" 
		$profile = $this->assembleFormToProfile($this->view->form);
		
		//persiste as informa��es do perfil na base de dados
		ProfileBusiness::save($profile);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(PROFILE_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe a tela de confirma��o de exclus�o de um perfil
	 * 
	 */
	function confirmAction()
	{
		//action a ser chamada caso o usu�rio confirma a exclus�o 
		$this->view->action = DEFAULT_DROP_ACTION;
		
		//verifica se o "id" informado pelo usu�rio � v�lido
		$errorMessages = ProfileValidator::validateProfileId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		//carrega os perfis a serem exclu�dos
		$this->view->objectProfiles = ProfileBusiness::load($this->view->form->getId());		
	}
		
	/**
	 * Remove um perfil
	 */
	function dropAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio
		$errorMessages = ProfileValidator::validateProfileId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			return;
		}

		//converte vari�veis do form para objeto "profile"		
		$profile = $this->assembleFormToProfile($this->view->form);

		//remove as informa��es do perfil da base de dados
		ProfileBusiness::drop($profile[AUTH_ID_PROFILE]);
		
		//redireciona fluxo da aplica��o para p�gina "index" do perfil 
		//$this->_redirect(PROFILE_CONTROLLER.'/'.DEFAULT_INDEX_ACTION);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(PROFILE_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */	
	function assembleFormToProfile(ProfileForm $frm)
	{	
		if(!Utils::isEmpty($frm))
		{
			//cria uma vari�vel do tipo array com nome "profile" 
			$profile = array();
		
			if(Utils::isEmpty($frm->getId()))
			{
				$profile[AUTH_ID_PROFILE] = null;
			}
			else
			{
				//adiciona vari�vel - id - do form no array "profile"
				$profile[AUTH_ID_PROFILE] = $frm->getId();	
			}
			
			if(Utils::isEmpty($frm->getProfileName()))
			{
				//adiciona vari�vel - profileName - no array "profile"
				$profile[AUTH_PROFILE] = null;
			}			
			else
			{
				//adiciona vari�vel - profileName - no array "profile"
				$profile[AUTH_PROFILE] = $frm->getProfileName();				
			}

			//adiciona vari�vel - status - do form no array "profile"
			$profile[AUTH_STATUS] = $frm->getStatus();	

			return $profile;
		}
		return null;
	}
	
	/**
	 * Converte objeto retornado para o form 
	 */
	function assembleProfileToForm($profile)
	{	
		//se lista retornou mais de um objeto, pegar somente o primeiro objeto dessa lista
		foreach($profile as $object)
		{
			$profile = $object;
			break;
		}
		
		//seta no form o "id" do "Perfil"
		$this->view->form->setId($profile->{AUTH_ID_PROFILE});
		
		//seta no form o nome do "Perfil"
		$this->view->form->setProfileName($profile->{AUTH_PROFILE});
		
		//seta no form o status do "Perfil"
		$this->view->form->setStatus($profile->{AUTH_STATUS});
	}
	
	//redireciona aplica��o para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
}