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
 * $Id$
 * $LastChangedBy$
 *
 * @author    Stefan Riedel <info@srit-stefanriedel.de>
 * @copyright 2010 SRIT Stefan Riedel
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Description of ORM
 *
 * @author stefanriedel
 */
class ORM extends Kohana_ORM {

    protected $_filterOpException = array();
    
    protected $_createValidators = array();

    public function setFilterOpExceptions(array $exceptions) {
        $this->_filterOpException = array_merge($this->_filterOpException, $exceptions);
        return $this;
    }

    public function save() {
        if ( ! $this->empty_pk() AND ! isset($this->_changed[$this->_primary_key])) {
            if (is_array($this->_updated_column)) {
                $column = $this->_updated_column['column'];
                $value = new Database_Expression('NOW()');
                $this->_changed[$column] = $column;
                $this->_object[$column] = $value;
                $this->_updated_column = null;
            }
        }
        else {
            if (is_array($this->_created_column)) {
                $column = $this->_created_column['column'];
                $value = new Database_Expression('NOW()');
                $this->_changed[$column] = $column;
                $this->_object[$column] = $value;
                $this->_created_column = null;
            }
        }
        return parent::save();
    }

    public function addFilters(array $filter) {
        foreach($filter as $key => $filt) {
            if(!empty($filt)) {
                if(array_key_exists($key, $this->_filterOpException)) {
                    $this->where($this->_table_name . '.' . $key, $this->_filterOpException[$key], $filt);
                }
                else {
                    $this->where($key, '=', $filt);
                }
            }
        }
        return $this;
    }

    public function isLoaded() {
        return (bool)$this->_loaded;
    }

}
?>
