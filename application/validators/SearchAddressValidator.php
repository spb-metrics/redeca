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
 * Saulo Esteves Rodrigues  - W3S		   			10/01/2008	                       Create file 
 * 
 */

require_once 'BasicValidator.php';

abstract class SearchAddressValidator extends BasicValidator
{
	public static function validateZipcode(SearchAddressForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorStringLength(8, 8);
		$validatorFloat = parent::validatorFloat();
		
		if (!$validatorRequired->isValid($frm->getZipcode()))
		{
			$errorMessages[SearchAddressForm::zipcode()][] = $validatorRequired->getMessages();
		}
		if (!$validator->isValid($frm->getZipcode()))
		{
			$errorMessages[SearchAddressForm::zipcode()][] = $validator->getMessages();
		}
		if (!$validatorFloat->isValid($frm->getZipcode()))
		{
			$errorMessages[SearchAddressForm::zipcode()][] = $validatorFloat->getMessages();
		}
			
		return $errorMessages;
	}
	
	public static function validateAddress(SearchAddressForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		$validator = parent::validatorStringLength(3, 72);
		
		if (!$validatorRequired->isValid($frm->getAddress()))
		{
			$errorMessages[SearchAddressForm::address()][] = $validatorRequired->getMessages();
		}
		if (!$validator->isValid($frm->getAddress()))
		{
			$errorMessages[SearchAddressForm::address()][] = $validator->getMessages();
		}
		return $errorMessages;
	}

	public static function validateData(SearchAddressForm &$frm, &$errorMessages = null)
	{
		$validatorRequired = parent::validatorNotEmpty();
		if (!$validatorRequired->isValid($frm->getAdrAddressType()))
			$errorMessages[SearchAddressForm::adr_addressType()][] = $validatorRequired->getMessages();
		if (!$validatorRequired->isValid($frm->getAdrNeighborhood()))
			$errorMessages[SearchAddressForm::adr_neighborhood()][] = $validatorRequired->getMessages();
		if (!$validatorRequired->isValid($frm->getAdrCity()))
			$errorMessages[SearchAddressForm::adr_city()][] = $validatorRequired->getMessages();
		if (!$validatorRequired->isValid($frm->getAdrUf()))
			$errorMessages[SearchAddressForm::adr_uf()][] = $validatorRequired->getMessages();
		if (!$validatorRequired->isValid($frm->getAdrAddress()))
			$errorMessages[SearchAddressForm::adr_address()][] = $validatorRequired->getMessages();
		
		$validatorAddress = parent::validatorStringLength(3, 72);
		if (!$validatorAddress->isValid($frm->getAdrAddress()))
			$errorMessages[SearchAddressForm::adr_address()][] = $validatorAddress->getMessages();
		
		$validatorZipcode = parent::validatorStringLength(8, 8);
		$validatorFloat = parent::validatorFloat();
		if (strlen($frm->getAdrZipcode()) > 0 && !$validatorZipcode->isValid($frm->getAdrZipcode()))
			$errorMessages[SearchAddressForm::adr_zipcode()][] = $validatorZipcode->getMessages();
		if (strlen($frm->getAdrZipcode()) > 0 && !$validatorFloat->isValid($frm->getAdrZipcode()))
			$errorMessages[SearchAddressForm::adr_zipcode()][] = $validatorFloat->getMessages();
		
//		$validatorReference = parent::validatorStringLength(3, 72);
//		if (strlen($frm->getAdrReference()) > 0 && !$validatorReference->isValid($frm->getAdrReference()))
//			$errorMessages[SearchAddressForm::adr_reference()][] = $validatorReference->getMessages();
//		
//		$validatorComplement = parent::validatorStringLength(3, 72);
//		if (strlen($frm->getAdrComplement()) > 0 && !$validatorComplement->isValid($frm->getAdrComplement()))
//			$errorMessages[SearchAddressForm::adr_complement()][] = $validatorComplement->getMessages();
	
//		$validatorNumber = parent::validatorInt();
//		if (strlen($frm->getAdrNumber()) > 0 && !$validatorNumber->isValid($frm->getAdrNumber()))
//			$errorMessages[SearchAddressForm::adr_number()][] = $validatorNumber->getMessages();

		return $errorMessages;
	}

	public static function validate(SearchAddressForm &$frm, &$errorMessages = null)
	{
		$errorMessages = null;
		self::validateZipcode($frm, $errorMessages);
		self::validateAddress($frm, $errorMessages);
		return $errorMessages;
	}
}