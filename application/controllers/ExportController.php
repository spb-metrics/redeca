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
 * Lucas dos Santos Borges Corrêa  - W3S		   	05/05/2008	                       Create file 
 * 
 */
require_once('BasicController.php');

class ExportController extends BasicController
{
	/**
	 * Mapa de operadores
	 */
	private static $operators = array(
		Constants::EQUAL_KEY 			=> Constants::EQUAL,
		Constants::DIFFERENT_KEY 		=> Constants::DIFFERENT,
		Constants::GREATER_THAN_KEY 	=> Constants::GREATER_THAN,
		Constants::GREATER_EQUAL_THAN_KEY => Constants::GREATER_EQUAL_THAN,
		Constants::LESS_THAN_KEY 		=> Constants::LESS_THAN,
		Constants::LESS_EQUAL_THAN_KEY 	=> Constants::LESS_EQUAL_THAN
	);
	
	/**
	 * Inicialização
	 */
	function init()
	{
		parent::init();
		parent::setControllerResources('Report');
		
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass('ExportBusiness');
		Zend_Loader::loadClass('ReportForm');
		Zend_Loader::loadClass('ReportBusiness');
		Zend_Loader::loadClass('ReportController');
		Zend_Loader::loadClass('ReportValidator');
		
		$frm				= new ReportForm();
		$frm->assembleRequest($this->_request);
		$this->view->form	= $frm;
	}
	
