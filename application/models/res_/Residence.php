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

define('RES_ID_RESIDENCE','id_residence');
define('RES_ID_ADDRESS','id_address');
define('RES_NUMBER','number');
define('RES_COMPLEMENT','complement');
define('RES_REFERENCE_POINT','reference_point');
define('RES_ACCOMMODATION','accommodation');
define('RES_ID_MORADA','id_morada');
define('RES_ID_STATUS','id_status');
define('RES_ID_LOCALITY','id_locality');
define('RES_ID_BUILDING','id_building');
define('RES_ID_SUPPLY','id_supply');
define('RES_ID_WATER','id_water');
define('RES_ID_ILLUMINATION','id_illumination');
define('RES_ID_SANITARY','id_sanitary');
define('RES_ID_TRASH','id_trash');
define('RES_STATUS','status');

require_once (CLS_ADDRESS.".php");
require_once (CLS_BUILDINGTYPE.".php");
require_once (CLS_MORADATYPE.".php");
require_once (CLS_LOCALITYTYPE.".php");
require_once (CLS_STATUSTYPE.".php");
require_once (CLS_WATERTYPE.".php");
require_once (CLS_ILLUMINATIONTYPE.".php");
require_once (CLS_SANITARYTYPE.".php");
require_once (CLS_TRASHTYPE.".php");
require_once (CLS_SUPPLYTYPE.".php");

class Residence extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_RESIDENCE;
	protected $_primary = RES_ID_RESIDENCE;
	
	protected $_dependentTables = array(CLS_FAMILYRESIDENCE);
	
	protected $_referenceMap    = array(
        CLS_ADDRESS => array(
            'columns'           => RES_ID_ADDRESS, 
            'refTableClass'     => CLS_ADDRESS, 
            'refColumns'        => ADR_ID_ADDRESS
        ),
        CLS_BUILDINGTYPE => array(
            'columns'           => RES_ID_BUILDING, 
            'refTableClass'     => CLS_BUILDINGTYPE, 
            'refColumns'        => BTP_ID_BUILDING
        ),
        CLS_MORADATYPE => array(
            'columns'           => RES_ID_MORADA, 
            'refTableClass'     => CLS_MORADATYPE, 
            'refColumns'        => MRT_ID_MORADA
        ),
        CLS_LOCALITYTYPE => array(
            'columns'           => RES_ID_LOCALITY, 
            'refTableClass'     => CLS_LOCALITYTYPE, 
            'refColumns'        => LTP_ID_LOCALITY
        ),
        CLS_STATUSTYPE => array(
            'columns'           => RES_ID_STATUS,
            'refTableClass'     => CLS_STATUSTYPE, 
            'refColumns'        => STT_ID_STATUS
        ),
        CLS_WATERTYPE => array(
            'columns'           => RES_ID_WATER, 
            'refTableClass'     => CLS_WATERTYPE, 
            'refColumns'        => WTP_ID_WATER
        ),
        CLS_ILLUMINATIONTYPE => array(
            'columns'           => RES_ID_ILLUMINATION, 
            'refTableClass'     => CLS_ILLUMINATIONTYPE, 
            'refColumns'        => ITP_ID_ILLUMINATION
        ),
        CLS_SANITARYTYPE => array(
            'columns'           => RES_ID_SANITARY,
            'refTableClass'     => CLS_SANITARYTYPE, 
            'refColumns'        => SNT_ID_SANITARY
        ),
        CLS_TRASHTYPE => array(
            'columns'           => RES_ID_TRASH, 
            'refTableClass'     => CLS_TRASHTYPE, 
            'refColumns'        => TST_ID_TRASH
        ),
        CLS_SUPPLYTYPE => array(
            'columns'           => RES_ID_SUPPLY, 
            'refTableClass'     => CLS_SUPPLYTYPE, 
            'refColumns'        => SPT_ID_SUPPLY
        )
    );
}
