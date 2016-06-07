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

require_once (CLS_LEVELINSTRUCTION.'.php');
require_once (CLS_REGISTRATION.'.php');
require_once (CLS_SCHOOL.'.php');
require_once (CLS_SCHOOLTYPE.'.php');
require_once (CLS_SCHOOLYEARTYPE.'.php');
require_once (CLS_DEGREETYPE.'.php');
require_once (CLS_PERIODTYPE.'.php');

require_once ('BuilderQueryAbstract.php');

class LevelInstructionBuilderQuery extends BuilderQueryAbstract
{
	private static $instance;
	
	/**
	 * Mapeamento das tabelas do mesmo módulo
	 * A chave do array é o nome da classe correspondente à tabela
	 * Os valores são: 
	 *  1 - Prefixo da tabela que consta como chave
	 *  2 - Boolean indicando se é a tabela principal do módulo (este é obrigatório somente para a tabela principal)
	 *  3 - Referências
	 *  3.1 - chave(Nome da classe correspondente à Tabela que tem referencia direta com esta tabela)
	 *  3.2 - campos(Prefixo da tabela principal => coluna de referencia
	 * 				Prefixo da tabela relacionada=> coluna na tabela relacionada que se relaciona com a tabela principal)
	 * OBS1: Uma vez que uma referência entre a tabela já foi declarada, não é necessário redeclarar.
	 * Exemplo: Tabela1 (tem relacionamento com ) Tabela2
	 * Se Tabela2 tem relacionamento com Tabela1, e a declaração de relacionamento entre Tabela1 e Tabela2
	 * já foi mapeado, o mapeamento entre Tabela2 e Tabela1 não é necessário.
	 * 
	 * OBS2: O relacionamento entre módulos diferentes deve ser configurado na classe BuilderQueryAbstract
	 */
	protected static $tables = array(
		CLS_LEVELINSTRUCTION => array(
				self::_PREFIX => self::LEVELINSTRUCTION_PREFIX,
				self::_MAIN_TABLE_KEY => TRUE,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_DEGREETYPE => array(
										self::LEVELINSTRUCTION_PREFIX => LIT_ID_DEGREE,
										self::DEGREETYPE_PREFIX => DTP_ID_DEGREE),
					CLS_REGISTRATION => array(
										self::LEVELINSTRUCTION_PREFIX => LIT_ID_LEVEL_INSTRUCTION,
										self::REGISTRATION_PREFIX => REG_ID_LEVEL_INSTRUCTION)
										)
				),
		CLS_REGISTRATION => array(
				self::_PREFIX => self::REGISTRATION_PREFIX,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_PERIODTYPE => array(
										self::REGISTRATION_PREFIX => REG_ID_PERIOD,
										self::PERIODTYPE_PREFIX => PTY_ID_PERIOD),
					CLS_SCHOOLYEARTYPE => array(
										self::REGISTRATION_PREFIX => REG_ID_SCHOOL_YEAR,
										self::SCHOOLYEARTYPE_PREFIX => SYT_ID_SCHOOL_YEAR),
					CLS_SCHOOL => array(
										self::REGISTRATION_PREFIX => REG_ID_SCHOOL,
										self::SCHOOL_PREFIX => SCH_ID_SCHOOL)
										)
				),
		CLS_SCHOOL => array(
				self::_PREFIX => self::SCHOOL_PREFIX,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_SCHOOLTYPE => array(
										self::SCHOOL_PREFIX => SCH_ID_SCHOOL_TYPE,
										self::SCHOOLTYPE_PREFIX => SCT_ID_SCHOOL_TYPE)
										)
				),
		CLS_DEGREETYPE => array(self::_PREFIX => self::DEGREETYPE_PREFIX),
		CLS_PERIODTYPE => array(self::_PREFIX => self::PERIODTYPE_PREFIX),
		CLS_SCHOOLYEARTYPE => array(self::_PREFIX => self::SCHOOLYEARTYPE_PREFIX),
		CLS_SCHOOLTYPE => array(self::_PREFIX => self::SCHOOLTYPE_PREFIX)
	);

	private function __construct(){ /* Classe Não pode ser instanciada fora da classe */ }
	
	public static function getInstance()
	{
        if (!isset(self::$instance))
        {
        	self::$instance = new LevelInstructionBuilderQuery();
    	}
    	return self::$instance;
    }
    
	public function getTableMap()
	{
		return self::$tables;
	}
	
	public function build()
	{
		$sql = $this->getBuildQuery();
		$sql[self::_WHERE_KEY] = self::REGISTRATION_PREFIX.'.'.REG_STATUS.' is null OR '.self::REGISTRATION_PREFIX.'.'.REG_STATUS.' = ""';
		return $sql;
	}
}
