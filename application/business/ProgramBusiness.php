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
 * Fabricio Meireles Monteiro  - W3S		    	19/02/2008	                       Create file 
 * 
 */

	require_once('BasicBusiness.php');
	
	class ProgramBusiness extends BasicBusiness
	{
		/**
		 * Carrega do banco um ou mais programas
		 */
		public static function loadProgram($idEntity, $idProgram = NULL)
		{	
			try
			{	
				Zend_Loader::loadClass('Program');
				$type = new Program();
				if(!empty($idProgram))
				{
					$where = PGR_ID_ENTITY . ' = ' . $idEntity . ' and ' .PGR_ID_PROGRAM . ' = ' . $idProgram;
				}
				else
				{
					$where[PGR_ID_ENTITY. ' = ?'] = $idEntity;
				}
				
				return $type->fetchAll($where);
				
				Logger::loggerOperation('Nenhum programa encontrado para '.$idEntity.' = '.implode(',' ,$idEntity));
				return ;
			}
			catch(Zend_Exception $e)
			{	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->program->load->fail, E_USER_ERROR);
			}
		}
		
		public static function loadEntityProgramType($idEntity, $idProgramType)
		{	
			try
			{	
				Zend_Loader::loadClass('Program');
				$type = new Program();
				
				$where = PGR_ID_ENTITY . ' = ' . $idEntity . ' and ' .PGR_ID_PROGRAM_TYPE . ' = ' . $idProgramType;
				return $type->fetchRow($where);
				
				Logger::loggerOperation('Nenhum programa encontrado para '.$idEntity.' = '.implode(',' ,$idEntity));
				return ;
			}
			catch(Zend_Exception $e)
			{	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->program->load->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Carrega do banco um ou mais programas do tipo grupo
		 */
		public static function loadProgramGroupByEntity($idEntity = null, $idProgram = NULL)
		{	
			try
			{	
				$objectsProgram = self::loadProgram($idEntity, $idProgram);
				
				if(sizeof($objectsProgram) > 0)
				{
					foreach($objectsProgram as $prg)
					{
						$verifyTypeProgram = Utils::getAssistanceClassification($prg->findParentRow(CLS_PROGRAMTYPE)->{PGT_ID_PROGRAM_TYPE});
						if($verifyTypeProgram == Constants::GROUP)
						{	
							$programGroup[] = $prg;
						}
					}
					
					return $programGroup;	
				}
				
				return null;
				Logger::loggerOperation('Nenhum programa encontrado para '.$idEntity.' = '.implode(',' ,$idEntity));
			}
			catch(Zend_Exception $e)
			{	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->program->load->fail, E_USER_ERROR);
			}
		}
		
	/**
	 * Carrega dados de Programa dado um array contendo filtro
	 * $query[nome_da_coluna . ' clausula ' . ' ? '] = 'valor_da_coluna'
	 * EX: $query[nome_da_coluna . ' in ' . ' (?) '] = 'valor_da_coluna'
	 */
	public static function loadByQuery($query)
	{
		$table = new Program();

		try
		{
			if(!empty($query) && is_array($query))
			{
				$where = NULL;
				foreach($query as $k => $v)
					$where[] = $table->getAdapter()->quoteInto($k, $v);
					
				return $table->fetchAll($where);
			}
			return $table->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage(). ' - '.$e);
			trigger_error(parent::getLabelResources()->program->load->fail, E_USER_ERROR);
		}
	}
	
		/**
		 * Insere ou edita um registro
		 * Se passar o segundo parâmetro (conexão), o método não 
		 * efetua o commit no final (assume que quem chama tem o 
		 * controle transacional)
		 * 
		 */
		public static function save($program, &$db=null)
		{
			if($db == null)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$mt = true;
			}
		
			try
			{	
				$programObj = new ProgramType();
				if($program[PGT_ID_PROGRAM_TYPE] == false)
				{	
					$insertedId = $programObj->insert($program);
					Logger::loggerOperation('Novo tipo de programa adicionado. [id='.$insertedId.']');
				}
				else
				{
					$where = PGT_ID_PROGRAM_TYPE . ' = ' . $program[PGT_ID_PROGRAM_TYPE];
					$programObj->update($program, $where);
					Logger::loggerOperation('Tipo de Programa modificado. [id='.$program[PGT_ID_PROGRAM_TYPE].']');
				}
				if($mt) $db->commit();
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->error->failDB, E_USER_ERROR);
			}
		}
		
		/**
		 * Insere ou atualiza um registro da tabela Program
		 * Se passar o segundo parâmetro (conexão), o método não 
		 * efetua o commit no final (assume que quem chama tem o 
		 * controle transacional)
		 * @return Integer retorna o id Inserido ou a quantidade de registros afetados na atualização
		 */
		public static function saveProgram($program, &$db=null)
		{
			if($db == null)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$mt = true;
			}
		
			try
			{
				$obj = new Program();
				if($program[PGR_ID_PROGRAM] == false)
				{	
					$res = $obj->insert($program);
					Logger::loggerOperation('Novo programa adicionado. [id='.$res.']');
				}
				else
				{
					$where = PGR_ID_PROGRAM . ' = ' . $program[PGR_ID_PROGRAM];
					$res = $obj->update($program, $where);
					Logger::loggerOperation('Programa modificado. [id='.$program[PGR_ID_PROGRAM].']');
				}
				if($mt) $db->commit();
				
				return $res;
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->error->failDB, E_USER_ERROR);
			}
		}

		/**
		 * Carrega do banco um ou mais tipos de programas sem desabilitados
		 */
		public static function load($id)
		{
			$table = new ProgramType();
			
			try
			{
				if(!empty($id))
				{					
					$where[] = $table->getAdapter()->quoteInto(PGT_ID_PROGRAM_TYPE.' in (?)', $id);
					$where[] = $table->getAdapter()->quoteInto(PGT_STATUS.' not in (?)', Constants::DISABLE);					
					return $table->fetchAll($where, PGT_PROGRAM_TYPE);					
				}
				Logger::loggerOperation('Nenhum tipo de programa encontrado para '.PGT_ID_PROGRAM_TYPE.' = '.implode(',' ,$id));
				return ;
			}
			catch(Zend_Exception $e)
			{	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->load->programType->fail, E_USER_ERROR);
			}
		}
		
		public static function loadByName($name)
		{
			$table = new ProgramType();
			
			try
			{
				$where[] = $table->getAdapter()->quoteInto(PGT_PROGRAM_TYPE.' = ?', $name);		
				return $table->fetchRow($where, PGT_PROGRAM_TYPE);			
			}
			catch(Zend_Exception $e)
			{	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->load->programType->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Carrega do banco um ou mais tipos de programas incluindo desabilitados
		 */
		public static function loadDisable($id)
		{
			$table = new ProgramType();
			
			try
			{
				if(!empty($id))
				{					
					$where = $table->getAdapter()->quoteInto(PGT_ID_PROGRAM_TYPE.' in (?)', $id);										
					return $table->fetchAll($where, PGT_PROGRAM_TYPE);					
				}
				Logger::loggerOperation('Nenhum tipo de programa encontrado para '.PGT_ID_PROGRAM_TYPE.' = '.implode(',' ,$id));
				return ;
			}
			catch(Zend_Exception $e)
			{	
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->load->programType->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Carrega todos os registros da tabela "Público Alvo"
		 * 
		 */
		public static function loadAllTargetPublic()
		{	
			$table	= new TargetMarket();
		
			try
			{	
				$where = $table->getAdapter()->quoteInto(TMK_STATUS.' not in (?)', Constants::DISABLE);
				return $table->fetchAll($where, TMK_TARGET_MARKET);
			}
			catch(Zend_Exception $e)
			{
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->load->targetPublic->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Carreaga um registro de publico alvo
		 */
		public static function loadTargetPublic($id)
		{
			$table	= new TargetMarket();
		
			try
			{	
				$where[] = $table->getAdapter()->quoteInto(TMK_ID_TARGET_MARKET.' = ?', $id);
				$where[] = $table->getAdapter()->quoteInto(TMK_STATUS.' not in (?)', Constants::DISABLE);
				return $table->fetchAll($where, TMK_TARGET_MARKET);
			}
			catch(Zend_Exception $e)
			{
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->load->targetPublic->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Carrega todos os registros da tabela "Tipo de Programa" sem os desabilitados
		 * 
		 */
		public static function loadAllTypeProgram()
		{	
			Zend_Loader::loadClass('ProgramType');
			$table	= new ProgramType();
		
			try
			{
				$where = $table->getAdapter()->quoteInto(PGT_STATUS.' not in (?)', Constants::DISABLE);
				return $table->fetchAll($where, PGT_PROGRAM_TYPE);
			}
			catch(Zend_Exception $e)
			{
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->load->programType->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Carrega todos os registros da tabela "Tipo de Programa" com os desabilitados
		 * 
		 */
		public static function loadAllTypeProgramDisable()
		{	
			Zend_Loader::loadClass('ProgramType');
			$table	= new ProgramType();
		
			try
			{				
				return $table->fetchAll(null, PGT_PROGRAM_TYPE);
			}
			catch(Zend_Exception $e)
			{
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->load->programType->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Carrega todos os registros da tabela "Tipo de Programa"
		 * 
		 */
		public static function loadProgramType($idProgramType)
		{	
			Zend_Loader::loadClass('ProgramType');
			$table	= new ProgramType();
		
			try
			{
				if(!empty($idProgramType))
				{
					$where[] = $table->getAdapter()->quoteInto(PGT_ID_PROGRAM_TYPE. ' in (?)', $idProgramType);
					$where[] = $table->getAdapter()->quoteInto(PGT_STATUS. ' not in (?)', Constants::DISABLE);
					return $table->fetchAll($where, PGT_PROGRAM_TYPE);
				}
				return null;
			}
			catch(Zend_Exception $e)
			{
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->load->programType->fail, E_USER_ERROR);
			}
		}
		
		public static function loadProgramById($id)
		{	
			Zend_Loader::loadClass('ProgramType');
			$table	= new Program();
		
			try
			{
				$where[] = $table->getAdapter()->quoteInto(PGR_ID_PROGRAM. ' in (?)', $id);
				return $table->fetchRow($where);
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->load->programType->fail, E_USER_ERROR);
			}
		}

		/**
		 * Atualiza um ou mais tipo de programas do banco de dados
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
				$table = new ProgramType();
				$res = 0;
				if(!is_null($id) || !empty($id))
				{
					$program[PGT_STATUS] = Constants::DISABLE;
					$where = $table->getAdapter()->quoteInto(PGT_ID_PROGRAM_TYPE.' in (?)', $id);					
					$res = $table->update($program, $where);
					
					if($mt) $db->commit();
				}
				if($res > 0)
					Logger::loggerOperation('O(s) tipo(s) de programa(s) com '.PGT_ID_PROGRAM_TYPE.' = '. implode(',' ,$id). " foi(foram) excluído(s).");
				else
					Logger::loggerOperation('Nenhum registro foi excluído');
				
				return $res; 
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->typeprogram->drop->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Exclui um ou mais programa cadastrado e seus respectivos relacionamentos
		 */
		public static function dropProgramWhileNotUsed($idEntity, $idProgramType, &$db = null)
		{
			if($db == NULL)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$mt = true;
			}
	
			try
			{
				$table = new Program();
				$res = 0;
				if(!is_null($idEntity) || !empty($idEntity) || !is_null($idProgramType) || !empty($idProgramType))
				{
					$where[] = $table->getAdapter()->quoteInto(PGR_ID_ENTITY.' = ?', $idEntity);
					$where[] = $table->getAdapter()->quoteInto(PGR_ID_PROGRAM_TYPE.' = ?', $idProgramType);
					
					$table->delete($where);
	
					if($mt) $db->commit();
				}
				
				Logger::loggerOperation('O tipo de programa de id = '.PGR_ID_PROGRAM_TYPE.' da entidade de id = '.PGR_ID_ENTITY.' foi excluído.');
				 
			}
			catch(Zend_Exception $e)
			{	
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->program->drop->fail, E_USER_ERROR);
			}
		}
		
		/**
		 * Deleta dados da tabela class_assistance dado um array contendo filtro
		 * $query[nome_da_coluna . ' clausula ' . ' ? '] = 'valor_da_coluna'
		 * EX: $query[nome_da_coluna . ' in ' . ' (?) '] = 'valor_da_coluna'
		 */
		public static function deleteByQuery($query, &$db=null)
		{
			if($db == NULL)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$mt = true;
			}
	
			try
			{
				if(!empty($query))
				{
					Zend_Loader::loadClass(CLS_PROGRAM);
					$table = new Program();
					$res = 0;
					{
						foreach($query as $k => $v)
						{
							$where[] = $table->getAdapter()->quoteInto($k, $v);
						}
						if(count($where) > 0)
							$res = $table->delete($where);
			
						if($mt) $db->commit();
					}
	
					Logger::loggerOperation('Foram excluídos '. $res. ' registros da tabela '. TBL_PROGRAM);
					return $res;
				}
			}
			catch(Zend_Exception $e)
			{
				$db->rollback();
				$db->closeConnection();
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->program->drop->fail, E_USER_ERROR);
			}
		}
	}
?>
