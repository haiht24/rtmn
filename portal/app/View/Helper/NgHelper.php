<?php

App::uses('AppHelper', 'View/Helper');

class NgHelper extends AppHelper {

    public $helpers = ['Html' => ['className' => 'I18nHtml']];

    protected $_ngInit = [];
    protected $_ngController = '';
    protected $_fdbServices = [];
    protected $_fdbFilters = [];
    protected $_fdbDirectives = [];

    public function ngInit($data) {
        $this->_ngInit = array_merge($this->_ngInit, $data);
    }

    public function ngInitOut() {
        if (!empty($this->_ngInit)) {
            $out = 'ng-init=\'';
            foreach ($this->_ngInit as $key => $value) {
                $out .= $key . '=' . json_encode($value, JSON_HEX_APOS ) . '; ';
            }
            return $out . '\'';
        }
        return '';
    }

    public function ngController($ctrl) {
        $this->_ngController = $ctrl;
    }

    public function ngControllerOut() {
        if (!empty($this->_ngController)) {
            return 'ng-controller="' . $this->_ngController . '"';
        } else {
            return '';
        }
    }

    public function ngAppOut() {
        return 'ng-app="fdb"';
    }

    public function fdbDirective($name) {
        if (is_array($name)) {
            $this->_fdbDirectives = array_merge($this->_fdbDirectives, $name);
        } else {
            $this->_fdbDirectives[] = $name;
        }
    }

    public function fdbService($name) {
        if (is_array($name)) {
            $this->_fdbServices = array_merge($this->_fdbServices, $name);
        } else {
            $this->_fdbServices[] = $name;
        }
    }

    public function fdbFilter($name) {
        if (is_array($name)) {
            $this->_fdbFilters = array_merge($this->_fdbFilters, $name);
        } else {
            $this->_fdbFilters[] = $name;
        }
    }

    public function beforeLayout($layoutFile) {
        parent::beforeLayout($layoutFile);

        if (!empty($this->_fdbDirectives) || !empty($this->_fdbServices) || !empty($this->_fdbFilters)) {
            $out = '<script type="text/javascript">';
            $scripts = [];
            if (!empty($this->_fdbDirectives)) {
                $out .= "angular.module('fdb.directives', []);";
                foreach ($this->_fdbDirectives as $name) {
                    $scripts[] = $this->Html->script('directives/' . $name);
                }
            }
            if (!empty($this->_fdbServices)) {
                $out .= "angular.module('fdb.services', []);";
                foreach ($this->_fdbServices as $name) {
                    $scripts[] = $this->Html->script('services/' . $name);
                }
            }
            if (!empty($this->_fdbFilters)) {
                $out .= "angular.module('fdb.filters', []);";
                foreach ($this->_fdbFilters as $name) {
                    $scripts[] = $this->Html->script('filters/' . $name);
                }
            }
            $out .= '</script>' . implode('', $scripts);
            $this->_View->append('script', $out);
        }
    }

}

?>