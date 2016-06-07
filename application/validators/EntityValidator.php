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

abstract class EntityValidator extends BasicValidator
{
	/**
	 * Verifica se o programa a ser desassociado de uma entidade está sendo usado pela classe
	 */
	public static function verifyInClass($arrEntityAndCollProgram, &$errorMessages = null)
	{
		$validatorProgramInClass = parent::validatorProgramInClass();
		$usedClass = $validatorProgramInClass->isValid($arrEntityAndCollProgram);
		if($usedClass != null)
		{	
			$errorMessages[EntityForm::programId()."_usedClass_".$usedClass][] = $validatorProgramInClass->getMessages();
		}
		
		return $errorMessages;
	}
	
	/**
	 * Verifica se o programa a ser desassociado de uma entidade está sendo usado pelo detallamento de turma
	 */
	public static function verifyInActDetail($arrEntityAndCollProgram, &$errorMessages = null)
	{
		$validatorProgramInActivityDetail = parent::validatorProgramInActivityDetail();
		$usedActDetail = $validatorProgramInActivityDetail->isValid($arrEntityAndCollProgram);
		
		if($usedActDetail != null)
		{	
			$errorMessages[EntityForm::programId()."_usedActDetail_".$usedActDetail][] = $validatorProgramInActivityDetail->getMessages();
		}
		
		return $errorMessages;
	}
	
	/**
	 * Verifica se o programa a ser desassociado de uma entidade está sendo usado pelo atendimento
	 */
	public static function verifyInAssistance($arrEntityAndCollProgram, &$errorMessages = null)
	{
		$validatorProgramInAssistance = parent::validatorProgramInAssistance();
		$usedAssistance = $validatorProgramInAssistance->isValid($arrEntityAndCollProgram);
		if($usedAssistance != null)
		{	
			$errorMessages[EntityForm::programId()."_usedAssistance_".$usedAssistance][] = $validatorProgramInAssistance->getMessages();
		}
		
		return $errorMessages;
	}

	/**
	 * Verifica se o programa a ser desassociado de uma entidade está sendo usado pelos seus respectivos relacionamentos
	 */
	public static function verifyIfProgramIsUsed(EntityForm &$frm, &$errorMessages = null, $collProgramByEntity)
	{	
		//retorna uma coleção de idProgram que será excluído
		$resultVerify = Utils::analyzeDiffArrays($frm->getId(), $frm->getProgramId(), $collProgramByEntity);
		
		if($resultVerify != null && sizeof($resultVerify) > 0 && $frm->getId() != null)
		{	
			$arrEntityAndCollProgram = array();
			$arrEntityAndCollProgram[ID_ENTITY] 		= $frm->getId();
			$arrEntityAndCollProgram[COLL_ID_PROGRAM] 	= $resultVerify;
			
			self::verifyInClass($arrEntityAndCollProgram, $errorMessages);
			self::verifyInActDetail($arrEntityAndCollProgram, $errorMessages);
			self::verifyInAssistance($arrEntityAndCollProgram, $errorMessages);		
		}
		
		return $errorMessages;
	}
	
