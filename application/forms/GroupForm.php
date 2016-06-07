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
require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('F_ID_GROUP',				'id');
define('F_NAME_GROUP',				'groupName');
define('F_ROLE_ID',					'role_id');
define('F_ID_RESOURCE', 			'id_resource');
define('F_READ_ONLY', 				'readOnly');
define('F_CHANGE_CONFIDENTIALITY', 	'changeConfidentiality');
define('F_DEFAULT_CONFIDENTIALITY', 'defaultConfidentiality');
define('F_STATUS', 'status');

class GroupForm extends BasicForm
{
	/**
	 * Campos
	 * 
	 */
	private $id;
	private $groupName;
	private $roleId;
	private $resourceId;
	private $readOnly;
	private $changeConfidentiality;
	private $defaultConfidentiality;
	private $status;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function id() { return F_ID_GROUP; }
	public static function groupName() { return F_NAME_GROUP; }
	public static function roleId() { return F_ROLE_ID; }
	public static function resourceId() { return F_ID_RESOURCE; }
	public static function readOnly() { return F_READ_ONLY; }
	public static function changeConfidentiality() { return F_CHANGE_CONFIDENTIALITY; }
	public static function defaultConfidentiality() { return F_DEFAULT_CONFIDENTIALITY; }
	public static function status() { return F_STATUS; }
	
	/**
	 * Preenche os valores vindos do request
	 * 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->id = $_request->getParam(GroupForm::id());
		$this->groupName = $_request->getParam(GroupForm::groupName());
		if($_request->isPost())
		{
			$filter				= BasicForm::getFilterStripTags();
			$this->id 			= $_request->getPost(GroupForm::id());
			$this->groupName 	= trim($filter->filter($_request->getPost(GroupForm::groupName())));
			$this->roleId 		= $_request->getPost(GroupForm::roleId());
			
			$this->resourceId				= $_request->getPost(GroupForm::resourceId());
			$this->readOnly					= $_request->getPost(GroupForm::readOnly());
			$this->changeConfidentiality	= $_request->getPost(GroupForm::changeConfidentiality());
			$this->defaultConfidentiality	= $_request->getPost(GroupForm::defaultConfidentiality());
			$this->status					= $_request->getPost(GroupForm::status());
		}
	}
	
	/**
	 * Recupera as informações do form e retorna no objeto Group
	 * 
	 */
	function assembleFormToGroup(GroupForm $frm)
	{
		$group 					= new Group();
		$group->{AGP_ID_GROUP} 	= $frm->getId();
		$group->{AGP_GROUP} 	= $frm->getGroupName();
		$group->{AUTH_STATUS} 	= $frm->getStatus();

		return $group;
	}
	
	/**
	 * Recupera as informações do objeto e insere no form
	 * 
	 */
	function assembleGroupToForm($group)
	{
		if(!empty($group))
		{
			$this->view->form->setId($group->{AGP_ID_GROUP});
			$this->view->form->setGroupName($group->{AGP_GROUP});
			$this->view->form->setStatus($group->{AUTH_STATUS});
		}
	}
	
	/**
	 * Recupera a informação de permissionamento do form;
	 * 
	 */
	function assembleFormToGroupResource(GroupForm $frm)
	{
		$gr = array
			(
				AGR_RESOURCE_ID => $frm->getResourceId(),
				AGR_ROLE_ID		=> $frm->getRoleId(),
				AGR_ID_GROUP	=> $frm->getId(),
				AGR_READONLY	=> $frm->isReadOnly(),
				AGR_CHANGE_CONFIDENTIALITY	=> $frm->isChangeConfidentiality(),
				AGR_DEFAULT_CONFIDENTIALITY => $frm->getDefaultConfidentiality()
			);
		return $gr;
	}
	
	/**
	 * Getters and Setters
	 * 
	 */
	 public function getId(){return $this->id;}
	 public function getGroupName(){return $this->groupName;}
	 public function getStatus(){return $this->status;}
	 public function getRoleId(){return $this->roleId;}
	 public function getResourceId(){return $this->resourceId;}
	 public function isReadOnly(){
	 	if($this->readOnly)
	 		return true;
	 	else
	 		return false;
	 }
	 public function isChangeConfidentiality(){
	 	if($this->changeConfidentiality)
	 		return true;
	 	else
	 		return false;
	 }
	 public function getDefaultConfidentiality(){return $this->defaultConfidentiality;}
	 
	 public function setId($id){$this->id = $id;}
	 public function setGroupName($groupName){$this->groupName = $groupName;}
	 public function setStatus($status){$this->status = $status;}
	 public function setRoleId($roleId){$this->roleId = $roleId;}
	 public function setResourceId($resourceId){$this->resourceOd = $resourceId;}
	 public function setReadOnly($readOnly){$this->readOnly = $readOnly;}
	 public function setChangeConfidentiality($changeConfidentiality){$this->changeConfidentiality = $changeConfidentiality;}
	 public function setDefaultConfidentiality($defaultConfidentiality){$this->defaultConfidentiality = $defaultConfidentiality;}
}

