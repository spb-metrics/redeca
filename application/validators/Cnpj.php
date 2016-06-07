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

class Validate_Cnpj extends Zend_Validate_Abstract
{
	const INVALID = 'cnpjInvalid';
	
	protected $_messageTemplates = array
	(
        self::INVALID => "O CNPJ '%value%' é inválido"
    );
	
	/**
     * Validade CNPJ (Cadastro Nacional de Pessoa Jurídica)
     *
     * @param   string $cnpj  CNPJ to validate
     * @return  bool          true if $cnpj is ok, false otherwise
     */
   	public function isValid($cnpj)
    {
        $cleaned = '';
        for ($i = 0; $i < strlen($cnpj); $i++) 
        {
            $num = substr($cnpj, $i, 1);
            if (ord($num) >= 48 && ord($num) <= 57) 
            {
                $cleaned .= $num;
            }
        }
        $cnpj = $cleaned;
        if (strlen($cnpj) != 14) 
        {
            $this->_error(self::INVALID);
            return false;
        }
        elseif ($cnpj == '00000000000000') 
        {
            $this->_error(self::INVALID);
            return false;
        }
        else 
        {
            $number[0]  = intval(substr($cnpj, 0, 1));
            $number[1]  = intval(substr($cnpj, 1, 1));
            $number[2]  = intval(substr($cnpj, 2, 1));
            $number[3]  = intval(substr($cnpj, 3, 1));
            $number[4]  = intval(substr($cnpj, 4, 1));
            $number[5]  = intval(substr($cnpj, 5, 1));
            $number[6]  = intval(substr($cnpj, 6, 1));
            $number[7]  = intval(substr($cnpj, 7, 1));
            $number[8]  = intval(substr($cnpj, 8, 1));
            $number[9]  = intval(substr($cnpj, 9, 1));
            $number[10] = intval(substr($cnpj, 10, 1));
            $number[11] = intval(substr($cnpj, 11, 1));
            $number[12] = intval(substr($cnpj, 12, 1));
            $number[13] = intval(substr($cnpj, 13, 1));

            $sum = $number[0]*5+$number[1]*4+$number[2]*3+$number[3]*2+
                   $number[4]*9+$number[5]*8+$number[6]*7+$number[7]*6+
                   $number[8]*5+$number[9]*4+$number[10]*3+$number[11]*2;

            $sum -= (11*(intval($sum/11)));

            if ($sum == 0 || $sum == 1) 
            {
                $result1 = 0;
            }
            else 
            {
                $result1 = 11-$sum;
            }

            if ($result1 == $number[12]) 
            {
                $sum = $number[0]*6+$number[1]*5+$number[2]*4+$number[3]*3+
                        $number[4]*2+$number[5]*9+$number[6]*8+$number[7]*7+
                        $number[8]*6+$number[9]*5+$number[10]*4+$number[11]*3+
                        $number[12]*2;
                $sum -= (11*(intval($sum/11)));
                if ($sum == 0 || $sum == 1) 
                {
                    $result2 = 0;
                }
                else 
                {
                    $result2 = 11-$sum;
                }

                if ($result2 == $number[13]) 
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