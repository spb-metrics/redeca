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

abstract class BasicValidator
{
	public static function validatorStringLength($min, $max)
	{
		Zend_Loader::loadClass('Zend_Validate_StringLength');
		$validator = new Zend_Validate_StringLength($min, $max);		
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
		    	Zend_Validate_StringLength::TOO_SHORT => $resources->string->length->tooshort,
		    	Zend_Validate_StringLength::TOO_LONG  => $resources->string->length->toolong,
			)
		);
		return $validator;
	}
	
	public static function validatorInt()
	{
		Zend_Loader::loadClass('Zend_Validate_Int');
		$validator = new Zend_Validate_Int();		
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
		    	Zend_Validate_Int::NOT_INT => $resources->integer->invalid
			)
		);
		return $validator;
	}
	
	public static function validatorFloat()
	{
		Zend_Loader::loadClass('Zend_Validate_Float');
		$validator = new Zend_Validate_Float();		
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
		    	Zend_Validate_Float::NOT_FLOAT => $resources->float->invalid
			)
		);
		return $validator;
	}

	public static function validatorEmailAddress()
	{
		Zend_Loader::loadClass('Zend_Validate_EmailAddress');
		$validator = new Zend_Validate_EmailAddress();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
		    	Zend_Validate_EmailAddress::INVALID => $resources->emailAddress->invalid,
		    	Zend_Validate_EmailAddress::INVALID_HOSTNAME => $resources->emailAddress->invalidHostname,
		    	Zend_Validate_EmailAddress::INVALID_MX_RECORD => $resources->emailAddress->invalidMxRecord,
		    	Zend_Validate_EmailAddress::DOT_ATOM => $resources->emailAddress->invalidDotAtom,
		    	Zend_Validate_EmailAddress::QUOTED_STRING => $resources->emailAddress->invalidQuotedString,
		    	Zend_Validate_EmailAddress::INVALID_LOCAL_PART => $resources->emailAddress->invalidLocalPart
			)
		);
		return $validator;		
	}
	
	public static function validatorNotEmpty()
	{
		Zend_Loader::loadClass('Zend_Validate_NotEmpty');
		$validator = new Zend_Validate_NotEmpty();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
				Zend_Validate_NotEmpty::IS_EMPTY => $resources->notEmpty->isEmpty
			)
		);
		return $validator;
	}
	
	public static function validatorAlpha()
	{
		Zend_Loader::loadClass('Zend_Validate_Alpha');
		$validator = new Zend_Validate_Alpha();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
				Zend_Validate_Alpha::NOT_ALPHA => $resources->alpha->notAlpha,
				Zend_Validate_Alpha::STRING_EMPTY => $resources->alpha->stringEmpty
			)
		);
		return $validator;
	}
	/**
	 * Valida a extensão de um arquivo
	 */
	public static function validatorExtensionFile($ext)
	{
		Zend_Loader::loadClass('FileExtensionValidate');
		$validator = new FileExtensionValidate($ext);		
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
		    	FileExtensionValidate::EXTENSION => $resources->file->extension->invalid
			)
		);
		return $validator;
	}
	
	/**
	 * Valida upload
	 */
	public static function validatorUploadFile($fieldName, $allowed_mime_types, $max_file_size)
	{
		Zend_Loader::loadClass('HttpUploadValidate');
		$validator = new HttpUploadValidate($fieldName, $allowed_mime_types, $max_file_size);
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
		    	HttpUploadValidate::MAX_SIZE_EXCEEDED => $resources->file->maxsize->exceeded,
		    	HttpUploadValidate::MIN_SIZE_NEEDED => $resources->file->minsize->needed
			)
		);
		return $validator;
	}
	
	public static function validatorDate()
	{
		Zend_Loader::loadClass('Zend_Validate_Date');
		$validator = new Zend_Validate_Date();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
				Zend_Validate_Date::INVALID => $resources->dateFormat->invalid,
				Zend_Validate_Date::NOT_YYYY_MM_DD => $resources->dateFormat->notYyyyMmDd	
			)
		);
		return $validator;
	}

	/** 
	 * Valida o CPF difgitado pelo usuário
	 */
	public static function validatorCpf()
	{
		Zend_Loader::loadClass('Validate_Cpf');
		$validator = new Validate_Cpf();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	Validate_Cpf::INVALID => $resources->cpf->invalid
			)
		);
		return $validator;
	}
	
	/** 
	 * Valida o CNPJ difgitado pelo usuário
	 */
	 public static function validatorCnpj()
	{
		Zend_Loader::loadClass('Validate_Cnpj');
		$validator = new Validate_Cnpj();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	Validate_Cnpj::INVALID => $resources->cnpj->invalid
			)
		);
		return $validator;
	}
	
	/**
	 * Verifica a idade da pessoa informada
	 */
	public static function validatorAge()
	{
		Zend_Loader::loadClass('Validate_Age');
		$validator = new Validate_Age();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	Validate_Age::INVALID => $resources->age->invalid
			)
		);
		return $validator;
	}
			
	/**
	 * Verifica se o programa a ser excluído está sendo usado pelo atendimento
	 */
	public static function validatorProgramInAssistance()
	{
		Zend_Loader::loadClass('ProgramUsedInAssistance');
		$validator = new ProgramUsedInAssistance();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	ProgramUsedInAssistance::INVALID => $resources->programUsedInAssistance->alert
			)
		);
	
		return $validator;
	}
	
	/**
	 * Verifica se o programa a ser excluído está sendo usado pela turma
	 */
	public static function validatorProgramInClass()
	{
		Zend_Loader::loadClass('ProgramInClass');
		$validator = new ProgramInClass();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	ProgramInClass::INVALID => $resources->programInClass->alert
			)
		);
	
		return $validator;
	}
	
	/**
	 * Verifica se o programa a ser excluído está sendo usado por detalhamento de atividades da entidade
	 */
	public static function validatorProgramInActivityDetail()
	{
		Zend_Loader::loadClass('ProgramInActivityDetail');
		$validator = new ProgramInActivityDetail();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	ProgramInActivityDetail::INVALID => $resources->programInActivityDetail->alert
			)
		);
	
		return $validator;
	}
	 
	/**
	 * Verifica se a atividade detalhada está sendo usada por alguma turma 
	 */
	public static function validatorActivityDetailInClass()
	{
		Zend_Loader::loadClass('ActivityDetailUsedInClass');
		$validator = new ActivityDetailUsedInClass();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	ActivityDetailUsedInClass::INVALID => $resources->activityDetailUsedInClass->alert
			)
		);
	
		return $validator;
	}
	
	/**
	 * Verifica se senha digitada pelo usuário é a mesma cadastrada no banco de dados
	 */
	public static function validatorPasswordIsEqualDatabase()
	{
		Zend_Loader::loadClass('ValidatePasswordIsEqualDatabase');
		$validator = new ValidatePasswordIsEqualDatabase();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	ValidatePasswordIsEqualDatabase::INVALID => $resources->passwordDiffInDatabase->alert
			)
		);
		return $validator;
	}
	
	/**
	 * Verifica se turma tem pessoa matriculada
	 */
	public static function validatorPersonInClass()
	{
		Zend_Loader::loadClass('PersonInClass');
		$validator = new PersonInClass();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	PersonInClass::INVALID => $resources->notPersonInClass->alert
			)
		);
		return $validator;
	}
	
	/**
	 * Verifica se uma família já tem um representante
	 * FALSE : família já tem um representante
	 * TRUE  : família não tem um representante
	 */
	public static function validateHasRepresentative()
	{
		Zend_Loader::loadClass('RepresentativeValidate');
		$validator = new RepresentativeValidate();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	RepresentativeValidate::INVALID => $resources->representative->invalid
			)
		);
		
		return $validator;
	}
	
	/**
	 * Verifica se o representante da família é pessoa em questão
	 * FALSE : pessoa é o representante da família
	 * TRUE  : pessoa não é o representante da família
	 */
	public static function validatePersonIsRepresentative()
	{
		Zend_Loader::loadClass('ValidatePersonRepresentative');
		$validator = new ValidatePersonRepresentative();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	ValidatePersonRepresentative::INVALID => $resources->personIsRepresentative->invalid
			)
		);
		return $validator;
	}
	
	/**
	 * Verifica se a pessoa em questão é do sexo feminino
	 * FALSE : pessoa é 
	 * TRUE  : pessoa não é o representante da família
	 */
	public static function validatePersonIsFemale()
	{
		Zend_Loader::loadClass('ValidateSex');
		$validator = new ValidateSex();
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages
		(
			array
			(
		    	ValidateSex::INVALID => $resources->sex->invalid
			)
		);
		return $validator;
	}
	
	
	
	public static function validatorBetween($min, $max, $strict = true)
	{
		Zend_Loader::loadClass('Zend_Validate_Between');
		$validator = new Zend_Validate_Between($min, $max, $strict);
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
				Zend_Validate_Between::NOT_BETWEEN        => $resources->between->notBetween,
       			Zend_Validate_Between::NOT_BETWEEN_STRICT => $resources->between->notBetweenStrict
			)
		);
		return $validator;
	}
	
	public static function validatorWords($string)
	{
		Zend_Loader::loadClass('ValidateWords');
		$validator = new ValidateWords($string);
		$resources = BasicValidator::getValidatorResources();
		
		$validator->setMessages(
			array
			(
				ValidateWords::INVALID        => $resources->words->largest
			)
		);
		return $validator;
	}
	
	protected static function getValidatorResources()
	{
		return Zend_Registry::get(VALIDATOR_RESOURCES);
	}
}