	public static function validateEntityData(EntityForm &$frm, &$errorMessages = null)
	{
		self::validateEntityId($frm, $errorMessages);
		self::validateEntityName($frm, $errorMessages);

		if($frm->getAdrComplement())
		{
			$validatorComplement = parent::validatorStringLength(3, 72);
			if(strlen($frm->getAdrComplement()) > 72)
			{
				$complement = Utils::abbreviate($frm->getAdrComplement(), 32);
				$errorMessages[BasicForm::adr_complement()][][] = parent::getValidatorResources()->text->long1.$complement.parent::getValidatorResources()->text->long2.'72'.parent::getValidatorResources()->text->long3;
			}
			else if (!$validatorComplement->isValid($frm->getAdrComplement()))
			{
				$errorMessages[BasicForm::adr_complement()][] = $validatorComplement->getMessages();
			}
		}
		
		/*Number*/
		if($frm->getAdrNumber())
		{
			$validatorNumber = parent::validatorInt();
			if($frm->getAdrNumber() < 0)
			{
				$errorMessages[BasicForm::adr_number()][][] = parent::getValidatorResources()->value->negative;
			}
			else if (strlen($frm->getAdrNumber()) > 10)
			{
				$number = Utils::abbreviate($frm->getAdrNumber(), 31);
				if (!$validatorNumber->isValid($number))
				{
					$errorMessages[BasicForm::adr_number()][] = $validatorNumber->getMessages();
				}
			}
			else if (!$validatorNumber->isValid($frm->getAdrNumber()))
			{
				$errorMessages[BasicForm::adr_number()][] = $validatorNumber->getMessages();
			}
			else if($frm->getAdrNumber() == 0)
			{
				$errorMessages[BasicForm::adr_number()][][] = parent::getValidatorResources()->value->zero;
			}
		}
		
		// Email
		$validatorEmail = parent::validatorStringLength(3, 90);
		if (strlen($frm->getEmail()) > 0 && !$validatorEmail->isValid($frm->getEmail()))
			$errorMessages[EntityForm::email()][] = $validatorEmail->getMessages();
		if(strlen($frm->getEmail()) > 0)
		{
			$validatorEmail = parent::validatorEmailAddress();
			if (strlen($frm->getEmail()) > 0 && !$validatorEmail->isValid($frm->getEmail()))
				$errorMessages[EntityForm::email()][] = $validatorEmail->getMessages();
		}

		// homepage		
		if($frm->getHomePage())
		{
			$validatorhomepage = parent::validatorStringLength(12, 200);
			if(strlen($frm->getHomePage()) > 200)
			{
				$homepage = Utils::abbreviate($frm->getHomePage(), 32);
				$errorMessages[EntityForm::homePage()][][] = parent::getValidatorResources()->text->long1.$homepage.parent::getValidatorResources()->text->long2.'200'.parent::getValidatorResources()->text->long3;
			}
			else if (strlen($frm->getHomePage()) > 0 && !$validatorhomepage->isValid($frm->getHomePage()))
			{
				$errorMessages[EntityForm::homePage()][] = $validatorhomepage->getMessages();
			}
			else
			{
				$http = substr($frm->getHomePage(), 0, 7);
				if($http != "http://")
				{
					$errorMessages[EntityForm::homePage()][][] = parent::getValidatorResources()->homepage->http;
				}
			}
		}

		$alterPhone = 0;
		$alterPhone = count($frm->getPhoneId());
		$alterPhone = count($frm->getPhoneNumber());
		$alterPhone = count($frm->getPhoneType());
		$alterPhone = count($frm->getPhoneCodeArea());
		
		if($alterPhone > 0)
		{
			// PhoneId
			if(is_array($frm->getPhoneId()))
			{
				$index = 0;
				foreach($frm->getPhoneId() as $id)
				{
					if($id)
					{
						$validatorPhoneNumber = parent::validatorInt();
						if (strlen($id) > 0 && !$validatorPhoneNumber->isValid($id))
							$errorMessages[EntityForm::phoneId().$index][] = $validatorPhoneNumber->getMessages();
					}
					$index++;
				}
			}
			else
			{
				if($frm->getPhoneId())
				{
					$validatorPhoneNumber = parent::validatorInt();
					if (strlen($frm->getPhoneId()) > 0 && !$validatorPhoneNumber->isValid($frm->getPhoneId()))
						$errorMessages[EntityForm::phoneId()][] = $validatorPhoneNumber->getMessages();
				}
			}

			// PhoneNumber
			if(is_array($frm->getPhoneNumber()))
			{
				$index = 0;
				foreach($frm->getPhoneNumber() as $number)
				{
					if($number)
					{
						$validatorPhoneNumber = parent::validatorStringLength(8, 8);
						if (strlen($number) > 0 && !$validatorPhoneNumber->isValid($number))
						{
							$errorMessages[EntityForm::phoneNumber().$index][] = $validatorPhoneNumber->getMessages();
						}
						else
						{
							$validatorCodeArea = parent::validatorInt();
							if (strlen($number) > 0 && !$validatorCodeArea->isValid($number))
							{
								$errorMessages[EntityForm::phoneNumber().$index][] = $validatorCodeArea->getMessages();
							}
							else if($number < 0)
							{
								$errorMessages[EntityForm::phoneNumber().$index][][] = parent::getValidatorResources()->value->negative;
							}
							else if($number == 0)
							{
								$errorMessages[EntityForm::phoneNumber().$index][][] = parent::getValidatorResources()->value->zero;
							}
						}
					}
					$index++;
				}
			}
			else
			{
				if($frm->getPhoneNumber())
				{
					$validatorPhoneNumber = parent::validatorStringLength(8, 8);
					if (strlen($frm->getPhoneNumber()) > 0 && !$validatorPhoneNumber->isValid($frm->getPhoneNumber()))
					{
						$errorMessages[EntityForm::phoneNumber()][] = $validatorPhoneNumber->getMessages();
					}
					else
					{
						$validatorCodeArea = parent::validatorInt();
						if (strlen($frm->getPhoneNumber()) > 0 && !$validatorCodeArea->isValid($frm->getPhoneNumber()))
						{
							$errorMessages[EntityForm::phoneNumber()][] = $validatorCodeArea->getMessages();
						}
						else if($frm->getPhoneNumber() < 0)
						{
							$errorMessages[EntityForm::phoneNumber()][][] = parent::getValidatorResources()->value->negative;
						}
						else if($frm->getPhoneNumber() == 0)
						{
							$errorMessages[EntityForm::phoneNumber()][][] = parent::getValidatorResources()->value->zero;
						}
					}
				}
			}
			// PhoneType
			if(is_array($frm->getPhoneType()))
			{
				$index = 0;
				foreach($frm->getPhoneType() as $type)
				{
					if($type)
					{
						$validatorPhoneType = parent::validatorInt();
						if (strlen($type) > 0 && !$validatorPhoneType->isValid($type))
						{
							$errorMessages[EntityForm::phoneType().$index][] = $validatorPhoneType->getMessages();
						}
						else
						{
							Zend_Loader::loadClass('TelephoneBusiness');
							$row = TelephoneBusiness::loadType($type);
							if(count($row) == 0)
							{
								$errorMessages[EntityForm::phoneType().$index][][] = parent::getValidatorResources()->phonetype->notfound;
							}
						}
					}
					$index++;
				}
			}
			else
			{
				if($frm->getPhoneType())
				{
					$validatorPhoneType = parent::validatorInt();
					if (strlen($frm->getPhoneType())>0 && !$validatorPhoneType->isValid($frm->getPhoneType()))
					{
						$errorMessages[EntityForm::phoneType()][] = $validatorPhoneType->getMessages();
					}
					else
					{
						Zend_Loader::loadClass('TelephoneBusiness');
						$row = TelephoneBusiness::loadType($frm->getPhoneType());
						if(count($row) == 0)
						{
							$errorMessages[EntityForm::phoneType()][][] = parent::getValidatorResources()->phonetype->notfound;
						}
					}
				}
			}
			// PhoneCodeArea			
			if(is_array($frm->getPhoneCodeArea()))
			{
				$index = 0;
				foreach($frm->getPhoneCodeArea() as $code)
				{					
					if($code)
					{
						$validatorCodeArea = parent::validatorStringLength(2,2);
						if (strlen($code) > 0 && !$validatorCodeArea->isValid($code))
						{
							$errorMessages[EntityForm::phoneCodeArea().$index][] = $validatorCodeArea->getMessages();
						}
						else
						{
							$validatorCodeArea = parent::validatorInt();
							if (strlen($code) > 0 && !$validatorCodeArea->isValid($code))
							{
								$errorMessages[EntityForm::phoneCodeArea().$index][] = $validatorCodeArea->getMessages();
							}
							else if($code < 0)
							{
								$errorMessages[EntityForm::phoneCodeArea().$index][][] = parent::getValidatorResources()->value->negative;
							}
							else if($code == 0)
							{
								$errorMessages[EntityForm::phoneCodeArea().$index][][] = parent::getValidatorResources()->value->zero;
							}
						}
					}
					$index++;
				}
			}
			else
			{
				if($frm->getPhoneCodeArea())
				{
					$validatorCodeArea = parent::validatorStringLength(2,2);
					if (strlen($frm->getPhoneCodeArea()) > 0 && !$validatorCodeArea->isValid($frm->getPhoneCodeArea()))
					{
						$errorMessages[EntityForm::phoneCodeArea()][] = $validatorCodeArea->getMessages();
					}
					else
					{
						$validatorCodeArea = parent::validatorInt();
						if (strlen($frm->getPhoneCodeArea()) > 0 && !$validatorCodeArea->isValid($frm->getPhoneCodeArea()))
						{
							$errorMessages[EntityForm::phoneCodeArea()][] = $validatorCodeArea->getMessages();
						}
						else if($frm->getPhoneCodeArea() < 0)
						{
							$errorMessages[EntityForm::phoneCodeArea()][][] = parent::getValidatorResources()->value->negative;
						}
						else if($frm->getPhoneCodeArea() == 0)
						{
							$errorMessages[EntityForm::phoneCodeArea()][][] = parent::getValidatorResources()->value->zero;
						}
					}
				}
			}
			
			if(is_array($frm->getPhoneNumber()) && is_array($frm->getPhoneType()) && is_array($frm->getPhoneCodeArea()))
			{
				if((count($frm->getPhoneNumber()) == count($frm->getPhoneType())) && (count($frm->getPhoneNumber()) == count($frm->getPhoneCodeArea())))
				{
					$arrayNumber = $frm->getPhoneNumber();
					$arrayCode = $frm->getPhoneCodeArea();
					$arrayType = $frm->getPhoneType();
					for($i = 0; $i < count($arrayNumber); $i++)
					{
						$flag = false;
						if(empty($arrayNumber[$i]) && empty($arrayCode[$i]) && empty($arrayType[$i]))
						{
							$flag = false;
						}
						else if(!empty($arrayNumber[$i]) && empty($arrayCode[$i]) && empty($arrayType[$i]))
						{							
							$flag = true;
						}
						else if(empty($arrayNumber[$i]) && !empty($arrayCode[$i]) && empty($arrayType[$i]))
						{
							$flag = true;
						}
						else if(empty($arrayNumber[$i]) && empty($arrayCode[$i]) && !empty($arrayType[$i]))
						{
							$flag = true;
						}
						else if(!empty($arrayNumber[$i]) && !empty($arrayCode[$i]) && empty($arrayType[$i]))
						{
							$flag = true;
						}
						else if(empty($arrayNumber[$i]) && !empty($arrayCode[$i]) && !empty($arrayType[$i]))
						{
							$flag = true;
						}
						else if(!empty($arrayNumber[$i]) && empty($arrayCode[$i]) && !empty($arrayType[$i]))
						{
							$flag = true;
						}
						else if(!empty($arrayNumber[$i]) && !empty($arrayCode[$i]) && !empty($arrayType[$i]))
						{
							$flag = false;
						}
						
						if($flag === true)
						{							
							$errorMessages[EntityForm::phoneNumber().$i][][] = parent::getValidatorResources()->phone->required;
						}
					}
				}
			}
		}
	}
	
