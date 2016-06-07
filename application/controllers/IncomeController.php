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

class IncomeController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Income');
		parent::setControllerHelp('Income');		
		
		Zend_Loader::loadClass('IncomeForm');
		Zend_Loader::loadClass('IncomeBusiness');
		Zend_Loader::loadClass('EmploymentBusiness');
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('HistoryBusiness');
		Zend_Loader::loadClass('TelephoneBusiness');
		Zend_Loader::loadClass('IncomeValidator');
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass(CLS_INCOME);
		Zend_Loader::loadClass(CLS_PERSONCHANGEHISTORY);
		Zend_Loader::loadClass(CLS_EMPLOYMENT);
		Zend_Loader::loadClass(CLS_TELEPHONENUMBER);
		Zend_Loader::loadClass(CLS_EMPLOYMENTPHONE);
		
		$frm = new IncomeForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
		
		$this->view->incomeType 		= IncomeBusiness::loadIncomeType();
		$this->view->employmentStatus	= IncomeBusiness::loadEmploymentStatus();
		$this->view->phoneType			= TelephoneBusiness::loadAllType();
		$this->view->notEmployment		= Zend_Registry::get(CONFIG)->status->employment;
	}
	
	/**
	 * Exibe as informações de renda
	 */
	function indexAction()
	{
		$id = $this->_request->getParam($this->view->form->personId());
		$this->view->form->setPersonId($id);
		
		$errorMessages = IncomeValidator::validatePersonId($this->view->form);		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		$this->view->person = PersonBusiness::load($this->view->form->getPersonId());		
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
	 * Exibe formulário para edição da renda
	 */
	function viewAction()
	{
		$id = $this->_request->getParam($this->view->form->personId());
		$this->view->form->setPersonId($id);
		
		$errorMessages = IncomeValidator::validatePersonId($this->view->form);		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		$person = PersonBusiness::load($this->view->form->getPersonId());
		
		IncomeForm::assemblePersonToForm($person);	
		
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
	 * Salva renda (edição)
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
			if($this->view->form->getIdEmploymentType() == Zend_Registry::get(CONFIG)->status->employment)
			{
				$array = $this->view->form->getValueSalary();
				$array[1] = null;
				$this->view->form->setValueSalary($array);
				$errorMessages = IncomeValidator::validatePersonId($this->view->form);
				IncomeValidator::validateIdEmploymentType($this->view->form, $errorMessages);
			}
			else
			{				
				$errorMessages = IncomeValidator::validateIncomeEdit($this->view->form);	
			}
						
			if(sizeof($errorMessages) > 0)
			{		
				$this->view->errorMessages = $errorMessages;		
				return;
			}
			
			$income[ICM_ID_PERSON] = $this->view->form->getPersonId();
			
			foreach($this->view->form->getValueSalary() as $k=>$v)
			{
				$income[ICM_ID_INCOME] = $k;
				
				if(($this->view->form->getIdEmploymentType() == Zend_Registry::get(CONFIG)->status->employment) && ($k == 1))
				{
					$income[ICM_VALUE] = 0;
					$this->view->form->setAdrIdAddress(null);
					$this->view->form->setAdrComplement(null);
					$this->view->form->setAdrReference(null);
					$this->view->form->setAdrNumber(null);
					$this->view->form->setCompanyName(null);
					$this->view->form->setIdPhone(null);
					$this->view->form->setPhoneType(null);
					$this->view->form->setPhoneNumber(null);
					$this->view->form->setPhoneCodeArea(null);
					$this->view->form->setOccupation(null);
					$this->view->form->setStartDate(null);
					$this->view->form->setEndDate(null);
				}
				else		
				{				
					$income[ICM_VALUE] = $v;
				}
				
				$id = IncomeBusiness::save($income, $this->_request->getParam('controller'));				
				
				if($income[ICM_ID_INCOME] == 1)
				{					
					if($this->view->form->getIdEmploymentType() && $id)
					{						
						$employment = IncomeForm::assembleFormToEmployment($this->view->form);						
						$employment[EMP_ID_INCOME] = $id;					
						
						if(empty($employment[EMP_ID_ADDRESS]))
							$employment[EMP_ID_ADDRESS] = null;
						
						EmploymentBusiness::save($employment);
												
						if($this->view->form->getIdEmploymentType() != Zend_Registry::get(CONFIG)->status->employment)
						{							
							if($this->view->form->getIdCompany())
							{
								$errorMessages = IncomeValidator::validateIdCompanyPhone($this->view->form);
								if(sizeof($errorMessages) > 0)
								{
									$this->view->errorMessages = $errorMessages;
									return;
								}
								$telephone = IncomeForm::assembleFormToTelephone($this->view->form);								
								self::telephoneManager($telephone);
							}
						}
					}
				}
				$income[ICM_VALUE] = null;
			}
			$this->_redirect(INCOME_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->personId().'/'.$this->view->form->getPersonId());
		}
	}
	
	/**
	 * Salva dados do telefone
	 */
	function telephoneAction()
	{
		$errorMessages = IncomeValidator::validatePhone($this->view->form);
		IncomeValidator::validateIdCompanyPhone($this->view->form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		$telephone = IncomeForm::assembleFormToTelephone($this->view->form);
		self::telephoneManager($telephone);
	}
	
	function telephoneManager($telephone)
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{			
			if(!is_null($telephone)){
				$empPhone[EMP_ID_EMPLOYMENT] = $this->view->form->getIdCompany();			
				foreach($telephone as $tel){
					if($tel[TNB_DDD] && $tel[TNB_NUMBER])
						$id = TelephoneBusiness::save($tel);
						
					if($id){
						$empPhone[EMT_ID_TELEPHONE] = $id;
						EmploymentBusiness::saveEmploymentPhone($empPhone);
						
						$id = null;
					}
					else{
						if($tel[TNB_ID_TELEPHONE_NUMBER]){
							$empPhone[EMT_ID_TELEPHONE] = $tel[TNB_ID_TELEPHONE_NUMBER];
							EmploymentBusiness::saveEmploymentPhone($empPhone, $delete=true);
						}
					}				
				}
			}
		}
	}
	
	function successAction()
	{
		$id = $this->_request->getParam($this->view->form->personId());
		$this->view->form->setPersonId($id);
	}
}