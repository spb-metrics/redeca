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
 * Fabricio Meireles Monteiro  - W3S		   		29/02/2008	                       Create file 
 * 
 */

require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('F_ID_ACTIVITY_CLASS',	'idActivityClass');
define('F_PROGRAM_ID',	 		'idProgram');
define('F_ID_CLASS_STATUS',		'idStatus');
define('F_ENTITY_ID',	 		'idEntity');
define('F_CLASS_ID',	 		'idClass');
define('F_ACTIVITY_DETAIL_ID', 	'idActivityDetail');
define('F_CLASS_NAME',			'className');
define('F_CLASS_VACANCY',		'vacancy');
define('F_CLASS_PERIOD',		'period');
define('F_CLASS_TIME',			'timeClass');
define('F_FLAG_MIGRATE',		'flagMigrate');
define('F_ID_NEW_CLASS',		'idNewClass');
define('F_ID_NEW_CLASS',		'idNewClass');
define('F_END_DATE',			'endDate');
define('F_CONFIDENTIALITY',		'confidentiality');
define('F_EXIST_ACT_DETAIL',	'existActDetail');

class ClassForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $idProgram;
	private $idEntity;
	private $idStatus;
	private $idClass;
	private $idActivityDetail;
	private $className;
	private $vacancy;
	private $period;
	private $timeClass;
	private $idActivityClass;
	private $flagMigrate;
	private $idNewClass;
	private $endDate;
	private $confidentiality;
	private $existActDetail;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function existActDetail()
	{
		return F_EXIST_ACT_DETAIL;
	}
	
	public static function confidentiality()
	{
		return F_CONFIDENTIALITY;
	}
	
	public static function endDate()
	{
		return F_END_DATE;
	}
	
	public static function idNewClass()
	{
		return F_ID_NEW_CLASS;
	}
	
	public static function flagMigrate()
	{
		return F_FLAG_MIGRATE;
	}
	
	public static function idClass()
	{
		return F_CLASS_ID;
	}
	
	public static function idStatus()
	{
		return F_ID_CLASS_STATUS;
	}
	
	public static function idEntity()
	{
		return F_ENTITY_ID;
	}
	
	public static function idProgram()
	{
		return F_PROGRAM_ID;
	} 
	
	public static function idActivityDetail() 
	{
		 return F_ACTIVITY_DETAIL_ID;
	}
	
	public static function className()
	{
		return F_CLASS_NAME;
	}
	
	public static function vacancy()
	{
		return F_CLASS_VACANCY;
	}
	
	public static function period()
	{
		return F_CLASS_PERIOD;
	}
	
	public static function timeClass()
	{
		return F_CLASS_TIME;
	}
	
	public static function idActivityClass()
	{
		return F_ID_ACTIVITY_CLASS;
	}
	
	/**
	 * Preenche os valores vindos do request
	 * "&" : pega a mesma instância da memória. 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->idEntity	= $_request->getParam(ClassForm::idEntity());
		$this->idClass	= $_request->getParam(ClassForm::idClass());
		$this->idStatus	= $_request->getParam(ClassForm::idStatus());
		$this->idActivityDetail	= $_request->getParam(ClassForm::idActivityDetail());
		$this->idActivityClass	= $_request->getParam(ClassForm::idActivityClass());
		
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->idProgram		= $_request->getPost(ClassForm::idProgram());
			$this->className 		= trim($filter->filter($_request->getPost(ClassForm::className())));
			$this->vacancy	 		= trim($filter->filter($_request->getPost(ClassForm::vacancy())));
			$this->period	 		= trim($filter->filter($_request->getPost(ClassForm::period())));
			$this->timeClass		= trim($filter->filter($_request->getPost(ClassForm::timeClass())));
			$this->flagMigrate 		= trim($filter->filter($_request->getPost(ClassForm::flagMigrate())));
			$this->endDate 			= trim($filter->filter($_request->getPost(ClassForm::endDate())));
			$this->idNewClass		= $_request->getPost(ClassForm::idNewClass());
			$this->confidentiality	= $_request->getPost(ClassForm::confidentiality());
			$this->existActDetail	= $_request->getPost(ClassForm::existActDetail());
		}
	}
	
	/**
	 * Getters and Setters
	 */
	 public function getExistActDetail()
	 {
	 	return $this->existActDetail;	
	 }
	 
	 public function getConfidentiality()
	 {
	 	return $this->confidentiality;	
	 }
	 
	 public function getEndDate()
	 {
	 	return $this->endDate;	
	 }
	 
	 public function getIdNewClass()
	 {
	 	return $this->idNewClass;	
	 }
	 
	 public function getIdClass()
	 {
	 	return $this->idClass;	
	 }
	 
	 public function getIdStatus()
	 {
	 	return $this->idStatus;	
	 }
	 
	 public function getIdEntity()
	 {
	 	return $this->idEntity;	
	 }
	 
	 public function getIdProgram()
	 {
	 	return $this->idProgram;	
	 }
	 
	 public function getIdActivityDetail()
	 {
	 	return $this->idActivityDetail;
	 }
	 
	 public function getClassName()
	 {
	 	return $this->className;
	 }
	 
	 public function getVacancy()
	 {
	 	return $this->vacancy;
	 }
	 
	 public function getPeriod()
	 {
	 	return $this->period;
	 }
	 
	 public function getTimeClass()
	 {
	 	return $this->timeClass;
	 }
	 
	 public function getIdActivityClass()
	 {
	 	return $this->idActivityClass;
	 }
	
	 public function getFlagMigrate()
	 {
	 	return $this->flagMigrate;
	 }
	 
	 
	 public function setExistActDetail($existActDetail)
	 {
	 	$this->existActDetail = $existActDetail;	
	 }
	 
	 public function setConfidentiality($confidentiality)
	 {
	 	$this->confidentiality = $confidentiality;	
	 }
	 
	 public function setEndDate($endDate)
	 {
	 	$this->endDate = $endDate;	
	 }
	 
	 public function setIdNewClass($idNewClass)
	 {
	 	$this->idNewClass = $idNewClass;	
	 }
	
	 public function setIdClass($idClass)
	 {
	 	$this->idClass = $idClass;	
	 }
	 
	 public function setIdStatus($idStatus)
	 {
	 	$this->idStatus = $idStatus;	
	 }
	 
	 public function setIdEntity($idEntity)
	 {
	 	$this->idEntity = $idEntity;	
	 }
	 
	 public function setIdProgram($idProgram)
	 {
	 	$this->idProgram = $idProgram;	
	 }
	 
	 public function setIdActivityDetail($idActivityDetail)
	 {
	 	$this->idActivityDetail = $idActivityDetail;	
	 }
	 
	 public function setClassName($className)
	 {
	 	$this->className = $className;	
	 }
	 
	 public function setVacancy($vacancy)
	 {
	 	$this->vacancy = $vacancy;	
	 }
	 
	 public function setPeriod($period)
	 {
	 	$this->period = $period;	
	 }
	 
	 public function setTime($time)
	 {
	 	$this->time = $time;
	 }
	 
	 public function setIdActivityClass($idActivityClass)
	 {
	 	$this->idActivityClass = $idActivityClass;
	 }
	 
	 public function setFlagMigrate($flagMigrate)
	 {
	 	$this->flagMigrate = $flagMigrate;
	 }
}