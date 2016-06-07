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

class HealthController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Health');
		parent::setControllerHelp('Health');
		
		Zend_Loader::loadClass('HealthForm');
		Zend_Loader::loadClass('Health');
		Zend_Loader::loadClass('FrameworkHealth');
		Zend_Loader::loadClass('HealthBusiness');
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('HistoryBusiness');
		Zend_Loader::loadClass('HealthValidator');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('Constants');
		Zend_Loader::loadClass('Resource');
		Zend_Loader::loadClass('PersonChangeHistory');
		
		$frm = new HealthForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
	}
	
	/**
	 * Exibe as informações atualmente preenchidas
	 */
	function indexAction()
	{
		$this->view->form->setIdPerson($this->view->form->getPersonId());
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = HealthValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			//Retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		//carrega as informações de saúde referente a uma pessoa
		$this->view->healthByPerson = HealthBusiness::loadHealthByPerson($this->view->form->getIdPerson());
		
		//carrega as informações de gestação referente a uma pessoa
		$this->view->pregnancyByPerson = HealthBusiness::loadPregnancyByPerson($this->view->form->getIdPerson());
		
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
	 * Exibe formulário para edição de um usuário
	 */
	function viewAction()
	{
		//valida as informações inseridas no form pelo usuário  
		$errorMessages = HealthValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			
			//Retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		//carrega todos os "tipo quadro saúde"
		$this->view->healthTypes = HealthBusiness::loadAllHealthTypes();
		
		//carrega as informações de saúde referente a uma pessoa
		$this->view->healthByPerson = HealthBusiness::loadHealthByPerson($this->view->form->getIdPerson());
//		desc($this->view->healthByPerson);die();

		$this->view->form->setUserDrug($this->view->healthByPerson->{HLT_DRUG_USER});
		$this->view->form->setVaccine($this->view->healthByPerson->{HLT_VACCINE});
		if($this->view->healthByPerson->{HLT_HEALTH_PLAN})
		{
			if($this->view->healthByPerson->{HLT_HEALTH_PLAN} == "Não")
			{
				$this->view->form->setCheckedHealthPlan(2);
			}
			else
			{
				$this->view->form->setCheckedHealthPlan(1);
			}
		}
		else
		{
			$this->view->form->setCheckedHealthPlan(0);
		}
		
		
//		desc($this->view->form);die();
		
		//carrega as informações de gestação referente a uma pessoa
		$this->view->pregnancyByPerson = HealthBusiness::loadPregnancyByPerson($this->view->form->getIdPerson());
		
		$this->view->form->setMet($this->view->pregnancyByPerson->{PRG_MET});
		
		//objeto person
		$this->view->person = PersonBusiness::loadPerson($this->view->form->getIdPerson());
	
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
			$errorMessages = HealthValidator::validateHealth($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{	
				//carrega variável com a(s) mensagem(ens) de erro de validação
				$this->view->errorMessages = $errorMessages;
				
				//objeto person
				$this->view->person = PersonBusiness::loadPerson($this->view->form->getIdPerson());
				
				//carrega todos os "tipo quadro saúde"
				$this->view->healthTypes = HealthBusiness::loadAllHealthTypes();
				
				//Retorna para o template atual exibindo as mensagens de validação
				return;
			}
			
			//converte variáveis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
//			desc($bean);die();
			//persiste as informações referentes a saúde na base de dados
			HealthBusiness::save($bean, $this->_request->getParam('controller'));
			
			//redireciona fluxo da aplicação para página de sucesso
			$this->_redirect(HEALTH_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Recupera as informações do form e retorna no array
	 */	
	function assembleFormToBean(HealthForm $frm)
	{	
		if(!Utils::isEmpty($frm))
		{
			//cria uma variável array de nome "bean" 
			$bean = array();
			
			if(!Utils::isEmpty($frm->getPregnancy()))
			{
				
				
				//adiciona uma variável - pregnancy - do form no array "bean"
				$bean[PREGNANCY] = $frm->getPregnancy();
				
				if(!Utils::isEmpty($frm->getMet()))
				{
					//adiciona uma variável - met - do form no array "bean"
					$bean[MET] = $frm->getMet();				
				}	
				
				if(!Utils::isEmpty($frm->getPregnancySis()))
				{
					//adiciona uma variável - pregnancySis - do form no array "bean"
					$bean[SIS_PREGNANCY] = $frm->getPregnancySis();				
				}	
				
				if(!Utils::isEmpty($frm->getPregnancyBegin()))
				{	
					//formata a data inserida pelo usuário
					$beginPregnancy = HealthForm::dateFormat($frm->getPregnancyBegin());
				
					//adiciona uma variável - pregnancyBegin - do form no array "bean"
					$bean[BEGIN_PREGNANCY] = $beginPregnancy;
				}		
			}
			else
			{
				$bean[PREGNANCY] = null;
				$bean[MET] = null;
				$bean[SIS_PREGNANCY] = null;
				$bean[BEGIN_PREGNANCY] = null;
			}
			
			if(!Utils::isEmpty($frm->getUserDrug()))
			{
				//adiciona uma variável - userDrug - do form no array "bean"
				$bean[USER_DRUG] = $frm->getUserDrug();				
			}
			
			if(!Utils::isEmpty($frm->getVaccine()))
			{
				//adiciona uma variável - vaccine - do form no array "bean"
				$bean[VACCINE] = $frm->getVaccine();
			}
			
			if($frm->getCheckedHealthPlan())
			{	
				
				//adiciona uma variável - healthPlan - do form no array "bean"
				$bean[NAME_PLAN] = $frm->getHealthPlan();
			}
			else
			{
				$bean[NAME_PLAN] = null;
			}
						
			if(!Utils::isEmpty($frm->getIdTypeHealth()))
			{
				//adiciona uma variável - idTypeHealth - do form no array "bean"
				$bean[TYPE_HEALTH] = $frm->getIdTypeHealth();				
			}
			
			if(!Utils::isEmpty($frm->getCollFrameworkHealth()))
			{
				//adiciona uma coleção de objetos saúde no array "bean"
				$bean[OBJECTS_HEALTH] = $frm->getCollFrameworkHealth();			
			}
			
			if(!Utils::isEmpty($frm->getIdPerson()))
			{
				//adiciona uma variável - idPerson - do form no array "bean"
				$bean[ID_PERSON] = $frm->getIdPerson();				
			}
			
			return $bean;
		}
	}
	
	//redireciona aplicação para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
}