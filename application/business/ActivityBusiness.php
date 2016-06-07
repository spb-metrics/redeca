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
 * Jefferson Barros Lima  - W3S         			18/02/2008                         Create file 
 * 
 */
require_once('BasicBusiness.php');

class ActivityBusiness extends BasicBusiness
{
	/**
	 * Insere ou edita uma atividade
	 * @param Category $category - objeto category
	 * @param Connection $db - objeto contendo a conexão
	 */
	public static function save($category, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$categoryObj = new Category();

			if(Utils::isEmpty($category[CAT_ID_CATEGORY]))
			{
				$insertedId = $categoryObj->insert($category);
				Logger::loggerOperation('Nova atividade adicionada. [id='.$insertedId.']');
			}
			else
			{
				$where = CAT_ID_CATEGORY . ' = ' . $category[CAT_ID_CATEGORY];
				$categoryObj->update($category, $where);
				Logger::loggerOperation('Atividade modificada. [id='.$category[CAT_ID_CATEGORY].']');
			}
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->save->fail, E_USER_ERROR);
		}
	}
	/**
	 * Carrrega Atividades por Id
	 */
	public static function load($idActivity)
	{
		$table = new Category();
		try
		{
			if(!empty($idActivity))
			{
				return $table->find($idActivity);
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.CAT_ID_CATEGORY.' = '.implode(',' ,$idActivity));
			return ;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega todos os registros de uma atividade
	 * 
	 * $start : Inicia a consulta do registro n. $start
	 * $limit : Total de registros à serem recuperados
	 * 
	 */
	public static function loadAll()
	{
		$table = new Category();
		try
		{
			return $table->fetchAll(null, CAT_CATEGORY);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadCategory($id)
	{
		$table = new Category();
		try
		{
			$where[] = $table->getAdapter()->quoteInto(CAT_ID_CATEGORY.' in (?)', $id);
			$where[] = $table->getAdapter()->quoteInto(CAT_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchRow($where, CAT_CATEGORY);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->load->fail, E_USER_ERROR);
		}
	}

	public static function loadCategoryByName($name)
	{
		$table = new Category();
		try
		{
			$where[] = $table->getAdapter()->quoteInto(CAT_CATEGORY.' = ?', $name);
			return $table->fetchRow($where, CAT_CATEGORY);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadMacroActivities()
	{		
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		try
		{
			$select = $db->select()->from(TBL_CATEGORY)
								   ->where(CAT_ID_CATEGORY_FATHER . ' is null')
								   ->order(CAT_CATEGORY);
									
			$res = $db->fetchAll($select);
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->load->fail, E_USER_ERROR);
		}
	}
	/**
	 * Carrega todas as subcategorias
	 */
	public static function loadActivities()
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		try
		{
			$select = $db->select()->from(TBL_CATEGORY)
								   ->where(CAT_ID_CATEGORY_FATHER . ' is not null')
								   ->order(CAT_CATEGORY);

			$res = $db->fetchAll($select);
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadActivitiesEnable()
	{
		$db = Zend_Registry::get(DB_CONNECTION);
		$db->setFetchMode(Zend_Db::FETCH_OBJ);

		try
		{
			$select = $db->select()->from(TBL_CATEGORY)
								   ->where(CAT_ID_CATEGORY_FATHER . ' is not null')
								   ->where(CAT_STATUS.' not in ("'.Constants::DISABLE.'")')
								   ->order(CAT_CATEGORY);
			
			$res = $db->fetchAll($select);

			return $res; 
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activity->load->fail, E_USER_ERROR);
		}
	}
	
	public static function delete($idActivity, &$db = null)
	{
		if($db == NULL)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$table = new Category();
			$res = 0;
			if(!is_null($idActivity) || !empty($idActivity))
			{
				$where = $table->getAdapter()->quoteInto(CAT_ID_CATEGORY.' in (?)', $idActivity);
				$activity[CAT_STATUS] = Constants::DISABLE;
				
				$res = $table->update($activity, $where);

				if($mt) $db->commit();
			}
			if($res > 0)
				Logger::loggerOperation('A(s) atividade(s) com '.CAT_ID_CATEGORY.' = '. implode(',' ,$idActivity). " foi(foram) excluída(s).");
			else
				Logger::loggerOperation('Nenhum registro foi excluído');
			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			if(PublicFunctions::verifyParentRowCascadeError($e->getMessage()))
			{
				return parent::getLabelResources()->activity->cascade->error;
			}
			$db->rollback();
			$db->closeConnection();
			trigger_error(parent::getLabelResources()->activity->delete->fail, E_USER_ERROR);
		}
	}

	public static function associate($activity, &$db = null)
	{
		if(!empty($activity))
		{
			if($db == NULL)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$mt = true;
			}
			try
			{
				/* Carrega todas as atividades que serão alteradas */
				$activities = ActivityBusiness::load($activity[CAT_ID_CATEGORY]);

				$table = new Category();
				$data = array();
				foreach($activities as $value)
				{
					$data[CAT_CATEGORY]				= $value->{CAT_CATEGORY};
					$data[CAT_ID_CATEGORY_FATHER]	= $activity[CAT_ID_CATEGORY_FATHER];
					$where = $table->getAdapter()->quoteInto(CAT_ID_CATEGORY.' = ?', 
								$value->{CAT_ID_CATEGORY});
					$table->update($data, $where);
				}
				Logger::loggerOperation('A(s) atividade(s) com '.CAT_ID_CATEGORY.' = '. 
						implode(',' ,$activity[CAT_ID_CATEGORY]). " foi(foram) associada(s) à Macro atividade '".$activity[CAT_ID_CATEGORY_FATHER]."'");
				if($mt) $db->commit();
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->activity->associate->fail, E_USER_ERROR);
			}
		}
	}
	
	/**
	 * Atualiza um relacionamento entre atividades e turma 
	 */
	public static function updateActivityClass($idActivityClass, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
	
		try
		{	
			$data = array();
			$data[ACC_END_DATE] = date("Y-m-d");
			
			$obj = new ActivityClass();
		
			$where[] = $obj->getAdapter()->quoteInto(ACC_ID_ACTIVITY_CLASS.' = ?', $idActivityClass);
			$where[] = $obj->getAdapter()->quoteInto(ACC_END_DATE.' is null', null);
			
			if($idActivityClass)
			{
				$obj->update($data, $where);
				Logger::loggerOperation('Relacionamento entre turma e atividade de [id = '.$idActivityClass.'] atualizado com sucesso.');
			}
			
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityclass->error->failDB, E_USER_ERROR);
		}
	}
}