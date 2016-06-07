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
 * Jefferson Barros Lima  - W3S		    			18/02/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class NeighborhoodBusiness extends BasicBusiness
{
	/**
	 * Insere uma ou vários Bairros
	 * @param Array $neighbours - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return Boolean TRUE se a inserção ocorreu corretamente
	 */
	public static function insert($neighbour, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$neighborhood = new Neighborhood();
			$id = $neighborhood->insert($neighbour);

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->neighborhood->save->fail, E_USER_ERROR);
			return FALSE;
		}
		return FALSE;
	}

	/**
	 * Retorna um Array de objetos Neighborhood dado o nome
	 */
	public static function findByName($neighborhood, &$db = NULL)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Neighborhood();
			if(!empty($neighborhood))
			{
				$where 	= $obj->getAdapter()->quoteInto(NHD_NEIGHBORHOOD.' = ?', $neighborhood);
        		$rows 	= $obj->fetchAll($where);

				if($mt) $db->commit();

				return $rows;
			}
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->neighborhood->load->fail, E_USER_ERROR);
		}
	}
	
	public static function findByQuery($query)
	{
		try
		{
			$obj = new Neighborhood();
			if(!empty($query))
			{
        		$row = $obj->fetchAll($query);

				return $row->current();
			}
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->neighborhood->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Retorna um Array de objetos Neighborhood dado o id
	 */
	public static function findById($id, &$db = NULL)
	{
		try
		{
			$obj = new Neighborhood();
			if(!empty($id))
			{
				$where 	= $obj->getAdapter()->quoteInto(NHD_ID_NEIGHBORHOOD.' = ?', $id);
        		$rows 	= $obj->fetchAll($where);

				return $rows;
			}
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->neighborhood->load->fail, E_USER_ERROR);
		}
	}
	
	public static function findByCity($id)
	{
		try
		{
			$obj = new Neighborhood();
			$where 	= $obj->getAdapter()->quoteInto(NHD_ID_CITY.' in (?)', $id);
    		$rows 	= $obj->fetchAll($where, NHD_NEIGHBORHOOD);

			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->neighborhood->load->fail, E_USER_ERROR);
		}
	}

	public static function loadAll(&$db=null)
	{
		try
		{
			$neighborhood = new Neighborhood();
			$res = $neighborhood->fetchAll();

			return $res;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->neighborhood->load->fail, E_USER_ERROR);
			return FALSE;
		}
		return FALSE;
	}
}