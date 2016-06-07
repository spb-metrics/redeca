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

require_once('BasicForm.php');

/*********************************************************/
/**      Nome das variáveis usadas em várias tabelas    **/
/*********************************************************/
define('MAN', 'm');
define('WOMAN', 'f');

/*********************************************************/
/**      Nome das variáveis da tabela per_person        **/
/*********************************************************/
define('F_NICKNAME', 						'nickname');
define('F_META_NICKNAME',					'metaNickname');
define('F_NAME', 							'name');
define('F_META_NAME',						'metaName');
define('F_SEX', 							'sex');
define('F_RACE', 							'race');
define('F_TATTOO', 							'tattoo');
define('F_MARITAL_STATUS', 					'maritalStatus');
define('F_BIRTH_DT', 						'birthDate');
define('F_DEATH_DT', 						'deathDate');
define('F_NATIONALITY', 					'nationality');
define('F_NATIVE_COUNTRY',					'nativeCountry');
define('F_ARRIVAL_DT', 						'arrivalDate');

/*********************************************************/
/**     Nome das variáveis da tabela per_deficiency     **/
/*********************************************************/
define('F_DEFICIENCY', 						'deficiency');

/*********************************************************/
/**      Nome das variáveis da tabela per_document      **/
/*********************************************************/
define('F_CPF', 							'cpf');
define('F_RA', 								'ra');
define('F_RG', 								'rg');
define('F_RG_COMPLEMENT',					'rgComplement');
define('F_RG_EMISSION_DT',					'rgEmissionDate');
define('F_RG_SENDER', 						'rgSender');
define('F_RG_STATE',						'rgState');
define('F_NIS_NUMBER',						'nisNumber');
define('F_SUS_NUMBER',						'susNumber');
define('F_TITLE_VOTER',						'titleVoter');
define('F_TITLE_ZONE', 						'titleZone');
define('F_TITLE_SESSION',					'titleSession');

/*********************************************************/
/**        Nome das variáveis da tabela per_ctps        **/
/*********************************************************/
define('F_CTPS_NUMBER',						'ctpsNumber');
define('F_CTPS_SERIES',						'ctpsSeries');
define('F_CTPS_EMISSION_DATE',				'ctpsEmissionDate');
define('F_CTPS_STATE',						'ctpsState');

/*********************************************************/
/**   Nome das variáveis da tabela per_civil_certicate  **/
/*********************************************************/
define('F_CIVIL_CERTIFICATE_TYPE',			'civilCertificateType');
define('F_CIVIL_CERTIFICATE_TERM',			'civilCertificateTerm');
define('F_CIVIL_CERTIFICATE_BOOK',			'civilCertificateBook');
define('F_CIVIL_CERTIFICATE_LEAF',			'civilCertificateLeaf');
define('F_CIVIL_CERTIFICATE_EMISSION_DATE',	'civilCertificateEmissionDate');
define('F_CIVIL_CERTIFICATE_OFFICE_NAME',	'civilCertificateOfficeName');
define('F_CIVIL_CERTIFICATE_STATE',			'civilCertificateState');

/************************************************************/
/**   Nome das variáveis da tabela per_person_address_temp **/
/************************************************************/
define('F_LIVE_SINCE',						'liveSince');

