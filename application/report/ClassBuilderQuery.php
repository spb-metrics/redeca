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
 * @copyright Funda��o Telef�nica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Ara�atuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guaruj� - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de S�o Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de V�rzea Paulista - http://www.varzeapaulista.sp.gov.br 
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

require_once (CLS_CLASSMODEL.'.php');
require_once (CLS_CLASSASSISTANCE.'.php');
require_once (CLS_STATUSCLASS.'.php');
require_once (CLS_ACTIVITYCLASS.'.php');
require_once (CLS_ACTIVITYDETAIL.'.php');
require_once (CLS_CATEGORY.'.php');

require_once ('BuilderQueryAbstract.php');

class ClassBuilderQuery extends BuilderQueryAbstract
{
	private static $instance;
	
	/**
	 * Mapeamento das tabelas do mesmo m�dulo
	 * A chave do array � o nome da classe correspondente � tabela
	 * Os valores s�o: 
	 *  1 - Prefixo da tabela que consta como chave
	 *  2 - Boolean indicando se � a tabela principal do m�dulo (este � obrigat�rio somente para a tabela principal)
	 *  3 - Refer�ncias
	 *  3.1 - chave(Nome da classe correspondente � Tabela que tem referencia direta com esta tabela)
	 *  3.2 - campos(Prefixo da tabela principal => coluna de referencia
	 * 				Prefixo da tabela relacionada=> coluna na tabela relacionada que se relaciona com a tabela principal)
	 * OBS1: Uma vez que uma refer�ncia entre a tabela j� foi declarada, n�o � necess�rio redeclarar.
	 * Exemplo: Tabela1 (tem relacionamento com ) Tabela2
	 * Se Tabela2 tem relacionamento com Tabela1, e a declara��o de relacionamento entre Tabela1 e Tabela2
	 * j� foi mapeado, o mapeamento entre Tabela2 e Tabela1 n�o � necess�rio.
	 * 
	 * OBS2: O relacionamento entre m�dulos diferentes deve ser configurado na classe BuilderQueryAbstract
	 */
	protected static $tables = array(
		CLS_CLASSMODEL => array(
				self::_PREFIX => self::CLASSMODEL_PREFIX,
				self::_MAIN_TABLE_KEY => TRUE,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_CLASSASSISTANCE => array(
										self::CLASSMODEL_PREFIX => CLS_ID_CLASS,
										self::CLASSASSISTANCE_PREFIX => CLA_ID_CLASS),
					CLS_ACTIVITYCLASS => array(
										self::CLASSMODEL_PREFIX => CLS_ID_CLASS,
										self::ACTIVITYCLASS_PREFIX => ACC_ID_CLASS)
										)
				),
		CLS_CLASSASSISTANCE => array(
				self::_PREFIX => self::CLASSASSISTANCE_PREFIX,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_STATUSCLASS => array(
										self::CLASSASSISTANCE_PREFIX => CLA_ID_STATUS,
										self::STATUSCLASS_PREFIX => STS_ID_STATUS)
										)
				),
		CLS_ACTIVITYCLASS => array(
				self::_PREFIX => self::ACTIVITYCLASS_PREFIX,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_ACTIVITYDETAIL => array(
										self::ACTIVITYCLASS_PREFIX => ACC_ID_ACTIVITY_DETAIL,
										self::ACTIVITYDETAIL_PREFIX => ACD_ID_ACTIVITY_DETAIL)
										)
				),
		CLS_ACTIVITYDETAIL => array(
				self::_PREFIX => self::ACTIVITYDETAIL_PREFIX,
				self::_REFERENCE_FIELDS_KEY => array(
					CLS_CATEGORY => array(
										self::ACTIVITYDETAIL_PREFIX => ACD_ID_CATEGORY,
										self::CATEGORY_PREFIX => CAT_ID_CATEGORY)
										)
				),
		CLS_CATEGORY => array(self::_PREFIX => self::CATEGORY_PREFIX),
		CLS_STATUSCLASS => array(self::_PREFIX => self::STATUSCLASS_PREFIX)
	);

	private function __construct(){ /* Classe N�o pode ser instanciada fora da classe */ }
	
	public static function getInstance()
	{
        if (!isset(self::$instance))
        {
        	self::$instance = new ClassBuilderQuery();
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
//		$sql[self::_WHERE_KEY] = self::PERSON_PREFIX.'.'.PRS_ID_MARITAL_STATUS.' is null';
		if(UserLogged::isCoordinator())
		{
			$sql[self::_FROM_KEY][self::_FROM_TABLES_KEY] = TBL_PROGRAM.' pgrClsMCoord, '.$sql[self::_FROM_KEY][self::_FROM_TABLES_KEY];
			$sql[self::_WHERE_KEY] = 'pgrClsMCoord.'.PGR_ID_PROGRAM.' = '.self::CLASSMODEL_PREFIX.'.'.CLS_ID_PROGRAM.' AND '.'pgrClsMCoord.'.PGR_ID_ENTITY.' = '.UserLogged::getEntityId();
		}
		return $sql;
	}
}
