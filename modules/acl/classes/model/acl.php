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
 * $Id: user.php 9 2010-08-12 20:31:38Z stefanriedel $
 * $LastChangedBy: stefanriedel $
 *
 * @author    Stefan Riedel <info@srit-stefanriedel.de>
 * @copyright 2010 SRIT Stefan Riedel
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Model_Acl extends ORM
{

    protected $_aclArray = array();

    /**
     *
     * fake pk - kohana orm expected a primary key
     *
     * @var string
     */
	protected $_primary_key  = 'role';

    /**
     *
     * we define an order as default - we haven't a pk
     *
     * @return Database_Result
     */
    public function find_all()
    {
        $this->order_by('resource', 'desc');
        return parent::find_all();
    }

    /**
     *
     * finds all acls which are allowed
     *
     * @return Database_Result
     */
    public function findAllAllowed()
    {
        $this->where('allowed', '=', 1);
        return $this->find_all();
    }

    /**
     *
     * iterate the allowed rowset and
     * returns an array for kohana_controllers
     *
     * @return array
     */
    public function asAclArray()
    {
        $rowSet = $this->findAllAllowed();
        $lastResource = null;
        $lastRule = null;
        foreach ($rowSet as $row) {
            $resource = $row->resource;
            $rule = $row->rule;
            $role = $row->role;
            if (empty($lastResource) || $resource !== $lastResource) {
                $lastResource = $resource;
                $this->_aclArray[$resource] = array();
            }
            if(empty($lastRule) || $rule !== $lastRule) {
                $lastRule = $rule;
                $this->_aclArray[$resource] = array($rule => array());
            }
            $this->_aclArray[$resource][$rule][] = $role;
        }

        return $this->_aclArray;
    }

}