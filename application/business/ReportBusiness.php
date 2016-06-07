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
 * Jefferson Barros Lima  - W3S		    			25/04/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class ReportBusiness extends BasicBusiness
{
	public static function load($modules, $filters=null, $orderBy=null, $limit=null)
	{
		if(isset($modules) && !empty($modules))
		{
			Zend_Loader::loadClass('FactoryBuilderQuery');
			$sqlMap=null;
			$previusClass = null;
			$index = 0;
			foreach($modules as $module)
			{
				if(!empty($module))
				{
					if(is_array($module))
					{
						foreach($module as $current)
						{
							if(class_exists($current, false))
							{
								$builder = FactoryBuilderQuery::factory($current);
								$sqlMap[$index] = $builder->build();

								$modulesJoin = $builder->getModulesJoin($previusClass, $current);
								if(!empty($modulesJoin))
								{
									$sqlMap[$index][BuilderQueryAbstract::_MODULE_JOIN_KEY] = $modulesJoin;
								}
								$index++;
							}
						}
						$previusClass = $current;
					}
					else
					{
						if(class_exists($module, false))
						{
							$builder = FactoryBuilderQuery::factory($module);
							$sqlMap[$index] = $builder->build();

							$modulesJoin = $builder->getModulesJoin($previusClass, $module);
							if(!empty($modulesJoin))
							{
								$sqlMap[$index][BuilderQueryAbstract::_MODULE_JOIN_KEY] = $modulesJoin;
							}
							$previusClass = $module;
							$index++;
						}
					}
				}
			}
			return self::loadByQuery( self::buildQuery($sqlMap, $filters, $orderBy, $limit) );
		}
	}
	/**
	 * Verifica se há relacionamento entre os módulos selecionados
	 */
	public static function hasRelationship($module1, $module2, array &$modules=null)
	{
		$boolean = TRUE;
		if(!empty($module1))
		{
			if(is_array($module1))
			{
				if(!Utils::arrayEmpty($module1))
				{
					foreach($module1 as $moduleOne)
					{
						if( (is_array($module2) && !Utils::arrayEmpty($module2)) || 
							(!is_array($module2) && !empty($module2)) )
						{
							if(is_array($module2))
							{
								foreach($module2 as $module)
								{
									if(!empty($module))
									{
										$values = BuilderQueryAbstract::$modulesRelationship[$moduleOne];
										if(in_array($module, $values))
											return TRUE;
										else
											$modules[]= self::getModuleName($moduleOne).' - '.self::getModuleName($module);
									}
									else
										return TRUE;
								}
								return FALSE;
							}
							else
							{
								$values = BuilderQueryAbstract::$modulesRelationship[$moduleOne];
								if(!in_array($module2, $values))
								{
									$modules[]= self::getModuleName($moduleOne).' - '.self::getModuleName($module2);
									$boolean = FALSE;
								}
							}
						}
					}
				}	
			}
			else
			{
				if( (is_array($module2) && !Utils::arrayEmpty($module2)) || 
					(!is_array($module2) && !empty($module2)) )
				{
					if(is_array($module2))
					{
						foreach($module2 as $module)
						{
							if(!empty($module))
							{
								$values = BuilderQueryAbstract::$modulesRelationship[$module1];
								if(in_array($module, $values))
									return TRUE;
								else
									$modules[]= self::getModuleName($module1).' - '.self::getModuleName($module);
							}
							else
								return TRUE;
						}
						return FALSE;
					}
					else
					{
						$values = BuilderQueryAbstract::$modulesRelationship[$module1];
						if(!in_array($module2, $values))
						{
							$modules[]= self::getModuleName($module1).' - '.self::getModuleName($module2);
							$boolean = FALSE;
						}
					}
				}
			}
		}
		return $boolean;
	}
	/**
	 * Retorna o nome internacionalizado do módulo
	 */
	public static function getModuleName($var)
	{
		if(!empty($var))
		{
			$resource = new Zend_Config_Ini('./application/resources/'.'Report'.'_'.LOCALE.'.ini', 	'controller');
			if(!empty($resource->report->text->$var))
				return $resource->report->text->$var;
			return $var;
		}
		return null;
	}

	private static function buildQuery($sqlMap, $filters=null, $orderBy=null, $limit=null)
	{
		if(!empty($sqlMap) && is_array($sqlMap) && count($sqlMap) > 0 && !Utils::array_is_empty($sqlMap))
		{
			$sql = null;
			foreach($sqlMap as $index => $module)
			{
				if($index > 0)
				{
					/* Adiciona os Campos para a consulta */
					$sql[BuilderQueryAbstract::_COLUMNS_KEY][] =
						$module[BuilderQueryAbstract::_COLUMNS_KEY];
					
					if(UserLogged::isCoordinator())
					{
						if(strrchr($module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY], ','))
						{
							$inner = str_replace(", ","",strrchr($module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY], ','));
							$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_INNER_JOIN_KEY][] = array($inner);
							
							$counter = strlen($module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY])-strlen($inner);
							
							$from = substr($module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY], 0, $counter);						
							
							$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY] = $from.$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY];
						}
						else
						{
							$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_INNER_JOIN_KEY][] =
								array($module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY]);
						}
					}
					else
					{
						/* Tabela que compõe o outer join ligando um módulo a outro*/
						$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_INNER_JOIN_KEY][] =
							array($module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY]);
					}

					/* campos que compõe o outer ligando um módulo a outro */
					$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_INNER_JOIN_FIELDS_KEY][] =
						array($module[BuilderQueryAbstract::_MODULE_JOIN_KEY]);

					/* Tabelas que compõe o outer join */
					$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_KEY][] =
						$module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_KEY];
					
					/* campos que compõe o outer join */
					$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_FIELDS_KEY][] =
						$module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_FIELDS_KEY];

					/* cláusula WHERE */
					if(!empty($module[BuilderQueryAbstract::_WHERE_KEY]))
						$sql[BuilderQueryAbstract::_WHERE_KEY][] = $module[BuilderQueryAbstract::_WHERE_KEY];
				}
				else
				{
					/* Adiciona os Campos para a consulta */
					$sql[BuilderQueryAbstract::_COLUMNS_KEY][] =  $module[BuilderQueryAbstract::_COLUMNS_KEY];
					/* Tabela da cláusula FROM */
					$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY] =
					$module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY];
					/* Tabela que compõe o outer join ligando um módulo a outro*/
					$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_KEY][] =
					$module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_KEY];
					/* campos que compõe o outer ligando um módulo a outro */
					$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_FIELDS_KEY][] =
					$module[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_FIELDS_KEY];

					/* cláusula WHERE */
					if(!empty($module[BuilderQueryAbstract::_WHERE_KEY]))
						$sql[BuilderQueryAbstract::_WHERE_KEY][] = $module[BuilderQueryAbstract::_WHERE_KEY];
				}
			}
						
			// Agrupa todos os campos que devem ser retornados na consulta
			$sql[BuilderQueryAbstract::_COLUMNS_KEY] = implode(', ',$sql[BuilderQueryAbstract::_COLUMNS_KEY]);
			// Agrupa os campos da clausula From
			if(is_array($sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY]))
			{
				$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY] = 
					implode(', ',$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY]);
			}
						
			/* Inicia a construção da query SQL */
			$query = 'SELECT '.$sql[BuilderQueryAbstract::_COLUMNS_KEY] .
			' FROM '.$sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_FROM_TABLES_KEY];
			
			/* INNER */
