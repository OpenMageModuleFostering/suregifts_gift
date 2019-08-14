<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Model_Invoice_Total_Giftcardapi extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
		
        $order = $invoice->getOrder();

        if (!$order->getBaseGiftcardapiDiscount() || $order->getBaseGiftcardapiDiscount() == $order->getBaseGiftcardapiDiscountInvoiced())
        {
            return $this;
        }
       
        $baseGiftcardDiscount = $order->getBaseGiftcardapiDiscount();
        $baseGiftcardDiscountInvoiced = floatval($order->getBaseGiftcardapiDiscountInvoiced());
        $baseInvoiceTotal = $invoice->getBaseGrandTotal();
        $giftcardDiscount = $order->getGiftcardapiDiscount();
        $giftcardDiscountInvoiced = floatval($order->getGiftcardapiDiscountInvoiced());
        $invoiceTotal = $invoice->getGrandTotal();

        if (!$baseGiftcardDiscount || $baseGiftcardDiscountInvoiced==$baseGiftcardDiscount) {
            return $this;
        }

        $baseGiftcardDiscountToInvoice = $baseGiftcardDiscount - $baseGiftcardDiscountInvoiced;
        $giftcardDiscountToInvoice = $giftcardDiscount - $giftcardDiscountInvoiced;
		$giftcardDiscountTax = $invoice->getOrder()->getGiftcardapiDiscountTax();
        $baseGiftcardDiscountTax = $invoice->getOrder()->getBaseGiftcardapiDiscountTax();
        $baseInvoiceTotal = $baseInvoiceTotal - $baseGiftcardDiscountToInvoice;
        $invoiceTotal = $invoiceTotal - $giftcardDiscountToInvoice;
        $invoice->setBaseGrandTotal($baseInvoiceTotal);
        $invoice->setGrandTotal($invoiceTotal);
        $invoice->setBaseGiftcardapiDiscount($baseGiftcardDiscountToInvoice);
        $invoice->setGiftcardapiDiscount($giftcardDiscountToInvoice);
		
        $order->setBaseGiftcardapiDiscountInvoiced($baseGiftcardDiscountInvoiced + $baseGiftcardDiscountToInvoice);
        $order->setGiftcardapiDiscountInvoiced($giftcardDiscountInvoiced + $giftcardDiscountToInvoice);
		
        return $this;
    }
}