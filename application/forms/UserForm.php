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
 * Saulo Esteves Rodrigues  - W3S		   			31/01/2008	                       Create file 
 * 
 */

require_once('BasicForm.php');

define('F_ID_USER', 		'id_user');
define('F_ID_ENTITY',		'id_entity');
define('F_ID_GROUP', 		'id_group');
define('F_ID_ROLE',			'id_role');
define('F_NAME', 			'name');
define('F_LOGIN', 			'login');
define('F_PASSWORD', 		'pass');
define('F_ACTIVE', 			'active');
define('F_EMAIL', 			'email');
define('F_CPF', 			'cpf');
define('F_CREATION_DATE',	'dat_creation');
define('F_ID_PROFILE',		'id_profile');
define('F_FLAG_PASSWORD',	'flag_password');
define('F_REPEAT_PASSWORD',	'repeatPassword');
define('F_OLD_PASSWORD',	'oldPassword');

/* utilizado apenas para link de visualizar entidade */
define('F_ENTITY_ID', 			'entity_id');

class UserForm extends BasicForm
{
	/**
	 * Campos
	 * 
	 */
	private $id;
	private $idEntity;
	private $idGroup;
	private $idRole;
	private $name;
	private $login;
	private $password;
	private $active;
	private $email;
	private $cpf;
	private $creationDate;
	private $idProfile;
	private $flagPassword;
	private $repeatPassword;
	private $oldPassword;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	/* utilizado apenas para link de visualizar entidade */
	public static function entityId()		{ return F_ENTITY_ID;}
	
	public static function id() 			{ return F_ID_USER; }
	public static function idEntity() 		{ return F_ID_ENTITY; }
	public static function idGroup() 		{ return F_ID_GROUP; }
	public static function idRole() 		{ return F_ID_ROLE; }
	public static function name() 			{ return F_NAME; }
	public static function login() 			{ return F_LOGIN; }
	public static function password() 		{ return F_PASSWORD; }
	public static function active() 		{ return F_ACTIVE; }
	public static function email() 			{ return F_EMAIL; }
	public static function cpf() 			{ return F_CPF; }
	public static function creationDate() 	{ return F_CREATION_DATE; }
	public static function idProfile() 		{ return F_ID_PROFILE; }
	public static function flagPassword() 	{ return F_FLAG_PASSWORD; }
	public static function repeatPassword()	{ return F_REPEAT_PASSWORD; }
	public static function oldPassword()	{ return F_OLD_PASSWORD; }
	
	/**
	 * Preenche os valores vindos do request
	 * 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
	
		$this->id 			= $_request->getParam(UserForm::id());
		$this->idEntity 	= $_request->getParam(UserForm::idEntity());
		$this->idGroup 		= $_request->getParam(UserForm::idGroup());
		$this->idRole 		= $_request->getParam(UserForm::idRole());
		$this->name 		= $_request->getParam(UserForm::name());
		$this->login 		= trim($_request->getParam(UserForm::login()));
		$this->flagPassword	= $_request->getParam(UserForm::flagPassword());
		$this->password 	= $_request->getParam(UserForm::password());
		$this->active 		= $_request->getParam(UserForm::active());
		$this->email 		= $_request->getParam(UserForm::email());
		$this->cpf 			= $_request->getParam(UserForm::cpf());
		$this->creationDate = $_request->getParam(UserForm::creationDate());
		$this->idProfile	= $_request->getParam(UserForm::idProfile());
		
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->id 				= $_request->getPost(UserForm::id());
			$this->idEntity 		= trim($filter->filter($_request->getPost(UserForm::idEntity())));
			$this->idGroup 			= trim($filter->filter($_request->getPost(UserForm::idGroup())));
			$this->idRole 			= $_request->getPost(UserForm::idRole());
			$this->name 			= trim($filter->filter($_request->getPost(UserForm::name())));
			$this->login 			= trim($filter->filter($_request->getPost(UserForm::login())));
			$this->flagPassword		= $_request->getPost(UserForm::flagPassword());
			$this->password 		= trim($filter->filter($_request->getPost(UserForm::password())));
			$this->active 			= trim($filter->filter($_request->getPost(UserForm::active())));
			$this->email 			= trim($filter->filter($_request->getPost(UserForm::email())));
			$this->cpf 				= trim($filter->filter($_request->getPost(UserForm::cpf())));
			$this->creationDate		= trim($filter->filter($_request->getPost(UserForm::creationDate())));
			$this->idProfile		= $_request->getPost(UserForm::idProfile());
			$this->repeatPassword 	= trim($filter->filter($_request->getPost(UserForm::repeatPassword())));
			$this->oldPassword	 	= trim($filter->filter($_request->getPost(UserForm::oldPassword())));
		}
	}
	
	/**
	 * Recupera as informações do form e retorna no array
	 * 
	 */
	function assembleFormToUser(UserForm $frm)
	{
		$user = array();
				
		$user[F_ID_USER] 		= $frm->getId();
		$user[F_ID_ENTITY] 		= $frm->getIdEntity();
		$user[F_ID_GROUP] 		= $frm->getIdGroup();
		$user[F_ID_ROLE] 		= $frm->getIdRole();
		$user[F_NAME] 			= $frm->getName();
		$user[F_LOGIN] 			= $frm->getLogin();
		$user[F_FLAG_PASSWORD] 	= $frm->getFlagPassword();
		$user[F_PASSWORD] 		= $frm->getPassword();
		$user[F_ACTIVE] 		= $frm->getActive();
		$user[F_EMAIL] 			= $frm->getEmail();
		$user[F_CPF]			= $frm->getCpf();
		$user[F_CREATION_DATE]	= $frm->getCreationDate();
		$user[F_ID_PROFILE]		= $frm->getIdProfile();
		
		return $user;
	}
	
