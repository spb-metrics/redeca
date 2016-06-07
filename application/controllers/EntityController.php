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
 * Jefferson Barros Lima  - W3S		   	 			05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicController.php');

class EntityController extends BasicController
{
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Entity');
		parent::setControllerHelp('Entity');
		
		Zend_Loader::loadClass('Entity');
		Zend_Loader::loadClass('TelephoneNumber');
		Zend_Loader::loadClass('EntityTelephone');
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('EntityForm');
		Zend_Loader::loadClass('UserForm');
		Zend_Loader::loadClass('AreaForm');
		Zend_Loader::loadClass('ClassificationForm');
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('UserBusiness');
		Zend_Loader::loadClass('AddressBusiness');
		Zend_Loader::loadClass('ProgramBusiness');
		Zend_Loader::loadClass('AreaBusiness');
		Zend_Loader::loadClass('ClassificationBusiness');
		Zend_Loader::loadClass('TelephoneBusiness');
		Zend_Loader::loadClass('EntityValidator');
		Zend_Loader::loadClass('UserValidator');
		Zend_Loader::loadClass('AreaValidator');
		Zend_Loader::loadClass('ClassificationValidator');
		Zend_Loader::loadClass('ClassBusiness');
		Zend_Loader::loadClass('ActivityClass');
		Zend_Loader::loadClass('AssistanceBusiness');
		
		// Form para a busca de Endereço
		Zend_Loader::loadClass('SearchAddressForm');
		Zend_Loader::loadClass('EntityClassification');
		Zend_Loader::loadClass('EntityArea');
		
		// EntityForm
		$frmEntity = new EntityForm();
		$frmEntity->assembleRequest($this->_request);
		$this->view->entityForm = $frmEntity;
		// UserForm
		$frmUser = new UserForm();
		$frmUser->assembleRequest($this->_request);
		$this->view->userForm = $frmUser;
		// Setado para suprir funcionalidade de busca de Endereços
		$this->view->form = $frmEntity;

		// AreaForm
		$frmArea = new AreaForm();
		$frmArea->assembleRequest($this->_request);
		$this->view->areaForm = $frmArea;

