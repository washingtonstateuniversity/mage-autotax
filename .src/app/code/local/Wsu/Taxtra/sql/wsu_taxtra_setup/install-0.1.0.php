<?php

$installer = $this;

$installer->startSetup();

/*$installer->run("
ALTER TABLE  {$this->getTable('tax_calculation_rate')}
    DROP COLUMN `update_history`,
    DROP COLUMN `update_type`,
    DROP COLUMN `update_history`;
");*/

$installer->run("ALTER TABLE {$this->getTable('tax_calculation_rate')} ADD `last_update` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;");
$installer->run("ALTER TABLE {$this->getTable('tax_calculation_rate')} ADD `update_type` VARCHAR(255) NOT NULL;");
$installer->run("ALTER TABLE {$this->getTable('tax_calculation_rate')} ADD `update_history` TEXT NOT NULL;");
$installer->run("ALTER TABLE {$this->getTable('tax_calculation_rate')} ADD `remittee_id` VARCHAR(255) NOT NULL;");



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