	/*
	 * Recupera as informações do objeto e insere no form
	 * 
	 */
	function assembleUserToForm($user)
	{
		if(!empty($user))
		{
			$this->view->form->setId($user->{AUTH_ID_USER});
			$this->view->form->setIdEntity($user->{AUTH_ID_ENTITY_USER});
			$this->view->form->setIdGroup($user->{AUTH_ID_GROUP_USER});
			$this->view->form->setIdRole($user->{AUTH_ID_ROLE_USER});
			$this->view->form->setName($user->{AUTH_NAME_USER});
			$this->view->form->setLogin($user->{AUTH_LOGIN_USER});
			$this->view->form->setPassword($user->{AUTH_PASSWORD_USER});
			$this->view->form->setEmail($user->{AUTH_EMAIL_USER});
			$this->view->form->setCpf($user->{AUTH_CPF_USER});
			$this->view->form->setActive($user->{AUTH_ACTIVE_USER});
			$this->view->form->setCreationDate($user->{AUTH_CREATION_DATE_USER});
		}
	}
	
	function assembleUserProfileToForm($userProfile)
	{
		if(!empty($userProfile))
		{
			foreach($userProfile as $frm)
			{
				$this->view->form->setIdProfile($frm);
			}
		}
	}
	
	/**
	 * Getters and Setters
	 * 
	 */
	 public function getId()			{return $this->id;}
	 public function getIdEntity()		{return $this->idEntity;}
	 public function getIdGroup()		{return $this->idGroup;}
	 public function getIdRole()		{return $this->idRole;}
	 public function getName()			{return $this->name;}
	 public function getLogin()			{return $this->login;}
	 public function getFlagPassword()		{return $this->flagPassword;}
	 public function getPassword()		{return $this->password;}
	 public function getActive()		{return $this->active;}
	 public function getEmail()			{return $this->email;}
	 public function getCpf()			{return $this->cpf;}
	 public function getCreationDate()	{return $this->creationDate;}
	 public function getIdProfile()		{return $this->idProfile;}
	 public function getRepeatPassword(){return $this->repeatPassword;}
	 public function getOldPassword()	{return $this->oldPassword;}
	
	 public function setId($id){$this->id = $id;}
	 public function setIdEntity($idEntity){$this->idEntity = $idEntity;}
	 public function setIdGroup($idGroup){$this->idGroup = $idGroup;}
	 public function setIdRole($idRole){$this->idRole = $idRole;}
	 public function setName($name){$this->name = $name;}
	 public function setLogin($login){$this->login = $login;}
	 public function setFlagPassword($flagPassword){$this->flagPassword = $flagPassword;}
	 public function setPassword($password){$this->password = $password;}
	 public function setActive($active){$this->active = $active;}
	 public function setEmail($email){$this->email = $email;}
	 public function setCpf($cpf){$this->cpf = $cpf;}
	 public function setCreationDate($creationDate){$this->creationDate = $creationDate;}
	 public function setIdProfile($idProfile){$this->idProfile 	= $idProfile;}
	 public function setRepeatPassword($repeatPassword){$this->repeatPassword = $repeatPassword;}
	 public function setOldPassword($oldPassword){$this->oldPassword = $oldPassword;}
}