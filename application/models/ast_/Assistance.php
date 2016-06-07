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

define('AST_ID_ASSISTANCE','id_assistance');
define('AST_ID_PERSON','id_person');
define('AST_ID_PROGRAM','id_program');
define('AST_ID_USER','id_user');
define('AST_BEGINNING_DATE','beginning_date');
define('AST_END_DATE_PREVISION','end_date_prevision');
define('AST_REAL_END_DATE','real_end_date');
define('AST_CONFIDENTIALITY','confidentiality');

require_once (CLS_PERSON.".php");
require_once (CLS_ESPECIALASSISTANCE.".php");
require_once (CLS_PROGRAM.".php");
require_once (CLS_AUTH_USER.".php");

class Assistance extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_ASSISTANCE;
	protected $_primary = AST_ID_ASSISTANCE;
	
	protected $_dependentTables = array(CLS_CLASSASSISTANCE, CLS_GENERALASSISTANCE);
	
	protected $_referenceMap    = array(
        CLS_PERSON => array(
            'columns'           => AST_ID_PERSON, 
            'refTableClass'     => CLS_PERSON, 
            'refColumns'        => PRS_ID_PERSON
        ),
        CLS_ESPECIALASSISTANCE => array(
            'columns'           => AST_ID_ASSISTANCE, 
            'refTableClass'     => CLS_ESPECIALASSISTANCE, 
            'refColumns'        => EAS_ID_ASSISTANCE
        ),
        CLS_PROGRAM => array(
            'columns'           => AST_ID_PROGRAM, 
            'refTableClass'     => CLS_PROGRAM, 
            'refColumns'        => PGR_ID_PROGRAM
        ),
        CLS_AUTH_USER => array(
            'columns'           => AST_ID_USER, 
            'refTableClass'     => CLS_AUTH_USER, 
            'refColumns'        => AUTH_ID_USER
        )
    );

}
