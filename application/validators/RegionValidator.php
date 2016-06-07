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

abstract class RegionValidator extends BasicValidator
{
	public static function validateRegionState(RegionForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		if(!$validatorRequired->isValid($frm->getState()))
		{
			$errorMessages[RegionForm::state()][] = $validatorRequired->getMessages();
		}
		else
		{
			$validatorInt = parent::validatorInt();
			if(!$validatorInt->isValid($frm->getState()))
			{
				$errorMessages[RegionForm::state()][] = $validatorInt->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('UFBusiness');
				$row = UFBusiness::load($frm->getState());				
				if(count($row) == 0)
				{
					$errorMessages[RegionForm::state()][][] = parent::getValidatorResources()->uf->notfound;
				}
			}
		}
		return $errorMessages;
	}
	
	public static function validateRegionCity(RegionForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		if(!$validatorRequired->isValid($frm->getCity()))
		{
			$errorMessages[RegionForm::city()][] = $validatorRequired->getMessages();
		}
		else
		{
			$validatorInt = parent::validatorInt();
			if(!$validatorInt->isValid($frm->getCity()))
			{
				$errorMessages[RegionForm::city()][] = $validatorInt->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('UFBusiness');
				$row = UFBusiness::load($frm->getState())->current();
				if(count($row) > 0)
				{
					$flag = false;
					foreach($row->findDependentRowset(CLS_CITY) as $city)
					{
						if($frm->getCity() == $city->{CTY_ID_CITY})
						{
							$flag = true;
							break;
						}						
					}
					if($flag === false)
					{
						$errorMessages[RegionForm::city()][][] = parent::getValidatorResources()->city->notfound;
					}
				}				
			}
		}		
		return $errorMessages;
	}
	
	public static function validateRegionNeighborhood(RegionForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('NeighborhoodBusiness');
		$row = NeighborhoodBusiness::findByCity($frm->getCity());
		if(count($row) > 0)
		{
			$flag = false;
			foreach($frm->getNeighborhood() as $nbh)
			{
				foreach($row as $v)
				{
					if($nbh == $v->{NHD_ID_NEIGHBORHOOD})
					{
						$flag = true;
						break;
					}					
				}
				if($flag === false)
				{
					$errorMessages[RegionForm::neighborhood()][][] = parent::getValidatorResources()->neighborhood->notfound;
				}
			}			
		}		
		return $errorMessages;
	}
	
	public static function validateEqualName(RegionForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('RegionBusiness');
		$row = RegionBusiness::loadByName($frm->getRegion());
		if(count($row) > 0)
		{
			$errorMessages[RegionForm::region()][][] = parent::getValidatorResources()->region->equal;
		}
		return $errorMessages;
	}
	
	public static function validateEqualEditName(RegionForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('RegionBusiness');
		$row = RegionBusiness::loadByName($frm->getRegion());
		if(count($row) > 0)
		{
			if($row->{RGN_ID_REGION} != $frm->getIdRegion())
			{
				$errorMessages[RegionForm::region()][][] = parent::getValidatorResources()->region->equal;
			}
		}
		return $errorMessages;
	}
	
	public static function validateInsert(RegionForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorStringLength(3, 50);
		$validatorRequired = parent::validatorNotEmpty();
		$validatorInt = parent::validatorInt();
		
		if(strlen($frm->getRegion()) > 50)
		{
			$region = Utils::abbreviate($frm->getRegion(), 22);
			$errorMessages[RegionForm::region()][][] = parent::getValidatorResources()->text->long1.$region.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;
		}
		else if (!$validator->isValid($frm->getRegion()))
		{
			$errorMessages[RegionForm::region()][] = $validator->getMessages();
		}
		
		if(is_array($frm->getNeighborhood()))
			foreach($frm->getNeighborhood() as $id)
			{
				if(!$validatorRequired->isValid($id))
					$errorMessages[RegionForm::neighborhood()][] = $validatorRequired->getMessages();
				if(!$validatorInt->isValid($id))
					$errorMessages[RegionForm::neighborhood()][] = $validatorInt->getMessages();				
			}
		else
		{
			if(!$validatorRequired->isValid($frm->getNeighborhood()))
				$errorMessages[RegionForm::neighborhood()][] = $validatorRequired->getMessages();
			if(!$validatorInt->isValid($frm->getNeighborhood()))
				$errorMessages[RegionForm::neighborhood()][] = $validatorInt->getMessages();
		}	
			
		return $errorMessages;
	}
	
	public static function validateEdit(RegionForm &$frm, &$errorMessages = null)
	{
		$errorMessages = self::validateInsert($frm);
		return self::validateIdRegion($frm, $errorMessages);
	}

	public static function validateIdRegion(RegionForm &$frm, &$errorMessages = null)
	{
		$validatorInt = parent::validatorInt();
		$validatorRequired = parent::validatorNotEmpty();

		if (!$validatorRequired->isValid($frm->getIdRegion()))
		{
			$errorMessages[RegionForm::idRegion()][] = $validatorRequired->getMessages();
		}
		else if (!$validatorInt->isValid($frm->getIdRegion()))
		{
			$errorMessages[RegionForm::idRegion()][] = $validatorInt->getMessages();
		}
		else
		{
			Zend_Loader::loadClass('RegionBusiness');
			$row = RegionBusiness::find($frm->getIdRegion());
			if(count($row) == 0)
			{
				$errorMessages[RegionForm::idRegion()][][] = parent::getValidatorResources()->region->notfound;
			}
		}

		return $errorMessages;
	}

	public static function validate(RegionForm &$frm)
	{
		$errorMessages = null;

		return $errorMessages;
	}
}