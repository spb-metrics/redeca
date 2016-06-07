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
 * Fabricio Meireles Monteiro  - W3S		   		28/02/2008	                       Create file 
 * 
 */

define('AGR_RESOURCE_ID',				'role_resource_id_resource');
define('AGR_ROLE_ID',					'role_resource_id_role');
define('AGR_ID_GROUP',					'id_group');
define('AGR_READONLY',					'readonly');
define('AGR_CHANGE_CONFIDENTIALITY',	'change_confidentiality');
define('AGR_DEFAULT_CONFIDENTIALITY',	'default_confidentiality');

define('CONFIDENTIALITY_ENTITY', 		'E');
define('CONFIDENTIALITY_PROFILE', 		'P');
define('CONFIDENTIALITY_PRIVATE', 		'R');

require_once (CLS_AUTH_ROLE_RESOURCE.".php");
require_once (CLS_AUTH_GROUP.".php");

class GroupResource extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_AUTH_GROUP_RESOURCE;
	protected $_primary = array(AGR_RESOURCE_ID,AGR_ROLE_ID,AGR_ID_GROUP); 
	
	protected $_referenceMap    = array(
        CLS_AUTH_ROLE_RESOURCE => array(
            'columns'           => AGR_ROLE_ID,
            'refTableClass'     => CLS_AUTH_ROLE_RESOURCE,
            'refColumns'        => ARR_ID_RESOURCE
        ),
        CLS_AUTH_GROUP => array(
            'columns'           => AGR_ID_GROUP,
            'refTableClass'     => CLS_AUTH_GROUP,
            'refColumns'        => AGP_ID_GROUP
        )
    );
}
