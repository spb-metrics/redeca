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

abstract class ImportValidator extends BasicValidator
{
	public static function validateZipCodeData(ImportForm &$frm, &$errorMessages = null)
	{
		if(!empty($frm))
		{
			$resources = Zend_Registry::get(VALIDATOR_RESOURCES);
			$file = $frm->getAddressFile();
			$validatorRequired = parent::validatorNotEmpty();
			
			if (!$validatorRequired->isValid($file[Constants::F_NAME]) && 
					!$validatorRequired->isValid($file[Constants::F_SIZE]))
			{
				$msg = $validatorRequired->getMessages();
				$msg[0] = $msg[0] . $resources->file->maxsize->allowed;
				$errorMessages[ImportForm::addressFile()][] = $msg;
				Logger::loggerImportAddress('Barrado na validação de tamanho do arquivo');				
				return $errorMessages;
			}		
			
//			$validatorUpload = parent::validatorUploadFile(ImportForm::addressFile(), 0, 81920000);
//			
//			if (!$validatorUpload->isValid(ImportForm::addressFile()))
//			{
//				$errorMessages[ImportForm::addressFile()][] = $validatorUpload->getMessages();
//				Logger::loggerImportAddress('Barrado na validação de tamanho do arquivo');
//			}
			
			$validator = parent::validatorExtensionFile('zip');
			
			if (!$validator->isValid($file[Constants::F_TYPE]) && 
					!$validator->isValid($file[Constants::F_NAME]))
			{
				$errorMessages[ImportForm::addressFile()][] = $validator->getMessages();
				Logger::loggerImportAddress('Barrado na validação de formato do arquivo');
			}
		}
		else
		{
			$validatorRequired = parent::validatorNotEmpty();
			
			if (!$validatorRequired->isValid(''))
			{
				$errorMessages[ImportForm::addressFile()][] = $validatorRequired->getMessages();
				Logger::loggerImportAddress('Barrado na validação de campo vazio');
			}		
		}

		return $errorMessages;
	}
	
	public static function validateSingleRegisterData(ImportForm &$frm, &$errorMessages = null)
	{
		if(!empty($frm))
		{
			$resources = Zend_Registry::get(VALIDATOR_RESOURCES);
			$file = $frm->getAddressFile();
			$validatorRequired = parent::validatorNotEmpty();

			if (!$validatorRequired->isValid($file[Constants::F_NAME]) && 
					!$validatorRequired->isValid($file[Constants::F_SIZE]))
			{
				$msg = $validatorRequired->getMessages();
				$msg[0] = $msg[0] . $resources->file->maxsize->allowed;
				$errorMessages[ImportForm::addressFile()][] = $msg;
				Logger::loggerImport('Barrado na validação de tamanho do arquivo');
				Logger::loggerImport($resources->file->maxsize->allowed);
				return $errorMessages;
			}		

//			$validatorUpload = parent::validatorUploadFile(ImportForm::addressFile(), 0, 5097152);
//			
//			if (!$validatorUpload->isValid(ImportForm::addressFile()))
//			{
//				$errorMessages[ImportForm::addressFile()][] = $validatorUpload->getMessages();
//				Logger::loggerImport('Barrado na validação de tamanho do arquivo');
//			}

			$validator = parent::validatorExtensionFile('zip');
			if (!$validator->isValid($file[Constants::F_NAME]))
			{
				$errorMessages[ImportForm::addressFile()][] = $validator->getMessages();
				Logger::loggerImport('Barrado na validação de formato do arquivo');
			}
		}
		else
		{
			$validatorRequired = parent::validatorNotEmpty();
			
			if (!$validatorRequired->isValid(''))
			{
				$errorMessages[ImportForm::addressFile()][] = $validatorRequired->getMessages();
				Logger::loggerImport('Barrado na validação de campo vazio');
			}		
		}
		return $errorMessages;
	}
	
	public static function validateSchoolData(ImportForm &$frm, &$errorMessages = null)
	{
		if(!empty($frm))
		{
			$resources = Zend_Registry::get(VALIDATOR_RESOURCES);
			$file = $frm->getAddressFile();
			$validatorRequired = parent::validatorNotEmpty();

			if (!$validatorRequired->isValid($file[Constants::F_NAME]) && 
					!$validatorRequired->isValid($file[Constants::F_SIZE]))
			{
				$msg = $validatorRequired->getMessages();
				$msg[0] = $msg[0] . $resources->file->maxsize->allowed;
				$errorMessages[ImportForm::addressFile()][] = $msg;
				Logger::loggerImportSchool('Barrado na validação de tamanho do arquivo');
				Logger::loggerImportSchool($resources->file->maxsize->allowed);
				return $errorMessages;
			}		

//			$validatorUpload = parent::validatorUploadFile(ImportForm::addressFile(), 0, 5097152);
//			
//			if (!$validatorUpload->isValid(ImportForm::addressFile()))
//			{
//				$errorMessages[ImportForm::addressFile()][] = $validatorUpload->getMessages();
//				Logger::loggerImportSchool('Barrado na validação de tamanho do arquivo');
//			}

			$validator = parent::validatorExtensionFile('zip');
			if (!$validator->isValid($file[Constants::F_NAME]))
			{
				$errorMessages[ImportForm::addressFile()][] = $validator->getMessages();
				Logger::loggerImportSchool('Barrado na validação de formato do arquivo');
			}
		}
		else
		{
			$validatorRequired = parent::validatorNotEmpty();
			
			if (!$validatorRequired->isValid(''))
			{
				$errorMessages[ImportForm::addressFile()][] = $validatorRequired->getMessages();
				Logger::loggerImportSchool('Barrado na validação de campo vazio');
			}		
		}
		return $errorMessages;
	}
	
