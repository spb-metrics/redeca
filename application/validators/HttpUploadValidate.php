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
 * Jefferson Barros Lima  - W3S		   				27/02/2008	                       Create file 
 * 
 */

require_once 'Zend/Validate/Abstract.php';

/**
 * Validates an HTTP upload
 */
class HttpUploadValidate extends Zend_Validate_Abstract
{   
    /**
     * Validation failure message key for when the file's size is higher than the maximum size specified
     */
    const MAX_SIZE_EXCEEDED = 'maxSizeExceeded';

    /**
     * Validation failure message key for when the file's size is lower than the minimum size specified
     */
    const MIN_SIZE_NEEDED = 'minSizeNeeded';
    
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::MAX_SIZE_EXCEEDED => "Max file size of '%value%' mb exceeded",
        self::MIN_SIZE_NEEDED => "Min file size must be higher than '%value%' mb"
    );
    
//    /**
//     * Additional variables available for validation failure messages
//     *
//     * @var array
//     */
//    protected $_messageVariables = array(
//        'max_file_size' => '_max_file_size' ,
//        'max_file_size' => '_min_file_size' 
//    );
    
    /**
     * Key found within the global $_FILES array
     *
     * @var string
     */
    protected $_field;
    
    /**
     * Maximum file size allowed.
     *
     * @var integer
     */
    protected $_max_file_size;
    
    /**
     * Minimum file size needed.
     *
     * @var integer
     */
    protected $_min_file_size;
    
    /**
     * Constructor
     *
     * @param   string  $field                  The name of the file field found within the $_FILES global array
     * @param   integer $max_file_size          Size in bytes. Compared against $_FILES[$field]['size']
     * @throws  Zend_Validate_Exception
     */
    public function __construct($field, $min_file_size, $max_file_size)
    {        
        /* Make sure the field actually exists */
        if(isset($_FILES) && !empty($_FILES) && !array_key_exists($field, $_FILES)) {
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception($field . ' not found in global $_FILES array!');
        }
        
        $this->_field = $field;
        $this->_max_file_size = (int)$max_file_size;
        $this->_min_file_size = (int)$min_file_size;
    }

    public function isValid($value)
    {
//        $this->_setValue($value);   

        /* Valiate size */
        if($this->_max_file_size <= $_FILES[$this->_field]['size']) {
            $this->_max_file_size = $this->_max_file_size/1024; // Convert to MB
            $this->_setValue($this->_max_file_size);
            $this->_error(self::MAX_SIZE_EXCEEDED);
            return false;
        }
        
        /* Valiate size */
        if($this->_min_file_size >= $_FILES[$this->_field]['size']) {
            $this->_min_file_size = $this->_min_file_size/1024; // Convert to MB
            $this->_setValue($this->_min_file_size);
            $this->_error(self::MIN_SIZE_NEEDED);
            return false;
        }

        return true;
    }
} 
