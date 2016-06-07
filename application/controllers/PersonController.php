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
 * Saulo Esteves Rodrigues  - W3S		   			09/01/2008	                       Create file 
 * 
 */

require_once('BasicController.php');

class PersonController extends BasicController
{
	/**
	 * Inicialização
	 * 
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Person');		
		parent::setControllerHelp('Person');
		
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass(CLS_CTPS);
		Zend_Loader::loadClass(CLS_CIVILCERTIFICATE);
		Zend_Loader::loadClass(CLS_DEFICIENCY);
		Zend_Loader::loadClass(CLS_PERSONINSERTBYUSER);
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('RaceBusiness');
		Zend_Loader::loadClass('DeficiencyBusiness');
		Zend_Loader::loadClass('MaritalStatusBusiness');
		Zend_Loader::loadClass('NationalityBusiness');
		Zend_Loader::loadClass('UFBusiness');
		Zend_Loader::loadClass('DocumentBusiness');
		Zend_Loader::loadClass('CtpsBusiness'); 
		Zend_Loader::loadClass('CivilCertificateBusiness');
		Zend_Loader::loadClass('AddressTemporaryBusiness');
		Zend_Loader::loadClass('MetaPhoneClass');
		Zend_Loader::loadClass('PersonForm');
		Zend_Loader::loadClass('PersonValidator');
		Zend_Loader::loadClass('PersonAddressTemp');
		Zend_Loader::loadClass('LevelInstructionBusiness');
		Zend_Loader::loadClass('HealthBusiness');
		Zend_Loader::loadClass('EntityBusiness');
		Zend_Loader::loadClass('ProgramBusiness');
		Zend_Loader::loadClass('AssistanceBusiness');
		Zend_Loader::loadClass('ResidenceBusiness');
		Zend_Loader::loadClass('FamilyBusiness');
		Zend_Loader::loadClass('ResidenceForm');
		Zend_Loader::loadClass('ResourcePermission');
		Zend_Loader::loadClass('CoverageAddress');
				
		$frm				= new PersonForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
		
		$this->view->races 		= RaceBusiness::loadAll();
		$this->view->marital 	= MaritalStatusBusiness::loadAll();
		$this->view->deficiency = DeficiencyBusiness::loadAll();
		$this->view->uf 		= UFBusiness::loadAll();
		$this->view->nation		= NationalityBusiness::loadAll();
	}
	
	/**
	 * Exibe as informações da pessoa selecionada
	 * 
	 */
	function indexAction()
	{
		//resgata parametro id do form
		$this->view->form->setId($this->_request->getParam($this->view->form->id()));
		
		$errorMessages = PersonValidator::validatePersonId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		if($this->view->form->getId())
		{
			$this->view->form->setId($this->view->form->getId());
			
			// resgata do banco a pessoa de acordo com o id			
			$resPerson = PersonBusiness::load($this->view->form->getId());
			
			// seta as informações da pessoa no form
			PersonForm::assemblePersonToForm($resPerson);
			
			// resgata do banco a deficiência de pessoa de acordo com o id			
			$resDeficinecy = DeficiencyBusiness::load($this->view->form->getId());
						
			// seta as informações da deficiência no form
			PersonForm::assembleDeficiencyToForm($resDeficinecy);
			
			// resgata do banco os documentos de acordo com o id da pessoa
			$resDocument = DocumentBusiness::load($this->view->form->getId());
			
			// seta as informações de documento no form
			PersonForm::assembleDocumentToForm($resDocument);
			
			// resgata do banco os dados da carteira de trabalho de acordo com o id da pessoa
			$resCtps = CtpsBusiness::load($this->view->form->getId());
			
			// seta as informações de carteira de trabalho no form
			PersonForm::assembleCtpsToForm($resCtps);

			// resgata do banco a certidão de acordo com o id da pessoa
			$resCivilCertificate = CivilCertificateBusiness::load($this->view->form->getId());
			
			// seta as informações de certidão no form
			PersonForm::assembleCivilCertificateToForm($resCivilCertificate);
			
			//funcionalidade somente-leitura?
			if(ResourcePermission::isResourceReadOnly($this->_request))
				$this->view->readOnly = TRUE;
			else
				$this->view->readOnly = FALSE;
		}
	}
	
