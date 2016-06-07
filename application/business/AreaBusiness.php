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
 * Lucas dos Santos Borges Corrêa  - W3S		    06/03/2008	                       Create file 
 * 
 */
require_once('BasicBusiness.php');

class AreaBusiness extends BasicBusiness
{
	/**
	 * Salva um array de registros na tabela EntityArea
	 *  
	 */
	public static function saveAll($areas, &$db = NULL)
	{
		$affected = 0;
		if($areas != false && is_array($areas))
		{
			foreach($areas as $area)
			{
				$id = self::saveArea($area, $db);
				if($id > 0)
					$affected++;
			}	
		}
		return $affected;
	}
	
	/**
	 * Carrega um registro
	 * 
	 */
	public static function load($id)
	{
		$table = new EntityAreaType();
		
		try
		{
			if(!empty($id))
			{
				return $table->find($id);
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.EAT_ID_ENTITY_AREA.' = '.implode(',' ,$id));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadByName($name)
	{
		$table = new EntityAreaType();
		
		try
		{
			$where[] = $table->getAdapter()->quoteInto(EAT_ENTITY_AREA.' = ?', $name);			
			return $table->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega todos os registros de EntityAreaType
	 * 
	 */
	public static function loadAll()
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		
		try
		{
			$select = $db->select()->from(TBL_ENTITY_AREA_TYPE)
								   ->order(EAT_ENTITY_AREA);

			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}

	public static function loadAllEnable()
	{
		Zend_Loader::loadClass('EntityAreaType');
		$table = new EntityAreaType();
		
		try
		{
			$where = $table->getAdapter()->quoteInto(EAT_STATUS.' not in (?)', Constants::DISABLE);
			$order = EAT_ENTITY_AREA;
			return $table->fetchAll($where, $order);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Salva registros na tabela EntityAreaType
	 */
	public static function save($area, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		$data = array
		(
			EAT_ENTITY_AREA	  	=> $area->areaName,
			EAT_STATUS		  	=> $area->status
		);

		try
		{
			if($area->id == false)
			{
				$insertedId = $area->insert($data);
				Logger::loggerOperation('Nova área adicionada. [id='.$insertedId.']');
			}
			else
			{
				$where = EAT_ID_ENTITY_AREA . ' = ' . $area->id;
				$area->update($data, $where);
				Logger::loggerOperation('Área modificado. [id='.$area->id.']');
			}
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->save->fail, E_USER_ERROR);
		}
	}
	/**
	 * Salva um array que representa a tabela EntityArea
	 */
	public static function saveArea($area, &$db=null)
	{	
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new EntityArea();
			$rows = count($obj->find($area[ETA_ID_ENTITY], $area[ETA_ID_ENTITY_AREA]));

			if($rows == 0)
			{
				$ret = $obj->insert($area);
				Logger::loggerOperation('Nova área adicionada. [id='.implode(',', $ret).']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(ETA_ID_ENTITY.' = ?', $area[ETA_ID_ENTITY]);
				$where[] = $obj->getAdapter()->quoteInto(ETA_ID_ENTITY_AREA.' = ?', $area[ETA_ID_ENTITY_AREA]);
				$ret = $obj->update($area, $where);
				
				if($ret > 0)
					Logger::loggerOperation('Registro modificado na tabela '.TBL_ENTITY_AREA.
						' ['.ETA_ID_ENTITY.'='.$area[ETA_ID_ENTITY].']'.
						' ['.ETA_ID_ENTITY_AREA.'='.$area[ETA_ID_ENTITY_AREA].']');
				else
					Logger::loggerOperation('Nenhum Registro modificado na tabela '.TBL_ENTITY_AREA);
			}
			if($mt) $db->commit();
			return $ret;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' '. $e);
			trigger_error(parent::getLabelResources()->area->save->fail, E_USER_ERROR);
		}
	}
	/**
	 * Exclui um ou mais registros da tabela EntityAreaType
	 */
	public static function drop($id, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$areap = new EntityAreaType();
			$res = 0;
			if(!is_null($id) || !empty($id))
			{
				$where = $areap->getAdapter()->quoteInto(EAT_ID_ENTITY_AREA.' in (?)', $id);
				$area[EAT_STATUS] = Constants::DISABLE;
				$res = $areap->update($area, $where);

				if($mt) $db->commit();
			}
			if($res > 0)
				Logger::loggerOperation('A(s) area(as) com '.EAT_ID_ENTITY_AREA.' = '. implode(',' ,$id). " foi(foram) excluído(s).");
			else
				Logger::loggerOperation('Nenhum registro foi excluído');
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->area->drop->fail, E_USER_ERROR);
		}
	}
	
	/**------------------------------------------------
	 * Exclui um ou mais registros da tabela EntityArea
	 *------------------------------------------------*/
	public static function deleteAreaByEntity($id, &$db=null)
	{
		Zend_Loader::loadClass('EntityArea');
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$area = new EntityArea();
			$res = 0;
			if(!is_null($id) && !empty($id))
			{
				$where = $area->getAdapter()->quoteInto(ETA_ID_ENTITY.' in (?)', $id);
				$res = $area->delete($where);

				if($mt) $db->commit();
			}
			(is_array($id))? $ids = implode(',',$id) : $ids = $id;
			if($res > 0)
				Logger::loggerOperation('A(s) area(as) com '.ETA_ID_ENTITY.' = '. $ids . " foi(foram) excluído(s).");
			else
				Logger::loggerOperation('Nenhum registro foi excluído na tabela '.TBL_ENTITY_AREA);
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' '. $e);
			trigger_error(parent::getLabelResources()->area->drop->fail, E_USER_ERROR);
		}
	}
}