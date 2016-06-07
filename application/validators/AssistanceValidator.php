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
 * Lucas dos Santos Borges Corrêa  - W3S		   	05/05/2008	                       Create file 
 * 
 */
require_once 'BasicValidator.php';

abstract class AssistanceValidator extends BasicValidator
{
	/**
	 * Validador de Identificação (genérico)
	 */
	public static function validateNeedProfile($frm, &$errorMessages = null)
	{		
		$values = Zend_Registry::get(CONFIG)->assistance->classification->general.';'.Zend_Registry::get(CONFIG)->assistance->classification->especial.';'.Zend_Registry::get(CONFIG)->assistance->classification->group;		
		$value = str_split($values);
		
		if(count($value) > 0)
		{ 
			foreach($value as $v)
			{
				if($v != ";")
				{					
					if(!UserLogged::isCoordinator())
					{
						if(($frm->getProgramType() == $v) && (!UserLogged::getProfiles()))
						{
							$errorMessages[AssistanceForm::programType()][][] = parent::getValidatorResources()->profile->need;
						}
					}
				}
			}		
		}
		
		return $errorMessages;
	}
	public static function validateRequiredId($value, $constant, &$errorMessages = null)
	{
		if(is_array($value))
		{
			if(sizeof($value) > 0)
			{
				foreach($value as $current)
				{
					AssistanceValidator::validateRequired($current, $constant, $errorMessages);
					AssistanceValidator::validateInt($current, $constant, $errorMessages);
				}
			}
			else
			{
				AssistanceValidator::validateRequired(null, $constant, $errorMessages);
			}
		}
		else
		{	
			AssistanceValidator::validateRequired($value, $constant, $errorMessages);			
			AssistanceValidator::validateInt($value, $constant, $errorMessages);
		}
		return $errorMessages;
	}
	/**
	 * Validação de valor requerido
	 */
	public static function validateRequired($value, $constant, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if (!$notEmpty->isValid($value))
			$errorMessages[$constant][] = $notEmpty->getMessages();
	
		return $errorMessages;
	}
	
