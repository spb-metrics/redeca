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
require_once('BasicForm.php');

/**
 * Nome das variáveis no template
 * 
 */
define('FE_ID_EXPENSE_TYPE','id_expense_type');
define('FE_EXPENSE_VALUE','expense_value');
define('FE_ID_FAMLY','id_family');

class FamilyExpenseForm extends BasicForm
{
	/**
	 * Campos
	 * 
	 */
	private $idPerson;
	private $idExpenseType;
	private $idFamily;
	private $expenseValue;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function id() { return F_PERSON_ID; }
	public static function idFamily() { return FE_ID_FAMLY; }
	public static function idExpenseType() { return FE_ID_EXPENSE_TYPE; }
	public static function expenseValue() { return FE_EXPENSE_VALUE; }
	
	/**
	 * Preenche os valores vindos do request
	 * 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->idPerson			= $_request->getParam(self::id());
		$this->idExpenseType	= $_request->getParam(self::idExpenseType());
		$this->idFamily 		= $_request->getParam(self::idFamily());
		$this->expenseValue 	= $_request->getParam(self::expenseValue());
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->idPerson			= $_request->getPost(self::id());
			$this->idExpenseType 	= $_request->getPost(self::idExpenseType());
			$this->idFamily 		= $_request->getPost(self::idFamily());
			$this->expenseValue 	= $_request->getPost(self::expenseValue());
		}
	}
	
	/**
	 * Recupera as informações do form e retorna no objeto Expense
	 * 
	 */
	function assembleFormToExpense(FamilyExpenseForm $frm)
	{
		$expense 						= array();
		$expense[EXP_ID_EXPENSE_TYPE]	= $frm->getIdExpenseType();
		$expense[EXP_ID_FAMILY]			= $frm->getIdFamily();
		$expense[EXP_EXPENSE_VALUE]		= $frm->getExpenseValue();
		
		return $expense;
	}
	
	/**
	 * Recupera as informações do objeto e insere no form
	 * 
	 */
	function assembleExpenseToForm($expense)
	{
		if(!empty($expense))
		{
			foreach($expense as $frm)
			{
				$this->view->form->setIdExpenseType($frm->{EXP_ID_EXPENSE_TYPE});
				$this->view->form->setIdFamily($frm->{EXP_ID_FAMILY});
				$this->view->form->setExpenseValue($frm->{EXP_EXPENSE_VALUE});
			}
		}
	}
	
	/**
	 * Getters and Setters
	 * 
	 */
	 public function getId(){return $this->idPerson;}
	 public function getIdExpenseType(){return $this->idExpenseType;}
	 public function getIdFamily(){return $this->idFamily;}
	 public function getExpenseValue(){return $this->expenseValue;}
	 
	 public function setId($id){$this->idPerson = $id;}
	 public function setIdExpenseType($idExpenseType){$this->idExpenseType = $idExpenseType;}
	 public function setIdFamily($idFamily){$this->idFamily = $idFamily;}
	 public function setExpenseValue($expenseValue){$this->expenseValue = $expenseValue;}
}