	/**
	 * Exibe relatório em html
	 */
	function indexAction()
	{
		$form = $this->view->form;
		$this->cleanViewModules();
		/* O nivel 1 é setado na camada de view separadamente */
		$this->view->module1 = ReportBusiness::getModuleRelationship();
		$this->setModuleToView($form);
		$errorMessages=null;
		ReportValidator::validateModule($form, $errorMessages);
		if(count($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;

			return;
		}
		$this->_redirect(REPORT_CONTROLLER."/".DEFAULT_INDEX_ACTION);
	}
	  
	/**
	 * Exporta relatório em formato XLS
	 */
	function xlsAction()
	{
		$form = $this->view->form;
		$mod1 = $form->getModule1();
		$mod2 = $form->getModule2();
		$mod3 = $form->getModule3();
		$mod4 = $form->getModule4();

		$this->cleanViewModules();
		$this->view->module1 = ReportBusiness::getModuleRelationship();
		
		$this->setModuleToView($form);
		
		$errorMessages=null;
		ReportValidator::validateData($form, $errorMessages);
		if(count($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->render('index');
			return ;
		}
		$report = ReportBusiness::load(array($mod1, $mod2, $mod3, $mod4), $this->assembleFilter(), $this->assembleOrder());
		$ret = ExportBusiness::exportXls($report, $this);
		
		if(!$ret) $this->_redirect(REPORT_CONTROLLER.'/'.DEFAULT_INDEX_ACTION);
		return;		
	}
	
	/**
	 * Exporta relatório em formato CSV
	 */
	function csvAction()
	{
		$form = $this->view->form;
		$mod1 = $form->getModule1();
		$mod2 = $form->getModule2();
		$mod3 = $form->getModule3();
		$mod4 = $form->getModule4();

		$this->cleanViewModules();
		$this->view->module1 = ReportBusiness::getModuleRelationship();
		
		$this->setModuleToView($form);
		
		$errorMessages=null;
		ReportValidator::validateData($form, $errorMessages);
		if(count($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->render('index');
			return ;
		}
		$report = ReportBusiness::load(array($mod1, $mod2, $mod3, $mod4), $this->assembleFilter(), $this->assembleOrder());
		$ret = ExportBusiness::exportCsv($report, $this);
		
		if(!$ret) $this->_redirect(REPORT_CONTROLLER.'/'.DEFAULT_INDEX_ACTION);
		return;
	}
	
	/**
	 * Exporta relatório em formato ARFF
	 */
	function arffAction()
	{
		$form = $this->view->form;
		$mod1 = $form->getModule1();
		$mod2 = $form->getModule2();
		$mod3 = $form->getModule3();
		$mod4 = $form->getModule4();

		$this->cleanViewModules();
		$this->view->module1 = ReportBusiness::getModuleRelationship();
		
		$this->setModuleToView($form);
		
		$errorMessages=null;
		ReportValidator::validateData($form, $errorMessages);
		if(count($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->render('index');
			return ;
		}
		$report = ReportBusiness::load(array($mod1, $mod2, $mod3, $mod4), $this->assembleFilter(), $this->assembleOrder());
		ExportBusiness::exportArff();
		
		$this->render('index');
		return;
	}
	
	/**
	 * Exporta relatório em formato PDF
	 */
	function pdfAction()
	{
		$form = $this->view->form;
		$mod1 = $form->getModule1();
		$mod2 = $form->getModule2();
		$mod3 = $form->getModule3();
		$mod4 = $form->getModule4();

		$this->cleanViewModules();
		$this->view->module1 = ReportBusiness::getModuleRelationship();
		
		$this->setModuleToView($form);
		
		$errorMessages=null;
		ReportValidator::validateData($form, $errorMessages);
		if(count($errorMessages) > 0)
		{
			$this->view->errorMessages = $errorMessages;
			$this->render('index');
			return ;
		}
		$report = ReportBusiness::load(array($mod1, $mod2, $mod3, $mod4), $this->assembleFilter(), $this->assembleOrder());
		$ret = ExportBusiness::exportPdf($report, $this);
		
		if(!$ret) $this->_redirect(REPORT_CONTROLLER.'/'.DEFAULT_INDEX_ACTION);
		return;
	}	
	
		/**
	 * Monta um array com os filtros selecionados
	 */
	private function assembleFilter()
	{
		$form = $this->view->form;
		if(!empty($form))
		{
			$filters = null;
			/* Filtros do nível 1 */
			$filter1 = $form->getFilter1();
			if(!empty($filter1))
			{
				if(is_array($filter1))
				{
					foreach($filter1 as $index => $current)
					{
						$operator = $form->getOperator1();
						$value = $form->getFilterValue1();
						(is_array($value))? $value = $value[$index] : $value = $value;
						$filter[] = $this->getFilter($current, $operator[$index], $value);
					}
				}
				else
				{
					$filter[] = $this->getFilter($filter1 ,$form->getOperator1(),$form->getFilterValue1());
				}
			}
			$filters[0] = $filter;
			/* Filtros do nível 2 */
			$filter = null;
			$filter2 = $form->getFilter2();
			if(!empty($filter2))
			{
				if(is_array($filter2))
				{
					foreach($filter2 as $index => $current)
					{
						$operator = $form->getOperator2();
						$value = $form->getFilterValue2();
						(is_array($value))? $value = $value[$index] : $value = $value;
						$filter[] = $this->getFilter($current, $operator[$index], $value);
					}
				}
				else
				{
					$filter[] = $this->getFilter($filter2 ,$form->getOperator2(),$form->getFilterValue2());
				}
			}
			$filters[1] = $filter;
			/* Filtros do nível 1 */
			$filter = null;
			$filter3 = $form->getFilter3();
			if(!empty($filter3))
			{
				if(is_array($filter3))
				{
					foreach($filter3 as $index => $current)
					{
						$operator = $form->getOperator3();
						$value = $form->getFilterValue3();
						(is_array($value))? $value = $value[$index] : $value = $value;
						$filter[] = $this->getFilter($current, $operator[$index], $value);
					}
				}
				else
				{
					$filter[] = $this->getFilter($filter3 ,$form->getOperator3(),$form->getFilterValue3());
				}
			}
			$filters[2] = $filter;
			/* Filtros do nível 1 */
			$filter = null;
			$filter4 = $form->getFilter4();
			if(!empty($filter4))
			{
				if(is_array($filter4))
				{
					foreach($filter4 as $index => $current)
					{
						$operator = $form->getOperator4();
						$value = $form->getFilterValue4();
						(is_array($value))? $value = $value[$index] : $value = $value;
						$filter[] = $this->getFilter($current, $operator[$index], $value);
					}
				}
				else
				{
					$filter[] = $this->getFilter($filter4 ,$form->getOperator4(),$form->getFilterValue4());
				}
			}
			$filters[3] = $filter;
		}
		return $filters;
	}
	
	/**
	 * Monta a construção de um filtro. Deve ser passado um valor para cada
	 * variável. Caso seja passado um array, usará a primeira posição do array para 
	 * montar o filtro.
	 */
	private function getFilter($field, $operator, $value)
	{
		if(!empty($field) && !empty($operator) && !empty($value))
		{
			(is_array($field))? $field = $field[0] : $field = $field;
			(is_array($operator))? $operator = $operator[0] : $operator = $operator;
			// Coloca o valor entre aspas
			(is_array($value))? $value = '\''.$value[0].'\'' : $value = '\''.$value.'\'';

			$operator = self::$operators[$operator];

			return $field.$operator.$value;
		}
	}
	
	private function assembleOrder()
	{
		$form = $this->view->form;
		if(!empty($form))
		{
			$order1 = $form->getOrder1();
			$order2 = $form->getOrder2();
			$order3 = $form->getOrder3();
			$order4 = $form->getOrder4();
			
			$order[]= $this->getOrderValue($order1);
			$order[]= $this->getOrderValue($order2);
			$order[]= $this->getOrderValue($order3);
			$order[]= $this->getOrderValue($order4);
		}
		return $order;
	}
	public function getOrderValue($order)
	{
		if(!empty($order))
		{
			if(is_array($order))
			{
				return $order;
			}
			else
				return array($order);
		}
	}
	/**
	 * Seta informações dos módulos e seus campos na camada de view 
	 */
	public function setModuleToView($form)
	{
		if(empty($form))
			return ;
		
		$mod1 = $form->getModule1();
		$mod2 = $form->getModule2();
		$mod3 = $form->getModule3();
		$mod4 = $form->getModule4();
		/*Modulos já selecionados que já não devem mais ser exibidos */
		if(!empty($mod1))
		{
			$this->view->fields1 = (ReportBusiness::loadFields($mod1));
			$selected = array_merge((array)$selected, (array)$mod1);
			// Carrega os Módulos de nivel 2 que podem se relacionar com o módulo do nível 1
			$this->view->module2 = ReportBusiness::getModuleRelationship($mod1,$selected);
			if(!Utils::array_is_empty($mod2))
			{
				$this->view->fields2 = (ReportBusiness::loadFields($mod2));
				$selected = array_merge((array)$selected, (array)$mod2);
				// Carrega os Módulos de nivel 3 que podem se relacionar com o módulo do nível 2
				$this->view->module3 = ReportBusiness::getModuleRelationship($mod2,$selected);
				if(!Utils::array_is_empty($mod3))
				{
					// Não são carregados pois não há filtro para o terceiro nível
//					$this->view->fields3 = (ReportBusiness::loadFields($mod3));

					$selected = array_merge((array)$selected, (array)$mod3);
					// Carrega os Módulos de nivel 4 que podem se relacionar com o módulo do nível 3
					$this->view->module4 = ReportBusiness::getModuleRelationship($mod3,$selected);
					if(!Utils::array_is_empty($mod4))
					{
						// Não são carregados pois não há filtro para o quarto nível
//						$this->view->fields4 = (ReportBusiness::loadFields($mod4));
//						$selected = array_merge((array)$selected, (array)$mod4);			
					}
				}
			}
		}		
	}

	public function cleanViewModules()
	{
		$module = 'module';
		$form = $this->view->form;
		foreach(range(1,4) as $n)
		{
			$setModule = 'setModule';
			if($n > $form->getChecked())
			{
				$setModule.=$n;
				$this->view->$module.$n = array(NULL);
				$form->$setModule(NULL);
			}
		}
	}
}