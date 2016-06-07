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
define('F_TABLE_NAME'			,'tableName');
define('F_TABLE_ID'				,'tableId');
define('F_TABLE_VALUE'			,'tableValue');
define('F_TABLE_FIRST_VALUE'	,'tableFirstValue');
define('F_TABLE_SECOND_VALUE'	,'tableSecondValue');
define('F_VALUE'				,'value');
define('F_FIRST_VALUE'			,'firstValue');
define('F_SECOND_VALUE'			,'secondValue');
define('F_STATUS'				,'status');

class AdditionalInformationForm extends BasicForm
{
	/**
	 * Campos
	 * 
	 */
	private $id;
	private $value;
	private $firstValue;
	private $secondValue;
	private $tableName;
	private $tableValue;
	private $tableId;
	private $tableFirstValue;
	private $tableSecondValue;
	private $status;
	
	/**
	 * Nomes dos campos vindos do formulário html
	 * 
	 */
	public static function tableId() { return F_TABLE_ID; }
	public static function tableName() { return F_TABLE_NAME; }
	public static function tableValue() { return F_TABLE_VALUE; }
	public static function tableFirstValue() { return F_TABLE_FIRST_VALUE; }
	public static function tableSecondValue() { return F_TABLE_SECOND_VALUE; }
	public static function value() { return F_VALUE; }
	public static function firstValue() { return F_FIRST_VALUE; }
	public static function secondValue() { return F_SECOND_VALUE; }
	public static function id() { return F_PERSON_ID; }
	public static function status() { return F_STATUS; }
	
	/**
	 * Preenche os valores vindos do request
	 * 
	 */
	public function assembleRequest(&$_request)
	{
		parent::assembleRequest($_request);
		
		$this->id = $_request->getParam(AdditionalInformationForm::id());
		$this->tableName = $_request->getParam(AdditionalInformationForm::tableName());
		$this->tableValue = $_request->getParam(AdditionalInformationForm::tableValue());
		$this->tableFirstValue = $_request->getParam(AdditionalInformationForm::tableFirstValue());
		$this->tableSecondValue = $_request->getParam(AdditionalInformationForm::tableSecondValue());
		$this->tableId = $_request->getParam(AdditionalInformationForm::tableId());
		$this->value = $_request->getParam(AdditionalInformationForm::value());
		$this->firstValue = $_request->getParam(AdditionalInformationForm::firstValue());
		$this->secondValue = $_request->getParam(AdditionalInformationForm::secondValue());
		$this->status = $_request->getParam(AdditionalInformationForm::status());
		if($_request->isPost())
		{
			$filter					= BasicForm::getFilterStripTags();
			$this->id 				= $_request->getPost(AdditionalInformationForm::id());
			$this->tableName 		= trim($filter->filter($_request->getPost(AdditionalInformationForm::tableName())));
			$this->tableValue 		= trim($filter->filter($_request->getPost(AdditionalInformationForm::tableValue())));
			$this->tableFirstValue 	= trim($filter->filter($_request->getPost(AdditionalInformationForm::tableFirstValue())));
			$this->tableSecondValue	= trim($filter->filter($_request->getPost(AdditionalInformationForm::tableSecondValue())));
			$this->tableId	 		= trim($filter->filter($_request->getPost(AdditionalInformationForm::tableId())));
			$this->firstValue		= trim($filter->filter($_request->getPost(AdditionalInformationForm::firstValue())));
			$this->secondValue		= trim($filter->filter($_request->getPost(AdditionalInformationForm::secondValue())));
			$this->status			= trim($filter->filter($_request->getPost(AdditionalInformationForm::status())));
			if(($this->firstValue) && $this->secondValue)
			{
				$this->value = array($this->firstValue,$this->secondValue);
			}
			else
			{
				$this->value = trim($filter->filter($_request->getPost(AdditionalInformationForm::value())));	
			}
		}
	}

	/**
	 * Recupera as informações do objeto e insere no form
	 * 
	 */
	function assembleAdditionalInformationToForm($tableName, $tableValue, $tableId, $id, $value, $status)
	{
		$this->view->form->setTableName($tableName);
		if(is_array($tableValue))
		{
			$this->view->form->setTableFirstValue($tableValue[0]);
			$this->view->form->setTableSecondValue($tableValue[1]);
		}
		else
		{
			$this->view->form->setTableValue($tableValue);
		}
		$this->view->form->setTableId($tableId);
		$this->view->form->setId($id);
		$this->view->form->setValue($value);
		$this->view->form->setStatus($status);
	}
	
	/**
	 * Getters and Setters
	 * 
	 */
	 public function getId(){return $this->id;}
	 public function getTableName(){return $this->tableName;}
	 public function getTableValue(){return $this->tableValue;}
	 public function getTableFirstValue(){return $this->tableFirstValue;}
	 public function getTableSecondValue(){return $this->tableSecondValue;}
	 public function getTableId(){return $this->tableId;}
	 public function getvalue(){return $this->value;}
	 public function getStatus(){return $this->status;}
	 
	 public function setId($id){$this->id = $id;}
	 public function setTableName($tableName){$this->tableName = $tableName;}
	 public function setTableValue($tableValue){$this->tableValue = $tableValue;}
	 public function setTableFirstValue($tableFirstValue){$this->tableFirstValue = $tableFirstValue;}
	 public function setTableSecondValue($tableSecondValue){$this->tableSecondValue = $tableSecondValue;}
	 public function setTableId($tableId){$this->tableId = $tableId;}
	 public function setValue($value){$this->value = $value;}
	 public function setStatus($status){$this->status = $status;}
}