	public static function validateZipCodeProcess(&$errorMessages = null)
	{
		$resources = Zend_Registry::get(VALIDATOR_RESOURCES);
		if(ImportValidator::isZipCodeImported())
		{
			$errorMessages[Constants::PROCESS_KEY][] = array($resources->import->hasZipCodeImported);
			Logger::loggerImportAddress('Barrado na validação de arquivo já foi importado');
		}
		return $errorMessages;
	}

	public static function validateSingleRegisterProcess(&$errorMessages = null)
	{
		$resources = Zend_Registry::get(VALIDATOR_RESOURCES);
		if(ImportValidator::isSingleRegisterImported())
		{
			$errorMessages[Constants::PROCESS_KEY][] = array($resources->import->hasImported);
			Logger::loggerImport('Barrado na validação de arquivo já foi importado');
		}
		return $errorMessages;
	}
	
	public static function validateSchoolProcess(&$errorMessages = null)
	{
		$resources = Zend_Registry::get(VALIDATOR_RESOURCES);
		$resSchool = count(SchoolBusiness::load());	 	
		if($resSchool > 0){
			$errorMessages[ImportForm::addressFile()][][] = $resources->import->hasSchoolImported;
			Logger::loggerImportSchool('Barrado na validação de arquivo já foi importado');			
		}
		
		return $errorMessages;
	}

	public static function validatePerson(ImportForm &$frm)
	{
		$errorMessages = null;

		return $errorMessages;
	}
	
	public static function validatePermission()
	{
		$permission = FileHelper::verifyFolderImportPermission();
		if($permission === FALSE)
		{
			$errorMessages[ImportForm::folder().'1'][][] = parent::getValidatorResources()->import->permission;
		}
		return $errorMessages;
	}
	
	public static function isZipCodeImported()
	{
		Zend_Loader::loadClass('BasicBusiness');
		Zend_Loader::loadClass(CLS_UF);
		Zend_Loader::loadClass(CLS_CITY);
		Zend_Loader::loadClass(CLS_NEIGHBORHOOD);
		Zend_Loader::loadClass(CLS_ADDRESS);
		Zend_Loader::loadClass(CLS_ADDRESSNICKNAME);
		Zend_Loader::loadClass(CLS_ADDRESSTYPE);
		$res = 0;
		$res += BasicBusiness::count(TBL_UF, UF_ID_UF);
		$res += BasicBusiness::count(TBL_CITY, CTY_ID_CITY);
		$res += BasicBusiness::count(TBL_NEIGHBORHOOD, NHD_ID_NEIGHBORHOOD);
		$res += BasicBusiness::count(TBL_ADDRESS, ADR_ID_ADDRESS);
		$res += BasicBusiness::count(TBL_ADDRESS_NICKNAME, ADN_ID_NICKNAME);
		$res += BasicBusiness::count(TBL_ADDRESS_TYPE, ADT_ID_ADDRESS_TYPE);
		if($res > 0) return TRUE;
		else return FALSE;
	}
	/**
	 * Verifica se o cadastro único foi importado
	 */
	public static function isSingleRegisterImported()
	{
		Zend_Loader::loadClass('BasicBusiness');
		Zend_Loader::loadClass(CLS_FAMILY);
		Zend_Loader::loadClass(CLS_FAMILY_ID);
		Zend_Loader::loadClass(CLS_RESIDENCE);
		Zend_Loader::loadClass(CLS_FAMILYRESIDENCE);
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass(CLS_DEFICIENCY);
		Zend_Loader::loadClass(CLS_DOCUMENT);
		Zend_Loader::loadClass(CLS_CTPS);
		Zend_Loader::loadClass(CLS_CIVILCERTIFICATE);
		Zend_Loader::loadClass(CLS_INCOME);
		Zend_Loader::loadClass(CLS_EMPLOYMENT);
		Zend_Loader::loadClass(CLS_EXPENSE);
		Zend_Loader::loadClass(CLS_SOCIALPROGRAM);
		Zend_Loader::loadClass(CLS_REGISTRATION);
		Zend_Loader::loadClass(CLS_LEVELINSTRUCTION);

		$res = 0;
		$res += BasicBusiness::count(TBL_FAMILY, FAM_ID_PERSON);
		$res += BasicBusiness::count(TBL_FAMILY_ID, FID_ID_FAMILY);
		$res += BasicBusiness::count(TBL_RESIDENCE, RES_ID_RESIDENCE);
		$res += BasicBusiness::count(TBL_FAMILY_RESIDENCE, FRS_ID_FAMILY);
		$res += BasicBusiness::count(TBL_PERSON, PRS_ID_PERSON);
		$res += BasicBusiness::count(TBL_DEFICIENCY, DFY_ID_PERSON);
		$res += BasicBusiness::count(TBL_DOCUMENT, DOC_ID_PERSON);
		$res += BasicBusiness::count(TBL_CTPS, CTS_ID_PERSON);
		$res += BasicBusiness::count(TBL_CIVIL_CERTIFICATE, CCF_ID_PERSON);
		$res += BasicBusiness::count(TBL_INCOME, ICM_ID_PERSON);
		$res += BasicBusiness::count(TBL_EMPLOYMENT, EMP_ID_EMPLOYMENT);
		$res += BasicBusiness::count(TBL_EXPENSE, EXP_ID_EXPENSE_TYPE);
		$res += BasicBusiness::count(TBL_SOCIAL_PROGRAM, SPG_ID_PERSON);
		$res += BasicBusiness::count(TBL_REGISTRATION, REG_ID_REGISTRATION);
		$res += BasicBusiness::count(TBL_LEVEL_INSTRUCTION, LIT_ID_PERSON);

		if($res > 0) return TRUE;
		else return FALSE;
	}
}