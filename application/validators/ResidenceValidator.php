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

abstract class ResidenceValidator extends BasicValidator
{
	public static function validateId(ResidenceForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		if(!$notEmpty->isValid($frm->getPersonId()))
		{
			$errorMessages[ResidenceForm::personId()][] = $notEmpty->getMessages();
		}
		else
		{
			if(!$validator->isValid($frm->getPersonId()))
			{
				$errorMessages[ResidenceForm::personId()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('PersonBusiness');
				
				$row = PersonBusiness::load($frm->getPersonId());				
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::personId()][][] = parent::getValidatorResources()->person->notfound;					
				}
			}
		}
		
		if($frm->getIdResidence())
		{
			if(!$notEmpty->isValid($frm->getIdResidence()))
			{
				$errorMessages[ResidenceForm::idResidence()][] = $notEmpty->getMessages();
			}
			else
			{
				if(!$validator->isValid($frm->getIdResidence()))
				{
					$errorMessages[ResidenceForm::idResidence()][] = $validator->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('ResidenceBusiness');
				
					$row = ResidenceBusiness::loadResidence($frm->getIdResidence());				
					if(count($row) == 0)
					{
						$errorMessages[ResidenceForm::idResidence()][][] = parent::getValidatorResources()->residence->notfound;					
					}
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateIdAddress(ResidenceForm &$frm, &$errorMessages = null)
	{
		
		if($frm->getAdrIdAddress())
		{
			$validator = parent::validatorInt();
			
			if(!$validator->isValid($frm->getAdrIdAddress()))
			{
				$errorMessages[ResidenceForm::idAddress()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateNumber(ResidenceForm &$frm, &$errorMessages = null)
	{
		
		if($frm->getAdrNumber())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getAdrNumber()) > 10)
			{
				$number = Utils::abbreviate($frm->getAdrNumber(), 33);				
				if(!$validator->isValid($number))
				{
					$errorMessages[ResidenceForm::number()][] = $validator->getMessages();
				}
			}		
			else if(!$validator->isValid($frm->getAdrNumber()))
			{
				$errorMessages[ResidenceForm::number()][] = $validator->getMessages();
			}
			else
			{
				if($frm->getAdrNumber() < 0)
				{
					$errorMessages[ResidenceForm::number()][][] = parent::getValidatorResources()->value->negative;
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateComplement(ResidenceForm &$frm, &$errorMessages = null)
	{
		if($frm->getAdrComplement())
		{
			$validator = parent::validatorStringLength(3, 30);
			if(strlen($frm->getAdrComplement()) > 30)
			{
				$complement = Utils::abbreviate($frm->getAdrComplement(), 31);
				if (!$validator->isValid($complement))
				{
					$errorMessages[ResidenceForm::complement()][] = $validator->getMessages();
				}
			}	
			else if (!$validator->isValid($frm->getAdrComplement()))
			{
				$errorMessages[ResidenceForm::complement()][] = $validator->getMessages();
			}
		}
		return $errorMessages;
	}
	
	public static function validateReference(ResidenceForm &$frm, &$errorMessages = null)
	{
		if($frm->getAdrReference())
		{
			$validator = parent::validatorStringLength(3, 30);
			if(strlen($frm->getAdrReference()) > 30)
			{
				$reference = Utils::abbreviate($frm->getAdrReference(), 31);
				if (!$validator->isValid($reference))
				{
					$errorMessages[ResidenceForm::reference()][] = $validator->getMessages();
				}
			}	
			else if (!$validator->isValid($frm->getAdrReference()))
			{
				$errorMessages[ResidenceForm::reference()][] = $validator->getMessages();
			}
		}
		return $errorMessages;
	}
	
	public static function validateTypeId(ResidenceForm &$frm, &$errorMessages = null)
	{	
		if($frm->getUbs())
		{
			$validator = parent::validatorInt();			
			if(strlen($frm->getUbs()) > 10)
			{
				$ubs = Utils::abbreviate($frm->getUbs(), 31);
				if(!$validator->isValid($ubs))
				{
					$errorMessages[ResidenceForm::ubs()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getUbs()))
			{
				$errorMessages[ResidenceForm::ubs()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneUbs($frm->getUbs());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::ubs()][][] = parent::getValidatorResources()->ubs->notfound;
				}
			}
		}
		
		if($frm->getLocality())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getLocality()) > 10)
			{
				$locality = Utils::abbreviate($frm->getLocality(), 31);
				if(!$validator->isValid($locality))
				{
					$errorMessages[ResidenceForm::locality()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getLocality()))
			{
				$errorMessages[ResidenceForm::locality()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneLocality($frm->getLocality());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::locality()][][] = parent::getValidatorResources()->locality->notfound;
				}
			}
		}
		
		if($frm->getMorada())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getMorada()) > 10)
			{
				$morada = Utils::abbreviate($frm->getMorada(), 31);
				if(!$validator->isValid($morada))
				{
					$errorMessages[ResidenceForm::morada()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getMorada()))
			{
				$errorMessages[ResidenceForm::morada()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneMorada($frm->getMorada());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::morada()][][] = parent::getValidatorResources()->morada->notfound;
				}
			}
		}
		
		if($frm->getStatus())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getStatus()) > 10)
			{
				$status = Utils::abbreviate($frm->getStatus(), 31);
				if(!$validator->isValid($status))
				{
					$errorMessages[ResidenceForm::status()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getStatus()))
			{
				$errorMessages[ResidenceForm::status()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneStatus($frm->getStatus());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::status()][][] = parent::getValidatorResources()->status->notfound;
				}
			}
		}
		
