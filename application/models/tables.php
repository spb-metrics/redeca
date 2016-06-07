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
 * Saulo Esteves Rodrigues  - W3S		   			29/01/2008	                       Create file 
 * 
 */

//define('TBL_PERSON', 	'person');
/* Tables Name */
define('TBL_AUTH_RESOURCE','auth_resource');
define('TBL_AUTH_ROLE_RESOURCE','auth_role_resource');
define('TBL_AUTH_GROUP_RESOURCE','auth_group_resource');
define('TBL_AUTH_GROUP_ENTITY','auth_group_entity');
define('TBL_AUTH_GROUP','auth_group');
define('TBL_AUTH_ROLE','auth_role');
define('TBL_AUTH_PROFILE','auth_profile');
define('TBL_AUTH_USER_PROFILE','auth_user_profile');
define('TBL_AUTH_USER','auth_user');
define('TBL_FRAMEWORK_HEALTH_TYPE', 'hlt_framework_health_type');
define('TBL_FRAMEWORK_HEALTH', 'hlt_framework_health');
define('TBL_HEALTH', 'hlt_health');
define('TBL_PREGNANCY', 'hlt_pregnancy');
define('TBL_SCHOOL_TYPE', 'edu_school_type');
define('TBL_SCHOOL', 'edu_school');
define('TBL_REGISTRATION', 'edu_registration');
define('TBL_SCHOOL_YEAR_TYPE', 'edu_school_year_type');
define('TBL_PERIOD_TYPE', 'edu_period_type');
define('TBL_LEVEL_INSTRUCTION', 'edu_level_instruction');
define('TBL_DEGREE_TYPE', 'edu_degree_type');
define('TBL_EXPENSE', 'exp_expense');
define('TBL_EXPENSE_TYPE', 'exp_expense_type');
define('TBL_REPRESENTATIVE', 'fam_representative');
define('TBL_FAMILY', 'fam_family');
define('TBL_FAMILY_ID', 'fam_family_id');
define('TBL_KINSHIP_TYPE', 'fam_kinship_type');
define('TBL_FAMILY_RESIDENCE', 'res_family_residence');
define('TBL_RESIDENCE', 'res_residence');
define('TBL_BUILDING_TYPE', 'res_building_type');
define('TBL_MORADA_TYPE', 'res_morada_type');
define('TBL_LOCALITY_TYPE', 'res_locality_type');
define('TBL_STATUS_TYPE', 'res_status_type');
define('TBL_WATER_TYPE', 'res_water_type');
define('TBL_ILLUMINATION_TYPE', 'res_illumination_type');
define('TBL_SANITARY_TYPE', 'res_sanitary_type');
define('TBL_TRASH_TYPE', 'res_trash_type');
define('TBL_SUPPLY_TYPE', 'res_supply_type');
define('TBL_CONSANGUINE_TYPE', 'csg_consanguine_type');
define('TBL_CONSANGUINE', 'csg_consanguine');
define('TBL_SOCIAL_PROGRAM', 'sop_social_program');
define('TBL_SOCIAL_PROGRAM_TYPE', 'sop_social_program_type');
define('TBL_SOCIAL_PROGRAM_ORIGIN_TYPE', 'sop_social_program_origin_type');
define('TBL_PERSON', 'per_person');
define('TBL_DEFICIENCY', 'per_deficiency');
define('TBL_DEFICIENCY_TYPE', 'per_deficiency_type');
define('TBL_MARITAL_STATUS', 'per_marital_status');
define('TBL_RACE', 'per_race');
define('TBL_NATIONALITY', 'per_nationality');
define('TBL_DOCUMENT', 'per_document');
define('TBL_CTPS', 'per_ctps');
define('TBL_CIVIL_CERTIFICATE', 'per_civil_certificate');
define('TBL_PERSON_TELEPHONE', 'per_person_telephone');
define('TBL_PERSON_ADDRESS_TEMP', 'per_person_address_temp');
define('TBL_PERSON_CHANGE_HISTORY', 'per_person_change_history');
define('TBL_INCOME', 'emp_income');
define('TBL_INCOME_TYPE', 'emp_income_type');
define('TBL_EMPLOYMENT', 'emp_employment');
define('TBL_EMPLOYMENT_STATUS_TYPE', 'emp_employment_status_type');
define('TBL_EMPLOYMENT_TELEPHONE', 'emp_employment_telephone');
define('TBL_ESPECIAL_ASSISTANCE', 'eas_especial_assistance');
define('TBL_OFFICIAL_LETTER_ORIGIN', 'eas_official_letter_origin');
define('TBL_LAWSUIT_ORIGIN', 'eas_lawsuit_origin');
define('TBL_ASSISTANCE', 'ast_assistance');
define('TBL_PROGRAM', 'ast_program');
define('TBL_PROGRAM_TYPE', 'ast_program_type');
define('TBL_TARGET_MARKET', 'ast_target_market');
define('TBL_ASSISTANCE_PROFILE', 'ast_assistance_profile');
define('TBL_GENERAL_ASSISTANCE', 'gas_general_assistance');
define('TBL_ASSISTANCE_BENEFIT', 'gas_assistance_benefit');
define('TBL_ASSISTANCE_BENEFIT_TYPE', 'gas_assistance_benefit_type');
define('TBL_UF', 'con_uf');
define('TBL_CITY', 'con_city');
define('TBL_NEIGHBORHOOD', 'con_neighborhood');
define('TBL_NEIGHBORHOOD_REGION', 'con_neighborhood_region');
define('TBL_REGION', 'con_region');
define('TBL_TELEPHONE_TYPE', 'con_telephone_type');
define('TBL_TELEPHONE_NUMBER', 'con_telephone_number');
define('TBL_ADDRESS', 'con_address');
define('TBL_ADDRESS_NICKNAME', 'con_address_nickname');
define('TBL_ADDRESS_TYPE', 'con_address_type');
define('TBL_SOURCE_TYPE', 'con_source_type');
define('TBL_COVERAGE_ADDRESS', 'cov_coverage_address');
define('TBL_UBS', 'cov_ubs');
define('TBL_COVERAGE_HEALTH_TYPE', 'cov_coverage_health_type');
define('TBL_CLASS', 'act_class');
define('TBL_CLASS_ASSISTANCE', 'act_class_assistance');
define('TBL_STATUS_CLASS', 'act_status_class');
define('TBL_ACTIVITY_CLASS', 'act_activity_class');
define('TBL_ACTIVITY_DETAIL', 'act_activity_detail');
define('TBL_CATEGORY', 'act_category');
define('TBL_ENTITY', 'ent_entity');
define('TBL_ENTITY_TELEPHONE', 'ent_entity_telephone');
define('TBL_ENTITY_AREA_TYPE', 'ent_entity_area_type');
define('TBL_ENTITY_AREA', 'ent_entity_area');
define('TBL_ENTITY_CLASSIFICATION', 'ent_entity_classification');
define('TBL_ENTITY_CLASSIFICATION_TYPE', 'ent_entity_classification_type');
define('TBL_ENTITY_GROUP', 'ent_entity_group');
define('TBL_ENTITY_GROUP_TYPE', 'ent_entity_group_type');
define('TBL_PERSON_INSERTS_BY_USER', 'sys_person_inserts_by_user');

