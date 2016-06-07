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
define('F_SOCIAL_PROGRAM_TYPE_ID',	'idSocialProgramType');
define('F_DATE_BENEFIT',			'dateBenefit');
define('F_COLL_BENEFIT',			'collBenefit');
define('F_PROGRAM_ID',				'idProgram');

class BenefitForm extends BasicForm
{
	/**
	 * Campos
	 */
	private $idSocialProgram;
	private $idPerson;
	private $dateBenefit;
	private $collBenefit;
	private $idProgram;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function idSocialProgramType()
	{
		return F_SOCIAL_PROGRAM_TYPE_ID;
	}
	
	public static function idProgram()
	{
		return F_PROGRAM_ID;
	}
	
	public static function idPerson()
	{
		return F_PERSON_ID;
	}
	
	public static function dateBenefit()
	{
		return F_DATE_BENEFIT;
	}
	
	public static function collBenefit()
	{
		return F_COLL_BENEFIT;
	}
	
	/**
	 * Preenche os valores vindos do request
	 * "&" : pega a mesma instância da memória. 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);	
		
		$this->idPerson				= $_request->getParam(BenefitForm::idPerson());
		$this->idSocialProgramType	= $_request->getParam(BenefitForm::idSocialProgramType());
		$this->idProgram			= $_request->getParam(BenefitForm::idProgram());
		
		if($_request->isPost())
		{
			$filter						= BasicForm::getFilterStripTags();
			$this->dateBenefit			= $_request->getPost(BenefitForm::dateBenefit());
						
			if($this->idSocialProgramType)
			{	
				foreach($this->idSocialProgramType as $idProgramSocial)
				{
					$uniqueBenefit = array();
					$uniqueBenefit[SPG_ID_SOCIAL_PROGRAM] = $idProgramSocial; 
					
					$sinceDate = $_request->getPost(BenefitForm::dateBenefit()."_".$idProgramSocial);
					if($sinceDate != null)
					{
						//formata a data inserida pelo usuário
						$formatedDate = self::dateFormat($sinceDate);
						$uniqueBenefit[SPG_REGISTER_DATE.$idProgramSocial] = $formatedDate;
						
						//utilizado para carregar data no business
						$uniqueBenefit[SPG_REGISTER_DATE] = $sinceDate;	
					}
					
					$objectsBenefit[$idProgramSocial] = $uniqueBenefit;
				}	
			}
			
			$this->collBenefit = $objectsBenefit;
		}		
	}
	
	/**
	 * Getters and Setters
	 */
	 public function getIdProgram()
	 {
	 	return $this->idProgram;	
	 }
	 
	 public function getIdSocialProgramType()
	 {
	 	return $this->idSocialProgramType;	
	 }
	 
	 public function getIdPerson()
	 {
	 	return $this->idPerson;	
	 }
	 
	 public function getDateBenefit()
	 {
	 	return $this->dateBenefit;	
	 }
	 
	 public function getCollBenefit()
	 {
	 	return $this->collBenefit;	
	 }
	 
	 
	 public function setIdProgram($idProgram)
	 {
	 	$this->idProgram = $idProgram;	
	 }
	 
	 public function setIdSocialProgramType($idSocialProgramType)
	 {
	 	$this->idSocialProgramType = $idSocialProgramType;	
	 }
	 
	 public function setIdPerson($idPerson)
	 {
	 	$this->idPerson = $idPerson;	
	 }
	 
	 public function setDateBenefit($dateBenefit)
	 {
	 	$this->dateBenefit = $dateBenefit;	
	 }
	 
	 public function setCollBenefit($collBenefit)
	 {
	 	$this->collBenefit = $collBenefit;	
	 }
	 
//	 public static function dateFormat($date)
//	 {
//		$year	= substr($date,-4);
//		$month	= substr($date,3,-5);
//		$day	= substr($date,0,-8);
//		
//		$dateFormat = $year.'-'.$month.'-'.$day;
//		
//		return $dateFormat;
//	 }
//	
//	 public static function dateFormatForm($date)
//	 {
//		$day	= substr($date,-2);
//		$month	= substr($date,5,-3);
//		$year	= substr($date,0,-6);
//		
//		$dateFormat = $day.'/'.$month.'/'.$year;
//		
//		return $dateFormat;
//	 }
}