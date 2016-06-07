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
 * Lucas dos Santos Borges Corrêa  - W3S		   	05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class SearchController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Search');
		parent::setControllerHelp('Search');
		
		Zend_Loader::loadClass(CLS_DOCUMENT);
		Zend_Loader::loadClass('Constants');
		Zend_Loader::loadClass(CLS_CTPS);
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass(CLS_FAMILY);
		Zend_Loader::loadClass(CLS_REGION);
		Zend_Loader::loadClass('SearchForm');
		Zend_Loader::loadClass('SearchValidator');
		Zend_Loader::loadClass('SearchBusiness');
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('NeighborhoodBusiness');
		Zend_Loader::loadClass('RegionBusiness');
		Zend_Loader::loadClass('PersonForm');
		Zend_Loader::loadClass('MetaPhoneClass');
		Zend_Loader::loadClass('Utils');
		
		$frm				= new SearchForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
		
		$this->view->documentOptions 	= Utils::documentOptions();
		$this->view->region				= RegionBusiness::loadAll();
	}
	
	/**
	 * Exibe página para efetuar busca
	 */
	function indexAction()
	{
		;
	}
	
	/**
	 * Realiza a busca conforme nome passado
	 */
	function searchByNameAction()
	{
		$errorMessages = SearchValidator::validateSearchPerson($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		// busca pessoa pelas informações passadas
		if($this->view->form)
			$resPerson = SearchBusiness::searchPerson($this->view->form);
				
		// verifica se a busca contém um número de registro maior que o configurado
		$config = Zend_Registry::get(CONFIG);				
		if(count($resPerson) > $config->search->document->max->size){
			$errorMessages[SearchForm::prsName()][] = array($this->view->controller->search->error->size);
			$this->view->errorMessages = $errorMessages;
			return;
		}
				
		// divide dentro de uma array as pessoas pelo primeiro nome
		// resgata a familia de cada pessoa
		foreach($resPerson as $prs){
				$firstName = split(" ",$prs->{PRS_NAME});
				$people[$firstName[0]][] = $prs;
				$families[] = $prs->findDependentRowset(CLS_FAMILY);
		}
		
		// busca as pessoas de cada familia
		foreach($families as $k=>$v)
			foreach($v as $fam)
				$family[$fam->{FAM_ID_PERSON}] = SearchBusiness::searchPeopleOfFamily($fam->{FAM_ID_FAMILY});
		
		// coloca array em ordem alfabetica
		ksort($people);
		
		// seta para o ser usada no form
		$this->view->people = $people;
		$this->view->families = $family;
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

		// busca por rg
		if($docType == DOC_RG_NUMBER)
			if(count($docNumber) == 9)
				$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
			else
				$resDoc= SearchBusiness::searchDocumentLike($docType, $docNumber);
		
		// busca por cpf
		if($docType == DOC_CPF)
			if(count($docNumber) == 11)
				$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
			else
				$resDoc = SearchBusiness::searchDocumentLike($docType, $docNumber);
		
		// busca por ctps
		if($docType == CTS_NUMBER)
			if(count($docNumber) == 5)
				$resDoc = SearchBusiness::searchCtpsEqual($docType, $docNumber);
			else
				$resDoc = SearchBusiness::searchCtpsLike($docType, $docNumber);
				
		// busca por titulo de eleitor
		if($docType == DOC_TITLE_NUMBER)
			if(count($docNumber) == 10)
				$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
			else
				$resDoc = SearchBusiness::searchDocumentLike($docType, $docNumber);
				
		// busca por cartão do sus
		if($docType == DOC_SUS_CARD)
			if(count($docNumber) == 11)
				$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
			else
				$resDoc = SearchBusiness::searchDocumentLike($docType, $docNumber);
				
		// busca por nis
		if($docType == DOC_NIS)
			if(count($docNumber) == 11)
				$resDoc = SearchBusiness::searchDocumentEqual($docType, $docNumber);
			else
				$resDoc = SearchBusiness::searchDocumentLike($docType, $docNumber);
				
		// busca por todos
		if($docType == Constants::ALL)
			$resDoc = SearchBusiness::searchAllDocuments($docNumber);
		
		// verifica se a busca contém um número de registro maior que o configurado
		$config = Zend_Registry::get(CONFIG);				
		if(count($resDoc) > $config->search->document->max->size){
			$errorMessages[SearchForm::docNumber()][] = array($this->view->controller->search->error->size);
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		// busca as pessoas
		foreach($resDoc as $res)
			$person[] = PersonBusiness::load($res->{DOC_ID_PERSON});
		
		// divide dentro de uma array as pessoas pelo primeiro nome
		// resgata a familia de cada pessoa
		foreach($person as $prs)
			foreach($prs as $p){
				$firstName = split(" ",$p->{PRS_NAME});
				$people[$firstName[0]][] = $p;
				$families[] = $p->findDependentRowset(CLS_FAMILY);
			}
		
		// busca as pessoas de cada familia
		foreach($families as $k=>$v)
			foreach($v as $fam)
				$family[$fam->{FAM_ID_PERSON}] = SearchBusiness::searchPeopleOfFamily($fam->{FAM_ID_FAMILY});
		
		// coloca array em ordem alfabetica
		ksort($people);
		
		// seta para o ser usada no form
		$this->view->people = $people;
		$this->view->families = $family;		
	}
}