/* Classes Name*/
/*prefix auth */
define('CLS_AUTH_USER','User');
define('CLS_AUTH_USER_PROFILE','UserProfile');
define('CLS_AUTH_PROFILE','Profile');
define('CLS_AUTH_ROLE','Role');
define('CLS_AUTH_GROUP','Group');
define('CLS_AUTH_GROUP_ENTITY','GroupEntity');
define('CLS_AUTH_GROUP_RESOURCE','GroupResource');
define('CLS_AUTH_ROLE_RESOURCE','RoleResource');
define('CLS_AUTH_RESOURCE','Resource');
/*prefix act */
define('CLS_ACTIVITYDETAIL','ActivityDetail');
define('CLS_CATEGORY','Category');
define('CLS_ACTIVITYCLASS','ActivityClass');
define('CLS_CLASSASSISTANCE','ClassAssistance');
define('CLS_CLASSMODEL','ClassModel');
define('CLS_STATUSCLASS','StatusClass');
/*prefix ast */
define('CLS_ASSISTANCE','Assistance');
define('CLS_PROGRAM','Program');
define('CLS_PROGRAMTYPE','ProgramType');
define('CLS_TARGETMARKET','TargetMarket');
define('CLS_ASSISTANCEPROFILE','AssistanceProfile');
/*prefix con */
define('CLS_REGION','Region');
define('CLS_NEIGHBORHOODREGION','NeighborhoodRegion');
define('CLS_CITY','City');
define('CLS_NEIGHBORHOOD','Neighborhood');
define('CLS_ADDRESSTYPE','AddressType');
define('CLS_ADDRESS','Address');
define('CLS_ADDRESSNICKNAME','AddressNickname');
define('CLS_TELEPHONENUMBER','TelephoneNumber');
define('CLS_TELEPHONETYPE','TelephoneType');
define('CLS_UF','Uf');
/*prefix cov */
define('CLS_COVERAGEADDRESS','CoverageAddress');
define('CLS_COVERAGEHEALTHTYPE','CoverageHealthType');
define('CLS_UBS','Ubs');
/*prefix csg*/
define('CLS_CONSANGUINE','Consanguine');
define('CLS_CONSANGUINETYPE','ConsanguineType');
/*prefix eas */
define('CLS_ESPECIALASSISTANCE','EspecialAssistance');
define('CLS_LAWSUITORIGIN','LawsuitOrigin');
define('CLS_OFFICIALLETTERORIGIN','OfficialLetterOrigin');
/*prefix edu */
define('CLS_LEVELINSTRUCTION','LevelInstruction');
define('CLS_DEGREETYPE','DegreeType');
define('CLS_PERIODTYPE','PeriodType');
define('CLS_REGISTRATION','Registration');
define('CLS_SCHOOL','School');
define('CLS_SCHOOLTYPE','SchoolType');
define('CLS_SCHOOLYEARTYPE','SchoolYearType');
/*prefix emp_*/
define('CLS_EMPLOYMENT','Employment');
define('CLS_EMPLOYMENTSTATUS','EmploymentStatus');
define('CLS_EMPLOYMENTPHONE','EmploymentPhone');
define('CLS_INCOME','Income');
define('CLS_INCOMETYPE','IncomeType');
/*prefix ent */
define('CLS_ENTITYCLASSIFICATION','EntityClassification');
define('CLS_ENTITYAREATYPE','EntityAreaType');
define('CLS_ENTITYAREA','EntityArea');
define('CLS_ENTITY','Entity');
define('CLS_ENTITYCLASSIFICATIONTYPE','EntityClassificationType');
define('CLS_ENTITYGROUP','EntityGroup');
define('CLS_ENTITYGROUPTYPE','EntityGroupType');
define('CLS_ENTITYTELEPHONE','EntityTelephone');
/*prefix exp */
define('CLS_EXPENSETYPE','ExpenseType');
define('CLS_EXPENSE','Expense');
/*prefix fam */
define('CLS_FAMILY','Family');
define('CLS_FAMILY_ID','FamilyId');
define('CLS_KINSHIPTYPE','KinshipType');
define('CLS_REPRESENTATIVE','Representative');
/*prefix gas */
define('CLS_ASSISTANCEBENEFIT','AssistanceBenefit');
define('CLS_ASSISTANCEBENEFITTYPE','AssistanceBenefitType');
define('CLS_GENERALASSISTANCE','GeneralAssistance');
/*prefix hlt */
define('CLS_HEALTH','Health');
define('CLS_PREGNANCY','Pregnancy');
define('CLS_FRAMEWORKHEALTH','FrameworkHealth');
define('CLS_FRAMEWORKHEALTHTYPE','FrameworkHealthType');
/*prefix per */
define('CLS_PERSON','Person');
define('CLS_NATIONALITY','Nationality');
define('CLS_MARITALSTATUS','MaritalStatus');
define('CLS_DOCUMENT','Document');
define('CLS_DEFICIENCYTYPE','DeficiencyType');
define('CLS_CTPS','Ctps');
define('CLS_CIVILCERTIFICATE','CivilCertificate');
define('CLS_DEFICIENCY','Deficiency');
define('CLS_PERSONADDRESSTEMP','PersonAddressTemp');
define('CLS_PERSONCHANGEHISTORY','PersonChangeHistory');
define('CLS_PERSONTELEPHONE','PersonTelephone');
define('CLS_RACE','Race');
/*prefix res */
define('CLS_RESIDENCE','Residence');
define('CLS_SANITARYTYPE','SanitaryType');
define('CLS_MORADATYPE','MoradaType');
define('CLS_LOCALITYTYPE','LocalityType');
define('CLS_ILLUMINATIONTYPE','IlluminationType');
define('CLS_BUILDINGTYPE','BuildingType');
define('CLS_FAMILYRESIDENCE','FamilyResidence');
define('CLS_STATUSTYPE','StatusType');
define('CLS_SUPPLYTYPE','SupplyType');
define('CLS_TRASHTYPE','TrashType');
define('CLS_WATERTYPE','WaterType');
/*prefix sop */
define('CLS_SOCIALPROGRAM','SocialProgram');
define('CLS_SOCIALPROGRAMORIGIN','SocialProgramOrigin');
define('CLS_SOCIALPROGRAMTYPE','SocialProgramType');
/*prefix sys */
define('CLS_PERSONINSERTBYUSER','PersonInsertsByUser');