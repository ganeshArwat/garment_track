<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Parent_Controller extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->set_config_vars();
        $this->set_constant_vars();
    }
     
    protected function set_config_vars()
    {
        $this->config->set_item('parent_config', 'parent_config');
    }
     
    protected function set_constant_vars()
    {
        define('PARENT_CONSTANT', 'Parent_constant');
    }
}
