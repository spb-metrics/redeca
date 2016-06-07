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
 * Saulo Esteves Rodrigues  - W3S		   			10/01/2008	                       Create file 
 * 
 */


require_once 'BasicValidator.php';

abstract class ActivityValidator extends BasicValidator
{
	/**
	 * Verifica se o programa e/ou categoria a ser desassociado de uma atividade está sendo usado pela turma
	 */
	public static function verifyIfActivityDetailIsUsed(ActivityForm &$frm, $alertActivityInUse = null)
	{
		$arrayProgramCategory[ACD_ID_PROGRAM]	= $frm->getIdProgram();
		$arrayProgramCategory[ACD_ID_CATEGORY]	= $frm->getIdCategory();
		
		if(strlen($frm->getIdActivityDetail()) > 0 )
		{
			$resultVerify = Utils::analyzeDiffDetailActivityArrays($frm->getIdActivityDetail(), $arrayProgramCategory);
			
			if($resultVerify)
			{
				$activityDetailInClass = parent::validatorActivityDetailInClass();
				if(!$activityDetailInClass->isValid($frm->getIdActivityDetail()))
				{	
					$alertActivityInUse[ActivityForm::idActivityDetail()][] = $activityDetailInClass->getMessages();
				}		
			}
		}
		
		return $alertActivityInUse;
	}
	
	/**
	 * Verifica se o programa e/ou categoria a ser excluida está sendo usado pela turma
	 */
	public static function verifyIfActivityDetailIsUsedBeforeDrop(ActivityForm &$frm, $alertActivityInUse = null)
	{	
		if(strlen($frm->getIdActivityDetail()) > 0 )
		{
			$activityDetailInClass = parent::validatorActivityDetailInClass();
			if(!$activityDetailInClass->isValid($frm->getIdActivityDetail()))
			{	
				$alertActivityInUse[ActivityForm::idActivityDetail()][] = $activityDetailInClass->getMessages();
			}
		}
		
		return $alertActivityInUse;
	}
	
