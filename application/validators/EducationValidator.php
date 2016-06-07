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
 * Fabricio Meireles Monteiro  - W3S		   		10/03/2008	                       Create file 
 * 
 */

require_once 'BasicValidator.php';

abstract class EducationValidator extends BasicValidator
{
	public static function validatePersonId(EducationForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdPerson()))
		{
			$errorMessages[EducationForm::idPerson()][] = $validatorRequired->getMessages();
		}
		else
		{
			if(!$validator->isValid($frm->getIdPerson()))
			{
				$errorMessages[EducationForm::idPerson()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('PersonBusiness');
				
				$row = PersonBusiness::load($frm->getIdPerson());				
				if(count($row) == 0)
				{
					$errorMessages[EducationForm::idPerson()][][] = parent::getValidatorResources()->person->notfound;					
				}
			}
		}			
		return $errorMessages;
	}
	
	public static function validateIdDegreeType(EducationForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdDegreeType()))
		{
			$errorMessages[EducationForm::idDegreeType()][] = $validatorRequired->getMessages();
		}
		else
		{
			if(!$validator->isValid($frm->getIdDegreeType()))
			{
				$errorMessages[EducationForm::idDegreeType()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('LevelInstructionBusiness');
				
				$row = LevelInstructionBusiness::loadDegree($frm->getIdDegreeType());				
				if(count($row) == 0)
				{
					$errorMessages[EducationForm::idDegreeType()][][] = parent::getValidatorResources()->degree->notfound;					
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateIdYearSchoolType(EducationForm &$frm, &$errorMessages = null)
	{	
		$validator = parent::validatorInt();
		
		if(strlen($frm->getIdYearSchoolType()) > 0 && !$validator->isValid($frm->getIdYearSchoolType()))
		{
			$errorMessages[EducationForm::idYearSchoolType()][] = $validator->getMessages();
		}
		else
		{
			Zend_Loader::loadClass('RegistrationBusiness');
				
			$row = RegistrationBusiness::loadSchoolYear($frm->getIdYearSchoolType());				
			if(count($row) == 0)
			{
				$errorMessages[EducationForm::idYearSchoolType()][][] = parent::getValidatorResources()->yearschool->notfound;					
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateIdPeriodType(EducationForm &$frm, &$errorMessages = null)
	{	
		$validator 			= parent::validatorInt();
		
		if(strlen($frm->getIdPeriodType()) > 0 && !$validator->isValid($frm->getIdPeriodType()))
		{
			$errorMessages[EducationForm::idPeriodType()][] = $validator->getMessages();
		}
		else
		{
			Zend_Loader::loadClass('RegistrationBusiness');
				
			$row = RegistrationBusiness::loadPeriodType($frm->getIdPeriodType());				
			if(count($row) == 0)
			{
				$errorMessages[EducationForm::idPeriodType()][][] = parent::getValidatorResources()->period->notfound;					
			}
		}
		
		return $errorMessages;
	}	
	
	public static function validateYear(EducationForm &$frm, &$errorMessages = null)
	{	
		$validatorInt = parent::validatorInt();		
		
		if(strlen($frm->getYear()) > 0 && !$validatorInt->isValid($frm->getYear()))
		{
			$errorMessages[EducationForm::year()][] = $validatorInt->getMessages();
		}
		else
		{	
			$validatorBetween 	= parent::validatorBetween(1, 99);
		
			if(strlen($frm->getYear()) > 0 && !$validatorBetween->isValid($frm->getYear()))
			{
				$errorMessages[EducationForm::year()][] = $validatorBetween->getMessages();
			}	
		}	
		
		return $errorMessages;
	}
	
	public static function validateMonth(EducationForm &$frm, &$errorMessages = null)
	{			
		$validatorInt = parent::validatorInt();
		
		if(strlen($frm->getMonth()) > 0 && !$validatorInt->isValid($frm->getMonth()))
		{
			$errorMessages[EducationForm::month()][] = $validatorInt->getMessages();
		}
		else
		{
			$validatorBetween 	= parent::validatorBetween(1, 11);
		
			if(strlen($frm->getMonth()) > 0 && !$validatorBetween->isValid($frm->getMonth()))
			{
				$errorMessages[EducationForm::month()][] = $validatorBetween->getMessages();
			}	
		}
		
		return $errorMessages;
	}
	
	public static function validateIdSchool(EducationForm &$frm, &$errorMessages = null)
	{	
		$validator = parent::validatorInt();
			
		if(strlen($frm->getIdSchool()) > 0 && !$validator->isValid($frm->getIdSchool()))
		{
			$errorMessages[EducationForm::idSchool()][] = $validator->getMessages();
		}
		else
		{
			Zend_Loader::loadClass('SchoolBusiness');
			$row = SchoolBusiness::loadSchool($frm->getIdSchool());
			if(count($row) == 0)
			{
				$errorMessages[EducationForm::idSchool()][][] = parent::getValidatorResources()->school->notfound;
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateEducation(EducationForm &$frm)
	{	
		$errorMessages = null;
		EducationValidator::validatePersonId($frm, $errorMessages);
		EducationValidator::validateIdDegreeType($frm, $errorMessages);
		EducationValidator::validateIdYearSchoolType($frm, $errorMessages);
		EducationValidator::validateIdPeriodType($frm, $errorMessages);
		EducationValidator::validateYear($frm, $errorMessages);
		EducationValidator::validateMonth($frm, $errorMessages);
		EducationValidator::validateIdSchool($frm, $errorMessages);
		
		return $errorMessages;
	}
	
	public static function validateEducationEdit(EducationForm &$frm)
	{	
		$errorMessages = null;
		EducationValidator::validatePersonId($frm, $errorMessages);
		EducationValidator::validateIdDegreeType($frm, $errorMessages);
		
		if($frm->getYear() || $frm->getMonth())
		{
			EducationValidator::validateYear($frm, $errorMessages);
			EducationValidator::validateMonth($frm, $errorMessages);
		}
		else
		{
			EducationValidator::validateIdYearSchoolType($frm, $errorMessages);
			EducationValidator::validateIdPeriodType($frm, $errorMessages);
			EducationValidator::validateIdSchool($frm, $errorMessages);
		}		
		
		return $errorMessages;
	}
}
 
 