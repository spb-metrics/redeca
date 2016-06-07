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

class ProfileBusiness extends BasicBusiness
{

	/**
	 * Insere ou edita um registro
	 * Se passar o segundo parâmetro (conexão), o método não 
	 * efetua o commit no final (assume que quem chama tem o 
	 * controle transacional)
	 * 
	 */
	public static function save($profile, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
	
		try
		{	
			$profileObj = new Profile();
			if($profile[AUTH_ID_PROFILE] == false)
			{				
				$insertedId = $profileObj->insert($profile);				
				Logger::loggerOperation('Novo perfil adicionado. [id='.$insertedId.']');
			}
			else
			{
				$where = AUTH_ID_PROFILE . ' = ' . $profile[AUTH_ID_PROFILE];
				$profileObj->update($profile, $where);
				Logger::loggerOperation('Perfil modificado. [id='.$profile[AUTH_ID_PROFILE].']');
			}
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->error->failDB, E_USER_ERROR);
		}
	}

	/**
	 * Carrega do banco um ou mais perfis
	 */
	public static function load($id)
	{
		$table = new Profile();
		
		try
		{
			if(!empty($id))
			{
				return $table->find($id);
			}
			Logger::loggerOperation('Nenhum registro encontrado para '.AUTH_ID_PROFILE.' = '.implode(',' ,$id));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadByName($name)
	{
		$table = new Profile();
		
		try
		{
			$where[] = $table->getAdapter()->quoteInto(AUTH_PROFILE.' = ?', $name);
			return $table->fetchRow($where, AUTH_PROFILE);			
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todos os registros da tabela "Profile" - Perfil
	 * 
	 */
	public static function loadAll()
	{	
		$table	= new Profile();
	
		try
		{	
			return $table->fetchAll(null, AUTH_PROFILE);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Exclui um ou mais perfis do banco de dados
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
			$table = new Profile();
			$res = 0;
			if(!is_null($id) || !empty($id))
			{
				$where = $table->getAdapter()->quoteInto(AUTH_ID_PROFILE.' in (?)', $id);
				$profile[AUTH_STATUS] = Constants::DISABLE;
				$res = $table->update($profile, $where);

				if($mt) $db->commit();
			}
			if($res > 0)
				Logger::loggerOperation('O(s) perfil(is) com '.AUTH_ID_PROFILE.' = '. implode(',' ,$id). " foi(foram) excluído(s).");
			else
				Logger::loggerOperation('Nenhum registro foi excluído');
			
			return $res; 
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->profile->drop->fail, E_USER_ERROR);
		}
	}
}
?>
