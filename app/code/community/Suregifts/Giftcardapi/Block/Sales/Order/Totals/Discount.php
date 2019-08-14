<?php
/**
 * Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Block_Sales_Order_Totals_Discount extends Mage_Core_Block_Abstract
{
    public function initTotals() {
		$_order   = $this->getParentBlock()->getOrder();
        $giftcardDiscountAmount = $this->getParentBlock()->getSource()->getGiftcardapiDiscount();
        $giftcardDiscountTaxAmount = $this->getParentBlock()->getSource()->getGiftcardapiDiscountTax();

        if ($giftcardDiscountAmount>0) {
		
            $giftcardDiscounTotal = new Varien_Object(array(
                'code'  => 'giftcardapi_discount',
                'label'  => $this->__('Suregifts Card Discount'),
                'value'  => -$giftcardDiscountAmount,
            ));
            $this->getParentBlock()->addTotalBefore($giftcardDiscounTotal, 'grand_total');
            
        }
        return $this;
    }
}