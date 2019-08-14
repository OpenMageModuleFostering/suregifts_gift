<?php
/**
 * Suregits Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq<damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Block_Sales_Creditmemo_Totals_Discount extends Mage_Core_Block_Abstract
{
    public function initTotals() {
		$parent = $this->getParentBlock();
		$this->_creditmemo = Mage_Adminhtml_Block_Sales_Items_Abstract::getCreditmemo();
        $giftcardDiscountAmount = $this->_creditmemo->getGiftcardapiDiscount();
        $giftcardDiscountTaxAmount = $this->_creditmemo->getGiftcardapiDiscountTax();

        if ($giftcardDiscountAmount>0) {
		
            $giftcardDiscounTotal = new Varien_Object(array(
                'code'  => 'giftcardapi_discount',
                'label'  => $this->__('Giftcardapi Card Discount'),
                'value'  => -$giftcardDiscountAmount,
            ));
            $parent->addTotalBefore($giftcardDiscounTotal, 'tax');
            
        }
        return $this;
    }
}