class PersonForm extends BasicForm
{
	/**
	 * Campos
	 * 
	 */
	private $id;
	private $state;
	private $nickname;
	private $metaNickname;
	private $name;
	private $metaName;
	private $sex;
	private $race;
	private $tattoo;
	private $maritalStatus;
	private $birthDate;
	private $deathDate;
	private $nationality;
	private $nativeCountry;
	private $arrivalDate;
	private $deficiency;
	private $cpf;
	private $ra;
	private $rg;
	private $rgComplement;
	private $rgEmissionDate;
	private $rgSender;
	private $rgState;
	private $nisNumber;
	private $susNumber;
	private $titleVoter;
	private $titleZone;
	private $titleSession;
	private $ctpsNumber;
	private $ctpsSeries;
	private $ctpsEmissionDate;
	private $ctpsState;
	private $civilCertificateType;
	private $civilCertificateTerm;
	private $civilCertificateBook;
	private $civilCertificateLeaf;
	private $civilCertificateEmissionDate;
	private $civilCertificateOfficeName;
	private $civilCertificateState;
	private $liveSince;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function id() 							{ return F_PERSON_ID; }
	public static function state() 							{ return F_STATE; }
	public static function nickname() 						{ return F_NICKNAME; }
	public static function metaNickname() 					{ return F_META_NICKNAME; }
	public static function name() 							{ return F_NAME; }
	public static function metaName() 						{ return F_META_NAME; }
	public static function sex() 							{ return F_SEX; }
	public static function race() 							{ return F_RACE; }
	public static function tattoo() 						{ return F_TATTOO; }
	public static function maritalStatus() 					{ return F_MARITAL_STATUS; }
	public static function birthDate() 						{ return F_BIRTH_DT; }
	public static function deathDate() 						{ return F_DEATH_DT; }
	public static function nationality() 					{ return F_NATIONALITY; }
	public static function nativeCountry()					{ return F_NATIVE_COUNTRY; }
	public static function arrivalDate() 					{ return F_ARRIVAL_DT; }
	public static function deficiency() 					{ return F_DEFICIENCY; }
	public static function cpf() 							{ return F_CPF; }
	public static function ra() 							{ return F_RA; }
	public static function rg() 							{ return F_RG; }
	public static function rgComplement() 					{ return F_RG_COMPLEMENT; }
	public static function rgEmissionDate() 				{ return F_RG_EMISSION_DT; }
	public static function rgSender() 						{ return F_RG_SENDER; }
	public static function rgState() 						{ return F_RG_STATE; }
	public static function nisNumber() 						{ return F_NIS_NUMBER; }
	public static function susNumber() 						{ return F_SUS_NUMBER; }
	public static function titleVoter() 					{ return F_TITLE_VOTER; }
	public static function titleZone() 						{ return F_TITLE_ZONE; }
	public static function titleSession() 					{ return F_TITLE_SESSION; }
	public static function ctpsNumber() 					{ return F_CTPS_NUMBER; }
	public static function ctpsSeries() 					{ return F_CTPS_SERIES; }
	public static function ctpsEmissionDate() 				{ return F_CTPS_EMISSION_DATE; }
	public static function ctpsState() 						{ return F_CTPS_STATE; }
	public static function civilCertificateType() 			{ return F_CIVIL_CERTIFICATE_TYPE; }
	public static function civilCertificateTerm() 			{ return F_CIVIL_CERTIFICATE_TERM; }
	public static function civilCertificateBook() 			{ return F_CIVIL_CERTIFICATE_BOOK; }
	public static function civilCertificateLeaf() 			{ return F_CIVIL_CERTIFICATE_LEAF; }
	public static function civilCertificateEmissionDate() 	{ return F_CIVIL_CERTIFICATE_EMISSION_DATE; }
	public static function civilCertificateOfficeName() 	{ return F_CIVIL_CERTIFICATE_OFFICE_NAME; }
	public static function civilCertificateState()			{ return F_CIVIL_CERTIFICATE_STATE; }
	public static function liveSince()						{ return F_LIVE_SINCE; }
		
