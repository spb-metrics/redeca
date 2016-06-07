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
 * Jefferson Barros Lima  - W3S		    			18/02/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class SearchAddressBusiness extends BasicBusiness
{
	const PAGINATION_VALUE = 5;

	public static function loadClasses()
	{
		Zend_Loader::loadClass('Utils');
		Zend_Loader::loadClass('AddressBusiness');
		Zend_Loader::loadClass('Address');
		Zend_Loader::loadClass('SearchAddressValidator');
		Zend_Loader::loadClass('MetaPhoneClass');
		Zend_Loader::loadClass('AddressTypeBusiness');
	}
	
	public static function searchAddress(BasicController &$controller)
	{	
		if(!empty($controller))
		{
			self::loadClasses();
			
			$frm = $controller->view->form;
			// Controle para manter estado do tipo de busca
			$controller->view->action = Constants::SEARCH_ADDRESS_ACTION_TYPE;
		
			$errorMessages = SearchAddressValidator::validateAddress($frm);
		 	if(sizeof($errorMessages) > 0)
			{
				$controller->view->addressErrorMessages = $errorMessages;
				return FALSE;
			}
			$controller->view->addressType = AddressTypeBusiness::fetchAll();
			
			$page 	= 1;
			if($controller->getRequest()->isPost() && !$controller->getRequest()->getPost(SearchAddressForm::filter()))
				$page 				= $controller->getRequest()->getPost(SearchAddressForm::page());

			$start 					= BasicController::pageToStart($page, self::PAGINATION_VALUE);
			$total					= AddressBusiness::count(TBL_ADDRESS, ADR_ADDRESS_METAFONE, self::assembleAddress($frm));
			$controller->view->addresses = AddressBusiness::searchByMetafone(self::assembleAddress($frm), $start, self::PAGINATION_VALUE);
			$controller->navBar($page, $total, self::PAGINATION_VALUE);
			return TRUE;
		}
	}
	
	public static function searchZipcode(BasicController &$controller)
	{
		if(!empty($controller))
		{
			self::loadClasses();
			
			$frm = $controller->view->form;
			// Controle para manter estado do tipo de busca
			$controller->view->action = Constants::SEARCH_ZIPCODE_ACTION_TYPE;
			
			$errorMessages = SearchAddressValidator::validateZipcode($frm);
		 	if(sizeof($errorMessages) > 0)
			{
				$controller->view->zipcodeErrorMessages = $errorMessages;
				return FALSE;
			}
			$controller->view->addressType = AddressTypeBusiness::fetchAll();
			
			
			$page 	= 1;
			if($controller->getRequest()->isPost() && !$controller->getRequest()->getPost(SearchAddressForm::filter()))
				$page 				= $controller->getRequest()->getPost(SearchAddressForm::page());
	
			$start 					= BasicController::pageToStart($page, self::PAGINATION_VALUE);
			
			$total					= AddressBusiness::count(TBL_ADDRESS, ADR_ZIP_CODE, self::assembleZipcode($frm));
			$controller->view->addresses = AddressBusiness::searchByZipCode(self::assembleZipcode($frm), $start, self::PAGINATION_VALUE);
			$controller->navBar($page, $total, self::PAGINATION_VALUE);
			return TRUE;
		}
	}
	
	/**
	 * Adiciona um Logradouro ou refaz a busca em caso de erro de validação
	 */
	public static function addAddress(BasicController &$controller)
	{
		self::loadClasses();
		$frm = $controller->view->form;
		$errorMessages = SearchAddressValidator::validateData($frm);
		if(sizeof($errorMessages) > 0)
		{
			$controller->view->addressErrorMessages = $errorMessages;
			if($frm->getFlgAction() == SearchAddressForm::FLG_ACTION_ZIPCODE_KEY)
				SearchAddressBusiness::searchZipcode($controller);

			if($frm->getFlgAction() == SearchAddressForm::FLG_ACTION_ADDRESS_KEY)
				SearchAddressBusiness::searchAddress($controller);
			
			return ;
		}
		$idAddress = self::add($controller);		
		$page 	= 1;
		if($controller->getRequest()->isPost() && !$controller->getRequest()->getPost(SearchAddressForm::filter()))
			$page 				= $controller->getRequest()->getPost(SearchAddressForm::page());

		$whereData[ADR_ID_ADDRESS. ' = ?'] = $idAddress;

		$start 					= BasicController::pageToStart($page, Zend_Registry::get(TPAGE));
		$total					= AddressBusiness::count(TBL_ADDRESS, ADR_ID_ADDRESS, $whereData);
		$controller->view->addresses 	= AddressBusiness::load($idAddress, $start, Zend_Registry::get(TPAGE));
		$controller->navBar($page, $total, Zend_Registry::get(TPAGE));
	}
	
	/**
	 * Adiciona um Logradouro 
	 * @return Integer Retorna o Id do logradouro inserido ou FALSE
	 */
	public static function add(BasicController &$controller)
	{
		if(!empty($controller))
		{
			self::loadClasses();
			
			$frm = $controller->view->form;
			Zend_Loader::loadClass('UFBusiness');
			Zend_Loader::loadClass('CityBusiness');
			Zend_Loader::loadClass('NeighborhoodBusiness');
			Zend_Loader::loadClass('AddressBusiness');
			Zend_Loader::loadClass('AddressTypeBusiness');
			
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			try
			{
				
				// Procura por UF já cadastrada e insere caso não encontre
				$ufData[UF_ABBREVIATION] = $frm->getAdrUf();
				$uf = UFBusiness::findByUf($frm->getAdrUf());
				if(count($uf) < 1)
				{
					$ufData[UF_USER_INSERTED] = TRUE;
					$idUf = UFBusiness::save($ufData, $db);
				}
				else
					$idUf = $uf->{UF_ID_UF};

				// Procura por cidade já cadastrada e insere caso não encontre
				$cityData[CTY_ID_UF] = $idUf;
				$cityData[CTY_CITY] = $frm->getAdrCity();
				$city = CityBusiness::findByQuery( self::buildWhere($cityData) );
				if(count($city) < 1)
				{
					$cityData[CTY_USER_INSERTED] = TRUE;
					$idCity = CityBusiness::insert($cityData, $db);
				}
				else
					$idCity = $city->{CTY_ID_CITY};

				// Procura por Bairro já cadastrada e insere caso não encontre
				$neighborData[NHD_ID_CITY] = $idCity;
				$neighborData[NHD_NEIGHBORHOOD] = $frm->getAdrNeighborhood();
				$neighborhood = NeighborhoodBusiness::findByQuery( self::buildWhere($neighborData) );
				if(count($neighborhood) < 1)
				{
					$neighborData[NHD_USER_INSERTED] = TRUE;
					$idNbh = NeighborhoodBusiness::insert($neighborData, $db);
				}
				else
					$idNbh = $neighborhood->{NHD_ID_NEIGHBORHOOD};

				// Procura por tipo de logradouro já cadastrada e insere caso não encontre
				$typeData[ADT_DESCRIPTION] = $frm->getAdrAddressType();
				$type = AddressTypeBusiness::findByQuery( self::buildWhere($typeData) );
				if(count($type) < 1)
				{
					$typeData[ADT_USER_INSERTED] = TRUE;
					$idType = AddressTypeBusiness::insert($typeData, $db);
				}
				else
					$idType = $type->{ADT_ID_ADDRESS_TYPE};

				// Procura por logradouro já cadastrada e insere caso não encontre
				$addressData[ADR_ID_NEIGHBORHOOD] = $idNbh;
				$addressData[ADR_ADDRESS] = $frm->getAdrAddress();
				$address = AddressBusiness::findByQuery( self::buildWhere($addressData) );
				$address = $address->current();
				if(count($address) < 1)
				{
					$addressData[ADR_ID_ADDRESS_TYPE] = $idType;
					$addressData[ADR_ADDRESS_METAFONE] = MetaPhoneClass::getMetaPhone($frm->getAdrAddress());
					$addressData[ADR_ZIP_CODE] = $frm->getAdrZipcode();
					$addressData[ADR_USER_INSERTED] = TRUE;
					$idAddress = AddressBusiness::insert($addressData, $db);
				}
				else
					$idAddress = $address->{ADR_ID_ADDRESS};

				$db->commit();	
				return $idAddress;
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
				trigger_error(parent::getLabelResources()->searchaddress->save->fail, E_USER_ERROR);
			}
		}
		return FALSE;
	}
	/**
	 * Adiciona o operador num dado array
	 */
	private static function buildWhere($arrayData, $operator = ' = ')
	{
		$data = NULL;
		if($arrayData && is_array($arrayData))
			foreach($arrayData as $key => $value)
			{
				$data[$key . $operator . ' ?'] = $value;
			}
		return $data;
	}

	public static function assembleZipcode(SearchAddressForm &$frm)
	{
		if(!empty($frm))
		{
			$zipcode[ADR_ZIP_CODE. ' = ?'] = self::getValue($frm->getZipcode());
			return $zipcode;
		}
		return NULL;
	}

	public static function assembleAddress(SearchAddressForm &$frm)
	{
		if(!empty($frm))
		{
			$meta = MetaPhoneClass::getMetaPhone(self::getValue($frm->getAddress()));
			
			if(strlen($meta) > 0 )
			{	
				$config = Zend_Registry::get(CONFIG);
				$arrayMetaname = explode($config->metaname->delimiter, $meta);
				
				foreach($arrayMetaname as $unique)
				{	
					if(strlen($unique) > 0)
					{
						$array[] = $unique;
					}
				}			

				if(sizeof($array) > 0)
				{
					if(sizeof($array) > 1)
					{	
						for($index = 0; $index < sizeof($array); $index ++)
						{
							$address[ADR_ADDRESS_METAFONE. ' like '.Constants::QUOTE_SIMPLE.'%'.$config->metaname->delimiter.$array[$index].$config->metaname->delimiter.'%'.Constants::QUOTE_SIMPLE] = null;
						}	
					}
					else
					{
						$address[ADR_ADDRESS_METAFONE. ' like '.Constants::QUOTE_SIMPLE.'%'.$config->metaname->delimiter.$array[0].$config->metaname->delimiter.'%'.Constants::QUOTE_SIMPLE] = null;
					}
				}
			}
			else
			{	
				$address[ADR_ADDRESS_METAFONE. ' = ?'] = $meta;
			}
			
			return $address;
		}
		
		return NULL;
	}
	
	private static function getValue($value, $default = NULL)
	{
		if(Utils::isEmpty($value))
			return $default;
		else
			return $value;
	}
}