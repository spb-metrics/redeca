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
 * Jefferson Barros Lima  - W3S		   				24/04/2008	                       Create file 
 * 
 */

require_once (CLS_REPRESENTATIVE.'.php');
require_once (CLS_FAMILY_ID.'.php');
require_once (CLS_FAMILY.'.php');
require_once (CLS_KINSHIPTYPE.'.php');

require_once ('BuilderQueryAbstract.php');

class FamilyBuilderQuery extends BuilderQueryAbstract
{
	private static $instance;
	
	protected static $tables = array(
		CLS_REPRESENTATIVE => array(
				parent::_PREFIX => parent::REPRESENTATIVE_PREFIX,
				self::_MAIN_TABLE_KEY => TRUE,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_FAMILY_ID => array(
										parent::REPRESENTATIVE_PREFIX => REP_ID_FAMILY, 
										parent::FAMILYID_PREFIX => FID_ID_FAMILY)
										)
				),
		CLS_FAMILY_ID => array(
				parent::_PREFIX => parent::FAMILYID_PREFIX, 
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_FAMILY => array(
										parent::FAMILYID_PREFIX => FID_ID_FAMILY, 
										parent::FAMILY_PREFIX => FAM_ID_FAMILY)
										)
				),
		CLS_FAMILY => array(
				parent::_PREFIX => parent::FAMILY_PREFIX,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_KINSHIPTYPE => array(
										parent::FAMILY_PREFIX => FAM_ID_KINSHIP, 
										parent::KINSHIPTYPE_PREFIX => KST_ID_KINSHIP)
										)
				),
		CLS_KINSHIPTYPE => array(parent::_PREFIX => parent::KINSHIPTYPE_PREFIX)
	);
	
	private function __construct(){ /* Classe N�o pode ser instanciada fora da classe */ }
	
	
	public static function getInstance()
	{
        if (!isset(self::$instance))
        {
        	self::$instance = new FamilyBuilderQuery();
    	}
    	return self::$instance;
    }
    
	public function getTableMap()
	{
		return self::$tables;
	}
	
	public function build($filter=null, $order=null)
	{
		$sql = $this->getBuildQuery();
//		$sql[self::_WHERE_KEY] = self::PERSON_PREFIX.'.'.PRS_ID_MARITAL_STATUS.' is null';
		return $sql;
	}
}
