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
 * Jefferson Barros Lima  - W3S		   				24/04/2008	                       Create file 
 * 
 */

interface BuilderQueryInterface
{
	/* Prefix Mappings*/
	/* PERSON */
	const PERSON_PREFIX 			= 'per';
	const DEFICIENCY_PREFIX 		= 'def';
	const DEFICIENCYTYPE_PREFIX 	= 'det';
	const MARITALSTATUS_PREFIX 		= 'mts';
	const RACE_PREFIX 				= 'rac';
	const NATIONALITY_PREFIX 		= 'nat';
	const DOCUMENT_PREFIX 			= 'doc';
	const CTPS_PREFIX 				= 'ctp';
	const CIVILCERTIFICATE_PREFIX 	= 'ccf';
	const PERSONTELEPHONE_PREFIX 	= 'prt';
	const PERSONADDRESSTEMP_PREFIX 	= 'pat';

	/* FAMILY */
	const REPRESENTATIVE_PREFIX		= 'rep';
	const FAMILYID_PREFIX			= 'fid';
	const FAMILY_PREFIX				= 'fam';
	const KINSHIPTYPE_PREFIX		= 'kst';
	
	/* INCOME - EMPLOYMENT*/
	const INCOME_PREFIX				= 'inc';
	const INCOMETYPE_PREFIX			= 'ict';
	const EMPLOYMENT_PREFIX			= 'emp';
	const EMPLOYMENTSTATUS_PREFIX 	= 'ems';
	const EMPLOYMENTTELEPHONE_PREFIX= 'emt';

	/* TELEPHONE */
	const TELEPHONE_PREFIX			= 'tnb';
	const TELEPHONETYPE_PREFIX		= 'ttp';

	/* EXPENSE */
	const EXPENSE_PREFIX			= 'exp';
	const EXPENSETYPE_PREFIX		= 'ext';

	/* ASSITANCE */
	const ASSISTANCE_PREFIX			= 'ast';
	const PROGRAM_PREFIX			= 'pgr';
	const PROGRAMTYPE_PREFIX		= 'pgt';
	const TARGETMARKET_PREFIX		= 'tmk';

	/* GENERAL ASSITANCE */
	const GENERALASSISTANCE_PREFIX			= 'gas';
	const ASSISTANCEBENEFIT_PREFIX			= 'abf';
	const ASSISTANCEBENEFITTYPE_PREFIX		= 'abt';

	/* ESPECIAL ASSITANCE */
	const ESPECIALASSISTANCE_PREFIX			= 'eas';
	const OFFICIALLETTERORIGIN_PREFIX		= 'olo';
	const LAWSUITORIGIN_PREFIX				= 'lwo';
	
	/* HEALTH */
	const HEALTH_PREFIX						= 'hlt';
	const FRAMEWORKHEALTH_PREFIX			= 'fhl';
	const FRAMEWORKHEALTHTYPE_PREFIX		= 'fht';
	const PREGNANCY_PREFIX					= 'prg';

	/* EDUCATION - LEVELINSTRUCTION */
	const LEVELINSTRUCTION_PREFIX			= 'lit';
	const DEGREETYPE_PREFIX					= 'dtp';
	const REGISTRATION_PREFIX				= 'reg';
	const PERIODTYPE_PREFIX					= 'pty';
	const SCHOOLYEARTYPE_PREFIX				= 'syt';
	const SCHOOL_PREFIX						= 'sch';
	const SCHOOLTYPE_PREFIX					= 'sct';

	/* SOCIAL PROGRAM */
	const SOCIALPROGRAM_PREFIX				= 'spg';
	const SOCIALPROGRAMTYPE_PREFIX			= 'scp';
	const SOCIALPROGRAMORIGIN_PREFIX		= 'spo';
	
	/* CONSANGUINE */
	const CONSANGUINE_PREFIX				= 'csg';
	const CONSANGUINETYPE_PREFIX			= 'ctp';

	/* ADDRESS */
	const ADDRESS_PREFIX					= 'adr';
	const UF_PREFIX							= 'uf';
	const CITY_PREFIX						= 'cty';
	const NEIGHBORHOOD_PREFIX				= 'nhd';
	const ADDRESSNICKNAME_PREFIX			= 'adn';
	const ADDRESSTYPE_PREFIX				= 'adt';
	const NEIGHBORHOODREGION_PREFIX			= 'nhr';
	const REGION_PREFIX						= 'rgn';
	
	/* RESIDENCE */
	const RESIDENCE_PREFIX 					= 'res';
	const FAMILYRESIDENCE_PREFIX 			= 'frs';
	const BUILDINGTYPE_PREFIX 				= 'btp';
	const MORADATYPE_PREFIX 				= 'mrt';
	const LOCALITYTYPE_PREFIX 				= 'ltp';
	const STATUSTYPE_PREFIX 				= 'stt';
	const WATERTYPE_PREFIX 					= 'wtp';
	const ILLUMINATIONTYPE_PREFIX 			= 'itp';
	const SANITARYTYPE_PREFIX 				= 'snt';
	const TRASHTYPE_PREFIX 					= 'tst';
	const SUPPLYTYPE_PREFIX 				= 'spt';
	/* UBS */
	const UBS_PREFIX						= 'ubs';
	const COVERAGEHEALTHTYPE_PREFIX			= 'cht';
	const COVERAGEADDRESS_PREFIX			= 'cad';

	/* CLASS */
	const CLASSASSISTANCE_PREFIX			= 'cla';
	const STATUSCLASS_PREFIX				= 'sts';
	const CLASSMODEL_PREFIX					= 'cls';
	const ACTIVITYCLASS_PREFIX				= 'acc';
	const ACTIVITYDETAIL_PREFIX				= 'acd';
	const CATEGORY_PREFIX					= 'cat';

	/* ENTITY */
	const ENTITY_PREFIX						= 'ent';
	const ENTITYTELEPHONE_PREFIX			= 'etp';
	const ENTITYAREA_PREFIX					= 'eta';
	const ENTITYAREATYPE_PREFIX				= 'eat';
	const ENTITYCLASSIFICATION_PREFIX		= 'enc';
	const ENTITYCLASSIFICATIONTYPE_PREFIX	= 'ect';
	const ENTITYGROUP_PREFIX				= 'eng';
	const ENTITYGROUPTYPE_PREFIX			= 'egt';

}