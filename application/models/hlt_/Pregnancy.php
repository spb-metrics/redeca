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

define('PRG_ID_PREGNANCY','id_pregnancy');
define('PRG_ID_PERSON','id_person');
define('PRG_PRENATAL_SIS','prenatal_sis');
define('PRG_BEGINNING_PREGNANCY','beginning_pregnancy');
define('PRG_MET','met');
define('PRG_STATUS','status');

require_once (CLS_PERSON.".php");

class Pregnancy extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_PREGNANCY;
	protected $_primary = PRG_ID_PREGNANCY;
	
	protected $_referenceMap    = array(
        CLS_PERSON => array(
            'columns'           => PRG_ID_PERSON, /*in this table*/
            'refTableClass'     => CLS_PERSON,   /* Class Name */
            'refColumns'        => PRS_ID_PERSON /*ref table*/
        )
    );
}