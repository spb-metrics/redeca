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
 * Jefferson Barros Lima  - W3S		    			05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class AttendanceController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Attendance');
		parent::setControllerHelp('Attendance');
		
		Zend_Loader::loadClass('AssistanceBusiness');
		Zend_Loader::loadClass('AssistanceValidator');
		Zend_Loader::loadClass('AssistanceForm');
		Zend_Loader::loadClass('GeneralAssistanceForm');
		Zend_Loader::loadClass('EspecialAssistanceForm');
		Zend_Loader::loadClass('ProgramBusiness');
		Zend_Loader::loadClass(CLS_ASSISTANCE);
	}
	
	/**
	 * Exibe as informações de atendimentos utilizados pelo usuário
	 */
	function indexAction()
	{		
		$this->_redirect(ATTENDANCE_CONTROLLER.'/'.DEFAULT_ATTENDANCE_ACTION);
	}
	
	function attendanceAction()
	{
		// GeneralAssistanceForm deve ser carregado para recuperar o nome do campo na camada de view 
		Zend_Loader::loadClass('GeneralAssistanceForm');
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
		
		// Validation
		$errorMessages = null; 
		AssistanceValidator::validateViewAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		
		$assistances = AssistanceBusiness::loadAllInProcessAssistance($form->getPersonId());		
		$this->view->assistances 	= AssistanceBusiness::buildAssistanceSummary($assistances);		
		$programs 					= ProgramBusiness::loadProgram(UserLogged::getEntityId());

		// Popula um array com os programas que a entidade oferece
		foreach($programs as $program)
			$programType[$program->{PGR_ID_PROGRAM_TYPE}] = $program->{PGR_ID_PROGRAM_TYPE};

		// Carrega todos os programas da entidade		
		$this->view->programTypes 	= ProgramBusiness::loadProgramType($programType);
	}

	/**
	 * Exibe o template para cadastramento de um atendimento
	 */
	function viewAction()
	{
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
		// Validation
		$errorMessages = null;		
		AssistanceValidator::validateNewAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$assistances = AssistanceBusiness::loadAllInProcessAssistance($form->getPersonId());
			$this->view->assistances 	= AssistanceBusiness::buildAssistanceSummary($assistances);
			
			$programs 					= ProgramBusiness::loadProgram(UserLogged::getEntityId());
			foreach($programs as $program)
				$programType[$program->{PGR_ID_PROGRAM_TYPE}] = $program->{PGR_ID_PROGRAM_TYPE};
		
			$this->view->programTypes 	= ProgramBusiness::loadProgramType($programType);
			
			$this->render('attendance/attendance', null, true);
			return;
		}
		
		$assistance = AssistanceBusiness::verifyPersonProgramTypeByEntity($this->view->form->getProgramType(), $this->view->form->getPersonId(), UserLogged::getEntityId());
		if(!is_null($assistance))
		{						
			$year = substr($assistance->{AST_END_DATE_PREVISION}, 0, 4);
			$month = substr(substr($assistance->{AST_END_DATE_PREVISION}, 5), 0, 2);
			$day = substr($assistance->{AST_END_DATE_PREVISION}, 8, 10);
			$this->view->form->setEndDate($day.'/'.$month.'/'.$year);
			
			$this->_request->setParam($this->view->form->assistanceId(), $assistance->{AST_ID_ASSISTANCE});
			$this->_request->setParam($this->view->form->endDate(), $this->view->form->getEndDate());
			$this->_request->setParam($this->view->form->confidentiality(), $assistance->{AST_CONFIDENTIALITY});
			
			$this->assistanceAction();
			$this->render('attendance/assistance', null, true);
		}		
	}
	
	/**
	 * Popula o container
	 */
	function containerAction()
	{
		$this->attendanceAction();
	}
	
	/**
	 * Persiste as informações do atendimento
	 */
	function assistanceAction()
	{
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
		// Validation
		$errorMessages = null;
		$id = $form->getAssistanceId();
		// Caso seja edição de um atendimento, não executa o restante do código
		if(!empty($id))
		{
			AssistanceValidator::validateEditAssistance($form, $errorMessages);			
			if(sizeof($errorMessages) > 0)
			{
				$this->view->errorMessages = $errorMessages;
				$this->render('attendance/attendance', null, true);
				return;
			}
			
			$this->setAssistanceType($form->getProgramType());
			return;
		}

		// Caso seja inserção de novo atendimento, continua
		AssistanceValidator::validateBasicAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{			
			$this->view->errorMessages = $errorMessages;
			$this->render('attendance/view', null, true);
			return;
		}
		$assistanceType = Utils::getAssistanceClassification($form->getProgramType());
		
		if($assistanceType == Constants::GENERAL)
		{
			$assistance = AssistanceBusiness::loadAllInProcessAssistance($form->getPersonId(), $form->getProgramType());

			if(count($assistance) > 0 )
			{
				foreach($assistance as $current)
				{
					$form->setAssistanceId($current->{AST_ID_ASSISTANCE});
					break;
				}
				// Seta o tipo de atendimento no request
				$this->setAssistanceType($form->getProgramType());
				return;
			}
		}

		$program = ProgramBusiness::loadByQuery($this->assembleProgramQuery($form));
		$program = $program->current();
		$astId = AssistanceBusiness::save($this->assembleAssistance($form, $program->{PGR_ID_PROGRAM}));
		$form->setAssistanceId($astId);

		// Seta o tipo de atendimento no request
		$this->setAssistanceType($program->{PGR_ID_PROGRAM_TYPE});
	}
	
	/**
	 * Exibe template para cadastramento de um atendimento geral
	 */
	function viewgeneralAction()
	{
		Zend_Loader::loadClass('GeneralAssistanceForm');
		$form = new GeneralAssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
		
		$errorMessages = null;
		AssistanceValidator::validateAssistanceId($form, $errorMessages);
		AssistanceValidator::validateAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			// Carrega informações para exibir dados na tela inicial
			$assistances = AssistanceBusiness::loadAllInProcessAssistance($form->getPersonId());
			$this->view->assistances 	= AssistanceBusiness::buildAssistanceSummary($assistances);
			$this->view->programTypes 	= ProgramBusiness::loadAllTypeProgram();
			$this->render('attendance/attendance', null, true);
			
			return;
		}
		$this->view->benefitTypes 		= AssistanceBusiness::loadAllBenefitType();

		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(GeneralAssistanceForm::filter()))
		{
			if($this->getRequest()->getPost(GeneralAssistanceForm::page()))
				$page 				= $this->getRequest()->getPost(GeneralAssistanceForm::page());
		}
		Zend_Loader::loadClass('GeneralAssistance');
		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
		$profile = Utils::getProfileIdAsArray();
		$total					= AssistanceBusiness::countGeneralAssistance($form->getAssistanceId(), $profile);
		$generalAssistance 		= AssistanceBusiness::loadAllGeneralByAssistanceId($form->getAssistanceId(), $profile, $start, Zend_Registry::get(TPAGE)); 
		$this->navBar($page, $total, Zend_Registry::get(TPAGE));
		$this->view->generalAssistance = $generalAssistance;

		$allowChangeConfidentiality = ResourcePermission::allowChangeDefaultConfidentiality($this->_request);
		$this->view->allowChangeConfidentiality = $allowChangeConfidentiality;
		
		try
		{
			$this->view->defaultConfidentiality 	= ResourcePermission::getDefaultConfidentiality($this->_request);
		}
		catch(InvalidResourceForThisOperation $e)
		{
			if(!AuthBusiness::isAdministrator() && !AuthBusiness::isManager() && !AuthBusiness::isCoordinator())
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
				trigger_error($this->view->labels->assistance->load->fail, E_USER_ERROR);
			}
		}

		if($allowChangeConfidentiality || ResourcePermission::getDefaultConfidentiality($this->_request) == Constants::VISIBILITY_PROFILE)
			$this->view->profiles = UserLogged::getProfiles();
	}
	/**
	 * Persiste as informações do atendimento geral
	 */
	function generalAction()
	{	
		Zend_Loader::loadClass('GeneralAssistanceForm');
		$form = new GeneralAssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;

		// Validation
		$errorMessages = null;
		AssistanceValidator::validateGeneralAssistance($form, $this->_request, $errorMessages);
		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->view->benefitTypes 		= AssistanceBusiness::loadAllBenefitType();
			$page 	= 1;
			if($this->getRequest()->isPost() && !$this->getRequest()->getPost(GeneralAssistanceForm::filter()))
			{
				if($this->getRequest()->getPost(GeneralAssistanceForm::page()))
					$page 				= $this->getRequest()->getPost(GeneralAssistanceForm::page());
			}	
			Zend_Loader::loadClass('GeneralAssistance');
			$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
			$profile = Utils::getProfileIdAsArray();
			$total					= AssistanceBusiness::countGeneralAssistance($form->getAssistanceId(), $profile);
			$generalAssistance 		= AssistanceBusiness::loadAllGeneralByAssistanceId($form->getAssistanceId(), $profile, $start, Zend_Registry::get(TPAGE)); 
			$this->navBar($page, $total, Zend_Registry::get(TPAGE));
			$this->view->generalAssistance = $generalAssistance;

			$allowChangeConfidentiality = ResourcePermission::allowChangeDefaultConfidentiality($this->_request);
			$this->view->allowChangeConfidentiality = $allowChangeConfidentiality;
			
			if($allowChangeConfidentiality || ResourcePermission::getDefaultConfidentiality($this->_request) == Constants::VISIBILITY_PROFILE)
				$this->view->profiles = UserLogged::getProfiles();
			
			$params = $this->_request->getParams();
			if($params[Constants::ESPECIAL])
			{
				$this->view->specialGeneral = true;
				$this->render('viewespecialgeneral', null);
			}
			else if($params[Constants::GROUP])
			{
				$this->view->groupGeneral = true;
				$this->render('viewgroupgeneral', null);
			}
			else
				$this->render('viewgeneral', null);
				
			return;
		}
		try
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			/*
			 * Assumindo que os dados sejam somente inseridos, caso contrário, não seria 
			 * possivel utilizar a variável $id
			 */ 
			$general = $this->assembleGeneral($form);
			$id = AssistanceBusiness::saveGeneralAssistance($general, $db);
			if(is_array($id)) $id = current($id);

			if($general[GAS_CONFIDENTIALITY] == Constants::VISIBILITY_PROFILE)
			{
				$profile = $this->assembleProfile($form, $id);
				AssistanceBusiness::saveAllAssistanceProfile($profile, $db);
			}
			$benefit = $this->assembleAstBenefit($form, $id);

			AssistanceBusiness::saveAssistanceBenefit( $benefit, $db );
			$db->commit();
			$db->closeConnection();
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
			trigger_error($this->view->labels->assistance->load->fail, E_USER_ERROR);
		}		
		$this->_redirect(ATTENDANCE_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$form->personId().'/'.$form->getPersonId());
	}

	function viewespecialgeneralAction()
	{
		Zend_Loader::loadClass('GeneralAssistanceForm');
		$form = new GeneralAssistanceForm();
		$params = $this->_request->getParams();
		$form->setAssistanceId($params[GeneralAssistanceForm::assistanceId()]);
		$this->view->form = $form;
		
		$errorMessages = null;
		AssistanceValidator::validateAssistanceId($form, $errorMessages);
		AssistanceValidator::validateAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;			
			// Carrega informações para exibir dados na tela inicial
			$assistances = AssistanceBusiness::loadAllInProcessAssistance($form->getPersonId());
			$this->view->assistances 	= AssistanceBusiness::buildAssistanceSummary($assistances);
			$this->view->programTypes 	= ProgramBusiness::loadAllTypeProgram();
			$this->render('attendance/attendance', null, true);
			
			return;
		}
		$this->view->benefitTypes 		= AssistanceBusiness::loadAllBenefitType();

		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(GeneralAssistanceForm::filter()))
		{
			if($this->getRequest()->getPost(GeneralAssistanceForm::page()))
				$page 				= $this->getRequest()->getPost(GeneralAssistanceForm::page());
		}
		Zend_Loader::loadClass('GeneralAssistance');
		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
		$profile = Utils::getProfileIdAsArray();
		$total					= AssistanceBusiness::countGeneralAssistance($form->getAssistanceId(), $profile);
		$generalAssistance 		= AssistanceBusiness::loadAllGeneralByAssistanceId($form->getAssistanceId(), $profile, $start, Zend_Registry::get(TPAGE));		 
		$this->navBar($page, $total, Zend_Registry::get(TPAGE));
		$this->view->generalAssistance = $generalAssistance;

		$allowChangeConfidentiality = ResourcePermission::allowChangeDefaultConfidentiality($this->_request);		
		$this->view->allowChangeConfidentiality = $allowChangeConfidentiality;
		
		try
		{
			$this->view->defaultConfidentiality 	= ResourcePermission::getDefaultConfidentiality($this->_request);			
		}
		catch(InvalidResourceForThisOperation $e)
		{
			if(!AuthBusiness::isAdministrator() && !AuthBusiness::isManager() && !AuthBusiness::isCoordinator())
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
				trigger_error($this->view->labels->assistance->load->fail, E_USER_ERROR);
			}
		}

		if($allowChangeConfidentiality || ResourcePermission::getDefaultConfidentiality($this->_request) == Constants::VISIBILITY_PROFILE)
			$this->view->profiles = UserLogged::getProfiles();
			
		$idPerson = AssistanceBusiness::load($form->getAssistanceId());
		$this->view->form->setPersonId($idPerson->{AST_ID_PERSON});
		
		$this->view->specialGeneral = true;
	}

	function viewgroupgeneralAction()
	{
		Zend_Loader::loadClass('GeneralAssistanceForm');
		$form = new GeneralAssistanceForm();
		$params = $this->_request->getParams();
		$form->setAssistanceId($params[GeneralAssistanceForm::assistanceId()]);
		$this->view->form = $form;
		
		$errorMessages = null;
		AssistanceValidator::validateAssistanceId($form, $errorMessages);
		AssistanceValidator::validateAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;			
			// Carrega informações para exibir dados na tela inicial
			$assistances = AssistanceBusiness::loadAllInProcessAssistance($form->getPersonId());
			$this->view->assistances 	= AssistanceBusiness::buildAssistanceSummary($assistances);
			$this->view->programTypes 	= ProgramBusiness::loadAllTypeProgram();
			$this->render('attendance/attendance', null, true);
			
			return;
		}
		$this->view->benefitTypes 		= AssistanceBusiness::loadAllBenefitType();

		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(GeneralAssistanceForm::filter()))
		{
			if($this->getRequest()->getPost(GeneralAssistanceForm::page()))
				$page 				= $this->getRequest()->getPost(GeneralAssistanceForm::page());
		}
		Zend_Loader::loadClass('GeneralAssistance');
		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
		$profile = Utils::getProfileIdAsArray();
		$total					= AssistanceBusiness::countGeneralAssistance($form->getAssistanceId(), $profile);
		$generalAssistance 		= AssistanceBusiness::loadAllGeneralByAssistanceId($form->getAssistanceId(), $profile, $start, Zend_Registry::get(TPAGE));		 
		$this->navBar($page, $total, Zend_Registry::get(TPAGE));
		$this->view->generalAssistance = $generalAssistance;

		$allowChangeConfidentiality = ResourcePermission::allowChangeDefaultConfidentiality($this->_request);		
		$this->view->allowChangeConfidentiality = $allowChangeConfidentiality;
		
		try
		{
			$this->view->defaultConfidentiality 	= ResourcePermission::getDefaultConfidentiality($this->_request);			
		}
		catch(InvalidResourceForThisOperation $e)
		{
			if(!AuthBusiness::isAdministrator() && !AuthBusiness::isManager() && !AuthBusiness::isCoordinator())
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
				trigger_error($this->view->labels->assistance->load->fail, E_USER_ERROR);
			}
		}

		if($allowChangeConfidentiality || ResourcePermission::getDefaultConfidentiality($this->_request) == Constants::VISIBILITY_PROFILE)
			$this->view->profiles = UserLogged::getProfiles();
			
		$idPerson = AssistanceBusiness::load($form->getAssistanceId());
		$this->view->form->setPersonId($idPerson->{AST_ID_PERSON});
		
		$this->view->groupGeneral = true;
	}

	/**
	 * Exibe template para cadastramento de um atendimento especial
	 */
	function viewespecialAction()
	{
		Zend_Loader::loadClass('EspecialAssistanceForm');
		$form = new EspecialAssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;

		$errorMessages = null;
		AssistanceValidator::validateAssistanceId($form, $errorMessages);
		AssistanceValidator::validateAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			// Carrega informações para exibir dados na tela inicial
			$assistances = AssistanceBusiness::loadAllInProcessAssistance($form->getPersonId());
			$this->view->assistances 	= AssistanceBusiness::buildAssistanceSummary($assistances);
			$this->view->programTypes 	= ProgramBusiness::loadAllTypeProgram();
			$this->render('attendance/attendance', null, true);

			return;
		}
		// Carrega o atendimento que vem do form - A verificação de edição é feita na validação
		$this->view->assistance 	= AssistanceBusiness::load($form->getAssistanceId());
		$this->view->lawsuit 		= AssistanceBusiness::loadAllLawsuit();
		$this->view->officialLetter = AssistanceBusiness::loadAllOfficialLetter();
		
		if(is_null($this->view->form->getPersonId()))
		{
			$idPerson = AssistanceBusiness::load($form->getAssistanceId());
			$this->view->form->setPersonId($idPerson->{AST_ID_PERSON});
		}
	}
	
	/**
	 * Persiste as informações do atendimento especial
	 */
	function especialAction()
	{	
		Zend_Loader::loadClass('EspecialAssistanceForm');
		$form = new EspecialAssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
		
		// Validation
		$errorMessages = null;
		AssistanceValidator::validateEspecialAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;

			$this->view->assistance 		= AssistanceBusiness::load($form->getAssistanceId());
			$this->view->lawsuit 		= AssistanceBusiness::loadAllLawsuit();
			$this->view->officialLetter = AssistanceBusiness::loadAllOfficialLetter();
			$this->render('attendance/viewespecial', null, true);
			return;
		}
		
		$bean = $this->assembleEspecial($form);
				
		AssistanceBusiness::saveEspecialAssistance( $bean );
		$this->_redirect(ATTENDANCE_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$form->personId().'/'.$form->getPersonId());
	}

	/**
	 * Exibe template para cadastramento de um atendimento geral
	 */
	function viewgroupAction()
	{
		Zend_Loader::loadClass('ClassBusiness');
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		
		if(is_null($form->getPersonId()))
		{
			$idPerson = AssistanceBusiness::load($form->getAssistanceId());
			$form->setPersonId($idPerson->{AST_ID_PERSON});
			$form->setProgramType($idPerson->findParentRow(CLS_PROGRAM)->{PGR_ID_PROGRAM_TYPE});
		}
		
		$this->view->form = $form;
		$errorMessages = null;		
		AssistanceValidator::validateViewGroupAssistance($form, $errorMessages);
		AssistanceValidator::validateAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;			
			// Carrega informações para exibir dados na tela inicial
			$assistances = AssistanceBusiness::loadAllInProcessAssistance($form->getPersonId());
			$this->view->assistances 	= AssistanceBusiness::buildAssistanceSummary($assistances);
			$this->view->programTypes 	= ProgramBusiness::loadAllTypeProgram();
			$this->render('attendance/attendance', null, true);

			return;
		}
		$programTypeId 	= $form->getProgramType();
		// Carrega todos os atendimentos utilizados pela pessoa		
		$assistances 	= AssistanceBusiness::loadAllInProcessAssistance($form->getPersonId(),$programTypeId);		
		$assistancesObj = AssistanceBusiness::loadByQuery($this->buildInQueryToAssistance($assistances));
		$this->view->assistances 	=  AssistanceBusiness::getClassesAsArray($assistancesObj);

		// Carrega todos os atendimentos disponíveis na entidade
		$classesMap = AssistanceBusiness::getClassesIdAsArray($assistancesObj);				
		$classes 					= ClassBusiness::loadAllClassesByEntity($programTypeId, null, $classesMap);		
		$classes 					= ClassBusiness::loadAllClassesByStatus($assistancesObj, $classes);		

		$this->view->classes 		= ClassBusiness::setUpdatedVacancy($classes);
		
		
		// Carrega um map de período
		$this->view->periodMap 		= Constants::getPeriodMap();		
	}

	/**
	 * Persiste as informações do atendimento especial
	 */
	function groupAction()
	{	
		Zend_Loader::loadClass('ClassBusiness');
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
		
		// Validation
		$errorMessages = null;
		AssistanceValidator::validateGroupAssistance($form, $errorMessages);		
//		desc($errorMessages);die();
		if(sizeof($errorMessages) > 0)
		{
			// Se houver erro de validação, valida tbm o programType para exibir corretamente o template viewGroup
			AssistanceValidator::validateRequiredId($form->getProgramType(), $form->programType(), $errorMessages);
			$this->view->errorMessages = $errorMessages;
			$this->render('attendance/viewgroup', null, true);
			return;
		}
		$data = $this->assembleGroup($form);
//		ClassBusiness::fillStatusByVacancy($data);
		
		ClassBusiness::saveClassAssistance($data);
		$this->_redirect(ATTENDANCE_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$form->personId().'/'.$form->getPersonId());
	}

	/**
	 * Encerra um atendimento
	 */
	function closeAction()
	{		
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
		// Validation
		$errorMessages = null;
		AssistanceValidator::validateCloseAssistance($form, $errorMessages);		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			if($this->assistanceType === Constants::GENERAL)
			{
				$this->render('attendance/viewgeneral', null, true);
			}	
			elseif($this->assistanceType === Constants::GROUP)
			{
				$this->render('attendance/viewgroup', null, true);
			}
			elseif($this->assistanceType === Constants::ESPECIAL)
			{
				$form = new EspecialAssistanceForm();
				$form->assembleRequest($this->_request);
				$this->view->form = $form;
				
				$this->view->assitance 		= AssistanceBusiness::load($form->getAssistanceId());
				$this->view->lawsuit 		= AssistanceBusiness::loadAllLawsuit();
				$this->view->officialLetter = AssistanceBusiness::loadAllOfficialLetter();
				$this->render('attendance/viewespecial', null, true);
			}
			return;
		}
		
		if($form->getClassId())
			$res = AssistanceBusiness::closeAssistance($form->getAssistanceId(), $form->getClassId());
		else
			$res = AssistanceBusiness::closeAssistance($form->getAssistanceId(), null);
			
		if($res < 1)
			trigger_error($this->view->labels->error->action, E_USER_ERROR);
		
		$this->_redirect(ATTENDANCE_CONTROLLER .'/'.DEFAULT_SUCCESS_ACTION.'/'.$form->personId().'/'.$form->getPersonId());
	}

	function successAction()
	{
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
	}
	/**
	 * Seta uma constante indicando o grupo do atendimento
	 * para utilização na camada de view  
	 */
	function setAssistanceType($programId)
	{
		try
		{
			$assistType = Utils::getAssistanceClassification($programId);

			if($assistType === NULL)
			{
				$config = Zend_Registry::get(CONFIG);
				throw new Zend_Exception('Invalid config parameters:'.
					' assistance.classification.general => '.$config->assistance->classification->general .
					' assistance.classification.group => '.$config->assistance->classification->group . 
					' assistance.classification.especial => '.$config->assistance->classification->especial);
			}
			$this->view->assistanceType = $assistType;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ". $e);
			trigger_error($this->view->labels->config->invalid->parameter, E_USER_ERROR);
		}
	}

	/**
	 * Exibe a tela de confirmação de exclusão de um atendimento
	 */
	function confirmAction()
	{
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;

		$this->view->action 	= DEFAULT_CLOSE_ACTION;

		// Verifica se o "id" informado pelo usuário é válido
		$errorMessages = null;
		AssistanceValidator::validateCloseAssistance($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;

			return;
		}
		$assistance = AssistanceBusiness::load($form->getAssistanceId());
		$this->view->assistance	= AssistanceBusiness::buildAssistance($assistance);
	}

	/**
	 * Exibe lista de atendimentos utilizados pelo usuario
	 */
	function viewAttendanceAction()
	{
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;		
		$id = $this->_request->getParam($this->view->form->personId());

		$errorMessages = null;
		AssistanceValidator::validateRequiredId($id, $this->view->form->personId(),$errorMessages);
		if($this->view->form->getEntity())
			AssistanceValidator::validateInt($this->view->form->getEntity(), $this->view->form->entity(),$errorMessages);
		if($this->view->form->getEndDate())
			AssistanceValidator::validateInt($this->view->form->getEndDate(), $this->view->form->endDate(),$errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			Zend_Loader::loadClass('EntityBusiness');
			$this->view->entity = EntityBusiness::loadAll();
				
			return $errorMessages;
		}
		$page 	= 1;		
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(AssistanceForm::filter()))
			$page = $this->getRequest()->getPost(AssistanceForm::page());
		
		$start 			= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
		
		$where 	= AssistanceBusiness::createConditionsToLoadAssistences($id, $this->view->form->getEntity(), $this->view->form->getEndDate());
		
		if(is_array($where))
		{
			foreach($where as $k=>$v)
			{
				if(!Utils::array_is_empty($v))
					$flag = true;
				else
					$flag = false;
			}
		}
		
		if($flag === true)
		{		
			$resAssistance = AssistanceBusiness::loadAllAssistancePerson($where, $start, Zend_Registry::get(TPAGE));			
			$total = AssistanceBusiness::count(TBL_ASSISTANCE, AST_ID_ASSISTANCE, $where);
		}
		else
		{
			$resAssistance = null;			
			$total = 1;
		}
				
		$this->view->attendance = $resAssistance;
		
		Zend_Loader::loadClass('EntityBusiness');
		$this->view->entity = EntityBusiness::loadAll();
		
		$this->navBar($page, $total, Zend_Registry::get(TPAGE));		
	}

	/**
	 * Exibe Detalhes do atendimento utilizado pelo usuário
	 */
	function detailAction()
	{		
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
		
		$id = $form->getAssistanceId();

		$errorMessages = null;
		AssistanceValidator::validateRequiredId($id, AssistanceForm::assistanceId(),$errorMessages);		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;				
			return $errorMessages;
		}
		$this->view->form->setPersonId(AssistanceBusiness::load($id)->{AST_ID_PERSON});
		$program = AssistanceBusiness::load($id)->{AST_ID_PROGRAM};
		Zend_Loader::loadClass('ProgramBusiness');
		$programType = ProgramBusiness::loadProgramById($program)->{PGR_ID_PROGRAM_TYPE};		
		$this->view->form->setProgramType($programType);
		
		$errorMessages = null;
		AssistanceValidator::validateNeedProfile($this->view->form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;				
			return $errorMessages;
		}
		
		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(BasicForm::filter()))
		{
			if($this->getRequest()->getPost(BasicForm::page()))
				$page 				= $this->getRequest()->getPost(BasicForm::page());
		}	
		Zend_Loader::loadClass('GeneralAssistance');
		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
		$profile = Utils::getProfileIdAsArray();
		$total					= AssistanceBusiness::countGeneralAssistance($id, $profile);
		$resAssistance = AssistanceBusiness::buildAssistanceDetail($id, $profile, $start, Zend_Registry::get(TPAGE));
		
		$this->navBar($page, $total, Zend_Registry::get(TPAGE));
		$this->view->form->setPersonId(AssistanceBusiness::load($id)->{AST_ID_PERSON});		
		$this->view->assistance = $resAssistance;
		
		if($resAssistance[Constants::AST_SUMMARY_TYPE] != Constants::GENERAL)
			$this->view->general = true;
	}
	
	function detailGeneralAction()
	{		
		$form = new AssistanceForm();
		$form->assembleRequest($this->_request);
		$this->view->form = $form;
		$id = $form->getAssistanceId();

		$errorMessages = null;
		AssistanceValidator::validateRequiredId($id, AssistanceForm::assistanceId(),$errorMessages);		
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;				
			return $errorMessages;
		}
		$this->view->form->setPersonId(AssistanceBusiness::load($id)->{AST_ID_PERSON});
		$program = AssistanceBusiness::load($id)->{AST_ID_PROGRAM};
		Zend_Loader::loadClass('ProgramBusiness');
		$programType = ProgramBusiness::loadProgramById($program)->{PGR_ID_PROGRAM_TYPE};		
		$this->view->form->setProgramType($programType);
		
		$errorMessages = null;
		AssistanceValidator::validateNeedProfile($this->view->form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;				
			return $errorMessages;
		}
		
		$page 	= 1;
		if($this->getRequest()->isPost() && !$this->getRequest()->getPost(BasicForm::filter()))
		{
			if($this->getRequest()->getPost(BasicForm::page()))
				$page 				= $this->getRequest()->getPost(BasicForm::page());
		}	
		Zend_Loader::loadClass('GeneralAssistance');
		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
		$profile = Utils::getProfileIdAsArray();
		$total					= AssistanceBusiness::countGeneralAssistance($id, $profile);
		$resAssistance = AssistanceBusiness::buildAssistanceDetailGeneral($id, $profile, $start, Zend_Registry::get(TPAGE));
		$this->navBar($page, $total, Zend_Registry::get(TPAGE));
		$this->view->form->setPersonId(AssistanceBusiness::load($id)->{AST_ID_PERSON});		
		$this->view->assistance = $resAssistance;
		
		$this->view->general = true;
	}
	
	/**
	 * Monta uma query com todos os Identificadores de um atendimento
	 * @param Object/Array $assistance Objeto que representa um conjunto de atendimentos
	 */
	public function buildInQueryToAssistance($assistance)
	{
		$arrayId = null;
		if(!empty($assistance) && is_array($assistance))
		{
			$ids = null;
			foreach($assistance as $current)
				$ids[] = $current->{AST_ID_ASSISTANCE};
			
			$arrayId[AST_ID_ASSISTANCE. ' in (?)'] = $ids;
		}
		return $arrayId;
	}

	/**
	 * Popula um array que representa um registro na tabela profile
	 */
	public function assembleProfile($form, $id)
	{
		if(!empty($id) && !empty($form))
		{
			Zend_Loader::loadClass(CLS_ASSISTANCEPROFILE);
			$data[AAP_ID_ASSISTANCE] = $id;
			$data[AAP_ID_PROFILE] = $form->getProfileId();
			$arr[] = $data;
		}
		return $arr;
	}

	/**
	 * Popula um array que representa um programa da entidade
	 */
	private function assembleProgramQuery($form)
	{
		if(!empty($form))
		{
			Zend_Loader::loadClass(CLS_PROGRAM);
			$data[PGR_ID_ENTITY.' = ?'] = UserLogged::getEntityId();
			$data[PGR_ID_PROGRAM_TYPE.' = ?'] = $form->getProgramType();
		}
		return $data;
	}
	
	/**
	 * Popula um array que representa um atendimento
	 */
	private function assembleAssistance($form, $programId)
	{
		if(!empty($form) && !empty($programId))
		{
			Zend_Loader::loadClass(CLS_ASSISTANCE);
			try{
				$userId = UserLogged::getUserId();
			}catch(UserNotLoggedException $e){
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$this->view->labels->notPermission.' - '.$e);
				trigger_error($this->view->labels->notPermission , E_USER_ERROR);
			}
			
			$data[AST_END_DATE_PREVISION] = $form->dateFormat($form->getEndDate());
			$data[AST_ID_PROGRAM] = $programId;
			$data[AST_ID_USER] = $userId;
			$data[AST_ID_PERSON] = $form->getPersonId();
			
			if($form->getConfidentiality() == 1)
				$data[AST_CONFIDENTIALITY] = TRUE;
			else
				$data[AST_CONFIDENTIALITY] = FALSE;
		}
		return $data;
	}
	/**
	 * Popula um array que representa um atendimento geral
	 */
	private function assembleGeneral(GeneralAssistanceForm $form)
	{
		if(!empty($form))
		{
			Zend_Loader::loadClass(CLS_GENERALASSISTANCE);
			$data[GAS_ID_ASSISTANCE] = $form->getAssistanceId();
			$data[GAS_ASSISTANCE_COMMENT] = $form->getComments();
			$allowChangeConfidentiality = ResourcePermission::allowChangeDefaultConfidentiality($this->_request);
			if($allowChangeConfidentiality)
				$data[GAS_CONFIDENTIALITY] = $form->getConfidentialityLevel();
			else
			{
				$default = ResourcePermission::getDefaultConfidentiality($this->_request);
				$data[GAS_CONFIDENTIALITY] = $default;
			}
				
			$data[GAS_REGISTER_DATA] = date('c',time());
		}
		return $data;
	}

	/**
	 * Popula um array que representa um atendimento de grupo
	 * para criar um relacionamento entre atendimento e turma
	 */
	private function assembleGroup($form)
	{
		if(!empty($form))
		{
			Zend_Loader::loadClass(CLS_CLASSASSISTANCE);
			$data[CLA_ID_ASSISTANCE]= $form->getAssistanceId();
			$data[CLA_ID_CLASS] 	= $form->getClassId();
			if(!$form->getIdStatus())
				$data[CLA_ID_STATUS] 	= Constants::APPLY_LIST;
			else
				$data[CLA_ID_STATUS] 	= $form->getIdStatus();	
		}
		return $data;
	}


	/**
	 * Popula um array que representa o relacionamento atendimento-beneficio(gas_assistance_benefit)
	 */
	private function assembleAstBenefit($form, $id)
	{
		if(!empty($form) && !empty($id))
		{
			Zend_Loader::loadClass(CLS_ASSISTANCEBENEFIT);
			$data[ABF_ID_ASSISTANCE] 				= $form->getAssistanceId(); 
			$data[ABF_ID_GENERAL_ASSISTANCE] 		= $id;
			$data[ABF_ID_ASSISTANCE_BENEFIT_TYPE] 	= $form->getBenefitType();
		}
		return $data;
	}
	
	/**
	 * Popula um array que representa um atendimento especial
	 */
	private function assembleEspecial(EspecialAssistanceForm $form)
	{
		if(!empty($form))
		{
			Zend_Loader::loadClass(CLS_ESPECIALASSISTANCE);
			if($form->getAssistanceId())
				$data[EAS_ID_ASSISTANCE] = $form->getAssistanceId();
			else
				$data[EAS_ID_ASSISTANCE] = null;
				
			if($form->getOfficialLetter())	
				$data[EAS_ID_OFFICIAL_LETTER_ORIGIN] = $form->getOfficialLetter();
			else
				$data[EAS_ID_OFFICIAL_LETTER_ORIGIN] = null;
			
			if($form->getLawsuit())
				$data[EAS_ID_LAWSUIT_ORIGIN] = $form->getLawsuit();
			else
				$data[EAS_ID_LAWSUIT_ORIGIN] = null;
			
			if($form->getOfficialLetterNumber())
				$data[EAS_OFFICIAL_LETTER_NUMBER] = $form->getOfficialLetterNumber();
			else
				$data[EAS_OFFICIAL_LETTER_NUMBER] = null;
			
			if($form->getOfficialLetterYear())
				$data[EAS_OFFICIAL_LETTER_YEAR] = $form->getOfficialLetterYear();
			else
				$data[EAS_OFFICIAL_LETTER_YEAR] = null;
			
			if($form->getLawsuitNumber())
				$data[EAS_LAWSUIT_NUMBER] = $form->getLawsuitNumber();
			else
				$data[EAS_LAWSUIT_NUMBER] = null;
			
			if($form->getLawsuitYear())
				$data[EAS_LAWSUIT_YEAR] = $form->getLawsuitYear();
			else
				$data[EAS_LAWSUIT_YEAR] = null;
			
			if($form->getLawsuitDetail())
				$data[EAS_LAWSUIT_DETAIL] = $form->getLawsuitDetail();
			else
				$data[EAS_LAWSUIT_DETAIL] = null;
			
			if($form->getRuling())
				$data[EAS_RULING] = $form->getRuling();
			else
				$data[EAS_RULING] = null;
		}
		return $data;
	}
}