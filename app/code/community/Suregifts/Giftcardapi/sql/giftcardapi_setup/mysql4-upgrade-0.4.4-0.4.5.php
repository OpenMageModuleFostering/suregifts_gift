<?php
$installer = $this;
$installer->startSetup();
$setup = new Mage_Sales_Model_Mysql4_Setup('core_setup');
$setup->addAttribute('quote', 'giftcardapi_code', array('type' => 'varchar'));
$setup->addAttribute('order', 'giftcardapi_code', array('type' => 'varchar'));
$setup->addAttribute('invoice', 'giftcardapi_code', array('type' => 'varchar'));
$setup->addAttribute('quote', 'giftcardapi_discount', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('quote', 'base_giftcardapi_discount', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('order', 'giftcardapi_discount', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('order', 'base_giftcardapi_discount', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('order', 'giftcardapi_discount_invoiced', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('order', 'base_giftcardapi_discount_invoiced', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('order', 'giftcardapi_discount_tax', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('order', 'base_giftcardapi_discount_tax', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('order', 'giftcardapi_discount_tax_invoiced', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('order', 'base_giftcardapi_discount_tax_invoiced', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('invoice', 'giftcardapi_discount', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('invoice', 'base_giftcardapi_discount', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('invoice', 'giftcardapi_discount_tax', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('invoice', 'base_giftcardapi_discount_tax', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('creditmemo', 'giftcardapi_discount', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('creditmemo', 'base_giftcardapi_discount', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('creditmemo', 'giftcardapi_discount_tax', array('type' => 'decimal', 'visible' => false));
$setup->addAttribute('creditmemo', 'base_giftcardapi_discount_tax', array('type' => 'decimal', 'visible' => false));

$installer->endSetup();