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
 * Saulo Esteves Rodrigues  - W3S		   			28/01/2008	                       Create file 
 * 
 */

define('DEFAULT_INDEX_ACTION', 				'index');
define('DEFAULT_CONTAINER_ACTION', 			'container');
define('DEFAULT_NEW_ACTION', 				'new');
define('DEFAULT_ADD_ACTION', 				'add');
define('DEFAULT_VIEW_ACTION', 				'view');
define('DEFAULT_EDIT_ACTION', 				'edit');
define('DEFAULT_CONFIRM_ACTION', 			'confirm');
define('DEFAULT_DROP_ACTION', 				'drop');
define('DEFAULT_CLOSE_ACTIVITY_ACTION',		'close-activity');
define('DEFAULT_SEARCH_ACTION', 			'search');
define('DEFAULT_ATTENDANCE_ACTION',			'attendance');
define('DEFAULT_DETAIL_ACTION',				'detail');
define('DEFAULT_DETAIL_GENERAL_ACTION',		'detail-general');
define('DEFAULT_ENABLE_ACTION',				'enable');
define('DEFAULT_ACTIVATE_ACTION',			'activate');
define('DEFAULT_DISABLE_ACTION',			'disable');
define('DEFAULT_CLOSE_ACTION',				'close');
define('DEFAULT_INITIAL_NEW_ACTION',		'initial-new');
define('DEFAULT_INITIAL_ADD_ACTION',		'initial-add');
define('DEFAULT_GROUP_ACTION',				'group');
define('DEFAULT_VIEW_ACTIVATE_ACTION',		'view-activate');
define('DEFAULT_VIEW_DISABLE_ACTION',		'view-disable');
define('DEFAULT_SEARCH_BY_NAME_ACTION',		'search-by-name');
define('DEFAULT_SEARCH_BY_DOCUMENT_ACTION',	'search-by-document');
define('DEFAULT_XLS_ACTION',				'xls');
define('DEFAULT_CSV_ACTION',				'csv');
define('DEFAULT_ARFF_ACTION',				'arff');
define('DEFAULT_PDF_ACTION',				'pdf');
define('DEFAULT_VIEWENTITY_ACTION',			'view-entity');
define('DEFAULT_VIEWACTIVITY_ACTION',		'view-activity');
define('DEFAULT_VIEWATTENDANCE_ACTION',		'view-attendance');
define('DEFAULT_SINGLEREGISTER_ACTION',		'single-register');
define('DEFAULT_SCHOOL_ACTION',				'school');
define('DEFAULT_ZIPCODE_ACTION',			'zipcode');
define('DEFAULT_LISTCLASS_ACTION',			'list-class');
define('DEFAULT_LISTPERSON_ACTION',			'list-person');
define('DEFAULT_ADDPERSON_ACTION',			'add-person');
define('DEFAULT_LOAD_PROGRAM',				'load-program');
define('DEFAULT_LOAD_PROGRAM_BY_CLASS',		'load-program-by-class');
define('DEFAULT_SUCCESS_ACTION', 			'success');
define('DEFAULT_TELEPHONE_ACTION', 			'telephone');
define('DEFAULT_ENTITY_ACTION', 			'entity');
define('DEFAULT_ROLE_ACTION', 				'role');
define('DEFAULT_CONFIRM_REPRESENTATIVE_ACTION',	'confirm-representative');
define('DEFAULT_SCHOOLSUCCESS_ACTION', 		'school-success');
define('DEFAULT_MIGRATE_CLASS_ACTION', 		'migrate');
define('DEFAULT_GROUP_PERMISSION_ACTION',	'group-permission');
define('DEFAULT_GROUP_ADD_PERMISSION_ACTION', 	'add-permission');
define('DEFAULT_GROUP_DROP_PERMISSION_ACTION', 	'drop-permission');
define('DEFAULT_IS_REPRESENTATIVE_ACTION', 	'is-representative');
define('DEFAULT_VALID_ACTION', 				'valid');
define('DEFAULT_EDIT_ADDRESS_TEMP_ACTION', 	'edit-address-temp');
define('DEFAULT_ADD_ADDRESS_TEMP_ACTION', 	'add-address-temp');
define('DEFAULT_CITY_ACTION', 				'city');
define('DEFAULT_NEIGHBORHOOD_ACTION', 		'neighborhood');
define('DEFAULT_CHANGE_PASSWORD_ACTION',	'save-change-password');
define('DEFAULT_VIEW_CHANGE_PASSWORD_ACTION',	'view-change-password');
define('DEFAULT_PROCESS_SCHOOL_ACTION',		'process-school');
define('DEFAULT_CLEAR_ACTION',				'clear');
define('DEFAULT_CONTAINER_MAIN_ACTION',		'containerMain');

