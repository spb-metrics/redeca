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
 * Fabricio Meireles Monteiro  - W3S		    	29/02/2008	                       Create file 
 * 
 */

require_once('BasicBusiness.php');

class ClassBusiness extends BasicBusiness
{
	/**
	 * Carrega os dados de atendimento da entidade do usuário logado
	 * @param Integer/Array(Integer) $idProgramType - Identificador de tipo de programa
	 * @param Integer/Array $entityId - Identificador de uma entidade(Opcional- caso não seja passado
	 * utiliza o id da entidade do usuário logado)
	 * @param Integer/Array $classId - Identificador de turma que não devem ser carregados 
	 * (Se passados os ID's de classes, estas não serão carregadas)
	 */
	public static function loadAllClassesByEntity($idProgramType = NULL, $entityId = NULL, $classId = NULL)
	{
	 	if(empty($entityId))
	 		$entityId = UserLogged::getEntityId();
	 	
	 	if(!empty($entityId))
	 	{
			try
			{
				Zend_Loader::loadClass(CLS_CLASSMODEL);
				Zend_Loader::loadClass(CLS_PROGRAM);

				$db = Zend_Registry::get(DB_CONNECTION);
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				$select = $db->select()
		    		->from(array('cls' => TBL_CLASS))
		    		->joinInner(array('prg' => TBL_PROGRAM),
		        		'prg.'. PGR_ID_PROGRAM .' = cls.'. CLS_ID_PROGRAM)		        	
					->where('prg.'. PGR_ID_ENTITY .' in (?)', $entityId )
					->where('cls.'. CLS_END_DATE  .' is null', null );
					

				if($idProgramType !== NULL)
					$select->where('prg.'. PGR_ID_PROGRAM_TYPE .' in (?)', $idProgramType );

				if($classId !== NULL)
					$select->where('cls.'. CLS_ID_CLASS .' not in (?)', $classId );
					
				$select->order(CLS_NAME);
								
				$res = $db->fetchAll($select);											
			}
			catch(Zend_Exception $e)
			{
				Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage(). ' - '.$e);
				trigger_error(parent::getLabelResources()->classassistance->load->fail, E_USER_ERROR);
			}
	 	}
	 	return $res;
	}

	public static function loadAllClassesByStatus($assistancesObj, $classes)
	{
		foreach($assistancesObj as $assist)
		{
			$classesAssistence			= $assist->findManyToManyRowset(CLS_CLASSMODEL, CLS_CLASSASSISTANCE);
			$idAssistance = $assist->{AST_ID_ASSISTANCE};
		}		
		
		$obj = new ClassAssistance();
		
		foreach($classesAssistence as $cla)
		{
			$row = $obj->find($cla->{CLS_ID_CLASS}, $idAssistance);
			if($row->current()->{CLA_ID_STATUS} == Constants::FINISHED)
			{
				$classes = array_merge($classes, array($cla));
			}
		}
		
		return $classes;
	}

	/**
	 * Persiste informações no DB 
	 * @param Array $class - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($class, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{	
			$obj = new ClassModel();
			if($class[CLS_ID_CLASS] == false)
			{
				$id = $obj->insert($class);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_CLASS .' [id='.$id.']');
			}
			else
			{
				$where = $obj->getAdapter()->quoteInto(CLS_ID_CLASS.' = ?', $class[CLS_ID_CLASS]);
				$obj->update($class, $where);
				$id = $class[CLS_ID_CLASS];
				
				Logger::loggerOperation('Registro modificado na tabela '.TBL_CLASS.
					' ['.CLS_ID_CLASS.'='.$class[CLS_ID_CLASS].']');
			}

			if($mt) $db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{	
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->actclass->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Persiste as informações de turma e relacionamento entre turma e atividade 
	 */
	public static function saveClass($bean, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$transaction = true;
		}
		
		try 
		{
			if(!empty($bean))
			{					
				$class = array();
				$class[CLS_ID_CLASS]	= $bean[CLS_ID_CLASS];
				$class[CLS_ID_PROGRAM]	= $bean[CLS_ID_PROGRAM];
				$class[CLS_VACANCY] 	= $bean[CLS_VACANCY];
				$class[CLS_PERIOD]		= $bean[CLS_PERIOD];
				$class[CLS_NAME] 		= $bean[CLS_NAME];
				$class[CLS_SCHEDULE] 	= $bean[CLS_SCHEDULE];
				$class[CLS_START_DATE]	= date("Y-m-d");
				$class[CLS_END_DATE] 	= null;
				
				$insertedClassId = self::save($class, $db);
				
				if($insertedClassId != null && sizeof($bean[ACC_ID_ACTIVITY_DETAIL]) > 0)
				{	
					self::saveActivityClass($bean, $insertedClassId, $db);
				}
			}
			
			if($transaction)
			{
				$db -> commit();
			}
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->actclass->save->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Persiste os relacionamentos entre turma e atividades
	 */
	public static function saveActivityClass($bean, $insertedClassId, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$transaction = true;
		}

		try
		{	
			$activityClass = array();
			$activityClass[ACC_ID_CLASS]	= $insertedClassId;
			$activityClass[ACC_START_DATE]	= date("Y-m-d");
			$activityClass[ACC_END_DATE]	= null;
			
			foreach($bean[ACC_ID_ACTIVITY_DETAIL] as $actDetailId)
			{
				$activityClass[ACC_ID_ACTIVITY_DETAIL]	= $actDetailId;
				$actClassObj = new ActivityClass();
				$insertedActClassId = $actClassObj->insert($activityClass);
				Logger::loggerOperation('Novo relacionamento entre turma id='.$insertedClassId.' e atividade [id='.$actDetailId.'] criado.');	
			}
			if($transaction)
				$db -> commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityclass->error->failDB, E_USER_ERROR);		
		}
	}
	
	/**
	 * Retorna a quantidade de vagas em aberto(caso exista) de uma turma dado seu Identificador
	 * @param Integer $classId - Identificador da turma
	 * @return Integer Número de vagas existentes para a turma solicitada
	 */
	public static function getVacancyByClassId($classId, $vacancy = null)
	{
		if(!empty($classId))
		{
			Zend_Loader::loadClass(CLS_CLASSASSISTANCE);
			if($vacancy == null)
				$vacancy = self::load($classId)->{CLS_VACANCY};		
			
			$table = new ClassAssistance();			
			$where = $table->getAdapter()->quoteInto(CLA_ID_CLASS.' in (?)',$classId);			
			$rows = $table->fetchAll($where);
			
			$attended = 0;
			foreach($rows as $row)
			{				
				if($row->{CLA_ID_STATUS} != Constants::FINISHED)
				{
					$attended++;
				}
			}					
//			$attended = self::count(TBL_CLASS_ASSISTANCE, CLA_ID_CLASS, $classId);
			
			if(!$vacancy) $vacancy = 0;
			
			return ($vacancy - $attended);
		}
		return 0;
	}
	
	/**
	 * 
	 */
	public static function setUpdatedVacancy($classes)
	{
		$updated = NULL;
		if(!empty($classes) && is_array($classes))
		{
			$updated = $classes;
			foreach($updated as $current)
			{
				$current->{CLS_VACANCY} = self::getVacancyByClassId($current->{CLS_ID_CLASS},$current->{CLS_VACANCY});
			}
		}
		return $updated; 
	}
	
	/**
	 * Preenche o status do atendimento de grupo dependendo do número de vagas
	 * @param Array $data  Array com os valores a serem persistidos na tabela act_class_assistance 
	 */
	public static function fillStatusByVacancy(&$data)
	{
		if(!empty($data) && is_array($data))
		{
			$vacancy = self::getVacancyByClassId();
			if($vacancy < 0)
				$data[CLA_ID_STATUS] = Constants::ASSISTANCE_WAITING_STATUS;
			else
				$data[CLA_ID_STATUS] = Constants::ASSISTANCE_IN_PROCCESS_STATUS;
		}
	}
	
	public static function saveClassAssistance($assistance, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			Zend_Loader::loadClass(CLS_CLASSASSISTANCE);
			$obj = new ClassAssistance();
			
			$rows = count($obj->find($assistance[CLA_ID_CLASS], $assistance[CLA_ID_ASSISTANCE]));
			if($rows == 0)
			{
				$res = $obj->insert($assistance);
				Logger::loggerOperation('Novo relacionamento turma-atendimento adicionado. [id='.$res.']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(CLA_ID_CLASS.' = ?', $assistance[CLA_ID_CLASS]);
				$where[] = $obj->getAdapter()->quoteInto(CLA_ID_ASSISTANCE.' = ?', $assistance[CLA_ID_ASSISTANCE]);
				
				$res = $obj->update($assistance, $where);
				Logger::loggerOperation('Registro modificado na tabela '.TBL_CLASS_ASSISTANCE.
					' ['.CLA_ID_CLASS.'='.$assistance[CLA_ID_CLASS].']'.
					' ['.CLA_ID_ASSISTANCE.'='.$assistance[CLA_ID_ASSISTANCE].']');
			}
			if($mt) $db->commit();
			
			return $res;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' - '.$e);
			trigger_error(parent::getLabelResources()->actclass->save->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega do banco uma ou mais turmas
	 */
	public static function load($idClass)
	{
		$table = new ClassModel();
		
		try
		{	
			if(!empty($idClass))
			{	
				return $table->find($idClass)->current();
			}
			Logger::loggerOperation('Nenhuma turma encontrada para '.CLS_ID_CLASS.' = '.implode(',' ,$idClass));
			return ;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->actclass->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadByName($name)
	{
		$table = new ClassModel();
		
		try
		{	
			$where[] = $table->getAdapter()->quoteInto(CLS_NAME.' = ?', $name);
			return $table->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->actclass->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Encerra uma turma e suas respectivas atividades associadas 
	 */
	public static function closeClass($idClass, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
	
		try
		{	
			self::closeAssistanceByClass($idClass, $db);
			
			$loadObjClass = self::load($idClass);
			if($loadObjClass)
			{
				$collActivityClass = $loadObjClass->findDependentRowset(CLS_ACTIVITYCLASS);
				foreach($collActivityClass as $ac)
				{
					$idActivityClass = $ac->{ACC_ID_ACTIVITY_CLASS};
					
					//atualiza o relacionamento entre atividade e turma
					ActivityBusiness::updateActivityClass($idActivityClass, $db);	
				}	
			}
			
			//constróe um objeto do tipo classe
			$actClass = array();
			$actClass[CLS_ID_CLASS] = $idClass;
			$actClass[CLS_END_DATE] = date("Y-m-d");
			
			//persiste o objeto classe
			self::save($actClass, $db);
			
			/**
			 * exclui registros de relacionamento entre turma e atendimento na tabela "act_class_assistance"
			 */			 
			//método não mais utilizado porque registros não serão excluídos
			//ClassBusiness::dropClassAssistance($idClass, $db);
			
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
	
	/**
	 * Encerra um atendimento conforme "idClass" informado  
	 */
	public static function closeAssistanceByClass($idClass, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
	
		try
		{
			$allAssistance = self::loadAllAssistanceByClass($idClass, $db);
			
			foreach($allAssistance as $uniqueAssClass)
			{	
				//seta o objeto "atendimento"
				$assistance = $uniqueAssClass->findParentRow(CLS_ASSISTANCE);

				//verifica se a data de encerramento está preechida
				//se data não estiver preenchida é porque o atendimento ainda existe
				if($assistance->{AST_REAL_END_DATE} == null);
				{
					//seta o relacionamento associado com o objeto "atendimento" 
					$countAssistanceInClass = sizeof($assistance->findDependentRowset(CLS_CLASSASSISTANCE));					
					
					//verifica se a quantidade o atendimento em questão está associada a mais de uma turma
					//caso esteja associado a mais de uma turma, a aplicação não deve fazer update na tabela "ast_assistance"
					if($countAssistanceInClass == 1)
					{			
						//id utilizado para persistir data
						$idAssistance = $uniqueAssClass->{CLA_ID_ASSISTANCE};

						$assistance = array();
						$assistance[AST_ID_ASSISTANCE] = $idAssistance;
						$assistance[AST_REAL_END_DATE] = date("Y-m-d");
						
						//existe somente um atendimento associado à turma em questão,
						//logo esse atendimento deve ser encerrado
						AssistanceBusiness::save($assistance, $db);
					}	
				}
			}
		
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->assistanceclass->error->failDB, E_USER_ERROR);
		}
	}
	
	
	/**
	 * Carrega todos os registros da tabela Turma - "act_class"
	 * 
	 */
	public static function loadAll()
	{	
		$table	= new ClassModel();
	
		try
		{	
			return $table->fetchAll(null, CLS_NAME);
		}
		catch(Zend_Exception $e)
		{
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->actclass->load->fail, E_USER_ERROR);
		}
	}

	/**
	 * Carrega todos os atendimentos associados a uma turma específica
	 */
	public static function loadAllAssistanceByClass($idClass, &$db=null)
	{	
		try
		{
			$obj = new ClassAssistance();
		
			//verifica se na tabela gestação existe registro referente ao "idPerson"  			
			$where[] = $obj->getAdapter()->quoteInto(CLA_ID_CLASS.' = ?', $idClass);
			return $obj->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
				trigger_error(parent::getLabelResources()->classassistance->load->fail, E_USER_ERROR);		
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
				Zend_Loader::loadClass(CLS_CLASSASSISTANCE);
				$table = new ClassAssistance();
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

				Logger::loggerOperation('Foram excluídos '. $res. ' registros da tabela '. TBL_CLASS_ASSISTANCE);
				return $res;
			}
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->classassistance->delete->fail, E_USER_ERROR);
		}
	}
	
	public static function loadActivitiesClass($collClass)
	{	
		if($collClass && sizeof($collClass) > 0 )
		{	
			foreach($collClass as $cl)
			{	
				$idClass[] = $cl->{CLS_ID_CLASS};
			}	
			
			$collActivityClass = self::loadAllActivityByClass($idClass);
					
			return $collActivityClass;
		}
		
		return null;
	}
	
	/**
	 * Migra uma turma 
	 */
	public static function migrateClass($idClass, $idNewClass, $endDate, $confidentiality, &$db=null)
	{	
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			$loadObjNewClass = self::load($idNewClass);
			$idProgramNewClass = $loadObjNewClass->{CLS_ID_PROGRAM}; 
			
			$collAssistanceClass = self::loadAllAssistanceByClass($idClass);
			if($collAssistanceClass != null && $idProgramNewClass != null && $idNewClass != null && $confidentiality != null )
			{
				if(sizeof($collAssistanceClass) > 0)
				{
					//constróe um objeto - act_assistance - atendimento
					$assistance = array();
					$assistance[AST_ID_PROGRAM]			= $idProgramNewClass;
					$assistance[AST_ID_USER]			= UserLogged::getUserId();
					$assistance[AST_END_DATE_PREVISION]	= $endDate;
					$assistance[AST_REAL_END_DATE]		= null;
					$assistance[AST_CONFIDENTIALITY]	= $confidentiality;
					
					foreach($collAssistanceClass as $aca)
					{	
						$assistance[AST_ID_PERSON]		= $aca->findParentRow(CLS_ASSISTANCE)->{AST_ID_PERSON};
						$assistance[AST_BEGINNING_DATE]	= date('c', time());
						
						$idAssistanceInserted[] = AssistanceBusiness::save($assistance, $db);		
					}
					
					//constróe um objeto - act_class_assistance - que relaciona um atendimento a uma classe
					$classAssistance = array();
					$classAssistance[CLA_ID_CLASS] = $idNewClass;
					
					//recupera o total de vagas cadastradas na turma que receberá a migração 
					$vacancyNewClass = $loadObjNewClass->{CLS_VACANCY};
					
					//retorna a quantidade de atendimentos já cadastrados para turma que receberá a migração
					$amount = self::loadAllAssistanceByClass($idNewClass);
					if($amount && sizeof($amount) > 0 )
					{
						$assAlreadyRegistered = sizeof($amount); 
					}
					else
					{
						$assAlreadyRegistered = 0;
					}
					
					//armazena o total efetivo de vagas disponíveis
					$totalEffectiveOfVacancy = ($vacancyNewClass - $assAlreadyRegistered);
					
					$i = 0;
					foreach($idAssistanceInserted as $idAssistance)
					{
						if($i < $totalEffectiveOfVacancy)
						{
							$classAssistance[CLA_ID_STATUS] = Constants::ONE;		
						}
						else
						{
							$classAssistance[CLA_ID_STATUS] = Constants::TWO;
						}
						
						$classAssistance[CLA_ID_ASSISTANCE] = $idAssistance;
						
						//persiste objeto - act_class_assistance
						self::saveClassAssistance($classAssistance, $db);
						
						$i++;
					}
					
					self::closeClass($idClass, $db);
					
					/**
					 * exclui registros de relacionamento entre turma e atendimento na tabela "act_class_assistance"
					 */
					//método não mais utilizado porque registros não serão excluídos
					//ClassBusiness::dropClassAssistance($idClass, $db);
					
					if($mt) $db->commit();
				}
				else
				{
					trigger_error(parent::getLabelResources()->notAssistanceExist->alert, E_USER_ERROR);
				}
			}
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->migrateclass->error->failDB, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega todas as atividades associadas com a uma turma específica
	 */
	public static function loadAllActivityByClass($idClass = null)
	{
		try
		{	
			$obj = new ActivityClass();
	
			if($idClass != null)
			{	
				$where = $obj->getAdapter()->quoteInto(ACC_ID_CLASS.' in (?)', $idClass);
			}
			
			return $obj->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityClass->load->fail, E_USER_ERROR);		
		}
	}
	
	/**
	 * Carrega todas as atividades associadas com a uma atividade detalhada específica
	 */
	public static function loadAllActivityByActivityDetail($idActivityDetail = null)
	{
		try
		{	
			$obj = new ActivityClass();
	
			if($idActivityDetail != null)
			{	
				$where = $obj->getAdapter()->quoteInto(ACC_ID_ACTIVITY_DETAIL.' in (?)', $idActivityDetail);
			}
			
			return $obj->fetchAll($where);
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->activityClass->load->fail, E_USER_ERROR);		
		}
	}
	
	/**
	 * Exclui um ou mais relacionamento entre turma e atendimento
	 */
	
//	método não mais utilizado porque registros não serão excluídos
//	public static function dropClassAssistance($id, &$db = null)
//	{	
//		try
//		{
//			$data[CLA_ID_CLASS. ' in(?)'] = $id;
//			$res = self::deleteByQuery($data, $db);
//				
//			return $res; 
//		}
//		catch(Zend_Exception $e)
//		{
//			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
//			trigger_error(parent::getLabelResources()->classassistance->delete->fail, E_USER_ERROR);
//		}
//	}
	
	/**
	 * Exclui uma ou mais turma
	 */
	 
//	método não mais utilizado porque registros não serão excluídos
//	public static function drop($id, &$db = null)
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
//			$table = new ClassModel();
//			$res = 0;
//			if(!is_null($id) || !empty($id))
//			{
//				$where = $table->getAdapter()->quoteInto(CLS_ID_CLASS.' in (?)', $id);
//				
//				$res = $table->delete($where);
//
//				if($mt) $db->commit();
//				
//				Logger::loggerOperation('Relacionamento entre turma de id = '.$id.' e seu respectivo atendimento excluído.');
//			}
//			
//			return $res; 
//		}
//		catch(Zend_Exception $e)
//		{
//			$db->rollback();
//			$db->closeConnection();
//			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
//			trigger_error(parent::getLabelResources()->actclass->delete->fail, E_USER_ERROR);
//		}
//	}
	
	/**
	 * Exclui um ou mais relacionamentos entre turma e atividade dado o id da turma
	 */

//	método não mais utilizado porque registros não serão excluídos
//	public static function dropActivityClass($id, &$db = null)
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
//			$table = new ActivityClass();
//			$res = 0;
//			if(!is_null($id) || !empty($id))
//			{
//				$where = $table->getAdapter()->quoteInto(ACC_ID_ACTIVITY_CLASS.' = ?', $id);
//				
//				$res = $table->delete($where);
//
//				if($mt) $db->commit();
//				
//				Logger::loggerOperation('Relacionamento entre turma e detalhamento de atividade de id = '.$id.' foi excluído.');
//			}
//			
//			return $res; 
//		}
//		catch(Zend_Exception $e)
//		{
//			$db->rollback();
//			$db->closeConnection();
//			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
//			trigger_error(parent::getLabelResources()->activityclass->drop->fail, E_USER_ERROR);
//		}
//	}
}	