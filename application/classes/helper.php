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


class Helper {
    /**
     * @static
     * @param  string $linkUri the uri
     * @param  string $value the value of the link
     * @param bool $acl for check the has access to this operation
     * @return string
     */
    public static function linkToEdit($linkUri, $value = null, $acl = true) {
        if (null === $value) {
            $title = __('Datensatz bearbeiten');
            $value = HTML2::image(Route::get('media')->uri(array('file' => 'icons/famfamfam/page_edit.png')), array('title' => $title, 'alt' => $title));
        }
        return HTML2::anchor($linkUri, $value);
    }

    /**
     * @static
     * @param  string $linkUri the uri
     * @param  string $value the value of the link
     * @param bool $acl for check the has access to this operation
     * @return string
     */
    public static function linkToDelete($linkUri, $value = null, $acl = true) {
        if (null === $value) {
            $title = __('Datensatz löschen');
            $value = HTML2::image(Route::get('media')->uri(array('file' => 'icons/famfamfam/delete.png')), array('title' => $title, 'alt' => $title));
        }
        self::addConfirmJavascript($linkUri, 'a.delete', I18n::get('Datensatz löschen?'), I18N::get('Wollen Sie den Datensatz wirklich löschen?'));
        return HTML2::anchor($linkUri, $value, array('class' => 'delete'));
    }

    /**
     * @static
     * @note   
     * @param  string $uri the uri to locate by clicking OK
     * @param  string $identifier the jQuery identifier
     * @param  string $title the title of confirm box
     * @param  string $dialogText the content of confirm box
     * @return void
     */
    public static function addConfirmJavascript($uri, $identifier, $title, $dialogText ) {
$function = <<<EOD
 jQuery('{$identifier}').click(function() {
    var anchor = jQuery(this);
    var dialog = jQuery('#dialog');
    dialog.dialog('destroy');
    dialog.attr('title', '{$title}');
    dialog.html('{$dialogText}');
    dialog.dialog({
        modal: true,
        resizable: false,
        buttons: {
		    Ok: function() {
                window.location=anchor.attr('href') + '?js=1';
		    },
            Abbrechen: function() {
                jQuery(this).dialog('close');
            }
        }
    });
    return false;
});
EOD;
    HTML2::addJavaScriptCode($function);
    }

}
