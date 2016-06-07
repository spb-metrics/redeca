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
 * Lucas dos Santos Borges Corr�a  - W3S		    05/05/2008	                       Create file 
 * 
 */

require_once('BasicController.php');

class EducationController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Education');
		parent::setControllerHelp('Education');
		
		Zend_Loader::loadClass('EducationForm');
		Zend_Loader::loadClass('LevelInstruction');
		Zend_Loader::loadClass('LevelInstructionBusiness');
		Zend_Loader::loadClass('HistoryBusiness');
		Zend_Loader::loadClass('SchoolBusiness');
		Zend_Loader::loadClass('School');
		Zend_Loader::loadClass('RegistrationBusiness');
		Zend_Loader::loadClass('Registration');
		Zend_Loader::loadClass('EducationValidator');
		Zend_Loader::loadClass('Constants');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('Resource');
		Zend_Loader::loadClass('PersonChangeHistory');
		
		$frm = new EducationForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
	}
	
	/**
	 * Visualiza informa��es referentes a situa��o escolar da pessoa em quest�o
	 */
	function indexAction()
	{		
		$this->view->form->setIdPerson($this->view->form->getPersonId());
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = EducationValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//Retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//carrega as as informa��es escolares de uma pessoa
		$this->view->levelInstruction = LevelInstructionBusiness::loadLevelInstructionByPerson($this->view->form->getIdPerson());
		
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
	 * Exibe formul�rio para edi��o de uma situa��o escolar
	 */
	function viewAction()
	{	
		//valida as informa��es inseridas no form pelo usu�rio  
		$errorMessages = EducationValidator::validatePersonId($this->view->form);
		
		if(sizeof($errorMessages) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->errorMessages = $errorMessages;
			
			//retorna para o template atual exibindo as mensagens de valida��o
			return;
		}
		
		//carrega todos os per�odos
		$this->view->periods = RegistrationBusiness::loadAllPeriodType();
		
		//carrega todas as s�ries escolares
		$this->view->years = RegistrationBusiness::loadAllSchoolYear();
		
		//carrega todos os graus escolares
		$this->view->degrees = LevelInstructionBusiness::loadAllDegree();
		
		//carrega todos os tipos de escola
		$this->view->schools = SchoolBusiness::loadAllSchool();
		
		//carrega as informa��es escolares de uma pessoa
		$this->view->levelInstruction = LevelInstructionBusiness::loadLevelInstructionByPerson($this->view->form->getIdPerson());
		
		//carrega todas as escolas cadastradas
		$this->view->allSchool = SchoolBusiness::load();
		
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
	 * Salva situa��o escolar editada
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
			$errorMessages = EducationValidator::validateEducationEdit($this->view->form);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega todos os per�odos
				$this->view->periods = RegistrationBusiness::loadAllPeriodType();
				
				//carrega todas as s�ries escolares
				$this->view->years = RegistrationBusiness::loadAllSchoolYear();
				
				//carrega todos os graus escolares
				$this->view->degrees = LevelInstructionBusiness::loadAllDegree();
				
				//carrega todos os tipos de escola
				$this->view->schools = SchoolBusiness::loadAllSchool();
				
				//carrega todas as escolas cadastradas
				$this->view->allSchool = SchoolBusiness::load();
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
			
			//persiste as informa��es referentes a educa��o na base de dados
			SchoolBusiness::saveEducation($bean);
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(EDUCATION_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idPerson().'/'.$this->view->form->getIdPerson());
		}
	}
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */	
	function assembleFormToBean(EducationForm $frm)
	{	
		if(!Utils::isEmpty($frm))
		{
			//cria uma vari�vel array de nome "bean" 
			$bean = array();
			
			if(!Utils::isEmpty($frm->getIdDegreeType()))
			{
				//adiciona uma vari�vel - idDegreeType - do form no array "bean"
				$bean[LIT_ID_DEGREE] = $frm->getIdDegreeType();				
			}
			
			if(!Utils::isEmpty($frm->getIdYearSchoolType()))
			{
				//adiciona uma vari�vel - idYearSchoolType - do form no array "bean"
				$bean[SYT_ID_SCHOOL_YEAR] = $frm->getIdYearSchoolType();				
			}
			
			if(!Utils::isEmpty($frm->getIdPeriodType()))
			{
				//adiciona uma vari�vel - idPeriodType - do form no array "bean"
				$bean[REG_ID_PERIOD] = $frm->getIdPeriodType();				
			}
			
			if(!Utils::isEmpty($frm->getMonth()))
			{
				//adiciona uma vari�vel - month - do form no array "bean"
				$bean[LIT_LAST_MONTH_STUDIED] = $frm->getMonth();				
			}
			
			if(!Utils::isEmpty($frm->getYear()))
			{
				//adiciona uma vari�vel - year - do form no array "bean"
				$bean[LIT_LAST_YEAR_STUDIED] = $frm->getYear();				
			}
			
			if(!Utils::isEmpty($frm->getIdPerson()))
			{
				//adiciona uma vari�vel - idPerson - do form no array "bean"
				$bean[LIT_ID_PERSON] = $frm->getIdPerson();				
			}
			
			if(Utils::isEmpty($frm->getIdSchool()))
			{
				$bean[SCH_ID_SCHOOL] = null;
			}
			else
			{
				//adiciona uma vari�vel - idSchool - do form no array "bean"
				$bean[SCH_ID_SCHOOL] = $frm->getIdSchool();	
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