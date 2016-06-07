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
define('R_ID_RESIDENCE','id_residence');
define('R_ID_ADDRESS','id_address');
define('R_NUMBER','number');
define('R_COMPLEMENT','complement');
define('R_REFERENCE','reference');
define('R_NEIGHBORHOOD','neighborhood');
define('R_DDD','ddd');
define('R_ID_PHONE','id_phone');
define('R_PHONE','phone');
define('R_PHONE_TYPE','phone_type');
define('R_LOCALITY','locality');
define('R_MORADA','morada');
define('R_STATUS','status');
define('R_BUILDING','building');
define('R_SUPPLY','supply');
define('R_WATER','water');
define('R_ILLUMINATION','illumination');
define('R_SANITARY','sanitary');
define('R_TRASH','trash');
define('R_UBS','ubs');

class ResidenceForm extends BasicForm
{
	/**
	 * Campos
	 * 
	 */
	private $id;
	private $idResidence;
	private $idAddress;
	private $number;
	private $complement;
	private $reference;
	private $neighborhood;
	private $ddd;
	private $idPhone;
	private $phone;
	private $phoneType;
	private $locality;
	private $morada;
	private $status;
	private $building;
	private $supply;
	private $water;
	private $illumination;
	private $sanitary;
	private $trash;
	private $ubs;
	
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function id() { return F_PERSON_ID; }
	public static function idResidence() { return R_ID_RESIDENCE; }
	public static function idAddress() { return R_ID_ADDRESS; }
	public static function number() { return R_NUMBER; }
	public static function complement() { return R_COMPLEMENT; }
	public static function reference() { return R_REFERENCE; }
	public static function neighborhood() { return R_NEIGHBORHOOD; }
	public static function ddd() { return R_DDD; }
	public static function idPhone() { return R_ID_PHONE; }
	public static function phone() { return R_PHONE; }
	public static function phoneType() { return R_PHONE_TYPE; }
	public static function locality() { return R_LOCALITY; }
	public static function morada() { return R_MORADA; }
	public static function status() { return R_STATUS; }
	public static function building() { return R_BUILDING; }
	public static function supply() { return R_SUPPLY; }
	public static function water() { return R_WATER; }
	public static function illumination() { return R_ILLUMINATION; }
	public static function sanitary() { return R_SANITARY; }
	public static function trash() { return R_TRASH; }
	public static function ubs() { return R_UBS; }
	
	
	/**
	 * Preenche os valores vindos do request
	 * 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->id 			= $_request->getParam(self::id());
		$this->idResidence 	= $_request->getParam(self::idResidence());
		$this->idAddress 	= $_request->getParam(self::idAddress());
		$this->number 		= $_request->getParam(self::number());
		$this->complement 	= $_request->getParam(self::complement());
		$this->reference 	= $_request->getParam(self::reference());
		$this->neighborhood = $_request->getParam(self::neighborhood());
		$this->ddd 			= $_request->getParam(self::ddd());
		$this->idPhone 		= $_request->getParam(self::idPhone());
		$this->phone 		= $_request->getParam(self::phone());
		$this->phoneType	= $_request->getParam(self::phoneType());
		$this->locality 	= $_request->getParam(self::locality());
		$this->morada 		= $_request->getParam(self::morada());
		$this->status 		= $_request->getParam(self::status());
		$this->building 	= $_request->getParam(self::building());
		$this->supply 		= $_request->getParam(self::supply());
		$this->water 		= $_request->getParam(self::water());
		$this->illumination = $_request->getParam(self::illumination());
		$this->sanitary 	= $_request->getParam(self::sanitary());
		$this->trash 		= $_request->getParam(self::trash());
		$this->ubs	 		= $_request->getParam(self::ubs());
		
		if($_request->isPost())
		{
			$filter				= BasicForm::getFilterStripTags();
			$this->id 			= $_request->getPost(self::id());
			$this->idResidence 	= $_request->getPost(self::idResidence());
			$this->idAddress 	= trim($filter->filter($_request->getPost(self::idAddress())));
			$this->number 		= trim($filter->filter($_request->getPost(self::number())));
			$this->complement 	= trim($filter->filter($_request->getPost(self::complement())));
			$this->reference 	= trim($filter->filter($_request->getPost(self::reference())));
			$this->neighborhood	= trim($filter->filter($_request->getPost(self::neighborhood())));
			$this->ddd	 		= $_request->getPost(self::ddd());
			$this->idPhone 		= $_request->getPost(self::idPhone());
			$this->phone 		= $_request->getPost(self::phone());
			$this->phoneType 	= $_request->getPost(self::phoneType());
			$this->locality		= trim($filter->filter($_request->getPost(self::locality())));
			$this->morada 		= trim($filter->filter($_request->getPost(self::morada())));
			$this->status 		= trim($filter->filter($_request->getPost(self::status())));
			$this->building		= trim($filter->filter($_request->getPost(self::building())));
			$this->supply 		= trim($filter->filter($_request->getPost(self::supply())));
			$this->water 		= trim($filter->filter($_request->getPost(self::water())));
			$this->illumination	= trim($filter->filter($_request->getPost(self::illumination())));
			$this->sanitary		= trim($filter->filter($_request->getPost(self::sanitary())));
			$this->trash		= trim($filter->filter($_request->getPost(self::trash())));
			$this->ubs			= trim($filter->filter($_request->getPost(self::ubs())));
		}
	}
	
	/**
	 * Recupera as informações do form e retorna no objeto Residence
	 * 
	 */
	function assembleFormToResidence(ResidenceForm $frm)
	{
		if($frm->getAdrIdAddress())
			$frm->setIdAddress($frm->getAdrIdAddress());
		else
			$frm->setIdAddress(null);
			
		$frm->setComplement($frm->getAdrComplement());
		$frm->setNumber($frm->getAdrNumber());
		$frm->setReference($frm->getAdrReference());
		
		$residence							= array();
		if($frm->getIdResidence())
			$residence[RES_ID_RESIDENCE]		= $frm->getIdResidence();		
		else
			$residence[RES_ID_RESIDENCE] 		= null;
		
		if($frm->getIdAddress())
			$residence[RES_ID_ADDRESS]			= $frm->getIdAddress();
		else
			$residence[RES_ID_ADDRESS] 		= null;
		
		if($frm->getComplement())
			$residence[RES_COMPLEMENT] 			= $frm->getComplement();
		else
			$residence[RES_COMPLEMENT] 		= null;
		
		if($frm->getNumber())
			$residence[RES_NUMBER] 				= $frm->getNumber();
		else
			$residence[RES_NUMBER] 		= null;
		
		if($frm->getReference())
			$residence[RES_REFERENCE_POINT] 	= $frm->getReference();
		else
			$residence[RES_REFERENCE_POINT] 		= null;
		
		if($frm->getLocality())
			$residence[RES_ID_LOCALITY] 		= $frm->getLocality();
		else
			$residence[RES_ID_LOCALITY] 		= null;
		
		if($frm->getMorada())
			$residence[RES_ID_MORADA] 			= $frm->getMorada();
		else
			$residence[RES_ID_MORADA] 		= null;
		
		if($frm->getStatus())
			$residence[RES_ID_STATUS] 			= $frm->getStatus();
		else
			$residence[RES_ID_STATUS] 		= null;
		
		if($frm->getBuilding())
			$residence[RES_ID_BUILDING] 		= $frm->getBuilding();
		else
			$residence[RES_ID_BUILDING] 		= null;
			
		if($frm->getSupply())
			$residence[RES_ID_SUPPLY] 			= $frm->getSupply();
		else
			$residence[RES_ID_SUPPLY] 		= null;
				
		if($frm->getWater())
			$residence[RES_ID_WATER] 		= $frm->getWater();
		else
			$residence[RES_ID_WATER] 		= null;
		
		if($frm->getIllumination())
			$residence[RES_ID_ILLUMINATION] 	= $frm->getIllumination();
		else
			$residence[RES_ID_ILLUMINATION] 		= null;
			
		if($frm->getSanitary())
			$residence[RES_ID_SANITARY] 		= $frm->getSanitary();
		else
			$residence[RES_ID_SANITARY] 		= null;
		
		if($frm->getTrash())
			$residence[RES_ID_TRASH] 			= $frm->getTrash();
		else
			$residence[RES_ID_TRASH] 		= null;
			
		return $residence;
	}
	
