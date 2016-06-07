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

abstract class GroupValidator extends BasicValidator
{
	public static function validateGroupEqualName(GroupForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('GroupBusiness');
		$row = GroupBusiness::loadByName($frm->getGroupName());
		if(count($row) > 0)
		{
			$errorMessages[GroupForm::groupName()][][] = parent::getValidatorResources()->group->equal;
		}
		return $errorMessages;
	}
	
	public static function validateGroupEditEqualName(GroupForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('GroupBusiness');
		$row = GroupBusiness::loadByName($frm->getGroupName());
		if(count($row) > 0)
		{
			if($row->{AGP_ID_GROUP} != $frm->getId())
			{			
				$errorMessages[GroupForm::groupName()][][] = parent::getValidatorResources()->group->equal;
			}
		}
		return $errorMessages;
	}
	
	public static function validateGroupData(GroupForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		$stringLenght = parent::validatorStringLength(3, 50);
		if (!$notEmpty->isValid($frm->getGroupName()))
		{
			$errorMessages[GroupForm::groupName()][] = $notEmpty->getMessages();
		}
		else if(strlen($frm->getGroupName()) > 50)
		{
			$groupName = Utils::abbreviate($frm->getGroupName(), 20);
			$errorMessages[GroupForm::groupName()][][] = parent::getValidatorResources()->text->long1.$groupName.parent::getValidatorResources()->text->long2."50".parent::getValidatorResources()->text->long3;			
		}
		else if (!$stringLenght->isValid($frm->getGroupName()))
		{
			$errorMessages[GroupForm::groupName()][] = $stringLenght->getMessages();
		}
		else
		{
			$validatorWord = parent::validatorWords($frm->getGroupName());
			if(!$validatorWord->isValid($frm->getGroupName()))
			{
				$errorMessages[GroupForm::groupName()][] = $validatorWord->getMessages();
			}
		}
		
		if (!$notEmpty->isValid($frm->getRoleId()))
		{
			$errorMessages[GroupForm::roleId()][] = $notEmpty->getMessages();
		}
		else
		{
			$validatorNumber = parent::validatorInt();
			if (!$validatorNumber->isValid($frm->getRoleId()))
			{
				$errorMessages[GroupForm::roleId()][] = $validatorNumber->getMessages();
			}
			else
			{
				$config = Zend_Registry::get(CONFIG);
				
				if(($frm->getRoleId() == $config->user->role->technician) || ($frm->getRoleId() == $config->user->role->operator))
				{
					;
				}
				else
				{
					$errorMessages[GroupForm::roleId()][][] = parent::getValidatorResources()->group->roleid;
				}
			}
		}		
		return $errorMessages;
	}
	