	/**
	 * Preenche os valores vindos do request
	 * 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->id = $_request->getParam(PersonForm::id());
		$this->state = $_request->getParam(PersonForm::state());
		$this->nickname = $_request->getParam(PersonForm::nickname());
		$this->metaNickname = $_request->getParam(PersonForm::metaNickname());
		$this->name = $_request->getParam(PersonForm::name());
		$this->metaName = $_request->getParam(PersonForm::metaName());
		$this->sex = $_request->getParam(PersonForm::sex());
		$this->race = $_request->getParam(PersonForm::race());
		$this->tattoo = $_request->getParam(PersonForm::tattoo());
		$this->maritalStatus = $_request->getParam(PersonForm::maritalStatus());
		$this->birthDate = $_request->getParam(PersonForm::birthDate());
		$this->deathDate = $_request->getParam(PersonForm::deathDate());
		$this->nationality = $_request->getParam(PersonForm::nationality());
		$this->nativeCountry = $_request->getParam(PersonForm::nativeCountry());
		$this->arrivalDate = $_request->getParam(PersonForm::arrivalDate());
		$this->deficiency = $_request->getParam(PersonForm::deficiency());
		$this->cpf = $_request->getParam(PersonForm::cpf());
		$this->ra = $_request->getParam(PersonForm::ra());
		$this->rg = $_request->getParam(PersonForm::rg());
		$this->rgComplement = $_request->getParam(PersonForm::rgComplement());
		$this->rgEmissionDate = $_request->getParam(PersonForm::rgEmissionDate());
		$this->rgSender = $_request->getParam(PersonForm::rgSender());
		$this->rgState = $_request->getParam(PersonForm::rgState());
		$this->nisNumber = $_request->getParam(PersonForm::nisNumber());
		$this->susNumber = $_request->getParam(PersonForm::susNumber());
		$this->titleVoter = $_request->getParam(PersonForm::titleVoter());
		$this->titleZone = $_request->getParam(PersonForm::titleZone());
		$this->titleSession = $_request->getParam(PersonForm::titleSession());
		$this->ctpsNumber = $_request->getParam(PersonForm::ctpsNumber());
		$this->ctpsSeries = $_request->getParam(PersonForm::ctpsSeries());
		$this->ctpsEmissionDate = $_request->getParam(PersonForm::ctpsEmissionDate());
		$this->ctpsState = $_request->getParam(PersonForm::ctpsState());
		$this->civilCertificateType = $_request->getParam(PersonForm::civilCertificateType());
		$this->civilCertificateTerm = $_request->getParam(PersonForm::civilCertificateTerm());
		$this->civilCertificateBook = $_request->getParam(PersonForm::civilCertificateBook());
		$this->civilCertificateLeaf = $_request->getParam(PersonForm::civilCertificateLeaf());
		$this->civilCertificateEmissionDate = $_request->getParam(PersonForm::civilCertificateEmissionDate());
		$this->civilCertificateOfficeName = $_request->getParam(PersonForm::civilCertificateOfficeName());
		$this->civilCertificateState = $_request->getParam(PersonForm::civilCertificateState());
		
		if($_request->isPost())
		{
			$filter								= BasicForm::getFilterStripTags();
			$this->id 							= trim($filter->filter($_request->getPost(PersonForm::id())));
			$this->state 						= trim($filter->filter($_request->getPost(PersonForm::state())));
			$this->nickname 					= trim($filter->filter($_request->getPost(PersonForm::nickname())));
			$this->metaNickname 				= trim($filter->filter($_request->getPost(PersonForm::metaNickname())));
			$this->name 						= trim($filter->filter($_request->getPost(PersonForm::name())));
			$this->metaName 					= trim($filter->filter($_request->getPost(PersonForm::metaName())));
			$this->sex 							= trim($filter->filter($_request->getPost(PersonForm::sex())));
			$this->race 						= trim($filter->filter($_request->getPost(PersonForm::race())));
			$this->tattoo 						= trim($filter->filter($_request->getPost(PersonForm::tattoo())));
			$this->maritalStatus 				= trim($filter->filter($_request->getPost(PersonForm::maritalStatus())));
			$this->birthDate 					= trim($filter->filter($_request->getPost(PersonForm::birthDate())));
			$this->deathDate 					= trim($filter->filter($_request->getPost(PersonForm::deathDate())));
			$this->nationality 					= trim($filter->filter($_request->getPost(PersonForm::nationality())));
			$this->nativeCountry 				= trim($filter->filter($_request->getPost(PersonForm::nativeCountry())));
			$this->arrivalDate 					= trim($filter->filter($_request->getPost(PersonForm::arrivalDate())));
			$this->deficiency 					= $_request->getPost(PersonForm::deficiency());
			$this->cpf 							= trim($filter->filter($_request->getPost(PersonForm::cpf())));
			$this->ra 							= trim($filter->filter($_request->getPost(PersonForm::ra())));
			$this->rg 							= trim($filter->filter($_request->getPost(PersonForm::rg())));
			$this->rgComplement 				= trim($filter->filter($_request->getPost(PersonForm::rgComplement())));
			$this->rgEmissionDate 				= trim($filter->filter($_request->getPost(PersonForm::rgEmissionDate())));
			$this->rgSender 					= trim($filter->filter($_request->getPost(PersonForm::rgSender())));
			$this->rgState 						= trim($filter->filter($_request->getPost(PersonForm::rgState())));
			$this->nisNumber 					= trim($filter->filter($_request->getPost(PersonForm::nisNumber())));
			$this->susNumber 					= trim($filter->filter($_request->getPost(PersonForm::susNumber())));
			$this->titleVoter 					= trim($filter->filter($_request->getPost(PersonForm::titleVoter())));
			$this->titleZone 					= trim($filter->filter($_request->getPost(PersonForm::titleZone())));
			$this->titleSession 				= trim($filter->filter($_request->getPost(PersonForm::titleSession())));
			$this->ctpsNumber 					= trim($filter->filter($_request->getPost(PersonForm::ctpsNumber())));
			$this->ctpsSeries 					= trim($filter->filter($_request->getPost(PersonForm::ctpsSeries())));
			$this->ctpsEmissionDate 			= trim($filter->filter($_request->getPost(PersonForm::ctpsEmissionDate())));
			$this->ctpsState 					= trim($filter->filter($_request->getPost(PersonForm::ctpsState())));
			$this->civilCertificateType 		= trim($filter->filter($_request->getPost(PersonForm::civilCertificateType())));
			$this->civilCertificateTerm 		= trim($filter->filter($_request->getPost(PersonForm::civilCertificateTerm())));
			$this->civilCertificateBook 		= trim($filter->filter($_request->getPost(PersonForm::civilCertificateBook())));
			$this->civilCertificateLeaf 		= trim($filter->filter($_request->getPost(PersonForm::civilCertificateLeaf())));
			$this->civilCertificateEmissionDate = trim($filter->filter($_request->getPost(PersonForm::civilCertificateEmissionDate())));
			$this->civilCertificateOfficeName 	= trim($filter->filter($_request->getPost(PersonForm::civilCertificateOfficeName())));
			$this->civilCertificateState 		= trim($filter->filter($_request->getPost(PersonForm::civilCertificateState())));
			$this->liveSince			 		= trim($filter->filter($_request->getPost(PersonForm::liveSince())));
		}
	}
	
	/**
	 * Recupera as informações do form e retorna no array de endereço temporário
	 * 
	 */
	function assembleFormToTempAddress(PersonForm $frm)
	{
		$addressTemp = array();
		
		$addressTemp[PAT_ID_ADDRESS]		= $frm->getAdrIdAddress();
		if($frm->getLiveSince() && strlen($frm->getLiveSince()) > 0 )
		{
			$addressTemp[PAT_LIVE_SINCE]		= BasicForm::dateFormat($frm->getLiveSince());
		}
		else
		{
			$addressTemp[PAT_LIVE_SINCE]		= null;
		}
		$addressTemp[PAT_NUMBER]			= $frm->getAdrNumber();
		$addressTemp[PAT_COMPLEMENT]		= $frm->getAdrComplement();
		$addressTemp[PAT_REFERENCE_POINT] 	= $frm->getAdrReference();
		
		return $addressTemp;
	}
	
