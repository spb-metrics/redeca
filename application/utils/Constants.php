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
 * Jefferson Barros Lima  - W3S		   				21/01/2008	                       Create file 
 * 
 */

class Constants
{
	/* Constantes */
	const ZERO 	= 0;
	const ONE 	= 1;
	const TWO 	= 2;
	
	/* Constante utilizada para setar status histórico */
	const HISTORY 		= 'h';
	
	/* Constante utilizada para setar status de desabilitado */
	const DISABLE		= 'd';
	const ENABLE		= 'e'; 
	
	const MESSAGE_KEY = 'message_key';
	const PROCESS_KEY = 'process_key';
	
	/* Constantes de arquivos */
	const F_NAME 		= 'name';
	const F_TYPE 		= 'type';
	const F_SIZE 		= 'size';
	const F_TMP_NAME 	= 'tmp_name';
	
	const ROOT_FOLDER_IMPORT = '/import';
	const FOLDER_ROOT_ZIPCODE = '/zipCodeDir/';
	const FOLDER_ROOT_SINGLEREGISTER = '/singleRegisterDir/';
	const FOLDER_ROOT_SCHOOL = '/schoolDir/';
	
 	/*Constantes de declaração dos diretorios de upload de CEP*/ 
	const FOLDER_TYPE_1 = 'uf';
	const FOLDER_TYPE_2 = 'localidade';
	const FOLDER_TYPE_3 = 'bairro';
	const FOLDER_TYPE_4 = 'logradouro';
	const FOLDER_TYPE_5 = 'apelido_log';
	
	const TYPE_1 = 'ptype1';
	const TYPE_2 = 'ptype2';
	const TYPE_3 = 'ptype3';
	const TYPE_4 = 'ptype4';
	const TYPE_5 = 'ptype5';
	
	/*Constantes de tipos de objetos do Cad Unico*/
	const ROWTYPE_FAMILY = 'obj_family';
	const ROWTYPE_PERSON = 'obj_person';
	const ARRAY_TYPE	 = 'type';
	
	const SEARCH_ADDRESS_ACTION_TYPE = 'search-address';
	const SEARCH_ZIPCODE_ACTION_TYPE = 'search-zipcode';
	
	/*Constantes de busca por documento*/
	const ALL			= 'all';
	const ALL_MSG		= 'Pesquisar em todos';
	const RG			= 'Carteira de Identidade';
	const CPF			= 'CPF';
	const CTPS 			= 'Carteira de Trabalho';
	const TITLE_NUMBER 	= 'Titulo de Leitor';
	const SUS_CARD 		= 'Cartão do SUS';
	const NIS 			= 'NIS';
	
	/*Constantes de sexo para busca de pessoa por nome*/
	const WOMAN			= 'f';
	const MAN			= 'm';
	
	/*Constantes de tipo de usuário adm = A | coordenador = E*/
	const ADMIN			= 'A';
	const COORDINATOR	= 'E';	
	
	const GROUP			= 'C_GROUP';
	const GENERAL		= 'C_GENERAL';
	const ESPECIAL		= 'C_ESPECIAL';

	// Niveis de visibilidade do atendimento
	const CONFIDENTIALITY_PUBLIC		= 0;
	const CONFIDENTIALITY_ENTITY		= 1;
	
	// Niveis de visibilidade do atendimento GERAL
	const VISIBILITY_PUBLIC		= 1;
	const VISIBILITY_ENTITY		= 2;
	const VISIBILITY_PROFILE	= 3;
	// Constants utilizadas no resumo de atendimentos
	const AST_SUMMARY_ID 			= 'AST_ID';
	const AST_SUMMARY_DESC			= 'AST_DESC';
	const AST_SUMMARY_PROGRAM_ID	= 'AST_PGR_ID';
	const AST_SUMMARY_TYPE			= 'AST_TYPE';
	const AST_USER_NAME				= 'AST_USER';
	
	// Constante para recuperar mensagem de validação em caso negativo no encerramento de um atendimento
	const NOT_ALLOWED_TO_CLOSE		= 'NOT_ALLOWED_TO_CLOSE';
	/*
	 * Constante de status do atendimento de grupo (em atendimento, em espera)
	 * Estas constantes acompanham o conteúdo da tabela act_status_class
	 */
	const ASSISTANCE_IN_PROCCESS_STATUS	= 1;
	const ASSISTANCE_WAITING_STATUS		= 2;

	// Constante para recuperação do objeto na camada de view 
	const GENERAL_ASSISTANCE_OBJECT = 'GENERAL_ASSISTANCE_OBJECT';	
	
	// Periodo
	const PERIOD_MORNING 	= 1;
	const PERIOD_AFTERNOON 	= 2;
	const PERIOD_NIGHT 		= 3;
	const PERIOD_FULLDAY 	= 4;
	// Mapa contendo os periodos para utilização em turmas inicialmente
	public static function getPeriodMap()
	{
		$labelResources = Zend_Registry::get(LABEL_RESOURCES);
		$map[self::PERIOD_MORNING] 		= $labelResources->text->period->morning;
		$map[self::PERIOD_AFTERNOON] 	= $labelResources->text->period->afternoon;
		$map[self::PERIOD_NIGHT] 		= $labelResources->text->period->night;
		$map[self::PERIOD_FULLDAY] 		= $labelResources->text->period->fullday;
		return $map;
	}
	const ZEND_ACL_ROLE 	= "user";
	
	/* Constantes para operadores do relatório */
	const EQUAL_KEY 			= "EQUAL_KEY";
	const DIFFERENT_KEY 		= "DIFFERENT_KEY";
	const GREATER_THAN_KEY 		= "GREATER_THAN_KEY";
	const GREATER_EQUAL_THAN_KEY= "GREATER_EQUAL_THAN_KEY";
	const LESS_THAN_KEY 		= "LESS_THAN_KEY";
	const LESS_EQUAL_THAN_KEY 	= "LESS_EQUAL_THAN_KEY";

	const EQUAL 			= " = ";
	const DIFFERENT 		= " <> ";
	const GREATER_THAN 		= " > ";
	const GREATER_EQUAL_THAN= " >= ";
	const LESS_THAN 		= " < ";
	const LESS_EQUAL_THAN 	= " <= ";
	
	const QUOTE_SIMPLE	 	= "'";
	
	/* Constantes para lista de espera em atendimento de turma */
	const APPLY_LIST		= 1;
	const WAIT_LIST			= 2;
	const FINISHED			= 3;
}
