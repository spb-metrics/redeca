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
 * @copyright Funda��o Telef�nica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Ara�atuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guaruj� - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de S�o Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de V�rzea Paulista - http://www.varzeapaulista.sp.gov.br 
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

abstract class FamilyExpenseValidator extends BasicValidator
{
	public static function validateIdFamily(FamilyExpenseForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if(!$notEmpty->isValid($frm->getIdFamily()))
		{
			$errorMessages[FamilyExpenseForm::idFamily()][] = $notEmpty->getMessages();
		}
		else
		{
			$validator = parent::validatorInt();
			if(!$validator->isValid($frm->getIdFamily()))
			{
				$errorMessages[FamilyExpenseForm::idFamily()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateIdPerson(FamilyExpenseForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if(!$notEmpty->isValid($frm->getId()))
		{
			$errorMessages[FamilyExpenseForm::id()][] = $notEmpty->getMessages();
		}
		else
		{
			$validator = parent::validatorInt();
			if(!$validator->isValid($frm->getId()))
			{
				$errorMessages[FamilyExpenseForm::id()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('PersonBusiness');
			
				$row = PersonBusiness::load($frm->getId());				
				if(count($row) == 0)
				{
					$errorMessages[FamilyExpenseForm::id()][][] = parent::getValidatorResources()->person->notfound;					
				}
				else
				{
					Zend_Loader::loadClass('FamilyBusiness');
					Zend_Loader::loadClass('Family');
			
					$row = FamilyBusiness::loadFamilyByIdPersonAndIdFamily($frm->getId(), null);				
					if(count($row) == 0)
					{
						$errorMessages[FamilyExpenseForm::id()][][] = parent::getValidatorResources()->family->notfound;					
					}
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateIdExpense(FamilyExpenseForm &$frm, &$errorMessages = null)
	{
		if(is_array($frm->getIdExpenseType()))
		{
			foreach($frm->getIdExpenseType() as $idExpense)
			{
				$notEmpty = parent::validatorNotEmpty();
				if(!$notEmpty->isValid($idExpense))
				{
					$errorMessages[FamilyExpenseForm::idExpenseType()][] = $notEmpty->getMessages();
				}
				else
				{
					$validator = parent::validatorInt();
					if(!$validator->isValid($idExpense))
					{
						$errorMessages[FamilyExpenseForm::idExpenseType()][] = $validator->getMessages();
					}
					else
					{
						Zend_Loader::loadClass('ExpenseBusiness');
					
						$row = ExpenseBusiness::load($idExpense);				
						if(count($row) == 0)
						{
							$errorMessages[FamilyExpenseForm::id()][][] = parent::getValidatorResources()->expense->notfound;					
						}
					}
				}
			}
		}
		else
		{
			$notEmpty = parent::validatorNotEmpty();
			if(!$notEmpty->isValid($frm->getIdExpenseType()))
			{
				$errorMessages[FamilyExpenseForm::idExpenseType()][] = $notEmpty->getMessages();
			}
			else
			{
				$validator = parent::validatorInt();
				if(!$validator->isValid($frm->getIdExpenseType()))
				{
					$errorMessages[FamilyExpenseForm::idExpenseType()][] = $validator->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('ExpenseBusiness');
				
					$row = ExpenseBusiness::load($frm->getIdExpenseType());				
					if(count($row) == 0)
					{
						$errorMessages[FamilyExpenseForm::id()][][] = parent::getValidatorResources()->expense->notfound;					
					}
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateExpenseValue(FamilyExpenseForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		$frm->setExpenseValue(str_replace(',','.',$frm->getExpenseValue()));		
		if(!$notEmpty->isValid($frm->getExpenseValue()))
		{
			$errorMessages[FamilyExpenseForm::expenseValue()][] = $notEmpty->getMessages();
		}
		else if($frm->getExpenseValue() < 0)
		{
			$errorMessages[FamilyExpenseForm::expenseValue()][][] = parent::getValidatorResources()->value->negative;
		}
		else if(!eregi('^([0-9]{1,4})((\.)[0-9]{2})?$', $frm->getExpenseValue()))
		{
			$errorMessages[FamilyExpenseForm::expenseValue()][][] = parent::getValidatorResources()->value->format;
		}		
		else
		{
			$validator = parent::validatorFloat();
			if(!$validator->isValid($frm->getExpenseValue()))
			{
				$errorMessages[FamilyExpenseForm::expenseValue()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateExpenseSave(ResidenceForm &$frm)
	{
		$errorMessages = null;
		
		self::validateIdPerson($frm, $errorMessages);
		self::validateIdFamily($frm, $errorMessages);
		self::validateIdExpense($frm, $errorMessages);
		self::validateExpenseValue($frm, $errorMessages);
				
		return $errorMessages;	
	}
	
	public static function validateExpenseNew(ResidenceForm &$frm)
	{
		$errorMessages = null;
		
		self::validateIdPerson($frm, $errorMessages);
		self::validateIdFamily($frm, $errorMessages);
				
		return $errorMessages;	
	}
	
	public static function validateExpenseDrop(ResidenceForm &$frm)
	{
		$errorMessages = null;
		
		self::validateIdPerson($frm, $errorMessages);
		self::validateIdFamily($frm, $errorMessages);
		self::validateIdExpense($frm, $errorMessages);
				
		return $errorMessages;	
	}
}