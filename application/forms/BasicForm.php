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

/**
 * Nome das variáveis no template
 * 
 */
define('F_PAGE', 	'page');
define('F_FILTER', 	'filter');
define('E_CONFIRM', 'confirm');
define('F_HIST_RESOURCE_ID',	'hist_resource');
define('F_PERSON_ID', 'person');

/* Definição de variáveis para busca de CEP */
define('S_ADDRESS_TYPE', 	'_adr_address_type');
define('S_ID_ADDRESS', 		'_adr_id_address');
define('S_ADDRESS', 		'_adr_address');
define('S_ZIPCODE', 		'_adr_zipcode');
define('S_UF', 				'_adr_uf');
define('S_CITY', 			'_adr_city');
define('S_NEIGHBORHOOD', 	'_adr_neighborhood');
define('S_NUMBER', 			'_adr_number');
define('S_COMPLEMENT', 		'_adr_complement');
define('S_REFERENCE', 		'_adr_reference');

abstract class BasicForm
{
	/**
	 * Campos
	 * 
	 */
	private $page;
	private $filter;
	private $confirm;
	private $resourceId;
	private $personId;
	
	private $_adr_address_type;
	private $_adr_id_address;
	private $_adr_address;
	private $_adr_zipcode;
	private $_adr_uf;
	private $_adr_city;
	private $_adr_neighborhood;
	private $_adr_number;
	private $_adr_complement;
	private $_adr_reference;
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function page() { return F_PAGE; }
	public static function filter() { return F_FILTER; }
	public static function confirm() { return E_CONFIRM; }
	public static function resourceId(){return F_HIST_RESOURCE_ID;}
	public static function personId(){return F_PERSON_ID;}
	
	public static function adr_addressType() { return S_ADDRESS_TYPE; }
	public static function adr_idAddress() { return S_ID_ADDRESS; }
	public static function adr_address() { return S_ADDRESS; }
	public static function adr_zipcode() { return S_ZIPCODE; }
	public static function adr_uf() { return S_UF; }
	public static function adr_city() { return S_CITY; }
	public static function adr_neighborhood() { return S_NEIGHBORHOOD; }
	public static function adr_number() { return S_NUMBER; }
	public static function adr_complement() { return S_COMPLEMENT; }
	public static function adr_reference() { return S_REFERENCE; }

	/**
	 * Preenche os valores vindos do request
	 * 
	 */
	public function assembleRequest(&$_request)
	{
		if($_request->isPost())
		{
			$filter			= BasicForm::getFilterStripTags();
			$this->page 	= $_request->getPost($this->page());
			$this->filter 	= $_request->getPost($this->filter());
			$this->confirm 	= $_request->getPost($this->confirm());

			$this->_adr_address_type	= trim($filter->filter( $_request->getPost(BasicForm::adr_addressType() )));
			$this->_adr_id_address		= trim($filter->filter( $_request->getPost(BasicForm::adr_idAddress() )));
			$this->_adr_address			= trim($filter->filter( $_request->getPost(BasicForm::adr_address() )));
			$this->_adr_zipcode			= trim($filter->filter( $_request->getPost(BasicForm::adr_zipcode() )));
			$this->_adr_uf				= trim($filter->filter( $_request->getPost(BasicForm::adr_uf() )));
			$this->_adr_city 			= trim($filter->filter( $_request->getPost(BasicForm::adr_city() )));
			$this->_adr_neighborhood	= trim($filter->filter( $_request->getPost(BasicForm::adr_neighborhood() )));
			$this->_adr_number			= trim($filter->filter( $_request->getPost(BasicForm::adr_number() )));
			$this->_adr_complement		= trim($filter->filter( $_request->getPost(BasicForm::adr_complement() )));
			$this->_adr_reference		= trim($filter->filter( $_request->getPost(BasicForm::adr_reference() )));
		}
		$this->resourceId		= $_request->getParam(BasicForm::resourceId());
		$this->personId			= $_request->getParam(BasicForm::personId());
	}
	
	/**
	 * Getters
	 * 
	 */
	public function getPage(){return $this->page;}
	public function getFilter(){return $this->filter;}
	public function getConfirm(){return $this->confirm;}	
	public function getResourceId(){ return $this->resourceId;}
	public function getPersonId(){ return $this->personId;}
	
	public function getAdrAddressType()	{return $this->_adr_address_type;}
	public function getAdrIdAddress()	{return $this->_adr_id_address;}
	public function getAdrAddress()		{return $this->_adr_address;}
	public function getAdrZipcode()		{return $this->_adr_zipcode;}
	public function getAdrUf()			{return $this->_adr_uf;}
	public function getAdrCity()		{return $this->_adr_city;}
	public function getAdrNeighborhood(){return $this->_adr_neighborhood;}
	public function getAdrNumber()		{return $this->_adr_number;}
	public function getAdrComplement()	{return $this->_adr_complement;}
	public function getAdrReference()	{return $this->_adr_reference;}

	public function setPersonId($personId)	{ $this->personId = $personId;}
	public function setAdrAddressType($_adr_address_type)	{ $this->_adr_address_type = $_adr_address_type;}
	public function setAdrIdAddress($_adr_id_address)	{ $this->_adr_id_address = $_adr_id_address;}
	public function setAdrAddress($_adr_address)		{ $this->_adr_address = $_adr_address;}
	public function setAdrZipcode($_adr_zipcode)		{ $this->_adr_zipcode = $_adr_zipcode;}
	public function setAdrUf($_adr_uf)					{ $this->_adr_uf = $_adr_uf;}
	public function setAdrCity($_adr_city)				{ $this->_adr_city = $_adr_city;}
	public function setAdrNeighborhood($_adr_neighborhood){ $this->_adr_neighborhood = $_adr_neighborhood;}
	public function setAdrNumber($_adr_number)			{ $this->_adr_number = $_adr_number;}
	public function setAdrComplement($_adr_complement)	{ $this->_adr_complement = $_adr_complement;}
	public function setAdrReference($_adr_reference)	{ $this->_adr_reference = $_adr_reference;}

	//------> other methods
	
	/**
	 * 
	 * 
	 */
	function getFilterStripTags()
	{
		Zend_Loader::loadClass('Zend_Filter_StripTags');
		return new Zend_Filter_StripTags();
	}
	
	public static function dateFormat($date)
	{
		$year	= substr($date,6,4);
		$month	= substr($date,3,2);
		$day	= substr($date,0,2);
		
		$dateFormat = $year.'-'.$month.'-'.$day;
		
		return $dateFormat;
	}
	
	public static function dateFormatForm($date)
	{
		$day	= substr($date,8,2);
		$month	= substr($date,5,2);
		$year	= substr($date,0,4);
		
		$dateFormat = $day.'/'.$month.'/'.$year;
		
		return $dateFormat;
	}
}
