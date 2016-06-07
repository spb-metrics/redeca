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
 * Fabricio Meireles Monteiro  - W3S		    	01/04/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class HistoryBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $class - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($changeHistory, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{	
			Zend_Loader::loadClass('PersonChangeHistory');
			$obj = new PersonChangeHistory();
			if($changeHistory[PCH_ID_CHANGE_HISTORY] == false)
			{
				$id = $obj->insert($changeHistory);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_PERSON_CHANGE_HISTORY .' [id='.$id.']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{	
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->changehistory->save->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega um registro dado ID PersonChangeHistory
	 * @param Integer $idHistory - representa a PrimaryKey
	 */
	public static function loadHistory($idHistory)
	{
		Zend_Loader::loadClass('PersonChangeHistory');
		$obj = new PersonChangeHistory();
		try
		{
			if(!empty($idHistory))
			{
				$res 	= $obj->find($idHistory)->current();
			}

			return $res;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->changehistory->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega um registro dado um array de filtro
	 * @param Integer $idResource - id do resource
	 * @param Integer $start - posição do registro de inicio para paginação
	 * @param Integer $limit - quantidade de registros que deve-se recuperar para paginação
	 */
	public static function loadByQuery($query, $start=NULL, $limit=NULL)
	{
		Zend_Loader::loadClass('PersonChangeHistory');
		$obj = new PersonChangeHistory();
		try
		{
			if(!empty($query))
			{
				if(is_array($query))
				{
					$where = NULL;
					foreach($query as $k => $v)
					{
						$where[] 	= $obj->getAdapter()->quoteInto($k, $v);
					}
				}
				
				$res 	= $obj->fetchAll($where, $order=NULL, $limit, $start);
			}

			return $res;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->changehistory->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega os dados de uma tabela dado o nome e a chave desta.
	 * @param String $class - Nome da classe que representa a tabela desejada
	 * @param integer $id - Identificador da tabela com o valor que se pretende recuperar
	 */
	public static function loadByTableName($class, $id)
	{
		if(!empty($class) && !empty($id))
		{
			try
			{
				Zend_Loader::loadClass($class);
				$obj = new $class();
				return $obj->find($id)->current();
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().'\n'.$e);
				trigger_error(parent::getLabelResources()->changehistory->load->fail, E_USER_ERROR);
			}
		}
	}
}