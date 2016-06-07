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
 * Fabricio Meireles Monteiro  - W3S		   		01/04/2008	                       Create file 
 * 
 */

require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('INC_ID_COMPANY',	 		'id_employment');
define('INC_COMPANY_NAME',	 		'company_name');
define('INC_ID_EMPLOYMENT_TYPE',	'id_emp_type');
define('INC_VALUE_SALARY',	 		'value_salary');
define('INC_ID_PHONE',	 			'id_phone');
define('INC_PHONE_TYPE',	 		'phone_type');
define('INC_PHONE_NUMBER',	 		'phone_number');
define('INC_PHONE_DDD',	 			'phone_ddd');
define('INC_OCCUPATION',	 		'occupation');
define('INC_EMPLOYMENT',	 		'employment');
define('INC_ID_SALARY',	 			'id_salary');
define('INC_START_DATE', 			'start_date');
define('INC_END_DATE', 				'end_date');

class IncomeForm extends BasicForm
{	
	
	//campos empresa
	private $idCompany;
	private $companyName;
	private $occupation;
	private $startDate;
	private $endDate;
	
	//campos pessoa
	private $idEmploymentType;
	private $valueSalary;
	
	//campos telefone da pessoa
	private $idPhone;
	private $phoneType;
	private $phoneNumber;
	private $phoneCodeArea;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function idCompany(){	return INC_ID_COMPANY;}
	public static function companyName(){return INC_COMPANY_NAME;}
	public static function idEmploymentType(){return INC_ID_EMPLOYMENT_TYPE;}
	public static function valueSalary(){return INC_VALUE_SALARY;}	
	public static function idPhone(){return INC_ID_PHONE;	}	
	public static function phoneType(){return INC_PHONE_TYPE;	}
	public static function phoneNumber(){return INC_PHONE_NUMBER;}	
	public static function phoneCodeArea(){	return INC_PHONE_DDD;}
	public static function occupation(){	return INC_OCCUPATION;}
	public static function employment(){	return INC_EMPLOYMENT;}
	public static function idSalary(){	return INC_ID_SALARY;}
	public static function startDate(){	return INC_START_DATE;}
	public static function endDate(){	return INC_END_DATE;}						
	
	/**
	 * Preenche os valores vindos do request
	 * "&" : pega a mesma instância da memória. 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
				
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->idCompany		= $_request->getPost(IncomeForm::idCompany());
			$this->companyName 		= trim($filter->filter($_request->getPost(IncomeForm::companyName())));
			$this->idEmploymentType	= $_request->getPost(IncomeForm::idEmploymentType());
			$this->idPhone  		= $_request->getPost(IncomeForm::idPhone());
			$this->phoneType	    = $_request->getPost(IncomeForm::phoneType());
			$this->phoneNumber	    = $_request->getPost(IncomeForm::phoneNumber());
			$this->phoneCodeArea    = $_request->getPost(IncomeForm::phoneCodeArea());
			$this->occupation    	= trim($filter->filter($_request->getPost(IncomeForm::occupation())));
			$this->startDate    	= trim($filter->filter($_request->getPost(IncomeForm::startDate())));
			$this->endDate    		= trim($filter->filter($_request->getPost(IncomeForm::endDate())));
			$this->valueSalary	    = $_request->getPost(IncomeForm::valueSalary());
			for($i = 1; count($this->valueSalary) >= $i; $i++)
			{
				$this->valueSalary[$i] = str_replace(',','.',$this->valueSalary[$i]);
			}
		}
	}
	
	
	 /**
	 * Getters
	 */
	 public function getIdCompany(){return $this->idCompany;}	 
	 public function getCompanyName() {	return $this->companyName; }	 
	 public function getIdEmploymentType() {return $this->idEmploymentType; }	 
	 public function getValueSalary() {	return $this->valueSalary; }	 
	 public function getIdPhone() {	return $this->idPhone; }	 
	 public function getPhoneType() {return $this->phoneType; }	 
	 public function getPhoneNumber() {	return $this->phoneNumber;	 }	 
	 public function getPhoneCodeArea() {return $this->phoneCodeArea; }
	 public function getOccupation() {return $this->occupation; }
	 public function getStartDate() {return $this->startDate; }
	 public function getEndDate() {return $this->endDate; }	 		 	 
	 
	 /**
	  * Setters
	  */	  
	 public function setIdCompany($idCompany) {	$this->idCompany = $idCompany; }	 
	 public function setCompanyName($companyName) {	$this->companyName = $companyName; }	 
	 public function setIdEmploymentType($idEmploymentType) {$this->idEmploymentType = $idEmploymentType; }	 
	 public function setValueSalary($valueSalary) {	$this->valueSalary = $valueSalary; }	 
	 public function setIdPhone($idPhone) {	$this->idPhone = $idPhone; }	 
	 public function setPhoneType($phoneType) {	$this->phoneType = $phoneType; }
	 public function setPhoneNumber($phoneNumber) {	$this->phoneNumber = $phoneNumber; }	 	 
	 public function setPhoneCodeArea($phoneCodeArea) {	$this->phoneCodeArea = $phoneCodeArea; }
	 public function setOccupation($occupation) {	$this->occupation = $occupation; }
	 public function setStartDate($startDate) {	$this->startDate = $startDate; }
	 public function setEndDate($endDate) {	$this->endDate = $endDate; }
	 
