<?php


if (!function_exists('app_settings_table_qry')) {
    function app_settings_table_qry()
    {
        $qry = "CREATE TABLE IF NOT EXISTS `app_settings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    `module_name` varchar(255) NOT NULL,
    `config_key` varchar(255) NOT NULL,
    `config_value` varchar(255) NOT NULL,
    `created_date` datetime NOT NULL,
    `created_by` int(11) NOT NULL,
    `modified_date` datetime NOT NULL,
    `modified_by` int(11) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        return $qry;
    }
}

if (!function_exists('company_bank_table_qry')) {
    function company_bank_table_qry()
    {
        $qry = "CREATE TABLE IF NOT EXISTS `company_bank` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    `company_master_id` int(11) NOT NULL,
    `bank_name` varchar(255) NOT NULL,
    `account_type` tinyint(1) NOT NULL,
    `bank_swift_id` varchar(255) NOT NULL,
    `branch` varchar(255) NOT NULL,
    `account_name` varchar(255) NOT NULL,
    `ifsc_code` varchar(255) NOT NULL,
    `account_no` varchar(255) NOT NULL,
    `address` text NOT NULL,
    `created_date` datetime NOT NULL,
    `created_by` int(11) NOT NULL,
    `modified_date` datetime NOT NULL,
    `modified_by` int(11) NOT NULL,
    `opening_amount` decimal(20,2) NOT NULL,
    `opening_date` date DEFAULT NULL,
    `opening_type` tinyint(1) NOT NULL COMMENT '1:credit;2:debit',
    `available_balance` decimal(20,2) NOT NULL,
    `serial_no` int(11) NOT NULL,
    `bank_iban` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        return $qry;
    }
}
if (!function_exists('company_master_table_qry')) {
    function company_master_table_qry()
    {
        $qry = "CREATE TABLE IF NOT EXISTS `company_master` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    `name` varchar(255) NOT NULL,
    `code` varchar(255) NOT NULL,
    `email_id` varchar(255) NOT NULL,
    `contact_no` varchar(255) NOT NULL,
    `website` varchar(255) NOT NULL,
    `city` varchar(255) NOT NULL,
    `state` varchar(255) NOT NULL,
    `address` text NOT NULL,
    `billing_company` int(11) NOT NULL,
    `pan_number` varchar(255) NOT NULL,
    `cin_number` varchar(255) NOT NULL,
    `gst_number` varchar(255) NOT NULL,
    `sac_code` varchar(255) NOT NULL,
    `text_color` varchar(255) NOT NULL,
    `border_color` varchar(255) NOT NULL,
    `background_color` varchar(255) NOT NULL,
    `logo_file` varchar(255) NOT NULL,
    `signature_file` varchar(255) NOT NULL,
    `stamp_file` varchar(255) NOT NULL,
    `authorization_file` varchar(255) NOT NULL,
    `supply_place` text NOT NULL,
    `country` varchar(255) NOT NULL,
    `pincode` varchar(255) NOT NULL,
    `address1` text NOT NULL,
    `courier_reg_no` varchar(255) NOT NULL,
    `auth_courier_name` varchar(255) NOT NULL,
    `created_date` datetime NOT NULL,
    `created_by` int(11) NOT NULL,
    `modified_date` datetime NOT NULL,
    `modified_by` int(11) NOT NULL,
    `tax_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:GST;2:VAT',
    `migration_id` int(11) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        return $qry;
    }
}

if (!function_exists('custom_validation_field_table_qry')) {
    function custom_validation_field_table_qry()
    {
        $qry = "CREATE TABLE IF NOT EXISTS `custom_validation_field` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    `module_id` int(11) NOT NULL,
    `label_key` varchar(255) NOT NULL,
    `created_date` datetime NOT NULL,
    `created_by` int(11) NOT NULL,
    `modified_date` datetime NOT NULL,
    `modified_by` int(11) NOT NULL,
    `validation_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:cumplusory;2:show/hide;3:show dropdown',
    `validation_user` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1:customer;2:portal user',
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        return $qry;
    }
}
if (!function_exists('module_setting_table_qry')) {
    function module_setting_table_qry()
    {
        $qry = "CREATE TABLE IF NOT EXISTS `module_setting` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    `module_id` int(11) NOT NULL,
    `module_type` tinyint(1) NOT NULL COMMENT '1:customer',
    `config_key` varchar(255) NOT NULL,
    `config_value` varchar(255) NOT NULL,
    `created_date` datetime NOT NULL,
    `created_by` int(11) NOT NULL,
    `modified_date` datetime NOT NULL,
    `modified_by` int(11) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        return $qry;
    }
}
if (!function_exists('setting_data_table_qry')) {
    function setting_data_table_qry()
    {
        $qry = "CREATE TABLE IF NOT EXISTS `setting_data` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `status` tinyint(1) NOT NULL DEFAULT 1,
    `config_key` varchar(255) NOT NULL,
    `config_value` text NOT NULL,
    `created_date` datetime NOT NULL,
    `created_by` int(11) NOT NULL,
    `modified_date` datetime NOT NULL,
    `modified_by` int(11) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        return $qry;
    }
}

