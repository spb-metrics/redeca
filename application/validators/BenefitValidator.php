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
 * Fabricio Meireles Monteiro  - W3S		   		19/03/2008	                       Create file 
 * 
 */

require_once 'BasicValidator.php';

abstract class BenefitValidator extends BasicValidator
{
	public static function validateBenefitIdAdd(BenefitForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(is_array($frm->getIdSocialProgramType()))
		{	
			foreach($frm->getIdSocialProgramType() as $id)
			{	
				if(!$validatorRequired->isValid($id))
				{
					$errorMessages[BenefitForm::idSocialProgramType()][] = $validatorRequired->getMessages();
				}
				else
				{
					if(!$validator->isValid($id))
					{
						$errorMessages[BenefitForm::idSocialProgramType()][] = $validator->getMessages();
					}	
					else
					{
						Zend_Loader::loadClass('SocialProgramBusiness');
						
						$row = SocialProgramBusiness::loadSocialPrograms($id);
						
						if(count($row) == 0)
						{
							$errorMessages[BenefitForm::idSocialProgramType()][][] = parent::getValidatorResources()->benefit->notfound;
						}
						else
						{							
							$row = SocialProgramBusiness::existProgramToPerson($frm->getIdPerson(), $id);							
							if(count($row) > 0)
							{
								$errorMessages[BenefitForm::idSocialProgramType()][][] = parent::getValidatorResources()->benefitPerson->notfound;
							}
						}
					}
				}
			}
		}
		else
		{	
			if(!$validatorRequired->isValid($frm->getIdSocialProgramType()))
			{
				$errorMessages[BenefitForm::idSocialProgramType()][] = $validatorRequired->getMessages();
				
			}
			else
			{
				if(!$validator->isValid($frm->getIdSocialProgramType()))
				{
					$errorMessages[BenefitForm::idSocialProgramType()][] = $validator->getMessages();
				}	
				else
				{
					Zend_Loader::loadClass('SocialProgramBusiness');
					
					$row = SocialProgramBusiness::loadSocialPrograms($frm->getIdSocialProgramType());
					
					if(count($row) == 0)
					{
						$errorMessages[BenefitForm::idSocialProgramType()][][] = parent::getValidatorResources()->benefit->notfound;
					}
					else
					{
						$row = SocialProgramBusiness::existProgramToPerson($frm->getIdPerson(), $frm->getIdSocialProgramType());
						if(count($row) == 0)
						{
							$errorMessages[BenefitForm::idSocialProgramType()][][] = parent::getValidatorResources()->benefitPerson->notfound;
						}
					}
				}
			}				
		}
					
		return $errorMessages;		
	}
	
	public static function validateBenefitId(BenefitForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(is_array($frm->getIdSocialProgramType()))
		{	
			foreach($frm->getIdSocialProgramType() as $id)
			{	
				if(!$validatorRequired->isValid($id))
				{
					$errorMessages[BenefitForm::idSocialProgramType()][] = $validatorRequired->getMessages();
				}
				else
				{
					if(!$validator->isValid($id))
					{
						$errorMessages[BenefitForm::idSocialProgramType()][] = $validator->getMessages();
					}	
					else
					{
						Zend_Loader::loadClass('SocialProgramBusiness');
						
						$row = SocialProgramBusiness::loadSocialPrograms($id);
						
						if(count($row) == 0)
						{
							$errorMessages[BenefitForm::idSocialProgramType()][][] = parent::getValidatorResources()->benefit->notfound;
						}
					}
				}
			}
		}
		else
		{	
			if(!$validatorRequired->isValid($frm->getIdSocialProgramType()))
			{
				$errorMessages[BenefitForm::idSocialProgramType()][] = $validatorRequired->getMessages();
				
			}
			else
			{
				if(!$validator->isValid($frm->getIdSocialProgramType()))
				{
					$errorMessages[BenefitForm::idSocialProgramType()][] = $validator->getMessages();
				}	
				else
				{
					Zend_Loader::loadClass('SocialProgramBusiness');
					
					$row = SocialProgramBusiness::loadSocialPrograms($frm->getIdSocialProgramType());
					
					if(count($row) == 0)
					{
						$errorMessages[BenefitForm::idSocialProgramType()][][] = parent::getValidatorResources()->benefit->notfound;
					}
				}
			}				
		}
					
		return $errorMessages;		
	}
	
	public static function validatePersonId(BenefitForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdPerson()))
		{
			$errorMessages[BenefitForm::idPerson()][] = $validatorRequired->getMessages();
		}
		else
		{
			if(!$validator->isValid($frm->getIdPerson()))
			{
				$errorMessages[BenefitForm::idPerson()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('PersonBusiness');
			
				$row = PersonBusiness::load($frm->getIdPerson());				
				if(count($row) == 0)
				{
					$errorMessages[BenefitForm::idPerson()][][] = parent::getValidatorResources()->person->notfound;					
				}
			}
		}
		
		return $errorMessages;
	}
	
	
	public static function validateProgramId(BenefitForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdProgram()))
		{
			$errorMessages[BenefitForm::idProgram()][] = $validatorRequired->getMessages();
		}
		else
		{
			if(!$validator->isValid($frm->getIdProgram()))
			{
				$errorMessages[BenefitForm::idProgram()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateDateBenefit(BenefitForm &$frm, &$errorMessages = null)
	{	
		$validatorDate = parent::validatorDate();
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getCollBenefit()))
		{	
			$errorMessages[BenefitForm::idSocialProgramType()][] = $validatorRequired->getMessages();
		}

		foreach($frm->getCollBenefit() as $objectBenefit)
		{	
			$stringDate = $objectBenefit[SPG_REGISTER_DATE.$objectBenefit[SPG_ID_SOCIAL_PROGRAM]];
			if((strlen($stringDate) > 0 ) && !$validatorDate->isValid($objectBenefit[SPG_REGISTER_DATE.$objectBenefit[SPG_ID_SOCIAL_PROGRAM]]))
			{	
				$errorMessages[BenefitForm::dateBenefit()."_".$objectBenefit[SPG_ID_SOCIAL_PROGRAM]][] = $validatorDate->getMessages();
			}
			
			if(!$validatorRequired->isValid($objectBenefit[SPG_ID_SOCIAL_PROGRAM]))
			{
				$errorMessages[BenefitForm::idSocialProgramType()."_".$objectBenefit[SPG_ID_SOCIAL_PROGRAM]][] = $validatorRequired->getMessages();
			}
			else
			{
				if(!$validator->isValid($objectBenefit[SPG_ID_SOCIAL_PROGRAM]))
				{
					$errorMessages[BenefitForm::idSocialProgramType()."_".$objectBenefit[SPG_ID_SOCIAL_PROGRAM]][] = $validator->getMessages();
				}	
			}
		}
				
		return $errorMessages;
	}
	
	public static function validateBenefit(BenefitForm &$frm)
	{	
		$errorMessages = null;
		BenefitValidator::validateBenefitId($frm, $errorMessages);
		BenefitValidator::validatePersonId($frm, $errorMessages);
		BenefitValidator::validateDateBenefit($frm, $errorMessages);
		
		return $errorMessages;
	}
	
	public static function validateBenefitAdd(BenefitForm &$frm)
	{	
		$errorMessages = null;
		BenefitValidator::validateBenefitIdAdd($frm, $errorMessages);
		BenefitValidator::validatePersonId($frm, $errorMessages);
		BenefitValidator::validateDateBenefit($frm, $errorMessages);
		
		return $errorMessages;
	}
		
}
