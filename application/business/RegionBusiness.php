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
 * Jefferson Barros Lima  - W3S		    			05/03/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class RegionBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $region - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($region, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Region();
			if($region[RGN_ID_REGION] == false)
			{
				$id = $obj->insert($region);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_REGION .' [id='.$id.']');
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(RGN_ID_REGION.' = ?', $region[RGN_ID_REGION]);
				
				$id = $obj->update($region, $where);
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_REGION.
					' ['.RGN_ID_REGION.'='.$region[RGN_ID_REGION].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->region->save->fail, E_USER_ERROR);
		}
	}

	public static function loadAll(&$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Region();
			$rows = $obj->fetchAll(null, RGN_REGION);

			if($mt) $db->commit();
			
			return $rows;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->region->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadByName($name)
	{
		try
		{
			$obj = new Region();
			$where = $obj->getAdapter()->quoteInto(RGN_REGION.' = ?', $name);
			$rows = $obj->fetchRow($where, RGN_REGION);
		
			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->region->load->fail, E_USER_ERROR);
		}
	}
	
	public static function find($id, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Region();
			$row = $obj->find($id);
			
			if($mt) $db->commit();
			
			return $row->current();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->region->load->fail, E_USER_ERROR);
		}
		return NULL;
	}
	
	public static function delete($idRegion, &$db = null)
	{
		if($db == NULL)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$table = new Region();
			$res = 0;
			if(!is_null($idRegion) || !empty($idRegion))
			{
				$where = $table->getAdapter()->quoteInto(RGN_ID_REGION.' in (?)', $idRegion);
				$res = $table->delete($where);

				if($mt)
				{
					$db->commit();
					$db->closeConnection();
				}
			}
			if($res > 0)
				Logger::loggerOperation('O(s) registro(s) ' .RGN_ID_REGION.' = '. implode(',' ,$idRegion). ' da tabela: '. TBL_NEIGHBORHOOD_REGION.' foi(foram) excluído(s).');
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->region->delete->fail, E_USER_ERROR);
		}
	}
}
