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
 * Jefferson Barros Lima  - W3S		   				05/05/2008	                       Create file 
 * 
 */
require_once('BasicController.php');

class HistoryController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('History');
		parent::setControllerHelp('History');
		Zend_Loader::loadClass('HistoryForm');
		Zend_Loader::loadClass('HistoryBusiness');
		Zend_Loader::loadClass('HistoryValidator');
		Zend_Loader::loadClass('IncomeForm');
		Zend_Loader::loadClass(CLS_PERSONCHANGEHISTORY);
		
		$form = new HistoryForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
	}
	
	/**
	 * Exibe o histórico de alterações de acordo com o módulo atual
	 */
	function indexAction()
	{
		$form = $this->view->form;
		
		$errorMessages = HistoryValidator::validateResourceId($form);
		HistoryValidator::validatePersonId($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		// Recupera as informações para o critério de busca
		$data[PCH_ID_RESOURCE. ' = ?'] = $form->getResourceId();
		$data[PCH_ID_PERSON.' = ?'] = $form->getPersonId();

		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(HistoryForm::filter()))
			$page 				= $this->getRequest()->getPost(HistoryForm::page());

		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
		$total					= HistoryBusiness::count(TBL_PERSON_CHANGE_HISTORY, PCH_ID_RESOURCE,  $data);
		$history 				= HistoryBusiness::loadByQuery($data , $start, Zend_Registry::get(TPAGE));
		$this->navBar($page, $total, Zend_Registry::get(TPAGE));
		
		$this->view->history = $history;
	}
	
	function viewAction()
	{
		$form = $this->view->form;
		
		$errorMessages = HistoryValidator::validateHistoryId($form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		Zend_Loader::loadClass('TableMapping');
		Zend_Loader::loadClass('HistoryBusiness');
		// Carrega o registro da tabela temporária 
		$hist = HistoryBusiness::loadHistory($form->getHistoryId());

		if($hist && count($hist) > 0)
		{
			// Recupera o Mapa de Tabelas, classes, templates
			$map = TableMapping::getTableMap();
			
			// Seta o arquivo de resources
			parent::setControllerResources($map[$hist->{PCH_TABLE_NAME}][TableMapping::$_rn]);
			
			// Recupera o nome da classe que corresponde a tabela
			$class = $map[$hist->{PCH_TABLE_NAME}][TableMapping::$_cn];
			if($class == CLS_INCOME)
				$history[] = HistoryBusiness::loadByTableName(CLS_PERSON, $hist->{PCH_ID_PERSON});
			else
				$history = HistoryBusiness::loadByTableName($class, $hist->{PCH_ID_REFERENCE_FOREIGN});
			$this->view->$map[$hist->{PCH_TABLE_NAME}][TableMapping::$_tv] = $history;

			if($hist->{PCH_TABLE_NAME} == TBL_RESIDENCE)
			{
				$this->setViewAddress($hist, $history);
			}
			elseif($hist->{PCH_TABLE_NAME} == TBL_SOCIAL_PROGRAM)
			{
				$this->setViewSocialProgram($hist);
			}
			elseif($hist->{PCH_TABLE_NAME} == TBL_EXPENSE)
			{
				$this->setExpense($hist);
			}
			
			$this->view->templateName = $map[$hist->{PCH_TABLE_NAME}][TableMapping::$_tn];				
			$this->view->history_view = TRUE;
			$this->view->form->setPersonId($hist->{PCH_ID_PERSON});
			$this->view->resource_key = $hist->{PCH_ID_RESOURCE};
		}
	}
	/**
	 * Trata Seta informações específicas de Despesas
	 */
	public function setExpense(&$hist)
	{
		Zend_Loader::loadClass('FamilyExpenseForm');
		Zend_Loader::loadClass('PersonBusiness');

		$form = new FamilyExpenseForm();
		$form->setId($hist->{PCH_ID_PERSON});
		$this->view->form = $form;
		$resPerson = PersonBusiness::load($hist->{PCH_ID_PERSON});
		
		if($resPerson)
			foreach($resPerson as $prs)
				$famliyId = $prs->findManyToManyRowset(CLS_FAMILY_ID, CLS_FAMILY);
		if($famliyId)
			foreach($famliyId as $famId)
				$expense = $famId->findDependentRowset(CLS_EXPENSE);
		else
			$expense = null;
		
		$this->view->family = $famliyId;
	}


	/**
	 * Trata Seta informações específicas de ProgramaSocial
	 */
	public function setViewSocialProgram(&$hist)
	{
		Zend_Loader::loadClass('BenefitForm');
		$form = new BenefitForm();
		$form->setIdPerson($hist->{PCH_ID_PERSON});
		$this->view->form = $form;
	}
	
	/**
	 * Trata e Seta informações específicas de Residência e Endereço
	 */
	public function setViewAddress(&$hist, &$result)
	{
		Zend_Loader::loadClass('ResidenceForm');
		Zend_Loader::loadClass('ResidenceBusiness');
		Zend_Loader::loadClass('TelephoneBusiness');
		Zend_Loader::loadClass('PersonBusiness');

		$form = new ResidenceForm();
		$form->assembleResidenceToForm($result);
		$form->setId($hist->{PCH_ID_PERSON});
		$this->view->form = $form;

		$this->view->locality 		= ResidenceBusiness::loadLocality();
		$this->view->morada 		= ResidenceBusiness::loadMorada();
		$this->view->status			= ResidenceBusiness::loadStatus();
		$this->view->building		= ResidenceBusiness::loadBuilding();
		$this->view->supply 		= ResidenceBusiness::loadSupply();
		$this->view->water 			= ResidenceBusiness::loadWater();
		$this->view->illumination 	= ResidenceBusiness::loadIllumination();
		$this->view->sanitary 		= ResidenceBusiness::loadSanitary();
		$this->view->trash 			= ResidenceBusiness::loadTrash();
		$this->view->phoneType		= TelephoneBusiness::loadAllType();
		
		$this->view->address = $result->findParentRow(CLS_ADDRESS);
		$person = PersonBusiness::load($hist->{PCH_ID_PERSON})->current();
		$this->view->phone = $person->findDependentRowset(CLS_PERSONTELEPHONE);
	}
}