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
 * Lucas dos Santos Borges Corrêa  - W3S		   	28/02/2008	                       Create file 
 * 
 */
define('AUTH_ID_USER','id_user');
define('AUTH_ID_ENTITY_USER','id_entity');
define('AUTH_ID_GROUP_USER','id_group');
define('AUTH_ID_ROLE_USER','id_role');
define('AUTH_NAME_USER','name');
define('AUTH_LOGIN_USER','login');
define('AUTH_PASSWORD_USER','pass');
define('AUTH_EMAIL_USER','email');
define('AUTH_CPF_USER','cpf');
define('AUTH_ACTIVE_USER','active');
define('AUTH_CREATION_DATE_USER','dat_creation');
define('AUTH_PERMISSION','permission');

require_once(CLS_ENTITY.".php");
require_once(CLS_AUTH_ROLE.".php");
require_once(CLS_AUTH_GROUP.".php");

class User extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_AUTH_USER;
	protected $_primary = AUTH_ID_USER;
	
	protected $_dependentTables = array(CLS_ASSISTANCE, CLS_AUTH_USER_PROFILE, CLS_PERSONCHANGEHISTORY);
										
	protected $_referenceMap    = array(
        CLS_ENTITY => array(
            'columns'           => AUTH_ID_ENTITY_USER, /*in this table*/
            'refTableClass'     => CLS_ENTITY,   /* Class Name */
            'refColumns'        => ENT_ID_ENTITY /*ref table*/
        ),
        CLS_AUTH_ROLE => array(
            'columns'           => AUTH_ID_ROLE_USER, /*in this table*/
            'refTableClass'     => CLS_AUTH_ROLE,   /* Class Name */
            'refColumns'        => AUTH_ID_ROLE /*ref table*/
        ),
        CLS_AUTH_GROUP => array(
            'columns'           => AUTH_ID_GROUP_USER, /*in this table*/
            'refTableClass'     => CLS_AUTH_GROUP,   /* Class Name */
            'refColumns'        => AGP_ID_GROUP /*ref table*/
        )
    );
}