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

define('ICM_ID_EMP_INCOME',	'id_emp_income');
define('ICM_ID_PERSON',		'id_person');
define('ICM_ID_INCOME',		'id_income');
define('ICM_REGISTER_DATE',	'register_date');
define('ICM_VALUE',			'value');
define('ICM_STATUS',		'status');

require_once (CLS_PERSON.".php");
require_once (CLS_INCOMETYPE.".php");

class Income extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_INCOME;
	protected $_primary = array(ICM_ID_EMP_INCOME);
	
	protected $_dependentTables = array(CLS_EMPLOYMENT);
	
	protected $_referenceMap    = array(
        CLS_PERSON => array(
            'columns'           => ICM_ID_PERSON, 
            'refTableClass'     => CLS_PERSON, 
            'refColumns'        => PRS_ID_PERSON
        ),
        CLS_INCOMETYPE => array(
            'columns'           => ICM_ID_INCOME, 
            'refTableClass'     => CLS_INCOMETYPE, 
            'refColumns'        => ICT_ID_INCOME
        )
    );

}
