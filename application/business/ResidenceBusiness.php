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

class ResidenceBusiness extends BasicBusiness
{
	/**
	 * Persiste informações no DB 
	 * @param Array $residence - array de valores a serem persistidos
	 * @param Connection $db - objeto contendo a conexão
	 * @return ID
	 */
	public static function save($residence, &$idPerson=null, &$db=null)
	{
		try
		{
			if($db == null)
			{
				$db = Zend_Registry::get(DB_CONNECTION);
				$db->beginTransaction();
				$mt = true;
			}
			
			$obj = new Residence();
			if($residence[RES_ID_RESIDENCE] == false)
			{
				$id = $obj->insert($residence);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_RESIDENCE .' [id='.$id.']');				
			}
			else
			{				
				$where = $obj->getAdapter()->quoteInto(RES_ID_RESIDENCE.' = ?', $residence[RES_ID_RESIDENCE]);
				
				$oldObj = $obj->fetchAll($where);
				
				foreach($oldObj as $old){
					if(!$old->{RES_STATUS}){
						
						$old->{RES_STATUS} = "h";
						
						$data = array(
							RES_ACCOMMODATION => $old->{RES_ACCOMMODATION},
							RES_COMPLEMENT => $old->{RES_COMPLEMENT},
							RES_ID_ADDRESS => $old->{RES_ID_ADDRESS},
							RES_ID_BUILDING => $old->{RES_ID_BUILDING},
							RES_ID_ILLUMINATION => $old->{RES_ID_ILLUMINATION},
							RES_ID_LOCALITY => $old->{RES_ID_LOCALITY},
							RES_ID_MORADA => $old->{RES_ID_MORADA},
							RES_ID_SANITARY => $old->{RES_ID_SANITARY},
							RES_ID_STATUS => $old->{RES_ID_STATUS},
							RES_ID_SUPPLY => $old->{RES_ID_SUPPLY},
							RES_ID_TRASH => $old->{RES_ID_TRASH},
							RES_ID_WATER => $old->{RES_ID_WATER},
							RES_NUMBER => $old->{RES_NUMBER},
							RES_REFERENCE_POINT => $old->{RES_REFERENCE_POINT},
							RES_STATUS => $old->{RES_STATUS}
						);
						
						$obj->update($data, $where);
						
						Logger::loggerOperation('Registro modificado na tabela '.TBL_RESIDENCE.
							' ['.RES_ID_RESIDENCE.'='.$residence[RES_ID_RESIDENCE].']');						
							
						try
						{
							$userId = UserLogged::getUserId();							
						}
						catch(UserNotLoggedException $e)
						{
							$labelResources = Zend_Registry::get(LABEL_RESOURCES);
							Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$labelResources->notPermission.' - '.$e);
							trigger_error($labelResources->notPermission , E_USER_ERROR);
						}
						$objResidence = array();
						$objResidence[PCH_ID_REFERENCE_FOREIGN]	= $old->{RES_ID_RESIDENCE};						
						$objResidence[PCH_ID_USER]				= $userId;
						$objResidence[PCH_ID_PERSON]			= $idPerson;
						
//						$admin = UserLogged::isAdministrator();						
//						if($admin === TRUE)
//						{ 
							$objResidence[PCH_ID_RESOURCE] = parent::loadIdResource(RESIDENCE_CONTROLLER);							
//						} 
//						else
//						{
//							$objResidence[PCH_ID_RESOURCE] = parent::loadIdResource(RESIDENCE_CONTROLLER);
//						}
										
						$objResidence[PCH_DATE_OPERATION]		= date("Y-m-d");
						$objResidence[PCH_TABLE_NAME]			= TBL_RESIDENCE;
						
						HistoryBusiness::save($objResidence, $db);		
						
						$arrResidence[RES_ID_ADDRESS] 		= $residence[RES_ID_ADDRESS];
						$arrResidence[RES_COMPLEMENT] 		= $residence[RES_COMPLEMENT];
						$arrResidence[RES_NUMBER] 			= $residence[RES_NUMBER];
						$arrResidence[RES_REFERENCE_POINT] 	= $residence[RES_REFERENCE_POINT];
						$arrResidence[RES_ID_BUILDING] 		= $residence[RES_ID_BUILDING];
						$arrResidence[RES_ID_ILLUMINATION] 	= $residence[RES_ID_ILLUMINATION];
						$arrResidence[RES_ID_LOCALITY] 		= $residence[RES_ID_LOCALITY];
						$arrResidence[RES_ID_MORADA] 		= $residence[RES_ID_MORADA];
						$arrResidence[RES_ID_SANITARY] 		= $residence[RES_ID_SANITARY];
						$arrResidence[RES_ID_STATUS]		= $residence[RES_ID_STATUS];
						$arrResidence[RES_ID_SUPPLY] 		= $residence[RES_ID_SUPPLY];
						$arrResidence[RES_ID_TRASH] 		= $residence[RES_ID_TRASH];
						$arrResidence[RES_ID_WATER] 		= $residence[RES_ID_WATER];
												
						$id = $obj->insert($arrResidence);
																				
						Logger::loggerOperation('Registro inserido na tabela '. TBL_RESIDENCE .' [id='.$id.']');						
						
						$oldFamilyResidence = $old->findDependentRowset(CLS_FAMILYRESIDENCE);
											
						foreach($oldFamilyResidence as $oldFam)
							$idFamily = $oldFam->{FRS_ID_FAMILY};
													
						$objFamRes = new FamilyResidence();
						
						$whereFam[] = $objFamRes->getAdapter()->quoteInto(FRS_ID_FAMILY.' = ?', $idFamily);
						
						$row = count($objFamRes->fetchAll($whereFam));
						
						if($row > 0)
						{
							$dataFamRes = array(
								FRS_ID_RESIDENCE => $id,
								FRS_LIVE_SINCE => date('Y-m-d')
							);

							$idFamRes = $objFamRes->update($dataFamRes, $whereFam);							
							Logger::loggerOperation('Registro inserido na tabela '. TBL_FAMILY_RESIDENCE .' [id='.$idFamRes.']');
						}						
					}
				}
			}
	
