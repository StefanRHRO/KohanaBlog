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
 * @author    Stefan Riedel <info@srit-stefanriedel.de>
 * @copyright 2010 SRIT Stefan Riedel
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
defined('SYSPATH') or die('No direct script access.');
class Kohana_Acl {
	
	protected static $_instance = null;
	
	protected $_aclTree = array();

    protected $_guestRole = null;

	public function __construct(array $config = array()) {
		if(empty($config)) {
			$config = Kohana::config('acl');
		}
        $this->_init($config);
	}
	
	public static function instance($config = array()) {
		if(null === self::$_instance && !(self::$_instance instanceof Kohana_Acl)) {
			self::$_instance = new self($config);
		}
		return self::$_instance;
	}
	
	public function getAclTree() {
		return $this->_aclTree;
	}
	
	public function isAllowed($resource, $rule) {
        $aclTree = $this->getAclTree();
        $auth = Auth::instance();
        if(array_key_exists($resource, $aclTree)
           && array_key_exists($rule, $aclTree[$resource])) {
            $allowedRoles = $aclTree[$resource][$rule];
            if(!$auth->logged_in()) {
                return in_array($this->_guestRole, $allowedRoles);
            }
            else {
                return $auth->logged_in($allowedRoles);
            }
        }
        return false;
	}
	
	protected function _init($config) {
		switch($config['adapter']) {
			case 'orm':
			default:
				$model = ORM::factory('acl');
				break;
		}
		$this->_aclTree = $model->asAclArray();
        $this->_guestRole = $config['guestRole'];
	}
	
}

?>