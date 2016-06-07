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

abstract class UserValidator extends BasicValidator
{
	/*
	 * valida a identificação da entidade
	 */
	public static function validateEntityId(UserForm &$frm, &$errorMessages = null)
	{
		$config = Zend_Registry::get(CONFIG);
		
		/**
		 * verifica idEntity
		 * Caso haja idEntity ou houver um tipo igual a técnico, operador ou coordenador é feita a verificação
		 */ 
		if(($frm->getIdEntity()) || ($frm->getIdRole() == $config->user->role->technician) 
			|| ($frm->getIdRole() == $config->user->role->operator)
			|| ($frm->getIdRole() == $config->user->role->coordinator)
			|| ($frm->getIdRole() == $config->user->role->manager))
		{
			$notEmpty = parent::validatorNotEmpty();
			if(!$notEmpty->isValid($frm->getIdEntity()))
			{
				$errorMessages[UserForm::idEntity()][] = $notEmpty->getMessages();
			}
			else
			{
				$notInt = parent::validatorInt();
						
				if(!$notInt->isValid($frm->getIdEntity()))
				{
					$errorMessages[UserForm::idEntity()][] = $notInt->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('EntityBusiness');
					$row = EntityBusiness::load($frm->getIdEntity());
					if(count($row) == 0)
					{
						$errorMessages[UserForm::idEntity()][][] = parent::getValidatorResources()->entity->notfound;
					}
				}
			}
		}
		
		return $errorMessages;
	}
	
	/*
	 * valida a identificação de grupo
	 */
	public static function validateGroupId(UserForm &$frm, &$errorMessages = null)
	{
		$config = Zend_Registry::get(CONFIG);
		
		/**
		 * verifica idGroup
		 * Caso haja idGroup ou houver um tipo igual a técnico ou operador é feita a verificação
		 */
		if(($frm->getIdGroup()) || ($frm->getIdRole() == $config->user->role->technician) || ($frm->getIdRole() == $config->user->role->operator))
		{
			$notEmpty = parent::validatorNotEmpty();
			if(!$notEmpty->isValid($frm->getIdGroup()))
			{
				$errorMessages[UserForm::idGroup()][] = $notEmpty->getMessages();
			}
			else
			{
				$notInt = parent::validatorInt();
						
				if(!$notInt->isValid($frm->getIdGroup()))
				{
					$errorMessages[UserForm::idGroup()][] = $notInt->getMessages();
				}
				else
				{
					$groupEntity = UserBusiness::loadGroupByIdEntity($frm->getIdEntity());
					
					foreach($groupEntity as $ge)
						if($ge->{AGE_ID_GROUP} != $frm->getIdGroup()) 
							$flag = false; 
						else{
							$flag = true; 
							break;
						}					
					if($flag === false)
						$errorMessages[UserForm::idGroup()][][] = parent::getValidatorResources()->user->idGroup->entity;
				}
			}
		}
		
		return $errorMessages;
	}
	
