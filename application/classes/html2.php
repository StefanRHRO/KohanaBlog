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
 * $Id: html2.php 9 2010-08-12 20:31:38Z stefanriedel $
 * $LastChangedBy: stefanriedel $
 *
 * @author    Stefan Riedel <info@srit-stefanriedel.de>
 * @copyright 2010 SRIT Stefan Riedel
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
defined('SYSPATH') or die('No direct script access.');

class HTML2 extends HTML {

    static protected $_javascript = array();

    public static function errorBlock(array $errors) {
        $errorStrings = '';
        foreach ($errors as $errorField => $errorsValue) {
            $errorStrings .= self::tag('p', array('id' => 'p' . $errorField), I18n::get($errorsValue));
        }
        return self::tag('div', array('class' => 'message error closeable'), $errorStrings);
    }

    public static function tag($tag, array $htmlOptions = array(), $content = false, $closeTag = true) {
        $html = '<' . $tag . self::_renderAttributes($htmlOptions);
        if ($content === false) {
            return $closeTag ? $html . ' />' : $html . '>';
        } else {
            return $closeTag ? $html . '>' . $content . '</' . $tag . '>' : $html . '>' . $content;
        }
    }

    public static function encode($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    protected static function _renderAttributes($htmlOptions) {
        if ($htmlOptions === array()) {
            return '';
        }
        $html = '';
        $raw = isset($htmlOptions ['encode']) && !$htmlOptions ['encode'];
        unset($htmlOptions ['encode']);
        if ($raw) {
            foreach ($htmlOptions as $name => $value) {
                $html .= ' ' . $name . '="' . $value . '"';
            }
        } else {
            foreach ($htmlOptions as $name => $value) {
                $html .= ' ' . $name . '="' . self::encode($value) . '"';
            }
        }
        return $html;
    }

    public static function orderAnchor($field, array $params = array(), $title = NULL, array $attributes = NULL, $protocol = NULL) {

        $request = Request::instance();
        /* $uri = $request->uri;
          if (!empty($params)) {
          foreach ($params as $k => $p) {
          if ($k === 0) {
          $uri .= '?';
          } else {
          $uri .= '&';
          }
          $uri .= $p['name'] . '=' . $p['value'];
          }
          $uri .= self::_orderString($field, $request, true);
          }
          else {
          $uri .= self::_orderString($field, $request);
          } */

        $base = URL::base();
        $baseLength = strlen($base);
        $reqUri = Request::current()->uri;
        $uri = URL::site($reqUri) . URL::query(array('order' => $field, 'type' => self::_orderString($field, $request)));
        $uri = substr($uri, $baseLength);
        //var_dump($uri);
        return self::anchor($uri, $title, $attributes, $protocol);
    }

    protected static function _orderString($field, Request $request, $query=false) {
        $acStatusOfField = (isset($_GET['order'])) ? $_GET['order'] : null;
        $type = (isset($_GET['type'])) ? $_GET['type'] : null;
        if (!empty($acStatusOfField) && $field === $acStatusOfField) {
            switch (strtolower($type)) {
                case 'asc':
                    $uri = 'DESC';
                    break;
                case 'desc':
                    $uri = 'ASC';
                    break;
            }
        } else {
            $uri = 'ASC';
        }
        return $uri;
    }

    public static function javascriptAnchor($title = null, array $attributes = array()) {
        return '<a href="javascript:void(0)"'.self::attributes($attributes).'>'.$title.'</a>';
    }

    public static function addJavaScriptCode($code, $indexName=null, $oneDeklaration = false) {
        if (!is_string($code)) {
            throw new Kohana_Exception('Im Moment werden leider nur Strings unterstützt. Es ist für eine spätere Version vorgesehen auch Arrays übergeben zu können.');
        }
        if(null !== $indexName && $oneDeklaration && isset(self::$_javascript[$indexName])) {
            return self::$_javascript[$indexName];
        }
        $index = (empty($indexName)) ? (count(self::$_javascript)) : $indexName;
        self::$_javascript[$index] = $code;
    }

    public static function getJavaScriptCodes() {
        return self::$_javascript;
    }

}