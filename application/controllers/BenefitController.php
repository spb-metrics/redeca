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
 * Fabricio Meireles Monteiro  - W3S		    	05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class BenefitController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Benefit');
		parent::setControllerHelp('Benefit');
		
		Zend_Loader::loadClass('BenefitValidator');
		Zend_Loader::loadClass('SocialProgramBusiness');
		Zend_Loader::loadClass('BenefitForm');
		Zend_Loader::loadClass('SocialProgram');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('Resource');
		Zend_Loader::loadClass('HistoryBusiness');
		Zend_Loader::loadClass('PersonChangeHistory');
		
		$frm = new BenefitForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
	}
	
	/**
	 * Exibe as informa��es atualmente preenchidas
	 */
	function indexAction()
	{	
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = BenefitValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//carrega as informa��es de programa social referente a uma pessoa
		$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByPerson($this->view->form->getIdPerson());
	
		//funcionalidade somente-leitura?
		if(ResourcePermission::isResourceReadOnly($this->_request))
		{
			$this->view->readOnly = TRUE;
		}
		else
		{
			$this->view->readOnly = FALSE;
		}
	}
	
	/**
	 * Exibe formul�rio para edi��o de um benef�cio
	 */
	function viewAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = BenefitValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//carrega todos os programas sociais
		$this->view->socialPrograms = SocialProgramBusiness::loadAllSocialPrograms();
		
		//carrega as informa��es de benef�cio referente a uma pessoa
		$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByPerson($this->view->form->getIdPerson());
	}
	
	/**
	 * Salva usu�rio (edi��o)
	 */
	function editAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{	
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = BenefitValidator::validateBenefit($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{	
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega todos os programas sociais
				$this->view->socialPrograms = SocialProgramBusiness::loadAllSocialPrograms();
				
				//carrega as informa��es de benef�cio referente a uma pessoa
				$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByPerson($this->view->form->getIdPerson());
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
			
			//persiste as informa��es referentes a educa��o na base de dados
			SocialProgramBusiness::saveBenefit($bean);
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(BENEFIT_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Salva usu�rio (edi��o)
	 */
	function addAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{	
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = BenefitValidator::validateBenefitAdd($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{	
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega todos os programas sociais
				$this->view->socialPrograms = SocialProgramBusiness::loadAllSocialPrograms();
				
				//carrega as informa��es de benef�cio referente a uma pessoa
				$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByPerson($this->view->form->getIdPerson());
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
					
			//persiste as informa��es referentes a educa��o na base de dados
			SocialProgramBusiness::saveBenefit($bean);
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(BENEFIT_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	
	/**
	 * Exibe formul�rio para inserir um novo benef�cio
	 */
	function newAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = BenefitValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//carrega todos os programas sociais
		$this->view->socialPrograms = SocialProgramBusiness::loadAllSocialPrograms();
		
		//carrega as informa��es de gesta��o referente a uma pessoa
		$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByPerson($this->view->form->getIdPerson());
	
		//funcionalidade somente-leitura?
		if(ResourcePermission::isResourceReadOnly($this->_request))
		{
			$this->view->readOnly = TRUE;
		}
		else
		{
			$this->view->readOnly = FALSE;
		}
	}
	
	/**
	 * Exclui um benef�cio de usu�rio
	 */
	function dropAction()
	{	
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = BenefitValidator::validateProgramId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{	
			//Retorna para o template atual
			return;
		}
		
		//persiste as informa��es referentes a educa��o na base de dados
		SocialProgramBusiness::updateStatus($this->view->form->getIdProgram());
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(BENEFIT_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
	}
	
	/**
	 * Exibe a tela de confirma��o de exclus�o de um benef�cio
	 * 
	 */
	function confirmAction()
	{
		//action a ser chamada caso o usu�rio confirma a exclus�o 
		$this->view->action = DEFAULT_DROP_ACTION;
		
		//verifica se o "id" informado pelo usu�rio � v�lido
		$errorMessages = BenefitValidator::validateProgramId($this->view->form);
		BenefitValidator::validatePersonId($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}

		//carrega as informa��es de benef�cio referente a uma pessoa
		$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByProgram($this->view->form->getIdProgram());	
	}
	
	
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */	
	function assembleFormToBean(BenefitForm $frm)
	{		
		if(!Utils::isEmpty($frm))
		{
			//cria uma vari�vel array de nome "bean" 
			$bean = array();
		
			if(!Utils::isEmpty($frm->getIdPerson()))
			{
				//adiciona uma vari�vel - idPerson - do form no array "bean"
				$bean[SPG_ID_PERSON] = $frm->getIdPerson();
			}
			
			if(!Utils::isEmpty($frm->getCollBenefit()))
			{
				//adiciona uma cole��o de objetos sa�de no array "bean"
				$bean[OBJECTS_BENEFIT] = $frm->getCollBenefit();			
			}
			
			if(!Utils::isEmpty($frm->getIdProgram()))
			{
				//adiciona uma vari�vel - idProgram - do form no array "bean"
				$bean[SPG_ID_PR_SOCIAL] = $frm->getIdProgram();
			}
			
			// Recupera o nome do controller
			$controller = $this->_request->getParam('controller');
			if(!Utils::isEmpty($controller))
			{
				//adiciona uma vari�vel - nome do controller - do form no array "bean"
				$bean[NAME_CONTROLLER] = $controller;
			}
			
			return $bean;
		}
		return null;
	}
	
	//redireciona aplica��o para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
}