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
 * Jefferson Barros Lima  - W3S		   				21/02/2008	                       Create file 
 * 
 */


class Utils
{
	/**
	 * Verifica se o valor é nulo ou vazio
	 */
	public static function isEmpty($value)
	{
		if(empty($value) && $value != '0')
		{
			return true;
		}
		return false;
	}
	/**
	 * Verifica se um array é vazio
	 * A verificação ocorre somente em um nível, NÃO é recursivo.
	 */
	public static function array_is_empty($array)
	{
		$bool = TRUE;
		if(!empty($array) && is_array($array))
		{
			foreach($array as $current)
			{
				if(!Utils::isEmpty($current))
				{	
					$bool = FALSE;
				}
			}
		}
		
		return $bool;
	}
	/**
	 * Verifica se um array é vazio
	 * A verificação ocorre recursivamente.
	 */
	public static function arrayEmpty(&$array)
	{
		$bool = TRUE;
		if(is_array($array))
		{
			foreach($array as $current)
				$bool = self::arrayEmpty($current);
		}
		else
		{
			if(!empty($array))
				$bool = FALSE;
		}
		
		return $bool;
	}

	/**
	 * Carrega os tipos de documentos
	 */
	function documentOptions()
	{
		$docs = array();
		$docs[DOC_RG_NUMBER] = Constants::RG;
		$docs[DOC_CPF] = Constants::CPF;		
		$docs[CTS_NUMBER] = Constants::CTPS;
		$docs[DOC_TITLE_NUMBER] = Constants::TITLE_NUMBER;
		$docs[DOC_SUS_CARD] = Constants::SUS_CARD;
		$docs[DOC_NIS] = Constants::NIS;
		$docs[Constants::ALL] = Constants::ALL_MSG;
		
		return $docs;
	}
	
	public static function getTypes()
	{
		$docs = array(
			DOC_RG_NUMBER,
			DOC_CPF,
			DOC_NIS,
			DOC_SUS_CARD,
			DOC_TITLE_NUMBER,
			CTS_NUMBER,
			Constants::ALL
		);
		
		return $docs;
	}
	
