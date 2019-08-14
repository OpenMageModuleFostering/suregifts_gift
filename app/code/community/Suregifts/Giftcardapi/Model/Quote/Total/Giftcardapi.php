<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */

class Suregifts_Giftcardapi_Model_Quote_Total_Giftcardapi extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function __construct() {
        $this->setCode('giftcardapi');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address) {        
        if (Mage::app()->getStore()->isAdmin()) {
            $allItems = Mage::getSingleton('adminhtml/sales_order_create')->getQuote()->getAllItems();
            $productIds = array();
            foreach ($allItems as $item) {
                $productIds[] = $item->getProductId();
            }
        } else {
            $productIds = Mage::getSingleton('checkout/cart')->getProductIds();            
        }
       
        if (count($productIds)==0) return $this;
        
        $addressType = Mage_Sales_Model_Quote_Address::TYPE_BILLING;
        foreach ($productIds as $productId) {
            $productTypeId = Mage::getModel('catalog/product')->load($productId)->getTypeId();
            if ($productTypeId!='downloadable' && $productTypeId!='virtual') {
                $addressType = Mage_Sales_Model_Quote_Address::TYPE_SHIPPING;
                break;
            }
        }
        
        //shipping or billing
        if ($addressType!=$address->getAddressType()) return $this;
		$discountAmount = 0;
        $address->setGiftcardapiDiscount(0);
        $address->setBaseGiftcardapiDiscount(0);
		$address->setGiftcardapiDiscountTax(0);
        $address->setBaseGiftcardapiDiscountTax(0);
        $session = Mage::getSingleton('checkout/session');
		if(!$session->getUseGiftcardapiDiscount()) return $this;
        $orderData = Mage::app()->getRequest()->getPost('order');               
        if(Mage::getModel('checkout/cart')->getQuote()->getData('items_qty') == 0 && !Mage::getSingleton('adminhtml/session_quote')->getCustomerId()) {
            return $this;
        }
		
        $quote = $address->getQuote();
                                  
        $baseGrandTotal = floatval($address->getBaseGrandTotal());
        $grandTotal = floatval($address->getGrandTotal());
        $baseShipping = floatval($address->getBaseShippingInclTax());
		$shipping = floatval($address->getShippingInclTax()); 
		if ($baseGrandTotal) $baseSubtotal = $baseGrandTotal - $baseShipping; else $baseSubtotal = floatval($address->getBaseSubtotalInclTax());
		if ($grandTotal) $subtotal = $grandTotal - $shipping; else $subtotal = floatval($address->getSubtotalInclTax());
        
        $quote = $address->getQuote();
        $store = $quote->getStore();   
		$quoteTotal = $baseSubtotal+$baseShipping;
		$giftcardDiscount = array();
		$giftcardDiscount[] = $session->getGiftcardapiDiscountAmount();
		sort($giftcardDiscount, SORT_NUMERIC);
		$discountAmount = $giftcardDiscount[0];
		
		if((int)$discountAmount >= (int)$address->getBaseGrandTotal())
			$discountAmount = $address->getBaseGrandTotal();
        $baseTax = floatval($address->getBaseTaxAmount());
        $tax = floatval($address->getTaxAmount());       
		
        $taxAmount = Mage::helper('giftcardapi')->getGiftcardDiscountTax(
			$discountAmount,
			true,
			$address, $address->getQuote()->getCustomerTaxClassId(), $store
		);
        $address->setBaseGiftcardapiDiscount($discountAmount);
        $address->setGiftcardapiDiscount($store->convertPrice($discountAmount, false));

		$address->setBaseGrandTotal($address->getBaseGrandTotal() - $discountAmount);
		$address->setGrandTotal($address->getGrandTotal() - $store->convertPrice($discountAmount, false)); 

        $address->setBaseGiftcardapiDiscountTax($taxAmount);
        $address->setGiftcardapiDiscountTax($store->convertPrice($taxAmount, false)); 
        $session->setUseGiftcardapiDiscount(true);

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {   
        if (!Mage::helper('giftcardapi')->isEnabled()) return $this;        

        if ($address->getGiftcardapiDiscount()>0) {
			$quote = $address->getQuote();
			$store = $quote->getStore();
			$amount = $address->getGiftcardapiDiscount();
			
            $address->addTotal(array(
                'code'=>$this->getCode(),
                'title'=>Mage::helper('giftcardapi')->__('SureGifts Card Discount'),
                'value'=>-$amount,
            ));
        }
    }
}