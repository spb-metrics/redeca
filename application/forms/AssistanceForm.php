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
 * Fabricio Meireles Monteiro  - W3S		   		05/05/2008	                       Create file 
 * 
 */
 
require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('F_AST_ASSISTANCE_ID',	'assistance_id');
define('F_AST_END_DATE',		'end_date');
define('F_AST_CONFIDENTIALITY',	'confidentiality');
define('F_AST_PROGRAM_TYPE',	'program_type');
define('F_AST_CLASS_ID',		'class_id');
define('F_AST_CLASS_STATUS_ID',	'id_status');
define('F_AST_ENTITY',			'entity');

class AssistanceForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $assistanceId;
	private $endDate;	
	private $confidentiality;
	private $programType;
	private $entity;
	
	private $classId;
	private $idStatus;

	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function assistanceId(){return F_AST_ASSISTANCE_ID;}
	public static function endDate(){return F_AST_END_DATE;}
	public static function confidentiality(){return F_AST_CONFIDENTIALITY;}
	public static function programType(){return F_AST_PROGRAM_TYPE;}
	public static function classId(){return F_AST_CLASS_ID;}
	public static function entity(){return F_AST_ENTITY;}
	public static function idStatus(){return F_AST_CLASS_STATUS_ID;}
	
	/**
	 * Preenche os valores vindos do request
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);

		$this->assistanceId		= $_request->getParam(AssistanceForm::assistanceId());
		$this->endDate			= $_request->getParam(AssistanceForm::endDate());		
		$this->confidentiality	= $_request->getParam(AssistanceForm::confidentiality());
		$this->programType		= $_request->getParam(AssistanceForm::programType());
		$this->entity			= $_request->getParam(AssistanceForm::entity());
		
		$this->classId			= $_request->getParam(AssistanceForm::classId());
		$this->idStatus			= $_request->getParam(AssistanceForm::idStatus());
	}

	/**
	 * Getters and Setters
	 */
	 public function getAssistanceId(){ return $this->assistanceId;}
	 public function getEndDate(){ return $this->endDate;}
	 public function getConfidentiality(){ return $this->confidentiality;}
	 public function getProgramType(){ return $this->programType;}
	 public function getEntity(){ return $this->entity;}
	 
	 public function getClassId(){ return $this->classId;}
	 public function getIdStatus(){ return $this->idStatus;}
	 
	 public function setProgramType($programType){ $this->programType = $programType;}
	 public function setAssistanceId($assistanceId){ $this->assistanceId = $assistanceId;}
	 public function setEntity($entity){ $this->entity = $entity;}
	 public function setEndDate($endDate){ $this->endDate = $endDate;}
	 public function setConfidentiality($confidentiality){ $this->confidentiality = $confidentiality;}
	 
	 public function setIdStatus($idStatus){ $this->idStatus = $idStatus;}
}