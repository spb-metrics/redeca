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
 * Jefferson Barros Lima  - W3S		   				02/04/2008	                       Create file 
 * 
 */


class TableMapping
{
	// Mapa de tabelas que são usadas na visualização de histórico
	private static $tableMap = array();
	// constants
	public static $_cn = 'class_name'; 		// Nome da classe 
	public static $_tn = 'template_name';	// Caminho/nome_do_template (sem a extensão ".phtml")
	public static $_tv = 'template_var';	// variavel esperada no template
	public static $_rn = 'resource_name';	// Nome do prefixo do arquivo de resources
	
	private function init()
	{
		self::$tableMap[TBL_HEALTH] 		= array( 
			TableMapping::$_cn => CLS_HEALTH, 
			TableMapping::$_rn => "Health",
			TableMapping::$_tv => "healthByPerson",
			TableMapping::$_tn => 'health/healthPreview'
			);
		self::$tableMap[TBL_SOCIAL_PROGRAM] = array( 
			TableMapping::$_cn => CLS_SOCIALPROGRAM, 
			TableMapping::$_rn => "Benefit",
			TableMapping::$_tv => "programsByPerson",
			TableMapping::$_tn => 'benefit/benefitPreview'
			);
		self::$tableMap[TBL_PREGNANCY] 		= array( 
			TableMapping::$_cn => CLS_PREGNANCY, 
			TableMapping::$_rn => "Health",
			TableMapping::$_tv => "pregnancyByPerson",
			TableMapping::$_tn => 'health/healthPreview'
			);
		self::$tableMap[TBL_LEVEL_INSTRUCTION]= array( 
			TableMapping::$_cn => CLS_LEVELINSTRUCTION,
			TableMapping::$_rn => "Education",
			TableMapping::$_tv => "levelInstruction",
			TableMapping::$_tn => 'education/educationPreview'
			);
		self::$tableMap[TBL_REGISTRATION] 	= array( 
			TableMapping::$_cn => CLS_REGISTRATION, 
			TableMapping::$_rn => "Education",
			TableMapping::$_tv => "levelInstruction",
			TableMapping::$_tn => 'education/educationPreview'
			);
		self::$tableMap[TBL_EXPENSE] 		= array( 
			TableMapping::$_cn => CLS_EXPENSE, 
			TableMapping::$_rn => "FamilyExpense",
			TableMapping::$_tv => "expense",
			TableMapping::$_tn => 'family-expense/expensePreview'
			);
		self::$tableMap[TBL_INCOME] 		= array( 
			TableMapping::$_cn => CLS_INCOME, 
			TableMapping::$_rn => "Income",
			TableMapping::$_tv => "person",
			TableMapping::$_tn => 'income/income-preview'
			);
		self::$tableMap[TBL_RESIDENCE] 		= array( 
			TableMapping::$_cn => CLS_RESIDENCE, 
			TableMapping::$_rn => "Residence",
			TableMapping::$_tv => "address",
			TableMapping::$_tn => 'residence/residencePreview'
			);
	}
	
	public static function getTableMap()
	{
		self::init();
		return self::$tableMap;
	} 
}