	public static function validateEntityId(EntityForm &$frm, &$errorMessages = null)
	{	
		/* name */
		if(is_array($frm->getId()))
		{
			foreach($frm->getId() as $id)
			{
				$notEmpty = parent::validatorNotEmpty();
				if (!$notEmpty->isValid($id))
					$errorMessages[EntityForm::id().$id][] = $notEmpty->getMessages();
				else
				{
					$validatorInt = parent::validatorInt();
					if (!$validatorInt->isValid($id))
						$errorMessages[EntityForm::id().$id][] = $validatorInt->getMessages();
				}
			}
		}
		else
		{
			$notEmpty = parent::validatorNotEmpty();
			if (!$notEmpty->isValid($frm->getId()))
				$errorMessages[EntityForm::id()][] = $notEmpty->getMessages();
			else
			{
				$validatorInt = parent::validatorInt();
				if (!$validatorInt->isValid($frm->getId()))
					$errorMessages[EntityForm::id()][] = $validatorInt->getMessages();
			}	
		}
	}
	
	/**
	 * Valida os grupos associados a entidade
	 */
	public static function validateGroupEntity(EntityForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		$validatorInt = parent::validatorInt();
		if(UserLogged::isAdministrator())
		{
			if(is_array($frm->getGroupEntity()))
			{
				if(count($frm->getGroupEntity()) > 0 )
				{
					foreach($frm->getGroupEntity() as $id)
					{
						$notEmpty = parent::validatorNotEmpty();
						if (!$notEmpty->isValid($id))
						{
							$errorMessages[EntityForm::groupEntity()][] = $notEmpty->getMessages();
						}
						else
						{
							if (!$validatorInt->isValid($id))
							{
								$errorMessages[EntityForm::groupEntity()][] = $validatorInt->getMessages();
							}
							else
							{
								Zend_Loader::loadClass('GroupBusiness');
								$row = GroupBusiness::loadNotDisable($id);
								
								if(count($row) == 0)
								{
									$errorMessages[EntityForm::groupEntity()][][] = parent::getValidatorResources()->group->notfound;
								}
							}
						}
					}
					return $errorMessages;
				}
			}
			
			if (!$notEmpty->isValid($frm->getGroupEntity()))
			{
				$errorMessages[EntityForm::groupEntity()][] = $notEmpty->getMessages();
			}
			else
			{
				if (!$validatorInt->isValid($frm->getGroupEntity()))
				{
					$errorMessages[EntityForm::groupEntity()][] = $validatorInt->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('GroupBusiness');
					$row = GroupBusiness::loadNotDisable($frm->getGroupEntity());					
					if(count($row) == 0)
					{
						$errorMessages[EntityForm::groupEntity()][][] = parent::getValidatorResources()->group->notfound;
					}
				}
			}
			return $errorMessages;
		}
	}
	