	public static function validateGroupId(GroupForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if(is_array($frm->getId()))
		{
			if(sizeof($frm->getId()) > 0)
			{
				foreach($frm->getId() as $value)
				{
					if (!$notEmpty->isValid($value))
					{
						$errorMessages[GroupForm::id()][] = $notEmpty->getMessages();
					}
					else
					{
						$id = parent::validatorInt();
						if(!$id->isValid($value))
						{
							$errorMessages[GroupForm::id()][] = $id->getMessages();
						}
					}
				}
			}
			else
			{
				if(!$notEmpty->isValid(null))
				{
					$errorMessages[GroupForm::id()][] = $notEmpty->getMessages();
				}
			}
		}
		else
		{
			if(!$notEmpty->isValid($frm->getId()))
			{
				$errorMessages[GroupForm::id()][] = $notEmpty->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateGroupPermission(GroupForm &$frm, &$errorMessages = null)
	{
		$required = parent::validatorNotEmpty();
		$integer = parent::validatorInt();
		$boolean = parent::validatorBetween(0, 1);
		$range = parent::validatorBetween(1, 3);

		/* Required */
		if (!$required->isValid($frm->getResourceId()))
			$errorMessages[GroupForm::resourceId().$frm->getResourceId()][] = $required->getMessages();
		if (!$integer->isValid($frm->getResourceId()))
			$errorMessages[GroupForm::resourceId().$frm->getResourceId()][] = $integer->getMessages();
		
		if(empty($errorMessages) && $frm->getResourceId())
		{
			$id = $frm->getResourceId();
			$type 		=  new Resource();
			$resource 	= $type->find($frm->getResourceId())->current(); 
			$config 	= Zend_Registry::get(CONFIG);
			$attendanceIdResource = $config->assistance->resourceId;
			if($resource->{ARC_RESOURCE_TYPE} == RESOURCE_TYPE_ENTITY)
			{
				if($resource->{ARC_ID_RESOURCE} != $attendanceIdResource)
				{
					/* Boolean */
					if (strlen($frm->isReadOnly()) > 0 && !$boolean->isValid($frm->isReadOnly()))
						$errorMessages[GroupForm::readOnly().$id][] = $boolean->getMessages();
				}
				else
				{
					/* Boolean */
					if (strlen($frm->changeConfidentiality()) > 0 && !$boolean->isValid($frm->isChangeConfidentiality()))
						$errorMessages[GroupForm::changeConfidentiality().$id][] = $boolean->getMessages();
					/* Range */
					if (strlen($frm->getDefaultConfidentiality()) > 0 && !$range->isValid($frm->getDefaultConfidentiality()))
						$errorMessages[GroupForm::defaultConfidentiality().$id][] = $range->getMessages();
				}
			}
			if (!$required->isValid($frm->getRoleId()))
				$errorMessages[GroupForm::roleId().$id][] = $required->getMessages();
			if (!$required->isValid($frm->getId()))
				$errorMessages[GroupForm::id().$id][] = $required->getMessages();
			/* Integer */
			if (!$integer->isValid($frm->getRoleId()))
				$errorMessages[GroupForm::roleId().$id][] = $integer->getMessages();
			if (!$integer->isValid($frm->getId()))
				$errorMessages[GroupForm::id().$id][] = $integer->getMessages();
		}
	}

	public static function validateDropGroupPermission(GroupForm &$frm, &$errorMessages = null)
	{
		$required = parent::validatorNotEmpty();
		$integer = parent::validatorInt();

		/* Required */
		if (!$required->isValid($frm->getResourceId()))
			$errorMessages[GroupForm::resourceId().$frm->getResourceId()][] = $required->getMessages();
		if (!$required->isValid($frm->getId()))
			$errorMessages[GroupForm::id().$frm->getResourceId()][] = $required->getMessages();
		/* Integer */
		if (!$integer->isValid($frm->getResourceId()))
			$errorMessages[GroupForm::resourceId().$frm->getResourceId()][] = $integer->getMessages();
		if (!$integer->isValid($frm->getId()))
			$errorMessages[GroupForm::id().$frm->getResourceId()][] = $integer->getMessages();
	}

	public static function validateStatus(GroupForm &$frm, &$errorMessages = null)
	{	
		$validatorRequired = parent::validatorNotEmpty();
		
		if(!$validatorRequired->isValid($frm->getStatus()))
		{
			$errorMessages[GroupForm::status()][] = $validatorRequired->getMessages();
		}
		else
		{
			$validator = parent::validatorStringLength(1, 1);
			
			if(!$validator->isValid($frm->getStatus()))
			{
				$errorMessages[GroupForm::status()][] = $validator->getMessages();
			}
			else
			{
				if(($frm->getStatus() == Constants::DISABLE) || ($frm->getStatus() == Constants::ENABLE))
				{
					;
				}
				else
				{
					$errorMessages[GroupForm::status()][][] = parent::getValidatorResources()->error->disable;
				}
				
				if($frm->getStatus() == Constants::ENABLE)
				{
					$frm->setStatus("");
				}	
			}
		}
			
		return $errorMessages;
	}

	public static function validateGroup(GroupForm &$frm)
	{
		$errorMessages = null;
		GroupValidator::validateGroupData($frm, $errorMessages);
		GroupValidator::validateGroupId($frm, $errorMessages);
		GroupValidator::validateGroupEditEqualName($frm, $errorMessages);
		GroupValidator::validateStatus($frm, $errorMessages);

		return $errorMessages;
	}
	
	public static function validateGroupAdd(GroupForm &$frm)
	{
		$errorMessages = null;
		GroupValidator::validateGroupData($frm, $errorMessages);
		GroupValidator::validateGroupEqualName($frm, $errorMessages);
		GroupValidator::validateStatus($frm, $errorMessages);

		return $errorMessages;
	}
}