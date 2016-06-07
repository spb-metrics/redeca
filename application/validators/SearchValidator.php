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

abstract class SearchValidator extends BasicValidator
{
	/**
	 * Utilizado apenas na busca de relação familiar / relação biológica
	 */
	public static function validateNamePerson(SearchForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		
		if(!$validatorRequired->isValid($frm->getPrsName()))
		{
			$errorMessages[SearchForm::prsName()][] = $validatorRequired->getMessages();
		}
		
		return $errorMessages;
	}
		
	/**
	 * Utilizado apenas na busca de relação familiar / relação biológica 
	 */
	public static function validatePersonId(SearchForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdPerson()))
		{
			$errorMessages[SearchForm::idPerson()][] = $validatorRequired->getMessages();
		}
		else if(!$validator->isValid($frm->getIdPerson()))
		{
			$errorMessages[SearchForm::idPerson()][] = $validator->getMessages();
		}
		else
		{
			Zend_Loader::loadClass('PersonBusiness');
			
			$row = PersonBusiness::load($frm->getIdPerson());				
			if(count($row) == 0)
			{
				$errorMessages[SearchForm::idPerson()][][] = parent::getValidatorResources()->person->notfound;					
			}
		}
		return $errorMessages;
	}
	
	/**
	 * Utilizado apenas na busca de relação familiar / relação biológica 
	 */
	public static function validateFamilyId(SearchForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdFamily()))
		{
			$errorMessages[SearchForm::idFamily()][] = $validatorRequired->getMessages();
		}
		
		if(!$validator->isValid($frm->getIdFamily()))
		{
			$errorMessages[SearchForm::idFamily()][] = $validator->getMessages();
		}
		
		return $errorMessages;
	}
	
	/**
	 * Utilizado apenas na busca de relação familiar / relação biológica 
	 */
	public static function validateParentId(SearchForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdParent()))
		{
			$errorMessages[SearchForm::idParent()][] = $validatorRequired->getMessages();
		}
		
		if(!$validator->isValid($frm->getIdParent()))
		{
			$errorMessages[SearchForm::idParent()][] = $validator->getMessages();
		}
		
		return $errorMessages;
	}
	
	public static function validateBiologicalAge(SearchForm &$frm, &$errorMessages = null)
	{		
		$config = Zend_Registry::get(CONFIG);
		
		if($frm->getIdKinship() && ($frm->getIdKinship() == $config->biological->father || $frm->getIdKinship() == $config->biological->grandfather))
		{
			if($frm->getIdParent() && $frm->getIdPerson())
			{
				Zend_Loader::loadClass('PersonBusiness');
				$birthPerson = PersonBusiness::load($frm->getIdPerson())->current()->{PRS_BIRTH_DATE};
				$birthParent = PersonBusiness::load($frm->getIdParent())->current()->{PRS_BIRTH_DATE};
				if($birthPerson > $birthParent)
				{
					$errorMessages[SearchForm::idKinship()][][] = parent::getValidatorResources()->biological->age;
				}	
			}
		}
		return $errorMessages;
	}
	
	public static function validateBiologicalUnique(SearchForm &$frm, &$errorMessages = null)
	{		
		if($frm->getIdPerson() == $frm->getIdParent())
		{
			$errorMessages[SearchForm::idKinship()][][] = parent::getValidatorResources()->biological->equal;
		}
		else
		{
			$config = Zend_Registry::get(CONFIG);
			if($frm->getIdKinship() && ($frm->getIdKinship() == $config->biological->father))
			{			
				Zend_Loader::loadClass('ConsanguineBusiness');
				if($frm->getIdParent())
				{
					$row = ConsanguineBusiness::loadBiologicalByIdPerson($frm->getIdParent());
					if(count($row) > 0)
					{
						$i = 0;$flag = false;
						foreach($row as $csg)
						{
							if($csg->{CSG_ID_CONSANGUINE_TYPE} == $config->biological->son)
							{
								$i++;											
							}
						}
						
						if($i >= 2)
						{
							$errorMessages[SearchForm::idKinship()][][] = parent::getValidatorResources()->biological->son;
						}					
					}				
					
					if($frm->getIdPerson())
					{
						$row = ConsanguineBusiness::loadBiologicalByIdPerson($frm->getIdPerson());
						if(count($row) > 0)
						{
							$flag = false;
							foreach($row as $csg)
							{
								if($csg->{CSG_ID_CONSANGUINE_TYPE} == $config->biological->son)
								{
									if($frm->getIdParent() == $csg->{CSG_ID_PERSON_TO})
									{
										$flag = true;
										break;
									}											
								}
							}
							
							if($flag === true)
							{
								$errorMessages[SearchForm::idKinship()][][] = parent::getValidatorResources()->biological->father;
							}					
						}
					}
				}
			}
			else if($frm->getIdKinship() && ($frm->getIdKinship() == $config->biological->son))
			{
				Zend_Loader::loadClass('ConsanguineBusiness');
				if($frm->getIdParent())
				{
					$row = ConsanguineBusiness::loadBiologicalByIdPerson($frm->getIdPerson());					
					if(count($row) > 0)
					{
						$i = 0;$flag = false;
						foreach($row as $csg)
						{	
							if($csg->{CSG_ID_CONSANGUINE_TYPE} == $config->biological->son)
							{
								$i++;											
							}
						}
						
						if($i >= 2)
						{
							$errorMessages[SearchForm::idKinship()][][] = parent::getValidatorResources()->biological->son;
						}					
					}				
					
					if($frm->getIdPerson())
					{
						$row = ConsanguineBusiness::loadBiologicalByIdPerson($frm->getIdPerson());
						if(count($row) > 0)
						{
							$flag = false;
							foreach($row as $csg)
							{
								if($csg->{CSG_ID_CONSANGUINE_TYPE} == $config->biological->son)
								{
									if($frm->getIdParent() == $csg->{CSG_ID_PERSON_TO})
									{
										$flag = true;
										break;
									}											
								}
							}
							
							if($flag === true)
							{
								$errorMessages[SearchForm::idKinship()][][] = parent::getValidatorResources()->biological->father;
							}					
						}
					}
				}
			}
		}
		return $errorMessages;
	}
	
	public static function validateSexKinshipId(SearchForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('PersonBusiness');
		$row = PersonBusiness::load($frm->getIdParent());
		if(count($row) > 0)
		{
			$config = Zend_Registry::get(CONFIG);
			if($frm->getIdKinship() == $config->kinship->mother)
			{
				if($row->current()->{PRS_SEX} != Constants::WOMAN)
				{
					$errorMessages[SearchForm::idKinship()][][] = parent::getValidatorResources()->kinship->female;
				}
			}
			else if($frm->getIdKinship() == $config->kinship->father)
			{
				if($row->current()->{PRS_SEX} != Constants::MAN)
				{
					$errorMessages[SearchForm::idKinship()][][] = parent::getValidatorResources()->kinship->male;
				}
			}
		}
		return $errorMessages;
	}
	
	public static function validateFamilyRelationship(SearchForm &$frm, &$errorMessages = null)
	{
		if($frm->getIdPerson() != null)
		{
			$row = FamilyBusiness::loadFamilyRelationship($frm->getIdPerson());
			
			if(count($row) > 0)
			{	
				if($frm->getIdFamily() != null)
				{	
					if($frm->getIdFamily() != null && $frm->getIdFamily() != $row->current()->{FID_ID_FAMILY})
					{
						$errorMessages[SearchForm::idFamily()][][] = parent::getValidatorResources()->person->associated;
					}
					
					$rowFamily = FamilyBusiness::loadFamilyByIdFamily($frm->getIdFamily());
					if(count($rowFamily) > 0)
					{
						if($frm->getIdParent() != null && $frm->getIdParent() != $rowFamily->current()->findDependentRowset(CLS_REPRESENTATIVE)->current()->{REP_ID_PERSON})
						{
							$errorMessages[SearchForm::idFamily()][][] = parent::getValidatorResources()->representative->notValid;
						}
					}		
				}
				else
				{
					$errorMessages[SearchForm::idFamily()][][] = parent::getValidatorResources()->identification->family;	
				}
			}	
		}
		else
		{
			$errorMessages[SearchForm::idFamily()][][] = parent::getValidatorResources()->person->notfound;
		}
		
		return $errorMessages;
	}
	
	/**
	 * Utilizado apenas na busca de relação familiar / relação biológica 
	 */
	public static function validateKinshipId(SearchForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorInt();
		
		if(!$validatorRequired->isValid($frm->getIdKinship()))
		{
			$errorMessages[SearchForm::idKinship()][] = $validatorRequired->getMessages();
		}
		else if(!$validator->isValid($frm->getIdKinship()))
		{
			$errorMessages[SearchForm::idKinship()][] = $validator->getMessages();
		}
		else
		{
			Zend_Loader::loadClass('FamilyBusiness');
			$row = FamilyBusiness::loadKinshipType($frm->getIdKinship());
			if(count($row) == 0)
			{
				$errorMessages[SearchForm::idKinship()][][] = parent::getValidatorResources()->kinship->notfound;
			}			
		}
		
		return $errorMessages;
	}
	
	public static function validateSearchNumber(SearchForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if(!$notEmpty->isValid($frm->getDocNumber()))
		{
			$errorMessages[SearchForm::docNumber()][] = $notEmpty->getMessages();
		}
		else
		{
			$validator = parent::validatorStringLength(4, 20);
			if(strlen($frm->getDocNumber()) > 11)
			{
				$doc = Utils::abbreviate($frm->getDocNumber(), 31);
				if (!$validator->isValid($doc))
				{
					$errorMessages[SearchForm::docNumber()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getDocNumber()))
			{
				$errorMessages[SearchForm::docNumber()][] = $validator->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateSearchType(SearchForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if(!$notEmpty->isValid($frm->getDocType()))
		{
			$errorMessages[SearchForm::docType()][] = $notEmpty->getMessages();
		}
		else
		{
			$validator = Utils::getTypes();			
			$flag = false;
			
			foreach($validator as $valid)
			{
				if ($frm->getDocType() == $valid)
				{
					$flag = true;
					break;
				}
			}
			
			if($flag === false)
			{
				$error = Zend_Registry::get(VALIDATOR_RESOURCES);
				$errorMessages[SearchForm::docType()][][] = $error->search->error->doc;
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateSearchSex(SearchForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorStringLength(1, 1);
		if(strlen($frm->getPrsSex()) > 1)
		{
			$sex = Utils::abbreviate($frm->getPrsSex(), 15);
			if (!$validator->isValid($sex))
			{
				$errorMessages[SearchForm::prsSex()][] = $validator->getMessages();
			}
		}
		else if (!$validator->isValid($frm->getPrsSex()))
		{
			$errorMessages[SearchForm::prsSex()][] = $validator->getMessages();
		}
		else
		{
			if(($frm->getPrsSex() == Constants::MAN) || ($frm->getPrsSex() == Constants::WOMAN))
			{
				;
			}
			else
			{
				$errorMessages[SearchForm::prsSex()][][] = parent::getValidatorResources()->sex->notfound;
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateSearchAge(SearchForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorInt();
		$flag = false;
		if (!$validator->isValid($frm->getPrsFirstAge()))
		{
			$errorMessages[SearchForm::prsFirstAge()][] = $validator->getMessages();
			$flag = true;
		}		
		
		if (!$validator->isValid($frm->getPrsSecondAge()))
		{
			$errorMessages[SearchForm::prsSecondAge()][] = $validator->getMessages();
		}
		
		if(($frm->getPrsSecondAge() <= $frm->getPrsFirstAge()) && ($flag === false))
		{
			$error = Zend_Registry::get(VALIDATOR_RESOURCES);	
			$errorMessages[SearchForm::prsSecondAge()][][] = $error->search->error->secondAge;
		}
		
		return $errorMessages;
	}
	
	public static function validateSearchRegion(SearchForm &$frm, &$errorMessages = null)
	{
		$validator = parent::validatorInt();
			if (!$validator->isValid($frm->getPrsRegion()))
				$errorMessages[SearchForm::prsRegion()][] = $validator->getMessages();
		
		return $errorMessages;
	}
	
	public static function validateSearchPerson(SearchForm &$frm, &$errorMessages = null)
	{
		$name = false;
		$nickname = false;
		
		if($frm->getPrsName())
		{	
			$validator = parent::validatorStringLength(3, 50);
			if(strlen($frm->getPrsName()) > 50)
			{
				$namePerson = Utils::abbreviate($frm->getPrsName(), 31);
				$errorMessages[SearchForm::prsName()][][] = parent::getValidatorResources()->text->long1.$namePerson.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;
			}
			else if (!$validator->isValid($frm->getPrsName()))
			{
				$errorMessages[SearchForm::prsName()][] = $validator->getMessages();
			}
			else
			{ 
				$name = true;
			}
		}
		
		if($frm->getPrsNickname())
		{
			$validator = parent::validatorStringLength(2, 15);
			if(strlen() > 15)
			{
				$nick = Utils::abbreviate($frm->getPrsNickname(), 20);
				if (!$validator->isValid($nick))
				{
					$errorMessages[SearchForm::prsNickname()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getPrsNickname()))
			{
				$errorMessages[SearchForm::prsNickname()][] = $validator->getMessages();
			}
			else 
			{
				$nickname = true;
			}
		}
		
		if($name !== false || $nickname !== false)
		{
			if($frm->getPrsSex())
			{				
				self::validateSearchSex($frm, $errorMessages);
			}
			
			if($frm->getPrsFirstAge() && $frm->getPrsFirstAge())
			{
				self::validateSearchAge($frm, $errorMessages);
			}
			else if($frm->getPrsFirstAge() && !($frm->getPrsSecondAge()))
			{
				$error = Zend_Registry::get(VALIDATOR_RESOURCES);
				$errorMessages[SearchForm::prsFirstAge()][] = array($error->search->error->twoAges);
			}
			else if($frm->getPrsSecondAge() && !($frm->getPrsFirstAge()))
			{
				$error = Zend_Registry::get(VALIDATOR_RESOURCES);
				$errorMessages[SearchForm::prsFirstAge()][] = array($error->search->error->twoAges);
			}
			
			if($frm->getPrsRegion())
			{
				self::validateSearchRegion($frm, $errorMessages);
			}
		}
		else
		{
			$error = Zend_Registry::get(VALIDATOR_RESOURCES);
			$errorMessages[SearchForm::prsName()][] = array($error->search->error->required);
		}
		
		return $errorMessages;
	}
	
	public static function validateSearchPersonFamily(SearchForm &$frm, &$errorMessages = null)
	{
		$name = false;
		$nickname = false;
		
		self::validatePersonId($frm, $errorMessages);
		
		if($frm->getPrsName())
		{	
			$validator = parent::validatorStringLength(3, 50);
			if(strlen($frm->getPrsName()) > 50)
			{
				$namePerson = Utils::abbreviate($frm->getPrsName(), 31);
				$errorMessages[SearchForm::prsName()][][] = parent::getValidatorResources()->text->long1.$namePerson.parent::getValidatorResources()->text->long2.'50'.parent::getValidatorResources()->text->long3;
			}
			else if (!$validator->isValid($frm->getPrsName()))
			{
				$errorMessages[SearchForm::prsName()][] = $validator->getMessages();
			}
			else $name = true;
		}
		
		if($frm->getPrsNickname())
		{
			$validator = parent::validatorStringLength(2, 15);
			if(strlen() > 15)
			{
				$nick = Utils::abbreviate($frm->getPrsNickname(), 20);
				if (!$validator->isValid($nick))
				{
					$errorMessages[SearchForm::prsNickname()][] = $validator->getMessages();
				}
			}
			else if (!$validator->isValid($frm->getPrsNickname()))
			{
				$errorMessages[SearchForm::prsNickname()][] = $validator->getMessages();
			}
			else 
			{
				$nickname = true;
			}
		}
		
		if($name !== false || $nickname !== false)
		{
			if($frm->getPrsSex())
			{				
				self::validateSearchSex($frm, $errorMessages);
			}
			
			if($frm->getPrsFirstAge() && $frm->getPrsFirstAge())
			{
				self::validateSearchAge($frm, $errorMessages);
			}
			else if($frm->getPrsFirstAge() && !($frm->getPrsSecondAge()))
			{
				$error = Zend_Registry::get(VALIDATOR_RESOURCES);
				$errorMessages[SearchForm::prsFirstAge()][][] = $error->search->error->twoAges;
			}
			else if($frm->getPrsSecondAge() && !($frm->getPrsFirstAge()))
			{
				$error = Zend_Registry::get(VALIDATOR_RESOURCES);
				$errorMessages[SearchForm::prsFirstAge()][][] = $error->search->error->twoAges;
			}			
		}
		else
		{
			$error = Zend_Registry::get(VALIDATOR_RESOURCES);
			$errorMessages[SearchForm::prsName()][] = array($error->searchfamily->error->required);
		}
		
		return $errorMessages;
	}
	
	/**
	 * Utilizado apenas na busca de relação familiar 
	 */
	public static function validateAgeRepresentative(SearchForm &$frm, &$errorMessages = null)
	{	
		$validatorAge = parent::validatorAge();
		
		if(!$validatorAge->isValid($frm->getIdPerson()))
		{
			$errorMessages[SearchForm::idPerson()][] = $validatorAge->getMessages();
		}
		
		return $errorMessages;
	}
	
	/**
	 * Utilizado apenas na busca de relação familiar 
	 */
	public static function validateFamilyHasRepresentative(SearchForm &$frm, &$errorMessages = null)
	{	
		$validatorRepresentative = parent::validateHasRepresentative();
		
		if(!$validatorRepresentative->isValid($frm->getIdPerson()))
		{
			$errorMessages[SearchForm::idPerson()][] = $validatorRepresentative->getMessages();
		}
		
		return $errorMessages;
	}
	
	/**
	 * Utilizado apenas na busca de relação familiar 
	 */
	public static function validatePersonIsRepresentative(SearchForm &$frm, &$errorMessages = null)
	{	
		$validatorPersonIsRepresentative = parent::validatePersonIsRepresentative();
		
		if(!$validatorPersonIsRepresentative->isValid($frm->getIdPerson()))
		{
			$errorMessages[SearchForm::idPerson()][] = $validatorPersonIsRepresentative->getMessages();
		}
		
		return $errorMessages;
	}
	
	public static function validateSearchDoc(SearchForm &$frm)
	{
		$errorMessages = null;
		self::validateSearchNumber($frm, $errorMessages);
		self::validateSearchType($frm, $errorMessages);

		return $errorMessages;
	}
	
	
}