	/**
	 * Recupera as informações do form e retorna no array
	 * 
	 */
	function assembleFormToPerson(PersonForm $frm)
	{
		$person = array();
		
		$person[PRS_ID_PERSON] 			= $frm->getId();
		$person[PRS_NICKNAME] 			= $frm->getNickname();
		$person[PRS_METANICKNAME] 		= $frm->getMetaNickname();
		$person[PRS_NAME] 				= $frm->getName();
		$person[PRS_METANAME] 			= $frm->getMetaName();
		$person[PRS_SEX] 				= $frm->getSex();
		$person[PRS_ID_RACE] 			= $frm->getRace();
		$person[PRS_TATTOO] 			= $frm->getTattoo();
		$person[PRS_ID_MARITAL_STATUS] 	= $frm->getMaritalStatus();
		$person[PRS_BIRTH_DATE] 		= null;
		if($frm->getBirthDate()){
			$birth = self::dateFormat($frm->getBirthDate());
			$person[PRS_BIRTH_DATE] 		= $birth;
		}
		$person[PRS_DEATH_DATE] 		= null;
		if($frm->getDeathDate()){
			$death = self::dateFormat($frm->getDeathDate());
			$person[PRS_DEATH_DATE] 		= $death;	
		}
		$person[PRS_ID_NATIONALITY] 	= $frm->getNationality();
		$person[PRS_NATIVE_COUNTRY] 	= $frm->getNativeCountry();
		$person[PRS_ARRIVAL_DATE] 		= null;
		if($frm->getArrivalDate()){
			$arrival = self::dateFormat($frm->getArrivalDate());
			$person[PRS_ARRIVAL_DATE] 		= $arrival;
		}

		return $person;
	}
	
	function assembleFormToDocument(PersonForm $frm)
	{
		Zend_Loader::loadClass(CLS_DOCUMENT);
		
		$document = null;
		
		$document[DOC_ID_PERSON] = $frm->getId();
		$document[DOC_CPF] = $frm->getCpf();
		$document[DOC_NIS] = $frm->getNisNumber();
		$document[DOC_SUS_CARD] = $frm->getSusNumber();
		$document[DOC_RA] = $frm->getRa();
		$document[DOC_RG_NUMBER] = $frm->getRg();
		$document[DOC_RG_COMPLEMENT] = $frm->getRgComplement();
		$document[DOC_RG_EMISSION_DATE] = null;
		if($frm->getRgEmissionDate()) 
		{
			$document[DOC_RG_EMISSION_DATE] = self::dateFormat($frm->getRgEmissionDate());	
		}
		$document[DOC_RG_SENDER] = $frm->getRgSender();
		$document[DOC_ID_RG_UF] = $frm->getRgState();
		$document[DOC_TITLE_NUMBER] = $frm->getTitleVoter();
		$document[DOC_TITLE_ZONE] = $frm->getTitleZone();
		$document[DOC_TITLE_SECTION] = $frm->getTitleSession();

		return $document;
	}
	