	/**
	 * Recupera as informações do form e retorna no objeto Telefone
	 * 
	 */
	function assembleFormToTelephone(ResidenceForm $frm)
	{
		$phone 		= array();
		$telephone 	= array();
		$ddd 		= $frm->getDdd();
		$number		= $frm->getPhone();
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
	
	/**
	 * Recupera as informações do objeto e insere no form
	 * 
	 */
	function assembleResidenceToForm($residence)
	{
		if(!empty($residence))
		{
			$this->setIdResidence($residence->{RES_ID_RESIDENCE});
			$this->setIdAddress($residence->{RES_ID_ADDRESS});
			$this->setComplement($residence->{RES_COMPLEMENT});
			$this->setReference($residence->{RES_REFERENCE_POINT});
			$this->setNumber($residence->{RES_NUMBER});
			$this->setLocality($residence->{RES_ID_LOCALITY});
			$this->setMorada($residence->{RES_ID_MORADA});
			$this->setStatus($residence->{RES_ID_STATUS});
			$this->setBuilding($residence->{RES_ID_BUILDING});
			$this->setSupply($residence->{RES_ID_SUPPLY});
			$this->setWater($residence->{RES_ID_WATER});
			$this->setIllumination($residence->{RES_ID_ILLUMINATION});
			$this->setSanitary($residence->{RES_ID_SANITARY});
			$this->setTrash($residence->{RES_ID_TRASH});
		}
	}
	
	/**
	 * Getters and Setters
	 * 
	 */
	 public function getId(){return $this->id;}
	 public function getIdResidence(){return $this->idResidence;}
	 public function getIdAddress(){return $this->idAddress;}
	 public function getComplement(){return $this->complement;}
	 public function getNumber(){return $this->number;}
	 public function getReference(){return $this->reference;}
	 public function getLocality(){return $this->locality;}
	 public function getMorada(){return $this->morada;}
	 public function getStatus(){return $this->status;}
	 public function getBuilding(){return $this->building;}
	 public function getSupply(){return $this->supply;}
	 public function getWater(){return $this->water;}
	 public function getIllumination(){return $this->illumination;}
	 public function getSanitary(){return $this->sanitary;}
	 public function getTrash(){return $this->trash;}
	 public function getUbs(){return $this->ubs;}
	 public function getIdPhone(){return $this->idPhone;}
	 public function getPhone(){return $this->phone;}
	 public function getPhoneType(){return $this->phoneType;}
	 public function getDdd(){return $this->ddd;}
	 public function getNeighborhood(){return $this->neighborhood;}
	 
	 public function setId($id){$this->id = $id;}
	 public function setIdResidence($idResidence){$this->idResidence = $idResidence;}
	 public function setIdAddress($idAddress){$this->idAddress = $idAddress;}
	 public function setComplement($complement){$this->complement = $complement;}
	 public function setNumber($number){$this->number = $number;}
	 public function setReference($reference){$this->reference = $reference;}
	 public function setLocality($locality){$this->locality = $locality;}
	 public function setMorada($morada){$this->morada = $morada;}
	 public function setStatus($status){$this->status = $status;}
	 public function setBuilding($building){$this->building = $building;}
	 public function setSupply($supply){$this->supply = $supply;}
	 public function setWater($water){$this->water = $water;}
	 public function setIllumination($illumination){$this->illumination = $illumination;}
	 public function setSanitary($sanitary){$this->sanitary = $sanitary;}
	 public function setTrash($trash){$this->trash = $trash;}
	 public function setUbs($ubs){$this->ubs = $ubs;}
	 public function setIdPhone($idPhone){$this->idPhone = $idPhone;}
	 public function setPhone($phone){$this->phone = $phone;}
	 public function setPhoneType($phoneType){$this->phoneType = $phoneType;}
	 public function setDdd($ddd){$this->ddd = $ddd;}
	 public function setNeighborhood($neighborhood){$this->neighborhood = $neighborhood;}
}