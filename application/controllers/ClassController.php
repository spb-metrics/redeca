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
 * Jefferson Barros Lima  - W3S		    			05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class ClassController extends BasicController
{
	/**
	 * Inicializa��o
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Class');
		parent::setControllerHelp('Class');
		
		Zend_Loader::loadClass('ClassForm');
		Zend_Loader::loadClass('ClassBusiness');
		Zend_Loader::loadClass('ActivityBusiness');
		Zend_Loader::loadClass('ClassAssistance');
		Zend_Loader::loadClass('ClassValidator');
		Zend_Loader::loadClass('ClassModel');
		Zend_Loader::loadClass('ActivityClass');
		Zend_Loader::loadClass('ProgramBusiness');
		Zend_Loader::loadClass('AssistanceBusiness');
		Zend_Loader::loadClass('Program');
		Zend_Loader::loadClass('Utils');
		
		$frm = new ClassForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
	}
	
	/**
	 * Exibe a tela que confirma o encerramento da turma
	 * 
	 */
	function confirmAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			//action a ser chamada caso o usu�rio confirma a exclus�o 
			$this->view->action = DEFAULT_CLOSE_ACTION;
			
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassValidator::validateClassId($this->view->form);
			ClassValidator::validateClassHaveEntity($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				$this->view->errorMessages = $errorMessages;
				return;
			}
			
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId();
			
			//carrega as todos os programas de uma entidade espec�fica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
		}		
	}
	
	/**
	 * Lista todas as turmas cadastradas no sistema
	 */
	function indexAction()
	{	
		//recupera o id da Entidade do usu�rio logado
		$idEntity = UserLogged::getEntityId();
		if($idEntity)
		{
			//carrega todas as turmas de uma Entidade espec�fica
			$this->view->classes = ClassBusiness::loadAllClassesByEntity(null, $idEntity);
			
			//carrega todas as atividades de uma turma especifica
			$this->view->acts = ClassBusiness::loadActivitiesClass($this->view->classes);
			
			//carrega as todos os programas de uma entidade espec�fica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity);
			
			$this->view->form->setIdEntity($idEntity);
		}
		else
		{
			$this->view->classes = $this->view->labels->required->user->entity;			
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
	 * Exibe formul�rio para cria��o de uma nova turma
	 */
	function newAction()
	{		
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId();
		
			//carrega as todos os programas de uma entidade espec�fica	
			$programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
			
			//seta objeto no template
			$this->view->programs = $programs;
	
			//seta objeto no template
			$this->view->activities = $programs;
			
			//carrega os per�odos poss�veis
			$this->view->allPeriod = Constants::getPeriodMap();
			
			//seta no form o id da entidade
			$this->view->form->setIdEntity($idEntity);
		}
	}
	
	function loadProgramAction()
	{	
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId();
			
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassValidator::validateProgramId($this->view->form);
			ClassValidator::validateClassHaveEntity($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
	
				//carrega a combo com os programas referentes a uma entidade espec�fica
				$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
				
				//carrega a combo com as atividades referentes a uma entidade espec�fica
				$this->view->activities = ProgramBusiness::loadProgramGroupByEntity($idEntity, $this->view->form->getIdProgram());
				
				//retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
	 
			//carrega a combo com os programas referentes a uma entidade espec�fica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity);
			
			//carrega a combo com as atividades referentes a uma entidade espec�fica
			$this->view->activities = ProgramBusiness::loadProgramGroupByEntity($idEntity, $this->view->form->getIdProgram());
			
			//carrega os per�odos poss�veis
			$this->view->allPeriod = Constants::getPeriodMap();
		}
	}
	
	function loadProgramByClassAction()
	{	
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassValidator::validateProgramId($this->view->form);
			ClassValidator::validateClassId($this->view->form, $errorMessages);
			ClassValidator::validateClassHaveEntity($this->view->form, $errorMessages);
			
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId();
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
	
				//carrega a combo com os programas referentes a uma entidade espec�fica	
				$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity);
				
				//carrega a combo com as atividades referentes a uma entidade espec�fica
				$this->view->activities = ProgramBusiness::loadProgramGroupByEntity($idEntity, $this->view->form->getIdProgram());
				
				//carrega uma turma espec�fica
				$this->view->classes = ClassBusiness::load($this->view->form->getIdClass());
				
				//retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
	 
			//carrega a combo com os programas referentes a uma entidade espec�fica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity);
			
			//carrega a combo com as atividades referentes a uma entidade espec�fica
			$this->view->activities = ProgramBusiness::loadProgramGroupByEntity($idEntity, $this->view->form->getIdProgram());
			
			//carrega uma turma espec�fica
			$this->view->classes = ClassBusiness::load($this->view->form->getIdClass());
			
			//carrega os per�odos poss�veis
			$this->view->allPeriod = Constants::getPeriodMap();
		}
	}
	
	/**
	 * Exibe formul�rio para edi��o de uma turma
	 */
	function viewAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{	
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{	
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId();
			
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassValidator::validateProgramId($this->view->form);
			ClassValidator::validateClassHaveEntity($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//carrega todos os programas de uma entidade espec�fica
			$programs = ProgramBusiness::loadProgramGroupByEntity($idEntity);
			
			//seta objeto no template
			$this->view->programs = $programs;
	
			//seta objeto no template
			$this->view->activities = $programs;
			
			//carrega uma turma espec�fica
			$this->view->classes = ClassBusiness::load($this->view->form->getIdClass());
			
			//carrega os per�odos poss�veis
			$this->view->allPeriod = Constants::getPeriodMap();
			
			//seta flag para carregar somente atividades de um programa espec�fico
			$this->view->flagEdit = true;
		}
		
	}
		
	/**
	 * Salva nova turma (cadastro)
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
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId();
			
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassValidator::validateClass($this->view->form);
			ClassValidator::validateClassEqualName($this->view->form, $errorMessages);
			ClassValidator::validateClassHaveEntity($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega a combo com os programas referentes a uma entidade espec�fica
				$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
				
				//carrega a combo com as atividades referentes a uma entidade espec�fica
				$this->view->activities = ProgramBusiness::loadProgramGroupByEntity($idEntity, $this->view->form->getIdProgram());
				
				//carrega os per�odos poss�veis
				$this->view->allPeriod = Constants::getPeriodMap();
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
			
			//persiste as informa��es referentes a turma na base de dados
			ClassBusiness::saveClass($bean);
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(CLASS_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idEntity().'/'.$idEntity);
		}
	}
	
	/**
	 * Salva turma (edi��o)
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
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId();
			
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassValidator::validateClass($this->view->form);
			ClassValidator::validateClassId($this->view->form, $errorMessages);
			ClassValidator::validateClassHaveEntity($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega a combo com os programas referentes a uma entidade espec�fica
				$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity);
				
				//carrega a combo com as atividades referentes a uma entidade espec�fica
				$this->view->activities = ProgramBusiness::loadProgramGroupByEntity($idEntity, $this->view->form->getIdProgram());
				
				//carrega uma turma espec�fica
				$this->view->classes = ClassBusiness::load($this->view->form->getIdClass());
				
				//carrega os per�odos poss�veis
				$this->view->allPeriod = Constants::getPeriodMap();
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "bean" 
			$bean = $this->assembleFormToBean($this->view->form);
			
			//persiste as informa��es referentes a educa��o na base de dados
			ClassBusiness::saveClass($bean);
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(CLASS_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idEntity().'/'.$idEntity);
		}
	}
	
	function validAction()
	{
		//recupera o id da Entidade do usu�rio logado
		$idEntity = UserLogged::getEntityId();
		
		$existPersonInClass = ClassValidator::validatePersonInClass($this->view->form);
		ClassValidator::validateClassHaveEntity($this->view->form, $existPersonInClass);
				
		if(sizeof($existPersonInClass) > 0)
		{
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->notPersonInClass = $existPersonInClass;
		
			return;
		}
		
		//carrega as todos os programas de uma entidade espec�fica
		$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
		
		//seta no template vari�vel para carregar div
		$this->view->viewDivMigrate = TRUE;
			
		//Retorna para o template atual exibindo as mensagens de valida��o
		return;
	}
	
	/**
	 * Encerra uma turma
	 */
	function closeAction()
	{	
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId();
			
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassValidator::validateClassId($this->view->form);
			ClassValidator::validateClassHaveEntity($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{	
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//redirecionar para template de par�metros inv�lidos
				return;
			}
	 	
	 		if($this->view->form->getFlagMigrate())
	 		{	
				$errorMessages = ClassValidator::validateFlagMigrate($this->view->form);
				ClassValidator::validateConfidentiality($this->view->form, $errorMessages);
				ClassValidator::validateNewClassId($this->view->form, $errorMessages);
				ClassValidator::validateEndDate($this->view->form, $errorMessages);
							
				if(sizeof($errorMessages) > 0)
				{	
					//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
					$this->view->errorMessages = $errorMessages;
			
					//carrega as todos os programas de uma entidade espec�fica
					$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
					
					//redirecionar para template de par�metros inv�lidos
					return;
				}
				
				//formata a data inserida pelo usu�rio
				$dateEndPrevision = BasicForm::dateFormat($this->view->form->getEndDate());
				
				//migra a turma e logo ap�s encerra a mesma
	 			ClassBusiness::migrateClass($this->view->form->getIdClass(), $this->view->form->getIdNewClass(), $dateEndPrevision, $this->view->form->getConfidentiality());
	 		}
	 		else
	 		{
				//encerra uma turma espec�fica
				ClassBusiness::closeClass($this->view->form->getIdClass()); 			
	 		}
	 				
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(CLASS_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idEntity().'/'.$idEntity);
		}
	}
	
	/**
	 * Encerra uma atividade de uma turma
	 */
	function closeActivityAction()
	{
		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
		if($readOnly)
		{
			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
		}
		else
		{
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId();
			
			//valida as informa��es inseridas no form pelo usu�rio  
			$errorMessages = ClassValidator::validateActivityClassId($this->view->form);
			ClassValidator::validateClassHaveEntity($this->view->form, $errorMessages);
			if(sizeof($errorMessages) > 0)
			{	
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//redirecionar para template de par�metros inv�lidos
				return;
			}
	 		
	 		//atualiza o relacionamento entre turma e atividades
			ActivityBusiness::updateActivityClass($this->view->form->getIdActivityClass());
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect(CLASS_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$this->view->form->idEntity().'/'.$idEntity);
		}
	}
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */	
	function assembleFormToBean(ClassForm $frm)
	{		
		if(!Utils::isEmpty($frm))
		{
			//cria uma vari�vel array de nome "bean" 
			$bean = array();
		
			if(!Utils::isEmpty($frm->getIdClass()))
			{
				//adiciona uma vari�vel - idClass - do form no array "bean"
				$bean[CLS_ID_CLASS] = $frm->getIdClass();
			}
			else
			{
				$bean[CLS_ID_CLASS] = null;
			}
		
			if(!Utils::isEmpty($frm->getIdProgram()))
			{
				//adiciona uma vari�vel - idProgram - do form no array "bean"
				$bean[CLS_ID_PROGRAM] = $frm->getIdProgram();
			}
			
			if(!Utils::isEmpty($frm->getIdActivityDetail()))
			{
				//adiciona uma vari�vel - idActivityDetail - do form no array "bean"
				$bean[ACC_ID_ACTIVITY_DETAIL] = $frm->getIdActivityDetail();
			}
			
			if(!Utils::isEmpty($frm->getIdActivityClass()))
			{
				//adiciona uma vari�vel - idActivityClass - do form no array "bean"
				$bean[ACC_ID_ACTIVITY_CLASS] = $frm->getIdActivityClass();
			}
			
			
			if(!Utils::isEmpty($frm->getClassName()))
			{
				//adiciona uma vari�vel - className - do form no array "bean"
				$bean[CLS_NAME] = $frm->getClassName();
			}
			
			if(!Utils::isEmpty($frm->getVacancy()))
			{
				//adiciona uma vari�vel - vacancy - do form no array "bean"
				$bean[CLS_VACANCY] = $frm->getVacancy();
			}
			
			if(!Utils::isEmpty($frm->getPeriod()))
			{
				//adiciona uma vari�vel - period - do form no array "bean"
				$bean[CLS_PERIOD] = $frm->getPeriod();
			}
			
			if(!Utils::isEmpty($frm->getTimeClass()))
			{
				//adiciona uma vari�vel - time - do form no array "bean"
				$bean[CLS_SCHEDULE] = $frm->getTimeClass();
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