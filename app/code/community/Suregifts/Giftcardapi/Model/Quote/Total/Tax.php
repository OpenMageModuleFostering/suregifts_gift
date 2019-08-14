<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Model_Quote_Total_Tax extends Mage_Sales_Model_Quote_Address_Total_Tax
{
    const CONFIG_XML_PATH_GIFTDISCOUNT_INCLUDES_TAX = 'tax/calculation/giftcardapi_includes_tax';
    const CONFIG_XML_PATH_GIFTDISCOUNT_TAX_CLASS = 'tax/classes/giftcardapi_tax_class';
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        $store = $address->getQuote()->getStore();        

        $items = $address->getAllItems();
        if (!count($items)) {
            return $this;
        }

        $custTaxClassId = $address->getQuote()->getCustomerTaxClassId();

        $taxCalculationModel = Mage::getSingleton('tax/calculation');
        /* @var $taxCalculationModel Mage_Tax_Model_Calculation */
        $request = $taxCalculationModel->getRateRequest(
            $address,
            $address->getQuote()->getBillingAddress(),
            $custTaxClassId,
            $store
        );
        $giftcardTaxClass = Mage::helper('giftcardapi')->getGiftcardDiscountTaxClass($store);

        $giftcardDiscountTax      = 0;
        $baseGiftcardDiscountTax  = 0;

        if ($giftcardTaxClass) {
            if ($rate = $taxCalculationModel->getRate($request->setProductClassId($giftcardTaxClass))) {
                
                $giftcardDiscountTax    = $address->getGiftcardapiDiscountTax();
                $baseGiftcardDiscountTax= $address->getBaseGiftcardapiDiscountTax();
                $giftcardDiscountTax    = $store->roundPrice($giftcardDiscountTax);
                $baseGiftcardDiscountTax= $store->roundPrice($baseGiftcardDiscountTax);
				$baseTax = $store->roundPrice($address->getBaseTaxAmount());
				$tax = $store->roundPrice($address->getTaxAmount());
				$address->setTaxAmount(floatval($tax - $giftcardDiscountTax));
				$address->setBaseTaxAmount(floatval($baseTax - $baseGiftcardDiscountTax));

				$this->_saveAppliedTaxes(
					$address,
					$taxCalculationModel->getAppliedRates($request),
					$giftcardDiscountTax,
					$baseGiftcardDiscountTax,
					$rate
				);
				
            }
        }
		
		$address->setGrandTotal($address->getGrandTotal() - $address->getGiftcardapiDiscountTax());
        $address->setBaseGrandTotal($address->getBaseGrandTotal() - $address->getBaseGiftcardapiDiscountTax());
		
        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {        
        $store = $address->getQuote()->getStore();
        /**
         * Modify subtotal
         */
        if (Mage::getSingleton('tax/config')->displayCartSubtotalBoth($store) ||
            Mage::getSingleton('tax/config')->displayCartSubtotalInclTax($store)) {
            if ($address->getSubtotalInclTax() > 0) {
                $subtotalInclTax = $address->getSubtotalInclTax();
            } else {
                $subtotalInclTax = $address->getSubtotal()+ $address->getTaxAmount() -
                    $address->getShippingTaxAmount() - $address->getGiftcardapiDiscountTax();
            }            

            $address->addTotal(
                array(
                    'code'      => 'subtotal',
                    'title'     => Mage::helper('sales')->__('Subtotal'),
                    'value'     => $subtotalInclTax,
                    'value_incl_tax' => $subtotalInclTax,
                    'value_excl_tax' => $address->getSubtotal()
                )
            );
        }

        return $this;
    }
}