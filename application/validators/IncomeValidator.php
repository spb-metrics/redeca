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

abstract class IncomeValidator extends BasicValidator
{
	/**
	 * Valida id da empresa 
	 */
	public static function validateIdCompany(IncomeForm &$frm, &$errorMessages = null)
	{	
		if($frm->getIdCompany())
		{
			$validator = parent::validatorInt();
			if(!$validator->isValid($frm->getIdCompany()))
			{
				$errorMessages[IncomeForm::idCompany()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}	
	
	public static function validateIdCompanyPhone(IncomeForm &$frm, &$errorMessages = null)
	{	
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdCompany()))
		{
			$errorMessages[IncomeForm::idCompany()][][] = parent::getValidatorResources()->company->notfound;
		}
		else if(!$validator->isValid($frm->getIdCompany()))
		{
			$errorMessages[IncomeForm::idCompany()][] = $validator->getMessages();
		}
		
		return $errorMessages;
	}
	
	/**
	 * Valida id da pessoa 
	 */
	public static function validatePersonId(IncomeForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getPersonId()))
		{
			$errorMessages[IncomeForm::personId()][] = $validatorRequired->getMessages();
		}
		else
		{
			if(!$validator->isValid($frm->getPersonId()))
			{
				$errorMessages[IncomeForm::personId()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('PersonBusiness');
			
				$row = PersonBusiness::load($frm->getPersonId());				
				if(count($row) == 0)
				{
					$errorMessages[IncomeForm::personId()][][] = parent::getValidatorResources()->person->notfound;					
				}
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Valida id situação de mercado de trabalho 
	 */
	public static function validateIdEmploymentType(IncomeForm &$frm, &$errorMessages = null)
	{			
		if($frm->getValueSalary())
		{
			foreach($frm->getValueSalary() as $k=>$v)
			{
				if(($k == 1) && (!$v) && ($frm->getIdEmploymentType() != Zend_Registry::get(CONFIG)->status->employment))
				{	
					$errorMessages[IncomeForm::employment()][][] = parent::getValidatorResources()->income->employment->required;
				}
				else if((($k == 1) && ($v)) || ($frm->getIdEmploymentType()))
				{
					$validatorRequired = parent::validatorNotEmpty();
					$validator = parent::validatorInt();
				
					if(!$validatorRequired->isValid($frm->getIdEmploymentType()))
					{
						$errorMessages[IncomeForm::idEmploymentType()][][] = parent::getValidatorResources()->income->employment->required;
					}
					else
					{
						if(!$validator->isValid($frm->getIdEmploymentType()))
						{
							$errorMessages[IncomeForm::idEmploymentType()][] = $validator->getMessages();
						}
						else
						{
							Zend_loader::loadClass('IncomeBusiness');
							
							$row = IncomeBusiness::loadOneEmploymentStatus($frm->getIdEmploymentType());
							if(count($row) == 0)
							{
								$errorMessages[IncomeForm::idEmploymentType()][][] = parent::getValidatorResources()->employment->notfound;
							}
						}
					}
				}
			}
		}
		
		
		
		return $errorMessages;
	}
	
	/**
	 * Valida ocupação 
	 */
	public static function validateOccupation(IncomeForm &$frm, &$errorMessages = null)
	{	
		if($frm->getOccupation())
		{
			$validator = parent::validatorStringLength(3, 32);
			if(strlen($frm->getOccupation()) > 32)
			{
				$occupation = Utils::abbreviate($frm->getOccupation(), 33);
				if(!$validator->isValid($occupation))
				{
					$errorMessages[IncomeForm::occupation()][] = $validator->getMessages();
				}
			}	
			else if(!$validator->isValid($frm->getOccupation()))
			{
				$errorMessages[IncomeForm::occupation()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Valida o tamanho da string inserida no campo nome da empresa
	 */
	public static function validateCompanyName(IncomeForm &$frm, &$errorMessages = null)
	{
		if($frm->getCompanyName())
		{
			$validator = parent::validatorStringLength(3, 50);
			if(strlen($frm->getCompanyName()) > 50)
			{
				$companyName = Utils::abbreviate($frm->getCompanyName(), 33);
				$errorMessages[IncomeForm::companyName()][][] = parent::getValidatorResources()->text->long1.$companyName.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;
			}
			else if(!$validator->isValid($frm->getCompanyName()))
			{
				$errorMessages[IncomeForm::companyName()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Valida o tamanho da string inserida como ponto de referência
	 */
	public static function validateReferencePoint(IncomeForm &$frm, &$errorMessages = null)
	{
		if($frm->getAdrReference())
		{	
			$validator = parent::validatorStringLength(3, 30);
			if(strlen($frm->getAdrReference()) > 30)
			{
				$reference = Utils::abbreviate($frm->getAdrReference(), 33);
				if(!$validator->isValid($reference))
				{
					$errorMessages[IncomeForm::adr_reference()][] = $validator->getMessages();
				}
			}	
			else if(!$validator->isValid($frm->getAdrReference()))
			{
				$errorMessages[IncomeForm::adr_reference()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Valida o tamanho da string inserida como complemento
	 */
	public static function validateComplement(IncomeForm &$frm, &$errorMessages = null)
	{
		if($frm->getAdrComplement())
		{
			$validator = parent::validatorStringLength(3, 30);
			if(strlen($frm->getAdrComplement()) > 30)
			{
				$complement = Utils::abbreviate($frm->getAdrComplement(), 33);
				if(!$validator->isValid($complement))
				{
					$errorMessages[IncomeForm::adr_complement()][] = $validator->getMessages();
				}
			}		
			else if(!$validator->isValid($frm->getAdrComplement()))
			{
				$errorMessages[IncomeForm::adr_complement()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Valida numero
	 */
	public static function validateNumber(IncomeForm &$frm, &$errorMessages = null)
	{
		if($frm->getAdrNumber())
		{			
			$validator = parent::validatorInt();
			
			if(strlen($frm->getAdrNumber()) > 10)
			{
				$number = Utils::abbreviate($frm->getAdrNumber(), 33);				
				if(!$validator->isValid($number))
				{
					$errorMessages[IncomeForm::adr_number()][] = $validator->getMessages();
				}
			}		
			else if(!$validator->isValid($frm->getAdrNumber()))
			{
				$errorMessages[IncomeForm::adr_number()][] = $validator->getMessages();
			}
			else
			{
				if($frm->getAdrNumber() < 0)
				{
					$errorMessages[IncomeForm::adr_number()][][] = parent::getValidatorResources()->value->negative;
				}
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Valida id address
	 */
	public static function validateIdAddress(IncomeForm &$frm, &$errorMessages = null)
	{		
		if($frm->getValueSalary())
		{
			foreach($frm->getValueSalary() as $k=>$v)
			{
				if(($k == 1) && (!$v || $frm->getIdEmploymentType() == Zend_Registry::get(CONFIG)->status->employment) && ($frm->getAdrIdAddress()))
				{					
					if(!$v)
						if(is_null($errorMessages[IncomeForm::employment()]))
							$errorMessages[IncomeForm::employment()][][] = parent::getValidatorResources()->income->employment->required;
					
					if($frm->getIdEmploymentType() == Zend_Registry::get(CONFIG)->status->employment)
						if(is_null($errorMessages[IncomeForm::idEmploymentType()]))
							$errorMessages[IncomeForm::idEmploymentType()][][] = parent::getValidatorResources()->income->employment->required;					
				}
				else if($frm->getAdrIdAddress())
				{					
					$validatorRequired = parent::validatorNotEmpty();
					$validator = parent::validatorInt();
				
					if(!$validatorRequired->isValid($frm->getAdrIdAddress()))
					{
						$errorMessages[IncomeForm::adr_idAddress()][][] = parent::getValidatorResources()->income->employment->required;
					}
					else
					{
						if(!$validator->isValid($frm->getAdrIdAddress()))
						{
							$errorMessages[IncomeForm::adr_idAddress()][] = $validator->getMessages();
						}
					}
				}
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Valida valores do salário e ids de renda 
	 */
	public static function validateSalary(IncomeForm &$frm, &$errorMessages = null)
	{	
		if(count($frm->getValueSalary()) > 0)
		{			
			$validatorRequired = parent::validatorNotEmpty();
			$validator = parent::validatorInt();
			$validatorFloat = parent::validatorFloat();
			
			foreach($frm->getValueSalary() as $k=>$v)
			{			
				if(!$validatorRequired->isValid($k))
				{
					$errorMessages[IncomeForm::idSalary()][] = $validatorRequired->getMessages();
				}
				else
				{
					if(!$validator->isValid($k))
					{
						$errorMessages[IncomeForm::idSalary()][] = $validator->getMessages();
					}
					else
					{
						if(($v) && ($v < 0))
						{
							$errorMessages[IncomeForm::valueSalary().$k][][] = parent::getValidatorResources()->value->negative;
						}
						else
						{
							if(($v) && (!eregi("^([0-9]{1,4})((\.)[0-9]{2})?$", $v)))
							{
								$errorMessages[IncomeForm::valueSalary().$k][][] = parent::getValidatorResources()->value->format;
							}
						}
					}
				}
				
				if(($k != 1) && ($v))
				{						
					if(!$validatorFloat->isValid($v))
					{
						$errorMessages[IncomeForm::valueSalary().$k][] = $validatorFloat->getMessages();
					}
				}
				else if(($k == 1) && (!$v) && ($frm->getIdEmploymentType() != Zend_Registry::get(CONFIG)->status->employment))
				{
					if(is_null($errorMessages[IncomeForm::valueSalary().$k]))
						$errorMessages[IncomeForm::valueSalary().$k][][] = parent::getValidatorResources()->income->employment->required;
				}
				else if(($k == 1) && ($v) && (($frm->getIdEmploymentType() == Zend_Registry::get(CONFIG)->status->employment) || (!$frm->getIdEmploymentType())))
				{					
					if(is_null($errorMessages[IncomeForm::idEmploymentType()]))
						$errorMessages[IncomeForm::idEmploymentType()][][] = parent::getValidatorResources()->income->employment->required;						
				}
				else if(($k == 1) && (!$v) && ($frm->getAdrIdAddress()))
				{
					if(is_null($errorMessages[IncomeForm::valueSalary().$k]))
						$errorMessages[IncomeForm::valueSalary().$k][][] = parent::getValidatorResources()->income->employment->required;
				}
				else if(($k == 1) && (!$v) && ($frm->getOccupation() || $frm->getCompanyName() || $frm->getAdrReference() || 
						$frm->getAdrComplement() || $frm->getAdrNumber() || $frm->getStartDate() || $frm->getEndDate() ||
						Utils::array_is_empty($frm->getPhoneCodeArea()) === false || Utils::array_is_empty($frm->getPhoneNumber()) === false || 
						Utils::array_is_empty($frm->getPhoneType()) === false || Utils::array_is_empty($frm->getIdPhone())) === false)
				{
					if(is_null($errorMessages[IncomeForm::valueSalary().$k]))
						$errorMessages[IncomeForm::valueSalary().$k][][] = parent::getValidatorResources()->income->employment->required;
				}				
			}	
		}
		else
		{
			$errorMessages[IncomeForm::valueSalary()][][] = parent::getValidatorResources()->income->value->required;			
		}
		return $errorMessages;
	}
	
	/**
	 * Valida valores de data 
	 */
	public static function validateDate(IncomeForm &$frm, &$errorMessages = null)
	{	
		if($frm->getStartDate() && !($frm->getStartDate() == "00/00/0000"))
		{
			$validator = parent::validatorDate();
			
			$dateFormat = IncomeForm::dateFormat($frm->getStartDate());			
			
			if (!$validator->isValid($dateFormat))
			{
				$errorMessages[IncomeForm::startDate()][] = $validator->getMessages();
			}				
		}
		
		if($frm->getEndDate() && !($frm->getEndDate() == "00/00/0000"))
		{	
			$validator = parent::validatorDate();
			
			$dateFormat = IncomeForm::dateFormat($frm->getEndDate());			
			
			if (!$validator->isValid($dateFormat))
			{
				$errorMessages[IncomeForm::endDate()][] = $validator->getMessages();
			}
		}
		return $errorMessages;
	}
	
	/**
	 * Valida id do telefone 
	 */
	public static function validatePhone(IncomeForm &$frm, &$errorMessages = null)
	{	
		
		if($frm->getPhoneCodeArea()){
			if($frm->getPhoneNumber()){
				$validator = parent::validatorInt();
				if($frm->getIdPhone()){
					foreach($frm->getIdPhone() as $id){
						if($id){
							if(!$validator->isValid($id))
							{
								$errorMessages[IncomeForm::idPhone()][] = $validator->getMessages();
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
							$errorMessages[IncomeForm::phoneType()][] = $validator->getMessages();
						}
						else
						{
							Zend_Loader::loadClass('TelephoneBusiness');
							$row = TelephoneBusiness::loadType($phoneType);
							
							if(count($row) == 0)
							{
								$errorMessages[IncomeForm::phoneType()][][] = parent::getValidatorResources()->phonetype->notfound;		
							}
						}
						$countType++;
					}					
				}
				
				$i = 0;
				$countDdd=0;
				foreach($frm->getPhoneCodeArea() as $ddd){
					if($ddd){
						if(!$validator->isValid($ddd))
						{
							$errorMessages[IncomeForm::phoneCodeArea()][] = $validator->getMessages();
						}
						else
						{
							if($ddd < 0)
							{
								$errorMessages[IncomeForm::phoneCodeArea()][][] = parent::getValidatorResources()->value->negative;
							}
							else if($ddd == 0)
							{
								$errorMessages[IncomeForm::phoneCodeArea()][][] = parent::getValidatorResources()->value->zero;
							}
							else
							{
								if(strlen($ddd) != 2)
								{
									$dddNumber = Utils::abbreviate($ddd, 5); 
									$errorMessages[IncomeForm::phoneCodeArea()][][] = parent::getValidatorResources()->text->long1.$dddNumber.parent::getValidatorResources()->text->long2.'2'.parent::getValidatorResources()->text->long3;
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
				foreach($frm->getPhoneNumber() as $phone)
				{
					if($phone)
					{
						if(strlen($phone) > 8)
						{
							$phoneNumber = Utils::abbreviate($phone, 9);
							if (!$stringLength->isValid($phoneNumber))
							{
								$errorMessages[IncomeForm::phoneNumber()][] = $stringLength->getMessages();
							}
						}
						else if (!$stringLength->isValid($phone))
						{
							$errorMessages[IncomeForm::phoneNumber()][] = $stringLength->getMessages();
						}
						else
						{
							if(!$validator->isValid($phone))
							{
								$errorMessages[IncomeForm::phoneNumber()][] = $validator->getMessages();
							}
							else
							{
								if($phone < 0)
								{
									$errorMessages[IncomeForm::phoneNumber()][][] = parent::getValidatorResources()->value->negative;
								}
								else
								{
									if($phone == 0)
									{
										$errorMessages[IncomeForm::phoneNumber()][][] = parent::getValidatorResources()->value->zero;
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
					$errorMessages[IncomeForm::phoneCodeArea()][][] = parent::getValidatorResources()->ddd->donthave;
				}
				else if($countType > $countNumber)
				{
					$errorMessages[IncomeForm::phoneNumber()][][] = parent::getValidatorResources()->phone->donthave;
				}
				else if($countType < $countDdd)
				{
					$errorMessages[IncomeForm::phoneType()][][] = parent::getValidatorResources()->phonetype->donthave;
				}
				else if($countType < $countNumber)
				{
					$errorMessages[IncomeForm::phoneType()][][] = parent::getValidatorResources()->phonetype->donthave;
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
								$errorMessages[IncomeForm::phoneCodeArea()][] = $validator->getMessages();
							}
							if (!$stringLength->isValid($flagPhone[$j]))
							{
								$errorMessages[IncomeForm::phoneNumber()][] = $stringLength->getMessages();
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
						$errorMessages[IncomeForm::phoneNumber()][] = $notEmpty->getMessages();
					}
				}
			}
		}
		else if($frm->getPhoneNumber()){
			$notEmpty = parent::validatorNotEmpty();
			foreach($frm->getDdd() as $ddd){
				if(!$notEmpty->isValid($ddd))
				{
					$errorMessages[IncomeForm::phoneCodeArea()][] = $notEmpty->getMessages();
				}
			}
		}
		return $errorMessages;
	}	
	
	public static function validateIncomeData(IncomeForm &$frm)
	{
		$errorMessages = null;
		self::validatePersonId($frm, $errorMessages);
		
		return $errorMessages;
	}
	
	public static function validateIncomeEdit(IncomeForm &$frm)
	{
		$errorMessages = null;
		self::validatePersonId($frm, $errorMessages);
		self::validateIdCompany($frm, $errorMessages);
		self::validateIdEmploymentType($frm, $errorMessages);		
		self::validateOccupation($frm, $errorMessages);
		self::validateDate($frm, $errorMessages);
		self::validateCompanyName($frm, $errorMessages);
		self::validateReferencePoint($frm, $errorMessages);
		self::validateComplement($frm, $errorMessages);
		self::validateNumber($frm, $errorMessages);
		self::validateIdAddress($frm, $errorMessages);		
		self::validateSalary($frm, $errorMessages);
		self::validatePhone($frm, $errorMessages);

		return $errorMessages;
	}
	
	public static function validateIncomeTelephone(IncomeForm &$frm)
	{
		$errorMessages = null;
		$validator = parent::validatorInt();
		$validatorRequired = parent::validatorNotEmpty();
		
		// person
		if(!$validatorRequired->isValid($frm->getPersonId()))
		{
			$errorMessages[IncomeForm::personId()][] = $validatorRequired->getMessages();
		}
		else
		{
			if(!$validator->isValid($frm->getPersonId()))
			{
				$errorMessages[IncomeForm::personId()][] = $validator->getMessages();
			}
		}		
		
		// company
		if(!$validator->isValid($frm->getIdCompany()))
		{
			$errorMessages[IncomeForm::idCompany()][] = $validator->getMessages();
		}	
		
		$dddSize = 0;
		$numberSize = 0;
		$idSize = 0;
		$typeSize = 0;
		
		// id phone
		if(!Utils::array_is_empty($frm->getIdPhone()))
		{			
			
			foreach($frm->getIdPhone() as $id)
			{
				
				if($id)
				{					
					if(!$validator->isValid($id))
					{
						if(is_null($errorMessages[IncomeForm::idPhone()]))
							$errorMessages[IncomeForm::idPhone()][] = $validator->getMessages();
					}
					$idSize++;
				}
			}
		}
		
		// number phone
		if(!Utils::array_is_empty($frm->getPhoneNumber()))
		{
			foreach($frm->getPhoneNumber() as $id)
			{				
				if($id)
				{
					if(!$validator->isValid($id))
					{
						if(is_null($errorMessages[IncomeForm::phoneNumber()]))
							$errorMessages[IncomeForm::phoneNumber()][] = $validator->getMessages();
					}
					$numberSize++;
				}
			}
		}
		
		// ddd phone
		if(!Utils::array_is_empty($frm->getPhoneCodeArea()))
		{			
			foreach($frm->getPhoneCodeArea() as $id)
			{				
				if($id)
				{
					if(!$validator->isValid($id))
					{
						if(is_null($errorMessages[IncomeForm::phoneCodeArea()]))
							$errorMessages[IncomeForm::phoneCodeArea()][] = $validator->getMessages();
					}
					$dddSize++;
				}
			}
		}
		
		// type phone
		if(!Utils::array_is_empty($frm->getPhoneType()))
		{
			foreach($frm->getPhoneType() as $id)
			{	
				if($id)
				{					
					if(!$validator->isValid($id))
					{
						if(is_null($errorMessages[IncomeForm::phoneType()]))
							$errorMessages[IncomeForm::phoneType()][] = $validator->getMessages();
					}
					$typeSize++;
				}
			}
		}	
		
		if(($typeSize < $dddSize) || ($typeSize < $numberSize) || ($typeSize < $idSize))
		{
			$errorMessages[IncomeForm::phoneType()][][] = parent::getValidatorResources()->telephone->required;
		}
		if(($dddSize < $typeSize) || ($dddSize < $numberSize) || ($dddSize < $idSize))
		{
			$errorMessages[IncomeForm::phoneCodeArea()][][] = parent::getValidatorResources()->telephone->required;
		}
		if(($numberSize < $dddSize) || ($numberSize < $typeSize) || ($numberSize < $idSize))
		{
			$errorMessages[IncomeForm::phoneNumber()][][] = parent::getValidatorResources()->telephone->required;
		}
		if(($idSize < $dddSize) || ($idSize < $numberSize) || ($idSize < $typeSize))
		{
			$errorMessages[IncomeForm::idPhone()][][] = parent::getValidatorResources()->telephone->required;
		}
		
		return $errorMessages;
	}
	
}