	function containerAction()
	{
		$this->indexAction();
	}
	
	/**
	 * Visualiza formulário para inserção
	 * 
	 */
	function newAction()
	{
		;
	}

	/**
	 * Inserir um novo registro em endereço temporário
	 * 
	 */
	function addAddressTempAction() 
	{
//		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
//		if($readOnly)
//		{
//			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
//		}
//		else
//		{		
//			$errorMessages = PersonValidator::validateTempAddress($this->view->form);
//			if(sizeof($errorMessages) > 0)
//			{
//				$this->view->errorMessages = $errorMessages;				
//				return;
//			}
//		}
	}
	
	/**
	 * Edita um registro de endereço temporário
	 * 
	 */
	function editAddressTempAction() 
	{
//		$readOnly = ResourcePermission::isResourceReadOnly($this->_request);
//		if($readOnly)
//		{
//			trigger_error(BasicBusiness::getLabelResources()->notPermission, E_USER_ERROR);
//		}
//		else
//		{	
//			
//			
//			$this->_redirect(PERSON_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.PersonForm::id().'/'.$this->view->form->getPersonId());
//		}
	}

	/**
	 * Validar e inserir um novo registro
	 * 
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
			$errorMessages = PersonValidator::validatePersonDataForAdd($this->view->form);
			PersonValidator::validateTempAddress($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				$this->view->errorMessages = $errorMessages;				
				return;
			}		
			
			// retorna um array com as informações de pessoa
			$person = PersonForm::assembleFormToPerson($this->view->form);
			
			// retorna metafones para nome e apelido
			$person[PRS_METANAME] = MetaPhoneClass::getMetaPhone($person[PRS_NAME]);
			$person[PRS_METANICKNAME] = MetaPhoneClass::getMetaPhone($person[PRS_NICKNAME]);
			
			// persiste pessoa e retorna seu id
			$idPerson = PersonBusiness::save($person);
			
			if($this->view->form->getAdrIdAddress() && strlen($this->view->form->getAdrIdAddress()) > 0)
			{
				//retorna um array com as informações de endereço temporário
				$addressTemp = PersonForm::assembleFormToTempAddress($this->view->form);
				if($idPerson && strlen($idPerson) > 0)
				{
					$addressTemp[PAT_ID_PERSON]	= $idPerson;
					AddressTemporaryBusiness::save($addressTemp);
				}
				else
				{
					trigger_error(BasicBusiness::getLabelResources()->notIdPersonAddressTemporary->save->fail, E_USER_ERROR);
				}
			}
			
			if(is_array($this->view->form->getDeficiency()))
			{
				// retorn um array com as informações de deficiência
				$deficiency = PersonForm::assembleFormToDeficiency($this->view->form);
				
				// seta o id de pessoa no array de deficiências
				$deficiency[DFY_ID_PERSON] = $idPerson;
				
				// resgata id de deficiência e persite relação entre pessoa de deficiência 
				$deficiencyType = $deficiency[DFY_ID_DEFICIENCY];
				foreach($deficiencyType as $dfy){
					$deficiency[DFY_ID_DEFICIENCY] = $dfy;
					DeficiencyBusiness::save($deficiency);
				}
			}	
			
			$this->_redirect(PERSON_CONTROLLER.'/'.DEFAULT_INDEX_ACTION.'/'.PersonForm::id().'/'.$idPerson);
		}
	}
	
	/**
	 * Visualiza registro para edição
	 * 
	 */
	function viewAction()
	{
		//resgata parametro id do form
		$this->view->form->setId($this->_request->getParam($this->view->form->id()));
		
		$errorMessages = PersonValidator::validatePersonId($this->view->form);
		if(sizeof($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			return;
		}
		if($this->view->form->getId())
		{
			$this->view->form->setId($this->view->form->getId());
			
			// resgata do banco a pessoa de acordo com o id			
			$resPerson = PersonBusiness::load($this->view->form->getId());
			
			// seta as informações da pessoa no form
			PersonForm::assemblePersonToForm($resPerson);
			
			// resgata do banco a deficiência de pessoa de acordo com o id			
			$resDeficinecy = DeficiencyBusiness::load($this->view->form->getId());
						
			// seta as informações da deficiência no form
			PersonForm::assembleDeficiencyToForm($resDeficinecy);
			
			// resgata do banco os documentos de acordo com o id da pessoa
			$resDocument = DocumentBusiness::load($this->view->form->getId());
			
			// seta as informações de documento no form
			PersonForm::assembleDocumentToForm($resDocument);
			
			// resgata do banco os dados da carteira de trabalho de acordo com o id da pessoa
			$resCtps = CtpsBusiness::load($this->view->form->getId());
			
			// seta as informações de carteira de trabalho no form
			PersonForm::assembleCtpsToForm($resCtps);

			// resgata do banco a certidão de acordo com o id da pessoa
			$resCivilCertificate = CivilCertificateBusiness::load($this->view->form->getId());
			
			// seta as informações de certidão no form
			PersonForm::assembleCivilCertificateToForm($resCivilCertificate);
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
	 * Valida e persiste as alterações do registro
	 * 
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
			$errorMessages = PersonValidator::validatePersonDataForEdit($this->view->form);
			PersonValidator::validateTempAddress($this->view->form, $errorMessages);
			
			if(sizeof($errorMessages) > 0)
			{
				$this->view->errorMessages = $errorMessages;
				return;
			}
			
			// retorna um array com as informações de pessoa
			$person = PersonForm::assembleFormToPerson($this->view->form);
			
			// retorna metafones para nome e apelido
			$person[PRS_METANAME] = MetaPhoneClass::getMetaPhone($person[PRS_NAME]);
			$person[PRS_METANICKNAME] = MetaPhoneClass::getMetaPhone($person[PRS_NICKNAME]);
			
			// persiste pessoa 
			PersonBusiness::save($person);
			
			// seta id de pessoa
			$idPerson = $person[PRS_ID_PERSON];
			
			if($this->view->form->getAdrIdAddress() && strlen($this->view->form->getAdrIdAddress()) > 0)
			{
				//retorna um array com as informações de endereço temporário
				$addressTemp = PersonForm::assembleFormToTempAddress($this->view->form);
				if($idPerson && strlen($idPerson) > 0)
				{
					$addressTemp[PAT_ID_PERSON]	= $idPerson;
					AddressTemporaryBusiness::save($addressTemp);
				}
				else
				{
					trigger_error(BasicBusiness::getLabelResources()->notIdPersonAddressTemporary->save->fail, E_USER_ERROR);
				}
			}
			
			if(is_array($this->view->form->getDeficiency()))
			{
				// retorn um array com as informações de deficiência
				$deficiency = PersonForm::assembleFormToDeficiency($this->view->form);
				
				// seta o id de pessoa no array de deficiências
				$deficiency[DFY_ID_PERSON] = $idPerson;
				
				// resgata id de deficiência e persite relação entre pessoa de deficiência 
				$deficiencyType = $deficiency[DFY_ID_DEFICIENCY];
				foreach($deficiencyType as $dfy){
					$deficiency[DFY_ID_DEFICIENCY] = $dfy;
					DeficiencyBusiness::save($deficiency);
				}
			}
			else
			{					
				// exclui relação entre pessoa e deficiência
				DeficiencyBusiness::drop($idPerson);
			}	
			
			// retorna um array com as informações de documento
			$document = PersonForm::assembleFormToDocument($this->view->form);
		
			// seta id de pessoa em documento
			$document[DOC_ID_PERSON] = $idPerson;
			
			// persiste documento
			DocumentBusiness::save($document);
			
			
			// retorna um array com as informações de ctps
			$ctps = PersonForm::assembleFormToCtps($this->view->form);
					
			// seta id de pessoa em ctps
			$ctps[CTS_ID_PERSON] = $idPerson;
			
			// persiste ctps
			CtpsBusiness::save($ctps);
					
			// retorna um array com as informações de certidão
			$civil = PersonForm::assembleFormToCivilCertificate($this->view->form);
					
			// seta id de pessoa em certidão
			$civil[CCF_ID_PERSON] = $idPerson;
			
			// persiste certidão
			CivilCertificateBusiness::save($civil);
			
			$this->_redirect(PERSON_CONTROLLER.'/'.DEFAULT_SUCCESS_ACTION.'/'.PersonForm::id().'/'.$idPerson);
		}
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
	
	function containerMainAction()
	{
		parent::setControllerResources('Container');	
		$this->view->form->setPersonId($this->view->form->getPersonId());		
		
		if(ResourcePermission::isAllowResource(PERSON_CONTROLLER))
		{
			$show = Zend_Registry::get(CONFIG)->container->identification;
			if($show)
			{	
				$resPerson = PersonBusiness::load($this->view->form->getPersonId());
				PersonForm::assemblePersonToForm($resPerson);
			}
		}
		
		if(ResourcePermission::isAllowResource(EDUCATION_CONTROLLER))
		{
			$show = Zend_Registry::get(CONFIG)->container->education;
			if($show)
			{	
				$this->view->levelInstruction = LevelInstructionBusiness::loadLevelInstructionByPerson($this->view->form->getPersonId());
			}
		}
		
		if(ResourcePermission::isAllowResource(HEALTH_CONTROLLER))
		{
			$show = Zend_Registry::get(CONFIG)->container->health;
			if($show)
			{	
				$this->view->healthByPerson = HealthBusiness::loadHealthByPerson($this->view->form->getPersonId());
				$this->view->pregnancyByPerson = HealthBusiness::loadPregnancyByPerson($this->view->form->getPersonId());
			}
		}
		
		if(ResourcePermission::isAllowResource(INCOME_CONTROLLER))
		{
			$show = Zend_Registry::get(CONFIG)->container->income;
			if($show)
			{	
				$this->view->person = PersonBusiness::load($this->view->form->getPersonId());
			}
		}
		
		if(ResourcePermission::isAllowResource(PERSON_LOG_CONTROLLER))
		{
			$show = Zend_Registry::get(CONFIG)->container->personlog;
			if($show)
			{
				$this->view->personsInserted = PersonBusiness::loadPersonsInsertedByUser(UserLogged::getUserId(), 7);
			}
		}
		
		if(ResourcePermission::isAllowResource(FAMILYRELATIONSHIP_CONTROLLER))
		{
			$show = Zend_Registry::get(CONFIG)->container->family;
			if($show)
			{
				$this->view->relationshipByPerson = FamilyBusiness::loadFamilyRelationship($this->view->form->getPersonId());
			}
		}
		
		if(ResourcePermission::isAllowResource(RESIDENCE_CONTROLLER))
		{
			$show = Zend_Registry::get(CONFIG)->container->residence;
			if($show)
			{	
				$resPerson = PersonBusiness::load($this->view->form->getPersonId());
					
				foreach($resPerson as $prs){
					$family = $prs->findDependentRowset(CLS_FAMILY);
					$phone = $prs->findDependentRowset(CLS_PERSONTELEPHONE);
				}
				if(count($family) > 0)
				{					
					foreach($family as $fam)
						$familyId = $fam->findParentRow(CLS_FAMILY_ID);
					
					$familyResidence = $familyId->findDependentRowset(CLS_FAMILYRESIDENCE);
					
					foreach($familyResidence as $famResidence){
						$residence = $famResidence->findParentRow(CLS_RESIDENCE);
						
						if(!$residence->{RES_STATUS}){								
							$this->view->numberResidence = $residence->{RES_NUMBER};
							$this->view->address = $residence->findParentRow(CLS_ADDRESS);							
						}
					}
					$this->view->phone = $phone;
				}
				else
				{
					$this->view->address = true;
				}
			}
		}
		
		if(ResourcePermission::isAllowResource(ATTENDANCE_CONTROLLER))
		{
			$show = Zend_Registry::get(CONFIG)->container->lastattendance;
			if($show)
			{	
				$assistances = AssistanceBusiness::loadAllInProcessAssistance($this->view->form->getPersonId());		
				$this->view->assistances 	= AssistanceBusiness::buildAssistanceSummary($assistances);		
				$programs 					= ProgramBusiness::loadProgram(UserLogged::getEntityId());
				foreach($programs as $program) $programType[$program->{PGR_ID_PROGRAM_TYPE}] = $program->{PGR_ID_PROGRAM_TYPE};
				$this->view->programTypes 	= ProgramBusiness::loadProgramType($programType);
			}
		}
		
		if(ResourcePermission::isAllowResource(NETWORK_CONTROLLER))
		{
			$show = Zend_Registry::get(CONFIG)->container->network;
			if($show)
			{
				$this->view->entity = EntityBusiness::loadAll();
			}
		}
	}
	
}