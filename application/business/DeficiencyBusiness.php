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

class DeficiencyBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $deficiency - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($deficiency, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Deficiency();
			$rows = count($obj->find($deficiency[DFY_ID_PERSON], $deficiency[DFY_ID_DEFICIENCY]));
			
			if($rows == 0)
			{
				$id = $obj->insert($deficiency);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_DEFICIENCY .' [id='.$id.']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(DFY_ID_PERSON.' = ?', $deficiency[DFY_ID_PERSON]);
				$where[] = $obj->getAdapter()->quoteInto(DFY_ID_DEFICIENCY.' = ?', $deficiency[DFY_ID_DEFICIENCY]);
				
				$id = $obj->update($deficiency, $where);
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_DEFICIENCY.
					' ['.DFY_ID_PERSON.'='.$deficiency[DFY_ID_PERSON].']'.
					' ['.DFY_ID_DEFICIENCY.'='.$deficiency[DFY_ID_DEFICIENCY].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->deficiency->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos as deficiências
	 */
	public static function loadAll(&$db=null)
	{
		try
		{
			Zend_Loader::loadClass(CLS_DEFICIENCYTYPE);
			$obj = new DeficiencyType();
			
			$where = $obj->getAdapter()->quoteInto(DFT_STATUS.' not in (?)', Constants::DISABLE);
			$rows = $obj->fetchAll($where, DFT_NAME);

			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->deficiency->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadDeficiency($id)
	{
		try
		{
			Zend_Loader::loadClass(CLS_DEFICIENCYTYPE);
			$obj = new DeficiencyType();
			
			$where[] = $obj->getAdapter()->quoteInto(DFT_ID_DEFICIENCY.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(DFT_STATUS.' not in (?)', Constants::DISABLE);
			$rows = $obj->fetchAll($where, DFT_NAME);

			return $rows;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->deficiency->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega um registro
	 * 
	 */
	public static function load($id)
	{
		$obj = new Deficiency();
		try
		{
			$where 	= $obj->getAdapter()->quoteInto(DFY_ID_PERSON.' = ?',$id);
			$res 	= $obj->fetchAll($where);
			return $res;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->deficiency->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui um registro
	 * 
	 * Se passar o segundo parâmetro (conexão), o método não 
	 * efetua o commit no final (assume que quem chama tem o 
	 * controle transacional)
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
			$obj = new Deficiency();
			$where = DFY_ID_PERSON . ' = ' . $id;
			$obj->delete($where);
			if($mt) $db->commit();
			Logger::loggerOperation('Pessoa excluída. [id='.$id.']');
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->deficiency->delete->fail, E_USER_ERROR);
		}
	}
}
