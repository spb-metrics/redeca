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

define('PCH_ID_CHANGE_HISTORY',		'id_person_change_history');
define('PCH_ID_REFERENCE_FOREIGN',	'id_reference_foreign');
define('PCH_ID_USER',	 			'id_user');
define('PCH_ID_PERSON',	 			'id_person');
define('PCH_ID_RESOURCE',			'id_resource');
define('PCH_DATE_OPERATION',		'dat_operation');
define('PCH_TABLE_NAME',			'table_name');

require_once (CLS_AUTH_USER.".php");
require_once (CLS_PERSON.".php");
require_once (CLS_AUTH_RESOURCE.".php");

class PersonChangeHistory extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_PERSON_CHANGE_HISTORY;
	protected $_primary = PCH_ID_CHANGE_HISTORY; 
	
	protected $_referenceMap    = array(
        CLS_AUTH_USER => array(
            'columns'           => PCH_ID_USER,
            'refTableClass'     => CLS_AUTH_USER,
            'refColumns'        => AUTH_ID_USER
        ),
        CLS_PERSON => array(
            'columns'           => PCH_ID_PERSON,
            'refTableClass'     => CLS_PERSON,
            'refColumns'        => PRS_ID_PERSON
        ),
        CLS_AUTH_RESOURCE => array(
            'columns'           => PCH_ID_RESOURCE,
            'refTableClass'     => CLS_AUTH_RESOURCE,
            'refColumns'        => ARC_ID_RESOURCE
        )
    );
}
