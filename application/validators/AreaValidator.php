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

abstract class AreaValidator extends BasicValidator
{
	public static function validateAreaEqualName(AreaForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('AreaBusiness');
		$row = AreaBusiness::loadByName($frm->getAreaName());
		if(count($row) > 0)
		{
			$errorMessages[AreaForm::areaName()][][] = parent::getValidatorResources()->area->equal;
		}
		return $errorMessages;
	}

	public static function validateAreaEditEqualName(AreaForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('AreaBusiness');
		$row = AreaBusiness::loadByName($frm->getAreaName());		
		if(count($row) > 0)
		{			
			if($row->current()->{EAT_ID_ENTITY_AREA} != $frm->getId())
				$errorMessages[AreaForm::areaName()][][] = parent::getValidatorResources()->area->equal;
		}
		return $errorMessages;
	}

	public static function validateAreaData(AreaForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		$stringLenght = parent::validatorStringLength(3, 50);
		
		if (!$notEmpty->isValid($frm->getAreaName()))
		{			
			$errorMessages[AreaForm::areaName()][] = $notEmpty->getMessages();
		}
		else if(strlen($frm->getAreaName()) > 50)
		{
			$areaName = Utils::abbreviate($frm->getAreaName(), 20);
			$errorMessages[AreaForm::areaName()][][] = parent::getValidatorResources()->text->long1.$areaName.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;			
		}
		else if (!$stringLenght->isValid($frm->getAreaName()))
		{
			$errorMessages[AreaForm::areaName()][] = $stringLenght->getMessages();
		}	
		else
		{
			$validatorWord = parent::validatorWords($frm->getAreaName());
			if(!$validatorWord->isValid($frm->getAreaName()))
			{
				$errorMessages[AreaForm::areaName()][] = $validatorWord->getMessages();
			}
		}	
		return $errorMessages;
	}
	
	public static function validateAreaId(AreaForm &$frm, &$errorMessages = null)
	{		
		$notEmpty = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(is_array($frm->getId()))
		{
			foreach($frm->getId() as $value)
			{
				if (!$notEmpty->isValid($value))
				{
					$errorMessages[AreaForm::id()][] = $notEmpty->getMessages();
				}
				if(!$validator->isValid($value))
				{
					$errorMessages[AreaForm::id()][] = $validator->getMessages();
				}
			}
		}
		else
		{	
			if($frm->getId())
			{
				if (!$notEmpty->isValid($frm->getId()))
				{
					$errorMessages[AreaForm::id()][] = $notEmpty->getMessages();
				}
				
				if(!$validator->isValid($frm->getId()))
				{
					$errorMessages[AreaForm::id()][] = $validator->getMessages();
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateAreaIdRequired(AreaForm &$frm, &$errorMessages = null)
	{		
		$notEmpty = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(is_array($frm->getId()))
		{
			foreach($frm->getId() as $value)
			{
				if (!$notEmpty->isValid($value))
				{
					$errorMessages[AreaForm::id()][] = $notEmpty->getMessages();
				}
				if(!$validator->isValid($value))
				{
					$errorMessages[AreaForm::id()][] = $validator->getMessages();
				}
			}
		}
		else
		{	
			if (!$notEmpty->isValid($frm->getId()))
			{
				$errorMessages[AreaForm::id()][] = $notEmpty->getMessages();
			}
			
			if(!$validator->isValid($frm->getId()))
			{
				$errorMessages[AreaForm::id()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Valida o AreaId como Integer somente se pelo menos um ID for passado
	 */
	public static function validateId(AreaForm &$frm, &$errorMessages = null)
	{		
		$validator = parent::validatorInt();
		
		if(is_array($frm->getId()))
		{
			foreach($frm->getId() as $value)
			{
				if(strlen($value) > 0 && !$validator->isValid($value))
				{
					$errorMessages[AreaForm::id()][] = $validator->getMessages();
				}
			}
		}
		else
		{	
			if(strlen($frm->getId()) > 0 && !$validator->isValid($frm->getId()))
			{
				$errorMessages[AreaForm::id()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateStatus(AreaForm &$frm, &$errorMessages = null)
	{	
		$validatorRequired = parent::validatorNotEmpty();
		
		if(!$validatorRequired->isValid($frm->getStatus()))
		{
			$errorMessages[AreaForm::status()][] = $validatorRequired->getMessages();
		}
		else
		{
			$validator = parent::validatorStringLength(1, 1);
			
			if(!$validator->isValid($frm->getStatus()))
			{
				$errorMessages[AreaForm::status()][] = $validator->getMessages();
			}
			else
			{
				if(($frm->getStatus() == Constants::DISABLE) || ($frm->getStatus() == Constants::ENABLE))
				{
					;
				}
				else
				{
					$errorMessages[AreaForm::status()][][] = parent::getValidatorResources()->error->disable;
				}
				
				if($frm->getStatus() == Constants::ENABLE)
				{
					$frm->setStatus("");
				}	
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateArea(AreaForm &$frm)
	{
		$errorMessages = null;
		AreaValidator::validateAreaData($frm, $errorMessages);
		AreaValidator::validateAreaId($frm, $errorMessages);
		AreaValidator::validateStatus($frm, $errorMessages);
		
		return $errorMessages;
	}
}