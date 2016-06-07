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
 * Jefferson Barros Lima  - W3S		   				18/02/2008	                       Create file 
 * 
 */

require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('ZIPCODE', 	'zipcode');
define('ADDRESS', 	'address');
define('FLG_ACTION','flg_action');

class SearchAddressForm extends BasicForm
{
	const FLG_ACTION_ZIPCODE_KEY 	= 'flg_zipcode';
	const FLG_ACTION_ADDRESS_KEY 	= 'flg_address';
	const FLG_ACTION_ADD_KEY 		= 'flg_add';

	private $zipcode;
	private $address;
	private $flg_action;

	public static function zipcode(){return ZIPCODE;}
	public static function address(){return ADDRESS;}
	public static function flgAction(){return FLG_ACTION;}
	
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);

		if($_request->isPost())
		{
			$filter				= BasicForm::getFilterStripTags();
			$this->zipcode		= trim($filter->filter($_request->getPost(SearchAddressForm::zipcode())));
			$this->address		= trim($filter->filter($_request->getPost(SearchAddressForm::address())));
			$this->flg_action	= trim($filter->filter($_request->getPost(SearchAddressForm::flgAction())));
		}
	}

	public function getZipcode()
	{
		return $this->zipcode;
	}
	public function getAddress()
	{
		return $this->address;
	}
	public function getFlgAction()
	{
		return $this->flg_action;
	}
	public function setZipcode($zipcode)
	{
		$this->zipcode = $zipcode;
	}
	public function setAddress($address)
	{
		$this->address = $address;
	}
}
