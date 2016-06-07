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
 * Fabricio Meireles Monteiro  - W3S		    	05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class BenefitController extends BasicController
{
	/**
	 * Inicialização
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
	 * Exibe as informações atualmente preenchidas
	 */
	function indexAction()
	{	
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = BenefitValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			//retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		//carrega as informações de programa social referente a uma pessoa
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
	 * Exibe formulário para edição de um benefício
	 */
	function viewAction()
	{
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = BenefitValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			//retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		//carrega todos os programas sociais
		$this->view->socialPrograms = SocialProgramBusiness::loadAllSocialPrograms();
		
		//carrega as informações de benefício referente a uma pessoa
		$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByPerson($this->view->form->getIdPerson());
	}
	
	/**
	 * Salva usuário (edição)
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
			//valida as informações inseridas no form pelo usuário  
			$errorMessages = BenefitValidator::validateBenefit($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{	
				//carrega variável com a(s) mensagem(ens) de erro de validação
				$this->view->errorMessages = $errorMessages;
				
				//carrega todos os programas sociais
				$this->view->socialPrograms = SocialProgramBusiness::loadAllSocialPrograms();
				
				//carrega as informações de benefício referente a uma pessoa
				$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByPerson($this->view->form->getIdPerson());
				
				//Retorna para o template atual exibindo as mensagens de validação
				return;
			}
			
			//converte variáveis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
			
			//persiste as informações referentes a educação na base de dados
			SocialProgramBusiness::saveBenefit($bean);
			
			//redireciona fluxo da aplicação para página de sucesso
			$this->_redirect(BENEFIT_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Salva usuário (edição)
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
			//valida as informações inseridas no form pelo usuário
			$errorMessages = BenefitValidator::validateBenefitAdd($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{	
				//carrega variável com a(s) mensagem(ens) de erro de validação
				$this->view->errorMessages = $errorMessages;
				
				//carrega todos os programas sociais
				$this->view->socialPrograms = SocialProgramBusiness::loadAllSocialPrograms();
				
				//carrega as informações de benefício referente a uma pessoa
				$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByPerson($this->view->form->getIdPerson());
				
				//Retorna para o template atual exibindo as mensagens de validação
				return;
			}
			
			//converte variáveis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
					
			//persiste as informações referentes a educação na base de dados
			SocialProgramBusiness::saveBenefit($bean);
			
			//redireciona fluxo da aplicação para página de sucesso
			$this->_redirect(BENEFIT_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	
	/**
	 * Exibe formulário para inserir um novo benefício
	 */
	function newAction()
	{
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = BenefitValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			//retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		//carrega todos os programas sociais
		$this->view->socialPrograms = SocialProgramBusiness::loadAllSocialPrograms();
		
		//carrega as informações de gestação referente a uma pessoa
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
	 * Exclui um benefício de usuário
	 */
	function dropAction()
	{	
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = BenefitValidator::validateProgramId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{	
			//Retorna para o template atual
			return;
		}
		
		//persiste as informações referentes a educação na base de dados
		SocialProgramBusiness::updateStatus($this->view->form->getIdProgram());
		
		//redireciona fluxo da aplicação para página de sucesso
		$this->_redirect(BENEFIT_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
	}
	
	/**
	 * Exibe a tela de confirmação de exclusão de um benefício
	 * 
	 */
	function confirmAction()
	{
		//action a ser chamada caso o usuário confirma a exclusão 
		$this->view->action = DEFAULT_DROP_ACTION;
		
		//verifica se o "id" informado pelo usuário é válido
		$errorMessages = BenefitValidator::validateProgramId($this->view->form);
		BenefitValidator::validatePersonId($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}

		//carrega as informações de benefício referente a uma pessoa
		$this->view->programsByPerson = SocialProgramBusiness::loadProgramSocialByProgram($this->view->form->getIdProgram());	
	}
	
	
	
	/**
	 * Recupera as informações do form e retorna no array
	 */	
	function assembleFormToBean(BenefitForm $frm)
	{		
		if(!Utils::isEmpty($frm))
		{
			//cria uma variável array de nome "bean" 
			$bean = array();
		
			if(!Utils::isEmpty($frm->getIdPerson()))
			{
				//adiciona uma variável - idPerson - do form no array "bean"
				$bean[SPG_ID_PERSON] = $frm->getIdPerson();
			}
			
			if(!Utils::isEmpty($frm->getCollBenefit()))
			{
				//adiciona uma coleção de objetos saúde no array "bean"
				$bean[OBJECTS_BENEFIT] = $frm->getCollBenefit();			
			}
			
			if(!Utils::isEmpty($frm->getIdProgram()))
			{
				//adiciona uma variável - idProgram - do form no array "bean"
				$bean[SPG_ID_PR_SOCIAL] = $frm->getIdProgram();
			}
			
			// Recupera o nome do controller
			$controller = $this->_request->getParam('controller');
			if(!Utils::isEmpty($controller))
			{
				//adiciona uma variável - nome do controller - do form no array "bean"
				$bean[NAME_CONTROLLER] = $controller;
			}
			
			return $bean;
		}
		return null;
	}
	
	//redireciona aplicação para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
}