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
 * Lucas dos Santos Borges Corrêa  - W3S		    05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class AdditionalInformationController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('AdditionalInformation');
		parent::setControllerHelp('AdditionalInformation');
		
		Zend_Loader::loadClass('AdditionalInformationForm');
		Zend_Loader::loadClass('AdditionalInformationValidator');
		Zend_Loader::loadClass('AdditionalInformationBusiness');
		
		$frm = new AdditionalInformationForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
		
		if($this->view->form->getTableName() == TBL_PROGRAM_TYPE)
		{
			$this->view->targetMarket = AdditionalInformationBusiness::listTableTargetMarket();
		}
		
		if($this->view->form->getTableName() == TBL_SOCIAL_PROGRAM_TYPE)
		{
			$this->view->socialProgramOrigen = AdditionalInformationBusiness::listTableSocialProgramOrigenType();
		}
		
		$this->view->tableName = $this->listTables();
	}
	
	/**
	 * Exibe formulário e lista todas as tabelas do banco de dados
	 */
	function indexAction()
	{
		;
	}
	
	/**
	 * Salva um novo campo
	 */
	function addAction()
	{		
		$errorMessages = AdditionalInformationValidator::validateAdditionalInformationforAdd($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			if($this->view->form->getTableName())
				$this->view->result = AdditionalInformationBusiness::loadAll($this->view->form->getTableName());
			
			if($this->view->form->getTableName() && $this->view->form->getTableValue() && $this->view->form->getTableId())
				AdditionalInformationForm::assembleAdditionalInformationToForm(	$this->view->form->getTableName(), $this->view->form->getTableValue(),
																				$this->view->form->getTableId());
			return;
		}
		
		AdditionalInformationBusiness::save($this->view->form->getTableName(),$this->view->form->getId(),
											$this->view->form->getValue(), $this->view->form->getStatus());
		

		$this->_redirect(ADDITIONALINFORMATION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.AdditionalInformationForm::tableName().'/'.$this->view->form->getTableName());
		
	}
	
	/**
	 * Exibe a tela de confirmação de exclusão de um campo
	 * 
	 */
	function confirmAction()
	{
		$errorMessages = AdditionalInformationValidator::validateAdditionalInformationforDrop($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$result = AdditionalInformationBusiness::load($this->view->form->getTableName(), $this->view->form->getId());
		
		$this->view->result = $result;
		
		AdditionalInformationForm::assembleAdditionalInformationToForm(	$this->view->form->getTableName(), $this->view->form->getTableValue(),
																		$this->view->form->getTableId());
	}
	
	/**
	 * Exclui um campo cadastrado
	 */
	function dropAction()
	{
		$errorMessages = AdditionalInformationValidator::validateAdditionalInformationforDrop($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			$this->view->result = AdditionalInformationBusiness::loadAll($this->view->form->getTableName());
			AdditionalInformationForm::assembleAdditionalInformationToForm(	$this->view->form->getTableName(), $this->view->form->getTableValue(),
																			$this->view->form->getTableId());
			return;
		}
		AdditionalInformationBusiness::drop($this->view->form->getTableName(),$this->view->form->getId());
		

		$this->_redirect(ADDITIONALINFORMATION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.AdditionalInformationForm::tableName().'/'.$this->view->form->getTableName());
	}

	/**
	 * Salva um campo editado
	 */
	function editAction()
	{
		$errorMessages = AdditionalInformationValidator::validateAdditionalInformationforEdit($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			$this->view->result = AdditionalInformationBusiness::loadAll($this->view->form->getTableName());
			AdditionalInformationForm::assembleAdditionalInformationToForm(	$this->view->form->getTableName(), $this->view->form->getTableValue(),
																			$this->view->form->getTableId());
			return;
		}
		
		AdditionalInformationBusiness::save($this->view->form->getTableName(),$this->view->form->getId(),
											$this->view->form->getValue(), $this->view->form->getStatus());
		

		$this->_redirect(ADDITIONALINFORMATION_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.AdditionalInformationForm::tableName().'/'.$this->view->form->getTableName());

	}
	
	/**
	 * Busca os dados de uma determinada tabela
	 * 
	 */
	function searchAction()
	{
		$errorMessages = AdditionalInformationValidator::validateAdditionalInformationTable($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		$order = self::listOrderTables();
		
		$result = AdditionalInformationBusiness::loadAll($this->view->form->getTableName(), $order[$this->view->form->getTableName()]);
		$this->view->result = $result;
		
		$columns = AdditionalInformationBusiness::getColumnNameByClass($this->view->form->getTableName());
		$fieldId = $columns[0];
		$fieldValue = $columns[1];
		
		AdditionalInformationForm::assembleAdditionalInformationToForm($this->view->form->getTableName(), $fieldValue, $fieldId);
	}
	
	//redireciona aplicação para tela de sucesso do respectivo controller
	function successAction()
	{
		$tableName = $this->_request->getParam($this->view->form->tableName());
		$this->view->form->setTableName($tableName);
	}
	
	/**
	 * Monta array com nomes das tabelas
	 */	
	function listTables()
	{
		$tableName = array(
			TBL_FRAMEWORK_HEALTH_TYPE => $this->view->controller->additional->table->health,
			TBL_SCHOOL_TYPE => $this->view->controller->additional->table->school,
			TBL_SCHOOL_YEAR_TYPE => $this->view->controller->additional->table->serie,
			TBL_DEGREE_TYPE => $this->view->controller->additional->table->degree,
			TBL_PERIOD_TYPE => $this->view->controller->additional->table->period,
			TBL_EXPENSE_TYPE => $this->view->controller->additional->table->expense,
			TBL_KINSHIP_TYPE => $this->view->controller->additional->table->kinship,
			TBL_BUILDING_TYPE => $this->view->controller->additional->table->building,
			TBL_MORADA_TYPE => $this->view->controller->additional->table->residence,
			TBL_LOCALITY_TYPE => $this->view->controller->additional->table->locality,
			TBL_STATUS_TYPE => $this->view->controller->additional->table->situation,
			TBL_WATER_TYPE => $this->view->controller->additional->table->water,
			TBL_ILLUMINATION_TYPE => $this->view->controller->additional->table->illumination,
			TBL_SANITARY_TYPE => $this->view->controller->additional->table->sanitary,
			TBL_TRASH_TYPE => $this->view->controller->additional->table->trash,
			TBL_SUPPLY_TYPE => $this->view->controller->additional->table->supply,
			TBL_CONSANGUINE_TYPE => $this->view->controller->additional->table->consanguine,
			TBL_SOCIAL_PROGRAM_ORIGIN_TYPE => $this->view->controller->additional->table->socialProgramOrigen,
			TBL_DEFICIENCY_TYPE => $this->view->controller->additional->table->deficiency,
			TBL_INCOME_TYPE => $this->view->controller->additional->table->income,
			TBL_EMPLOYMENT_STATUS_TYPE => $this->view->controller->additional->table->employmentSituation,
			TBL_ASSISTANCE_BENEFIT_TYPE => $this->view->controller->additional->table->benefit,
			TBL_TELEPHONE_TYPE => $this->view->controller->additional->table->phone,
			TBL_ADDRESS_TYPE => $this->view->controller->additional->table->address,
			TBL_COVERAGE_HEALTH_TYPE => $this->view->controller->additional->table->healthCoverage,
			TBL_ENTITY_AREA_TYPE => $this->view->controller->additional->table->area,
			TBL_ENTITY_CLASSIFICATION_TYPE => $this->view->controller->additional->table->classification,
			TBL_ENTITY_GROUP_TYPE => $this->view->controller->additional->table->group,
			TBL_TARGET_MARKET => $this->view->controller->additional->table->market,
			TBL_PROGRAM_TYPE => $this->view->controller->additional->table->program,
			TBL_SOCIAL_PROGRAM_TYPE => $this->view->controller->additional->table->socialProgram 
		);
		
		asort($tableName);
		
		return $tableName;
	}
	
	/**
	 * Array contendo a coluna que deve ser usada para ordenar
	 */
	function listOrderTables()
	{
		Zend_Loader::loadClass(CLS_FRAMEWORKHEALTHTYPE);
		Zend_Loader::loadClass(CLS_SCHOOLTYPE);
		Zend_Loader::loadClass(CLS_SCHOOLYEARTYPE);
		Zend_Loader::loadClass(CLS_DEGREETYPE);
		Zend_Loader::loadClass(CLS_PERIODTYPE);
		Zend_Loader::loadClass(CLS_EXPENSETYPE);
		Zend_Loader::loadClass(CLS_KINSHIPTYPE);
		Zend_Loader::loadClass(CLS_BUILDINGTYPE);
		Zend_Loader::loadClass(CLS_MORADATYPE);
		Zend_Loader::loadClass(CLS_LOCALITYTYPE);
		Zend_Loader::loadClass(CLS_STATUSTYPE);
		Zend_Loader::loadClass(CLS_WATERTYPE);
		Zend_Loader::loadClass(CLS_ILLUMINATIONTYPE);
		Zend_Loader::loadClass(CLS_SANITARYTYPE);
		Zend_Loader::loadClass(CLS_TRASHTYPE);
		Zend_Loader::loadClass(CLS_SUPPLYTYPE);
		Zend_Loader::loadClass(CLS_CONSANGUINETYPE);
		Zend_Loader::loadClass(CLS_SOCIALPROGRAMORIGIN);
		Zend_Loader::loadClass(CLS_DEFICIENCYTYPE);
		Zend_Loader::loadClass(CLS_INCOMETYPE);
		Zend_Loader::loadClass(CLS_EMPLOYMENTSTATUS);
		Zend_Loader::loadClass(CLS_ASSISTANCEBENEFITTYPE);
		Zend_Loader::loadClass(CLS_TELEPHONETYPE);
		Zend_Loader::loadClass(CLS_ADDRESSTYPE);
		Zend_Loader::loadClass(CLS_COVERAGEHEALTHTYPE);
		Zend_Loader::loadClass(CLS_ENTITYAREATYPE);
		Zend_Loader::loadClass(CLS_ENTITYCLASSIFICATIONTYPE);
		Zend_Loader::loadClass(CLS_ENTITYGROUPTYPE);
		Zend_Loader::loadClass(CLS_TARGETMARKET);
		Zend_Loader::loadClass(CLS_PROGRAMTYPE);
		Zend_Loader::loadClass(CLS_SOCIALPROGRAMTYPE);
		
		$tableName = array(
			TBL_FRAMEWORK_HEALTH_TYPE => FHT_FRAMEWORK_HEALTH,
			TBL_SCHOOL_TYPE => SCT_SCHOOL_TYPE,
			TBL_SCHOOL_YEAR_TYPE => SYT_SCHOOL_YEAR,
			TBL_DEGREE_TYPE => DTP_DEGREE,
			TBL_PERIOD_TYPE => PTY_PERIOD,
			TBL_EXPENSE_TYPE => EXT_EXPENSE,
			TBL_KINSHIP_TYPE => KST_KINSHIP,
			TBL_BUILDING_TYPE => BTP_BUILDING,
			TBL_MORADA_TYPE => MRT_MORADA,
			TBL_LOCALITY_TYPE => LTP_PLACE,
			TBL_STATUS_TYPE => STT_STATUS_TYPE,
			TBL_WATER_TYPE => WTP_WATER,
			TBL_ILLUMINATION_TYPE => ITP_ILLUMINATION,
			TBL_SANITARY_TYPE => SNT_SANITARY,
			TBL_TRASH_TYPE => TST_TRASH,
			TBL_SUPPLY_TYPE => SPT_SUPPLY,
			TBL_CONSANGUINE_TYPE => CTP_DESCRIPTION,
			TBL_SOCIAL_PROGRAM_ORIGIN_TYPE => SPO_ORIGIN,
			TBL_DEFICIENCY_TYPE => DFT_NAME,
			TBL_INCOME_TYPE => ICT_INCOME,
			TBL_EMPLOYMENT_STATUS_TYPE => EMS_EMPLOYMENT_STATUS,
			TBL_ASSISTANCE_BENEFIT_TYPE => ABT_DESCRIPTION,
			TBL_TELEPHONE_TYPE => TTP_TELEPHONE,
			TBL_ADDRESS_TYPE => ADT_DESCRIPTION,
			TBL_COVERAGE_HEALTH_TYPE => CHT_COVERAGE_HEALTH,
			TBL_ENTITY_AREA_TYPE => EAT_ENTITY_AREA,
			TBL_ENTITY_CLASSIFICATION_TYPE => ECT_ENTITY_CLASSIFICATION,
			TBL_ENTITY_GROUP_TYPE => EGT_ENTITY_GROUP,
			TBL_TARGET_MARKET => TMK_TARGET_MARKET,
			TBL_PROGRAM_TYPE => PGT_PROGRAM_TYPE,
			TBL_SOCIAL_PROGRAM_TYPE => SCP_BENEFIT  
		);
				
		return $tableName;
	}
}