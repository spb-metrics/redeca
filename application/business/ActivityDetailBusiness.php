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
 * Fabricio Meireles Monteiro  - W3S         		28/04/2008                         Create file 
 * 
 */
require_once('BasicBusiness.php');

class ActivityDetailBusiness extends BasicBusiness
{
	/**
	 * Insere ou edita um detalhamento de atividade
	 * @param Activitydetail $activityDetail
	 * @param Connection $db - objeto contendo a conexão
	 */
	public static function save($activityDetail, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$dtActObj = new ActivityDetail();

			if($activityDetail[ACD_ID_ACTIVITY_DETAIL] == false)
			{
				$insertedId = $dtActObj->insert($activityDetail);
				Logger::loggerOperation('Novo detalhamento de atividade adicionado. [id='.$insertedId.']');
			}
			else
			{
				$where = ACD_ID_ACTIVITY_DETAIL . ' = ' . $activityDetail[ACD_ID_ACTIVITY_DETAIL];
				$dtActObj->update($activityDetail, $where);
				Logger::loggerOperation('Detalhamento de atividade modificado. [id='.$activityDetail[ACD_ID_ACTIVITY_DETAIL].']');
			}
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityclassdetail->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todas as atividades detalhadas de uma entidade especifica 
	 */
	public static function loadAllByEntity($idEntity = null)
	{
		try
		{
			if($idEntity != null)
			{	
				Zend_Loader::loadClass(CLS_ACTIVITYDETAIL);
				Zend_Loader::loadClass(CLS_PROGRAM);
				
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$select = $db->select()
		    		->from(array('tad' => TBL_ACTIVITY_DETAIL ))
		    		->joinInner(array('prg' => TBL_PROGRAM),
		        		'prg.'. PGR_ID_PROGRAM .' = tad.'. ACD_ID_PROGRAM)
					->where('prg.'. PGR_ID_ENTITY .' = ?', $idEntity);
			
				$resultDB = $db->fetchAll($select);
				
				if(sizeof($resultDB) > 0)
				{	
					foreach($resultDB as $rdb)
					{	
						$iad[] = $rdb->{ACD_ID_ACTIVITY_DETAIL};
					}
					
					return self::findActivityDetail($iad);
				}
				
				return null;
			}
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityclassdetail->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadByEntityAndName($idEntity, $name)
	{
		try
		{
			Zend_Loader::loadClass(CLS_ACTIVITYDETAIL);
			Zend_Loader::loadClass(CLS_PROGRAM);
			
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->setFetchMode(Zend_Db::FETCH_OBJ);
			
			$select = $db->select()
	    		->from(array('tad' => TBL_ACTIVITY_DETAIL ))
	    		->joinInner(array('prg' => TBL_PROGRAM),
	        		'prg.'. PGR_ID_PROGRAM .' = tad.'. ACD_ID_PROGRAM)
				->where('prg.'. PGR_ID_ENTITY .' = ?', $idEntity)
				->where('tad.'. ACD_ACTIVITY_DETAIL .' = ?', $name);		
		
			return $db->fetchAll($select);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityclassdetail->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega uma ou mais atividades detalhadas do banco de dados 
	 */
	public static function findActivityDetail($idDetailActivity)
	{
		try
		{
			$table = new ActivityDetail();
				
			if(!empty($idDetailActivity))
			{
				$resultSearch = $table->find($idDetailActivity); 
				
				return $resultSearch;
			}
			
			return null;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityclassdetail->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os detalhamentos de atividade persistidos em "act_activity_class" dado o "id_activity_detail"
	 */
	public static function loadActivityClass($idActivityDetail)
	{	
		try
		{	
			Zend_Loader::loadClass('ActivityClass');
			
			$type = new ActivityClass();
			
			if(!empty($idActivityDetail))
			{
				$where[ACC_ID_ACTIVITY_DETAIL. ' = ?'] = $idActivityDetail;
			
				return $type->fetchAll($where);
			}
			
			return null;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityclass->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui um detalhamento de atividade
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
			$table = new ActivityDetail();
			$res = 0;
			if(!is_null($id) || !empty($id))
			{
				$where = $table->getAdapter()->quoteInto(ACD_ID_ACTIVITY_DETAIL.' in (?)', $id);
				
				$res = $table->delete($where);

				if($mt) $db->commit();
			}
			else
			{
				trigger_error(parent::getLabelResources()->activityclassdetail->invalidId->fail, E_USER_ERROR);
			}
			
			if($res > 0)
			{
				Logger::loggerOperation('A atividade detalhada de id '.$id. " foi excluída.");
			}
			else
			{
				Logger::loggerOperation('Não foi possível excluir registro da tabela act_activity_detail ');
			}
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityclassdetail->drop->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui uma ou mais atividades detalhadas cadastrados
	 */
	public static function dropProgramByIdActivityDetail($idActivityDetail, &$db = null)
	{
		if($db == NULL)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{	
			if($idActivityDetail != null)
			{
				$actClass = ClassBusiness::loadAllActivityByActivityDetail($idActivityDetail);
				if($actClass != null && sizeof($actClass) <= 0)
				{
					$res = self::drop($idActivityDetail, $db);
				}
			}
			
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{	
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityclassdetail->drop->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui todos os relacionamentos envolvendo detalhamento de atividade, inclusive o detalhamento
	 */
	 
//	método não mais utilizado porque registros não serão excluídos
//	public static function dropAllRelationWithActivityDetail($idActivityDetail, $skipFlowUpdateInAssistance = FALSE, &$db = null)
//	{	
//		if($db == NULL)
//		{
//			$db = Zend_Registry::get(DB_CONNECTION);
//			$db->beginTransaction();
//			$mt = true;
//		}
//		
//		try
//		{
//			$collObjectActClass = self::loadActivityClass($idActivityDetail);
//			foreach($collObjectActClass as $oac)
//			{
//				if(strlen($oac->{ACC_END_DATE}) <= 0 || $oac->{ACC_END_DATE} == null)
//				{	
//					$flagNotDropActivityDetail = TRUE; 
//					break;
//				}
//			}
//				
//			if($flagNotDropActivityDetail == FALSE)
//			{
//				$flagNotRepeat = FALSE;
//				
//				foreach($collObjectActClass as $oac)
//				{	
//					$idClass = $oac->findParentRow(CLS_CLASSMODEL)->{CLS_ID_CLASS};
//						
//					$collObjActClass = ClassBusiness::loadAllActivityByActivityDetail($idActivityDetail);
//					if($collObjActClass && sizeof($collObjActClass) > 0)
//					{	
//						if($flagNotRepeat == FALSE)
//						{
//							foreach($collObjActClass as $uniqueObject)
//							{	
//								$idActClass = $uniqueObject->{ACC_ID_ACTIVITY_CLASS};
//								ClassBusiness::dropActivityClass($idActClass, $db);
//								
//								//constróe um array com os "id" de todas as turmas que têm o id_activity_detail em questão 
//								$arrayObjectActDetail[] = $uniqueObject->{ACC_ID_CLASS};
//							}
//						}
//						
//						$objectActDetail[] = $idClass;
//						
//						//retira os valores repetidos do array
//						$collIdClass = array_unique($arrayObjectActDetail);
//						
//						if($collIdClass == $objectActDetail)
//						{	
//							if(strlen($oac->findParentRow(CLS_CLASSMODEL)->{CLS_END_DATE}) > 0 || $oac->findParentRow(CLS_CLASSMODEL)->{CLS_END_DATE} != null)
//							{
//								$loadAssClass = ClassBusiness::loadAllAssistanceByClass($idClass);
//								if($loadAssClass && sizeof($loadAssClass) > 0)
//								{	
//									
//									if($skipFlowUpdateInAssistance == FALSE)
//									{	
//										ClassBusiness::closeAssistanceByClass($idClass);
//									}
//									ClassBusiness::dropClassAssistance($idClass, $db);	
//								}
//								
//								ClassBusiness::drop($idClass, $db);
//							}
//						}
//						
//						$flagNotRepeat = TRUE;
//					}
//				}
//				
//				self::drop($idActivityDetail, $db);
//			}
//			
//			if($mt) $db->commit();
//		}
//		catch(Zend_Exception $e)
//		{
//			$db->rollback();
//			$db->closeConnection();
//			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
//			trigger_error(parent::getLabelResources()->dropRelationActivityDetail->fail, E_USER_ERROR);
//		}
//	}
}