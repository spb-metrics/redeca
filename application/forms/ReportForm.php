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
 * Jefferson Barros Lima  - W3S		   				02/05/2008	                       Create file 
 * 
 */

require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 */
define('REPORT_TYPE',		'reportType');
define('MODULE_LEVEL_1',	'module1');
define('MODULE_LEVEL_2',	'module2');
define('MODULE_LEVEL_3',	'module3');
define('MODULE_LEVEL_4',	'module4');
define('FILTER_LEVEL_1',	'filter1');
define('FILTER_LEVEL_2',	'filter2');
define('FILTER_LEVEL_3',	'filter3');
define('FILTER_LEVEL_4',	'filter4');
define('ORDER_LEVEL_1',		'order1');
define('ORDER_LEVEL_2',		'order2');
define('ORDER_LEVEL_3',		'order3');
define('ORDER_LEVEL_4',		'order4');
define('FILTER_VALUE_1',	'filterValue1');
define('FILTER_VALUE_2',	'filterValue2');
define('FILTER_VALUE_3',	'filterValue3');
define('FILTER_VALUE_4',	'filterValue4');
define('OPERATOR_1',		'operator1');
define('OPERATOR_2',		'operator2');
define('OPERATOR_3',		'operator3');
define('OPERATOR_4',		'operator4');
define('CHECKED',			'checked');

class ReportForm extends BasicForm
{
	private $reportType;
	private $module1;
	private	$module2;
	private $module3;
	private $module4;
	private $filter1;
	private $filter2;
	private $filter3;
	private $filter4;
	private $order1;
	private $order2;
	private $order3;
	private $order4;
	private $filterValue1;
	private $filterValue2;
	private $filterValue3;
	private $filterValue4;
	private $operator1;
	private $operator2;
	private $operator3;
	private $operator4;
	private $checked;
	
	public static function reportType(){return REPORT_TYPE;}
	public static function module1(){return MODULE_LEVEL_1;}
	public static function module2(){return MODULE_LEVEL_2;}
	public static function module3(){return MODULE_LEVEL_3;}
	public static function module4(){return MODULE_LEVEL_4;}
	public static function filter1(){return FILTER_LEVEL_1;}
	public static function filter2(){return FILTER_LEVEL_2;}
	public static function filter3(){return FILTER_LEVEL_3;}
	public static function filter4(){return FILTER_LEVEL_4;}
	public static function order1(){return ORDER_LEVEL_1;}
	public static function order2(){return ORDER_LEVEL_2;}
	public static function order3(){return ORDER_LEVEL_3;}
	public static function order4(){return ORDER_LEVEL_4;}
	public static function filterValue1(){return FILTER_VALUE_1;}
	public static function filterValue2(){return FILTER_VALUE_2;}
	public static function filterValue3(){return FILTER_VALUE_3;}
	public static function filterValue4(){return FILTER_VALUE_4;}
	public static function operator1(){return OPERATOR_1;}
	public static function operator2(){return OPERATOR_2;}
	public static function operator3(){return OPERATOR_3;}
	public static function operator4(){return OPERATOR_4;}
	public static function checked(){return CHECKED;}
	
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);

		if($_request->isPost())
		{
			$filter				= BasicForm::getFilterStripTags();
			$this->reportType	= $_request->getPost(ReportForm::reportType());
			$this->module1		= $_request->getPost(ReportForm::module1());
			$this->module2		= $_request->getPost(ReportForm::module2());
			$this->module3		= $_request->getPost(ReportForm::module3());
			$this->module4		= $_request->getPost(ReportForm::module4());
			$this->order1		= $_request->getPost(ReportForm::order1());
			$this->order2		= $_request->getPost(ReportForm::order2());
			$this->order3		= $_request->getPost(ReportForm::order3());
			$this->order4		= $_request->getPost(ReportForm::order4());
			$this->filter1		= $_request->getPost(ReportForm::filter1());
			$this->filter2		= $_request->getPost(ReportForm::filter2());
			$this->filter3		= $_request->getPost(ReportForm::filter3());
			$this->filter4		= $_request->getPost(ReportForm::filter4());
			$this->filterValue1	= $_request->getPost(ReportForm::filterValue1());
			$this->filterValue2	= $_request->getPost(ReportForm::filterValue2());
			$this->filterValue3	= $_request->getPost(ReportForm::filterValue3());
			$this->filterValue4	= $_request->getPost(ReportForm::filterValue4());
			$this->operator1	= $_request->getPost(ReportForm::operator1());
			$this->operator2	= $_request->getPost(ReportForm::operator2());
			$this->operator3	= $_request->getPost(ReportForm::operator3());
			$this->operator4	= $_request->getPost(ReportForm::operator4());
			$this->checked	= $_request->getPost(ReportForm::checked());
		}
	}
	
	public function getReportType(){ return $this->reportType; }
	public function getModule1(){ return $this->module1; }
	public function getModule2(){ return $this->module2; }
	public function getModule3(){ return $this->module3; }
	public function getModule4(){ return $this->module4; }
	public function getFilter1(){ return $this->filter1; }
	public function getFilter2(){ return $this->filter2; }
	public function getFilter3(){ return $this->filter3; }
	public function getFilter4(){ return $this->filter4; }
	public function getOrder1(){ return $this->order1; }
	public function getOrder2(){ return $this->order2; }
	public function getOrder3(){ return $this->order3; }
	public function getOrder4(){ return $this->order4; }
	public function getFilterValue1(){ return $this->filterValue1; }
	public function getFilterValue2(){ return $this->filterValue2; }
	public function getFilterValue3(){ return $this->filterValue3; }
	public function getFilterValue4(){ return $this->filterValue4; }
	public function getOperator1(){ return $this->operator1; }
	public function getOperator2(){ return $this->operator2; }
	public function getOperator3(){ return $this->operator3; }
	public function getOperator4(){ return $this->operator4; }
	public function getChecked(){ return $this->checked; }

	public function setModule1($value){ return $this->module1 = $value; }
	public function setModule2($value){ return $this->module2 = $value; }
	public function setModule3($value){ return $this->module3 = $value; }
	public function setModule4($value){ return $this->module4 = $value; }
	public function setChecked($value){ return $this->checked = $value; }
	
	public function setFilterValue1($v){ return $this->filterValue1 = $v; }
	public function setFilterValue2($v){ return $this->filterValue2 = $v; }
	public function setFilterValue3($v){ return $this->filterValue3 = $v; }
	public function setFilterValue4($v){ return $this->filterValue4 = $v; }
}
