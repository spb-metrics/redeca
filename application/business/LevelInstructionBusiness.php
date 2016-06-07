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

class LevelInstructionBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $instruction - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($instruction, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new LevelInstruction();
			
			if($instruction[LIT_ID_LEVEL_INSTRUCTION] == false)
			{
				$id = $obj->insert($instruction);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_LEVEL_INSTRUCTION .' [id='.$id.']');
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(LIT_ID_PERSON.' = ?', $instruction[LIT_ID_PERSON]);
				
				$id = $obj->update($instruction, $where);
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_LEVEL_INSTRUCTION.
					' ['.LIT_ID_PERSON.'='.$instruction[LIT_ID_PERSON].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->levelInstruction->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os registros da tabela "DegreeType" - Grau de Instrução
	 * 
	 */
	public static function loadAllDegree()
	{	
		Zend_Loader::loadClass('DegreeType');
		
		$table	= new DegreeType();
	
		try
		{	
			$where = $table->getAdapter()->quoteInto(DTP_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, DTP_DEGREE);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->typedegree->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadDegree($id)
	{	
		Zend_Loader::loadClass('DegreeType');
		
		$table	= new DegreeType();
	
		try
		{	
			$where[] = $table->getAdapter()->quoteInto(DTP_ID_DEGREE.' in (?)', $id);
			$where[] = $table->getAdapter()->quoteInto(DTP_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->typedegree->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega do banco as informações de nível escolar educacional de acordo com o "id" e/ou "status" da pessoa  
	 */
	public static function loadLevelInstructionByPerson($idPerson)
	{	
		try
		{	
			$type = new LevelInstruction();
			
			if(!empty($idPerson))
			{	
				
				//busca na tabela nível de instrução a linha referente ao "idPerson" e "status" informados 			
				$where[] = $type->getAdapter()->quoteInto(LIT_ID_PERSON.' = ?', $idPerson);
				$where[] = $type->getAdapter()->quoteInto(LIT_STATUS.' is null', null);
				
				$row = $type->fetchAll($where);
				
				return $row->current();
			}
			
			Logger::loggerOperation('Nenhum nível educacional encontrado para '.$idPerson.' = '.implode(',' ,$idPerson));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->levelinstruction->load->fail, E_USER_ERROR);
		}
	}
}