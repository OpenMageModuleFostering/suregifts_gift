<?php
/**
 * Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Block_Sales_Invoice_Totals_Discount extends Mage_Core_Block_Abstract
{
    public function initTotals() {
		$parent = $this->getParentBlock();
		$this->_invoice = Mage_Adminhtml_Block_Sales_Items_Abstract::getInvoice();
        $giftcardDiscounttAmount = $this->_invoice->getGiftcardapiDiscount();
        $giftcardDiscounttTaxAmount = $this->_invoice->getGiftcardapiDiscountTax();

        if ($giftcardDiscounttAmount>0) {
		
            $giftcardDiscountTotal = new Varien_Object(array(
                'code'  => 'giftcardapi_discount',
                'label'  => $this->__('Suregifts Giftcardapi Card Discount'),
                'value'  => -$giftcardDiscounttAmount,
            ));
            $parent->addTotalBefore($giftcardDiscountTotal, 'tax');
            
        }
        return $this;
    }
}