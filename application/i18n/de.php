<?php
/**
 * 
 * Copyright (c) 2010, SRIT Stefan Riedel <info@srit-stefanriedel.de>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * - Redistributions of source code must retain the above copyright notice, 
 * this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice, 
 * this list of conditions and the following disclaimer in the documentation 
 * and/or other materials provided with the distribution.
 * - Neither the name of the author nor the names of its 
 * contributors may be used to endorse or promote products derived from 
 * this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, 
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR 
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, 
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; 
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
 * OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, 
 * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP version 5
 *
 * $Id: de.php 9 2010-08-12 20:31:38Z stefanriedel $
 * $LastChangedBy: stefanriedel $
 *
 * @author    Stefan Riedel <info@srit-stefanriedel.de>
 * @copyright 2010 SRIT Stefan Riedel
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

return array ( 
    
        ':field must not be empty' => 'Das Feld \':field\' darf nicht leer bleiben.', 
        ':field must be the same as :param1' => 'Der Inhalt von :field stimmt nicht mit dem von :param1 überein.',
        ':field does not match the required format' => 'Der Inhalt von :field hat nicht das erforderliche Format.', 
        ':field must be exactly :param1 characters long' => 'Der Inhalt von :field muss genau :param1 Zeichen lang sein.', 
        ':field must be at least :param1 characters long' => 'Der Inhalt von :field muss min. :param1 Zeichen lang sein.', 
        ':field must be less than :param1 characters long' => 'Der Inhalt von :field darf max. :param1 Zeichen lang sein.', 
        ':field must be one of the available options', 
        ':field must be a digit' => ':field sollte eine Zahl beinhalten.', 
        ':field must be a decimal with :param1 places' => ':field muss eine Fließkommazahl mit :param1 stellen sein.',
        ':field must be within the range of :param1 to :param2' => ':field muss zwischen :param1 und :param2 sein.', 
        'Nutzername' => 'Nutzername',
        'signin.user.invalid' => 'Der Nutzername und/oder das Passwort sind leider falsch.'
);