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
 * Description of user
 *
 * @author stefanriedel
 */
class Helper_User {

    public static function linkToUser(Model_User $user, $value = null) {
        if (null === $value) {
            $value = HTML2::image(Route::get('media')->uri(array('file' => 'icons/user_go.png')), array('title' => __('Zum Profil von :user', array(':user' => $user->user)), 'alt' => __('Zum Profil von :user', array(':user' => $user->user))));
        }
        return HTML2::anchor(Kohana::config('defaults.fischkopf_url') . '/' . $user->user, $value);
    }

    public static function textLinkToUserWithInfo(Model_User $user) {
        $geschlIcon = ($user->geschlecht === 'w') ? 'female' : 'male';
        $value = HTML2::image(Route::get('media')->uri(array('file' => 'icons/' . $geschlIcon . '.png')), array('title' => __('Zum Profil von :user', array(':user' => $user->user)), 'alt' => __('Zum Profil von :user', array(':user' => $user->user))));
        $value .= ' ' . $user->user . ' (' . $user->useralter . ')';
        return self::linkToUser($user, $value);
    }

    public static function renderOnlineOfflineIcon(Model_Useronline $useronline) {
        $onlineStatus = (int) (!empty($useronline->id));
        $icon = ($onlineStatus === 1) ? 'bullet_green' : 'bullet_red';
        $title = ($onlineStatus === 1) ? __('Nutzer ist online') : __('Nutzer ist offline');
        return HTML2::image(Route::get('media')->uri(array('file' => 'icons/' . $icon . '.png')), array('title' => $title, 'alt' => $title));
    }

    public static function renderUserPicture(Model_Picture $picture, $type='thumb', $withChecker = false) {
        $allowedTypes = array('minia', 'minib', 'thumb', 'full', 'orig');
        if (!in_array($type, $allowedTypes)) {
            return __('Bild Fehler');
        }
        $content = '';
        $pictureFilePath = Kohana::config('defaults.fischkopf_front_path');
        $pictureFilePath .= Kohana::config('defaults.fischkopf_bild_path');
        $pictureFilePath .= $picture->{$type};
        $picturePath = dirname($pictureFilePath);


        $pictureUrlPath = Kohana::config('defaults.fischkopf_url');
        $pictureUrlPath .= Kohana::config('defaults.fischkopf_bild_path');
        $pictureUrl = $pictureUrlPath . $picture->{$type};

        if (!file_exists($pictureFilePath) || !is_readable($pictureFilePath)) {
            return __('Bild Fehler');
            //throw new Kohana_Exception(__('Das Bild :bildPath kann nicht ausgelesen werden.'), array(':bildPath' => $pictureFilePath));
        }
        $imageData = getimagesize($pictureFilePath);

        $pictureId = $picture->id;

        $image = HTML2::image($pictureUrl, array('width' => $imageData[0], 'height' => $imageData[1], 'id' => $pictureId));
        $divClass = 'message';

        switch ((int) $picture->status_bild) {
            case 1:
                $divClass .= ' tip';
                break;
            case 2:
                $divClass .= ' error';
                break;
            case 3:
                $divClass .= ' success';
                break;
        }

        $tooltip = '';

        $fullUrl = $pictureUrlPath . $picture->full;
        $imageAnchor = HTML2::anchor($fullUrl, $image, array('class' => 'preview', 'title' => $picture->info_bild));
        $content = HTML2::tag('div', array('class' => $divClass), $imageAnchor);
        if ($withChecker) {
            $form = Form::open(Route::get('default')->defaults(array('controller' => 'pictures', 'action' => 'changeStatus'))->uri(array('id' => $picture->id)), array('class' => 'save-picture-status'));
            $form .= HTML2::tag('div', array('class' => 'success saveForm'), Form::radio('status_bild['.$picture->id.']', 3, ((int) $picture->status_bild === 3), array('class' => 'success')) . ' ' . __('erl.'));
            $form .= HTML2::tag('div', array('class' => 'error saveForm'), Form::radio('status_bild['.$picture->id.']', 2, ((int) $picture->status_bild === 2), array('class' => 'error')) . ' ' . __('abgel.'));
            $form .= Form::textarea('info_bild['.$picture->id.']', $picture->info_bild, array('cols' => 10, 'style' => 'height:50px; margin-top: 3px; display:none'));
            $form .= Form::button('sub' . $picture->id, __('Save'), array('type' => 'submit'));
            $form .= HTML2::tag('span', array('style' => 'display:none; padding:1px 2px;'), ' ');
            $form .= Form::close();
            $checkerDiv = HTML2::tag('div', array('class' => $divClass), $form);

            $submitFormFunktion = "
            var options = {
                beforeSubmit:  bRequest,
                success:       aRequest,
                dataType:      'json'
            };

            // bind form using 'ajaxForm'
            jQuery('form.save-picture-status').submit(function() {
                // submit the form via ajax
                jQuery(this).ajaxSubmit(options);
                //jQuery(this).blockUI();
                return false;
            });";
            HTML2::addJavaScriptCode($submitFormFunktion, 'save-picture-status', true);

            $radioClickFunktion = "
            jQuery('.saveForm').click(function(){
                var parent = $(this).parent();
                var activeRadio = $('input:radio:checked', parent);
                var textarea = parent.children('textarea:first');
                if(activeRadio.val() == 3) {
                    textarea.hide('slow');
                } else if(activeRadio.val() == 2 ) {
                    textarea.show('slow');
                }
            });
            ";

            HTML2::addJavaScriptCode($radioClickFunktion, 'open-info-area', true);

            $content .= $checkerDiv;
            //$content = HTML2::tag('div', array('class' => 'schleier'), $content);
        }
        return $content;
    }

}

?>