	public static function validateEntityName(EntityForm &$frm, &$errorMessages = null)
	{	
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getEntityName()))
		{
			$errorMessages[EntityForm::entityName()][] = $notEmpty->getMessages();
		}
		else
		{
			$stringLenght = parent::validatorStringLength(3, 200);
			if(strlen($frm->getEntityName()) > 200)
			{
				$entityName = Utils::abbreviate($frm->getEntityName(), 201);
				if (!$stringLenght->isValid($entityName))
				{
					$errorMessages[EntityForm::entityName()][] = $stringLenght->getMessages();
				}
			}
			else if (!$stringLenght->isValid($frm->getEntityName()))
			{
				$errorMessages[EntityForm::entityName()][] = $stringLenght->getMessages();
			}
		}
	}
	
	/*
	 * Valida os "id" do programa
	 */
	public static function validateProgramId(EntityForm &$frm, &$errorMessages = null)
	{	
		$validatorInt = parent::validatorInt();
		
		if(is_array($frm->getProgramId()))
		{
			foreach($frm->getProgramId() as $id)
			{	
				if(strlen($id) > 0 && !$validatorInt->isValid($id))
				{
					$errorMessages[EntityForm::programId()][] = $validatorInt->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('ProgramBusiness');
				
					$row = ProgramBusiness::load($id);
					if(count($row) == 0)
					{
						$errorMessages[EntityForm::programId()][][] = parent::getValidatorResources()->program->notfound;
					}
				}				
			}
		}
		else
		{
			if(strlen($frm->getProgramId()) > 0 && !$validatorInt->isValid($frm->getProgramId()))
			{	
				$errorMessages[EntityForm::programId()][] = $validatorInt->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('ProgramBusiness');
				
				$row = ProgramBusiness::load($frm->getProgramId());
				if(count($row) == 0)
				{
					$errorMessages[EntityForm::programId()][][] = parent::getValidatorResources()->program->notfound;
				}
			}
		}
	}
	
	/*
	 * Valida os "id" do tipo de classificação
	 */
	public static function validateClassificationId(&$frm, &$errorMessages = null)
	{	
		if($frm instanceOf EntityForm)
		{
			$classification = $frm->getClassificationId();
		}
		else if($frm instanceOf ClassificationForm)
		{
			$classification = $frm->getId();
		}
		
		$validatorInt = parent::validatorInt();
		if(is_array($classification))
		{
			foreach($classification as $id)
			{	
				if(strlen($id) > 0 && !$validatorInt->isValid($id))
				{
					$errorMessages[EntityForm::classificationId()][] = $validatorInt->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('ClassificationBusiness');
					$row = ClassificationBusiness::loadOneDisableClassification($id);					
					if(count($row) == 0)
					{
						$errorMessages[EntityForm::classificationId()][][] = parent::getValidatorResources()->classification->notfound;
					}
				}
			}
		}
		else
		{
			if(strlen($classification) > 0 && !$validatorInt->isValid($classification))
			{	
				$errorMessages[EntityForm::classificationId()][] = $validatorInt->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('ClassificationBusiness');				
				$row = ClassificationBusiness::loadOneDisableClassification($classification);				
				if(count($row) == 0)
				{
					$errorMessages[EntityForm::classificationId()][][] = parent::getValidatorResources()->classification->notfound;
				}
			}
		}
	}
	
	/*
	 * Valida os "id" da área de atuação
	 */
	public static function validateAreaId(&$frm, &$errorMessages = null)
	{	
		if($frm instanceOf EntityForm)
		{
			$area = $frm->getAreaId();
		}
		else if($frm instanceOf AreaForm)
		{
			$area = $frm->getId();
		}
		
		$validatorInt = parent::validatorInt();
		
		if(is_array($area))
		{
			foreach($area as $id)
			{	
				if(strlen($id) > 0 && !$validatorInt->isValid($id))
				{
					$errorMessages[EntityForm::areaId()][] = $validatorInt->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('AreaBusiness');
				
					$row = AreaBusiness::load($id);
					if(count($row) == 0)
					{
						$errorMessages[EntityForm::areaId()][][] = parent::getValidatorResources()->area->notfound;
					}
				}
				
			}
		}
		else
		{
			if(strlen($area) > 0 && !$validatorInt->isValid($area))
			{	
				$errorMessages[EntityForm::areaId()][] = $validatorInt->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('AreaBusiness');
			
				$row = AreaBusiness::load($area);
				if(count($row) == 0)
				{
					$errorMessages[EntityForm::areaId()][][] = parent::getValidatorResources()->area->notfound;
				}
			}
		}
	}
	
	/*
	 * Valida CNPJ da entidade
	 */
	public static function validateCnpj(EntityForm &$frm, &$errorMessages = null)
	{	
		$validatorCnpj = parent::validatorCnpj();
		
		if(!$validatorCnpj->isValid($frm->getEntityCnpj()))
		{
			$errorMessages[EntityForm::entityCnpj()][] = $validatorCnpj->getMessages();
		}
	}
}