	/**
	 * Essa função verifica diferença de idade entre duas datas
	 * Return TRUE : se $dateToday(data mais recente) é $diffDate(idade) mais novo que $dateSince(data mais antiga)
	 * A data deve estar em um dos seguintes formatos : "2008(ano) -caracter separador- 12(mês) -caracter separador- 25(dia)" 
	 */
	public static function diffDate($dateToday, $dateSince, $age)
	{	
		$daySince	= substr($dateSince,8,2);
		$monthSince	= substr($dateSince,5,2);
		$yearSince	= substr($dateSince,0,4);
		 
		$dayToday	= substr($dateToday,8,2);
		$monthToday	= substr($dateToday,5,2);
		$yearToday	= substr($dateToday,0,4);
		
		if($yearToday > $yearSince)
		{	
			$diffYear = $yearToday - $yearSince;
			if($diffYear > $age)
			{
				return true;
			}
			else
			{
				if($diffYear == $age)
				{	
					if($monthToday > $monthSince)
					{
						return true;
					}
					else
					{	
						if($monthToday == $monthSince)
						{	
							if($dayToday > $daySince)
							{
								return true;
							}
						}	
					}	
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Verifica se pessoa informada é permitida, dado a sua data de nascimento
	 */
	public static function isAdult($idPerson)
	{	
		//recupera a idade permitida para uma pessoa ser um representante legal
		$config = Zend_Registry::get(CONFIG);				
		$agePermitted = $config->relationship->representative->age->permitted;
		
		try
		{	
			$type = new Person();
			
			if(!empty($idPerson))
			{	
				//busca na tabela família "per_person" a linha referente ao "idPerson" 			
				$where = $type->getAdapter()->quoteInto(PRS_ID_PERSON.' = ?', $idPerson);
				$row = $type->fetchAll($where);
				
				$birthdate = $row->current()->{PRS_BIRTH_DATE};
				
				if($birthdate != null)
				{	
					$verifyDate = self::diffDate(date("Y-m-d"), $birthdate, $agePermitted);
					
					if($verifyDate)
					{
						return true;
					}	
					else
					{
						return false;
					}
				}
				else
				{
					trigger_error(BasicBusiness::getLabelResources()->age->invalid, E_USER_ERROR);
				}
			}			
			
			Logger::loggerOperation('Nenhum registro encontrado para a pessoa de id = '.$idPerson.' = '.implode(',' ,$idPerson));
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Verifica se pessoa informada é do sexo feminino
	 */
	public static function isFemale($idPerson)
	{	
		try
		{	
			$type = new Person();
			
			if(!empty($idPerson))
			{	
				//busca na tabela família "per_person" a linha referente ao "idPerson" 			
				$where = $type->getAdapter()->quoteInto(PRS_ID_PERSON.' = ?', $idPerson);
				$row = $type->fetchAll($where);
				
				$typeSex = $row->current()->{PRS_SEX};
				
				if($typeSex != null)
				{	
					if($typeSex == Constants::WOMAN)
					{
						return true;
					}	
					else
					{
						return false;
					}
				}
				else
				{
					trigger_error(BasicBusiness::getLabelResources()->sex->invalid, E_USER_ERROR);
				}
			}			
			
			Logger::loggerOperation('Nenhum registro encontrado para a pessoa de id = '.$idPerson.' = '.implode(',' ,$idPerson));
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->person->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Carrega da base de dados todos os registros de uma entidade e que tem um ou mais programas cadastrados 
	 */
	public static function joinBetweenTablesUseIdprogram($arrEntityAndCollProgram = null, $tableFrom = null)
	{	
		try
		{	
			$idEntity = $arrEntityAndCollProgram[ID_ENTITY];
			
			if($idEntity != null && $arrEntityAndCollProgram[COLL_ID_PROGRAM] != null && sizeof($arrEntityAndCollProgram[COLL_ID_PROGRAM]) > 0 )
			{
				Zend_Loader::loadClass(CLS_CLASSMODEL);
				Zend_Loader::loadClass(CLS_CLASSASSISTANCE);
				Zend_Loader::loadClass(CLS_ACTIVITYDETAIL);
				Zend_Loader::loadClass(CLS_PROGRAM);
				
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->setFetchMode(Zend_Db::FETCH_OBJ);
				
				foreach($arrEntityAndCollProgram[COLL_ID_PROGRAM] as $idPr)
				{					
					$select = $db->select()
			    		->from(array('tableFrom' => $tableFrom))
			    		->joinInner(array('prg' => TBL_PROGRAM),
			        		'prg.'. PGR_ID_PROGRAM .' = tableFrom.'. CLS_ID_PROGRAM)
						->where('prg.'. PGR_ID_ENTITY .' = ?', $idEntity)
						->where('prg.'. PGR_ID_PROGRAM .' = ?', $idPr);
				
					$res[] = $db->fetchAll($select);
				}		
				
				return $res;
			}
			
			trigger_error(BasicBusiness::getLabelResources()->paramInvalid->error, E_USER_ERROR);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			
			if($tableFrom == TBL_ASSISTANCE)
			{	
				trigger_error(BasicBusiness::getLabelResources()->assistance->load->fail, E_USER_ERROR);
			}
			elseif($tableFrom == TBL_CLASS)
			{
				trigger_error(BasicBusiness::getLabelResources()->actclass->load->fail, E_USER_ERROR);
			}
			elseif($tableFrom == TBL_ACTIVITY_DETAIL)
			{
				trigger_error(BasicBusiness::getLabelResources()->activityclassdetail->load->fail, E_USER_ERROR);
			}
		}
	}
	
	/**
	 * Verifica se programa a ser excluido está sendo usado pela tabela "act_class" - turma
	 */
	public static function programUsedInClass($arrEntityAndCollProgram = null)
	{		
		try
		{	
			$idEntity = $arrEntityAndCollProgram[ID_ENTITY];
			
			if($idEntity != null && $arrEntityAndCollProgram[COLL_ID_PROGRAM] != null && sizeof($arrEntityAndCollProgram[COLL_ID_PROGRAM]) > 0 )
			{	
				$result = self::joinBetweenTablesUseIdprogram($arrEntityAndCollProgram, TBL_CLASS);
					
				if($result != null && sizeof($result) > 0 )
				{	
					foreach($result as $r)
					{	
						if(sizeof($r) > 0 && sizeof($r[0]) > 0)
						{
							$objClass = $r[0];
							$ac = ClassBusiness::load($objClass->{CLS_ID_CLASS});
							return $ac->findParentRow(CLS_PROGRAM)->{PGR_ID_PROGRAM_TYPE};
						}
					}
				}
			}
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->actclass->load->fail, E_USER_ERROR);
		}
		
		return null;
	}
	  
	/**
	 * Verifica se programa a ser excluido está sendo usado pela tabela "act_activity_detail" - detalhamento de atividades em turma
	 */
	public static function programUsedInClassDetail($arrEntityAndCollProgram = null)
	{	
		try
		{
			$idEntity = $arrEntityAndCollProgram[ID_ENTITY];
			
			if($idEntity != null && $arrEntityAndCollProgram[COLL_ID_PROGRAM] != null && sizeof($arrEntityAndCollProgram[COLL_ID_PROGRAM]) > 0 )
			{	
				$result = self::joinBetweenTablesUseIdprogram($arrEntityAndCollProgram, TBL_ACTIVITY_DETAIL);
				
				if($result != null && sizeof($result) > 0 )
				{
					foreach($result as $r)
					{	
						if(sizeof($r) > 0 && sizeof($r[0]) > 0)
						{
							$objActDetail = $r[0];
							
							if($objActDetail->{PGR_ID_PROGRAM_TYPE} != null && strlen($objActDetail->{PGR_ID_PROGRAM_TYPE}) > 0)
							{
								return $objActDetail->{PGR_ID_PROGRAM_TYPE};
							}
							else
							{	
								$aac = ClassBusiness::loadAllActivityByActivityDetail($objActDetail->{ACD_ID_ACTIVITY_DETAIL});
								if(sizeof($aac) > 0)
								{		
									foreach($aac as $unique)
									{	
										return $unique->findParentRow(CLS_ACTIVITYDETAIL)->findParentRow(CLS_PROGRAM)->{PGR_ID_PROGRAM_TYPE};
									}
								}
							}
						}	
					}
				}
			}
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->activityclassdetail->load->fail, E_USER_ERROR);
		}
		
		return null;
	}
	
	/**
	 * Verifica se programa a ser excluido está sendo usado pela tabela "ast_assistance" - atendimento
	 */
	public static function programUsedInAssistance($arrEntityAndCollProgram = null)
	{	
		try
		{	
			$idEntity = $arrEntityAndCollProgram[ID_ENTITY];
			
			if($idEntity != null && $arrEntityAndCollProgram[COLL_ID_PROGRAM] != null && sizeof($arrEntityAndCollProgram[COLL_ID_PROGRAM]) > 0 )
			{
				$result = self::joinBetweenTablesUseIdprogram($arrEntityAndCollProgram, TBL_ASSISTANCE);
				
				if($result != null && sizeof($result) > 0 )
				{
					foreach($result as $r)
					{	
						if(sizeof($r) > 0 && sizeof($r[0]) > 0)
						{
							$objAssistance = $r[0];	
							$as = AssistanceBusiness::load($objAssistance->{AST_ID_ASSISTANCE});
							return $as->findParentRow(CLS_PROGRAM)->{PGR_ID_PROGRAM_TYPE};
						}
					}
				}
			}
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->assistance->load->fail, E_USER_ERROR);
		}
		
		return null;
	}
	
	
	/**
	 * Validação usada na tela de edição de uma entidade
	 * 
	 * Verifica a diferença entre os seguintes arrays:
	 * 
	 * 	- array contendo todos os "ids" tipo de programa armazenado no banco de dados na tabela "ast_program" - Programa
	 *  - array contendo todos os "ids" tipo de programa carregado no form de edição de entidade
	 * 
	 *  return : coleção de "idProgram" que tem na base de dados e nao tem na colecao vinda do form.
	 *  		 se colecao de "idProgram" vinda do form contem todos os "idProgram" persistidos, return TRUE e não pára na validação.
	 * 
	 */
	public static function analyzeDiffArrays($idEntity, $collIdProgramTypeOfForm, $collProgramByEntity)
	{	
		try
		{	
			if(!empty($idEntity))
			{	
				if(sizeof($collProgramByEntity) > 0)
				{	
					foreach($collProgramByEntity as $pr)
					{
						//armazena no array todos os "ids" de "tipo de programa" carregados do banco de dados 
						$arrayProgramTypeOfBD[] = $pr->{PGR_ID_PROGRAM_TYPE};	
					}
					
					//verifica se coleção de "ids" de "tipo de programa" vindos do form estão contidos no array retornado do banco de dados
					$resultDiff = array_diff($arrayProgramTypeOfBD, $collIdProgramTypeOfForm);
					
					//caso o banco de dados tenha um ou mais "id" do que o carregado no form, pegar o "idProgram" do respectivo "idProgramType" ausente no form 
					if(sizeof($resultDiff) > 0)
					{	
						foreach($collProgramByEntity as $uniqueProgram)
						{	
							if(in_array($uniqueProgram->{PGR_ID_PROGRAM_TYPE}, $resultDiff))
							{
								//armazena no array todos os "idProgram" que serão excluídos para averiguar se os mesmos estão sendo usados pela aplicação 
								$takeIdProgram[] = $uniqueProgram->{PGR_ID_PROGRAM};
							}
						}
						
						return $takeIdProgram;
					}
				}
					
				return null;
				
			}			
			
			trigger_error(BasicBusiness::getLabelResources()->notIdEntity, E_USER_ERROR);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->program->load->fail, E_USER_ERROR);
		}
	}
		
	/**
	 * Validação usada na tela de edição de uma atividade detalhada
	 * 
	 * Verifica a diferença entre os seguintes arrays:
	 * 
	 * 	- array contendo o "id" do programa e "id" da categoria armazenado no banco de dados na tabela "act_activity_detail" - Detalhamento de atividade
	 *  - array contendo o "id" do programa e "id" da categoria carregado no form de edição de atividade detalhada
	 * 
	 *  return : "idActivityDetail" referente a linha editada que tem na base de dados e nao tem na colecao vinda do form.
	 *  		 se arrays forem forem iguais, return TRUE e não pára na validação.
	 * 
	 */
	public static function analyzeDiffDetailActivityArrays($idActivityDetail, $arrayProgramCategory)
	{	
		try
		{	
			if(!empty($idActivityDetail))
			{	 			
				//busca na base de dados a atividade detalhada
				$objActivityDetail = ActivityDetailBusiness::findActivityDetail($idActivityDetail);
				if($objActivityDetail && sizeof($objActivityDetail) == 1)
				{	
					//constróe um array de "idProgram" e "idCategory" carregados do banco de dados 
					$arrayActivityDetailOfBD[ACD_ID_PROGRAM]	= $objActivityDetail->current()->{ACD_ID_PROGRAM};
					$arrayActivityDetailOfBD[ACD_ID_CATEGORY]	= $objActivityDetail->current()->{ACD_ID_CATEGORY};
					
					//verifica a diferença do array acima construido e array vindo do form
					$resultDiff = array_diff($arrayActivityDetailOfBD, $arrayProgramCategory);
					
					//caso tenha diferença, return TRUE 
					if(sizeof($resultDiff) > 0)
					{	
						return true;
					}
				}
					
				return false;
			}			
			
			trigger_error(BasicBusiness::getLabelResources()->notIdEntity, E_USER_ERROR);
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->program->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Verifica se atividade detalhada a ser alterada está sendo usada pela tabela "act_activity_class" - relacionamento entre turma e atividades
	 */
	public static function activityDetailUsedInClass($idActivityDetail = null)
	{		
		try
		{
			if($idActivityDetail != null)
			{
				$collObjActDetailInClass = ActivityDetailBusiness::loadActivityClass($idActivityDetail);
				if(sizeof($collObjActDetailInClass) > 0 )
				{	
					//trecho foi comentado porque registros não serão excluídos
					//foreach($collObjActDetailInClass as $aac)
					//{
					//	if(strlen($aac->{ACC_END_DATE}) <= 0 || $aac->{ACC_END_DATE} == null )						
					//	{	
							return true;
					//	}
					//}
				}
			}
			
			return false;
		}
		catch(Zend_Exception $e)
		{	
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(BasicBusiness::getLabelResources()->activityclass->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Verifica se a família do representante legal a ser excluído tem dependentes
	 */
	public static function verifyPersonIsRepresentativeAndMembers($idPerson = null, $idFamily = null)
	{
		$objFamily = FamilyBusiness::loadFamilyByIdPersonAndIdFamily(null, $idFamily);
		
		if($objFamily)
		{	
			if(sizeof($objFamily) == 1)
			{
				if($idPerson != null)
				{	
					if($objFamily->{FAM_ID_PERSON} == $idPerson)
					{
						return true;
					}
				}
				else
				{
					trigger_error(BasicBusiness::getLabelResources()->notFamilyByIdPerson->fail, E_USER_ERROR);
				}	
			}
			else
			{
				$objectRepresentative = RepresentativeBusiness::loadRepresentative($idFamily, null, null);
				$idPersonRepresentative = $objectRepresentative->current()->{REP_ID_PERSON};
					
				if($idPersonRepresentative && $idPersonRepresentative != null)
				{
					if($idPersonRepresentative == $idPerson)
					{	
						return false;
					}
				}
			}
		}
		else
		{
			trigger_error(BasicBusiness::getLabelResources()->notFamilyById->fail, E_USER_ERROR);	
		}
			
		return true;
	}
	
	/**
	 * Verifica se representante de uma família é o mesmo que está sendo passado com parâmetro.
	 */
	public static function verifyPersonIsRepresentative($idPerson = null)
	{
		$objFamily = FamilyBusiness::loadFamilyByIdPersonAndIdFamily($idPerson, null);
		
		if(sizeof($objFamily) <= 0)
		{
			return true;
		}
		else
		{
			if((sizeof($objFamily) == 1))
			{
				$of = $objFamily->current();
				
			    $objIdFamily = $of->findParentRow(CLS_FAMILY_ID);
			    
				$objsRepresentative = $objIdFamily->findDependentRowset(CLS_REPRESENTATIVE);
				
				if(sizeof($objsRepresentative) == 1)
				{
					foreach($objsRepresentative as $uniqueRepresentative)
					{
						if($uniqueRepresentative->{REP_ID_PERSON} == $of->{FAM_ID_PERSON})
						{
							Logger::loggerOperation('Pessoa de id = '.$of->{FAM_ID_PERSON}.' já é o representante da família de id = '.$of->{FAM_ID_FAMILY}.'.') ;
							return false;
						}
						else
						{
							return true;
						}
					}
				}
			}
		}
			
		trigger_error(BasicBusiness::getLabelResources()->familyUnique->fail, E_USER_ERROR);
		return false;
	}
	
	/**
	 * Verifica se família já tem o seu representante
	 */
	public static function verifyFamilyHasRepresentative($idPerson)
	{
		$objFamily = FamilyBusiness::loadFamilyByIdPersonAndIdFamily($idPerson, null);
		
		if(sizeof($objFamily) <= 0)
		{
			return true;
		}
		else
		{
			if(sizeof($objFamily) == 1)
			{	
				$of = $objFamily->current();
				
			    $objIdFamily = $of->findParentRow(CLS_FAMILY_ID);
			    
				$objsRepresentative = $objIdFamily->findDependentRowset(CLS_REPRESENTATIVE);
				
				if(sizeof($objsRepresentative) > 0)
				{
					Logger::loggerOperation('Família de id = '.$of->{FAM_ID_FAMILY}.' já tem um representante.') ;
					return false;
				}
				else
				{
					return true;
				}
			}
		}
		
		trigger_error(BasicBusiness::getLabelResources()->familyUnique->fail, E_USER_ERROR);
		return false;
	}
	
	/**
	 * Verifica se a turma a ser excluída tem um ou mais alunos matriculados
	 */
	public static function verifyPersonInClass($idClass)
	{
		$collAssistanceClass = ClassBusiness::loadAllAssistanceByClass($idClass);
		
		if(sizeof($collAssistanceClass) > 0)
		{
			Logger::loggerOperation('Turma de id = '.$idClass.' tem aluno matriculado.') ;
			
			return true;
		}
		
		Logger::loggerOperation('Turma de id = '.$idClass.' não tem aluno matriculado.') ;
		return false;
	}

	/**
	 * Constrói um mapa dada uma string e o caractere separador
	 */
	public static function buildMap($value, $separator = ';')
	{
		if(!Utils::isEmpty($value))
		{
			$m = split($separator, $value);
			foreach($m as $c)
				$map[$c] = $c;
		}
		return $map;
	}
	/**
	 * Carrega as informações de classificação de atendimento
	 */
	public static function getAssistanceClassificationMap()
	{
		$config = Zend_Registry::get(CONFIG);

		$classif[Constants::GENERAL] 	= Utils::buildMap($config->assistance->classification->general);
		$classif[Constants::GROUP] 		= Utils::buildMap($config->assistance->classification->group);
		$classif[Constants::ESPECIAL] 	= Utils::buildMap($config->assistance->classification->especial);
		return $classif;
	}
	
	/**
	 * Carrega as informações de classificação de atendimento
	 */
	public static function getProgramIdByAssistanceType($assistanceType)
	{
		$programsId = NULL;
		if(!empty($assistanceType))
		{
			$map = Utils::getAssistanceClassificationMap();
			return $map[$assistanceType];
		}
		return $programsId;
	}
	
	/**
	 * Retorna a constante indicando a classificação para um dado programa
	 * @param Integer $program - Identificador do tipo de programa
	 * @return Constant contante que indica a classificação do tipo de programa
	 */
	public static function getAssistanceClassification($program)
	{
		if(!empty($program))
		{
			$map = Utils::getAssistanceClassificationMap();
			foreach($map as $k => $value)
			{
				if(is_array($value) && in_array($program, $value))
				{
					return $k;
				}
			}
		}
		return NULL;
	}
	/**
	 * Retorna se um programa é do tipo geral
	 */
	public static function isGeneralAssistance($value)
	{
		if(!Utils::isEmpty($value))
		{
			$classif = Utils::getAssistanceClassificationMap();
			return in_array($value, $classif[Constants::GENERAL]);
		}
		return FALSE;
	}
	/**
	 * Retorna se um programa é do tipo Grupo
	 */
	public static function isGroupAssistance($value)
	{
		if(!Utils::isEmpty($value))
		{
			$classif = Utils::getAssistanceClassification();
			return in_array($value, $classif[Constants::GROUP]);
		}
		return FALSE;
	}
	/**
	 * Retorna se um programa é do tipo Especial
	 */
	public static function isEspecialAssistance($value)
	{
		if(!Utils::isEmpty($value))
		{
			$classif = Utils::getAssistanceClassification();
			return in_array($value, $classif[Constants::ESPECIAL]);
		}
		return FALSE;
	}

	/* Função de abreviação de strings */
	public static function abbreviate($str, $maxWidth=15)
	{
		if ($str == null) {
	        return null;
	    }
	    if($maxWidth < 4)
	    {
	    	return substr($str, 0, $maxWidth);
	    }
	    if (strlen($str) <= $maxWidth) {
	        return $str;
	    }
	    return substr($str, 0, $maxWidth - 3)."...";
	}
	
	/**
	 * Retorna somente os IDS de perfil do usuário logado
	 */
	public static function getProfileIdAsArray()
	{
		$profile = UserLogged::getProfiles();
		if(!empty($profile))
		{
			Zend_Loader::loadClass(CLS_AUTH_USER_PROFILE);
			foreach($profile as $current)
				$profileId[] = $current->{AUTH_ID_PROFILE};
		}
		return $profileId;
	}
}