/*Activity Controller*/
define('DEFAULT_ASSOCIATION_ACTION',		'association');
define('DEFAULT_ACTIVITY_ACTION',			'activity');

/*Import Controller*/
define('PROCESS_ACTION',					'process');
define('PROCESS_SINGLEREGISTER_ACTION',		'process-single-register');
define('PROCESS_SCHOOL_ACTION',				'process-school');

/* SearchAddress Controller*/
define('SEARCH_ZIPCODE_ACTION',					'search-zipcode');
define('SEARCH_ADDRESS_ACTION',					'search-address');

/* AttendanceController*/
define('ASSISTANCE_ACTION',					'assistance');
define('GENERAL_VIEW_ACTION',				'viewgeneral');
define('ESPECIAL_VIEW_ACTION',				'viewespecial');
define('ESPECIAL_GENERAL_VIEW_ACTION',		'viewespecialgeneral');
define('GROUP_VIEW_ACTION',					'viewgroup');
define('GROUP_GENERAL_VIEW_ACTION',			'viewgroupgeneral');
define('GENERAL_ADD_ACTION',				'general');
define('ESPECIAL_ADD_ACTION',				'especial');
define('GROUP_ADD_ACTION',					'group');

/* ReportController */
define('REPORT_ACTION',						'report');


define('FWD_AUTH_LOGIN', 		'/auth/');
define('FWD_HOME', 				'/');


define('INDEX_CONTROLLER', 					'/index');
define('AUTH_CONTROLLER', 					'/auth');
define('AREA_CONTROLLER', 					'/area');
define('PERSON_CONTROLLER',					'/person');
define('ACTIVITY_CONTROLLER',				'/activity');
define('ATTENDANCE_CONTROLLER',				'/attendance');
define('EDUCATION_CONTROLLER',				'/education');
define('FAMILYRELATIONSHIP_CONTROLLER',		'/family-relationship');
define('BIOLOGICALRELATIONSHIP_CONTROLLER',	'/biological-relationship');
define('HEALTH_CONTROLLER',					'/health');
define('INCOME_CONTROLLER',					'/income');
define('RESIDENCE_CONTROLLER',				'/residence');
define('SEARCH_CONTROLLER',					'/search');
define('SEARCHADDRESS_CONTROLLER',			'/search-address');
define('BENEFIT_CONTROLLER',				'/benefit');
define('HISTORY_CONTROLLER',				'/history');
define('ENTITY_CONTROLLER',					'/entity');
define('USER_CONTROLLER',					'/user');
define('CLASS_CONTROLLER',					'/class');
define('ASSOCIATE_ENTITY_CONTROLLER',		'/associate-entity');
define('CLASSIFICATION_CONTROLLER',			'/classification');
define('PROGRAM_CONTROLLER',				'/program');
define('REPORT_CONTROLLER',					'/report');
define('EXPORT_CONTROLLER',					'/export');
define('NETWORK_CONTROLLER',				'/network');
define('REGION_CONTROLLER',					'/region');
define('IMPORT_CONTROLLER',					'/import');
define('PROFILE_CONTROLLER',				'/profile');
define('FAMILYEXPENSE_CONTROLLER',			'/family-expense');
define('GROUP_CONTROLLER',					'/group');
define('ADDITIONALINFORMATION_CONTROLLER',	'/additional-information');
define('ENTITYINITIAL_CONTROLLER',			'/entity-initial');
define('ACCESSDENIED_CONTROLLER',			'/access-denied');
define('ACTIVITY_DETAIL_CONTROLLER',		'/activity-detail');
define('PERSON_LOG_CONTROLLER',				'/person-log');

define('SUCCESS_CONTROLLER', 	'/success');