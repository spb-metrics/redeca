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

abstract class PersonValidator extends BasicValidator
{
	/**************************************************************************************
	 * ************************** validaçao endereço temporário ***************************
	 * ************************************************************************************
	*/
	public static function validateIdAddress(PersonForm $frm, &$errorMessages = null)
	{
		if($frm->getAdrIdAddress())
		{
			$validator = parent::validatorInt();
			
			if($frm->getAdrIdAddress() < 0)
			{
				$errorMessages[PersonForm::adr_idAddress()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if (strlen($frm->getAdrIdAddress()) > 10)
			{
				$address = Utils::abbreviate($frm->getAdrIdAddress(), 31);
				if (!$validator->isValid($address))
				{
					$errorMessages[PersonForm::adr_idAddress()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getAdrIdAddress()))
			{
				$errorMessages[PersonForm::adr_idAddress()][] = $validator->getMessages();
			}
			else if($frm->getAdrIdAddress() == 0)
			{
				$errorMessages[PersonForm::adr_idAddress()][][] = parent::getValidatorResources()->value->zero;
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateNumber(PersonForm &$frm, &$errorMessages = null)
	{
		
		if($frm->getAdrNumber())
		{
			$validator = parent::validatorInt();
			
			if($frm->getAdrNumber() < 0)
			{
				$errorMessages[PersonForm::adr_number()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if (strlen($frm->getAdrNumber()) > 10)
			{
				$number = Utils::abbreviate($frm->getAdrNumber(), 31);
				if (!$validator->isValid($number))
				{
					$errorMessages[PersonForm::adr_number()][] = $validator->getMessages();
				}
			}
			else if(!$validator->isValid($frm->getAdrNumber()))
			{
				$errorMessages[PersonForm::adr_number()][] = $validator->getMessages();
			}
			else if($frm->getAdrNumber() == 0)
			{
				$errorMessages[PersonForm::adr_number()][][] = parent::getValidatorResources()->value->zero;
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateComplement(PersonForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorStringLength(0, 30);
			
		if(strlen($frm->getAdrComplement()) > 30)
		{
			$name = Utils::abbreviate($frm->getAdrComplement(), 31);
			if (!$validator->isValid($name))
			{
				$errorMessages[PersonForm::adr_complement()][] = $validator->getMessages();
			}
		}
		else if (!$validator->isValid($frm->getAdrComplement()))
		{
			$errorMessages[PersonForm::adr_complement()][] = $validator->getMessages();
		}
		return $errorMessages;
	}
	
	public static function validateReference(PersonForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorStringLength(0, 50);
			
		if(strlen($frm->getAdrReference()) > 30)
		{
			$name = Utils::abbreviate($frm->getAdrReference(), 31);
			if (!$validator->isValid($name))
			{
				$errorMessages[PersonForm::adr_reference()][] = $validator->getMessages();
			}
		}
		else if (!$validator->isValid($frm->getAdrReference()))
		{
			$errorMessages[PersonForm::adr_reference()][] = $validator->getMessages();
		}
		return $errorMessages;
	}
	
	public static function validateLiveSince(PersonForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorDate();
			
		//formata a data inserida pelo usuário
		$formatedDate = BasicForm::dateFormat($frm->getLiveSince());
		
		if((strlen($frm->getLiveSince()) > 0) && !$validator->isValid($formatedDate))
		{
			$errorMessages[PersonForm::liveSince()][] = $validator->getMessages();
		}
		
		return $errorMessages;
	}
	
	/******************************************************************************************
	 * ************************** fim validaçao endereço temporário ***************************
	 * ****************************************************************************************
	 */
	
	public static function validatePersonId(&$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if(!$notEmpty->isValid($frm->getId()))
		{
			$errorMessages[PersonForm::id()][] = $notEmpty->getMessages();
		}
		else
		{
			$validator = parent::validatorInt();
			
			if(!$validator->isValid($frm->getId()))
			{
				$errorMessages[PersonForm::id()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('PersonBusiness');
				
				$row = PersonBusiness::load($frm->getId());				
				if(count($row) == 0)
				{
					$errorMessages[PersonForm::id()][][] = parent::getValidatorResources()->person->notfound;					
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validatePersonName(&$frm, &$errorMessages = null)
	{		
		$notEmpty = parent::validatorNotEmpty();
		$validator = parent::validatorStringLength(3, 50);
		
		if(!$notEmpty->isValid($frm->getName()))
		{
			$errorMessages[PersonForm::name()][] = $notEmpty->getMessages();
		}
		else if(strlen($frm->getName()) > 50)
		{				
			$name = Utils::abbreviate($frm->getName(), 31);
			$errorMessages[PersonForm::name()][][] = parent::getValidatorResources()->text->long1.$name.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;
		}
		else if (!$validator->isValid($frm->getName()))
		{
			$errorMessages[PersonForm::name()][] = $validator->getMessages();
		}
		else
		{
			$validatorWord = parent::validatorWords($frm->getName());
			if(!$validatorWord->isValid($frm->getName()))
			{
				$errorMessages[PersonForm::name()][] = $validatorWord->getMessages();
			}
		}		
		
		return $errorMessages;
	}
	
	public static function validatePersonNickname(&$frm, &$errorMessages = null)
	{
		$validator = parent::validatorStringLength(0, 15);
		
		if(strlen($frm->getNickname()) > 15)
		{
			$nick = Utils::abbreviate($frm->getNickname(), 31);
			if (!$validator->isValid($nick))
			{
				$errorMessages[PersonForm::nickname()][] = $validator->getMessages();
			}
		}
		else if (!$validator->isValid($frm->getNickname()))
		{
			$errorMessages[PersonForm::nickname()][] = $validator->getMessages();
		}	
		return $errorMessages;
	}
	
	public static function validatePersonSex(&$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if(!$notEmpty->isValid($frm->getSex()))
		{
			$errorMessages[PersonForm::sex()][] = $notEmpty->getMessages();
		}
		else
		{
			$validator = parent::validatorStringLength(1, 1);
			
			if(strlen($frm->getSex()) > 1)
			{
				$sexName = Utils::abbreviate($frm->getSex(), 31);
				if (!$validator->isValid($sexName))
				{
					$errorMessages[PersonForm::sex()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getSex()))
			{
				$errorMessages[PersonForm::sex()][] = $validator->getMessages();
			}
			else
			{
				if(($frm->getSex() == Constants::MAN) || ($frm->getSex() == Constants::WOMAN))
				{
					;
				}
				else
				{
					$errorMessages[PersonForm::sex()][][] = parent::getValidatorResources()->sex->notfound;
				}
			}
		}
		return $errorMessages;
	}
	
	public static function validatePersonMaritalStatus(&$frm, &$errorMessages = null)
	{
		
		$validator = parent::validatorInt();
		
		if(strlen($frm->getMaritalStatus()) > 10)
		{
			$marital = Utils::abbreviate($frm->getMaritalStatus(), 31);
			if (!$validator->isValid($marital))
			{
				$errorMessages[PersonForm::maritalStatus()][] = $validator->getMessages();
			}
		}
		else if (!$validator->isValid($frm->getMaritalStatus()))
		{
			$errorMessages[PersonForm::maritalStatus()][] = $validator->getMessages();
		}
		else
		{
			Zend_Loader::loadClass('MaritalStatusBusiness');
			$row = MaritalStatusBusiness::load($frm->getMaritalStatus());
			if(count($row) == 0)
			{
				$errorMessages[PersonForm::maritalStatus()][][] = parent::getValidatorResources()->marital->notfound;
			}
		}
		return $errorMessages;
	}
	
	public static function validatePersonRace(&$frm, &$errorMessages = null)
	{
		
		$validator = parent::validatorInt();
		if(strlen($frm->getRace()) > 10)
		{
			$race = Utils::abbreviate($frm->getRace(), 31);
			if (!$validator->isValid($race))
			{
				$errorMessages[PersonForm::race()][] = $validator->getMessages();
			}
		}
		else if (!$validator->isValid($frm->getRace()))
		{
			$errorMessages[PersonForm::race()][] = $validator->getMessages();
		}
		else
		{
			Zend_Loader::loadClass('RaceBusiness');
			$row = RaceBusiness::load($frm->getRace());
			if(count($row) == 0)
			{
				$errorMessages[PersonForm::race()][][] = parent::getValidatorResources()->race->notfound;
			}
		}
		return $errorMessages;
	}
	
	public static function validatePersonTattoo(&$frm, &$errorMessages = null)
	{
		$validator = parent::validatorStringLength(0, 50);
		
		if(strlen($frm->getTattoo()) > 50)
		{
			$tattoo = Utils::abbreviate($frm->getTattoo(), 31);
			$errorMessages[PersonForm::tattoo()][][] = parent::getValidatorResources()->text->long1.$tattoo.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;
		}
		else if (!$validator->isValid($frm->getTattoo()))
		{
			$errorMessages[PersonForm::tattoo()][] = $validator->getMessages();
		}
		else
		{
			$validatorWord = parent::validatorWords($frm->getTattoo());
			if(!$validatorWord->isValid($frm->getTattoo()))
			{
				$errorMessages[PersonForm::tattoo()][] = $validatorWord->getMessages();
			}
		}
		return $errorMessages;
	}
	
	public static function validatePersonBirthDate(PersonForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		
		if(!$notEmpty->isValid($frm->getBirthDate()))
		{
			$errorMessages[PersonForm::birthDate()][] = $notEmpty->getMessages();
		}
		else
		{
			if(strlen($frm->getBirthDate()) != 10)
			{
				$errorMessages[PersonForm::birthDate()][][] = parent::getValidatorResources()->date->long;
			}
			else
			{
				Zend_Loader::loadClass('PersonForm');
				
				$validator = parent::validatorDate();
				
				$dateFormat = PersonForm::dateFormat($frm->getBirthDate());			
				
				if (!$validator->isValid($dateFormat))
				{
					$dateFormat = PersonForm::dateFormatForm($dateFormat);
					$errorMessages[PersonForm::birthDate()][][] = parent::getValidatorResources()->person->data->error;
				}
			}
		}
		return $errorMessages;
	}
	
	public static function validatePersonDeathDate(PersonForm &$frm, &$errorMessages = null)
	{
		if($frm->getDeathDate())
		{	
			if(strlen($frm->getDeathDate()) != 10)
			{
				$errorMessages[PersonForm::deathDate()][][] = parent::getValidatorResources()->date->long;
			}
			else
			{
				Zend_Loader::loadClass('PersonForm');
				
				$validator = parent::validatorDate();
				
				$dateFormat = PersonForm::dateFormat($frm->getDeathDate());			
				
				if (!$validator->isValid($dateFormat))
				{
					$errorMessages[PersonForm::deathDate()][][] = parent::getValidatorResources()->person->data->error;
				}
			}
		}
		return $errorMessages;
	}
	
	public static function validatePersonNationality(PersonForm &$frm, &$errorMessages = null)
	{
		
		$validator = parent::validatorInt();
		
		if(strlen($frm->getNationality()) > 10)
		{
			$nationality = Utils::abbreviate($frm->getNationality(), 31);
			if (!$validator->isValid($nationality))
			{
				$errorMessages[PersonForm::nationality()][] = $validator->getMessages();
			}
		}
		else if (!$validator->isValid($frm->getNationality()))
		{
			$errorMessages[PersonForm::nationality()][] = $validator->getMessages();
		}
		else
		{
			Zend_Loader::loadClass('NationalityBusiness');
			$row = NationalityBusiness::load($frm->getNationality());
			if(count($row) == 0)
			{
				$errorMessages[PersonForm::nationality()][][] = parent::getValidatorResources()->nationality->notfound;
			}
		}
		
		if($frm->getNativeCountry())
		{
			$validator = parent::validatorStringLength(0, 15);
			
			if(strlen($frm->getNativeCountry()) > 15)
			{
				$native = Utils::abbreviate($frm->getNativeCountry(), 31);
				if (!$validator->isValid($native))
				{
					$errorMessages[PersonForm::nativeCountry()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getNativeCountry()))
			{
				$errorMessages[PersonForm::nativeCountry()][] = $validator->getMessages();
			}
		}
		
		if($frm->getArrivalDate())
		{
			if(($frm->getArrivalDate()) && ($frm->getArrivalDate() != "00/00/0000"))
			{
				if(strlen($frm->getArrivalDate()) != 10)
				{
					$errorMessages[PersonForm::arrivalDate()][][] = parent::getValidatorResources()->date->long;
				}
				else
				{
					Zend_Loader::loadClass('PersonForm');
					
					$validator = parent::validatorDate();
					
					$dateFormat = PersonForm::dateFormat($frm->getArrivalDate());			
					
					if (!$validator->isValid($dateFormat))
					{
						$dateFormat = PersonForm::dateFormatForm($dateFormat);
						$errorMessages[PersonForm::arrivalDate()][][] = parent::getValidatorResources()->person->data->error;
					}
				}
			}	
		}
		return $errorMessages;
	}
	
	public static function validatePersonDeficiency(PersonForm &$frm, &$errorMessages = null)
	{		
		if(is_array($frm->getDeficiency()))
		{
			$validator = parent::validatorInt();
			
			foreach($frm->getDeficiency() as $deficiency)
			{				
				if(strlen($deficiency) > 10)
				{
					$def = Utils::abbreviate($deficiency, 31);
					if (!$validator->isValid($def))
					{
						$errorMessages[PersonForm::deficiency()][] = $validator->getMessages();
					}
				}
				else if (!$validator->isValid($deficiency))
				{
					$errorMessages[PersonForm::deficiency()][] = $validator->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('DeficiencyBusiness');
					$row = DeficiencyBusiness::loadDeficiency($deficiency);
					
					if(count($row) == 0)
					{
						$errorMessages[PersonForm::deficiency()][][] = parent::getValidatorResources()->deficiency->notfound;
					}					
				}
			}
		}
		return $errorMessages;
	}

	public static function validatePersonCpf(PersonForm &$frm, &$errorMessages = null)
	{
		if($frm->getCpf())
		{
			$validatorCpf = parent::validatorCpf();
			$validator = parent::validatorStringLength(11, 11);
			if($frm->getCpf() < 0)
			{
				$errorMessages[PersonForm::cpf()][][] = parent::getValidatorResources()->value->negative;
			}
			else if(strlen($frm->getCpf()) > 11)
			{
				$cpfNumber = Utils::abbreviate($frm->getCpf(), 31);
				if (!$validator->isValid($cpfNumber))
				{
					$errorMessages[PersonForm::cpf()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getCpf()))
			{
				$errorMessages[PersonForm::cpf()][] = $validator->getMessages();
			}			
			else if(!$validatorCpf->isValid($frm->getCpf()))
			{
				$errorMessages[PersonForm::cpf()][] = $validatorCpf->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validatePersonRg(PersonForm &$frm, &$errorMessages = null)
	{				
		$validatorInt = parent::validatorInt();
		if($frm->getRg())
		{			
			$validator = parent::validatorStringLength(8, 8);
								
			if(strlen($frm->getRg()) > 8)
			{
				$rgNumber = Utils::abbreviate($frm->getRg(), 31);
				if (!$validator->isValid($rgNumber))
				{
					$errorMessages[PersonForm::rg()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getRg()))
			{
				$errorMessages[PersonForm::rg()][] = $validator->getMessages();
			}
			else
			{				
				if(strlen($frm->getRg()) > 10)
				{
					$rgNumber = Utils::abbreviate($frm->getRg(), 31);
					if (!$validatorInt->isValid($rgNumber))
					{
						$errorMessages[PersonForm::rg()][] = $validatorInt->getMessages();
					}
				}
				else if(!$validatorInt->isValid($frm->getRg()))
				{
					$errorMessages[PersonForm::rg()][] = $validatorInt->getMessages();
				}
				else if($frm->getRg() == 0)
				{
					$errorMessages[PersonForm::rg()][][] = parent::getValidatorResources()->value->zero;
				}
				else if($frm->getRg() < 0)
				{
					$errorMessages[PersonForm::rg()][][] = parent::getValidatorResources()->value->negative;
				}
			}
		}			
		
		if($frm->getRgComplement()) 	
		{				
			$validator = parent::validatorStringLength(1, 1);
			if(strlen($frm->getRgComplement()) > 30)
			{
				$rgComplement = Utils::abbreviate($frm->getRgComplement(), 31);
				if (!$validator->isValid($rgComplement))
				{
					$errorMessages[PersonForm::rgComplement()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getRgComplement()))
			{
				$errorMessages[PersonForm::rgComplement()][] = $validator->getMessages();
			}
		}
		
		if($frm->getRgEmissionDate())
		{
			if(strlen($frm->getRgEmissionDate()) != 10)
			{
				$errorMessages[PersonForm::rgEmissionDate()][][] = parent::getValidatorResources()->date->long;
			}
			else
			{
				Zend_Loader::loadClass('PersonForm');
					
				$validator = parent::validatorDate();
				
				$dateFormat = PersonForm::dateFormat($frm->getRgEmissionDate());			
				
				if (!$validator->isValid($dateFormat))
				{
					$dateFormat = PersonForm::dateFormatForm($dateFormat);
					$errorMessages[PersonForm::rgEmissionDate()][][] = parent::getValidatorResources()->person->data->error;
				}
			}
		}
		
		if($frm->getRgState())
		{
			if(strlen($frm->getRgState()) > 10)
			{
				$rgState = Utils::abbreviate($frm->getRgState(), 31);
				if (!$validatorInt->isValid($rgState))
				{
					$errorMessages[PersonForm::rgState()][] = $validatorInt->getMessages();
				}
			}
			else if (!$validatorInt->isValid($frm->getRgState()))
			{
				$errorMessages[PersonForm::rgState()][] = $validatorInt->getMessages();
			}
			else if($frm->getRgState() < 0)
			{
				$errorMessages[PersonForm::rgState()][][] = parent::getValidatorResources()->value->negative;
			}
			else if($frm->getRgState() == 0)
			{
				$errorMessages[PersonForm::rgState()][][] = parent::getValidatorResources()->value->zero;
			}
			else
			{
				Zend_Loader::loadClass('UFBusiness');
				$row = UFBusiness::load($frm->getRgState());
				if(count($row) == 0)
				{
					$errorMessages[PersonForm::rgState()][][] = parent::getValidatorResources()->uf->notfound;
				}
			}
		}
		
		if($frm->getRgSender())
		{
			$validator = parent::validatorStringLength(0, 5);
			if(strlen($frm->getRgSender()) > 5)
			{
				$rgSender = Utils::abbreviate($frm->getRgSender(), 31);
				if (!$validator->isValid($rgSender))
				{
					$errorMessages[PersonForm::rgSender()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getRgSender()))
			{
				$errorMessages[PersonForm::rgSender()][] = $validator->getMessages();
			}
		}
		return $errorMessages;
	}

	public static function validatePersonNis(PersonForm &$frm, &$errorMessages = null)
	{		
		$validator = parent::validatorStringLength(0, 21);
		
		if(strlen($frm->getNisNumber()) > 21)
		{
			$nis = Utils::abbreviate($frm->getNisNumber(), 31);
			if (!$validator->isValid($nis))
			{
				$errorMessages[PersonForm::nisNumber()][] = $validator->getMessages();
			}
		}	
		else if (!$validator->isValid($frm->getNisNumber()))
		{
			$errorMessages[PersonForm::nisNumber()][] = $validator->getMessages();
		}
		return $errorMessages;
	}

	public static function validatePersonSus(PersonForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorStringLength(0, 15);
		
		if(strlen($frm->getSusNumber()) > 15)
		{
			$sus = Utils::abbreviate($frm->getSusNumber(), 31);
			if (!$validator->isValid($sus))
			{
				$errorMessages[PersonForm::susNumber()][] = $validator->getMessages();
			}
		}
		else if (!$validator->isValid($frm->getSusNumber()))
		{
			$errorMessages[PersonForm::susNumber()][] = $validator->getMessages();
		}
		return $errorMessages;
	}

	public static function validatePersonRa(PersonForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorStringLength(0, 20);
		
		if(strlen($frm->getRa()) > 20)
		{
			$ra = Utils::abbreviate($frm->getRa(), 31);
			if (!$validator->isValid($ra))
			{
				$errorMessages[PersonForm::ra()][] = $validator->getMessages();
			}
		}
		else if (!$validator->isValid($frm->getRa()))
		{
			$errorMessages[PersonForm::ra()][] = $validator->getMessages();
		}
		return $errorMessages;
	}
	
	public static function validatePersonTitle(PersonForm &$frm, &$errorMessages = null)
	{
		if($frm->getTitleVoter())
		{
			$validator = parent::validatorStringLength(12, 12);
			
			if(strlen($frm->getTitleVoter()) > 12)
			{
				$voter = Utils::abbreviate($frm->getTitleVoter(), 31);
				if (!$validator->isValid($voter))
				{
					$errorMessages[PersonForm::titleVoter()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getTitleVoter()))
			{
				$errorMessages[PersonForm::titleVoter()][] = $validator->getMessages();
			}
			else
			{
				$validator = parent::validatorFloat();
			
				if($frm->getTitleVoter() < 0)
				{
					$errorMessages[PersonForm::titleVoter()][][] = parent::getValidatorResources()->value->negative;
				}			
				else if (strlen($frm->getTitleVoter()) > 13)
				{
					$voter = Utils::abbreviate($frm->getTitleVoter(), 31);
					if (!$validator->isValid($voter))
					{
						$errorMessages[PersonForm::titleVoter()][] = $validator->getMessages();
					}
				}
				else if (!$validator->isValid($frm->getTitleVoter()))
				{
					$errorMessages[PersonForm::titleVoter()][] = $validator->getMessages();
				}
				else if($frm->getTitleVoter() == 0)
				{
					$errorMessages[PersonForm::titleVoter()][][] = parent::getValidatorResources()->value->zero;
				}
			}
		}
		
		$validatorInt = parent::validatorInt();
		$validator = parent::validatorStringLength(0, 4);		
		if($frm->getTitleZone())
		{
			if($frm->getTitleZone() < 0)
			{
				$errorMessages[PersonForm::titleZone()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if(strlen($frm->getTitleZone()) > 10)
			{
				$zone = Utils::abbreviate($frm->getTitleZone(), 31);
				if(!$validatorInt->isValid($zone))
				{
					$errorMessages[PersonForm::titleZone()][] = $validatorInt->getMessages();
				}
			}
			else if(!$validatorInt->isValid($frm->getTitleZone()))
			{
				$errorMessages[PersonForm::titleZone()][] = $validatorInt->getMessages();
			}
			else if($frm->getTitleZone() == 0)
			{
				$errorMessages[PersonForm::titleZone()][][] = parent::getValidatorResources()->value->zero;
			}
			else
			{				
				if(strlen($frm->getTitleZone()) > 4)
				{
					$zone = Utils::abbreviate($frm->getTitleZone(), 31);
					if (!$validator->isValid($zone))
					{
						$errorMessages[PersonForm::titleZone()][] = $validator->getMessages();
					}
				}
				else if (!$validator->isValid($frm->getTitleZone()))
				{
					$errorMessages[PersonForm::titleZone()][] = $validator->getMessages();
				}
			}
		}
		
		if($frm->getTitleSession())
		{
			if($frm->getTitleSession() < 0)
			{
				$errorMessages[PersonForm::titleSession()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if(strlen($frm->getTitleSession()) > 10)
			{
				$session = Utils::abbreviate($frm->getTitleSession(), 31);
				if(!$validatorInt->isValid($session))
				{
					$errorMessages[PersonForm::titleSession()][] = $validatorInt->getMessages();
				}
			}
			else if(!$validatorInt->isValid($frm->getTitleSession()))
			{
				$errorMessages[PersonForm::titleSession()][] = $validatorInt->getMessages();
			}
			else if($frm->getTitleSession() == 0)
			{
				$errorMessages[PersonForm::titleSession()][][] = parent::getValidatorResources()->value->zero;
			}
			else
			{				
				if(strlen($frm->getTitleZone()) > 4)
				{
					$session = Utils::abbreviate($frm->getTitleSession(), 31);
					if (!$validator->isValid($session))
					{
						$errorMessages[PersonForm::titleSession()][] = $validator->getMessages();
					}
				}
				else if (!$validator->isValid($frm->getTitleSession()))
				{
					$errorMessages[PersonForm::titleSession()][] = $validator->getMessages();
				}
			}
		}		
		
		return $errorMessages;
	}

	public static function validatePersonCtps(PersonForm &$frm, &$errorMessages = null)
	{		
		if($frm->getCtpsNumber())
		{
			$validator = parent::validatorInt();
			if($frm->getCtpsNumber() < 0)
			{
				$errorMessages[PersonForm::ctpsNumber()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if(strlen($frm->getCtpsNumber()) > 10)
			{
				$ctpsNumber = Utils::abbreviate($frm->getCtpsNumber(), 31);
				if (!$validator->isValid($ctpsNumber))
				{
					$errorMessages[PersonForm::ctpsNumber()][] = $validator->getMessages();
				}
			}	
			else if (!$validator->isValid($frm->getCtpsNumber()))
			{
				$errorMessages[PersonForm::ctpsNumber()][] = $validator->getMessages();
			}
			else if($frm->getCtpsNumber() == 0)
			{
				$errorMessages[PersonForm::ctpsNumber()][][] = parent::getValidatorResources()->value->zero;
			}
		}
		
		if($frm->getCtpsSeries())
		{
			$validator = parent::validatorInt();
			if($frm->getCtpsSeries() < 0)
			{
				$errorMessages[PersonForm::ctpsSeries()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if(strlen($frm->getCtpsSeries()) > 10)
			{
				$ctpsSeries = Utils::abbreviate($frm->getCtpsSeries(), 31);
				if (!$validator->isValid($ctpsSeries))
				{
					$errorMessages[PersonForm::ctpsSeries()][] = $validator->getMessages();
				}
			}	
			else if (!$validator->isValid($frm->getCtpsSeries()))
			{
				$errorMessages[PersonForm::ctpsSeries()][] = $validator->getMessages();
			}		
			else if($frm->getCtpsSeries() == 0)
			{
				$errorMessages[PersonForm::ctpsSeries()][][] = parent::getValidatorResources()->value->zero;
			}
		}
		
		if($frm->getCtpsState())
		{
			$validator = parent::validatorInt();
			if($frm->getCtpsState() < 0)
			{
				$errorMessages[PersonForm::ctpsState()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if(strlen($frm->getCtpsState()) > 10)
			{
				$ctpsState = Utils::abbreviate($frm->getCtpsState(), 31);
				if (!$validator->isValid($ctpsState))
				{
					$errorMessages[PersonForm::ctpsState()][] = $validator->getMessages();
				}
			}	
			else if (!$validator->isValid($frm->getCtpsState()))
			{
				$errorMessages[PersonForm::ctpsState()][] = $validator->getMessages();
			}
			else if($frm->getCtpsState() == 0)
			{
				$errorMessages[PersonForm::ctpsState()][][] = parent::getValidatorResources()->value->zero;
			}
			else
			{
				Zend_Loader::loadClass('UFBusiness');
				$row = UFBusiness::load($frm->getCtpsState());
				if(count($row) == 0)
				{
					$errorMessages[PersonForm::ctpsState()][][] = parent::getValidatorResources()->uf->notfound;
				}
			}	
		}
		
		if($frm->getCtpsEmissionDate())
		{
			if(strlen($frm->getCtpsEmissionDate()) != 10)
			{
				$errorMessages[PersonForm::ctpsEmissionDate()][][] = parent::getValidatorResources()->date->long;
			}
			else
			{
				Zend_Loader::loadClass('PersonForm');
					
				$validator = parent::validatorDate();
				
				$dateFormat = PersonForm::dateFormat($frm->getCtpsEmissionDate());			
				
				if (!$validator->isValid($dateFormat))
				{
					$dateFormat = PersonForm::dateFormatForm($dateFormat);
					$errorMessages[PersonForm::ctpsEmissionDate()][][] = parent::getValidatorResources()->person->data->error;
				}
			}	
		}
		
		return $errorMessages;
	}

	public static function validatePersonCivilCertificate(PersonForm &$frm, &$errorMessages = null)
	{
		if($frm->getCivilCertificateType())
		{
			$validator = parent::validatorInt();
			if($frm->getCivilCertificateType() < 0)
			{
				$errorMessages[PersonForm::civilCertificateType()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if (strlen($frm->getCivilCertificateType()) > 10)
			{
				$type = Utils::abbreviate($frm->getCivilCertificateType(), 31);
				if (!$validator->isValid($type))
				{
					$errorMessages[PersonForm::civilCertificateType()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getCivilCertificateType()))
			{
				$errorMessages[PersonForm::civilCertificateType()][] = $validator->getMessages();
			}
			else if($frm->getCivilCertificateType() == 0)
			{
				$errorMessages[PersonForm::civilCertificateType()][][] = parent::getValidatorResources()->value->zero;
			}
		}
			
		if($frm->getCivilCertificateBook())
		{			
			$validator = parent::validatorInt();
			if($frm->getCivilCertificateBook() < 0)
			{
				$errorMessages[PersonForm::civilCertificateBook()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if (strlen($frm->getCivilCertificateBook()) > 10)
			{
				$book = Utils::abbreviate($frm->getCivilCertificateBook(), 31);
				if (!$validator->isValid($book))
				{
					$errorMessages[PersonForm::civilCertificateBook()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getCivilCertificateBook()))
			{
				$errorMessages[PersonForm::civilCertificateBook()][] = $validator->getMessages();
			}		
			else if($frm->getCivilCertificateBook() == 0)
			{
				$errorMessages[PersonForm::civilCertificateBook()][][] = parent::getValidatorResources()->value->zero;
			}
		}
		
		if($frm->getCivilCertificateLeaf())
		{			
			$validator = parent::validatorInt();
			if($frm->getCivilCertificateLeaf() < 0)
			{
				$errorMessages[PersonForm::civilCertificateLeaf()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if (strlen($frm->getCivilCertificateLeaf()) > 10)
			{
				$leaf = Utils::abbreviate($frm->getCivilCertificateLeaf(), 31);
				if (!$validator->isValid($leaf))
				{
					$errorMessages[PersonForm::civilCertificateLeaf()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getCivilCertificateLeaf()))
			{
				$errorMessages[PersonForm::civilCertificateLeaf()][] = $validator->getMessages();
			}		
			else if($frm->getCivilCertificateLeaf() == 0)
			{
				$errorMessages[PersonForm::civilCertificateLeaf()][][] = parent::getValidatorResources()->value->zero;
			}
		}
		
		if($frm->getCivilCertificateEmissionDate())
		{
			if(strlen($frm->getCivilCertificateEmissionDate()) != 10)
			{
				$errorMessages[PersonForm::civilCertificateEmissionDate()][][] = parent::getValidatorResources()->date->long;
			}
			else
			{
				Zend_Loader::loadClass('PersonForm');
					
				$validator = parent::validatorDate();
				
				$dateFormat = PersonForm::dateFormat($frm->getCivilCertificateEmissionDate());			
				
				if (!$validator->isValid($dateFormat))
				{
					$dateFormat = PersonForm::dateFormatForm($dateFormat);
					$errorMessages[PersonForm::civilCertificateEmissionDate()][][] = parent::getValidatorResources()->person->data->error;
				}
			}	
		}
		
		if($frm->getCivilCertificateTerm())
		{
			$validator = parent::validatorInt();
			if($frm->getCivilCertificateTerm() < 0)
			{
				$errorMessages[PersonForm::civilCertificateTerm()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if (strlen($frm->getCivilCertificateTerm()) > 10)
			{
				$term = Utils::abbreviate($frm->getCivilCertificateTerm(), 31);
				if (!$validator->isValid($term))
				{
					$errorMessages[PersonForm::civilCertificateTerm()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getCivilCertificateTerm()))
			{
				$errorMessages[PersonForm::civilCertificateTerm()][] = $validator->getMessages();
			}
			else if($frm->getCivilCertificateTerm() == 0)
			{
				$errorMessages[PersonForm::civilCertificateTerm()][][] = parent::getValidatorResources()->value->zero;
			}
		}
		
		if($frm->getCivilCertificateState())
		{
			$validator = parent::validatorInt();
			if($frm->getCivilCertificateState() < 0)
			{
				$errorMessages[PersonForm::civilCertificateState()][][] = parent::getValidatorResources()->value->negative;
			}			
			else if (strlen($frm->getCivilCertificateState()) > 10)
			{
				$state = Utils::abbreviate($frm->getCivilCertificateState(), 31);
				if (!$validator->isValid($state))
				{
					$errorMessages[PersonForm::civilCertificateState()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getCivilCertificateState()))
			{
				$errorMessages[PersonForm::civilCertificateState()][] = $validator->getMessages();
			}
			else if($frm->getCivilCertificateState() == 0)
			{
				$errorMessages[PersonForm::civilCertificateState()][][] = parent::getValidatorResources()->value->zero;
			}
			else
			{
				Zend_Loader::loadClass('UFBusiness');
				$row = UFBusiness::load($frm->getCivilCertificateState());
				if(count($row) == 0)
				{
					$errorMessages[PersonForm::civilCertificateState()][][] = parent::getValidatorResources()->uf->notfound;
				}
			}
		}
		
		if($frm->getCivilCertificateOfficeName())
		{
			$validator = parent::validatorStringLength(0, 50);
			if(strlen($frm->getCivilCertificateOfficeName()) > 50)
			{
				$name = Utils::abbreviate($frm->getCivilCertificateOfficeName(), 30);
				$errorMessages[PersonForm::civilCertificateOfficeName()][][] = parent::getValidatorResources()->text->long1.$name.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;				
			}
			else if (!$validator->isValid($frm->getCivilCertificateOfficeName()))
			{
				$errorMessages[PersonForm::civilCertificateOfficeName()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validatePersonDataForAdd(PersonForm &$frm)
	{
		$errorMessages = null;
		
		self::validatePersonName($frm, $errorMessages);
		self::validatePersonNickname($frm, $errorMessages);
		self::validatePersonSex($frm, $errorMessages);
		self::validatePersonMaritalStatus($frm, $errorMessages);
		self::validatePersonRace($frm, $errorMessages);
		self::validatePersonTattoo($frm, $errorMessages);
		self::validatePersonBirthDate($frm, $errorMessages);
		self::validatePersonNationality($frm, $errorMessages);
		self::validatePersonDeficiency($frm, $errorMessages);
		
		return $errorMessages;	
	}	
	
	public static function validatePersonDataForEdit(PersonForm &$frm)
	{
		$errorMessages = null;
		
		self::validatePersonId($frm, $errorMessages);
		self::validatePersonName($frm, $errorMessages);
		self::validatePersonNickname($frm, $errorMessages);
		self::validatePersonSex($frm, $errorMessages);
		self::validatePersonMaritalStatus($frm, $errorMessages);
		self::validatePersonRace($frm, $errorMessages);
		self::validatePersonTattoo($frm, $errorMessages);
		self::validatePersonBirthDate($frm, $errorMessages);
		self::validatePersonDeathDate($frm, $errorMessages);
		self::validatePersonNationality($frm, $errorMessages);
		self::validatePersonDeficiency($frm, $errorMessages);
		self::validatePersonCpf($frm, $errorMessages);
		self::validatePersonRg($frm, $errorMessages);
		self::validatePersonNis($frm, $errorMessages);
		self::validatePersonSus($frm, $errorMessages);
		self::validatePersonRa($frm, $errorMessages);
		self::validatePersonTitle($frm, $errorMessages);
		self::validatePersonCtps($frm, $errorMessages);
		self::validatePersonCivilCertificate($frm, $errorMessages);
				
		return $errorMessages;	
	}
	
	public static function validatePerson(PersonForm &$frm)
	{
		$errorMessages = null;
		PersonValidator::validatePersonData($frm, $errorMessages);
		PersonValidator::validatePersonId($frm, $errorMessages);

		return $errorMessages;
	}
	
	/**
	 * Método que valida informações referentes a endereço temporário
	 */
	public static function validateTempAddress(PersonForm &$frm, &$errorMessages = null)
	{	
		self::validateIdAddress($frm, $errorMessages);
		self::validateNumber($frm, $errorMessages);
		self::validateComplement($frm, $errorMessages);
		self::validateReference($frm, $errorMessages);
		self::validateLiveSince($frm, $errorMessages);
		
		return $errorMessages;	
	}
}