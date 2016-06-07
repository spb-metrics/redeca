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
 * Jefferson Barros Lima  - W3S		   				24/04/2008	                       Create file 
 * 
 */

class FactoryBuilderQuery
{
	public static function factory($class)
	{
		if($class === CLS_PERSON)
		{
			Zend_Loader::loadClass('PersonBuilderQuery');
			return PersonBuilderQuery::getInstance();
		}
		if($class === CLS_INCOME)
		{
			Zend_Loader::loadClass('IncomeBuilderQuery');
			return IncomeBuilderQuery::getInstance();
		}
		if($class === CLS_TELEPHONENUMBER)
		{
			Zend_Loader::loadClass('TelephoneBuilderQuery');
			return TelephoneBuilderQuery::getInstance();
		}
		if($class === CLS_FAMILY)
		{
			Zend_Loader::loadClass('FamilyBuilderQuery');
			return FamilyBuilderQuery::getInstance();
		}
		if($class === CLS_EXPENSE)
		{
			Zend_Loader::loadClass('ExpenseBuilderQuery');
			return ExpenseBuilderQuery::getInstance();
		}
		if($class === CLS_ASSISTANCE)
		{
			Zend_Loader::loadClass('AssistanceBuilderQuery');
			return AssistanceBuilderQuery::getInstance();
		}
		if($class === CLS_GENERALASSISTANCE)
		{
			Zend_Loader::loadClass('GeneralAssistanceBuilderQuery');
			return GeneralAssistanceBuilderQuery::getInstance();
		}
		if($class === CLS_ESPECIALASSISTANCE)
		{
			Zend_Loader::loadClass('EspecialAssistanceBuilderQuery');
			return EspecialAssistanceBuilderQuery::getInstance();
		}
		if($class === CLS_HEALTH)
		{
			Zend_Loader::loadClass('HealthBuilderQuery');
			return HealthBuilderQuery::getInstance();
		}
		if($class === CLS_LEVELINSTRUCTION)
		{
			Zend_Loader::loadClass('LevelInstructionBuilderQuery');
			return LevelInstructionBuilderQuery::getInstance();
		}
		if($class === CLS_SOCIALPROGRAM)
		{
			Zend_Loader::loadClass('SocialProgramBuilderQuery');
			return SocialProgramBuilderQuery::getInstance();
		}
		if($class === CLS_RESIDENCE)
		{
			Zend_Loader::loadClass('ResidenceBuilderQuery');
			return ResidenceBuilderQuery::getInstance();
		}
		if($class === CLS_ADDRESS)
		{
			Zend_Loader::loadClass('AddressBuilderQuery');
			return AddressBuilderQuery::getInstance();
		}
		if($class === CLS_ENTITY)
		{
			Zend_Loader::loadClass('EntityBuilderQuery');
			return EntityBuilderQuery::getInstance();
		}
		if($class === CLS_CLASSMODEL)
		{
			Zend_Loader::loadClass('ClassBuilderQuery');
			return ClassBuilderQuery::getInstance();
		}
		if($class === CLS_CONSANGUINE)
		{
			Zend_Loader::loadClass('ConsanguineBuilderQuery');
			return ConsanguineBuilderQuery::getInstance();
		}
		if($class === CLS_PREGNANCY)
		{
			Zend_Loader::loadClass('PregnancyBuilderQuery');
			return PregnancyBuilderQuery::getInstance();
		}
		if($class === CLS_CLASSASSISTANCE)
		{
			Zend_Loader::loadClass('ClassAssistanceBuilderQuery');
			return ClassAssistanceBuilderQuery::getInstance();
		}
		if($class === CLS_PROGRAM)
		{
			Zend_Loader::loadClass('ProgramBuilderQuery');
			return ProgramBuilderQuery::getInstance();
		}
	}
}