			if($mt)	$db->commit();
			
			return $id;
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->save->fail, E_USER_ERROR);
		}
	}
	
	public static function savePersonPhone($personPhone, $delete=null, &$db=null)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}

		try
		{
			$obj = new PersonTelephone();
			$rows = count($obj->find($personPhone[PRT_ID_PERSON], $personPhone[PRT_ID_TELEPHONE]));
			
			if($rows == 0)
			{ 
				$id = $obj->insert($personPhone);
				Logger::loggerOperation('Registro inserido na tabela '. TBL_PERSON_TELEPHONE .' [id='.$id.']');
			}
			else
			{
				$where[] = $obj->getAdapter()->quoteInto(PRT_ID_PERSON.' = ?', $personPhone[PRT_ID_PERSON]);
				$where[] = $obj->getAdapter()->quoteInto(PRT_ID_TELEPHONE.' = ?', $personPhone[PRT_ID_TELEPHONE]);
				
				if($delete === true){					
					$obj->delete($where);					
					TelephoneBusiness::drop($personPhone[PRT_ID_TELEPHONE], $db);
					Logger::loggerOperation('Registro deletado na tabela '.TBL_PERSON_TELEPHONE.
						' ['.PRT_ID_TELEPHONE.'='.$personPhone[PRT_ID_TELEPHONE].']'.
						' ['.PRT_ID_PERSON.'='.$personPhone[PRT_ID_PERSON].']');
				}
				else
				{					
					$id = $obj->update($personPhone, $where);								
					Logger::loggerOperation('Registro modificado na tabela '.TBL_PERSON_TELEPHONE.
						' ['.PRT_ID_TELEPHONE.'='.$personPhone[PRT_ID_TELEPHONE].']'.
						' ['.PRT_ID_PERSON.'='.$personPhone[PRT_ID_PERSON].']');
				}
			}

			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->phone->save->fail, E_USER_ERROR);
		}
	}

	/**
	 * Salva e relaciona a UBS com a Residencia
	 * 
	 */
	public static function saveUbs($coverageAddress, $db=NULL)
	{
		if($db == null)
		{
			$db = Zend_Registry::get(DB_CONNECTION);
			$db->beginTransaction();
			$mt = true;
		}
		
		try
		{
			$obj = new CoverageAddress();			
			$obj->insert($coverageAddress);
			
			
			if($mt) $db->commit();
		}
		catch(Zend_Exception $e)
		{
			$db->rollback();
			$db->closeConnection();
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->save->fail, E_USER_ERROR);
		}	
		
	}

	/**
	 * 
	 * Retorna uma residencia segundo o id
	 */
	public static function loadResidence($id)
	{
		$obj = new Residence();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(RES_ID_RESIDENCE.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(RES_STATUS.' not in (?)', Constants::HISTORY);
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	/**
	 * Retorna o objeto Residence dado ID_ADDRESS
	 */
	public static function findByAddress($idAddress)
	{
		try
		{
			$obj = new Residence();
			if(!empty($idAddress))
			{
				$where 	= $obj->getAdapter()->quoteInto(RES_ID_ADDRESS.' = ?', $idAddress);
        		$rows 	= $obj->fetchAll($where);

				return $rows;
			}
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todos os tipos de localização
	 */
	public static function loadLocality()
	{
		$obj = new LocalityType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(LTP_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchAll($where, LTP_PLACE);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneLocality($id)
	{
		$obj = new LocalityType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(LTP_ID_LOCALITY.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(LTP_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todos os tipos de moradia
	 */
	public static function loadMorada()
	{
		$obj = new MoradaType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(MRT_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchAll($where, MRT_MORADA);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneMorada($id)
	{
		$obj = new MoradaType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(MRT_ID_MORADA.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(MRT_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todos os tipos de situação
	 */
	public static function loadStatus()
	{
		$obj = new StatusType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(STT_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchAll($where, STT_STATUS_TYPE);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneStatus($id)
	{
		$obj = new StatusType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(STT_ID_STATUS.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(STT_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todos os tipos de construção
	 */
	public static function loadBuilding()
	{
		$obj = new BuildingType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(BTP_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchAll($where, BTP_BUILDING);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneBuilding($id)
	{
		$obj = new BuildingType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(BTP_ID_BUILDING.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(BTP_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todos os tipos de abastecimento de água
	 */
	public static function loadSupply()
	{
		$obj = new SupplyType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(SPT_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchAll($where, SPT_SUPPLY);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneSupply($id)
	{
		$obj = new SupplyType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(SPT_ID_SUPPLY.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(SPT_STATUS.' not in (?)', Constants::DISABLE);			
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todos os tipos de tratamento de água
	 */
	public static function loadWater()
	{
		$obj = new WaterType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(WTP_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchAll($where, WTP_WATER);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneWater($id)
	{
		$obj = new WaterType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(WTP_ID_WATER.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(WTP_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todos os tipos de iluminação
	 */
	public static function loadIllumination()
	{
		$obj = new IlluminationType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(ITP_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchAll($where, ITP_ILLUMINATION);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneIllumination($id)
	{
		$obj = new IlluminationType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(ITP_ID_ILLUMINATION.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(ITP_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todos os tipos de escoamento sanitário
	 */
	public static function loadSanitary()
	{
		$obj = new SanitaryType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(SNT_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchAll($where, SNT_SANITARY);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneSanitary($id)
	{
		$obj = new SanitaryType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(SNT_ID_SANITARY.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(SNT_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todos os tipos de destino do lixo
	 */
	public static function loadTrash()
	{
		$obj = new TrashType();
		try
		{
			$where = $obj->getAdapter()->quoteInto(TST_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchAll($where, TST_TRASH);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneTrash($id)
	{
		$obj = new TrashType();
		try
		{
			$where[] = $obj->getAdapter()->quoteInto(TST_ID_TRASH.' in (?)', $id);
			$where[] = $obj->getAdapter()->quoteInto(TST_STATUS.' not in (?)', Constants::DISABLE);
			return $result = $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna todas as UBS's
	 * 
	 */
	public static function loadAllUbs()
	{
		$obj = new Ubs();
		try
		{
			$result = $obj->fetchAll(null, UBS_UBS_NAME);
			foreach($result as $res)
			{
				$type = $res->findParentRow(CLS_COVERAGEHEALTHTYPE);
				if($type->{CHT_STATUS} != Constants::DISABLE)
				{
					$arrResult[] = $res;
				}
			}
			
			return $arrResult;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	/**
	 * Retorna o UBS referente a uma residencia
	 * 
	 */
	public static function loadUbs($idresidence)
	{
		$obj = new CoverageAddress();
		try
		{
			$where = $obj->getAdapter()->quoteInto(CAD_ID_RESIDENCE.' = ?', $idresidence);
			return $obj->fetchAll($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
	
	public static function loadOneUbs($id)
	{
		$obj = new Ubs();
		try
		{
			$where = $obj->getAdapter()->quoteInto(UBS_ID_UBS.' in (?)', $id);
			return $obj->fetchRow($where);			
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage().' [Stack] '.$e);
			trigger_error(parent::getLabelResources()->residence->load->fail, E_USER_ERROR);
		}
	}
}