	/*
	 * valida e identificação do usuário logado
	 */
	public static function validateRoleId(UserForm &$frm, &$errorMessages = null)
	{
		/* idRole */
		$config = Zend_Registry::get(CONFIG);
		$loggedRole = UserLogged::getUserRoleId();
		
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getIdRole()))
		{
			$errorMessages[UserForm::idRole()][] = $notEmpty->getMessages();
		}
		else
		{
			$notInt = parent::validatorInt();
					
			if(!$notInt->isValid($frm->getIdRole()))
			{
				$errorMessages[UserForm::idRole()][] = $notInt->getMessages();
			}
			else
			{
				if(($frm->getIdRole() == $config->user->role->manager) || ($frm->getIdRole() == $config->user->role->coordinator) || 
					($frm->getIdRole() == $config->user->role->administrator) || ($frm->getIdRole() == $config->user->role->technician) || 
					($frm->getIdRole() == $config->user->role->operator))
				{
					;
				}
				else
				{
					$errorMessages[UserForm::idRole()][][] = parent::getValidatorResources()->user->idRole->notfound;
				}
				/**
				 * verifica idRole
				 * Caso não seja um coordenador é verificado se ele tem permissão
				 */				
				$admin = UserLogged::isAdministrator();
				if($admin === FALSE){
					if(($loggedRole == $config->user->role->manager) || ($loggedRole == $config->user->role->coordinator)){
						if($frm->getIdRole() < $config->user->role->technician)
						{
							$errorMessages[UserForm::idRole()][][] = parent::getValidatorResources()->user->idRole->greaterThan;
						}
					}
				}
			}
		}
		
		return $errorMessages;
	}
	
	/*
	 * valida a identificação do perfil
	 */
	public static function validateProfileId(UserForm &$frm, &$errorMessages = null)
	{
		/* idProfile */
		$notEmpty = parent::validatorNotEmpty();
		
		if(is_array($frm->getIdProfile()))
		{
			foreach($frm->getIdProfile() as $idProfile)
			{
				$notInt = parent::validatorInt();
				
				if(!$notInt->isValid($idProfile))
				{
					$errorMessages[UserForm::idProfile()][] = $notInt->getMessages();
				}
				else
				{
					Zend_Loader::loadClass('UserBusiness');
					$row = UserBusiness::loadOneProfile($frm->getIdProfile());
					if(count($row) == 0)
					{
						$errorMessages[UserForm::idProfile()][][] = parent::getValidatorResources()->user->idProfile->notfound;
					}
				}
			}
		}
		
		return $errorMessages;
	}
	
	/*
	 * valida status do usuário
	 */
	public static function validateStatusUser(UserForm &$frm, &$errorMessages = null)
	{
		/* active */
		$notEmpty = parent::validatorNotEmpty();
		
		if(!($frm->getActive() == 1 || $frm->getActive() == 0)){
			
			if (!$notEmpty->isValid($frm->getActive()))
			{
				$errorMessages[UserForm::active()][] = $notEmpty->getMessages();
			}
			else
			{
				$notInt = parent::validatorInt();
						
				if(!$notInt->isValid($frm->getActive()))
				{
					$errorMessages[UserForm::active()][] = $notInt->getMessages();
				}
			}
		}
		return $errorMessages;
	}
	
	public static function validateUserEqualCpf(UserForm &$frm, &$errorMessages = null)
	{
		Zend_Loader::loadClass('UserBusiness');
		$row = UserBusiness::loadUserByCpf($frm->getCpf());
		if(count($row) > 0)
		{
			$errorMessages[UserForm::cpf()][][] = parent::getValidatorResources()->cpf->equal;
		}
		return $errorMessages;
	}
	
	public static function validateUserData(UserForm &$frm, &$errorMessages = null)
	{	
		/* name */
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getName()))
		{
			$errorMessages[UserForm::name()][] = $notEmpty->getMessages();
		}
		else
		{
			$stringLenght = parent::validatorStringLength(3, 200);
			if(strlen($frm->getName()) > 200)
			{
				$userName = Utils::abbreviate($frm->getName(), 201);
				if (!$stringLenght->isValid($userName))
				{
					$errorMessages[UserForm::name()][] = $stringLenght->getMessages();
				}
			}
			else if (!$stringLenght->isValid($frm->getName()))
			{
				$errorMessages[UserForm::name()][] = $stringLenght->getMessages();
			}
		}
		
		/* CPF */
		$validatorCpf = parent::validatorCpf();
		$stringLenght = parent::validatorStringLength(11, 11);
		if(strlen($frm->getCpf()) > 11)
		{
			$cpf = Utils::abbreviate($frm->getCpf(), 31);
			if(!$stringLenght->isValid($cpf))
			{
				$errorMessages[UserForm::cpf()][] = $stringLenght->getMessages();
			}
		}
		else if(!$stringLenght->isValid($frm->getCpf()))
		{
			$errorMessages[UserForm::cpf()][] = $stringLenght->getMessages();
		}
		else if(!$validatorCpf->isValid($frm->getCpf()))
		{
			$errorMessages[UserForm::cpf()][] = $validatorCpf->getMessages();
		}
		
		/* login */
		$notEmpty = parent::validatorNotEmpty();
		if (!$notEmpty->isValid($frm->getLogin()))
		{
			$errorMessages[UserForm::login()][] = $notEmpty->getMessages();
		}
		else
		{
			$stringLenght = parent::validatorStringLength(4, 12);
			
			if(strlen($frm->getLogin()) > 12)
			{
				$login = Utils::abbreviate($frm->getLogin(), 13);
				if (!$stringLenght->isValid($login))
				{
					$errorMessages[UserForm::login()][] = $stringLenght->getMessages();
				}
			}
			else if (!$stringLenght->isValid($frm->getLogin()))
			{
				$errorMessages[UserForm::login()][] = $stringLenght->getMessages();
			}
			else
			{
				if($frm->getId())
				{				
					$isUniqueLogin = UserBusiness::isUniqueLogin($frm->getId(), $frm->getLogin());
					
					if(!$isUniqueLogin)				
						$errorMessages[UserForm::login()][][] = parent::getValidatorResources()->user->login->unique;
				}
				else
				{
					$isUniqueLogin = UserBusiness::findByLogin($frm->getLogin());
					if($isUniqueLogin->{AUTH_LOGIN_USER})
						$errorMessages[UserForm::login()][][] = parent::getValidatorResources()->user->login->unique;
				}
			}
		}
		
		/* email */
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getEmail()))
		{
			$errorMessages[UserForm::email()][] = $notEmpty->getMessages();
		}
		else
		{
			$emailAdress = parent::validatorEmailAddress();
			if(strlen($frm->getEmail()) > 90)
			{
				$email = Utils::abbreviate($frm->getEmail(), 90);
				if (!$emailAdress->isValid($email))
				{
					$errorMessages[UserForm::email()][] = $emailAdress->getMessages();
				}
			}
			else if (!$emailAdress->isValid($frm->getEmail()))
			{
				$errorMessages[UserForm::email()][] = $emailAdress->getMessages();
			}
/*	WHY? o Else implica em email < 90 e válido.
			 else
			{				
				$stringLenght = parent::validatorStringLength(5, 90);

				if(strlen($frm->getEmail()) > 90)
				{
					$email = Utils::abbreviate($frm->getEmail(), 91);
					if (!$stringLenght->isValid($email))
					{
						$errorMessages[UserForm::email()][][] = parent::getValidatorResources()->user->email->maxlen.$email.parent::getValidatorResources()->text->long2.'30'.parent::getValidatorResources()->text->long3;
					}
				}
				else if (!$stringLenght->isValid($frm->getEmail()))
				{
					$errorMessages[UserForm::email()][][] = $stringLenght->getMessages();
				}
			}
*/
		}
		
		return $errorMessages;
	}
	
	/*
	 * valida identificação do usuário
	 */
	public static function validateUserId(UserForm &$frm, &$errorMessages = null)
	{		
		/* id user */		
		if(is_array($frm->getId()))
		{
			Zend_Loader::loadClass('UserBusiness');
			foreach($frm->getId() as $id)
			{
				self::idUser($id, $errorMessages);
				
				$row = UserBusiness::loadOneUser($id);
				if(count($row) > 0)
				{
					if($row->{AUTH_ID_ROLE} < UserLogged::getUserRoleId())
					{
						$errorMessages[UserForm::id()][][] = parent::getValidatorResources()->user->idUser->autority;
					}
					else if($row->{AUTH_ID_ROLE} == UserLogged::getUserRoleId())
					{
						if(UserLogged::getUserId() != $id)
						{
							$errorMessages[UserForm::id()][][] = parent::getValidatorResources()->user->idUser->autority;
						}
					}
				}
			}
		}
		else
		{
			self::idUser($frm->getId(), $errorMessages);
			Zend_Loader::loadClass('UserBusiness');
			$row = UserBusiness::loadOneUser($frm->getId());
			if(count($row) > 0)
			{				
				if($row->{AUTH_ID_ROLE} < UserLogged::getUserRoleId())
				{
					$errorMessages[UserForm::id()][][] = parent::getValidatorResources()->user->idUser->autority;
				}
				else if($row->{AUTH_ID_ROLE} == UserLogged::getUserRoleId())
				{
					if(UserLogged::getUserId() != $frm->getId())
					{
						$errorMessages[UserForm::id()][][] = parent::getValidatorResources()->user->idUser->autority;
					}
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateUserFlagPassword(UserForm &$frm, &$errorMessages = null)
	{
		/* password com flag */
		$notEmpty = parent::validatorNotEmpty();
		
		if($frm->getFlagPassword())
		{
			if (!$notEmpty->isValid($frm->getFlagPassword()))
			{
				$errorMessages[UserForm::flagPassword()][] = $notEmpty->getMessages();
			}
			else
			{
				$notInt = parent::validatorInt();
						
				if(!$notInt->isValid($frm->getFlagPassword()))
				{
					$errorMessages[UserForm::flagPassword()][] = $notInt->getMessages();
				}
				else
				{
					$notEmpty = parent::validatorNotEmpty();
			
					if (!$notEmpty->isValid($frm->getPassword()))
					{
						$errorMessages[UserForm::password()][] = $notEmpty->getMessages();
					}
					else
					{
						if(strlen($frm->getPassword()) > 32)
						{							
							$errorMessages[UserForm::password()][][] = parent::getValidatorResources()->password->max.'32'.parent::getValidatorResources()->text->long3;
						}
						else if (strlen($frm->getPassword()) < 4)
						{
							$errorMessages[UserForm::password()][][] = parent::getValidatorResources()->password->min.'4'.parent::getValidatorResources()->text->long3;
						}
					}
				}
			}
		}
	}
	
	public static function validateUserPassword(UserForm &$frm, &$errorMessages = null)
	{
		/* password sem flag */
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getPassword()))
		{
			$errorMessages[UserForm::password()][] = $notEmpty->getMessages();
		}
		else
		{
			if(strlen($frm->getPassword()) > 32)
			{							
				$errorMessages[UserForm::password()][][] = parent::getValidatorResources()->password->max.'32'.parent::getValidatorResources()->text->long3;
			}
			else if (strlen($frm->getPassword()) < 4)
			{
				$errorMessages[UserForm::password()][][] = parent::getValidatorResources()->password->min.'4'.parent::getValidatorResources()->text->long3;
			}
		}		
	}
	
	/**
	 * Utilizada no caso de uso alterar senha do usuário - campo "repetir senha"
	 */
	public static function validateUserRepeatPassword(UserForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getRepeatPassword()))
		{
			$errorMessages[UserForm::repeatPassword()][] = $notEmpty->getMessages();
		}
		else
		{
			$stringLenght = parent::validatorStringLength(4, 32);
			
			if(strlen($frm->getRepeatPassword()) > 32)
			{
				$pwd = Utils::abbreviate($frm->getRepeatPassword(), 33);
				if (!$stringLenght->isValid($pwd))
				{
					$errorMessages[UserForm::repeatPassword()][] = $stringLenght->getMessages();
				}
			}
			else if (!$stringLenght->isValid($frm->getRepeatPassword()))
			{
				$errorMessages[UserForm::repeatPassword()][] = $stringLenght->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Utilizada no caso de uso alterar senha do usuário - campo "senha atual"
	 */
	public static function validateUserOldPassword(UserForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		
		if (!$notEmpty->isValid($frm->getOldPassword()))
		{
			$errorMessages[UserForm::oldPassword()][] = $notEmpty->getMessages();
		}
		else
		{
			$stringLenght = parent::validatorStringLength(4, 32);
			
			if(strlen($frm->getOldPassword()) > 32)
			{
				$pwd = Utils::abbreviate($frm->getOldPassword(), 33);
				if (!$stringLenght->isValid($pwd))
				{
					$errorMessages[UserForm::oldPassword()][] = $stringLenght->getMessages();
				}
			}
			else if (!$stringLenght->isValid($frm->getOldPassword()))
			{
				$errorMessages[UserForm::oldPassword()][] = $stringLenght->getMessages();
			}
		}
		
		return $errorMessages;
	}
	
	/**
	 * Verifica se senha digitada pelo usuário é a mesma que está cadastrada no banco de dados
	 */
	public static function validatePasswordIsEqualDatabase(UserForm &$frm, &$errorMessages = null)
	{	
		$validator = parent::validatorPasswordIsEqualDatabase();
		if (!$validator->isValid($frm->getOldPassword()))
		{
			$errorMessages[UserForm::oldPassword()][] = $validator->getMessages();
		}
		
		return $errorMessages;
	}
	
	public static function validateEqualsBetweenPasswordTyped(UserForm &$frm, &$errorMessages = null)
	{	
		if($frm->getPassword() != null && $frm->getRepeatPassword() != null)
		{
			if(strlen($frm->getPassword()) > 0 && strlen($frm->getRepeatPassword()) > 0)
			{
				if($frm->getPassword() != $frm->getRepeatPassword())
				{
					$errorMessages[UserForm::repeatPassword()][][] = parent::getValidatorResources()->user->repeatPasswordDiff;
					$errorMessages[UserForm::password()][][] = parent::getValidatorResources()->user->passwordDiff;		
				}
			}
		}
		
		return $errorMessages;
	}

	public static function validateIdUser(UserForm &$frm, &$errorMessages = null)
	{
		$notEmpty = parent::validatorNotEmpty();
		if(!$notEmpty->isValid($frm->getUserId()))
		{
			$errorMessages[BasicForm::userId()][] = $notEmpty->getMessages();
		}
		else
		{
			$validator = parent::validatorInt();
			
			if(!$validator->isValid($frm->getUserId()))
			{
				$errorMessages[BasicForm::userId()][] = $validator->getMessages();
			}
			else
			{
				Zend_Loader::loadClass('UserBusiness');
				
				$row = UserBusiness::loadOneUser($frm->getUserId());				
				
				if(count($row) == 0)
				{
					$errorMessages[BasicForm::userId()][][] = parent::getValidatorResources()->user->notfound;					
				}
			}
		}
		
		return $errorMessages;
	}
	
	public static function validateUserAdd(UserForm &$frm)
	{
		$errorMessages = null;
		UserValidator::validateUserData($frm, $errorMessages);
		UserValidator::validateUserEqualCpf($frm, $errorMessages);
		UserValidator::validateRoleId($frm, $errorMessages);
		UserValidator::validateEntityId($frm, $errorMessages);
		UserValidator::validateGroupId($frm, $errorMessages);
		UserValidator::validateProfileId($frm, $errorMessages);
		UserValidator::validateStatusUser($frm, $errorMessages);
		UserValidator::validateUserPassword($frm, $errorMessages);

		return $errorMessages;
	}
	
	public static function validateUserEdit(UserForm &$frm)
	{
		$errorMessages = null;
		UserValidator::validateUserData($frm, $errorMessages);
		UserValidator::validateRoleId($frm, $errorMessages);
		UserValidator::validateEntityId($frm, $errorMessages);
		UserValidator::validateGroupId($frm, $errorMessages);
		UserValidator::validateProfileId($frm, $errorMessages);
		UserValidator::validateStatusUser($frm, $errorMessages);
		UserValidator::validateUserFlagPassword($frm, $errorMessages);
		UserValidator::validateUserId($frm, $errorMessages);

		return $errorMessages;
	}
	
	public static function validateChangePassword(UserForm &$frm)
	{
		$errorMessages = null;
		UserValidator::validateUserOldPassword($frm, $errorMessages);
		UserValidator::validateUserPassword($frm, $errorMessages);
		UserValidator::validateUserRepeatPassword($frm, $errorMessages);
		UserValidator::validatePasswordIsEqualDatabase($frm, $errorMessages);
		UserValidator::validateEqualsBetweenPasswordTyped($frm, $errorMessages);
		
		return $errorMessages;
	}
	
	function idUser($id, &$errorMessages = null)
	{
		/* id user */
		$notEmpty = parent::validatorNotEmpty();
		$notInt = parent::validatorInt();
		
		if(!$notEmpty->isValid($id))
		{
			$errorMessages[UserForm::id()][] = $notEmpty->getMessages();
		}
		else
		{
			if(!$notInt->isValid($id))
			{
				$errorMessages[UserForm::id()][] = $notInt->getMessages();
			}
			else
			{
				// verifica se usuario pertence a determinada entidade
				$admin = UserLogged::isAdministrator();
				if($admin === FALSE){
					$user = UserBusiness::loadOneUser($id);
					if(count($user) > 0){
						$idEntity = UserLogged::getEntityId();
						if($user->{AUTH_ID_ENTITY_USER} != $idEntity){
							$errorMessages[UserForm::id()][][] = parent::getValidatorResources()->user->idUser->entity;
						}
					}							
				}
			}
		}
		return $errorMessages;
	}
}
