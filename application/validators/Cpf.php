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
 * Fabricio Meireles Monteiro  - W3S		   		26/02/2008	                       Create file 
 * 
 */

require_once 'Zend/Validate/Abstract.php';

class Validate_Cpf extends Zend_Validate_Abstract
{
	const INVALID = 'cpfInvalid';
	
	protected $_messageTemplates = array
	(
        self::INVALID => "O CPF '%value%' é inválido"
    );
	
	/**
     * Validade value (Cadastro de Pessoa Física)
     *
     * @param   string $value  value to validate
     * @return  bool         true if $value is ok, false otherwise
     */
   	public function isValid($value)
    {   
        $this->_setValue($value);
        
        $cleaned = '';
        for ($i = 0; $i < strlen($value); $i++) {
            $num = substr($value, $i, 1);
            if (ord($num) >= 48 && ord($num) <= 57) {
                $cleaned .= $num;
            }
        }
        $value = $cleaned;

        if (strlen($value) != 11) {
            $this->_error(self::INVALID);
            
            
            return false;
        } elseif (in_array($value,array("00000000000","11111111111",
                                      "22222222222","33333333333",
                                      "44444444444","55555555555",
                                      "66666666666","77777777777",
                                      "88888888888","99999999999"))) {
                  
                  
                  $this->_error(self::INVALID);
                  return false;
        } else {
            $number[0]  = intval(substr($value, 0, 1));
            $number[1]  = intval(substr($value, 1, 1));
            $number[2]  = intval(substr($value, 2, 1));
            $number[3]  = intval(substr($value, 3, 1));
            $number[4]  = intval(substr($value, 4, 1));
            $number[5]  = intval(substr($value, 5, 1));
            $number[6]  = intval(substr($value, 6, 1));
            $number[7]  = intval(substr($value, 7, 1));
            $number[8]  = intval(substr($value, 8, 1));
            $number[9]  = intval(substr($value, 9, 1));
            $number[10] = intval(substr($value, 10, 1));

            $sum = 10*$number[0]+9*$number[1]+8*$number[2]+7*$number[3]+
                6*$number[4]+5*$number[5]+4*$number[6]+3*$number[7]+
                2*$number[8];

            $sum -= (11*(intval($sum/11)));

            if ($sum == 0 || $sum == 1) {
                $result1 = 0;
            } else {
                $result1 = 11-$sum;
            }

            if ($result1 == $number[9]) 
            {
                $sum = $number[0]*11+$number[1]*10+$number[2]*9+$number[3]*8+
                        $number[4]*7+$number[5]*6+$number[6]*5+$number[7]*4+
                        $number[8]*3+$number[9]*2;
                $sum -= (11*(intval($sum/11)));

                if ($sum == 0 || $sum == 1) 
                {
                    $result2 = 0;
                }
                else 
                {
                    $result2 = 11-$sum;
                }

                if ($result2 == $number[10]) 
                {
                    return true;
                }
                else 
                {
                    $this->_error(self::INVALID);
                    return false;
                }
            } 
            else
            {  	 
                $this->_error(self::INVALID);
                return false;
            }
        }
    }    
}
?>