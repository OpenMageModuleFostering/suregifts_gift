<?php
/**
 *Giftcardapi Extension
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.store.onlinebizsoft.com/license.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to admin@onlinebizsoft.com so we can mail you a copy immediately.
 *
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     OnlineBiz <sales@onlinebizsoft.com>
 * @copyright  2007-2011 OnlineBiz
 * @license    http://www.store.onlinebizsoft.com/license.txt
 * @version    1.0.1
 * @link       http://www.store.onlinebizsoft.com
 */
class Suregifts_Giftcardapi_Model_Invoice_Total_Tax extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $memberDiscountTax = 0;
        $baseMemberDiscountTax = 0;
        $order = $invoice->getOrder();
        if (!$order->getBaseGiftcardapiDiscountTax() || $order->getBaseGiftcardapiDiscountTax() == $order->getBaseGiftcardapiDiscountTaxInvoiced())
        {
            return $this;
        }
		$includeTax = true;
        /**
         * Check Member Discount amount in previus invoices
         */
        foreach ($invoice->getOrder()->getInvoiceCollection() as $previusInvoice) {
            if ($previusInvoice->getGiftcardapiDiscountTax() && !$previusInvoice->isCanceled()) {
                $includeTax = false;
            }
		}

		$rate = $order->getBaseGiftcardapiDiscountTax()/$order->getBaseGiftcardapiDiscountTax();
		
		$memberDiscountTax = floatval($invoice->getGiftcardapiDiscount()*$rate);
		$baseMemberDiscountTax = floatval($invoice->getBaseGiftcardapiDiscount()*$rate);

        if ($includeTax) {
            $invoice->setGiftcardapiDiscountTax($invoice->getOrder()->getGiftcardapiDiscountTax());
            $invoice->setBaseGiftcardapiDiscountTax($invoice->getOrder()->getBaseGiftcardapiDiscountTax());
            $order->setGiftcardapiDiscountTaxInvoiced(floatval($order->getGiftcardapiDiscountTaxInvoiced()) + $memberDiscountTax); 
            $order->setBaseGiftcardapiDiscountTaxInvoiced(floatval($order->getBaseGiftcardapiDiscountTaxInvoiced()) + $baseMemberDiscountTax);

        }
		
		
        /**
         * Not isLast() invoice case handling
         * totalTax adjustment
         * check Mage_Sales_Model_Order_Invoice_Total_Tax::collect()
         */
		 
        $allowedTax     = $order->getTaxAmount() - $order->getTaxInvoiced();
        $allowedBaseTax = $order->getBaseTaxAmount() - $order->getBaseTaxInvoiced();
        $totalTax = $invoice->getTaxAmount();

        $baseTotalTax = $invoice->getBaseTaxAmount();
		$invoice->setTaxAmount($invoice->getTaxAmount() - $memberDiscountTax);
		$invoice->setBaseTaxAmount($invoice->getBaseTaxAmount() - $baseMemberDiscountTax);
        if ($invoice->isLast()) {
			$invoice->setBaseSubtotalInclTax($invoice->getBaseSubtotalInclTax() + $invoice->getOrder()->getBaseGiftcardapiDiscountTax());
			$invoice->setSubtotalInclTax($invoice->getSubtotalInclTax() + $invoice->getOrder()->getGiftcardapiDiscountTax());
		}
		//$order->setBaseGiftcardapiDiscountInvoiced(floatval($order->getBaseGiftcardapiDiscountInvoiced()) + floatval($invoice->getBaseGiftcardapiDiscount()));
        //$order->setGiftcardapiDiscountInvoiced(floatval($order->getGiftcardapiDiscountInvoiced()) + floatval($invoice->getGiftcardapiDiscount()));
		
        return $this;
    }
}