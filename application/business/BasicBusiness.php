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

abstract class BasicBusiness
{
	static function getLabelResources()
	{
		return Zend_Registry::get(LABEL_RESOURCES);
	}

	/**
	 * Count dada a tabela
	 * @return Número de registros na tabela
	 */ 
	public static function count($tableName, $columnName, $where=null) 
	{
        if(!empty($tableName) && !empty($columnName))
        {
	        $db = Zend_Registry::get(DB_CONNECTION);
	        $db->setFetchMode(Zend_Db::FETCH_OBJ);
	        
	        $select = $db->select();
	        $select->from($tableName,'COUNT('. $columnName .') AS '.$columnName);
	        if($where)
	        {
	        	if(is_array($where))
	        	{
	        		foreach($where as $k => $v)
	        		{
	        			$select->where($db->quoteInto($k,$v));
	        		}
	        	}
	        	else
	        	{
	        		$select->where($db->quoteInto($columnName.' = ?',$where));
	        	}
	        }
	       	
        	$row	= $db->fetchRow($select);
        	return $row->{$columnName};
        }
        return NULL;
	}
	/**
	 * Carrega o "id_resource" da tabela "auth_resource" conforme "controller" passado como parâmetro 
	 */
	public static function loadIdResource($controller = null)
	{	
		if(UserLogged::isLogged())
		{
//			try
//			{
//				$isOperator = UserLogged::isOperator();							
//			}
//			catch(UserNotLoggedException $e){
//				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$this->view->labels->notPermission.' - '.$e);
//				trigger_error(self::getLabelResources()->notPermission , E_USER_ERROR);
//			}
//			if($isOperator)
//			{				
				try
				{						
					
					$resource = ResourcePermission::getResource($controller);					
					return $resource->{ARC_ID_RESOURCE};
				}
				catch(InvalidResourceForThisOperation $e){
					$msg = self::getLabelResources()->invalid->controller . ' [Controller: '. $controller. ']';
					Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ". $msg .' - '.$e);
					trigger_error(self::getLabelResources()->invalid->controller , E_USER_ERROR);
				}  
//			}
			
			return null;
		}
	}
	
	/**
	 * Recupera o nome da coluna-chave de uma tabela dado o nome da classe que representa esta tabela
	 * @param String $className - Nome da classe que representa uma tabela
	 * @return String Nome da coluna ou null caso não seja passado o nome da classe
	 */
	public static function getPKColumnName($className)
	{
		if(!empty($className))
		{
			$table = new $className();
			$info = $table->info();
			return $info['primary'];
		}
		return NULL;
	}
}
