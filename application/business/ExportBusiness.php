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
 * Lucas dos Santos Borges Corrêa  - W3S		    02/05/2008	                       Create file 
 * 
 */
 
require_once('BasicBusiness.php');

class ExportBusiness extends BasicBusiness
{
	public static function exportCsv($report, $resource)
	{
		try
		{
			if(count($report) > 0)
			{
				$config = Zend_Registry::get(CONFIG);
				// Seta tempo limite de processamento de uma requisição
				set_time_limit($config->report->process->max->time->limit);

				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");    			
    			header("Content-type: text/x-csv");
    			header("Content-Disposition: attachment; filename=search_results.csv");

				self::xlsBOF();
                
                self::buildCsvXlsStructure($report, $resource);
                
    			self::xlsEOF();
                exit();
    			
    			return true;			
			}
			else
			{
				return false;
			}
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->export->csv->fail, E_USER_ERROR);
		}
	}

	public static function exportArff($resource)
	{
		$db = Zend_Registry::get(DB_CONNECTION);		
		$db->setFetchMode(Zend_Db::FETCH_OBJ);	
				
		try
		{
			$config = Zend_Registry::get(CONFIG);
			// Seta tempo limite de processamento de uma requisição
			set_time_limit($config->report->process->max->time->limit);
			ob_start();
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=search_results.arff");

			self::buildHeaderArff($resource, $db);
			
			self::buildValuesArff($db);

			exit;
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->export->arff->fail, E_USER_ERROR);
		}
	}
	
	private function buildQueryArff()
	{
		$query = " SELECT per.".PRS_ID_PERSON." as per_id_person, per.".PRS_NAME." as per_name, per.".PRS_METANAME." as per_metaname, per.".PRS_NICKNAME." as per_nickname, per.".PRS_METANICKNAME." as per_metanickname, per.".PRS_SEX." as per_sex, per.".PRS_TATTOO." as per_tattoo, per.".PRS_NATIVE_COUNTRY." as per_native_country, ";
		$query .= "per.".PRS_ARRIVAL_DATE." as per_arrival_date, per.".PRS_DEATH_DATE." as per_death_date, per.".PRS_BIRTH_DATE." as per_birth_date, nat.".NTY_NATIONALITY." as per_nationality, rac.".RAC_RACE." as per_race, mts.".MST_MARITAL_STATUS." as per_marital_status, det.".DFT_NAME." as per_deficiency, ";
//		$query .= "doc.cpf, doc.nis, doc.sus_card, doc.ra, doc.rg_number, doc.rg_complement, doc.rg_emission_date, doc.rg_sender, doc.id_rg_uf, ";
//		$query .= "doc.title_number, doc.title_zone, doc.title_section, ctp.id_uf, ctp.number, ctp.series, ctp.emission_date, ";
//		$query .= "ccf.certificate_type, ccf.term, ccf.book, ccf.leaf, ccf.registry_office_name, prt.id_telephone, ";
		$query .= "ict.".ICT_INCOME.", inc.".ICM_REGISTER_DATE.", inc.".ICM_VALUE.", inc.".ICM_STATUS.", inc.".ICM_ID_EMP_INCOME.", ";
//		$query .= "emt.employment_status, emp.company_name, emp.start_date, emp.end_date, emp.number, emp.complement, emp.reference_point, emp.occupation, ";
//		$query .= "cst.description as cst_consanguine, csg.id_person_from, csg.id_person_to, ";
		$query .= "scp.".SPG_ID_PR_SOCIAL.", sct.".SCP_BENEFIT.", scp.".SPG_STATUS." as scp_status, ";
//		$query .= "pgn.id_pregnancy, pgn.prenatal_sis, pgn.beginning_pregnancy, pgn.met, pgn.status as pgn_status, ";
		$query .= "hlt.".HLT_ID_HEALTH.", hlt.".HLT_DRUG_USER.", hlt.".HLT_VACCINE.", hlt.".HLT_VACCINE_TO_DATE.", hlt.".HLT_HEALTH_PLAN.", hlt.".HLT_STATUS." as hlt_status, fhl.".FHL_ID_FRAMEWORK_HEALTH.", fht.".FHT_FRAMEWORK_HEALTH.", ";
		$query .= "lvl.".LIT_ID_LEVEL_INSTRUCTION.", dgr.".DTP_DEGREE.", lvl.".LIT_LAST_YEAR_STUDIED.", lvl.".LIT_STATUS." as lvl_status, lvl.".LIT_LAST_MONTH_STUDIED.", lvl.".LIT_DATE_COLLECTED.", ";
		$query .= "reg.".REG_ID_REGISTRATION.", shy.".SYT_SCHOOL_YEAR.", pdt.".PTY_PERIOD." as pdt_period, reg.".REG_STATUS." as reg_status, sch.".SCH_ID_SCHOOL.", sch.".SCH_INEP.", sch.".SCH_NAME." as sch_name, sht.".SCT_SCHOOL_TYPE.", ";
		$query .= "kin.".KST_KINSHIP.",  exp.".EXP_ID_EXPENSE.", ext.".EXT_EXPENSE.", exp.".EXP_EXPENSE_VALUE.", exp.".EXP_STATUS." as exp_status, ";
		$query .= "res.".RES_ID_RESIDENCE.", res.".RES_NUMBER." as res_number, res.".RES_COMPLEMENT." as res_complement, res.".RES_REFERENCE_POINT." as res_reference_point, res.".RES_ACCOMMODATION.", ";
		$query .= "mot.".MRT_MORADA.", stt.".STT_STATUS_TYPE.", lot.".LTP_PLACE.", blt.".BTP_BUILDING.", slt.".SPT_SUPPLY.", wat.".WTP_WATER.", ilt.".ITP_ILLUMINATION.", snt.".SNT_SANITARY.", trt.".TST_TRASH.", res.".RES_STATUS." as res_status, ";
		$query .= "adr.".ADR_ID_ADDRESS.", nbh.".NHD_NEIGHBORHOOD.", adr.".ADR_ID_ADDRESS_TYPE.", adr.".ADR_ZIP_CODE.", adr.".ADR_ADDRESS.", adr.".ADR_ADDRESS_METAFONE.", adr.".ADR_USER_INSERTED.", cit.".CTY_CITY.", ";
		$query .= "ast.".AST_ID_ASSISTANCE.", ast.".AST_BEGINNING_DATE.", ast.".AST_END_DATE_PREVISION.", ast.".AST_REAL_END_DATE.", ast.".AST_CONFIDENTIALITY.", ";
		$query .= "olo.".OLO_OFFICIAL_LETTER_ORIGIN.", lwo.".LWO_LAWSUIT_ORIGIN.", ess.".EAS_OFFICIAL_LETTER_NUMBER.", ess.".EAS_OFFICIAL_LETTER_YEAR.", ess.".EAS_LAWSUIT_NUMBER.", ess.".EAS_LAWSUIT_YEAR.", ess.".EAS_LAWSUIT_DETAIL.", ess.".EAS_RULING.", ";
		$query .= "gas.".GAS_ID_GENERAL_ASSISTANCE.", gas.".GAS_ASSISTANCE_COMMENT.", gas.".GAS_REGISTER_DATA." as gas_register_date, gas.".GAS_CONFIDENTIALITY." as gas_confidentiality, abt.".ABT_DESCRIPTION." as abt_description, ";
		$query .= "sta.".STS_STATUS." as sta_status, cls.".CLS_ID_CLASS.", cls.".CLS_VACANCY.", cls.".CLS_SCHEDULE.", cls.".CLS_PERIOD." as cls_period, cls.".CLS_NAME." as cls_name, cls.".CLS_START_DATE." as cls_start_date, cls.".CLS_END_DATE." as cls_end_date, ";
		$query .= "acl.".ACC_ID_ACTIVITY_CLASS.", acl.".ACC_START_DATE." as acl_start_date, acl.".ACC_END_DATE." as acl_end_date, acd.".ACD_ID_ACTIVITY_DETAIL.", acd.activity_detail, ";			
		$query .= "acd.".ACD_HOURLY_LOAD.", cat.".CAT_CATEGORY.", prg.".PGR_ID_PROGRAM.", pgt.".PGT_PROGRAM_TYPE.", ";
		$query .= "ent.".ENT_ID_ENTITY.", ent.".ENT_NAME." as ent_name, ent.".ENT_EMAIL.", ent.".ENT_HOMEPAGE.", ent.".ENT_LOGO_IMG.", ent.".ENT_NUMBER." as ent_number, ent.".ENT_COMPLEMENT." as ent_complement, ent.".ENT_LATITUDE.", ";
		$query .= "ent.".ENT_LONGITUDE.", ent.".ENT_CNPJ.", ent.".ENT_STATUS." as ent_status, eat.".EAT_ENTITY_AREA.", ect.".ECT_ENTITY_CLASSIFICATION.", egt.".EGT_ENTITY_GROUP."";		
										
		$query .= " FROM ".TBL_PERSON." per ";
		$query .= " LEFT OUTER JOIN ".TBL_NATIONALITY." nat ON per.".NTY_ID_NATIONALITY." = nat.".NTY_ID_NATIONALITY." ";
		$query .= " LEFT OUTER JOIN ".TBL_RACE." rac ON per.".RAC_ID_RACE." = rac.".RAC_ID_RACE." ";
		$query .= " LEFT OUTER JOIN ".TBL_MARITAL_STATUS." mts ON per.".MST_ID_MARITAL_STATUS." = mts.".MST_ID_MARITAL_STATUS." ";
		$query .= " LEFT OUTER JOIN ".TBL_DEFICIENCY." def ON per.".PRS_ID_PERSON." = def.".PRS_ID_PERSON." ";
		$query .= " LEFT OUTER JOIN ".TBL_DEFICIENCY_TYPE." det ON def.".DFT_ID_DEFICIENCY." = det.".DFT_ID_DEFICIENCY." ";			
		$query .= " LEFT OUTER JOIN ".TBL_INCOME." inc ON per.".PRS_ID_PERSON." = inc.".PRS_ID_PERSON." ";
		$query .= " LEFT OUTER JOIN ".TBL_INCOME_TYPE." ict ON inc.".ICM_ID_INCOME." = ict.".ICM_ID_INCOME." ";			
		$query .= " LEFT OUTER JOIN ".TBL_SOCIAL_PROGRAM." scp ON per.".PRS_ID_PERSON." = scp.".PRS_ID_PERSON." ";
		$query .= " LEFT OUTER JOIN ".TBL_SOCIAL_PROGRAM_TYPE." sct ON scp.".SCP_ID_SOCIAL_PROGRAM." = sct.".SCP_ID_SOCIAL_PROGRAM." ";			
		$query .= " LEFT OUTER JOIN ".TBL_HEALTH." hlt ON per.".PRS_ID_PERSON." = hlt.".PRS_ID_PERSON." ";
		$query .= " LEFT OUTER JOIN ".TBL_FRAMEWORK_HEALTH." fhl ON hlt.".HLT_ID_HEALTH." = fhl.".HLT_ID_HEALTH." ";
		$query .= " LEFT OUTER JOIN ".TBL_FRAMEWORK_HEALTH_TYPE." fht ON fhl.".FHL_ID_FRAMEWORK_HEALTH." = fht.".FHL_ID_FRAMEWORK_HEALTH." ";
		$query .= " LEFT OUTER JOIN ".TBL_LEVEL_INSTRUCTION." lvl ON per.".PRS_ID_PERSON." = lvl.".PRS_ID_PERSON." ";
		$query .= " LEFT OUTER JOIN ".TBL_DEGREE_TYPE." dgr ON lvl.".DTP_ID_DEGREE." = dgr.".DTP_ID_DEGREE." ";
		$query .= " LEFT OUTER JOIN ".TBL_REGISTRATION." reg ON lvl.".LIT_ID_LEVEL_INSTRUCTION." = reg.".LIT_ID_LEVEL_INSTRUCTION." ";
		$query .= " LEFT OUTER JOIN ".TBL_PERIOD_TYPE." pdt ON reg.".REG_ID_PERIOD." = pdt.".REG_ID_PERIOD." ";
		$query .= " LEFT OUTER JOIN ".TBL_SCHOOL_YEAR_TYPE." shy ON reg.".REG_ID_SCHOOL_YEAR." = shy.".REG_ID_SCHOOL_YEAR." ";
		$query .= " LEFT OUTER JOIN ".TBL_SCHOOL." sch ON reg.".REG_ID_SCHOOL." = sch.".REG_ID_SCHOOL." ";
		$query .= " LEFT OUTER JOIN ".TBL_SCHOOL_TYPE." sht ON sch.".SCH_ID_SCHOOL_TYPE." = sht.".SCH_ID_SCHOOL_TYPE." ";
		$query .= " LEFT OUTER JOIN ".TBL_FAMILY." fam ON per.".PRS_ID_PERSON." = fam.".PRS_ID_PERSON." ";	
		$query .= " LEFT OUTER JOIN ".TBL_KINSHIP_TYPE." kin ON fam.".KST_ID_KINSHIP." = kin.".KST_ID_KINSHIP." ";
		$query .= " LEFT OUTER JOIN ".TBL_FAMILY_ID." fid ON fam.".FAM_ID_FAMILY." = fid.".FAM_ID_FAMILY." ";
		$query .= " LEFT OUTER JOIN ".TBL_EXPENSE." exp ON fid.".FAM_ID_FAMILY." = exp.".FAM_ID_FAMILY." ";
		$query .= " LEFT OUTER JOIN ".TBL_EXPENSE_TYPE." ext ON exp.".EXP_ID_EXPENSE_TYPE." = ext.".EXT_ID_EXPENSE." ";
		$query .= " LEFT OUTER JOIN ".TBL_FAMILY_RESIDENCE." far ON fid.".FAM_ID_FAMILY." = far.".FAM_ID_FAMILY." ";
		$query .= " LEFT OUTER JOIN ".TBL_RESIDENCE." res ON far.".RES_ID_RESIDENCE." = res.".RES_ID_RESIDENCE." ";
		$query .= " LEFT OUTER JOIN ".TBL_MORADA_TYPE." mot ON res.".RES_ID_MORADA." = mot.".RES_ID_MORADA." ";
		$query .= " LEFT OUTER JOIN ".TBL_STATUS_TYPE." stt ON res.".RES_ID_STATUS." = stt.".RES_ID_STATUS." ";
		$query .= " LEFT OUTER JOIN ".TBL_LOCALITY_TYPE." lot ON res.".RES_ID_LOCALITY." = lot.".RES_ID_LOCALITY." ";
		$query .= " LEFT OUTER JOIN ".TBL_BUILDING_TYPE." blt ON res.".RES_ID_BUILDING." = blt.".RES_ID_BUILDING." ";
		$query .= " LEFT OUTER JOIN ".TBL_SUPPLY_TYPE." slt ON res.".RES_ID_SUPPLY." = slt.".RES_ID_SUPPLY." ";
		$query .= " LEFT OUTER JOIN ".TBL_WATER_TYPE." wat ON res.".RES_ID_WATER." = wat.".RES_ID_WATER." ";
		$query .= " LEFT OUTER JOIN ".TBL_ILLUMINATION_TYPE." ilt ON res.".RES_ID_ILLUMINATION." = ilt.".RES_ID_ILLUMINATION." ";
		$query .= " LEFT OUTER JOIN ".TBL_SANITARY_TYPE." snt ON res.".RES_ID_SANITARY." = snt.".RES_ID_SANITARY." ";
		$query .= " LEFT OUTER JOIN ".TBL_TRASH_TYPE." trt ON res.".RES_ID_TRASH." = trt.".RES_ID_TRASH." ";
		$query .= " LEFT OUTER JOIN ".TBL_ADDRESS." adr ON res.".ADR_ID_ADDRESS." = adr.".ADR_ID_ADDRESS." ";
		$query .= " LEFT OUTER JOIN ".TBL_NEIGHBORHOOD." nbh ON adr.".ADR_ID_NEIGHBORHOOD." = nbh.".ADR_ID_NEIGHBORHOOD." ";
		$query .= " LEFT OUTER JOIN ".TBL_CITY." cit ON nbh.".NHD_ID_CITY." = cit.".NHD_ID_CITY." ";	
		$query .= " LEFT OUTER JOIN ".TBL_ASSISTANCE." ast ON per.".PRS_ID_PERSON." = ast.".PRS_ID_PERSON." ";
		$query .= " LEFT OUTER JOIN ".TBL_ESPECIAL_ASSISTANCE." ess ON ast.".AST_ID_ASSISTANCE." = ess.".AST_ID_ASSISTANCE." ";
		$query .= " LEFT OUTER JOIN ".TBL_OFFICIAL_LETTER_ORIGIN." olo ON ess.".EAS_ID_OFFICIAL_LETTER_ORIGIN." = olo.".EAS_ID_OFFICIAL_LETTER_ORIGIN." ";
		$query .= " LEFT OUTER JOIN ".TBL_LAWSUIT_ORIGIN." lwo ON ess.".EAS_ID_LAWSUIT_ORIGIN." = lwo.".EAS_ID_LAWSUIT_ORIGIN." ";
		$query .= " LEFT OUTER JOIN ".TBL_GENERAL_ASSISTANCE." gas ON ast.".AST_ID_ASSISTANCE." = gas.".AST_ID_ASSISTANCE." ";
		$query .= " LEFT OUTER JOIN ".TBL_ASSISTANCE_BENEFIT." asb ON gas.".AST_ID_ASSISTANCE." = asb.".AST_ID_ASSISTANCE." ";
		$query .= " LEFT OUTER JOIN ".TBL_ASSISTANCE_BENEFIT_TYPE." abt ON asb.".ABT_ID_ASSISTANCE_BENEFIT_TYPE." = abt.".ABT_ID_ASSISTANCE_BENEFIT_TYPE." ";
		$query .= " LEFT OUTER JOIN ".TBL_CLASS_ASSISTANCE." cla ON ast.".AST_ID_ASSISTANCE." = cla.".AST_ID_ASSISTANCE." ";
		$query .= " LEFT OUTER JOIN ".TBL_STATUS_CLASS." sta ON cla.".STS_ID_STATUS." = sta.".STS_ID_STATUS." ";
		$query .= " LEFT OUTER JOIN ".TBL_CLASS." cls ON cla.".CLS_ID_CLASS." = cls.".CLS_ID_CLASS." ";
		$query .= " LEFT OUTER JOIN ".TBL_ACTIVITY_CLASS." acl ON cls.".CLS_ID_CLASS." = acl.".CLS_ID_CLASS." ";
		$query .= " LEFT OUTER JOIN ".TBL_ACTIVITY_DETAIL." acd ON acl.".ACD_ID_ACTIVITY_DETAIL." = acd.".ACD_ID_ACTIVITY_DETAIL." ";
		$query .= " LEFT OUTER JOIN ".TBL_CATEGORY." cat ON acd.".CAT_ID_CATEGORY." = cat.".CAT_ID_CATEGORY." ";
		$query .= " LEFT OUTER JOIN ".TBL_PROGRAM." prg ON cls.".PGR_ID_PROGRAM." = prg.".PGR_ID_PROGRAM." ";
		$query .= " LEFT OUTER JOIN ".TBL_PROGRAM_TYPE." pgt ON prg.".PGR_ID_PROGRAM_TYPE." = pgt.".PGR_ID_PROGRAM_TYPE." ";
		$query .= " LEFT OUTER JOIN ".TBL_ENTITY." ent ON prg.".ENT_ID_ENTITY." = ent.".ENT_ID_ENTITY." ";
		$query .= " LEFT OUTER JOIN ".TBL_ENTITY_AREA." ena ON ent.".ENT_ID_ENTITY." = ena.".ENT_ID_ENTITY." ";
		$query .= " LEFT OUTER JOIN ".TBL_ENTITY_AREA_TYPE." eat ON ena.".EAT_ID_ENTITY_AREA." = eat.".EAT_ID_ENTITY_AREA." ";
		$query .= " LEFT OUTER JOIN ".TBL_ENTITY_CLASSIFICATION." enc ON ent.".ENT_ID_ENTITY." = enc.".ENT_ID_ENTITY." ";
		$query .= " LEFT OUTER JOIN ".TBL_ENTITY_CLASSIFICATION_TYPE." ect ON enc.".ECT_ID_ENTITY_CLASSIFICATION." = ect.".ECT_ID_ENTITY_CLASSIFICATION." ";
		$query .= " LEFT OUTER JOIN ".TBL_ENTITY_GROUP." eng ON ent.".ENT_ID_ENTITY." = eng.".ENT_ID_ENTITY." ";
		$query .= " LEFT OUTER JOIN ".TBL_ENTITY_GROUP_TYPE." egt ON eng.".EGT_ID_ENTITY_GROUP." = egt.".EGT_ID_ENTITY_GROUP." ";
//		$query .= " LEFT OUTER JOIN ".TBL_CONSANGUINE." csg ON per.id_person = csg.id_person_to ";
//		$query .= " LEFT OUTER JOIN ".TBL_CONSANGUINE_TYPE." cst ON csg.id_consanguine_type = cst.id_consanguine_type ";
//		$query .= " LEFT OUTER JOIN ".TBL_PREGNANCY." pgn ON per.id_person = pgn.id_person ";
//		$query .= " LEFT OUTER JOIN ".TBL_ADDRESS_TYPE." adt ON adr.id_address_type = adt.id_address_type ";
//		$query .= " LEFT OUTER JOIN ".TBL_REPRESENTATIVE." rep ON per.id_person = rep.id_person ";				
//		$query .= " LEFT OUTER JOIN ".TBL_EMPLOYMENT." emp ON inc.id_emp_income = emp.id_emp_income ";
//		$query .= " LEFT OUTER JOIN ".TBL_EMPLOYMENT_STATUS_TYPE." emt ON emp.id_employment_status = emt.id_employment_status ";
//		$query .= " LEFT OUTER JOIN ".TBL_DOCUMENT." doc ON per.id_person = doc.id_person ";
//		$query .= " LEFT OUTER JOIN ".TBL_CTPS." ctp ON per.id_person = ctp.id_person ";
//		$query .= " LEFT OUTER JOIN ".TBL_CIVIL_CERTIFICATE." ccf ON per.id_person = ccf.id_person";
//		$query .= " LEFT OUTER JOIN ".TBL_PERSON_TELEPHONE." prt ON per.id_person = prt.id_person";
		
		return $query;			
	}
	
	private function buildValuesArff(&$db)
	{
		$query = self::buildQueryArff();
			
		$stmt = $db->query($query);
		
		// valores				
		echo "@data\n";
		
		while($rows = $stmt->fetch())
		{
			$c = count((array)$rows);
			$i = 1;
			
			foreach($rows as $k=>$v)
			{
				if($i < $c)
				{
					if(!empty($v) || ($v != ""))
					{
						// retira os carestes " ' "
						$v = str_replace("'", "", $v);
						$v = str_replace("}", "", $v);
						$v = str_replace("{", "", $v);
						$v = preg_replace('/\s/',' ',$v);
						
						if((!is_numeric($v)) && (substr_count($v, " ") > 0))
						{
							print "'".$v."'".",";
						}
						else if($v == "0000-00-00")
						{
							print "?,";
						}
						else if($v == "m")
						{
							print "'Masculino',";	
						}
						else if($v == "f")
						{
							print "'Feminino',";
						}
						else
						{
							print "'".$v."',";
						}
					}
					else
					{
						print "?,";
					}
				}
				else if($i == $c)
				{
					if(!empty($v) || ($v != ""))
					{
						if((!is_numeric($v)) && (substr_count($v, " ") > 0))
						{
							print "'".$v."'";
						}
						else if($v == "0000-00-00")
						{
							print "?";
						}
						else if($v == "m")
						{
							print "'Masculino'";	
						}
						else if($v == "f")
						{
							print "'Feminino'";
						}
						else
						{
							print "'".$v."'";
						}
					}
					else
					{
						print "?";
					}
				}
				$i++;
				ob_end_flush();			
			}
			print "\n";
			ob_end_flush();	
		}
	}
	
	private function buildHeaderArff($resource, &$db)
	{
		$numeric = "numeric";
		$string = "string";
		$date = "date";
		
		$arrTypes = self::getColumnsAndTypes();
		
		// cabeçalho
		echo "@relation result_report\n";
		
		// atributos			
		foreach($arrTypes as $key=>$value)
		{		
			foreach($value as $kv=>$vv)
			{	
				$vv = str_replace("'", "", $vv);
				$vv = str_replace("}", "", $vv);
				$vv = str_replace("{", "", $vv);
				$vv = preg_replace('/\s/',' ',$vv);
				
				echo "@attribute ";
				echo self::getResourceText($resource,$kv,'_');
				if(($vv == $numeric) || ($vv == $string))
				{
					echo " ".$vv."\n";
				}
				else if($vv == $date)
				{
					echo " ".$vv." 'yyyy-MM-dd'\n";
				}
				else
				{										
					if(is_array($vv))
					{
						foreach($vv as $array)
						{
							$arrNominal[] = $array;
						}
					}
					else
					{
						$select = $db->select()->from($vv);
						$rows = $db->fetchAll($select);
						
						$arrUnique = array();									
						if(count($rows) > 0)
						{									
							foreach($rows as $kr=>$vr)
							{
								foreach($vr as $kvr=>$vvr)
								{
									if(is_string($vvr) && (strlen($vvr) > 1) && (!is_numeric($vvr)))
									{
										if(!array_search($vvr, $arrUnique))
										{
											$arrUnique[$vvr] = $vvr;
											$arrNominal[] = $vvr;
										}										
									}
								}
							}
						}
						unset($arrUnique);
					}					
					
					echo " {";
					$count = count($arrNominal);								
					for($i = 0; $i<=$count; $i++)
					{
						if($i < ($count-1))
						{
							$arrNominal[$i] = str_replace("'", "", $arrNominal[$i]);
							$arrNominal[$i] = str_replace("}", "", $arrNominal[$i]);
							$arrNominal[$i] = str_replace("{", "", $arrNominal[$i]);
							$arrNominal[$i] = preg_replace('/\s/',' ',$arrNominal[$i]);
							echo '"'.$arrNominal[$i].'"'.",";
						}
						else if($i == ($count-1))
						{
							$arrNominal[$i] = str_replace("'", "", $arrNominal[$i]);
							$arrNominal[$i] = str_replace("}", "", $arrNominal[$i]);
							$arrNominal[$i] = str_replace("{", "", $arrNominal[$i]);
							$arrNominal[$i] = preg_replace('/\s/',' ',$arrNominal[$i]);
							echo '"'.$arrNominal[$i].'"';
						}
					}
					echo "}\n";
					unset($arrNominal);
				}
				ob_end_flush();							
			}
			ob_end_flush();							
		}
	}

	public static function exportPdf($report, $resource)
	{
		header('Pragma: no-cache');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="example.pdf"');

		static $REGISTER_PER_PAGE 	= 39;
		static $FIRST_LINE_POSITION	= 50;
		static $LINE_HEIGHT 		= 13;
		static $LEFT_MARGIN 		= 20;
		// Fator multiplicador para o espaçamento adequado para impressão dos valores de uma coluna
		static $FACTOR 				= 8;
		try
		{			
			if(!empty($report))
			{
				$config = Zend_Registry::get(CONFIG);
				// Seta tempo limite de processamento de uma requisição
				set_time_limit($config->report->process->max->time->limit);
				$width = 0;
				$height = 595;
				$colWidth = null;

				Zend_Loader::loadClass(Zend_Pdf);
               	$pdf = new Zend_Pdf();
				
				$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
				// flag que indica primeira iteração
				$first = true;
				// Flag que sinaliza quando os dados devem ser colocados em uma nova página
				$newPage = true;
				// linha de fim dos dados em uma página
				$end = $REGISTER_PER_PAGE;
				// Contador de linhas
				$lineCount = 0;
				// Altura da página
				$pageHeight = null;
				// Contador para posicionamento das linha s no eixo y
				$i = 0;
				$pdfPage = null;
				while($res = $report->fetch())
				{
					$widthCol = 0;
					if($first)
					{
						// Calcula a largura das colunas
						foreach($res as $k=>$v)
						{
							$columnHeader = self::getResourceText($resource,$k,'_');
							if(strlen($columnHeader) > strlen($v))
							{
								$width += ( strlen($columnHeader) * $FACTOR );
								// Seta a largura da coluna
								if(empty($colWidth[$k]))
									$colWidth[$k] = ( strlen($columnHeader) * $FACTOR );
							}
							else
							{
								$width += ( strlen($v) * $FACTOR );
								// Seta a largura da coluna
								if(empty($colWidth[$v]))
									$colWidth[$k] = ( strlen($v) * $FACTOR );
							}
						}
						// Adiciona o tamanho da margem esquerda no tamanho total da pagina
						$width += $LEFT_MARGIN;
						// Se maior que tamanho máximo permitido, seta para o valor máximo
						if($width > 14400)
							$width = 14400;

						$first = false;
					}

					if($newPage)
					{
						// Se já houver pagina anterior, adiciona antes de criar uma nova
						if(!empty($pdfPage))
						{
							$pdf->pages[] = ($pdfPage);
						}
						
						$pdfPage = new Zend_Pdf_Page("$width:$height:");
						$pdfPage->setFont($font, 12);

						$pageHeight = $pdfPage->getHeight();
						$yPos = $pageHeight - ($FIRST_LINE_POSITION+$i);

						// Imprime o Header da página
						$pdfPage->drawText('Page '.(count($pdf->pages)+1) , $LEFT_MARGIN, ($pageHeight - 25));
						$date = date('c',time());
						$pdfPage->drawText($date, 200 , ($pageHeight - 25));
						$title = $resource->view->labels->title->report;
						$pdfPage->drawText($title, 500, ($pageHeight - 25));
					}
					else
					{
						$yPos = $pageHeight - ($FIRST_LINE_POSITION+$i);
					}
					// Posição x da coluna
					$widthCol = $LEFT_MARGIN;
					$f = true;
					foreach($res as $k=>$v)
					{							
						if($newPage)
						{
							if($f) {$f = false; $i += $LINE_HEIGHT;}
							$columnHeader = self::getResourceText($resource,$k,'_');
							$pdfPage->drawText(Utils::abbreviate($columnHeader,$colWidth[$k] / $FACTOR), $widthCol, $yPos);
							$pdfPage->drawText(Utils::abbreviate($v,$colWidth[$k] / $FACTOR), $widthCol, $yPos - $i);
						}
						else
							$pdfPage->drawText(Utils::abbreviate($v,$colWidth[$k] / $FACTOR), $widthCol, $yPos);
						// Atualiza a posição da coluna
						$widthCol += ($colWidth[$k]);
					}
					// Incrementa o tamanho de uma linha
					$i += $LINE_HEIGHT;
					
					if($lineCount == $end)
					{
						$newPage = TRUE;
						$i=0;
						$end += $REGISTER_PER_PAGE;
					}
					else
						$newPage = FALSE;
					
					$lineCount++;
				}
				if($lineCount != 0)
				{
					// Adiciona a última página
					$pdf->pages[] = ($pdfPage);
	
					// Renderiza o arquivo
					echo $pdf->render();
					exit;
									
	    			return true;	    				
				}
				return false;
			}
			else
			{
				return false;
			}
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->export->pdf->fail, E_USER_ERROR);
		}
	}

	public static function buildCsvXlsStructure($report, $resource)
	{
		if(!empty($report) && !empty($resource))
		{
            ob_start();
            // Contador para geração de colunas
            $i = 0;
            // Contador para geração de linhas
            $xlsRow = 1;
            // Recupera as informações linha a linha
            while($res = $report->fetch())
            {
            	foreach($res as $k=>$v)
            	{	                		
            		if(($xlsRow - 1) == 0)
            		{
                		$key = self::getResourceText($resource,$k,'_');	                		
                		// Gera o cabeçalho na linha 0
                		self::xlsWrite(($xlsRow - 1),$i,$key);
                		// Gera os dados para a linha 1
                		self::xlsWrite($xlsRow,$i,$v);
                		$i++;
            		}
            		else
            		{
                		// Gera o resultado para todas as demais linhas
                		self::xlsWrite($xlsRow,$i,$v);
                		$i++;
            		}
            	}
            	$i=0;
            	$xlsRow++;
            	ob_end_flush();
            }
			if($xlsRow != 1)
			{
				return true;
			}
			return false;
		}
	}
	public static function exportXls($report, $resource)
	{			
		try
		{							
			if(count($report) > 0)
			{
				$config = Zend_Registry::get(CONFIG);
				// Seta tempo limite de processamento de uma requisição
				set_time_limit($config->report->process->max->time->limit);
				header('Pragma: no-cache');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			    header("Content-Type: application/octet-stream");
				header('Content-Disposition: attachment; filename="search_results.xls"');

                self::xlsBOF();
				self::buildCsvXlsStructure($report, $resource);

    			self::xlsEOF();
                exit();
    			
    			return true;			
			}
			else
			{
				return false;
			}
		}
		catch(Zend_Exception $e)
		{
			Logger::loggerError("Caught exception: ".get_class($e)."\nMessage: ".$e->getMessage());
			trigger_error(parent::getLabelResources()->export->xls->fail, E_USER_ERROR);
		}
	}
	
	function xlsBOF() 
	{
	    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);  
	    return;
	}
	
	function xlsEOF() 
	{
	    echo pack("ss", 0x0A, 0x00);
	    return;
	}
		
	function xlsWrite($Row, $Col, $Value ) 
	{
	    $L = strlen($Value);
	    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
	    echo $Value;
	}
	
	function getResourceText($view, $value, $regexToSplit=null)
	{
		if(!empty($value))
		{			
			if(!empty($regexToSplit))	
			{			
				$var = split($regexToSplit,$value,2);				
			}
			else
			{
				$var = split('\.',$value,2);
			}

			if(count($var) == 1)
			{				
				$var1 = $var[0];
				if(!empty($view->view->controller->report->text->$var1))
					return $view->view->controller->report->text->$var1;
			}
			elseif(count($var) == 2)
			{				
				$var1 = $var[0];
				$var2 = $var[1];								
				if(!empty($view->view->controller->report->text->$var1->$var2))
					return $view->view->controller->report->text->$var1->$var2;
			}
			return $value;
		}
	}
	
	private function getColumnsAndTypes()
	{
		$numeric = "numeric";
		$string = "string";
		$date = "date";
		
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass(CLS_PERSONTELEPHONE);
		Zend_Loader::loadClass(CLS_DEFICIENCY);
		Zend_Loader::loadClass(CLS_DEFICIENCYTYPE);
		Zend_Loader::loadClass(CLS_RACE);
		Zend_Loader::loadClass(CLS_NATIONALITY);
		Zend_Loader::loadClass(CLS_MARITALSTATUS);
		Zend_Loader::loadClass(CLS_DOCUMENT);
		Zend_Loader::loadClass(CLS_CTPS);
		Zend_Loader::loadClass(CLS_CIVILCERTIFICATE);
		Zend_Loader::loadClass(CLS_INCOME);
		Zend_Loader::loadClass(CLS_INCOMETYPE);
		Zend_Loader::loadClass(CLS_EMPLOYMENT);
		Zend_Loader::loadClass(CLS_EMPLOYMENTPHONE);
		Zend_Loader::loadClass(CLS_EMPLOYMENTSTATUS);
		Zend_Loader::loadClass(CLS_CONSANGUINE);
		Zend_Loader::loadClass(CLS_SOCIALPROGRAM);
		Zend_Loader::loadClass(CLS_FRAMEWORKHEALTH);
		Zend_Loader::loadClass(CLS_REGISTRATION);
		Zend_Loader::loadClass(CLS_SCHOOL);
		Zend_Loader::loadClass(CLS_FAMILY);
		Zend_Loader::loadClass(CLS_REPRESENTATIVE);
		Zend_Loader::loadClass(CLS_EXPENSE);
		Zend_Loader::loadClass(CLS_RESIDENCE);
		Zend_Loader::loadClass(CLS_ASSISTANCE);
		Zend_Loader::loadClass(CLS_ASSISTANCEBENEFIT);
		Zend_Loader::loadClass(CLS_ESPECIALASSISTANCE);
		Zend_Loader::loadClass(CLS_GENERALASSISTANCE);
		Zend_Loader::loadClass(CLS_CLASSASSISTANCE);
		Zend_Loader::loadClass(CLS_CLASSMODEL);
		Zend_Loader::loadClass(CLS_ACTIVITYCLASS);
		Zend_Loader::loadClass(CLS_ACTIVITYDETAIL);
		Zend_Loader::loadClass(CLS_ACTIVITYCLASS);
		Zend_Loader::loadClass(CLS_PROGRAM);
		Zend_Loader::loadClass(CLS_ENTITYAREA);
		Zend_Loader::loadClass(CLS_ENTITYCLASSIFICATION);
		Zend_Loader::loadClass(CLS_ENTITYGROUP);		
		
		$arrTypes = array(
			// person
			TBL_PERSON => array(
				PRS_ID_PERSON => $numeric,
				PRS_NAME => $string,
				PRS_METANAME => $string,
				PRS_NICKNAME => $string,
				PRS_METANICKNAME => $string,
				PRS_SEX => array("Masculino","Feminino"),
				PRS_TATTOO => $string,
				PRS_NATIVE_COUNTRY => $string,
				PRS_ARRIVAL_DATE => $date,
				PRS_DEATH_DATE => $date,
				PRS_BIRTH_DATE => $date,
				PRS_ID_NATIONALITY => TBL_NATIONALITY,
				PRS_ID_RACE => TBL_RACE,
				PRS_ID_MARITAL_STATUS => TBL_MARITAL_STATUS
			),
			// person - deficiencia
			TBL_DEFICIENCY => array(
				DFY_ID_DEFICIENCY => TBL_DEFICIENCY_TYPE
			),
//			// person - documento
//			TBL_DOCUMENT => array(
//				DOC_CPF => $string,
//				DOC_NIS => $string,
//				DOC_SUS_CARD => $string,
//				DOC_RA => $string,
//				DOC_RG_NUMBER => $string,
//				DOC_RG_COMPLEMENT => $string,
//				DOC_RG_EMISSION_DATE => $date,
//				DOC_RG_SENDER => $string,
//				DOC_ID_RG_UF => TBL_UF,						
//				DOC_TITLE_NUMBER => $string,
//				DOC_TITLE_ZONE => $string,
//				DOC_TITLE_SECTION => $string			
//			),
//			// person - carteira trabalho
//			TBL_CTPS => array(
//				CTS_ID_UF => TBL_UF,
//				CTS_NUMBER => $numeric,
//				CTS_SERIES => $numeric,
//				CTS_EMISSION => $date
//			),
//			// person - certidao civil
//			TBL_CIVIL_CERTIFICATE => array(
//				CCF_CERTIFICATE_TYPE => $numeric,					
//				CCF_TERM => $numeric,
//				CCF_BOOK => $numeric,
//				CCF_LEAF => $numeric,
//				CCF_REGISTRY_OFFICE_NAME => $string
//			),
//			// person - telefone				
//			TBL_PERSON_TELEPHONE => array(
//				PRT_ID_TELEPHONE => $numeric
//			),
			// pessoa - renda
			TBL_INCOME => array(
				ICM_ID_INCOME => TBL_INCOME_TYPE,
				ICM_REGISTER_DATE => $date,
				ICM_VALUE => $numeric,					
				ICM_STATUS => $string,
				ICM_ID_EMP_INCOME => $numeric,
			),
//			// pessoa - emprego
//			TBL_EMPLOYMENT => array(
//				EMP_ID_EMPLOYMENT => $numeric,
//				EMP_ID_EMPLOYMENT_STATUS => TBL_EMPLOYMENT_STATUS_TYPE,
//				EMP_COMPANY_NAME => $string,
//				EMP_START_DATE => $date,
//				EMP_END_DATE => $date,
//				EMP_COMPLEMENT => $string,
//				EMP_REFERENCE_POINT => $string,
//				EMP_OCCUPATION => $string,
//			),
//			TBL_CONSANGUINE => array(
//				CSG_ID_CONSANGUINE_TYPE => TBL_CONSANGUINE_TYPE,
//				CSG_ID_PERSON_FROM => $numeric,
//				CSG_ID_PERSON_TO => $numeric
//			),
			TBL_SOCIAL_PROGRAM => array(
				SPG_ID_PR_SOCIAL => $numeric,
       			SPG_ID_SOCIAL_PROGRAM => TBL_SOCIAL_PROGRAM_TYPE,
        		SPG_STATUS => $string
			),
//			TBL_PREGNANCY => array(
//				PRG_ID_PREGNANCY => $numeric,
//        		PRG_PRENATAL_SIS => $string,
//    			PRG_BEGINNING_PREGNANCY => $date,
//        		PRG_MET => $numeric,
//        		PRG_STATUS => $string
//			),
			TBL_HEALTH => array(
				HLT_ID_HEALTH => $numeric,
        		HLT_DRUG_USER => $numeric,
        		HLT_VACCINE => $numeric,
        		HLT_VACCINE_TO_DATE => $date,
    			HLT_HEALTH_PLAN => $string,
        		HLT_STATUS => $string
			),
			TBL_FRAMEWORK_HEALTH => array(
        		FHL_ID_FRAMEWORK_HEALTH => $numeric,
        		FHL_FRAMEWORK_HEALTH_DESCRIPTION => TBL_FRAMEWORK_HEALTH_TYPE
			),
			TBL_LEVEL_INSTRUCTION => array(
				LIT_ID_LEVEL_INSTRUCTION => $numeric,
        		LIT_ID_DEGREE => TBL_DEGREE_TYPE,
        		LIT_LAST_YEAR_STUDIED => $numeric,
        		LIT_STATUS => $string,
        		LIT_LAST_MONTH_STUDIED => $numeric,
        		LIT_DATE_COLLECTED => $date
			),
			TBL_REGISTRATION => array(
				REG_ID_REGISTRATION => $numeric,
    			REG_ID_SCHOOL_YEAR => TBL_SCHOOL_YEAR_TYPE,
        		REG_ID_PERIOD => TBL_PERIOD_TYPE,
        		REG_STATUS => $string
			),
			TBL_SCHOOL => array(
				SCH_ID_SCHOOL => $numeric,
        		SCH_INEP => $string,
        		SCH_NAME => $string,
        		SCH_ID_SCHOOL_TYPE => TBL_SCHOOL_TYPE
        	),            
			TBL_FAMILY => array(
        		FAM_ID_KINSHIP => TBL_KINSHIP_TYPE
			),            
//			TBL_REPRESENTATIVE => array(
//				REP_ID_REPRESENTATIVE => $numeric
//        	),
			TBL_EXPENSE => array(
				EXP_ID_EXPENSE => $numeric,
        		EXP_ID_EXPENSE_TYPE => TBL_EXPENSE_TYPE,
        		EXP_EXPENSE_VALUE => $numeric,
        		EXP_STATUS => $string
        	),
			TBL_RESIDENCE => array(
				RES_ID_RESIDENCE => $numeric,
        		RES_NUMBER => $numeric,
        		RES_COMPLEMENT => $string,
        		RES_REFERENCE_POINT => $string,
        		RES_ACCOMMODATION => $numeric,
        		RES_ID_MORADA => TBL_MORADA_TYPE,
        		RES_ID_STATUS => TBL_STATUS_TYPE,
        		RES_ID_LOCALITY => TBL_LOCALITY_TYPE,
        		RES_ID_BUILDING => TBL_BUILDING_TYPE,
        		RES_ID_SUPPLY => TBL_SUPPLY_TYPE,
        		RES_ID_WATER => TBL_WATER_TYPE,
        		RES_ID_ILLUMINATION => TBL_ILLUMINATION_TYPE,
        		RES_ID_SANITARY => TBL_SANITARY_TYPE,
        		RES_ID_TRASH => TBL_TRASH_TYPE,
        		RES_STATUS => $string
        	),				
			TBL_ADDRESS => array(
				ADR_ID_ADDRESS => $numeric,
        		ADR_ID_NEIGHBORHOOD => TBL_NEIGHBORHOOD,
        		ADR_ID_ADDRESS_TYPE => $numeric,
        		ADR_ZIP_CODE => $string,
        		ADR_ADDRESS => $string,
        		ADR_ADDRESS_METAFONE => $string,
        		ADR_USER_INSERTED => $numeric,			
				NHD_ID_CITY => TBL_CITY
			),				
			TBL_ASSISTANCE => array(
				AST_ID_ASSISTANCE => $numeric,
        		AST_BEGINNING_DATE => $date,
        		AST_END_DATE_PREVISION => $date,
        		AST_REAL_END_DATE => $date,
        		AST_CONFIDENTIALITY => $numeric
        	),
			TBL_ESPECIAL_ASSISTANCE => array(
        		EAS_ID_OFFICIAL_LETTER_ORIGIN => TBL_OFFICIAL_LETTER_ORIGIN,
        		EAS_ID_LAWSUIT_ORIGIN => TBL_LAWSUIT_ORIGIN,
        		EAS_OFFICIAL_LETTER_NUMBER => $numeric,
        		EAS_OFFICIAL_LETTER_YEAR => $numeric,
        		EAS_LAWSUIT_NUMBER => $numeric,
        		EAS_LAWSUIT_YEAR => $numeric,
        		EAS_LAWSUIT_DETAIL => $string,
        		EAS_RULING => $string
        	),
			TBL_GENERAL_ASSISTANCE => array(
        		GAS_ID_GENERAL_ASSISTANCE => $numeric,
        		GAS_ASSISTANCE_COMMENT => $string,
        		GAS_REGISTER_DATA => $date,
        		GAS_CONFIDENTIALITY => $numeric
        	),
			TBL_ASSISTANCE_BENEFIT => array(
        		ABF_ID_ASSISTANCE_BENEFIT_TYPE => TBL_ASSISTANCE_BENEFIT_TYPE
        	),
			TBL_CLASS_ASSISTANCE => array(
        		CLA_ID_STATUS => TBL_STATUS_CLASS
        	),
			TBL_CLASS => array(
				CLS_ID_CLASS => $numeric,
        		CLS_VACANCY => $numeric,
        		CLS_SCHEDULE => $string,
        		CLS_PERIOD => $numeric,
        		CLS_NAME => $string,
        		CLS_START_DATE => $date,
        		CLS_END_DATE => $date
        	),
			TBL_ACTIVITY_CLASS => array(
				ACC_ID_ACTIVITY_CLASS => $numeric,
        		ACC_START_DATE => $date,
        		ACC_END_DATE => $date
        	),
			TBL_ACTIVITY_DETAIL => array(
				ACD_ID_ACTIVITY_DETAIL => $numeric,
        		ACD_ACTIVITY_DETAIL => $string,
    			ACD_HOURLY_LOAD => $numeric,
        		ACD_ID_CATEGORY => TBL_CATEGORY
			),
			TBL_PROGRAM => array(
				PGR_ID_PROGRAM => $numeric,
        		PGR_ID_PROGRAM_TYPE => TBL_PROGRAM_TYPE
			),
			TBL_ENTITY => array(
				ENT_ID_ENTITY => $numeric,
        		ENT_NAME => $string,
        		ENT_EMAIL => $string,
        		ENT_HOMEPAGE => $string,
        		ENT_LOGO_IMG => $string,
        		ENT_NUMBER => $numeric,
        		ENT_COMPLEMENT => $string,
        		ENT_LATITUDE => $numeric,
        		ENT_LONGITUDE => $numeric,
        		ENT_CNPJ => $string,
        		ENT_STATUS => $numeric
        	),
			TBL_ENTITY_AREA => array(
        		ETA_ID_ENTITY_AREA => TBL_ENTITY_AREA_TYPE
        	),
			TBL_ENTITY_CLASSIFICATION => array(
        		ENC_ID_ENTITY_CLASSIFICATION => TBL_ENTITY_CLASSIFICATION_TYPE
        	),
			TBL_ENTITY_GROUP => array(
        		ENG_ID_ENTITY_GROUP => TBL_ENTITY_GROUP_TYPE
        	)
		);
		
		return $arrTypes;
	}
}