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
 * Fabricio Meireles Monteiro  - W3S		   		18/02/2008	                       Create file 
 * 
 */

 
	require_once 'BasicValidator.php';
	
	abstract class ProfileValidator extends BasicValidator
	{
		public static function validateProfileEqualName(ProfileForm &$frm, &$errorMessages = null)
		{
			Zend_Loader::loadClass('ProfileBusiness');
			$row = ProfileBusiness::loadByName($frm->getProfileName());
			if(count($row) > 0)
			{
				$errorMessages[ProfileForm::profileName()][][] = parent::getValidatorResources()->profile->equal;
			}
			return $errorMessages;
		}
		
		public static function validateProfileEditEqualName(ProfileForm &$frm, &$errorMessages = null)
		{
			Zend_Loader::loadClass('ProfileBusiness');
			$row = ProfileBusiness::loadByName($frm->getProfileName());
			if(count($row) > 0)
			{
				if($row->{AUTH_ID_PROFILE} != $frm->getId())
				{
					$errorMessages[ProfileForm::profileName()][][] = parent::getValidatorResources()->profile->equal;
				}
			}
			return $errorMessages;
		}
		
		public static function validateProfileData(ProfileForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();			
			if (!$validatorRequired->isValid($frm->getProfileName()))
			{
				$errorMessages[ProfileForm::profileName()][] = $validatorRequired->getMessages();
			}
			else
			{
				$validator = parent::validatorStringLength(1, 20);				
				if(strlen($frm->getProfileName()) > 20)
				{
					$profileName = Utils::abbreviate($frm->getProfileName(), 25);
					if (!$validator->isValid($profileName))
					{
						$errorMessages[ProfileForm::profileName()][] = $validator->getMessages();
					}
				}			
				else if (!$validator->isValid($frm->getProfileName()))
				{
					$errorMessages[ProfileForm::profileName()][] = $validator->getMessages();
				}				
			}
			
			return $errorMessages;
		}
				
		public static function validateProfileId(ProfileForm &$frm, &$errorMessages = null)
		{
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(is_array($frm->getId()))
			{
				foreach($frm->getId() as $id)
				{
					if(!$validatorRequired->isValid($id))
					{
						$errorMessages[ProfileForm::id()][] = $validatorRequired->getMessages();
					}
					
					if(!$validator->isValid($id))
					{
						$errorMessages[ProfileForm::id()][] = $validator->getMessages();
					}
				}
			}
			else
			{
				if(!$validatorRequired->isValid($frm->getId()))
				{
					$errorMessages[ProfileForm::id()][] = $validatorRequired->getMessages();
				}
				
				if(!$validator->isValid($frm->getId()))
				{
					$errorMessages[ProfileForm::id()][] = $validator->getMessages();
				}				
			}
						
			return $errorMessages;		
		}
		
		public static function validateStatus(ProfileForm &$frm, &$errorMessages = null)
		{		
			$validatorRequired = parent::validatorNotEmpty();
			
			if(!$validatorRequired->isValid($frm->getStatus()))
			{
				$errorMessages[ProfileForm::status()][] = $validatorRequired->getMessages();
			}
			else
			{
				$validator = parent::validatorStringLength(1, 1);
				
				if(!$validator->isValid($frm->getStatus()))
				{
					$errorMessages[ProfileForm::status()][] = $validator->getMessages();
				}
				else
				{
					if(($frm->getStatus() == Constants::DISABLE) || ($frm->getStatus() == Constants::ENABLE))
					{
						;
					}
					else
					{
						$errorMessages[ProfileForm::status()][][] = parent::getValidatorResources()->error->disable;
					}
					
					if($frm->getStatus() == Constants::ENABLE)
					{
						$frm->setStatus("");
					}	
				}
			}
			
			return $errorMessages;
		}
		
		public static function validateProfile(ProfileForm &$frm)
		{	
			$errorMessages = null;
			ProfileValidator::validateProfileData($frm, $errorMessages);
			ProfileValidator::validateProfileId($frm, $errorMessages);
			ProfileValidator::validateProfileEditEqualName($frm, $errorMessages);
			ProfileValidator::validateStatus($frm, $errorMessages);
			
			return $errorMessages;
		}
		
		public static function validateProfileAdd(ProfileForm &$frm)
		{	
			$errorMessages = null;
			ProfileValidator::validateProfileData($frm, $errorMessages);
			ProfileValidator::validateProfileEqualName($frm, $errorMessages);
			ProfileValidator::validateStatus($frm, $errorMessages);
			
			return $errorMessages;
		}
	}
?>