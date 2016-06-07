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

class BiologicalRelationshipController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('BiologicalRelationship');
		parent::setControllerHelp('BiologicalRelationship');
		
		Zend_Loader::loadClass('SearchForm');
		Zend_Loader::loadClass('FamilyBusiness');
		Zend_Loader::loadClass('ConsanguineBusiness');
		Zend_Loader::loadClass('Family');
		Zend_Loader::loadClass('FamilyId');
		Zend_Loader::loadClass('SearchValidator');
		Zend_Loader::loadClass('Constants');
		Zend_Loader::loadClass('SearchBusiness');
		Zend_Loader::loadClass('MetaPhoneClass');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('ConsanguineType');
		Zend_Loader::loadClass('Consanguine');
		
		$this->view->documentOptions 	= Utils::documentOptions();
		
		$frm = new SearchForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
	}
	
	/**
	 * Exibir formulário para a ediçao da relação biológica
	 */
	function viewAction()
	{
		//carrega os tipos de relação biológica
		$this->view->kinship = self::loadTypesConsanguine();		
	}
		
	/**
	 * Salva uma nova relação biológica
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
			$errorMessages = SearchValidator::validatePersonId($this->view->form);
			SearchValidator::validateParentId($this->view->form, $errorMessages);
			SearchValidator::validateKinshipId($this->view->form, $errorMessages);
			SearchValidator::validateBiologicalAge($this->view->form, $errorMessages);
			SearchValidator::validateBiologicalUnique($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega variável com a(s) mensagem(ens) de erro de validação
				$this->view->errorMessages = $errorMessages;
				
				//Retorna para o template atual exibindo as mensagens de validação
				return;
			}
			
			//converte variáveis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
			
			//persiste as informações referentes a educação na base de dados
			ConsanguineBusiness::save($bean);
			
			//redireciona fluxo da aplicação para página de sucesso
			$this->_redirect(BIOLOGICALRELATIONSHIP_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Salva relação biológica
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
			$errorMessages = SearchValidator::validatePersonId($this->view->form);
			SearchValidator::validateParentId($this->view->form, $errorMessages);
			SearchValidator::validateKinshipId($this->view->form, $errorMessages);
			SearchValidator::validateBiologicalAge($this->view->form, $errorMessages);
			SearchValidator::validateBiologicalUnique($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega variável com a(s) mensagem(ens) de erro de validação
				$this->view->errorMessages = $errorMessages;			
				if($this->view->form->getPersonId())
					$this->view->form->setPrsName(PersonBusiness::load($this->view->form->getIdParent())->current()->{PRS_NAME});
					
				//carrega os tipos de relação familiar
				$this->view->kinship = self::loadTypesConsanguine();
				
				//Retorna para o template atual exibindo as mensagens de validação
				return;
			}
			
			//converte variáveis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
			
			//persiste as informações referentes a educação na base de dados
			ConsanguineBusiness::save($bean);
					
			//redireciona fluxo da aplicação para página de sucesso
			$this->_redirect(BIOLOGICALRELATIONSHIP_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Exibe formulário de busca
	 */
	function indexAction()
	{
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = SearchValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			//Retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		//carrega as informações de relação familiar referente a uma pessoa
		$this->view->relationshipByPerson = ConsanguineBusiness::loadBiologicalByIdPerson($this->view->form->getIdPerson());
		
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
	 * Realiza a busca conforme nome passado
	 */
	function searchByNameAction()
	{
		$errorMessages = SearchValidator::validateSearchPersonFamily($this->view->form);
			
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;			
			return;
		}
		
		$resPerson = SearchBusiness::searchPerson($this->view->form);
				
		// verifica se a busca contém um número de registro maior que o configurado
		$config = Zend_Registry::get(CONFIG);				
		if(count($resPerson) > $config->search->document->max->size)
		{
			$errorMessages[SearchForm::prsName()][][] = $this->view->controller->search->error->size;
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		$this->view->resultSearchByName = $resPerson;
		
		$this->view->kinship = self::loadTypesConsanguine();
	}
	
	/**
	 * Realiza a busca conforme documento passado
	 */
	function searchByDocumentAction()
	{
		$errorMessages = SearchValidator::validateSearchDoc($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
				
		// recupera informações do form
		$docNumber = $this->view->form->getDocNumber();
		$docType = $this->view->form->getDocType();

		if($docNumber != null && $docType != null)
		{
			// busca por rg
			if($docType == DOC_RG_NUMBER)
			{
				if(count($docNumber) == 9)
				{
					$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
				}
				else
				{
					$resDoc= SearchBusiness::searchDocumentLike($docType, $docNumber);
				}
			}
			
			// busca por cpf
			if($docType == DOC_CPF)
			{
				if(count($docNumber) == 11)
				{
					$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
				}
				else
				{
					$resDoc = SearchBusiness::searchDocumentLike($docType, $docNumber);
				}
			}
			
			// busca por ctps
			if($docType == CTS_NUMBER)
			{
				if(count($docNumber) == 5)
				{
					$resDoc = SearchBusiness::searchCtpsEqual($docType, $docNumber);
				}
				else
				{
					$resDoc = SearchBusiness::searchCtpsLike($docType, $docNumber);
				}
			}
					
			// busca por titulo de eleitor
			if($docType == DOC_TITLE_NUMBER)
			{
				if(count($docNumber) == 10)
				{
					$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
				}
				else
				{
					$resDoc = SearchBusiness::searchDocumentLike($docType, $docNumber);
				}
			}
					
			// busca por cartão do sus
			if($docType == DOC_SUS_CARD)
			{
				if(count($docNumber) == 11)
				{
					$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
				}
				else
				{		
					$resDoc = SearchBusiness::searchDocumentLike($docType, $docNumber);
				}
			}
					
			// busca por nis
			if($docType == DOC_NIS)
			{
				if(count($docNumber) == 11)
				{
					$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
				}
				else
				{
					$resDoc = SearchBusiness::searchDocumentLike($docType, $docNumber);
				}
			}
					
			// busca por todos
			if($docType == Constants::ALL)
			{
				$resDoc = SearchBusiness::searchAllDocuments($docNumber);
			}
			
			// verifica se a busca contém um número de registro maior que o configurado
			$config = Zend_Registry::get(CONFIG);				
			if(count($resDoc) > $config->search->document->max->size)
			{
				$errorMessages[SearchForm::docNumber()][] = array($this->view->controller->search->error->size);
				$this->view->errorMessages = $errorMessages;
				return;
			}
			
			$this->view->resultSearchByDocument = $resDoc;
			
			$this->view->kinship = self::loadTypesConsanguine();
		}	
	}
	
	/**
	 * Exclui um relacionamento biológico cadastrado
	 */
	function dropAction()
	{
		//valida as informações vindas do form
		$errorMessages = SearchValidator::validatePersonId($this->view->form);
		SearchValidator::validateParentId($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{	
			return;
		}

		//converte variáveis do form para objeto do tipo "bean" 
		$bean = $this->assembleFormToBean($this->view->form);
		
		//persiste as informações referentes a educação na base de dados
		ConsanguineBusiness::drop($bean);
		
		//redireciona fluxo da aplicação para página de sucesso
		$this->_redirect(BIOLOGICALRELATIONSHIP_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
	}
	
	
	/**
	 * Exibe a tela de confirmação de exclusão de uma relação biológica
	 * 
	 */
	function confirmAction()
	{
		//action a ser chamada caso o usuário confirma a exclusão 
		$this->view->action = DEFAULT_DROP_ACTION;
		
		//valida as informações vindas do form
		$errorMessages = SearchValidator::validatePersonId($this->view->form);
		SearchValidator::validateParentId($this->view->form, $errorMessages);
		SearchValidator::validateNamePerson($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}		
	}
	
	/**
	 * Executa a busca da relação biológica
	 */
	function searchAction()
	{
		;
	}
	
	//redireciona aplicação para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
	
	/**
	 * Carrega todos os tipos de parentesco
	 */
	public static function loadTypesConsanguine()
	{
		return ConsanguineBusiness::loadAllConsaguineType(); 
	}
	
	/**
	 * Recupera as informações do form e retorna no array
	 */	
	function assembleFormToBean(SearchForm $frm)
	{		
		if(!Utils::isEmpty($frm))
		{
			//cria uma variável array de nome "bean" 
			$biological = array();
		
			if(!Utils::isEmpty($frm->getIdPerson()))
			{
				//adiciona uma variável - idPerson da pessoa do perfilselecionado - do form no array "biological"
				$biological[CSG_ID_PERSON_FROM] = $frm->getIdPerson();
			}
			
			if(!Utils::isEmpty($frm->getIdkinship()))
			{
				//adiciona uma variável - idKinship - do form no array "biological"
				$biological[CSG_ID_CONSANGUINE_TYPE] = $frm->getIdKinship();
			}
			
			if(!Utils::isEmpty($frm->getIdParent()))
			{
				//adiciona uma variável - idParent : pessoa buscada - do form no array "biological"
				$biological[CSG_ID_PERSON_TO] = $frm->getIdParent();
			}
			
			return $biological;
		}
		return null;
	}
}