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
 * @copyright Funda��o Telef�nica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Ara�atuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guaruj� - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de S�o Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de V�rzea Paulista - http://www.varzeapaulista.sp.gov.br 
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

define('REG_ID_REGISTRATION','id_registration');
define('REG_ID_LEVEL_INSTRUCTION','id_level_instruction');
define('REG_ID_SCHOOL_YEAR','id_school_year');
define('REG_ID_PERIOD','id_period');
define('REG_ID_SCHOOL','id_school');
define('REG_STATUS','status');

require_once(CLS_SCHOOL.'.php');
require_once(CLS_SCHOOLYEARTYPE.'.php');
require_once(CLS_PERIODTYPE.'.php');
require_once(CLS_LEVELINSTRUCTION.'.php');

class Registration extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_REGISTRATION;
	protected $_primary = REG_ID_REGISTRATION;
	
	protected $_referenceMap    = array(
        CLS_SCHOOL => array(
            'columns'           => REG_ID_SCHOOL, 
            'refTableClass'     => CLS_SCHOOL,
            'refColumns'        => SCH_ID_SCHOOL
        ),
        CLS_SCHOOLYEARTYPE => array(
            'columns'           => REG_ID_SCHOOL_YEAR, 
            'refTableClass'     => CLS_SCHOOLYEARTYPE,
            'refColumns'        => SYT_ID_SCHOOL_YEAR
        ),
        CLS_PERIODTYPE => array(
            'columns'           => REG_ID_PERIOD, 
            'refTableClass'     => CLS_PERIODTYPE,
            'refColumns'        => PTY_ID_PERIOD
        ),
        CLS_LEVELINSTRUCTION => array(
            'columns'           => REG_ID_LEVEL_INSTRUCTION, 
            'refTableClass'     => CLS_LEVELINSTRUCTION,
            'refColumns'        => LIT_ID_LEVEL_INSTRUCTION
        )        
    );
}