	/**
	 * Validação para inteiro
	 */
	public static function validateInt($value, $constant, &$errorMessages = null)
	{
		$validator = parent::validatorInt();
		if(strlen($value) > 20)
		{
			$number = Utils::abbreviate($value, 21);
			if(!$validator->isValid($number))
			{
				$errorMessages[$constant][] = $validator->getMessages();
			}
		}
		else if(strlen($value) > 0 && !$validator->isValid($value))
		{
			$errorMessages[$constant][] = $validator->getMessages();
		}
		if(($constant == AssistanceForm::programType()) && is_numeric($value))
		{
			if(!is_null($value))
			{
				Zend_Loader::loadClass('ProgramBusiness');
				$row = ProgramBusiness::loadEntityProgramType(UserLogged::getEntityId(), $value);
				if(count($row) == 0)
				{
					$errorMessages[$constant][][] = parent::getValidatorResources()->programEntity->notfound;
				}
			}
		}
		if($constant == GeneralAssistanceForm::benefitType())
		{
			Zend_Loader::loadClass('AssistanceBusiness');
			$row = AssistanceBusiness::loadBenefitType($value);			
			if(count($row) == 0)
			{
				$errorMessages[$constant][][] = parent::getValidatorResources()->activity->notfound;				
			}
		}
		if($constant == EspecialAssistanceForm::officialLetter())
		{
			Zend_Loader::loadClass('AssistanceBusiness');
			$row = AssistanceBusiness::loadOfficialLetter($value);			
			if(count($row) == 0)
			{
				$errorMessages[$constant][][] = parent::getValidatorResources()->officialletter->notfound;				
			}
		}
		if($constant == EspecialAssistanceForm::lawsuit())
		{
			Zend_Loader::loadClass('AssistanceBusiness');
			$row = AssistanceBusiness::loadLawsuit($value);			
			if(count($row) == 0)
			{
				$errorMessages[$constant][][] = parent::getValidatorResources()->lawsuit->notfound;				
			}
		}
		if($constant == GeneralAssistanceForm::profileId())
		{
			Zend_Loader::loadClass('ProfileBusiness');
			$profile = UserLogged::getProfiles();
			if(count($profile) > 0)
			{
				$flag = false;
				foreach($profile as $pf)
				{
					if($value == $pf->{AUTH_ID_PROFILE})
					{
						$flag = true;
						break;
					}
				}
				if($flag === false)
				{
					$errorMessages[$constant][][] = parent::getValidatorResources()->profile->notfound;
				}
			}
			else
			{
				$errorMessages[$constant][][] = parent::getValidatorResources()->profile->need;
			}
		}				
		
		if(($constant == AssistanceForm::classId()) && is_numeric($value))
		{
			Zend_Loader::loadClass('ClassBusiness');
			Zend_Loader::loadClass(CLS_CLASSMODEL);
			$row = ClassBusiness::load($value);	
			
			if(count($row) == 0)
			{
				$errorMessages[$constant][][] = parent::getValidatorResources()->lawsuit->notfound;				
			}
		}
		return $errorMessages;
	}
	/**
	 * Validação para Booleano
	 */
	public static function validateBoolean($value, $constant, &$errorMessages = null)
	{
		$resources = parent::getValidatorResources();
		$validator = parent::validatorInt();
		if(strlen($value) > 0 && !$validator->isValid($value))
			$errorMessages[$constant][] = $validator->getMessages();
		elseif($value !== 0 && $value !=='0' && $value !== 1 && $value !== '1')
			$errorMessages[$constant][][][] = $resources->boolean->invalid;

		return $errorMessages;
	}
	/**
	 * Validação de Tamanho de string
	 */
	public static function validateStringLength($value, $constant, $min=0, $max=null, &$errorMessages = null)
	{
		$stringLenght = parent::validatorStringLength($min, $max);
		if(strlen($value) > $max)
		{
			$comment = Utils::abbreviate($value, 21);
			$errorMessages[$constant][][] = parent::getValidatorResources()->text->long1.$comment.parent::getValidatorResources()->text->long2.$max.parent::getValidatorResources()->text->long3;			
		}
		else if (!$stringLenght->isValid($value))
		{
			$errorMessages[$constant][] = $stringLenght->getMessages();
		}
		return $errorMessages;
	}
	/**
	 * Validação de Data
	 */
	public static function validateDate($value, $constant, &$errorMessages = null)
	{
		$dateFormat = BasicForm::dateFormat($value);
		if($constant == AssistanceForm::endDate() && !empty($value))
		{	
			if(date('Y-m-d') > $dateFormat)
			{
				$errorMessages[$constant][][] = parent::getValidatorResources()->date->endDate;
			}
		}
		$validator = parent::validatorDate();			
		if (!$validator->isValid($dateFormat))
		{
			$errorMessages[$constant][] = $validator->getMessages();
		}			

		return $errorMessages;
	}
	//------------------------------ Validação 
	/**
	 * Valida Pessoa a ser atendida
	 */
	public static function validatePerson(AssistanceForm &$frm, &$errorMessages = null)
	{
		AssistanceValidator::validateRequiredId($frm->getPersonId(), $frm->personId(), $errorMessages);
		$person = $frm->getPersonId();
		if(strlen($frm->getPersonId()) > 0 && !empty($person))
		{
			Zend_Loader::loadClass(CLS_PERSON);
			Zend_Loader::loadClass('PersonBusiness');
			$obj = PersonBusiness::load($person)->current();
			$id = $obj->{PRS_ID_PERSON};
			if(empty($obj) || empty($id))
			{
				$resources = parent::getValidatorResources();
				$errorMessages[$frm->personId()][][] = $resources->person->invalid;
			}
		}
	}

	/**
	 * Valida a Identificação do atendimento
	 */
	public static function validateAssistanceId(&$frm, &$errorMessages = null)
	{		
		AssistanceValidator::validateRequiredId($frm->getAssistanceId(), $frm->assistanceId(), $errorMessages);
		
		return $errorMessages;
	}
	
	/**
	 * Valida exibição de atendimento
	 */
	public static function validateViewAssistance(AssistanceForm &$frm, &$errorMessages = null)
	{
		/* Valida Id da pessoa */
		AssistanceValidator::validatePerson($frm, $errorMessages);
		AssistanceValidator::validateUserLoggedEntity($errorMessages);
	}

	/**
	 * Valida exibição de atendimento do tipo grupo
	 */
	public static function validateViewGroupAssistance(AssistanceForm &$frm, &$errorMessages = null)
	{
		/* Valida Id da pessoa */
		AssistanceValidator::validatePerson($frm, $errorMessages);		
		AssistanceValidator::validateRequiredId($frm->getAssistanceId(), $frm->personId(), $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getProgramType(), $frm->programType(), $errorMessages);		
	}

	/**
	 * Valida novo atendimento
	 */
	public static function validateNewAssistance(AssistanceForm &$frm, &$errorMessages = null)
	{
		/* Valida Id da pessoa */
		AssistanceValidator::validatePerson($frm, $errorMessages);
		/* Valida Id tipo do programa */
		if($frm->getProgramType() == "")
			$frm->setProgramType(null);		
		AssistanceValidator::validateInt($frm->getProgramType(), $frm->programType(), $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getProgramType(), $frm->programType(), $errorMessages);		
		AssistanceValidator::validateUserLoggedEntity($errorMessages);
	}
	