//			$inner = $sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_INNER_JOIN_KEY];
//			if(!empty($inner) && count($inner) > 0)
//			{
//			 	foreach($inner as $index => $tableSet)
//			 	{
//			 		foreach($tableSet as $tabIndex => $table)
//			 		{
//			 			$fields = $sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_INNER_JOIN_FIELDS_KEY][$index][$tabIndex];
//			 			if(!empty($fields))
//			 			{
//				 			$query = $query.
//				 			' INNER JOIN '.$table;
//			 				if(is_array($fields))
//			 				{
//			 					if(count($fields) > 0)
//			 					{
//			 						$query = $query.' ON '. implode(' AND ',$fields);
//			 					}
//			 				}
//			 				else
//			 					$query = $query.' ON '. $fields;
//			 			}
//			 		}
//			 	}
//			}
			
			/* OUTER */
			$outer = $sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_KEY];
			if(!empty($outer) && count($outer) > 0)
			{
			 	foreach($outer as $index => $tableSet)
			 	{
			 		foreach($tableSet as $tabIndex => $table)
			 		{
			 			$fields = $sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_OUTER_JOIN_FIELDS_KEY][$index][$tabIndex];
			 			if(!empty($fields))
			 			{
				 			$query = $query.
				 			' LEFT OUTER JOIN '.$table;
			 				if(is_array($fields))
			 				{
			 					if(count($fields) > 0)
			 					{
			 						$query = $query.' ON '. implode(' AND ',$fields);
			 					}
			 				}
			 				else
			 					$query = $query.' ON '. $fields;
			 			}
			 		}
			 		/* INNER */
					$tableSet = $sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_INNER_JOIN_KEY][$index];
					foreach($tableSet as $tabIndex => $table)
			 		{
			 			$fields = $sql[BuilderQueryAbstract::_FROM_KEY][BuilderQueryAbstract::_INNER_JOIN_FIELDS_KEY][$index][$tabIndex];
			 			if(!empty($table) && !empty($fields))
			 			{
				 			$query = $query.
				 			' INNER JOIN '.$table;
			 				if(is_array($fields))
			 				{
			 					if(count($fields) > 0)
			 					{
			 						$query = $query.' ON '. implode(' AND ',$fields);
			 					}
			 				}
			 				else
			 					$query = $query.' ON '. $fields;
			 			}
			 		}
			 	}
			}

			/* WHERE */
			if(!empty($filters))
			{				
				$sql[BuilderQueryAbstract::_WHERE_KEY] = array_merge((array)$sql[BuilderQueryAbstract::_WHERE_KEY], (array)$filters);
				
				if(!empty($sql[BuilderQueryAbstract::_WHERE_KEY]) && is_array($sql[BuilderQueryAbstract::_WHERE_KEY]) &&
					count($sql[BuilderQueryAbstract::_WHERE_KEY]) > 0)
				{
					$where = null;
					foreach($sql[BuilderQueryAbstract::_WHERE_KEY] as $whereFields)
					{
						if(is_array($whereFields))
						{
							foreach($whereFields as $current)
							{
								if(!empty($current))
									$where[]= $current;
							}
						}
						else
						{
							if(!empty($whereFields))
								$where[]= $whereFields;
						}
					}
					if(!empty($where))
					{
						$query = $query.' WHERE '.implode(' AND ',$where);
					}
				}
			}
						
			/* ORDER BY */
			if(is_array($orderBy))
			{
				if(count($orderBy) > 0)
				{
					$order = null;
					foreach($orderBy as &$current)
					{
						if(is_array($current) && count($current) > 0)
						{
							foreach($current as $k => $v)
								if(empty($v)) unset($current[$k]);
						}
						if(!empty($current))
							$order = array_merge((array)$order, (array)$current); 
					}

					if(!empty($order) && count($order) > 0)
					{
						$order = implode(', ',$order);
						if(!empty($order))
							$query = $query.' ORDER BY '.$order;
					}
				}
			}
			else
			{
				if(!empty($orderBy))
				{
					$query = $query.' ORDER BY '.$orderBy;
				}
			}
			/* LIMIT */
			if($limit)
			{
				$query .= ' LIMIT 0,'.Zend_Registry::get(CONFIG)->report->limit;
			}
			
			return $query;
		}
		return null;
	}

	private static function loadByQuery($query)
	{
		if(!empty($query))
		{
			try
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				if(!empty($query))
				{
					Logger::loggerOperation("Iniciando pesquisa do relatório.");
					self::scriptFriendly($query);
					$stmt = $db->query($query);
					// Carrega os dados em memória
//					$rows = $stmt->fetchAll();
					
					// Retorna o resultset
					$rows = $stmt;
					unset($stmt);
					Logger::loggerOperation("Fim da pesquisa do relatório.");
					return $rows;
				}
				return null;
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				Logger::loggerError("SLQ: ".self::scriptFriendly($query, PHP_EOL) );
				trigger_error(parent::getLabelResources()->report->load->fail, E_USER_ERROR);
			}
		}
	}
	/**
	 * Monta o script de modo amigável visualmente.
	 */
	public function scriptFriendly($query, $breakLine="<br/>")
	{
		if(!empty($query))
		{
			$query = str_replace(" FROM ", $breakLine."FROM ", $query );
			$query = str_replace(" LEFT ", $breakLine."LEFT ", $query );
			$query = str_replace(" INNER ", $breakLine."INNER ", $query );
			$query = str_replace(" ON ", $breakLine." ON ", $query );
			$query = str_replace(" WHERE ", $breakLine."WHERE ", $query );
			$query = str_replace(" AND ", $breakLine." AND ", $query );
			$query = str_replace(" ORDER BY ", $breakLine." ORDER BY ", $query );
		}
		return $query;
	}
	/**
	 * Carrega os campos dado o módulo
	 */
	public static function loadFields($modules)
	{
		if(!empty($modules))
		{
			if(UserLogged::isCoordinator())
			{
				if(is_array($modules))
				{
					foreach($modules as $mod)
					{
						if($mod == CLS_ENTITY)
							return null;	
					}
				}
				else
				{
					if($modules == CLS_ENTITY)
						return null;
				}
			}
			
			Zend_Loader::loadClass('FactoryBuilderQuery');
			if(is_array($modules))
			{
				$previous = null;
				foreach($modules as $module)
				{
					$builder = FactoryBuilderQuery::factory($module);
					$fields = $builder->getFields();
					$previous = array_merge((array)$previous ,(array)$fields);
				}
				return $previous;
			}
			else
			{
				$builder = FactoryBuilderQuery::factory($modules);
				return $builder->getFields();
			}
		}
	}
	
	public function getModuleRelationship($module=null,$selected=null)
	{
		Zend_Loader::loadClass('BuilderQueryAbstract');
		if(!empty($module))
		{
			if(is_array($module) && count($module) > 0)
			{
				$prior = null;
				$rel = BuilderQueryAbstract::$modulesRelationship;
				foreach($module as $current)
				{
					$prior = array_unique(array_merge((array)$prior,(array)$rel[$current]));
				}				
				return self::excludSelectedModule($prior, $selected);
			}
			else
			{
				$rel = BuilderQueryAbstract::$modulesRelationship;				
				return array_unique(self::excludSelectedModule($rel[$module], $selected));
			}
		}
		else
		{
			return BuilderQueryAbstract::$allModules;
		}
	}
	/**
	 * Retira da lista de exibição, os módulos já selecionados em níveis anteriores
	 */
	public static function excludSelectedModule($modules, $selected)
	{
		if(is_array($modules))
		{
			if(!Utils::array_is_empty($modules) && !Utils::array_is_empty($selected))
			{				
				foreach($modules as $key=>$module)
				{					
					if(in_array($module, $selected))
					{
						unset($modules[$key]);
					}
				}
			}
		}
		return $modules;
	}
}