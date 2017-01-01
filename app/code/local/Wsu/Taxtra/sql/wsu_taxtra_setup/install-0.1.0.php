<?php

$installer = $this;

$installer->startSetup();

$taxtrareports_table = $installer->getTable('wsu_taxtra/taxtrareports');
$installer->getConnection()->dropTable($taxtrareports_table);
$installer->run("
    CREATE TABLE `{$taxtrareports_table}` (
        `taxtra_report_id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `title` VARCHAR(255) NOT NULL default '',
        `filename` VARCHAR(255) NOT NULL default '',
        `content` TEXT NOT NULL default '',
        `status` SMALLINT(6) NOT NULL default '0',
        `created_time` DATETIME NULL,
        `update_time` DATETIME NULL
    ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT='Reporting views for taxes collected';
");

$installer->endSetup();
