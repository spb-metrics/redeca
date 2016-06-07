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
 * Fabricio Meireles Monteiro  - W3S		   		25/02/2008	                       Create file 
 * 
 */
 
require_once 'BasicValidator.php';

abstract class EntityGroupValidator extends BasicValidator
{
	public static function validateEntityData(EntityGroupForm &$frm, &$errorMessages = null)
	{
		self::validateEntityId($frm, $errorMessages);
		self::validateEntityTypeId($frm, $errorMessages);
	}

	public static function validateEntityId(EntityGroupForm &$frm, &$errorMessages = null)
	{	
		if(is_array($frm->getEntityId()))
		{
			Zend_Loader::loadClass('EntityBusiness');
			foreach($frm->getEntityId() as $id)
			{
				$notEmpty = parent::validatorNotEmpty();
				if (!$notEmpty->isValid($id))
				{
					$errorMessages[EntityGroupForm::entityId()][] = $notEmpty->getMessages();
				}
				else
				{
					$validatorInt = parent::validatorInt();
					if (!$validatorInt->isValid($id))
					{
						$errorMessages[EntityGroupForm::entityId()][] = $validatorInt->getMessages();
					}
					else
					{
						$row = EntityBusiness::load($id);
						if(count($row) == 0)
						{
							$errorMessages[EntityGroupForm::entityId()][][] = parent::getValidatorResources()->entity->notfound;
						}
					}
				}				
			}
		}
		else
		{
			$notEmpty = parent::validatorNotEmpty();
			if (!$notEmpty->isValid($frm->getEntityId()))
			{
				$errorMessages[EntityGroupForm::entityId()][] = $notEmpty->getMessages();
			}
			else
			{
				$validatorInt = parent::validatorInt();
				if (!$validatorInt->isValid($frm->getEntityId()))
				{
					$errorMessages[EntityGroupForm::entityId()][] = $validatorInt->getMessages();
				}
				else
				{
					$row = EntityBusiness::load($frm->getEntityId());
					if(count($row) == 0)
					{
						$errorMessages[EntityGroupForm::entityId()][][] = parent::getValidatorResources()->entity->notfound;
					}
				}
			}	
		}
	}

	public static function validateEntityTypeId(EntityGroupForm &$frm, &$errorMessages = null)
	{	
		if(is_array($frm->getEntityTypeId()))
		{
			Zend_Loader::loadClass('EntityBusiness');
			
			foreach($frm->getEntityTypeId() as $id)
			{
				$notEmpty = parent::validatorNotEmpty();
				if (!$notEmpty->isValid($id))
				{
					$errorMessages[EntityGroupForm::entityTypeId()][] = $notEmpty->getMessages();
				}
				else
				{
					$validatorInt = parent::validatorInt();
					if (!$validatorInt->isValid($id))
					{
						$errorMessages[EntityGroupForm::entityTypeId()][] = $validatorInt->getMessages();
					}
					else
					{
						$row = EntityBusiness::loadTypes($id);
						if(count($row) == 0)
						{
							$errorMessages[EntityGroupForm::entityTypeId()][][] = parent::getValidatorResources()->classification->notfound;
						}
					}
				}
			}
		}
		else
		{
			$notEmpty = parent::validatorNotEmpty();
			if (!$notEmpty->isValid($frm->getEntityTypeId()))
			{
				$errorMessages[EntityGroupForm::entityTypeId()][] = $notEmpty->getMessages();
			}
			else
			{
				$validatorInt = parent::validatorInt();
				if (!$validatorInt->isValid($frm->getEntityTypeId()))
				{
					$errorMessages[EntityGroupForm::entityTypeId()][] = $validatorInt->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('EntityBusiness');
					$row = EntityBusiness::loadTypes($frm->getEntityTypeId());
					if(count($row) == 0)
					{
						$errorMessages[EntityGroupForm::entityTypeId()][][] = parent::getValidatorResources()->classification->notfound;
					}
				}
			}	
		}
	}

}