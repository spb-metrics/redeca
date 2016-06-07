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
require_once (CLS_PERSON.'.php');
require_once (CLS_DEFICIENCY.'.php');
require_once (CLS_DEFICIENCYTYPE.'.php');
require_once (CLS_MARITALSTATUS.'.php');
require_once (CLS_RACE.'.php');
require_once (CLS_NATIONALITY.'.php');
require_once (CLS_DOCUMENT.'.php');
require_once (CLS_CTPS.'.php');
require_once (CLS_CIVILCERTIFICATE.'.php');
require_once (CLS_PERSONTELEPHONE.'.php');
require_once (CLS_PERSONADDRESSTEMP.'.php');

require_once ('BuilderQueryAbstract.php');

class PersonBuilderQuery extends BuilderQueryAbstract
{
	private static $instance;
	protected static $tables = array(
		CLS_PERSON => array(
				parent::_PREFIX => parent::PERSON_PREFIX,
				self::_MAIN_TABLE_KEY => TRUE,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_DEFICIENCY => array(
										parent::PERSON_PREFIX => PRS_ID_PERSON, 
										parent::DEFICIENCY_PREFIX => DFY_ID_PERSON),
					CLS_MARITALSTATUS => array(
										parent::PERSON_PREFIX => PRS_ID_MARITAL_STATUS, 
										parent::MARITALSTATUS_PREFIX => MST_ID_MARITAL_STATUS),
					CLS_RACE => array(
										parent::PERSON_PREFIX => PRS_ID_RACE, 
										parent::RACE_PREFIX => RAC_ID_RACE),
					CLS_NATIONALITY => array(
										parent::PERSON_PREFIX => PRS_ID_NATIONALITY, 
										parent::NATIONALITY_PREFIX => NTY_ID_NATIONALITY),
					CLS_DOCUMENT => array(
										parent::PERSON_PREFIX => PRS_ID_PERSON, 
										parent::DOCUMENT_PREFIX => DOC_ID_PERSON),
					CLS_CTPS => array(
										parent::PERSON_PREFIX => PRS_ID_PERSON, 
										parent::CTPS_PREFIX => CTS_ID_PERSON),
					CLS_CIVILCERTIFICATE => array(
										parent::PERSON_PREFIX => PRS_ID_PERSON, 
										parent::CIVILCERTIFICATE_PREFIX => CCF_ID_PERSON),
					CLS_PERSONTELEPHONE => array(
										parent::PERSON_PREFIX => PRS_ID_PERSON, 
										parent::PERSONTELEPHONE_PREFIX => PRT_ID_PERSON)
					,CLS_PERSONADDRESSTEMP => array(
										parent::PERSON_PREFIX => PRS_ID_PERSON, 
										parent::PERSONADDRESSTEMP_PREFIX => PAT_ID_PERSON)
					)
				),
		
		CLS_DEFICIENCY => array(
				parent::_PREFIX => parent::DEFICIENCY_PREFIX,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_DEFICIENCYTYPE => array(
										parent::DEFICIENCY_PREFIX => DFY_ID_DEFICIENCY, 
										parent::DEFICIENCYTYPE_PREFIX => DFT_ID_DEFICIENCY)
					)
				),
		CLS_DEFICIENCYTYPE => array(parent::_PREFIX => parent::DEFICIENCYTYPE_PREFIX),
		CLS_MARITALSTATUS => array(parent::_PREFIX => parent::MARITALSTATUS_PREFIX),
		CLS_RACE => array(parent::_PREFIX => parent::RACE_PREFIX),
		CLS_NATIONALITY => array(parent::_PREFIX => parent::NATIONALITY_PREFIX),
		CLS_DOCUMENT => array(parent::_PREFIX => parent::DOCUMENT_PREFIX),
		CLS_CTPS => array(parent::_PREFIX => parent::CTPS_PREFIX),
		CLS_CIVILCERTIFICATE => array(parent::_PREFIX => parent::CIVILCERTIFICATE_PREFIX),
		CLS_PERSONTELEPHONE => array(parent::_PREFIX => parent::PERSONTELEPHONE_PREFIX),
		CLS_PERSONADDRESSTEMP => array(parent::_PREFIX => parent::PERSONADDRESSTEMP_PREFIX)
	);
	
	private function __construct(){ /* Classe Não pode ser instanciada fora da classe */ }
	
	
	public static function getInstance()
	{
        if (!isset(self::$instance))
        {
        	self::$instance = new PersonBuilderQuery();
    	}
    	return self::$instance;
    }
    
	public function getTableMap()
	{
		return self::$tables;
	}
	
	public function build()
	{
		$sql = $this->getBuildQuery();
		/* o conteúdo da cláusula where deve ser um array de valores */
		$sql[self::_WHERE_KEY] = array( 
//				self::PERSON_PREFIX.'.'.PRS_ID_MARITAL_STATUS.' is null'
		);
		return $sql;
	}
}
