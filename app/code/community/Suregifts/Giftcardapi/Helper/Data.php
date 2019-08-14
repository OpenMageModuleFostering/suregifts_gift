<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Helper_Data extends Mage_Core_Helper_Abstract
{
	protected $_giftcardDiscountIncludesTax;
	protected $_creditPriceIncludesTax;
	public function isEnabled()
	{
		return true;
	}
	public function giftcardDiscountIncludesTax($store = null)
    {
		$storeId = Mage::app()->getStore($store)->getId();
        if (!isset($this->_giftcardDiscountIncludesTax[$storeId])) {
            $this->_creditPriceIncludesTax[$storeId] = (int)Mage::getStoreConfig(
                Suregifts_Giftcardapi_Model_Quote_Total_Tax::CONFIG_XML_PATH_GIFTDISCOUNT_INCLUDES_TAX,
                $storeId
            );
        }
        return $this->_giftcardDiscountIncludesTax[$storeId];
    }
    public function getGiftcardDiscountTax($price, $includingTax = null, $shippingAddress = null, $ctc = null, $store = null)
    {
        $billingAddress = false;
        if ($shippingAddress && $shippingAddress->getQuote() && $shippingAddress->getQuote()->getBillingAddress()) {
            $billingAddress = $shippingAddress->getQuote()->getBillingAddress();
        }
        
        $calc = Mage::getSingleton('tax/calculation');
        $taxRequest = $calc->getRateRequest(
            $shippingAddress,
            $billingAddress,
            $shippingAddress->getQuote()->getCustomerTaxClassId(),
            $store
        );
		
        $taxRequest->setProductClassId($this->getGiftcardDiscountTaxClass($store));
        $rate = $calc->getRate($taxRequest);
        $tax = $calc->calcTaxAmount($price, $rate, $this->giftcardDiscountIncludesTax($store), true);
        return $tax;
    }
	
	public function getGiftcardDiscountTaxClass($store)
    {
        return (int)Mage::getStoreConfig(
            Suregifts_Giftcardapi_Model_Quote_Total_Tax::CONFIG_XML_PATH_GIFTDISCOUNT_TAX_CLASS,
            $store
        );
    }

}