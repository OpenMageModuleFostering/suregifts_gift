<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Adminhtml_ExtendoController extends Mage_Adminhtml_Controller_Action
{
	
	
    public function indexAction()
    { 
		$this->loadLayout();  
		$this->renderLayout();
    }
	
	
	
}