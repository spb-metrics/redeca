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
 * Lucas dos Santos Borges Corr�a  - W3S		   	05/05/2008	                       Create file 
 * 
 */
require_once 'BasicValidator.php';

abstract class HistoryValidator extends BasicValidator
{
	public static function validateResourceId(HistoryForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		
		if(is_array($frm->getResourceId()))
		{
			foreach($frm->getResourceId() as $value)
			{
				if (!$notEmpty->isValid($value))
				{
					$errorMessages[HistoryForm::resourceId()][] = $notEmpty->getMessages();
				}
				else
				{
					$id = parent::validatorInt();
					
					if(!$id->isValid($value))
					{
						$errorMessages[HistoryForm::resourceId()][] = $id->getMessages();
					}
				}
			}
		}
		else
		{
			if (!$notEmpty->isValid($frm->getResourceId() ))
				$errorMessages[HistoryForm::resourceId()][] = $notEmpty->getMessages();
			else
			{
				$int = parent::validatorInt();
				if(!$int->isValid($frm->getResourceId() ))
					$errorMessages[HistoryForm::resourceId()][] = $int->getMessages();
			}	
		}
		
		return $errorMessages;
	}

	public static function validateHistoryId(HistoryForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		
		if(is_array($frm->getHistoryId()))
		{
			foreach($frm->getHistoryId() as $value)
			{
				if (!$notEmpty->isValid($value))
				{
					$errorMessages[HistoryForm::historyId()][] = $notEmpty->getMessages();
				}
				else
				{
					$id = parent::validatorInt();
					
					if(!$id->isValid($value))
					{
						$errorMessages[HistoryForm::historyId()][] = $id->getMessages();
					}
				}
			}
		}
		else
		{
			if (!$notEmpty->isValid($frm->getHistoryId()))
				$errorMessages[HistoryForm::historyId()][] = $notEmpty->getMessages();
			else
			{
				$int = parent::validatorInt();
				if(!$int->isValid($frm->getHistoryId() ))
					$errorMessages[HistoryForm::historyId()][] = $int->getMessages();
			}	
		}
		
		return $errorMessages;
	}

	public static function validatePersonId(HistoryForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getPersonId()))
		{
			$errorMessages[HistoryForm::personId()][] = $notEmpty->getMessages();
		}
		else
		{
			$int = parent::validatorInt();
			if(!$int->isValid($frm->getPersonId() ))
			{
				$errorMessages[HistoryForm::personId()][] = $int->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('PersonBusiness');
				
				$row = PersonBusiness::load($frm->getPersonId());				
				if(count($row) == 0)
				{
					$errorMessages[HistoryForm::personId()][][] = parent::getValidatorResources()->person->notfound;					
				}
			}
		}	
		
		return $errorMessages;
	}
	
}