	function assembleFormToCtps(PersonForm $frm)
	{
		Zend_Loader::loadClass(CLS_CTPS);
		
		$ctps = null;
		
		$ctps[CTS_ID_PERSON] = $frm->getId();
		$ctps[CTS_ID_UF] = $frm->getCtpsState();
		$ctps[CTS_NUMBER] = $frm->getCtpsNumber();
		$ctps[CTS_SERIES] = $frm->getCtpsSeries();
		$ctps[CTS_EMISSION] = null;
		if($frm->getCtpsEmissionDate()) 
		{
			$ctps[CTS_EMISSION] = self::dateFormat($frm->getCtpsEmissionDate());	
		}

		return $ctps;
	}
	
	function assembleFormToCivilCertificate(PersonForm $frm)
	{
		Zend_Loader::loadClass(CLS_CIVILCERTIFICATE);
		
		$civil = null;
		
		$civil[CCF_ID_PERSON] = $frm->getId();
		$civil[CCF_ID_UF] = $frm->getCivilCertificateState();
		$civil[CCF_CERTIFICATE_TYPE] = $frm->getCivilCertificateType();
		$civil[CCF_TERM] = $frm->getCivilCertificateTerm();
		$civil[CCF_BOOK] = $frm->getCivilCertificateBook();
		$civil[CCF_LEAF] = $frm->getCivilCertificateLeaf();
		$civil[CCF_EMISSION_DATE] = null;
		if($frm->getCivilCertificateEmissionDate()) 
		{
			$civil[CCF_EMISSION_DATE] = self::dateFormat($frm->getCivilCertificateEmissionDate());	
		}
		$civil[CCF_REGISTRY_OFFICE_NAME] = $frm->getCivilCertificateOfficeName();
		
		return $civil;
	}
	
	function assembleFormToDeficiency(PersonForm $frm)
	{
		Zend_Loader::loadClass(CLS_DEFICIENCY);
		
		$deficiency = array();
		
		$deficiency[DFY_ID_PERSON] 		= $frm->getId();
		$deficiency[DFY_ID_DEFICIENCY]	= $frm->getDeficiency();
		
		return $deficiency;
	}
	