		if($frm->getBuilding())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getBuilding()) > 10)
			{
				$building = Utils::abbreviate($frm->getBuilding(), 31);
				if(!$validator->isValid($building))
				{
					$errorMessages[ResidenceForm::building()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getBuilding()))
			{
				$errorMessages[ResidenceForm::building()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneBuilding($frm->getBuilding());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::building()][][] = parent::getValidatorResources()->building->notfound;
				}
			}
		}
		
		if($frm->getSupply())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getSupply()) > 10)
			{
				$supply = Utils::abbreviate($frm->getSupply(), 31);
				if(!$validator->isValid($supply))
				{
					$errorMessages[ResidenceForm::supply()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getSupply()))
			{
				$errorMessages[ResidenceForm::supply()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneSupply($frm->getSupply());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::supply()][][] = parent::getValidatorResources()->supply->notfound;
				}
			}
		}
		
		if($frm->getWater())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getWater()) > 10)
			{
				$water = Utils::abbreviate($frm->getWater(), 31);
				if(!$validator->isValid($water))
				{
					$errorMessages[ResidenceForm::water()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getWater()))
			{
				$errorMessages[ResidenceForm::water()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneWater($frm->getWater());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::water()][][] = parent::getValidatorResources()->water->notfound;
				}
			}
		}
		
		if($frm->getIllumination())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getIllumination()) > 10)
			{
				$illumination = Utils::abbreviate($frm->getIllumination(), 31);
				if(!$validator->isValid($illumination))
				{
					$errorMessages[ResidenceForm::illumination()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getIllumination()))
			{
				$errorMessages[ResidenceForm::illumination()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneIllumination($frm->getIllumination());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::illumination()][][] = parent::getValidatorResources()->illumination->notfound;
				}
			}
		}
		
		if($frm->getSanitary())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getSanitary()) > 10)
			{
				$sanitary = Utils::abbreviate($frm->getSanitary(), 31);
				if(!$validator->isValid($sanitary))
				{
					$errorMessages[ResidenceForm::sanitary()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getSanitary()))
			{
				$errorMessages[ResidenceForm::sanitary()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneSanitary($frm->getSanitary());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::sanitary()][][] = parent::getValidatorResources()->sanitary->notfound;
				}
			}
		}
		
		if($frm->getTrash())
		{
			$validator = parent::validatorInt();
			
			if(strlen($frm->getTrash()) > 10)
			{
				$trash = Utils::abbreviate($frm->getTrash(), 31);
				if(!$validator->isValid($trash))
				{
					$errorMessages[ResidenceForm::trash()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getTrash()))
			{
				$errorMessages[ResidenceForm::trash()][] = $validator->getMessages();
			}
			else
			{				
				Zend_Loader::loadClass('ResidenceBusiness');
				$row = ResidenceBusiness::loadOneTrash($frm->getTrash());
				if(count($row) == 0)
				{
					$errorMessages[ResidenceForm::trash()][][] = parent::getValidatorResources()->trash->notfound;
				}
			}
		}
		return $errorMessages;
	}	
	
	public static function validatePhone(ResidenceForm &$frm, &$errorMessages = null)
	{
		if($frm->getDdd()){
			if($frm->getPhone()){
				$validator = parent::validatorInt();
				if($frm->getIdPhone()){
					foreach($frm->getIdPhone() as $id){
						if($id){
							if(!$validator->isValid($id))
							{
								$errorMessages[ResidenceForm::idPhone()][] = $validator->getMessages();
							}
						}
					}
				}
				
				$countType=0;				
				foreach($frm->getPhoneType() as $phoneType)
				{		
					if(!empty($phoneType))
					{
						if(!$validator->isValid($phoneType))
						{
							$errorMessages[ResidenceForm::phoneType()][] = $validator->getMessages();
						}
						else
						{
							Zend_Loader::loadClass('TelephoneBusiness');
							$row = TelephoneBusiness::loadType($phoneType);
							
							if(count($row) == 0)
							{
								$errorMessages[ResidenceForm::phoneType()][][] = parent::getValidatorResources()->phonetype->notfound;		
							}
						}
						$countType++;
					}					
				}
				
				$i = 0;
				$countDdd=0;
				foreach($frm->getDdd() as $ddd){
					if($ddd){
						if(!$validator->isValid($ddd))
						{
							$errorMessages[ResidenceForm::ddd()][] = $validator->getMessages();
						}
						else
						{
							if($ddd < 0)
							{
								$errorMessages[ResidenceForm::ddd()][][] = parent::getValidatorResources()->value->negative;
							}
							else if($ddd == 0)
							{
								$errorMessages[ResidenceForm::ddd()][][] = parent::getValidatorResources()->value->zero;
							}
							else
							{
								if(strlen($ddd) != 2)
								{
									$dddNumber = Utils::abbreviate($ddd, 5); 
									$errorMessages[ResidenceForm::ddd()][][] = parent::getValidatorResources()->text->long1.$dddNumber.parent::getValidatorResources()->text->long2.'2'.parent::getValidatorResources()->text->long3;
								}
							}
							$countDdd++;
						}
						$flagDdd[$i] = $ddd;
					}else{
						$flagDdd[$i] = $ddd;
					}
					$i++;				
				}
				
				$stringLength = parent::validatorStringLength(8, 8);
				$c = 0;
				$countNumber=0;
				foreach($frm->getPhone() as $phone)
				{
					if($phone)
					{
						if(strlen($phone) > 8)
						{
							$phoneNumber = Utils::abbreviate($phone, 9);
							if (!$stringLength->isValid($phoneNumber))
							{
								$errorMessages[ResidenceForm::phone()][] = $stringLength->getMessages();
							}
						}
						else if (!$stringLength->isValid($phone))
						{
							$errorMessages[ResidenceForm::phone()][] = $stringLength->getMessages();
						}
						else
						{
							if(!$validator->isValid($phone))
							{
								$errorMessages[ResidenceForm::phone()][] = $validator->getMessages();
							}
							else
							{
								if($phone < 0)
								{
									$errorMessages[ResidenceForm::phone()][][] = parent::getValidatorResources()->value->negative;
								}
								else
								{
									if($phone == 0)
									{
										$errorMessages[ResidenceForm::phone()][][] = parent::getValidatorResources()->value->zero;
									}
								}
							}
						}
						$countNumber++;
						$flagPhone[$c] = $phone;
					}
					else
					{
						$flagPhone[$c] = $phone;
					}
					$c++;
				}
				
				
				if(($countType == $countDdd) && ($countType == $countNumber))
				{
					;
				}
				else if($countType > $countDdd)
				{
					$errorMessages[ResidenceForm::ddd()][][] = parent::getValidatorResources()->ddd->donthave;
				}
				else if($countType > $countNumber)
				{
					$errorMessages[ResidenceForm::phone()][][] = parent::getValidatorResources()->phone->donthave;
				}
				else if($countType < $countDdd)
				{
					$errorMessages[ResidenceForm::phoneType()][][] = parent::getValidatorResources()->phonetype->donthave;
				}
				else if($countType < $countNumber)
				{
					$errorMessages[ResidenceForm::phoneType()][][] = parent::getValidatorResources()->phonetype->donthave;
				}
				
				if(sizeof($flagDdd) == sizeof($flagPhone))
				{
					$lenght = sizeof($flagDdd);
					for($j = 0; $j < $lenght; $j++)
					{
						if(($flagDdd[$j] == $flagPhone[$j]));
						else{
							if(!$validator->isValid($flagDdd[$j]))
							{
								$errorMessages[ResidenceForm::ddd()][] = $validator->getMessages();
							}
							if (!$stringLength->isValid($flagPhone[$j]))
							{
								$errorMessages[ResidenceForm::phone()][] = $stringLength->getMessages();
							}
						}							
					}
				}
				
			}
			else
			{
				$notEmpty = parent::validatorNotEmpty();
				foreach($frm->getPhone() as $phone){
					if(!$notEmpty->isValid($phone))
					{
						$errorMessages[ResidenceForm::phone()][] = $notEmpty->getMessages();
					}
				}
			}
		}
		else if($frm->getPhone()){
			$notEmpty = parent::validatorNotEmpty();
			foreach($frm->getDdd() as $ddd){
				if(!$notEmpty->isValid($ddd))
				{
					$errorMessages[ResidenceForm::ddd()][] = $notEmpty->getMessages();
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateResidenceEdit(ResidenceForm &$frm)
	{
		$errorMessages = null;
		
		self::validateId($frm, $errorMessages);
		self::validateIdAddress($frm, $errorMessages);
		self::validateNumber($frm, $errorMessages);
		self::validateComplement($frm, $errorMessages);
		self::validateReference($frm, $errorMessages);
		self::validateTypeId($frm, $errorMessages);		
		self::validatePhone($frm, $errorMessages);
				
		return $errorMessages;	
	}
	
	public static function validateResidenceTelephone(ResidenceForm &$frm)
	{
		$errorMessages = null;
		
		self::validateId($frm, $errorMessages);	
		self::validatePhone($frm, $errorMessages);
				
		return $errorMessages;	
	}
}