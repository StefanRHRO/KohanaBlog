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
defined('SYSPATH') or die ('No direct script access.');
/**
 *
 * @throws Kohana_Exception
 *
 */
class Controller_Main extends Controller_Template
{

    /**
     * Das haupttemplate
     * @var string
     */
    public $template = 'theme/default/template';
    /**
     * the template path
     * @var string
     */
    protected $_templatePath = 'theme/default';
    /**
     * the template path prefix
     * @var string
     */
    protected $_templatePrefix = 'theme/default/';
    /**
     * @var $_session Session
     */
    protected $_session;
    /**
     * for checking if is auth required for this controller/action
     * @var bool
     */
    protected $_authRequired = false;
    /**
     * an associative array of action => array('role1', 'role2) pair
     * @var array
     */
    protected $_secureActions = array();

    /**
     * where parameters for our model
     * @var array
     */
    protected $_whereParameter = array();
    /**
     * an array of
     * @var array
     */
    protected $_filterOpException = array();
    protected $_excludeParameter = array(
        'sub', 'page', 'order', 'type'
    );

    /**
     *
     * @var array von ajax actions, welche mit layout ausgeliefert werden sollen
     */
    protected $_layoutedAjaxException = array();
    /**
     *
     * @var array which crud operations are allowed in this context
     */
    protected $_allowedCrudOperations = array();

    public function allowed($action = '')
    {
        var_dump($action);
    }

    /**
     * the before method will
     * innitialize the template
     * and our media data (javascript, css etc)
     * @return void
     */
    public function before()
    {
        $action = Request::instance()->action;
        if (Request::$is_ajax && !in_array($action, $this->_layoutedAjaxException)) {
            $this->auto_render = false;
        }

        parent::before();

        $this->_session = Session::instance();
        if (($this->_authRequired !== FALSE
             && Auth::instance()->logged_in($this->_authRequired) === FALSE) || (is_array($this->_secureActions)
                                                                                 && array_key_exists($action, $this->_secureActions)
                                                                                 && Auth::instance()->logged_in($this->_secureActions [$action]) === FALSE)) {
            if (Auth::instance()->logged_in()) {
                Request::instance()->redirect('noaccess');
            } else {
                Request::instance()->redirect('signin');
            }
        }

        if ($this->auto_render) {
            // Initialize empty values
            /**
             * @todo become from database like wordpress
             */
            $this->template->title = 'Blog';
            $this->template->content = '';
            $this->template->siteTitle = 'No Title';
            $this->template->errors = array();
            $this->template->styles = array();
            $this->template->scripts = array();

            $styles = array('media/css/jquery-ui.css' => 'screen');
            $scripts = array('media/js/jquery.js', 'media/js/jquery-ui.js','media/js/jquery.blockui.js' );
            $this->template->styles = array_merge($this->template->styles, $styles);
            $this->template->scripts = array_merge($this->template->scripts, $scripts);
            Html2::addJavaScriptCode('$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);');
        }
    }

    /**
     * will innitialize where parameters for our
     * model
     * @param  $parameter array
     * @return void
     */
    protected function _initializeWhereParameter(array $parameter)
    {
        foreach ($parameter as $column => $param) {
            if (!in_array($column, $this->_excludeParameter)) {
                $this->_whereParameter [$column] = $param;
            }
        }
    }

    public function action_doDelete()
    {
        $id = $_POST['id'];
        if (!$this->_checkActionAllowed()) {
            throw new Kohana_Exception (I18N::get('Die Operation \':operation\' ist in diesem Kontext leider nicht erlaubt!'), array(':operation' => $action));
        }
        try {
            $modelClassName = substr($this->request->controller, 0, -1);
            $row = ORM::factory($modelClassName, $id);
            if ($row->loaded()) {
                $row->delete();
            }
            if (Request::$is_ajax) {
                return json_encode(true);
            }
            else {
                return true;
            }

        }
        catch (Kohana_Exception $e) {
            throw new Kohana_Exception(__('Es ist ein Fehler aufgetreten: :fehler'), array(':fehler' => $e->getMessage()));
        }
    }

    /**
     * CRUD operation for delete a row
     *
     * @throws Kohana_Exception
     * @return void
     */
    public function action_delete()
    {
        $id = strip_tags(Request::current()->param('id'));
        $action = $this->request->action;
        $controller = $this->request->controller;
        if (!$this->_checkActionAllowed()) {
            throw new Kohana_Exception (I18N::get('Die Operation \':operation\' ist in diesem Kontext leider nicht erlaubt!'), array(':operation' => $action));
        }
        $modelClassName = substr($this->request->controller, 0, -1);
        /*switch((int)$_GET['js']) {
            case 1:
                try {
                    $row = ORM::factory($modelClassName, $id);
                    if($row->loaded()) {
                        $row->delete();
                    }
                }
                catch(Kohana_Exception $e) {
                    throw new Kohana_Exception(__('Es ist ein Fehler aufgetreten: :fehler'), array(':fehler' => $e->getMessage()));
                }
                break;
            case 0:
            default:
            if (! $this->_checkTemplateExists ()) {
                throw new Kohana_Exception ( I18n::get ( 'Das Template für die Action existiert leider nicht.' ) );
            }
                break;
        }*/
    }