	/**
	 * Recupera as informações do objeto e insere no form
	 * 
	 */
	function assemblePersonToForm($person)
	{
		foreach($person as $prs)
		{
			$this->view->form->setId($prs->{PRS_ID_PERSON});
			$this->view->form->setNickname($prs->{PRS_NICKNAME});
			$this->view->form->setMetaNickname($prs->{PRS_METANICKNAME});
			$this->view->form->setName($prs->{PRS_NAME});
			$this->view->form->setMetaName($prs->{PRS_METANAME});
			$this->view->form->setSex($prs->{PRS_SEX});
			$this->view->form->setRace($prs->{PRS_ID_RACE});
			$this->view->form->setTattoo($prs->{PRS_TATTOO});
			$this->view->form->setMaritalStatus($prs->{PRS_ID_MARITAL_STATUS});
			if($prs->{PRS_BIRTH_DATE})
				$birth = self::dateFormatForm($prs->{PRS_BIRTH_DATE});
			$this->view->form->setBirthDate($birth);
			if($prs->{PRS_DEATH_DATE})
				$death = self::dateFormatForm($prs->{PRS_DEATH_DATE});
			$this->view->form->setDeathDate($death);
			$this->view->form->setNationality($prs->{PRS_ID_NATIONALITY});
			$this->view->form->setNativeCountry($prs->{PRS_NATIVE_COUNTRY});
			if($prs->{PRS_ARRIVAL_DATE})
				$arrival = self::dateFormatForm($prs->{PRS_ARRIVAL_DATE});
			$this->view->form->setArrivalDate($arrival);
			
			//carrega as informações de endereço temporário 
			$personAddressTemp = $prs->findDependentRowset(CLS_PERSONADDRESSTEMP);
			
			if($personAddressTemp && sizeof($personAddressTemp) > 0 )
			{		
				if($personAddressTemp->current()->{PAT_LIVE_SINCE} && strlen($personAddressTemp->current()->{PAT_LIVE_SINCE}) > 0)
				{
					$this->view->form->setLiveSince(parent::dateFormatForm($personAddressTemp->current()->{PAT_LIVE_SINCE}));
				}
				else
				{
					$this->view->form->setLiveSince(null);
				}
				
				if($personAddressTemp->current()->{PAT_NUMBER} && strlen($personAddressTemp->current()->{PAT_NUMBER}) > 0)
				{
					$this->view->form->setAdrNumber($personAddressTemp->current()->{PAT_NUMBER});
				}
				else
				{
					$this->view->form->setAdrNumber(null);
				}
				
				if($personAddressTemp->current()->{PAT_COMPLEMENT} && strlen($personAddressTemp->current()->{PAT_COMPLEMENT}) > 0)
				{
	 				$this->view->form->setAdrComplement($personAddressTemp->current()->{PAT_COMPLEMENT});
				}
				else
				{
					$this->view->form->setAdrComplement(null);
				}
				
				if($personAddressTemp->current()->{PAT_REFERENCE_POINT} && strlen($personAddressTemp->current()->{PAT_REFERENCE_POINT}) > 0)
				{
	 				$this->view->form->setAdrReference($personAddressTemp->current()->{PAT_REFERENCE_POINT});
				}
				else
				{
					$this->view->form->setAdrReference(null);
				}
	 			
	 			$address = $personAddressTemp->current()->findParentRow(CLS_ADDRESS);
	 			
	 			if($address->findParentRow(CLS_ADDRESSTYPE)->{ADT_DESCRIPTION} && strlen($address->findParentRow(CLS_ADDRESSTYPE)->{ADT_DESCRIPTION}) > 0)
	 			{
	 				$this->view->form->setAdrAddressType($address->findParentRow(CLS_ADDRESSTYPE)->{ADT_DESCRIPTION});
	 			}
	 			else
	 			{
	 				$this->view->form->setAdrAddressType(null);
	 			}
				
				if($address->{ADR_ID_ADDRESS} && strlen($address->{ADR_ID_ADDRESS}) > 0)
				{
					$this->view->form->setAdrIdAddress($address->{ADR_ID_ADDRESS});
				}
				else
				{
					$this->view->form->setAdrIdAddress(null);
				}
				
				if($address->{ADR_ADDRESS} && strlen($address->{ADR_ADDRESS}) > 0)
				{
					$this->view->form->setAdrAddress($address->{ADR_ADDRESS});
				}
				else
				{
					$this->view->form->setAdrAddress(null);
				}	
				
				if($address->{ADR_ZIP_CODE} && strlen($address->{ADR_ZIP_CODE}) > 0)
				{
					$this->view->form->setAdrZipcode($address->{ADR_ZIP_CODE});
				}
				else
				{
					$this->view->form->setAdrZipcode(null);
				}
				
				$uf = $address->findParentRow(CLS_NEIGHBORHOOD)->findParentRow(CLS_CITY)->findParentRow(CLS_UF)->{UF_ABBREVIATION};
				if($uf && strlen($uf) > 0)
				{
					$this->view->form->setAdrUf($address->findParentRow(CLS_NEIGHBORHOOD)->findParentRow(CLS_CITY)->findParentRow(CLS_UF)->{UF_ABBREVIATION});
				}
				else
				{
					$this->view->form->setAdrUf(null);
				}
				
				$city = $address->findParentRow(CLS_NEIGHBORHOOD)->findParentRow(CLS_CITY)->{CTY_CITY};
				if($city && strlen($city) > 0)
				{	
					$this->view->form->setAdrCity($address->findParentRow(CLS_NEIGHBORHOOD)->findParentRow(CLS_CITY)->{CTY_CITY});
				}
				else
				{
					$this->view->form->setAdrCity(null);
				}
				
				$neighborhood = $address->findParentRow(CLS_NEIGHBORHOOD)->{NHD_NEIGHBORHOOD};
				if($neighborhood && strlen($neighborhood) > 0)
				{
					$this->view->form->setAdrNeighborhood($address->findParentRow(CLS_NEIGHBORHOOD)->{NHD_NEIGHBORHOOD});
				}
				else
				{
					$this->view->form->setAdrNeighborhood(null);
				}
			}
		}
	}
	