	 /**
	 * Recupera as informações do form e retorna no objeto Telefone
	 * 
	 */
	function assembleFormToTelephone(IncomeForm $frm)
	{
		$phone 		= array();
		$telephone 	= array();
		$ddd 		= $frm->getPhoneCodeArea();
		$number		= $frm->getPhoneNumber();
		$id 		= $frm->getIdPhone();
		$type 		= $frm->getPhoneType();
		
		if($ddd){
			for($i = 0; $i < sizeof($ddd); $i++){
				$phone[TNB_ID_TELEPHONE_NUMBER] = $id[$i];
				$phone[TNB_ID_TELEPHONE_TYPE] = $type[$i];
				$phone[TNB_DDD] = $ddd[$i];
				$phone[TNB_NUMBER] = $number[$i];
				$telephone[] = $phone;
			}
		}
		else
			$telephone = null;

		return $telephone;
	}
	
	function assemblePersonToForm($person)
	{
		foreach($person as $prs)
			$income = $prs->findDependentRowset(CLS_INCOME);
		
		foreach($income as $inc){
			if($inc->{ICM_STATUS} != Constants::HISTORY){
				$salaryObj[$inc->{ICM_ID_INCOME}] = trim(str_replace('.',',',$inc->{ICM_VALUE}));
				
				$employment = $inc->findDependentRowset(CLS_EMPLOYMENT);
				foreach($employment as $emp){
					
					$idEmploymentType 	= $emp->{EMP_ID_EMPLOYMENT_STATUS};
					$companyName		= $emp->{EMP_COMPANY_NAME};
					$occupation			= $emp->{EMP_OCCUPATION};
					$startDate			= self::dateFormatForm($emp->{EMP_START_DATE});
					$endDate			= self::dateFormatForm($emp->{EMP_END_DATE});
					$idCompany			= $emp->{EMP_ID_EMPLOYMENT};					
					$address 			= $emp->findParentRow(CLS_ADDRESS);
					$idAddress 			= $address->{ADR_ID_ADDRESS};
					$addressName 		= $address->{ADR_ADDRESS};
					$number 			= $emp->{EMP_NUMBER};
					$complement 		= $emp->{EMP_COMPLEMENT};
					$referencePoint 	= $emp->{EMP_REFERENCE_POINT};
					$zipcode			= $address->{ADR_ZIP_CODE};
					if(count($address) > 0)
					{				
						$adrType = $address->findParentRow(CLS_ADDRESSTYPE);
						$addressType 		= $adrType->{ADT_DESCRIPTION};					
						$nbh = $address->findParentRow(CLS_NEIGHBORHOOD);
						$neighborhood = $nbh->{NHD_NEIGHBORHOOD};					
						$cty = $nbh->findParentRow(CLS_CITY);
						$city = $cty->{CTY_CITY};					
						$state = $cty->findParentRow(CLS_UF);
						$uf = $state->{UF_ABBREVIATION};
					}
					
					$telephone = $emp->findManyToManyRowset(CLS_TELEPHONENUMBER, CLS_EMPLOYMENTPHONE);
					
					foreach($telephone as $phone){
						$ddd[] = $phone->{TNB_DDD};
						$phoneNumber[] = $phone->{TNB_NUMBER};
						$idPhone[] = $phone->{TNB_ID_TELEPHONE_NUMBER};
						$idPhoneType[] = $phone->{TNB_ID_TELEPHONE_TYPE};
					}		
				}
			}  
		}
		$salaryArray[] = $salaryObj;
		
		$this->view->form->setIdCompany($idCompany);
		$this->view->form->setValueSalary($salaryArray);
		$this->view->form->setIdEmploymentType($idEmploymentType);
		$this->view->form->setCompanyName($companyName);
		$this->view->form->setOccupation($occupation);
		$this->view->form->setStartDate($startDate);
		$this->view->form->setEndDate($endDate);
		$this->view->form->setAdrIdAddress($idAddress);
		$this->view->form->setAdrAddress($addressName);
		$this->view->form->setAdrAddressType($addressType);
		$this->view->form->setAdrComplement($complement);
		$this->view->form->setAdrReference($referencePoint);
		$this->view->form->setAdrNumber($number);
		$this->view->form->setAdrCity($city);
		$this->view->form->setAdrNeighborhood($neighborhood);
		$this->view->form->setAdrUf($uf);
		$this->view->form->setAdrZipcode($zipcode);
		$this->view->form->setIdPhone($idPhone);
		$this->view->form->setPhoneType($idPhoneType);
		$this->view->form->setPhoneCodeArea($ddd);
		$this->view->form->setPhoneNumber($phoneNumber);
	}
	
	function assembleFormToEmployment(Income $frm)
	{
		$employment[EMP_ID_EMPLOYMENT] = $frm->getIdCompany();
		$employment[EMP_ID_EMPLOYMENT_STATUS] = $frm->getIdEmploymentType();
		$employment[EMP_ID_ADDRESS] = $frm->getAdrIdAddress();
		$employment[EMP_NUMBER] = $frm->getAdrNumber();
		$employment[EMP_COMPLEMENT] = $frm->getAdrComplement();
		$employment[EMP_REFERENCE_POINT] = $frm->getAdrReference();
		$employment[EMP_OCCUPATION] = $frm->getOccupation();
		$employment[EMP_COMPANY_NAME] = $frm->getCompanyName();
		$employment[EMP_START_DATE] = self::dateFormat($frm->getStartDate());
		$employment[EMP_END_DATE] = self::dateFormat($frm->getEndDate());
		
		return $employment;
	}
}