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

class ConsanguineBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $consanguine - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($consanguine, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Consanguine();
			$rows = count($obj->find($consanguine[CSG_ID_PERSON_FROM], $consanguine[CSG_ID_PERSON_TO]));
			if($rows == 0)
			{
				$id = $obj->insert($consanguine);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_CONSANGUINE .' [id='.$id.']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(CSG_ID_PERSON_FROM.' = ?', $consanguine[CSG_ID_PERSON_FROM]);
				$where[] = $obj->getAdapter()->quoteInto(CSG_ID_PERSON_TO.' = ?', $consanguine[CSG_ID_PERSON_TO]);
				
				$id = $obj->update($consanguine, $where);
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_CONSANGUINE.
					' ['.CSG_ID_PERSON_FROM.'='.$consanguine[CSG_ID_PERSON_FROM].']'.
					' ['.CSG_ID_PERSON_TO.'='.$consanguine[CSG_ID_PERSON_TO].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->consanguine->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui um relacionameto biológico
	 */
	public static function drop($consanguine, &$db = null)
	{
		if($db == NULL)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Consanguine();
			$res = 0;
			$rows = count($obj->find($consanguine[CSG_ID_PERSON_FROM], $consanguine[CSG_ID_PERSON_TO]));
			if($rows > 0)
			{
				$where[] = $obj->getAdapter()->quoteInto(CSG_ID_PERSON_FROM.'	= ?', $consanguine[CSG_ID_PERSON_FROM]);
				$where[] = $obj->getAdapter()->quoteInto(CSG_ID_PERSON_TO.'		= ?', $consanguine[CSG_ID_PERSON_TO]);
				
				$res = $obj->delete($where);

				if($mt) $db->commit();
			}
			
			if($res > 0)
			{	
				Logger::loggerOperation('A relacão biológica entre id_person_from = ' .$consanguine[CSG_ID_PERSON_FROM].' e id_person_to = '.$consanguine[CSG_ID_PERSON_TO]. " foi excluído.");
			}
			else
			{
				Logger::loggerOperation('Nenhum registro foi excluído');
			}
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->consanguine->drop->fail, E_USER_ERROR);
		}
	}
	
	
	/**
	 * Carrega todos os registros da tabela "csg_consaguine_type" - Tipo de relação sanguínea
	 * 
	 */
	public static function loadAllConsaguineType()
	{	
		$table	= new ConsanguineType();
	
		try
		{	
			$where = $table->getAdapter()->quoteInto(CTP_STATUS.' not in (?)', Constants::DISABLE);
			return $table->fetchAll($where, CTP_DESCRIPTION);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->typeConsanguine->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega do banco as informações de relação biológica de uma pessoa específica 
	 */
	public static function loadBiologicalByIdPerson($idPerson)
	{	
		try
		{	
			$type = new Consanguine();
			
			if(!empty($idPerson))
			{	
				//busca na tabela "csg_consanguine" a linha referente ao "idPerson" 			
				$where = $type->getAdapter()->quoteInto(CSG_ID_PERSON_FROM.' = ?', $idPerson);
				$row = $type->fetchAll($where);
				
				return $row;
			}
			
			Logger::loggerOperation('Nenhum registro encontrado em relação biológica para a pessoa de id = '.$idPerson);
			return null;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->biological->load->fail, E_USER_ERROR);
		}
	}
}
