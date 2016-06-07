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
 * Fabricio Meireles Monteiro  - W3S		   		19/02/2008	                       Create file 
 * 
 */

	require_once 'BasicValidator.php';
	
	abstract class ProgramValidator extends BasicValidator
	{
		public static function validateProgramEqualName(ProgramForm &$frm, &$errorMessages = null)
		{
			Zend_Loader::loadClass('ProgramBusiness');
			$row = ProgramBusiness::loadByName($frm->getProgramName());
			if(count($row) > 0)
			{
				$errorMessages[ProgramForm::programName()][][] = parent::getValidatorResources()->program->equal;
			}
		}
		
		public static function validateProgramData(ProgramForm &$frm, &$errorMessages = null)
		{	
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorStringLength(3, 50);
			
			if (!$validatorRequired->isValid($frm->getProgramName()))
			{
				$errorMessages[ProgramForm::programName()][] = $validatorRequired->getMessages();
			}
			else if(strlen($frm->getProgramName()) > 50)
			{
				$programName = Utils::abbreviate($frm->getProgramName(), 20);
				$errorMessages[ProgramForm::programName()][][] = parent::getValidatorResources()->text->long1.$programName.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;
			}
			else if (!$validator->isValid($frm->getProgramName()))
			{
				$errorMessages[ProgramForm::programName()][] = $validator->getMessages();				
			}
			else
			{
				$validatorWord = parent::validatorWords($frm->getProgramName());
				if(!$validatorWord->isValid($frm->getProgramName()))
				{
					$errorMessages[ProgramForm::programName()][] = $validatorWord->getMessages();
				}
			}
			return $errorMessages;
		}
				
		public static function validateProgramId(ProgramForm &$frm, &$errorMessages = null)
		{
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(is_array($frm->getId()))
			{
				foreach($frm->getId() as $id)
				{
					if(!$validatorRequired->isValid($id))
					{
						$errorMessages[ProgramForm::id()][] = $validatorRequired->getMessages();
					}
					
					if(!$validator->isValid($id))
					{
						$errorMessages[ProgramForm::id()][] = $validator->getMessages();
					}
				}
			}
			else
			{
				if(!$validatorRequired->isValid($frm->getId()))
				{
					$errorMessages[ProgramForm::id()][] = $validatorRequired->getMessages();
				}
				
				if(!$validator->isValid($frm->getId()))
				{
					$errorMessages[ProgramForm::id()][] = $validator->getMessages();
				}				
			}
						
			return $errorMessages;		
		}
		
		public static function validateIdTargetPublic(ProgramForm &$frm, &$errorMessages = null)
		{
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			
			if(!$validatorRequired->isValid($frm->getIdTargetPublic()))
			{
				$errorMessages[ProgramForm::idTargetPublic()][] = $validatorRequired->getMessages();
			}
			else
			{
				if(!$validator->isValid($frm->getIdTargetPublic()))
				{
					$errorMessages[ProgramForm::idTargetPublic()][] = $validator->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('ProgramBusiness');
					
					$row = ProgramBusiness::loadTargetPublic($frm->getIdTargetPublic());
					
					if(count($row) == 0)
					{
						$errorMessages[ProgramForm::idTargetPublic()][][] = parent::getValidatorResources()->target->notfound;
					}
				}	
			}
		}
		
		public static function validateStatus(ProgramForm &$frm, &$errorMessages = null)
		{			
			$validatorRequired = parent::validatorNotEmpty();
			
			if(!$validatorRequired->isValid($frm->getStatus()))
			{
				$errorMessages[ProgramForm::status()][] = $validatorRequired->getMessages();
			}
			else
			{
				$validator = parent::validatorStringLength(1, 1);
				
				if(!$validator->isValid($frm->getStatus()))
				{
					$errorMessages[ProgramForm::status()][] = $validator->getMessages();
				}
				else
				{
					if(($frm->getStatus() == Constants::DISABLE) || ($frm->getStatus() == Constants::ENABLE))
					{
						;
					}
					else
					{
						$errorMessages[ProgramForm::status()][][] = parent::getValidatorResources()->error->disable;
					}
					
					if($frm->getStatus() == Constants::ENABLE)
					{
						$frm->setStatus("");
					}						
				}
			}
			
			return $errorMessages;
		}
			
		public static function validateProgram(ProgramForm &$frm)
		{	
			$errorMessages = null;
			ProgramValidator::validateProgramData($frm, $errorMessages);
			ProgramValidator::validateIdTargetPublic($frm, $errorMessages);
			ProgramValidator::validateStatus($frm, $errorMessages);
			
			return $errorMessages;
		}
	}
?>