	function assembleDocumentToForm($document)
	{
		foreach($document as $doc)
		{
			$this->view->form->setId($doc->{DOC_ID_PERSON});
			$this->view->form->setCpf($doc->{DOC_CPF});
			$this->view->form->setNisNumber($doc->{DOC_NIS});
			$this->view->form->setSusNumber($doc->{DOC_SUS_CARD});
			$this->view->form->setRa($doc->{DOC_RA});
			$this->view->form->setRg($doc->{DOC_RG_NUMBER});
			$this->view->form->setRgComplement($doc->{DOC_RG_COMPLEMENT});
			if($doc->{DOC_RG_EMISSION_DATE})
				$this->view->form->setRgEmissionDate(self::dateFormatForm($doc->{DOC_RG_EMISSION_DATE}));
			$this->view->form->setRgSender($doc->{DOC_RG_SENDER});
			$this->view->form->setRgState($doc->{DOC_ID_RG_UF});
			$this->view->form->setTitleVoter($doc->{DOC_TITLE_NUMBER});
			$this->view->form->setTitleZone($doc->{DOC_TITLE_ZONE});
			$this->view->form->setTitleSession($doc->{DOC_TITLE_SECTION});
		}
	} 
	
	function assembleCtpsToForm($ctps)
	{
		foreach($ctps as $work)
		{
			$this->view->form->setId($work->{CTS_ID_PERSON});
			$this->view->form->setCtpsState($work->{CTS_ID_UF});
			if($work->{CTS_NUMBER} == 0)
				$this->view->form->setCtpsNumber(null);
			else
				$this->view->form->setCtpsNumber($work->{CTS_NUMBER});
			if($work->{CTS_SERIES} == 0)
				$this->view->form->setCtpsSeries(null);
			else
				$this->view->form->setCtpsSeries($work->{CTS_SERIES});
			if($work->{CTS_EMISSION})
				$this->view->form->setCtpsEmissionDate(self::dateFormatForm($work->{CTS_EMISSION}));
		}
	}
	
	function assembleCivilCertificateToForm($civil)
	{
		foreach($civil as $cc)
		{
			$this->view->form->setId($cc->{CCF_ID_PERSON});
			$this->view->form->setCivilCertificateState($cc->{CCF_ID_UF});
			if($cc->{CCF_CERTIFICATE_TYPE} == 0)
				$this->view->form->setCivilCertificateType(null);
			else
				$this->view->form->setCivilCertificateType($cc->{CCF_CERTIFICATE_TYPE});
			if($cc->{CCF_TERM} == 0)
				$this->view->form->setCivilCertificateTerm(null);
			else
				$this->view->form->setCivilCertificateTerm($cc->{CCF_TERM});
			if($cc->{CCF_BOOK} == 0)
				$this->view->form->setCivilCertificateBook(null);
			else
				$this->view->form->setCivilCertificateBook($cc->{CCF_BOOK});
			if($cc->{CCF_LEAF} == 0)
				$this->view->form->setCivilCertificateLeaf(null);
			else
				$this->view->form->setCivilCertificateLeaf($cc->{CCF_LEAF});
			if($cc->{CCF_EMISSION_DATE})
				$this->view->form->setCivilCertificateEmissionDate(self::dateFormatForm($cc->{CCF_EMISSION_DATE}));
			$this->view->form->setCivilCertificateOfficeName($cc->{CCF_REGISTRY_OFFICE_NAME});
		}
	}
	
	function assembleDeficiencyToForm($deficiency)
	{
		foreach($deficiency as $def)
			if($def->{DFY_ID_DEFICIENCY})
				$this->view->form->setDeficiency($deficiency);
			else
				$this->view->form->setDeficiency(null);		
	}
	
	/**
	 * Getters and Setters
	 * 
	 */
	 public function getId(){return $this->id;}
	 public function getNickname(){return $this->nickname;}
	 public function getMetaNickname(){return $this->metaNickname;}
	 public function getName(){return $this->name;}
	 public function getMetaName(){return $this->metaName;}
	 public function getSex(){return $this->sex;}
	 public function getRace(){return $this->race;}
	 public function getTattoo(){return $this->tattoo;}
	 public function getMaritalStatus(){return $this->maritalStatus;}
	 public function getBirthDate(){return $this->birthDate;}
	 public function getDeathDate(){return $this->deathDate;}
	 public function getNationality(){return $this->nationality;}
	 public function getNativeCountry(){return $this->nativeCountry;}
	 public function getArrivalDate(){return $this->arrivalDate;}
	 public function getDeficiency(){return $this->deficiency;}
	 public function getCpf(){return $this->cpf;}
	 public function getRa(){return $this->ra;}
	 public function getRg(){return $this->rg;}
	 public function getRgComplement(){return $this->rgComplement;}
	 public function getRgEmissionDate(){return $this->rgEmissionDate;}
	 public function getRgSender(){return $this->rgSender;}
	 public function getRgState(){return $this->rgState;}
	 public function getNisNumber(){return $this->nisNumber;}
	 public function getSusNumber(){return $this->susNumber;}
	 public function getTitleVoter(){return $this->titleVoter;}
	 public function getTitleZone(){return $this->titleZone;}
	 public function getTitleSession(){return $this->titleSession;}
	 public function getCtpsNumber(){return $this->ctpsNumber;}
	 public function getCtpsSeries(){return $this->ctpsSeries;}
	 public function getCtpsEmissionDate(){return $this->ctpsEmissionDate;}
	 public function getCtpsState(){return $this->ctpsState;}
	 public function getCivilCertificateType(){return $this->civilCertificateType;}
	 public function getCivilCertificateTerm(){return $this->civilCertificateTerm;}
	 public function getCivilCertificateBook(){return $this->civilCertificateBook;}
	 public function getCivilCertificateLeaf(){return $this->civilCertificateLeaf;}
	 public function getCivilCertificateEmissionDate(){return $this->civilCertificateEmissionDate;}
	 public function getCivilCertificateOfficeName(){return $this->civilCertificateOfficeName;}
	 public function getCivilCertificateState(){return $this->civilCertificateState;}
	 public function getLiveSince(){return $this->liveSince;}
	 
