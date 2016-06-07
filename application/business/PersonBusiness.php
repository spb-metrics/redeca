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
 * Saulo Esteves Rodrigues  - W3S		    		29/01/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class PersonBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $person - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($person, &$db=null)
	{
		
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new Person();
			Zend_Loader::loadClass('PersonInsertsByUser');
			if($person[PRS_ID_PERSON] == false)
			{
				$obj->insert($person);
				$id = $db->lastInsertId(TBL_PERSON);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_PERSON .' [id='.$id.']');
				
				//log de inserções de pessoas
				$logData			 	= array();
				$logData[PIU_ID_PERSON] = $id;
				$logData[PIU_ID_USER]	= UserLogged::getUserId();
				$logObj 				= new PersonInsertsByUser();
				$logObj->insert($logData);				
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(PRS_ID_PERSON.' = ?', $person[PRS_ID_PERSON]);
				$id = $obj->update($person, $where);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_PERSON.
					' ['.PRS_ID_PERSON.'='.$person[PRS_ID_PERSON].']');
			}
			
			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->person->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os registros
	 * 
	 * $start : Inicia a consulta do registro n. $start
	 * $limit : Total de registros à serem recuperados
	 * 
	 */
	public static function loadAll($lastName, $firstName, $start=null, $limit=null)
	{
		$db 		= Zend_Registry::get(DB_CONNECTION);
		try
		{
			$select 	= $db->select()->from(TBL_PERSON);
			
			if($lastName != null)
			{
				$select->where(PERSON_LASTNAME . ' LIKE ?', $lastName);
			}
			if($firstName != null)
			{
				$select->where(PERSON_FIRSTNAME . ' LIKE ?', $firstName);
			}
			$select->limit($limit, $start);

			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Recupera a quantidade total de registros
	 * 
	 */
	public static function countAll($lastName, $firstName)
	{
		$db 		= Zend_Registry::get(DB_CONNECTION);
		try
		{
			$select 	= $db->select()
							->from(TBL_PERSON,
								array('total' => 'COUNT(*)'));
			if($lastName != null)
			{
				$select->where(PERSON_LASTNAME . ' LIKE ?', $lastName);
			}
			if($firstName != null)
			{
				$select->where(PERSON_FIRSTNAME . ' LIKE ?', $firstName);
			}
								
			$count		= $db->fetchRow($select);
			
			return $count['total'];
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega um registro
	 * 
	 */
	public static function load($id)
	{
		$obj = new Person();
		try
		{
			$res = $obj->find($id);
			return $res;	
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
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
			$person = new Person();
			$where = PERSON_ID . ' = ' . $id;
			$person->delete($where);
			if($mt) $db->commit();
			Logger::loggerOperation('Pessoa excluída. [id='.$id.']');
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega uma única pessoa
	 */
	public static function loadPerson($idPerson)
	{	
		try
		{	
			$type = new Person();
			
			if(!empty($idPerson))
			{	
				//busca na tabela família "per_person" a linha referente ao "idPerson" 			
				$where = $type->getAdapter()->quoteInto(PRS_ID_PERSON.' = ?', $idPerson);
				$row = $type->fetchAll($where);
				
				return $row->current();
			}		
			
			return null;	
			
			Logger::loggerOperation('Nenhum registro encontrado para a pessoa de id = '.$idPerson.' = '.implode(',' ,$idPerson));
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega as últimas pessoas inseridas no sistema (por usuário)
	 * 
	 */
	public static function loadPersonsInsertedByUser($id_user, $limit)
	{
		$db 		= Zend_Registry::get(DB_CONNECTION);
		try
		{
			$select = $db->select()
						->from(array('log' => TBL_PERSON_INSERTS_BY_USER))
						->joinInner(array('per' => TBL_PERSON),
		        		'log.'. PIU_ID_PERSON .' = per.'. PRS_ID_PERSON);
			
			if($id_user != null)
			{
				$select->where('log.'.PIU_ID_USER . ' = ?', $id_user);
			}
			$select->order('log.'.PIU_TSTAMP.' DESC');
			$select->limit($limit, 0);

			return $db->fetchAll($select);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
}