	/**
	 * Valida "id" do detalhamento da atividade
	 */
	public static function validateIdDetailActivity(ActivityForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdActivityDetail()))
		{
			$errorMessages[ActivityForm::idActivityDetail()][] = $validatorRequired->getMessages();
		}
		else
		{
			if(!$validator->isValid($frm->getIdActivityDetail()))
			{
				$errorMessages[ActivityForm::idActivityDetail()][] = $validator->getMessages();
			}
		}	
					
		return $errorMessages;
	}
	
	/** 
	 * Valida o nome inserido em detalhamento de atividade
	 */
	public static function validateNameDetailActivity(ActivityForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getNameDetailActivity()))
		{
			$errorMessages[ActivityForm::nameDetailActivity()][] = $notEmpty->getMessages();
		}
		else
		{
			$stringLenght = parent::validatorStringLength(1, 30);
			if(strlen($frm->getNameDetailActivity()) > 30)
			{
				$name = Utils::abbreviate($frm->getNameDetailActivity(), 31);
				if (!$stringLenght->isValid($name))
				{
					$errorMessages[ActivityForm::nameDetailActivity()][] = $stringLenght->getMessages();
				}
			}
			else if (!$stringLenght->isValid($frm->getNameDetailActivity()))
			{
				$errorMessages[ActivityForm::nameDetailActivity()][] = $stringLenght->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('ActivityDetailBusiness');
				$row = ActivityDetailBusiness::loadByEntityAndName(UserLogged::getEntityId(), $frm->getNameDetailActivity());
				if(count($row) > 0)
				{
					$errorMessages[ActivityForm::nameDetailActivity()][][] = parent::getValidatorResources()->activitydetail->equal;
				}
			}
		}

		return $errorMessages;
	}
	
	public static function validateNameDetailActivityEdit(ActivityForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getNameDetailActivity()))
		{
			$errorMessages[ActivityForm::nameDetailActivity()][] = $notEmpty->getMessages();
		}
		else
		{
			$stringLenght = parent::validatorStringLength(1, 30);
			if(strlen($frm->getNameDetailActivity()) > 30)
			{
				$name = Utils::abbreviate($frm->getNameDetailActivity(), 31);
				if (!$stringLenght->isValid($name))
				{
					$errorMessages[ActivityForm::nameDetailActivity()][] = $stringLenght->getMessages();
				}
			}
			else if (!$stringLenght->isValid($frm->getNameDetailActivity()))
			{
				$errorMessages[ActivityForm::nameDetailActivity()][] = $stringLenght->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('ActivityDetailBusiness');
				$row = ActivityDetailBusiness::loadByEntityAndName(UserLogged::getEntityId(), $frm->getNameDetailActivity());
								
				if(count($row) > 0)
				{
					foreach($row as $current)
					{
						$id[$current->{ACD_ID_ACTIVITY_DETAIL}] = $current->{ACD_ID_ACTIVITY_DETAIL};
					}
					
					if(count($id) > 0)
					{
						if(!array_search($frm->getIdActivityDetail(), $id))
						{
							$errorMessages[ActivityForm::nameDetailActivity()][][] = parent::getValidatorResources()->activitydetail->equal;
						}
					}
				}
			}
		}

		return $errorMessages;
	}
	
	/**
	 * Valida carga horária
	 */
	public static function validateWorkingHour(ActivityForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorFloat();
		if($frm->getWorkingHour() < 0)
		{
			$errorMessages[ActivityForm::workingHour()][][] = parent::getValidatorResources()->value->negative;
		}
		else if(strlen($frm->getWorkingHour()) > 30)
		{
			$hour = Utils::abbreviate($frm->getWorkingHour(), 30);
			$errorMessages[ActivityForm::workingHour()][][] = parent::getValidatorResources()->text->long1.$hour.parent::getValidatorResources()->text->long2.'15'.parent::getValidatorResources()->text->long3;
		}
		else if(!$validator->isValid($frm->getWorkingHour()))
		{
			$errorMessages[ActivityForm::workingHour()][] = $validator->getMessages();
		}		
		else if($frm->getWorkingHour() == 0)
		{
			$errorMessages[ActivityForm::workingHour()][][] = parent::getValidatorResources()->value->zero;
		}
		return $errorMessages;
	}
		
	/**
	 * Valida "idProgram" do form de detalhamento de atividades
	 */
	public static function validateIdProgram(ActivityForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdProgram()))
		{
			$errorMessages[ActivityForm::idProgram()][] = $validatorRequired->getMessages();
		}
		else
		{
			if(!$validator->isValid($frm->getIdProgram()))
			{
				$errorMessages[ActivityForm::idProgram()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('ProgramBusiness');
				$row = ProgramBusiness::loadProgram(UserLogged::getEntityId(), $frm->getIdProgram());
				if(count($row) == 0)
				{
					$errorMessages[ActivityForm::idProgram()][][] = parent::getValidatorResources()->programEntity->notfound;
				}
			}
		}	
					
		return $errorMessages;
	}
	
	/**
	 * Valida "idCategory" do form de detalhamento de atividades
	 */
	public static function validateIdCategory(ActivityForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdCategory()))
		{
			$errorMessages[ActivityForm::idCategory()][] = $validatorRequired->getMessages();
		}
		else
		{	
			if(!$validator->isValid($frm->getIdCategory()))
			{
				$errorMessages[ActivityForm::idCategory()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('ActivityBusiness');
				$row = ActivityBusiness::loadCategory($frm->getIdCategory());				
				if(count($row) == 0)
				{
					$errorMessages[ActivityForm::idCategory()][][] = parent::getValidatorResources()->activity->notfound;
				}
			}
		}
					
		return $errorMessages;
	}
	
	
	public static function validateActivityEqualName(ActivityForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('ActivityBusiness');
		$row = ActivityBusiness::loadCategoryByName($frm->getActivityName());
		if(count($row) > 0)
		{
			$errorMessages[ActivityForm::activityName()][][] = parent::getValidatorResources()->activity->equal;
		}
		return $errorMessages;
	}
	
	public static function validateActivityEditEqualName(ActivityForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('ActivityBusiness');
		$row = ActivityBusiness::loadCategoryByName($frm->getActivityName());
		if(count($row) > 0)
		{
			if($row->{CAT_ID_CATEGORY} != $frm->getIdActivity())
			{
				$errorMessages[ActivityForm::activityName()][][] = parent::getValidatorResources()->activity->equal;
			}
		}
		return $errorMessages;
	}
	
	public static function validateActivityData(ActivityForm &$frm, &$errorMessages = null)
	{						
		if($frm->getActivityType() == 0)
		{
			$validator = parent::validatorStringLength(3, 80);
			
			if(strlen($frm->getActivityName()) > 80)
			{
				$activityName = Utils::abbreviate($frm->getActivityName(), 20);
				$errorMessages[ActivityForm::activityName()][][] = parent::getValidatorResources()->text->long1.$activityName.parent::getValidatorResources()->text->long2.'80'.parent::getValidatorResources()->text->long3;
			}
			else if (!$validator->isValid($frm->getActivityName()))
			{
				$errorMessages[ActivityForm::activityName()][] = $validator->getMessages();
			}
			else if ($frm->getActivityName() == parent::getValidatorResources()->macro->activity->info)
			{			
				$errorMessages[ActivityForm::activityName()][][] = parent::getValidatorResources()->macro->activity->info;
			}		
			else
			{
				$validatorWord = parent::validatorWords($frm->getActivityName());
				if(!$validatorWord->isValid($frm->getActivityName()))
				{
					$errorMessages[ActivityForm::activityName()][] = $validatorWord->getMessages();
				}
			}
		}
		else if($frm->getActivityType() == 1)
		{
			$validatorRequired = parent::validatorNotEmpty();
			if (!$validatorRequired->isValid($frm->getActivityType()) && $frm->getActivityType() != 0 )
			{
				$errorMessages[ActivityForm::activityType()][] = $validatorRequired->getMessages();
			}		
			else
			{
				$validatorInt = parent::validatorInt();
				if (!$validatorInt->isValid($frm->getActivityType()) )
				{
					$resources = Zend_Registry::get(VALIDATOR_RESOURCES);
					$errorMessages[ActivityForm::activityType()][] = $validatorInt->getMessages();
				}
				else
				{
					self::validateIdMacroActivity($frm, $errorMessages);
					$validator = parent::validatorStringLength(3, 80);
			
					if(strlen($frm->getActivityName()) > 80)
					{
						$activityName = Utils::abbreviate($frm->getActivityName(), 20);
						$errorMessages[ActivityForm::activityName()][][] = parent::getValidatorResources()->text->long1.$activityName.parent::getValidatorResources()->text->long2.'80'.parent::getValidatorResources()->text->long3;
					}
					else if (!$validator->isValid($frm->getActivityName()))
					{
						$errorMessages[ActivityForm::activityName()][] = $validator->getMessages();
					}
					else if ($frm->getActivityName() == parent::getValidatorResources()->macro->activity->info)
					{			
						$errorMessages[ActivityForm::activityName()][][] = parent::getValidatorResources()->macro->activity->info;
					}		
					else
					{
						$validatorWord = parent::validatorWords($frm->getActivityName());
						if(!$validatorWord->isValid($frm->getActivityName()))
						{
							$errorMessages[ActivityForm::activityName()][] = $validatorWord->getMessages();
						}
					}					
				}
			}
		}	
		
		return $errorMessages;
	}
	/**
	 * Valida o Identificador de uma Macro-atividade
	 */
	public static function validateActivityId(ActivityForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(is_array($frm->getIdActivity()))
		{
			foreach($frm->getIdActivity() as $id)
			{
				if (!$validatorRequired->isValid($id))
				{
					$errorMessages[ActivityForm::idActivity()][] = $validatorRequired->getMessages();
				}
				else if(!$validator->isValid($id))
				{
					$errorMessages[ActivityForm::idActivity()][] = $validator->getMessages();
				}
			}
		}
		else
		{				
			if (!$validatorRequired->isValid($frm->getIdActivity()))
			{				
				$errorMessages[ActivityForm::idActivity()][] = $validatorRequired->getMessages();
			}
			else if(!$validator->isValid($frm->getIdActivity()))
			{
				$errorMessages[ActivityForm::idActivity()][] = $validator->getMessages();
			}			
		}

		return $errorMessages;
	}
	/**
	 * Valida o Identificador de uma atividade
	 */
	public static function validateIdMacroActivity(ActivityForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(is_array($frm->getIdMacroActivity()))
		{
			foreach($frm->getIdMacroActivity() as $id)
			{
				if (!$validatorRequired->isValid($frm->getIdMacroActivity()))
				{
					$errorMessages[ActivityForm::idMacroActivity()][] = $validatorRequired->getMessages();
				}		
		
				if(!$validator->isValid($frm->getIdMacroActivity()))
				{
					$errorMessages[ActivityForm::idMacroActivity()][] = $validator->getMessages();
				}
			}
		}
		else
		{
			if (!$validatorRequired->isValid($frm->getIdMacroActivity()))
			{
				$errorMessages[ActivityForm::idMacroActivity()][] = $validatorRequired->getMessages();
			}		

			if(!$validator->isValid($frm->getIdMacroActivity()))
			{
				$errorMessages[ActivityForm::idMacroActivity()][] = $validator->getMessages();
			}
		}
		return $errorMessages;
	}

	public static function validateStatus(ActivityForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		
		if(!$validatorRequired->isValid($frm->getStatus()))
		{
			$errorMessages[ActivityForm::status()][] = $validatorRequired->getMessages();
		}
		else
		{
			$validator = parent::validatorStringLength(1, 1);
			
			if(!$validator->isValid($frm->getStatus()))
			{
				$errorMessages[ActivityForm::status()][] = $validator->getMessages();
			}
			else
			{
				if(($frm->getStatus() == Constants::DISABLE) || ($frm->getStatus() == Constants::ENABLE))
				{
					;
				}
				else
				{
					$errorMessages[ActivityForm::status()][][] = parent::getValidatorResources()->error->disable;
				}
				
				if($frm->getStatus() == Constants::ENABLE)
				{
					$frm->setStatus("");
				}
			}
		}	
		
		return $errorMessages;
	}
	
	public static function validateMacroActivityData(ActivityForm &$frm)
	{
		$errorMessages = null;
		ActivityValidator::validateActivityData($frm, $errorMessages);
		ActivityValidator::validateIdMacroActivity($frm, $errorMessages);
		ActivityValidator::validateActivityEditEqualName($frm, $errorMessages);		
		ActivityValidator::validateStatus($frm, $errorMessages);
		
		return $errorMessages;
	}
	
	public static function validateActivityDataEdit(ActivityForm &$frm)
	{
		$errorMessages = null;
		ActivityValidator::validateActivityData($frm, $errorMessages);
		ActivityValidator::validateActivityId($frm, $errorMessages);
		ActivityValidator::validateActivityEditEqualName($frm, $errorMessages);		
		ActivityValidator::validateStatus($frm, $errorMessages);
		
		return $errorMessages;
	}
	
	public static function validateActivity(ActivityForm &$frm)
	{
		$errorMessages = null;
		ActivityValidator::validateActivityData($frm, $errorMessages);
		ActivityValidator::validateActivityId($frm, $errorMessages);
		ActivityValidator::validateIdMacroActivity($frm, $errorMessages);
		ActivityValidator::validateStatus($frm, $errorMessages);

		return $errorMessages;
	}
	
	public static function validateActivityAdd(ActivityForm &$frm)
	{
		$errorMessages = null;
		ActivityValidator::validateActivityData($frm, $errorMessages);
		ActivityValidator::validateActivityEqualName($frm, $errorMessages);
		ActivityValidator::validateStatus($frm, $errorMessages);

		return $errorMessages;
	}
}