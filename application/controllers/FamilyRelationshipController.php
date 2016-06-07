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
 * Jefferson Barros Lima  - W3S		   				05/05/2008	                       Create file 
 * 
 */
require_once('BasicController.php');

class FamilyRelationshipController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('FamilyRelationship');
		parent::setControllerHelp('FamilyRelationship');
		
		Zend_Loader::loadClass('SearchForm');
		Zend_Loader::loadClass('FamilyBusiness');
		Zend_Loader::loadClass('Family');
		Zend_Loader::loadClass('FamilyId');
		Zend_Loader::loadClass('SearchValidator');
		Zend_Loader::loadClass('Constants');
		Zend_Loader::loadClass('SearchBusiness');
		Zend_Loader::loadClass('MetaPhoneClass');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('KinshipType');
		Zend_Loader::loadClass('RepresentativeBusiness');
		Zend_Loader::loadClass('Representative');
		
		$this->view->documentOptions 	= Utils::documentOptions();
		
		$frm = new SearchForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
	}
	
	/**
	 * Exibe formul�rio para criar rela��o familiar
	 */
	function newAction()
	{
		;
	}
	
	/**
	 * Exibe formul�rio para a edi�ao da rela��o familiar
	 */
	function viewAction()
	{	
		//carrega os tipos de rela��o familiar
		$this->view->kinship = self::loadTypesKinship();
	}
		
	/**
	 * Salva uma nova rela��o familiar
	 */
	function addAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = SearchValidator::validatePersonId($this->view->form);
		SearchValidator::validateParentId($this->view->form, $errorMessages);
		SearchValidator::validateFamilyId($this->view->form, $errorMessages);
		SearchValidator::validateKinshipId($this->view->form, $errorMessages);
		SearchValidator::validateSexKinshipId($this->view->form, $errorMessages);
		SearchValidator::validateFamilyRelationship($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//Retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//converte vari�veis do form para objeto do tipo "bean" 
		$bean = $this->assembleFormToBean($this->view->form);
		
		//persiste as informa��es referentes a familia na base de dados
		FamilyBusiness::assembleObjectFamily($bean);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(FAMILYRELATIONSHIP_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
	}
	
	/**
	 * Salva rela��o familiar
	 */
	function editAction()
	{
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = SearchValidator::validatePersonId($this->view->form);
		SearchValidator::validateParentId($this->view->form, $errorMessages);
		SearchValidator::validateFamilyId($this->view->form, $errorMessages);
		SearchValidator::validateKinshipId($this->view->form, $errorMessages);
		SearchValidator::validateSexKinshipId($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//carrega os tipos de rela��o familiar
			$this->view->kinship = self::loadTypesKinship();
			
			//Retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//converte vari�veis do form para objeto do tipo "bean" 
		$bean = $this->assembleFormToBean($this->view->form);
		
		//persiste as informa��es referentes a educa��o na base de dados
		FamilyBusiness::assembleObjectFamily($bean);
		
		//redireciona fluxo da aplica��o para p�gina de sucesso
		$this->_redirect(FAMILYRELATIONSHIP_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
	}
	
	/**
	 * Persiste informa��es de um representante legal
	 */
	function isRepresentativeAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = SearchValidator::validatePersonId($this->view->form);
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			$alertAge = SearchValidator::validateAgeRepresentative($this->view->form);
			if(sizeof($alertAge) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->alertAge = $alertAge;
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			$alertPersonIsRepresentative = SearchValidator::validatePersonIsRepresentative($this->view->form);
			if(sizeof($alertPersonIsRepresentative) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->alertPersonIsRepresentative = $alertPersonIsRepresentative;
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			$alertHasRepresentative = SearchValidator::validateFamilyHasRepresentative($this->view->form);
			if(sizeof($alertHasRepresentative) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->alertHasRepresentative = $alertHasRepresentative;
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//persiste as informa��es de representante legal
			FamilyBusiness::saveRepresentative($this->view->form->getIdPerson(), $this->view->form->getIdKinship());
			
			//carrega os tipos de rela��o familiar
			$this->view->kinship = self::loadTypesKinship();
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(FAMILYRELATIONSHIP_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Persiste altera��es do representante legal de uma fam�lia
	 */
	function confirmRepresentativeAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = SearchValidator::validatePersonId($this->view->form);
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//persiste as informa��es de representante legal
			FamilyBusiness::saveRepresentative($this->view->form->getIdPerson(), $this->view->form->getIdKinship());
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(FAMILYRELATIONSHIP_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Exibe formul�rio de busca
	 */
	function indexAction()
	{	
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = SearchValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//Retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//carrega as informa��es de rela��o familiar referente a uma pessoa
		$this->view->relationshipByPerson = FamilyBusiness::loadFamilyRelationship($this->view->form->getIdPerson());
		
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
	 * Popula o container
	 */
	function containerAction()
	{
		$this->indexAction();
	}
	
	/**
	 * Executa a busca da rela��o familiar
	 */
	function searchAction()
	{
		//carrega os tipos de rela��o familiar
		$this->view->kinship = self::loadTypesKinship();
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
			
		// verifica se a busca cont�m um n�mero de registro maior que o configurado
		$config = Zend_Registry::get(CONFIG);				
		if(count($resPerson) > $config->search->document->max->size)
		{
			$errorMessages[SearchForm::prsName()][] = array($this->view->controller->search->error->size);
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		$this->view->resultSearchByName = $resPerson;
		
		$this->view->kinship = self::loadTypesKinship();
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
				
		// recupera informa��es do form
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
					
			// busca por cart�o do sus
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
			
			// verifica se a busca cont�m um n�mero de registro maior que o configurado
			$config = Zend_Registry::get(CONFIG);				
			if(count($resDoc) > $config->search->document->max->size)
			{
				$errorMessages[SearchForm::docNumber()][] = array($this->view->controller->search->error->size);
				$this->view->errorMessages = $errorMessages;
				return;
			}
			
			$this->view->resultSearchByDocument = $resDoc;
			
			$this->view->kinship = self::loadTypesKinship();
		}	
	}
	
	/**
	 * Exclui um relacionamento familiar cadastrado
	 */
	function dropAction()
	{
		$idPerson	= $this->view->form->getIdPerson();
		$idParent	= $this->view->form->getIdParent();
		
		if($idPerson && $idParent && $idPerson == $idParent)
		{
			//valida as informa��es vindas do form
			$errorMessages = SearchValidator::validateParentId($this->view->form);
			SearchValidator::validateFamilyId($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				$this->view->errorMessages = $errorMessages;
				
				return;
			}
			
			//persiste as informa��es referentes a educa��o na base de dados
			FamilyBusiness::drop($this->view->form->getIdFamily(), $this->view->form->getIdParent());
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(FAMILYRELATIONSHIP_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
		else
		{
			trigger_error(BasicBusiness::getLabelResources()->notDrop->fail, E_USER_ERROR);
		}
	}
	
	
	/**
	 * Exibe a tela de confirma��o de exclus�o de uma rela��o familiar
	 * 
	 */
	function confirmAction()
	{	
		//action a ser chamada caso o usu�rio confirma a exclus�o 
		$this->view->action = DEFAULT_DROP_ACTION;
		
		//valida as informa��es vindas do form
		$errorMessages = SearchValidator::validateParentId($this->view->form);
		SearchValidator::validateFamilyId($this->view->form, $errorMessages);
		SearchValidator::validateNamePerson($this->view->form, $errorMessages);
		SearchValidator::validatePersonId($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}		
		
		$isRepresentative = Utils::verifyPersonIsRepresentativeAndMembers($this->view->form->getIdPerson(), $this->view->form->getIdFamily());
		if($isRepresentative === FALSE)
		{	
			$this->view->notDropRelationWhileIsRepresentative = BasicBusiness::getLabelResources()->notDropRelationWhileIsRepresentative;
			return;
		}
		else
		{
			$objectRepresentative = RepresentativeBusiness::loadRepresentative($this->view->form->getIdFamily(), null, null);
			$idPersonRepresentative = $objectRepresentative->current()->{REP_ID_PERSON};
			
			if($idPersonRepresentative && $idPersonRepresentative != null)
			{
				if($idPersonRepresentative == $this->view->form->getIdPerson())
				{	
					$this->view->isRepresentative = BasicBusiness::getLabelResources()->messageDropRelationWhileIsRepresentative;
				}
			}
		}
	}
	
	//redireciona aplica��o para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
	
	/**
	 * Carrega todos os tipos de parentesco
	 */
	public static function loadTypesKinship()
	{
		return FamilyBusiness::loadAllKinshipType(); 
	}
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */	
	function assembleFormToBean(SearchForm $frm)
	{		
		if(!Utils::isEmpty($frm))
		{
			//cria uma vari�vel array de nome "bean" 
			$family = array();
		
			if(!Utils::isEmpty($frm->getIdPerson()))
			{
				//adiciona uma vari�vel - idPerson - do form no array "family"
				$family[FAM_ID_PERSON] = $frm->getIdPerson();
			}
			
			if(!Utils::isEmpty($frm->getIdFamily()))
			{
				//adiciona uma vari�vel - idFamily - do form no array "family"
				$family[FAM_ID_FAMILY] = $frm->getIdFamily();
			}
			
			if(!Utils::isEmpty($frm->getIdkinship()))
			{
				//adiciona uma vari�vel - idKinship - do form no array "family"
				$family[FAM_ID_KINSHIP] = $frm->getIdKinship();
			}
			
			if(!Utils::isEmpty($frm->getIdParent()))
			{
				//adiciona uma vari�vel - idParent - do form no array "family"
				$family[FAM_ID_PARENT] = $frm->getIdParent();
			}
			
			return $family;
		}
		return null;
	}
}