	/**
	 * Valida atendimento basico (comum a todos os atendimentos)
	 */
	public static function validateBasicAssistance(AssistanceForm &$frm, &$errorMessages = null)
	{
		AssistanceValidator::validatePerson($frm, $errorMessages);
		AssistanceValidator::validateRequired($frm->getProgramType(), $frm->programType(), $errorMessages);
		AssistanceValidator::validateNeedProfile($frm, $errorMessages);
		AssistanceValidator::validateBoolean($frm->getConfidentiality(), $frm->confidentiality(), $errorMessages);
		AssistanceValidator::validateDate($frm->getEndDate(), $frm->endDate(), $errorMessages);
		AssistanceValidator::validateUserLoggedEntity($errorMessages);
		try
		{
			$entityId = UserLogged::getEntityId();
		}
		catch(UserNotLoggedException $e)
		{
			$labelResources = Zend_Registry::get(LABEL_RESOURCES);
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$labelResources->notPermission.' - '.$e);
			trigger_error($labelResources->notPermission , E_USER_ERROR);
		}			

		if($entityId != null)
		{
			Zend_Loader::loadClass(CLS_PROGRAM);
			$resources = parent::getValidatorResources();
			$data[PGR_ID_ENTITY.' = ?'] = $entityId;
			$data[PGR_ID_PROGRAM_TYPE.' = ?'] = $frm->getProgramType();
			$program = ProgramBusiness::loadByQuery($data)->current();
			if(!$program || count($program) == 0) 
				$errorMessages[$frm->programType()][][]= $resources->attendance->error->invalid->programType;
		}
	}

	/**
	 * Valida atendimento geral
	 */
	public static function validateGeneralAssistance(GeneralAssistanceForm &$frm, Zend_Controller_Request_Http $request, &$errorMessages = null)
	{
		AssistanceValidator::validatePerson($frm, $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getAssistanceId(), $frm->assistanceId(), $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getBenefitType(), $frm->benefitType(), $errorMessages);

		$allowChangeConfidentiality = ResourcePermission::allowChangeDefaultConfidentiality($request);
		if($allowChangeConfidentiality)
		{
			AssistanceValidator::validateRequiredId($frm->getConfidentialityLevel(), $frm->confidentialityLevel(), $errorMessages);
			
			if($frm->getConfidentialityLevel() == Constants::VISIBILITY_PROFILE)
			{
				AssistanceValidator::validateRequiredId($frm->getProfileId(), $frm->profileId(), $errorMessages);
			}
		}
		
		/* Validação de comentário do atendimento geral (1024 caracteres)*/
		AssistanceValidator::validateRequired($frm->getComments(), $frm->comments(), $errorMessages);
		AssistanceValidator::validateStringLength($frm->getComments(), $frm->comments(), 0, 200, $errorMessages);
		$validatorWord = parent::validatorWords($frm->getComments());
		if(!$validatorWord->isValid($frm->getComments()))
		{
			$errorMessages[$frm->comments()][] = $validatorWord->getMessages();
		}
	}

	/**
	 * Valida atendimento Especial
	 */
	public static function validateEspecialAssistance(EspecialAssistanceForm &$frm, &$errorMessages = null)
	{
		AssistanceValidator::validatePerson($frm, $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getAssistanceId(), $frm->assistanceId(), $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getOfficialLetter(), $frm->officialLetter(), $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getLawsuit(), $frm->lawsuit(), $errorMessages);
		AssistanceValidator::validateInt($frm->getOfficialLetterNumber(), $frm->officialLetterNumber(), $errorMessages);
		AssistanceValidator::validateInt($frm->getOfficialLetterYear(), $frm->OfficialLetterYear(), $errorMessages);
		// Valida somente se foi digitado algo no campo
		if(strlen($frm->getOfficialLetterYear()) > 0)
		AssistanceValidator::validateStringLength($frm->getOfficialLetterYear(), $frm->OfficialLetterYear(), 4, 4, $errorMessages);
		AssistanceValidator::validateInt($frm->getLawsuitNumber(), $frm->lawsuitNumber(), $errorMessages);
		AssistanceValidator::validateInt($frm->getLawsuitYear(), $frm->lawsuitYear(), $errorMessages);
		// Valida somente se foi digitado algo no campo
		if(strlen($frm->getLawsuitYear()) > 0)
			AssistanceValidator::validateStringLength($frm->getLawsuitYear(), $frm->lawsuitYear(), 4, 4, $errorMessages);

		/* Validação de Detalhes do processo do atendimento especial (1024 caracteres)*/
		AssistanceValidator::validateRequired($frm->getLawsuitDetail(), $frm->lawsuitDetail(), $errorMessages);
		AssistanceValidator::validateStringLength($frm->getLawsuitDetail(), $frm->lawsuitDetail(), 0, 1024, $errorMessages);
		$validatorWord = parent::validatorWords($frm->getLawsuitDetail());
		if(!$validatorWord->isValid($frm->getLawsuitDetail()))
		{
			$errorMessages[$frm->lawsuitDetail()][] = $validatorWord->getMessages();
		}
		/* Validação de decisão judicial do atendimento especial (1024 caracteres)*/
//		AssistanceValidator::validateRequired($frm->getRuling(), $frm->ruling(), $errorMessages);
		AssistanceValidator::validateStringLength($frm->getRuling(), $frm->ruling(), 0, 1024, $errorMessages);
		$validatorWord = parent::validatorWords($frm->getRuling());
		if(!$validatorWord->isValid($frm->getRuling()))
		{
			$errorMessages[$frm->ruling()][] = $validatorWord->getMessages();
		}
	}

	/**
	 * Valida atendimento de Grupo
	 */
	public static function validateGroupAssistance(AssistanceForm &$frm, &$errorMessages = null)
	{
		AssistanceValidator::validatePerson($frm, $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getAssistanceId(), $frm->assistanceId(), $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getClassId(), $frm->classId(), $errorMessages);
	}
	
	/**
	 * Valida edição de um atendimento
	 */
	public static function validateEditAssistance(AssistanceForm &$frm, &$errorMessages = null)
	{
		AssistanceValidator::validateRequiredId($frm->getAssistanceId(), $frm->assistanceId(), $errorMessages);
		AssistanceValidator::validateRequiredId($frm->getProgramType(), $frm->programType(), $errorMessages);
		AssistanceValidator::validateNeedProfile($frm, $errorMessages);
		AssistanceValidator::validatePerson($frm, $errorMessages);
		AssistanceValidator::validateUserLoggedEntity($errorMessages);
	}
	/**
	 * Valida se o usuário logado pode encerrar o atendimento
	 */
	public static function validateCloseAssistance(AssistanceForm &$frm, &$errorMessages = null)
	{
		AssistanceValidator::validateRequiredId($frm->getAssistanceId(), $frm->assistanceId(), $errorMessages);
		AssistanceValidator::validatePerson($frm, $errorMessages);
		// Caso seja Encerramento de atendimento do tipo grupo, valida também o parâmetro classId
		$assistance = AssistanceBusiness::load($frm->getAssistanceId());
		if(!empty($assistance))
		{
			$program = $assistance->findParentRow(CLS_PROGRAM);
			$resources = parent::getValidatorResources();
			try
			{
				$entityId = UserLogged::getEntityId();
			}
			catch(UserNotLoggedException $e)
			{
				$labelResources = Zend_Registry::get(LABEL_RESOURCES);
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$labelResources->notPermission.' - '.$e);
				trigger_error($labelResources->notPermission , E_USER_ERROR);
			}
			if($entityId != $program->{PGR_ID_ENTITY})
			{
				$errorMessages[Constants::NOT_ALLOWED_TO_CLOSE()][][]= $resources->notallowed->close->attendance;
			}
			else
			{
				$type = Utils::getAssistanceClassification($program->{PGR_ID_PROGRAM_TYPE});
				if($type == Constants::GROUP)
					AssistanceValidator::validateInt($frm->getClassId(), $frm->classId(), $errorMessages);
//					AssistanceValidator::validateRequiredId($frm->getClassId(), $frm->classId(), $errorMessages);

				elseif($type == Constants::GENERAL)
				{
					$profile = Utils::getProfileIdAsArray();
					$generalAssistance = AssistanceBusiness::loadAllGeneralByAssistanceId($frm->getAssistanceId(), $profile, null, null);
					if(empty($generalAssistance))
						$errorMessages[Constants::NOT_ALLOWED_TO_CLOSE()][][]= $resources->notallowed->close->attendance;
				}
			}
		}
	}

	/**
	 * Valida edição de um atendimento
	 */
	public static function validateAssistance(AssistanceForm &$frm, &$errorMessages = null)
	{
		$resources = parent::getValidatorResources();
		if(!AssistanceBusiness::isVisibleAssistance($frm->getAssistanceId()))
			$errorMessages[$frm->assistanceId()][][]= $resources->attendance->error->attendance; 
	}

	/**
	 * Valida Se o usuário está associado a alguma entidade
	 */
	public static function validateUserLoggedEntity(&$errorMessages = null)
	{
		$resources = parent::getValidatorResources();
		try
		{
			$entityId = UserLogged::getEntityId();
		}
		catch(UserNotLoggedException $e)
		{
			$labelResources = Zend_Registry::get(LABEL_RESOURCES);
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$labelResources->notPermission.' - '.$e);
			trigger_error($labelResources->notPermission , E_USER_ERROR);
		}
		if(empty($entityId))
			$errorMessages[AssistanceForm::entity()][][]= $resources->required->user->entity;
	}

}