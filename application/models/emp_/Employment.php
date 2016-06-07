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

define('EMP_ID_EMPLOYMENT','id_employment');
define('EMP_ID_EMPLOYMENT_STATUS','id_employment_status');
define('EMP_ID_ADDRESS','id_address');
define('EMP_COMPANY_NAME','company_name');
define('EMP_START_DATE','start_date');
define('EMP_END_DATE','end_date');
define('EMP_NUMBER','number');
define('EMP_COMPLEMENT','complement');
define('EMP_REFERENCE_POINT','reference_point');
define('EMP_ID_PERSON','id_person');
define('EMP_ID_INCOME','id_emp_income');
define('EMP_OCCUPATION','occupation');

require_once (CLS_INCOME.".php");
require_once (CLS_EMPLOYMENTSTATUS.".php");
require_once (CLS_ADDRESS.".php");

class Employment extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_EMPLOYMENT;
	protected $_primary = EMP_ID_EMPLOYMENT;
	
	protected $_dependentTables = array(CLS_EMPLOYMENTPHONE);
	
	protected $_referenceMap    = array(
        CLS_INCOME => array(
            'columns'           => EMP_ID_INCOME, 
            'refTableClass'     => CLS_INCOME, 
            'refColumns'        => ICM_ID_EMP_INCOME
        ),
        CLS_EMPLOYMENTSTATUS => array(
            'columns'           => EMP_ID_EMPLOYMENT_STATUS, 
            'refTableClass'     => CLS_EMPLOYMENTSTATUS, 
            'refColumns'        => EMS_ID_EMPLOYMENT_STATUS
        ),
        CLS_ADDRESS => array(
            'columns'           => EMP_ID_ADDRESS, 
            'refTableClass'     => CLS_ADDRESS, 
            'refColumns'        => ADR_ID_ADDRESS
        )
    );

}
