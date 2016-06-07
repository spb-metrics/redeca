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
define('F_SCHOOL_TYPE_ID',		'idSchoolType');
define('F_DEGREE_TYPE_ID',		'idDegreeType');
define('F_YEAR_SCHOOL_TYPE_ID',	'idYearSchoolType');
define('F_PERIOD_TYPE_ID',		'idPeriodType');
define('F_MONTH',				'month');
define('F_YEAR',				'year');
define('F_COD_INEP',			'codINEP');
define('F_NAME_SCHOOL',			'nameSchool');
define('F_ID_SCHOOLL',			'idSchool');

class EducationForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $idSchoolType;
	private $idDegreeType;
	private $idYearSchoolType;
	private $idPeriodType;
	private $month;
	private $year;
	private $codINEP;
	private $nameSchool;
	private $idPerson;
	private $idSchool;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function idSchoolType()
	{
		return F_SCHOOL_TYPE_ID;
	} 
	
	public static function idDegreeType()
	{
		return F_DEGREE_TYPE_ID;
	}
	
	public static function idYearSchoolType()
	{
		return F_YEAR_SCHOOL_TYPE_ID;
	}
	
	public static function idPeriodType()
	{
		return F_PERIOD_TYPE_ID;
	}
	
	public static function month()
	{
		return F_MONTH;
	}	
	
	public static function year()
	{
		return F_YEAR;
	}
	
	public static function codINEP()
	{
		return F_COD_INEP;
	}
	
	public static function nameSchool()
	{
		return F_NAME_SCHOLL;
	}
	
	public static function idPerson()
	{
		return F_PERSON_ID;
	}
	
	public static function idSchool()
	{
		return F_SCHOOL_ID;
	}
	
	
	/**
	 * Preenche os valores vindos do request
	 * "&" : pega a mesma instância da memória. 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->idPerson			= $_request->getParam(EducationForm::idPerson());
		
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->idSchoolType		= $_request->getPost(EducationForm::idSchoolType());
			$this->idDegreeType		= $_request->getPost(EducationForm::idDegreeType());
			$this->idYearSchoolType	= $_request->getPost(EducationForm::idYearSchoolType());
			$this->idPeriodType		= $_request->getPost(EducationForm::idPeriodType());
			$this->month	 		= trim($filter->filter($_request->getPost(EducationForm::month())));
			$this->year		 		= trim($filter->filter($_request->getPost(EducationForm::year())));
			$this->codINEP	 		= trim($filter->filter($_request->getPost(EducationForm::codINEP())));
			$this->nameSchool 		= trim($filter->filter($_request->getPost(EducationForm::nameSchool())));
			$this->idPerson			= $_request->getPost(EducationForm::idPerson());
			$this->idSchool			= $_request->getPost(EducationForm::idSchool());
		}
	}
	
	/**
	 * Getters and Setters
	 */
	 public function getIdSchoolType()
	 {
	 	return $this->idSchoolType;	
	 }
	 
	 public function getIdDegreeType()
	 {
	 	return $this->idDegreeType;	
	 }
	 
	 public function getIdYearSchoolType()
	 {
	 	return $this->idYearSchoolType;	
	 }
	 
	 public function getIdPeriodType()
	 {
	 	return $this->idPeriodType;	
	 }
	 
	 public function getMonth()
	 {
	 	return $this->month;	
	 }
	 
	 public function getYear()
	 {
	 	return $this->year;	
	 }
	 
	 public function getCodINEP()
	 {
	 	return $this->codINEP;	
	 }
	 
	 public function getNameSchool()
	 {
	 	return $this->nameSchool;	
	 } 
	 
	 public function getIdPerson()
	 {
	 	return $this->idPerson;	
	 }
	 
	 public function getIdSchool()
	 {
	 	return $this->idSchool;	
	 }
	 
	 
	 
	 public function setIdSchoolType($idSchoolType)
	 {
	 	$this->idSchoolType = $idSchoolType;	
	 }
	 
	 public function setIdDegreeType($idDegreeType)
	 {
	 	$this->idDegreeType = $idDegreeType;	
	 }
	 
	 public function setIdYearSchoolType($idYearSchoolType)
	 {
	 	$this->idYearSchoolType = $idYearSchoolType;	
	 }
	 
	 public function setIdPeriodType($idPeriodType)
	 {
	 	$this->idPeriodType = $idPeriodType;	
	 }
	 
	 public function setMonth($month)
	 {
	 	$this->month = $month;	
	 }
	 
	 public function setYear($year)
	 {
	 	$this->year = $year;	
	 }
	 
	 public function setCodINEP($codINEP)
	 {
	 	$this->codINEP = $codINEP;	
	 }
	 
	 public function setnameSchool($nameSchool)
	 {
	 	$this->nameSchool = $nameSchool;	
	 }
	 
	 public function setIdPerson($idPerson)
	 {
	 	$this->idPerson = $idPerson;	
	 }
	 
	 public function setIdSchool($idSchool)
	 {
	 	$this->idSchool = $idSchool;	
	 }
}
