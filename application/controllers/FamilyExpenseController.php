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
 * Fabricio Meireles Monteiro  - W3S		   		05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class FamilyExpenseController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('FamilyExpense');
		parent::setControllerHelp('FamilyExpense');
		
		Zend_Loader::loadClass('FamilyExpenseForm');
		Zend_Loader::loadClass('FamilyExpenseValidator');
		Zend_Loader::loadClass('PersonForm');
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('ExpenseBusiness');
		Zend_Loader::loadClass('PersonValidator');
		Zend_Loader::loadClass('HistoryBusiness');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('Resource');
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass(CLS_EXPENSE);
		Zend_Loader::loadClass(CLS_EXPENSETYPE);
		Zend_Loader::loadClass(CLS_PERSONCHANGEHISTORY);
		
		$frm = new FamilyExpenseForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
		
		$this->view->expType = ExpenseBusiness::loadAll();
	}
	
	/**
	 * Exibe as informações atualmente preenchidas
	 */
	function indexAction()
	{
		//resgata parametro id do form
		$idPerson = $this->_request->getParam($this->view->form->id());
		
		$errorMessages = FamilyExpenseValidator::validateIdPerson($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		if($idPerson){
			$resPerson = PersonBusiness::load($idPerson);
			
			if($resPerson)
				foreach($resPerson as $prs)
					$famliyId = $prs->findManyToManyRowset(CLS_FAMILY_ID, CLS_FAMILY);
			if($famliyId)
				foreach($famliyId as $famId)
					$expense = $famId->findDependentRowset(CLS_EXPENSE);
			else
				$expense = null;
			
			$this->view->family = $famliyId;
			$this->view->expense = $expense;
			$this->view->form->setId($idPerson);	
		}
		
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
	 * Exibe formulário para edição de despesas familiar
	 */
	function viewAction()
	{
		$errorMessages = FamilyExpenseValidator::validateExpenseSave($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$this->view->edit = true;
	}
	
	/**
	 * Salva despesas familiar editada
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
			$errorMessages = FamilyExpenseValidator::validateExpenseSave($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{
				$this->view->errorMessages = $errorMessages;
				$this->view->edit = true;
				return;
			}
			$expense = FamilyExpenseForm::assembleFormToExpense($this->view->form);
			ExpenseBusiness::save($expense, $this->view->form->getId());
			
			$this->_redirect(FAMILYEXPENSE_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->id().'/'.$this->view->form->getId());
		}
	}
	
	
	/**
	 * Exibe formulário para inserir despesas familiar
	 */
	function newAction()
	{
		$errorMessages = FamilyExpenseValidator::validateExpenseNew($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$expense = FamilyExpenseForm::assembleFormToExpense($this->view->form);
				
		$resExpense = ExpenseBusiness::loadByFamily($expense[EXP_ID_FAMILY]);
		
		$this->view->expSelect = $resExpense;
		$this->view->edit = false;
//		$this->view->form->setIdExpenseType(null);
		$this->view->form->setIdFamily($expense[EXP_ID_FAMILY]);
	}
	
	/**
	 * Salva vona despesas familiar
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
			$errorMessages = FamilyExpenseValidator::validateExpenseSave($this->view->form);
			if(sizeof($errorMessages) > 0)
			{
				$this->view->errorMessages = $errorMessages;
//				$this->view->form->setIdExpenseType(null);
				$this->view->edit = false;
				return;
			}
			$expense = FamilyExpenseForm::assembleFormToExpense($this->view->form);
			ExpenseBusiness::save($expense, $this->view->form->getId());
			
			$this->_redirect(FAMILYEXPENSE_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->id().'/'.$this->view->form->getId());
		}
	}
	
	/**
	 * Exclui despesas familiares
	 */
	function dropAction()
	{
		$errorMessages = FamilyExpenseValidator::validateExpenseDrop($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		if(is_array($this->view->form->getIdExpenseType()))
		{
			$arrayType = $this->view->form->getIdExpenseType();
			foreach($arrayType as $type){
				$this->view->form->setIdExpenseType($type);
				$expense = FamilyExpenseForm::assembleFormToExpense($this->view->form);
				ExpenseBusiness::drop($expense);
			}
			
			$this->_redirect(FAMILYEXPENSE_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->id().'/'.$this->view->form->getId());		
		}
	}
	
	/**
	 * Confirmação de exclusão
	 */
	function confirmAction()
	{		
		$errorMessages = FamilyExpenseValidator::validateExpenseDrop($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			return;
		}
		$resPerson = PersonBusiness::load($this->view->form->getId());
		
		if($resPerson)
			foreach($resPerson as $prs)
				$famliyId = $prs->findManyToManyRowset(CLS_FAMILY_ID, CLS_FAMILY);
		if($famliyId)
			foreach($famliyId as $famId)
				$expense = $famId->findDependentRowset(CLS_EXPENSE);
		else
			$expense = null;
		
		$this->view->result = $expense;
	}
	
	function successAction()
	{
		$errorMessages = PersonValidator::validatePersonId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$id = $this->_request->getParam($this->view->form->id());
		$this->view->form->setId($id);
	}
}