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


class ActivityDetailController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Activity');
		parent::setControllerHelp('Activity');
		
		Zend_Loader::loadClass('Category');
		Zend_Loader::loadClass('ActivityBusiness');
		Zend_Loader::loadClass('ClassBusiness');
		Zend_Loader::loadClass('ClassAssistance');
		Zend_Loader::loadClass('ActivityDetailBusiness');
		Zend_Loader::loadClass('ActivityDetail');
		Zend_Loader::loadClass('ActivityForm');
		Zend_Loader::loadClass('ActivityValidator');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('ProgramBusiness');

		$frm = new ActivityForm();
		$frm->assembleRequest($this->_request);
		$this->view->form = $frm;
	}
	
	/**
	 * Template para exibir erro apenas quando usuário altera parâmetros do form
	 */
	function validAction()
	{
		;
	}
	
	/**
	 * Exibe uma determinada atividade para ser editada 
	 */
	function viewAction()
	{
		$errorMessages = ActivityValidator::validateIdDetailActivity($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->_redirect(ACTIVITY_DETAIL_CONTROLLER.'/'.DEFAULT_VALID_ACTION);		
		}
			
		//recupera o id da Entidade do usuário logado
		$idEntity = UserLogged::getEntityId();
		
		//carrega a combo com os programas referentes a uma entidade específica
		$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
		
		//carrega todas as subcategorias cadastradas na rede
		$this->view->activities = ActivityBusiness::loadActivitiesEnable();
		
		//Carrega a atividade a ser editada
		$loadActivityDetail = ActivityDetailBusiness::findActivityDetail($this->view->form->getIdActivityDetail());
		
		//carrega a atividade a ser editada
		$this->view->activityDetail = $loadActivityDetail->current(); 
	}
	
	/**
	 * Redireciona para a funcionalidade padrão 
	 */
	function indexAction()
	{
		//recupera o id da Entidade do usuário logado
		$idEntity = UserLogged::getEntityId();
	
		//carrega a combo com os programas referentes a uma entidade específica
		$this->view->collActivityDetail = ActivityDetailBusiness::loadAllByEntity($idEntity);
	}
	
	/**
	 * Salva as informações de detalhamento de atividade
	 */
	function addAction()
	{
		$errorMessages = ActivityValidator::validateNameDetailActivity($this->view->form);
		ActivityValidator::validateWorkingHour($this->view->form, $errorMessages);
		ActivityValidator::validateIdProgram($this->view->form, $errorMessages);
		ActivityValidator::validateIdCategory($this->view->form, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;

			//recupera o id da Entidade do usuário logado
			$idEntity = UserLogged::getEntityId();
		
			//carrega a combo com os programas referentes a uma entidade específica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
			
			//carrega todas as subcategorias cadastradas na rede
			$this->view->activities = ActivityBusiness::loadActivitiesEnable();
			
			return;
		}
		
		$actDetail = $this->assembleFormDetailToActivity($this->view->form);

		ActivityDetailBusiness::save($actDetail);
		
		$this->_redirect(ACTIVITY_DETAIL_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Salva as informações de detalhamento de atividade editadas
	 */
	function editAction()
	{
		$errorMessages = ActivityValidator::validateIdDetailActivity($this->view->form);
		ActivityValidator::validateNameDetailActivityEdit($this->view->form, $errorMessages);
		ActivityValidator::validateWorkingHour($this->view->form, $errorMessages);
		ActivityValidator::validateIdProgram($this->view->form, $errorMessages);
		ActivityValidator::validateIdCategory($this->view->form, $errorMessages);		
		
		$alertActivityInUse = ActivityValidator::verifyIfActivityDetailIsUsed($this->view->form);
		
		if(sizeof($errorMessages) > 0 || sizeof($alertActivityInUse) > 0 )
		{
			$this->view->errorMessages = $errorMessages;
			$this->view->alertActivityInUse = $alertActivityInUse;
			//recupera o id da Entidade do usuário logado
			$idEntity = UserLogged::getEntityId();
		
			//carrega a combo com os programas referentes a uma entidade específica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
			
			//carrega todas as subcategorias cadastradas na rede
			$this->view->activities = ActivityBusiness::loadActivitiesEnable();
			
			return;
		}
		
		$actDetail = $this->assembleFormDetailToActivity($this->view->form);

		ActivityDetailBusiness::save($actDetail);
		
		$this->_redirect(ACTIVITY_DETAIL_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe formulário para criação de nova atividade
	 */
	function newAction()
	{
		//recupera o id da Entidade do usuário logado
		$idEntity = UserLogged::getEntityId();
	
		//carrega a combo com os programas referentes a uma entidade específica
		$this->view->programs = ProgramBusiness::loadProgramGroupByEntity($idEntity, null);
		
		//carrega todas as subcategorias cadastradas na rede
		$this->view->activities = ActivityBusiness::loadActivitiesEnable();
	}
	
	/**
	 * Exclui um detalhamento de atividade
	 */
	function dropAction()
	{
		$errorMessages = ActivityValidator::validateIdDetailActivity($this->view->form);
		$alertActivityInUse = ActivityValidator::verifyIfActivityDetailIsUsedBeforeDrop($this->view->form);
		
		if(sizeof($errorMessages) > 0 || sizeof($alertActivityInUse) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->view->alertActivityInUse = $alertActivityInUse;
			
			return;
		}
		
		//exclui informações referentes ao detalhamento de atividade
		ActivityDetailBusiness::dropProgramByIdActivityDetail($this->view->form->getIdActivityDetail());
		
		//redireciona fluxo da aplicação para página de sucesso
		$this->_redirect(ACTIVITY_DETAIL_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION);
	}
	
	/**
	 * Exibe a tela de confirmação de exclusão de uma atividade
	 * 
	 */
	function confirmAction()
	{
		//action a ser chamada caso o usuário confirma a exclusão 
		$this->view->action = DEFAULT_DROP_ACTION;
		
		$errorMessages = ActivityValidator::validateIdDetailActivity($this->view->form);
		$alertActivityInUse = ActivityValidator::verifyIfActivityDetailIsUsedBeforeDrop($this->view->form);
		
		if(sizeof($errorMessages) > 0 || sizeof($alertActivityInUse) > 0 )
		{
			$this->view->errorMessages = $errorMessages;
			$this->view->alertActivityInUse = $alertActivityInUse;
			
			return;
		}
		
		//carrega a atividade a ser excluída
		$loadActivityDetail = ActivityDetailBusiness::findActivityDetail($this->view->form->getIdActivityDetail());
		
		$this->view->objectActivityDetail = $loadActivityDetail->current();	
	}
	
	/**
	 * Constróe uma array com as informações vindas do form
	 */
	private function assembleFormDetailToActivity(ActivityForm &$frm)
	{
		if(!Utils::isEmpty($frm))
		{	
			$detailActivity = array();
			
			if(!Utils::isEmpty($frm->getIdActivityDetail()) )
			{
				$detailActivity[ACD_ID_ACTIVITY_DETAIL] = $frm->getIdActivityDetail();
			}
			
			if(!Utils::isEmpty($frm->getIdProgram()) )
			{
				$detailActivity[ACD_ID_PROGRAM] = $frm->getIdProgram();
			}

			if(!Utils::isEmpty($frm->getIdCategory()) )
			{
				$detailActivity[ACD_ID_CATEGORY] = $frm->getIdCategory();
			}

			if(!Utils::isEmpty($frm->getNameDetailActivity()) )
			{
				$detailActivity[ACD_ACTIVITY_DETAIL] = $frm->getNameDetailActivity();
			}

			if(!Utils::isEmpty($frm->getWorkingHour()) )
			{
				$detailActivity[ACD_HOURLY_LOAD] = $frm->getWorkingHour();
			}
			
			return $detailActivity;
		}
		
		return null;
	}
	
	function successAction()
	{
		;
	}
}