	 public function setId($id){$this->id = $id;}
	 public function setNickname($nickname){$this->nickname = $nickname;}
	 public function setMetaNickname($metaNickname){$this->metaNickname = $metaNickname;}
	 public function setName($name){$this->name = $name;}
	 public function setMetaName($metaName){$this->metaName = $metaName;}
	 public function setSex($sex){$this->sex = $sex;}
	 public function setRace($race){$this->race = $race;}
	 public function setTattoo($tattoo){$this->tattoo = $tattoo;}
	 public function setMaritalStatus($maritalStatus){$this->maritalStatus = $maritalStatus;}
	 public function setBirthDate($birthDate){$this->birthDate = $birthDate;}
	 public function setDeathDate($deathDate){$this->deathDate = $deathDate;}
	 public function setNationality($nationality){$this->nationality = $nationality;}
	 public function setNativeCountry($nativeCountry){$this->nativeCountry = $nativeCountry;}
	 public function setArrivalDate($arrivalDate){$this->arrivalDate = $arrivalDate;}
	 public function setDeficiency($deficiency){$this->deficiency = $deficiency;}
	 public function setCpf($cpf){$this->cpf = $cpf;}
	 public function setRa($ra){$this->ra = $ra;}
	 public function setRg($rg){$this->rg = $rg;}
	 public function setRgComplement($rgComplement){$this->rgComplement = $rgComplement;}
	 public function setRgEmissionDate($rgEmissionDate){$this->rgEmissionDate = $rgEmissionDate;}
	 public function setRgSender($rgSender){$this->rgSender = $rgSender;}
	 public function setRgState($rgState){$this->rgState = $rgState;}
	 public function setNisNumber($nisNumber){$this->nisNumber = $nisNumber;}
	 public function setSusNumber($susNumber){$this->susNumber = $susNumber;}
	 public function setTitleVoter($titleVoter){$this->titleVoter = $titleVoter;}
	 public function setTitleZone($titleZone){$this->titleZone = $titleZone;}
	 public function setTitleSession($titleSession){$this->titleSession = $titleSession;}
	 public function setCtpsNumber($ctpsNumber){$this->ctpsNumber = $ctpsNumber;}
	 public function setCtpsSeries($ctpsSeries){$this->ctpsSeries = $ctpsSeries;}
	 public function setCtpsEmissionDate($ctpsEmissionDate){$this->ctpsEmissionDate = $ctpsEmissionDate;}
	 public function setCtpsState($ctpsState){$this->ctpsState = $ctpsState;}
	 public function setCivilCertificateType($civilCertificateType){$this->civilCertificateType = $civilCertificateType;}
	 public function setCivilCertificateTerm($civilCertificateTerm){$this->civilCertificateTerm = $civilCertificateTerm;}
	 public function setCivilCertificateBook($civilCertificateBook){$this->civilCertificateBook = $civilCertificateBook;}
	 public function setCivilCertificateLeaf($civilCertificateLeaf){$this->civilCertificateLeaf = $civilCertificateLeaf;}
	 public function setCivilCertificateEmissionDate($civilCertificateEmissionDate){$this->civilCertificateEmissionDate = $civilCertificateEmissionDate;}
	 public function setCivilCertificateOfficeName($civilCertificateOfficeName){$this->civilCertificateOfficeName = $civilCertificateOfficeName;}
	 public function setCivilCertificateState($civilCertificateState){$this->civilCertificateState = $civilCertificateState;}
	 public function setLiveSince($liveSince){$this->liveSince = $liveSince;}
}