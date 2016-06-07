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

define('EAS_ID_ASSISTANCE','id_assistance');
define('EAS_ID_OFFICIAL_LETTER_ORIGIN','id_official_letter_origin');
define('EAS_ID_LAWSUIT_ORIGIN','id_lawsuit_origin');
define('EAS_OFFICIAL_LETTER_NUMBER','official_letter_number');
define('EAS_OFFICIAL_LETTER_YEAR','official_letter_year');
define('EAS_LAWSUIT_NUMBER','lawsuit_number');
define('EAS_LAWSUIT_YEAR','lawsuit_year');
define('EAS_LAWSUIT_DETAIL','lawsuit_detail');
define('EAS_RULING','ruling');

require_once (CLS_OFFICIALLETTERORIGIN.".php");
require_once (CLS_LAWSUITORIGIN.".php");

class EspecialAssistance extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_ESPECIAL_ASSISTANCE;
	protected $_primary = EAS_ID_ASSISTANCE;
	
	protected $_dependentTables = array(CLS_ASSISTANCE);
	
	protected $_referenceMap    = array(
        CLS_OFFICIALLETTERORIGIN => array(
            'columns'           => EAS_ID_OFFICIAL_LETTER_ORIGIN, 
            'refTableClass'     => CLS_OFFICIALLETTERORIGIN, 
            'refColumns'        => OLO_ID_OFFICIAL_LETTER_ORIGIN
        ),
        CLS_LAWSUITORIGIN => array(
            'columns'           => EAS_ID_LAWSUIT_ORIGIN, 
            'refTableClass'     => CLS_LAWSUITORIGIN, 
            'refColumns'        => LWO_ID_LAWSUIT_ORIGIN
        )
    );

}