		// ClassificationForm
		$frmClassif = new ClassificationForm();
		$frmClassif->assembleRequest($this->_request);
		$this->view->classifForm = $frmClassif;
	}
	
	/**
	 * Verifica se o usuário Logado tem permissão para exibir/alterar a entidade desejada
	 */
	private function verifyPermission($entityId)
	{
		$sessionEntityId = UserLogged::getEntityId();
		if($entityId != $sessionEntityId)
		{
			if(!UserLogged::isAdministrator())
			{
				Logger::loggerOperation($this->view->labels->invalid->access->entity.
					' \n[Entidade= '. $entityId .']');
				trigger_error($this->view->labels->invalid->access->entity, E_USER_ERROR);
			}
		}	
	}

	/**
	 * Lista todas as entidades cadastradas no sistema
	 */
	function indexAction()
	{
		$sessionEntityId = UserLogged::getEntityId();

		$isAdm = UserLogged::isAdministrator();
		if($isAdm)
		{	
			$page 	= 1;
			if($this->getRequest()->isPost() && !$this->getRequest()->getPost(EntityForm::filter()))
				$page 				= $this->getRequest()->getPost(EntityForm::page());

			$start 					= BasicController::pageToStart($page, Zend_Registry::get(TENTITYPAGE));
			$total					= EntityBusiness::count(TBL_ENTITY, ENT_ID_ENTITY);
			$entities 				= EntityBusiness::loadEntity(null, $start, Zend_Registry::get(TENTITYPAGE));
			$this->navBar($page, $total, Zend_Registry::get(TENTITYPAGE));

			foreach($entities as $entity) {
				$coordinatorMap[$entity->{ENT_ID_ENTITY}] = @UserBusiness::getCoordinator($entity->{ENT_ID_ENTITY})->current();
			}
			
			$this->view->entities = $entities;
			$this->view->coordinatorMap = $coordinatorMap; 
		}
		else
			$this->view->entities = EntityBusiness::loadEntity($sessionEntityId);
		$this->view->isAdm = $isAdm;
	}
	
	/**
	 * Exibe formulário para criação de uma nova entidade
	 */
	function newAction()
	{
		;
	}
	
	/**
	 * Exibe formulário para edição de uma entidade
	 */
	function viewAction()
	{
		$entityFrm 	= $this->view->entityForm;
		
		$errorMessages = null;
		EntityValidator::validateEntityId($entityFrm, $errorMessages);
		
		$isAdm = UserLogged::isAdministrator();
		$this->view->isAdm = $isAdm;
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;
			// Id de Entidade do usuário logado
			$sessionEntityId = UserLogged::getEntityId();
			
			if($isAdm)
			{
				$page 	= 1;
				if($this->getRequest()->isPost() && !$this->getRequest()->getPost(SearchAddressForm::filter()))
					$page 				= $this->getRequest()->getPost(SearchAddressForm::page());
	
				$start 					= BasicController::pageToStart($page, Zend_Registry::get(TENTITYPAGE));
				$total					= EntityBusiness::count(TBL_ENTITY, ENT_ID_ENTITY);
				$entities 				= EntityBusiness::loadEntity(null, $start, Zend_Registry::get(TENTITYPAGE));
				$this->navBar($page, $total, Zend_Registry::get(TENTITYPAGE));
	
				foreach($entities as $entity)
					$coordinatorMap[$entity->{ENT_ID_ENTITY}] = UserBusiness::getCoordinator($entity->{ENT_ID_ENTITY})->current();
				
				$this->view->entities = $entities;
				$this->view->coordinatorMap = $coordinatorMap; 
			}
			else
				$this->view->entities = EntityBusiness::loadEntity($sessionEntityId);

			return;
		}
		$id = $entityFrm->getId();
		if(is_array($id))
			$entityId = $id[0];
		else
			$entityId = $id;
		
		$this->verifyPermission($entityId);
		
		if(!empty($entityId))
			$this->view->entities = EntityBusiness::load($entityId);
		else
			$this->view->entities = NULL;
		
		$this->view->entityUser = UserBusiness::getCoordinator($entityId)->current();
		/**
		 * Carrega informações dos combos de area de atuação e classificação da entidade 
		 * somente se o usuário logado form o administrador
		**/
		if($isAdm)
		{
			Zend_Loader::loadClass('GroupBusiness');
			$this->view->allAreas = AreaBusiness::loadAllEnable();
			$this->view->allClassifications = ClassificationBusiness::loadAllEnable();
			$this->view->groups = GroupBusiness::loadAllNotDisable();
			
			//carrega todos os tipos de programas disponíveis na rede
			$this->view->allTypeProgram = ProgramBusiness::loadAllTypeProgram();
		}
		
		$this->view->phoneTypes = TelephoneBusiness::loadAllType();
	}
		
	/**
	 * Salva nova entidade (cadastro)
	 */
	function addAction()
	{
		;
	}
	
	/**
	 * Salva entidade (edição)
	 */
	function editAction()
	{
		Zend_Loader::loadClass('GroupBusiness');
		$userFrm 	= $this->view->userForm;
		$classifFrm = $this->view->classifForm;
		$entityFrm 	= $this->view->entityForm;
		$areaFrm 	= $this->view->areaForm;
		
		$entityId = $entityFrm->getId();
		
		$this->verifyPermission($entityId);
		
		$errorMessages = null;
		UserValidator::validateUserData($userFrm, $errorMessages);
		// mudança na validação
		UserValidator::validateUserId($userFrm, $errorMessages);
		if(strlen($userFrm->getFlagPassword()) > 0)
			UserValidator::validateUserPassword($userFrm, $errorMessages);
		EntityValidator::validateEntityData($entityFrm, $errorMessages);
		EntityValidator::validateGroupEntity($entityFrm, $errorMessages);
		if(UserLogged::isAdministrator())
		{
			EntityValidator::validateAreaId($areaFrm, $errorMessages);
			EntityValidator::validateClassificationId($classifFrm, $errorMessages);
		}

		//busca na base de dados todos os programas associados a um entidade
		$collProgramByEntity = ProgramBusiness::loadProgram($entityId);
		if($collProgramByEntity != null)
			EntityValidator::verifyIfProgramIsUsed($entityFrm, $errorMessages, $collProgramByEntity);
		
		$isAdm = UserLogged::isAdministrator();
		$this->view->isAdm = $isAdm;
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			
			if(!empty($entityId))
			{
				$entity = EntityBusiness::load($entityId);
				$this->view->entities = $entity;
				if($entity->{ENT_ID_ADDRESS} != $this->view->entityForm->getAdrIdAddress()) 
					$this->view->address = AddressBusiness::load($entityFrm->getAdrIdAddress())->current();
			}
			else
				$this->view->entities = NULL;
			
			$date = UserBusiness::getCoordinator($entityId)->current()->{AUTH_CREATION_DATE_USER};
			$year = substr($date,0,4);
			$month = substr(substr($date, 5), 0, 2);
			$day = substr($date, 8, 10);
			$this->view->userForm->setCreationDate($day.'/'.$month.'/'.$year);
			
			/**
			 * Carrega informações dos combos de area de atuação, classificação da entidade e programas da rede 
			 * somente se o usuário logado form o administrador
			**/
			if($isAdm)
			{
				$this->view->allAreas = AreaBusiness::loadAllEnable();
				$this->view->allClassifications = ClassificationBusiness::loadAllEnable();
				$this->view->groups = GroupBusiness::loadAllNotDisable();
				
				//carrega todos os tipos de programas disponíveis na rede
				$this->view->allTypeProgram = ProgramBusiness::loadAllTypeProgram();
			}
			$this->view->phoneTypes = TelephoneBusiness::loadAllType();

			return;
		}
		
		try
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$affectedentities 	= EntityBusiness::update(self::assembleEntity($entityFrm), $db);
			$affectedUsers 		= UserBusiness::update(self::assembleUser($userFrm), $db);

			TelephoneBusiness::dropEntityPhoneByEntity($entityId, $db);
			TelephoneBusiness::drop($entityFrm->getPhoneId(), $db);
			TelephoneBusiness::saveAllEntityPhones(self::assemblePhone($entityFrm), $entityId, $db);
			
			if($isAdm)
			{
				AreaBusiness::deleteAreaByEntity($entityId, $db);
				AreaBusiness::saveAll(self::assembleArea($areaFrm, $entityId), $db);
		
				ClassificationBusiness::deleteClassificationByEntity($entityId, $db);
				ClassificationBusiness::saveAll(self::assembleClassification($classifFrm, $entityId), $db);
				
				GroupBusiness::dropGroupEntityByEntity($entityId, $db);
				GroupBusiness::saveAllGroupEntity($this->assembleGroup($entityFrm, $entityId), $db);
				
				if($entityFrm->getProgramId() != null && $collProgramByEntity != null)
				{	
					if(sizeof($collProgramByEntity) > 0)
					{
						foreach($collProgramByEntity as $pr)
						{
							//armazena no array todos os "ids" de "tipo de programa" carregados do banco de dados 
							$arrayProgramTypeOfBD[] = $pr->{PGR_ID_PROGRAM_TYPE};	
						}
						
						//remove valores duplicados do array retornado do banco de dados
						$uniqueValuesArray = array_unique($arrayProgramTypeOfBD); 
						
						$diffDrop = array_diff($uniqueValuesArray, $entityFrm->getProgramId());
						if(sizeof($diffDrop) > 0)
						{
							foreach($diffDrop as $unPr)
							{	
								ProgramBusiness::dropProgramWhileNotUsed($entityFrm->getId(), $unPr, $db);
							}
						}
						
						$diffSave = array_diff($entityFrm->getProgramId(), $uniqueValuesArray);
						if(sizeof($diffSave) > 0)
						{	
							$program = array();	
							$program[PGR_ID_ENTITY]	= $entityFrm->getId();
								
							foreach($diffSave as $uniqueProgram)
							{
							 	$program[PGR_ID_PROGRAM_TYPE] = $uniqueProgram;
							 	
								ProgramBusiness::saveProgram($program, $db);
							}
						}
					}
					else
					{
						if(sizeof($entityFrm->getProgramId()) > 0)
						{						
							$progr = array();	
							$progr[PGR_ID_ENTITY]	= $entityFrm->getId();
								
							foreach($entityFrm->getProgramId() as $uniqueProgram)
							{
							 	$progr[PGR_ID_PROGRAM_TYPE] = $uniqueProgram;
							 	
								ProgramBusiness::saveProgram($progr, $db);
							}
						}
					}
				}
			}
			$db->commit();
			$db->closeConnection();
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e);
			trigger_error(parent::getLabelResources()->entity->save->fail, E_USER_ERROR);
		}

		$this->_redirect(ENTITY_CONTROLLER . SUCCESS_CONTROLLER);
	}
	
	/**
	 * Habilita uma Entidade
	 */
	function enableAction()
	{
		$form = $this->view->entityForm;

		$isAdm = UserLogged::isAdministrator();
		if(!$isAdm)
		{
			Logger::loggerOperation($this->view->labels->invalid->access->operation);
			trigger_error($this->view->labels->invalid->access->operation, E_USER_ERROR);
		}
		$errorMessages = null;
		EntityValidator::validateEntityId($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;

			$sessionEntityId = UserLogged::getEntityId();
			if($isAdm)
			{
				$page 	= 1;
				if($this->getRequest()->isPost() && !$this->getRequest()->getPost(SearchAddressForm::filter()))
					$page 				= $this->getRequest()->getPost(SearchAddressForm::page());
	
				$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
				$total					= EntityBusiness::count(TBL_ENTITY, ENT_ID_ENTITY);
				$entities 				= EntityBusiness::loadEntity(null, $start, Zend_Registry::get(TPAGE));
				$this->navBar($page, $total, Zend_Registry::get(TPAGE));
	
				foreach($entities as $entity)
					$coordinatorMap[$entity->{ENT_ID_ENTITY}] = UserBusiness::getCoordinator($entity->{ENT_ID_ENTITY})->current();
				
				$this->view->entities = $entities;
				$this->view->coordinatorMap = $coordinatorMap; 
			}
			else
				$this->view->entities = EntityBusiness::loadEntity($sessionEntityId);
			$this->view->isAdm = $isAdm;

			//retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		EntityBusiness::updateAll($this->assembleEntityStatus($form, Constants::ONE));
		
		$this->_redirect(ENTITY_CONTROLLER.'/'.DEFAULT_INDEX_ACTION);
	}
	
	/**
	 * Desabilita uma Entidade
	 */
	function disableAction()
	{
		$form = $this->view->entityForm;

		$isAdm = UserLogged::isAdministrator();
		if(!$isAdm)
		{
			Logger::loggerOperation($this->view->labels->invalid->access->operation);
			trigger_error($this->view->labels->invalid->access->operation, E_USER_ERROR);
		}
		
		$errorMessages = null;
		EntityValidator::validateEntityId($form, $errorMessages);
		if(sizeof($errorMessages) > 0)
		{
			//carrega variável com a(s) mensagem(ens) de erro de validação
			$this->view->errorMessages = $errorMessages;

			$sessionEntityId = UserLogged::getEntityId();
			if($isAdm)
			{
				$page 	= 1;
				if($this->getRequest()->isPost() && !$this->getRequest()->getPost(SearchAddressForm::filter()))
					$page 				= $this->getRequest()->getPost(SearchAddressForm::page());
	
				$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
				$total					= EntityBusiness::count(TBL_ENTITY, ENT_ID_ENTITY);
				$entities 				= EntityBusiness::loadEntity(null, $start, Zend_Registry::get(TPAGE));
				$this->navBar($page, $total, Zend_Registry::get(TPAGE));
	
				foreach($entities as $entity)
					$coordinatorMap[$entity->{ENT_ID_ENTITY}] = UserBusiness::getCoordinator($entity->{ENT_ID_ENTITY})->current();
				
				$this->view->entities = $entities;
				$this->view->coordinatorMap = $coordinatorMap; 
			}
			else
				$this->view->entities = EntityBusiness::loadEntity($sessionEntityId);
			$this->view->isAdm = $isAdm;

			//retorna para o template atual exibindo as mensagens de validação
			return;
		}
		
		EntityBusiness::updateAll($this->assembleEntityStatus($form, Constants::ZERO));
		
		$this->_redirect(ENTITY_CONTROLLER.'/'.DEFAULT_INDEX_ACTION);
	}

	//redireciona aplicação para tela de sucesso do respectivo controller
	function successAction()
	{
		;
	}
	
	private function assembleEntityStatus(EntityForm $form, $status)
	{
		if(!empty($form))
		{
			$entities = NULL;
			if(is_array($form->getId()))
			{
				foreach($form->getId() as $id)
				{
					$entity[ENT_ID_ENTITY] = $id;
					$entity[ENT_STATUS] = $status;
					$entities[]= $entity;
				}
			}
			else
			{
				$entity[ENT_ID_ENTITY] = $form->getId();
				$entity[ENT_STATUS] = $status;
				$entities[]= $entity;
			}
			return $entities;
		}
		return NULL;
	}

	private function assemblePhone(EntityForm $form)
	{
		if(!empty($form))
		{
			$phoneId= self::getValue($form->getPhoneId());
			$number = self::getValue($form->getPhoneNumber());
			$type 	= self::getValue($form->getPhoneType());
			$code 	= self::getValue($form->getPhoneCodeArea());
			if(count($number) == count($type) && count($type) == count($code))
			{
				for($i = 0; $i < count($number); $i++)
				{
					$phone[TNB_ID_TELEPHONE_NUMBER]	= $phoneId[$i];
					$phone[TNB_NUMBER]				= $number[$i];
					$phone[TNB_ID_TELEPHONE_TYPE]	= $type[$i];
					$phone[TNB_DDD]					= $code[$i];
					$phones[] = $phone;
				}
			}
			
			return $phones;
		}
		return null;
	}

	/**
	 * Popula e retorna um array que representa uma entidade
	 */
	private function assembleEntity(EntityForm $form)
	{
		if(!empty($form))
		{
			$entity[ENT_ID_ENTITY]	= self::getValue($form->getId());
			$entity[ENT_NAME]		= self::getValue($form->getEntityName());
			$entity[ENT_ID_ADDRESS]	= self::getValue($form->getAdrIdAddress());
			$entity[ENT_NUMBER]		= self::getValue($form->getAdrNumber());
			$entity[ENT_COMPLEMENT]	= self::getValue($form->getAdrComplement());
			$entity[ENT_EMAIL]		= self::getValue($form->getEmail());
			$entity[ENT_HOMEPAGE]	= self::getValue($form->getHomePage());
			return $entity;
		}
		return null;
	}

	private function assembleUser(UserForm $form)
	{
		if(!empty($form))
		{
			$user[AUTH_ID_USER]		= self::getValue($form->getId());
			$user[AUTH_NAME_USER]	= self::getValue($form->getName());
			$user[AUTH_CPF_USER]	= self::getValue($form->getCpf());
			$user[AUTH_LOGIN_USER]	= self::getValue($form->getLogin());
			// Altera o password somente se foi digitado um novo valor no campo
			if(self::getValue($form->getPassword()) !== NULL)
				$user[AUTH_PASSWORD_USER] = md5($form->getPassword());

			$user[AUTH_EMAIL_USER]	= self::getValue($form->getEmail());
//			$user[AUTH_ACTIVE_USER]	= self::getValue($form->getActive());
			return $user;
		}
		return NULL;
	}
	
	private function assembleArea(AreaForm $form, $entityId)
	{
		$areas = NULL;
		if(!empty($form))
		{
			foreach($form->getId() as $areaId)
			{
				$area[ETA_ID_ENTITY] 		= $entityId;
				$area[ETA_ID_ENTITY_AREA] 	= $areaId;
				if($areaId != false && $entityId != false)
					$areas[] = $area;
			}
		}
		return $areas;
	}

	private function assembleClassification(ClassificationForm $form, $entityId)
	{
		$classifications = NULL;
		if(!empty($form))
		{
			foreach($form->getId() as $classificationId)
			{
				$classif[ENC_ID_ENTITY] 				= $entityId;
				$classif[ENC_ID_ENTITY_CLASSIFICATION] 	= $classificationId;
				if($entityId != false && $classificationId != false)
					$classifications[] = $classif; 
			}
		}
		return $classifications;
	}
	
	/**
	 * Recupera as informações do form e retorna no array
	 */	
	function assembleFormToBean(EntityForm $entityForm, UserForm $userForm)
	{	
		if(!Utils::isEmpty($entityForm) && !Utils::isEmpty($userForm))
		{
			$bean = array();
			
			$bean[0] = $entityForm->getEntityName();
			$bean[1] = $userForm->getName();
			$bean[2] = $userForm->getCpf();
			$bean[3] = $userForm->getLogin();
			$bean[4] = $userForm->getPassword();
			$bean[5] = $userForm->getEmail();	
					
			return $bean;
		}
		
		return null;		
	}
	/**
	 * Recupera as informações do form e retorna no array
	 */	
	function assembleGroup(EntityForm $entityForm, $entityId)
	{	
		if(!Utils::isEmpty($entityForm) && !Utils::isEmpty($entityId))
		{
			Zend_Loader::loadClass('GroupEntity');
			$groupArray = null;
			foreach($entityForm->getGroupEntity() as $group)
			{
				$data[AGE_ID_GROUP]	= $group;
				$data[AGE_ID_ENTITY]= $entityId;
				if($entityId != false)
					$groupArray[] = $data; 
			}
			return $groupArray;
		}
		
		return null;		
	}
	
	private function getValue($value, $default = NULL)
	{
		if(Utils::isEmpty($value))
			return $default;
		else
			return $value;
	}
}
