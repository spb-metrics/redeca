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
 * Fabricio Meireles Monteiro  - W3S		    	18/02/2008	                       Create file 
 * 
 */
 
require_once('BasicBusiness.php');

class ClassificationBusiness extends BasicBusiness
{
	/**
	 * Salva um array de registros na tabela EntityClassification
	 *  
	 */
	public static function saveAll($classifications, &$db = NULL)
	{
		$affected = 0;
		if($classifications != false && is_array($classifications))
		{
			foreach($classifications as $current)
			{
				$id = self::saveClassification($current, $db);
				if($id > 0)
					$affected++;
			}	
		}
		return $affected;
	}

	/**
	 * Insere ou edita um registro da tabela EntityClassificationType
	 * Se passar o segundo parâmetro (conexão), o método não 
	 * efetua o commit no final (assume que quem chama tem o 
	 * controle transacional)
	 * 
	 */
	public static function save($classification, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
	
		try
		{	
			$classificationObj = new EntityClassificationType();
			if($classification[ECT_ID_ENTITY_CLASSIFICATION] == false)
			{
				$insertedId = $classificationObj->insert($classification);				
				Logger::loggerOperation('Nova classificação adicionada. [id='.$insertedId.']');
			}
			else
			{
				$where = ECT_ID_ENTITY_CLASSIFICATION . ' = ' . $classification[ECT_ID_ENTITY_CLASSIFICATION];
				$classificationObj->update($classification, $where);
				Logger::loggerOperation('Classificação modificada. [id='.$classification[ECT_ID_ENTITY_CLASSIFICATION].']');
			}
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->classification->error->failDB, E_USER_ERROR);
		}
	}

	/**
	 * Insere ou edita um registro da tabela EntityClassification
	 * Se passar o segundo parâmetro (conexão), o método não 
	 * efetua o commit no final (assume que quem chama tem o 
	 * controle transacional)
	 */
	public static function saveClassification($classification, &$db=null)
	{	
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{	
			$obj = new EntityClassification();
			
			$rows = count($obj->find($classification[ENC_ID_ENTITY], $classification[ENC_ID_ENTITY_CLASSIFICATION]));
			
			if($rows == 0)
			{	
				$ret = $obj->insert($classification);
				
				Logger::loggerOperation('Nova classificação adicionada. '. 
					' ['.ENC_ID_ENTITY.'='.$classification[ENC_ID_ENTITY].']'.
					' ['.ENC_ID_ENTITY_CLASSIFICATION.'='.$classification[ENC_ID_ENTITY_CLASSIFICATION].']');
			}
			else
			{		
				$where[] = $obj->getAdapter()->quoteInto(ENC_ID_ENTITY.' = ?', $classification[ENC_ID_ENTITY]);
				$where[] = $obj->getAdapter()->quoteInto(ENC_ID_ENTITY_CLASSIFICATION.' = ?', $classification[ENC_ID_ENTITY_CLASSIFICATION]);

				$ret = $obj->update($classification, $where);
				if($ret > 0)
					Logger::loggerOperation('Registro modificado na tabela '.TBL_ENTITY_CLASSIFICATION.
						' ['.ENC_ID_ENTITY.'='.$classification[ENC_ID_ENTITY].']'.
						' ['.ENC_ID_ENTITY_CLASSIFICATION.'='.$classification[ENC_ID_ENTITY_CLASSIFICATION].']');
				else
					Logger::loggerOperation('Nenhum Registro modificado na tabela '.TBL_ENTITY_CLASSIFICATION);
				
			}
			if($mt) $db->commit();
			return $ret;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' '. $e);
			trigger_error(parent::getLabelResources()->classification->error->failDB, E_USER_ERROR);
		}
	}

	/**
	 * Carrega do banco uma ou mais classificações
	 */
	public static function load($id)
	{
		$table = new EntityClassificationType();
		
		try
		{
			if(!empty($id))
			{
				$where[] = $table->getAdapter()->quoteInto(ECT_ID_ENTITY_CLASSIFICATION.' in (?)', $id);			
				return $table->fetchAll($where);
			}
			Logger::loggerOperation('Nenhuma classificação encontrada para '.ECT_ID_ENTITY_CLASSIFICATION.' = '.implode(',' ,$id));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->classification->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadByName($name)
	{
		$table = new EntityClassificationType();
		
		try
		{
			$where[] = $table->getAdapter()->quoteInto(ECT_ENTITY_CLASSIFICATION.' = ?', $name);			
			return $table->fetchRow($where);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->classification->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneDisableClassification($id)
	{
		Zend_Loader::loadClass('EntityClassificationType');
		$table = new EntityClassificationType();
		
		try
		{
			if(!empty($id))
			{
				$where[] = $table->getAdapter()->quoteInto(ECT_ID_ENTITY_CLASSIFICATION.' in (?)', $id);			
				$where[] = $table->getAdapter()->quoteInto(ECT_STATUS.' not in (?)', Constants::DISABLE);
				return $table->fetchAll($where);
			}
			Logger::loggerOperation('Nenhuma classificação encontrada para '.ECT_ID_ENTITY_CLASSIFICATION.' = '.implode(',' ,$id));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->classification->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os registros da tabela "EntityClassificationType" - Classificação
	 * 
	 */
	public static function loadAll()
	{
		Zend_Loader::loadClass('EntityClassificationType');
		$table	= new EntityClassificationType();
	
		try
		{	
			return $table->fetchAll(null, ECT_ENTITY_CLASSIFICATION);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->classification->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadAllEnable()
	{
		Zend_Loader::loadClass('EntityClassificationType');
		$table	= new EntityClassificationType();
	
		try
		{	
			$where = $table->getAdapter()->quoteInto(ECT_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, ECT_ENTITY_CLASSIFICATION);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->classification->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui uma ou mais tipos de classificação do banco de dados (ClassificationType)
	 */
	public static function drop($id, &$db = null)
	{
		if($db == NULL)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$table = new EntityClassificationType();
			$res = 0;
			if(!is_null($id) || !empty($id))
			{
				$where = $table->getAdapter()->quoteInto(ECT_ID_ENTITY_CLASSIFICATION.' in (?)', $id);
				$classification[ECT_STATUS] = Constants::DISABLE;
				$res = $table->update($classification, $where);

				if($mt) $db->commit();
			}
			(is_array($id))? $ids = implode(',',$id): $ids = $id;
			if($res > 0)
				Logger::loggerOperation('A(s) classificação(ões) com '.ECT_ID_ENTITY_CLASSIFICATION.' = '.$ids. " foi(foram) excluída(s).");
			else
				Logger::loggerOperation('Nenhum registro foi excluído');
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->classification->drop->fail, E_USER_ERROR);
		}
	}
	
	/**------------------------------------------------
	 * Exclui uma ou mais classificações do banco de dados
	 *------------------------------------------------*/
	public static function deleteClassificationByEntity($id, &$db = null)
	{
		Zend_Loader::loadClass('EntityClassification');
		if($db == NULL)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$table = new EntityClassification();
			$res = 0;
			if(!is_null($id) && !empty($id))
			{
				$where = $table->getAdapter()->quoteInto(ENC_ID_ENTITY.' in (?)', $id);
				$res = $table->delete($where);

				if($mt) $db->commit();
			}
			(is_array($id))? $ids = implode(',',$id): $ids = $id;
			if($res > 0)
				Logger::loggerOperation('A(s) classificação(ões) com '.ENC_ID_ENTITY.' = '. $ids . " foi(foram) excluída(s).");
			else
				Logger::loggerOperation('Nenhum registro foi excluído na tabela '.TBL_ENTITY_CLASSIFICATION);
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' '. $e);
			trigger_error(parent::getLabelResources()->classification->drop->fail, E_USER_ERROR);
		}
	}
}
?>
