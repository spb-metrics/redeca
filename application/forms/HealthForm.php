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
 * Fabricio Meireles Monteiro  - W3S		   		13/03/2008	                       Create file 
 * 
 */


require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('F_PREGNANCY',				'pregnancy');
define('F_MET',						'met');
define('F_PREGNANCY_SIS',			'pregnancySis');
define('F_PREGNANCY_BEGIN',			'pregnancyBegin');
define('F_USER_DRUG',				'userDrug');
define('F_VACCINE',					'vaccine');
define('F_HEALTH_PLAN',				'healthPlan');
define('F_CHECKED_HEALTH_PLAN',		'checkedHealthPlan');
define('F_TYPE_HEALTH_ID',			'idTypeHealth');
define('F_TYPE_HEALTH_DESCR',		'descrTypeHealth');
define('F_COLL_FRAMEWORK_HEALTH',	'collFrameworkHealth');
define('F_PERSON_ID',				'person');

//a ser utilizado ? - aguardando feedback do Jordão 
define('F_UBS_ID',				'idUBS');


class HealthForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $idPerson;
	private $pregnancy;
	private $met;
	private $pregnancySis;
	private $pregnancyBegin;
	private $userDrug;
	private $vaccine;
	private $healthPlan;
	private $checkedHealthPlan;
	private $idTypeHealth;
	private $descrTypeHealth;
	private $collFrameworkHealth;
	
	//a ser utilizado ? - aguardando feedback do Jordão
	private $idUBS;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function idPerson()
	{
		return F_PERSON_ID;
	}
	
	public static function idSocialProgram()
	{
		return F_SOCIAL_PROGRAM_ID;
	}
	
	public static function pregnancy()
	{
		return F_PREGNANCY;
	}
	
	public static function met()
	{
		return F_MET;
	}
	
	public static function pregnancySis()
	{
		return F_PREGNANCY_SIS;
	}
	
	public static function pregnancyBegin()
	{
		return F_PREGNANCY_BEGIN;
	}
	
	public static function userDrug()
	{
		return F_USER_DRUG;
	}
	
	public static function vaccine()
	{
		return F_VACCINE;
	}
	
	public static function healthPlan()
	{
		return F_HEALTH_PLAN;
	}
	
	public static function checkedHealthPlan()
	{
		return F_CHECKED_HEALTH_PLAN;
	}
	
	public static function idTypeHealth()
	{
		return F_TYPE_HEALTH_ID;
	}
	
	public static function descrTypeHealth()
	{
		return F_TYPE_HEALTH_DESCR;
	}
	
	public static function idUBS()
	{
		return F_UBS_ID;
	}
	
	public static function collFrameworkHealth()
	{
		return F_COLL_FRAMEWORK_HEALTH;
	}
	
	/**
	 * Preenche os valores vindos do request
	 * "&" : pega a mesma instância da memória
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->idPerson			= $_request->getParam(HealthForm::idPerson());
		
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->idPerson			= $_request->getPost(HealthForm::idPerson());
			$this->pregnancy 		= trim($filter->filter($_request->getPost(HealthForm::pregnancy())));
			$this->met				= $_request->getPost(HealthForm::met());
			$this->pregnancySis		= trim($filter->filter($_request->getPost(HealthForm::pregnancySis())));
			$this->pregnancyBegin	= trim($filter->filter($_request->getPost(HealthForm::pregnancyBegin())));						
			$this->userDrug			= $_request->getPost(HealthForm::userDrug());
			$this->vaccine			= $_request->getPost(HealthForm::vaccine());
			$this->healthPlan 		= trim($filter->filter($_request->getPost(HealthForm::healthPlan())));
			$this->checkedHealthPlan= $_request->getPost(HealthForm::checkedHealthPlan());
			$this->idTypeHealth		= $_request->getPost(HealthForm::idTypeHealth());
			$this->idUBS			= $_request->getPost(HealthForm::idUBS());
			
			if($this->idTypeHealth != null)
			{
				foreach($this->idTypeHealth as $idHealth)
				{	
					$uniqueHealth = array();
					$uniqueHealth[FHL_ID_FRAMEWORK_HEALTH] = $idHealth; 
					
					$descriptionHealth = $_request->getPost(HealthForm::descrTypeHealth()."_".$idHealth);
					if($descriptionHealth != null)
					{
						$uniqueHealth[FHL_FRAMEWORK_HEALTH_DESCRIPTION] = $_request->getPost(HealthForm::descrTypeHealth()."_".$idHealth);	
					}
					else
					{
						$uniqueHealth[FHL_FRAMEWORK_HEALTH_DESCRIPTION] = null;
					}
					
					$objectsHealth[] = $uniqueHealth;
				}	
			}
			
			$this->collFrameworkHealth = $objectsHealth;
		}
	}
	
	/**
	 * Getters and Setters
	 */
	 public function getIdPerson()
	 {
	 	return $this->idPerson;	
	 }
	 
	 public function getPregnancy()
	 {
	 	return $this->pregnancy;	
	 }
	 
	 public function getMet()
	 {
	 	return $this->met;	
	 }
	 
	 public function getPregnancySis()
	 {
	 	return $this->pregnancySis;	
	 }
	 
	 public function getPregnancyBegin()
	 {
	 	return $this->pregnancyBegin;	
	 }
	 
	 public function getUserDrug()
	 {
	 	return $this->userDrug;	
	 }
	 
	 public function getVaccine()
	 {
	 	return $this->vaccine;	
	 }
	 
	 public function getHealthPlan()
	 {
	 	return $this->healthPlan;	
	 }
	 
	 public function getCheckedHealthPlan()
	 {
	 	return $this->checkedHealthPlan;	
	 }
	 
	 public function getIdTypeHealth()
	 {
	 	return $this->idTypeHealth;	
	 }
	 
	 public function getDescrTypeHealth()
	 {
	 	return $this->descrTypeHealth;	
	 }
	 
	 public function getIdUBS()
	 {
	 	return $this->idUBS;	
	 }
	 
	 public function getCollFrameworkHealth()
	 {
	 	return $this->collFrameworkHealth;	
	 }
	 
	 
	 
	 public function setIdPerson($idPerson)
	 {
	 	$this->idPerson = $idPerson;	
	 }
	 
	 public function setPregnancy($pregnancy)
	 {
	 	$this->pregnancy = $pregnancy;	
	 }
	 
	 public function setMet($met)
	 {
	 	$this->met = $met;	
	 }
	 
	 public function setPregnancySis($pregnancySis)
	 {
	 	$this->pregnancySis = $pregnancySis;	
	 }
	 
	 public function setPregnancyBegin($pregnancyBegin)
	 {
	 	$this->pregnancyBegin = $pregnancyBegin;	
	 }
	 
	 public function setUserDrug($userDrug)
	 {
	 	$this->userDrug = $userDrug;	
	 }
	 
	 public function setVaccine($vaccine)
	 {
	 	$this->vaccine = $vaccine;	
	 }
	 
	 public function setHealthPlan($healthPlan)
	 {
	 	$this->healthPlan = $healthPlan;	
	 }
	 
	 public function setCheckedHealthPlan($checkedHealthPlan)
	 {
	 	$this->checkedHealthPlan = $checkedHealthPlan;	
	 }
	 
	 public function setIdTypeHealth($idTypeHealth)
	 {
	 	$this->idTypeHealth = $idTypeHealth;	
	 }
	 
	 public function setDescrTypeHealth($descrTypeHealth)
	 {
	 	$this->descrTypeHealth = $descrTypeHealth;	
	 }
	 
	 public function setIdUBS($idUBS)
	 {
	 	$this->idUBS = $idUBS;	
	 }
	 
	 public function setCollFrameWorkHealth($collFrameworkHealth)
	 {
	 	$this->collFrameworkHealth = $collFrameworkHealth;	
	 }
}