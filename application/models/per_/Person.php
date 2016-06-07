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
 * Jefferson Barros Lima  - W3S		   				13/02/2008	                       Create file 
 * 
 */

define('PRS_ID_PERSON','id_person');
define('PRS_NAME','name');
define('PRS_METANAME','metaname');
define('PRS_NICKNAME','nickname');
define('PRS_METANICKNAME','metanickname');
define('PRS_SEX','sex');
define('PRS_TATTOO','tattoo');
define('PRS_NATIVE_COUNTRY','native_country');
define('PRS_ARRIVAL_DATE','arrival_date');
define('PRS_DEATH_DATE','death_date');
define('PRS_BIRTH_DATE','birth_date');
define('PRS_ID_NATIONALITY','id_nationality');
define('PRS_ID_RACE','id_race');
define('PRS_ID_MARITAL_STATUS','id_marital_status');

require_once(CLS_HEALTH.".php");
require_once(CLS_PREGNANCY.".php");
require_once(CLS_LEVELINSTRUCTION.".php");
require_once(CLS_MARITALSTATUS.".php");
require_once(CLS_RACE.".php");
require_once(CLS_NATIONALITY.".php");
require_once(CLS_DOCUMENT.".php");

class Person extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_PERSON;
	protected $_primary = PRS_ID_PERSON;
	
	protected $_dependentTables = array(CLS_PREGNANCY,
										CLS_REPRESENTATIVE, 
										CLS_FAMILY, 
										CLS_SOCIALPROGRAM, 
										CLS_CONSANGUINE, 
										CLS_DEFICIENCY,
										CLS_ASSISTANCE,
										CLS_CTPS,
										CLS_CIVILCERTIFICATE,
										CLS_PERSONTELEPHONE,
										CLS_PERSONADDRESSTEMP,
										CLS_INCOME,
										CLS_HEALTH,
										CLS_LEVELINSTRUCTION,
										CLS_DOCUMENT);	
	
	protected $_referenceMap    = array(
//        CLS_LEVELINSTRUCTION => array(
//            'columns'           => PRS_ID_PERSON, /*in this table*/
//            'refTableClass'     => CLS_LEVELINSTRUCTION,   /* Class Name */
//            'refColumns'        => LIT_ID_PERSON /*ref table*/
//        ),
//        CLS_DOCUMENT => array(
//            'columns'           => PRS_ID_PERSON, /*in this table*/
//            'refTableClass'     => CLS_DOCUMENT,   /* Class Name */
//            'refColumns'        => DOC_ID_PERSON /*ref table*/
//        ),
        CLS_MARITALSTATUS => array(
            'columns'           => PRS_ID_MARITAL_STATUS, /*in this table*/
            'refTableClass'     => CLS_MARITALSTATUS,   /* Class Name */
            'refColumns'        => MST_ID_MARITAL_STATUS /*ref table*/
        ),
        CLS_RACE => array(
            'columns'           => PRS_ID_RACE, /*in this table*/
            'refTableClass'     => CLS_RACE,   /* Class Name */
            'refColumns'        => RAC_ID_RACE /*ref table*/
        ),
        CLS_NATIONALITY => array(
            'columns'           => PRS_ID_NATIONALITY, /*in this table*/
            'refTableClass'     => CLS_NATIONALITY,   /* Class Name */
            'refColumns'        => NTY_ID_NATIONALITY /*ref table*/
        )
    );
}