    public function action_add()
    {
        $action = $this->request->action;
        $controller = $this->request->controller;
        if (!$this->_checkActionAllowed()) {
            throw new Kohana_Exception (I18N::get('Die Operation \':operation\' ist in diesem Kontext leider nicht erlaubt!'), array(':operation' => $action));
        }
        if (!$this->_checkTemplateExists()) {
            throw new Kohana_Exception (I18n::get('Das Template für die Action existiert leider nicht.'));
        }
        /**
         * @todo refactoring
         */
        $templatePath = $this->_templatePath . '/' . $controller . '/' . $action;
        $siteTitle = I18n::get('Neuen Datensatz anlegen');
        $this->template->bind_global('siteTitle', $siteTitle);
        /**
         * @todo refactoring
         */
        $this->template->content = View::factory($templatePath)->bind('row', $row)->bind('templatePath', $this->_templatePath);
        try {
            $modelClassName = substr($this->request->controller, 0, -1);
            $row = ORM::factory($modelClassName);
        }
        catch (Kohana_Exception $e) {
            throw new Kohana_Exception(__('Es ist ein Fehler aufgetreten: :fehler'), array(':fehler' => $e->getMessage()));
        }
    }

    /**
     * CRUD Operation for edit a row
     * @throws Kohana_Exception
     * @return void
     */
    public function action_edit()
    {
        $action = $this->request->action;
        $controller = $this->request->controller;
        $id = $this->request->param('id');
        if (!$this->_checkActionAllowed()) {
            throw new Kohana_Exception (I18N::get('Die Operation \':operation\' ist in diesem Kontext leider nicht erlaubt!'), array(':operation' => $action));
        }
        if (!$this->_checkTemplateExists()) {
            throw new Kohana_Exception (I18n::get('Das Template für die Action existiert leider nicht.'));
        }
        /**
         * @todo refactoring
         */
        $templatePath = $this->_templatePath . '/' . $controller . '/' . $action;
        $siteTitle = I18n::get('Bearbeiten');
        $this->template->bind_global('siteTitle', $siteTitle);
        /**
         * @todo refactoring
         */
        $this->template->content = View::factory($templatePath)->bind('row', $row)->bind('templatePath', $this->_templatePath);

        try {
            $modelClassName = substr($this->request->controller, 0, -1);
            $row = ORM::factory($modelClassName, $id);
            if (!$row->loaded()) {
                Kohana::$log->add(Kohana::ERROR, I18N::get('Der Datensatz mit der ID: ' . $id . ' existiert leider nicht.'));
                $request->status = 404;
            }

            if($_POST) {
                $_POST = Arr::xss($_POST);
                $row->values($_POST);
                if($row->check()) {
                    $row->save();
                    $this->request->redirect(Route::get('default')->uri(array('controller' => $controller, 'action' => 'list')));
                }
            }

        }
        catch (Kohana_Exception $e) {
            throw new Kohana_Exception(__('Es ist ein Fehler aufgetreten: :fehler'), array(':fehler' => $e->getMessage()));
        }
    }

    /**
     * CRUD Operation for list the rowset
     * @throws Kohana_Exception
     * @return void
     */
    public function action_list()
    {
        $action = $this->request->action;
        $controller = $this->request->controller;
        if (!$this->_checkActionAllowed()) {
            throw new Kohana_Exception (I18N::get('Die Operation \':operation\' ist in diesem Kontext leider nicht erlaubt!'), array(':operation' => $action));
        }
        if (!$this->_checkTemplateExists()) {
            throw new Kohana_Exception (I18n::get('Das Template für die Action existiert leider nicht.'));
        }
        $templatePath = $this->_templatePath . '/' . $controller . '/' . $action;
        $this->template->siteTitle = I18n::get('Übersicht');
        $this->template->content = View::factory($templatePath)->bind('results', $results)->bind('page_links', $page_links)->bind('current_page', $current_page)->bind('count', $count);

        $this->_initializeWhereParameter($_GET);

        $modelClassName = substr($this->request->controller, 0, -1);
        $model = ORM::factory($modelClassName);
        $model->setFilterOpExceptions($this->_filterOpException);

        if (!empty ($this->_whereParameter)) {
            $model = $model->addFilters($this->_whereParameter);
        }

        $count = $model->count_all();

        /**
         * warum muessen wir das wieder holen
         */
        if (!empty ($this->_whereParameter)) {
            $model = $model->addFilters($this->_whereParameter);
        }

        $pagination = Pagination::factory(array('total_items' => $count, 'items_per_page' => 15));

        $model->limit($pagination->items_per_page)->offset($pagination->offset);
        if (!empty ($_GET ['order']) && !empty ($_GET ['type'])) {
            $model->order_by($_GET ['order'], $_GET ['type']);
        }
        $results = $model->find_all();
        $page_links = $pagination->render($this->_templatePath . '/pagination/basic');
        $current_page = $pagination->current_page;
    }

    /**
     * @return bool
     */
    protected function _checkActionAllowed()
    {
        $action = $this->request->action;
        return in_array($action, $this->_allowedCrudOperations);
    }

    /**
     * @param  $templateName string
     * @return bool
     */
    protected function _checkTemplateExists($templateName = null)
    {
        if (null === $templateName) {
            $action = $this->request->action;
            $controller = $this->request->controller;
            $templatePath = $this->_templatePath . '/' . $controller . '/' . $action;
        }
        return ($path = Kohana::find_file('views', $templatePath)) !== FALSE;
    }

}
