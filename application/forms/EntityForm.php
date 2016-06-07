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
 * Fabricio Meireles Monteiro  - W3S		   		18/02/2008	                       Create file 
 * 
 */

require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('F_ENTITY_ID', 			'entity_id');
define('F_ENTITY_NAME',			'entityName');
define('F_ENTITY_CNPJ',			'entityCnpj');
define('F_ENTITY_EMAIL',		'entity_email');
define('F_ENTITY_HOMEPAGE',		'homepage');

define('F_PHONE_ID',			'phone_id');
define('F_PHONE_TYPE',			'phone_type');
define('F_PHONE_NUMBER',		'phone_number');
define('F_PHONE_CODE_AREA',		'phone_code_area');
/* Id de grupo */
define('F_GROUP_ENTITY',		'group_entity');

/* Id do programa */
define('F_PROGRAM_ID',			'program_id');

/* Id da classificação */
define('F_CLASSIFICATION_ID',	'classification_id');

/* Id da área */
define('F_AREA_ID',				'area_id');

class EntityForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $id;
	private $entityName;
	private $email;
	private $homePage;
	
	private $idPhone;
	private $phoneType;
	private $phoneNumber;
	private $phoneCodeArea;
	private $groupEntity;
	private $idProgram;
	private $idClassification;
	private $idArea;
	private $entityCnpj;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function id(){return F_ENTITY_ID;} 
	public static function entityName(){return F_ENTITY_NAME;}
	public static function entityCnpj(){return F_ENTITY_CNPJ;}
	public static function email(){return F_ENTITY_EMAIL;}
	public static function homePage(){return F_ENTITY_HOMEPAGE;}
	
	public static function phoneId(){return F_PHONE_ID;}
	public static function phoneType(){return F_PHONE_TYPE;}
	public static function phoneNumber(){return F_PHONE_NUMBER;}
	public static function phoneCodeArea(){return F_PHONE_CODE_AREA;}
	public static function groupEntity(){return F_GROUP_ENTITY;}
	public static function programId(){return F_PROGRAM_ID;}
	public static function classificationId(){return F_CLASSIFICATION_ID;}
	public static function areaId(){return F_AREA_ID;}
	

	/**
	 * Preenche os valores vindos do request
	 * "&" : pega a mesma instância da memória. 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);

		$this->id = $_request->getParam(EntityForm::id());
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->id 				= $_request->getPost(EntityForm::id());
			$this->entityName 		= trim($filter->filter($_request->getPost(EntityForm::entityName())));
			$this->entityCnpj 		= trim($filter->filter($_request->getPost(EntityForm::entityCnpj())));
			$this->email 			= trim($filter->filter($_request->getPost(EntityForm::email())));
			$this->homePage 		= trim($filter->filter($_request->getPost(EntityForm::homePage())));

			$this->phoneId 			= $_request->getPost(EntityForm::phoneId());
			$this->phoneType 		= $_request->getPost(EntityForm::phoneType());
			$this->phoneNumber 		= $_request->getPost(EntityForm::phoneNumber());
			$this->phoneCodeArea	= $_request->getPost(EntityForm::phoneCodeArea());
			$this->groupEntity		= $_request->getPost(EntityForm::groupEntity());
			$this->programId		= $_request->getPost(EntityForm::programId());
			$this->classificationId	= $_request->getPost(EntityForm::classificationId());
			$this->areaId			= $_request->getPost(EntityForm::areaId());
		}
	}	
	
	/**
	 * Getters and Setters
	 */
	 public function getPhoneId(){ return $this->phoneId; }
	 public function getPhoneType(){ return $this->phoneType; }
	 public function getPhoneNumber(){ return $this->phoneNumber; }
	 public function getPhoneCodeArea(){ return $this->phoneCodeArea; }
	 public function getGroupEntity(){ return $this->groupEntity; }
	 public function getId(){return $this->id; }
	 public function getEntityName(){return $this->entityName; }
	 public function getEntityCnpj(){return $this->entityCnpj; }
	 public function getEmail(){return $this->email; }
	 public function getHomePage(){return $this->homePage; }
	 public function getProgramId(){return $this->programId; }
	 public function getClassificationId(){return $this->classificationId; }
	 public function getAreaId(){return $this->areaId; }
	 
	 public function setPhoneId($phoneId){ $this->phoneId = $phoneId; }
	 public function setPhoneType($phoneType){ $this->phoneType = $phoneType; }
	 public function setPhoneNumber($phoneNumber){ $this->phoneNumber = $phoneNumber; }
	 public function setPhoneCodeArea($phoneCodeArea){ $this->phoneCodeArea = $phoneCodeArea; }
	 public function setId($id){$this->id = $id; }
	 public function setEntityName($entityName){$this->entityName = $entityName; }
	 public function setEntityCnpj($entityCnpj){$this->entityCnpj = $entityCnpj; }
	 public function setEmail($email){$this->email = $email; }
	 public function setProgramId($programId){$this->programId = $programId; }
	 public function setClassificationId($classificationId){$this->classificationId = $classificationId; }
	 public function setAreaId($areaId){$this->areaId = $areaId; }
}