<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Model_Creditmemo_Total extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {

        $order = $creditmemo->getOrder();

        $baseCreditmemoTotal = $creditmemo->getBaseGrandTotal();
        $creditmemoTotal = $creditmemo->getGrandTotal();
        $baseGiftcardapiDiscountInvoiced = $order->getBaseGiftcardapiDiscountInvoiced();
        $giftcardapiDiscountInvoiced = $order->getGiftcardapiDiscountInvoiced();

        if ($creditmemo->getInvoice()) {
            $invoice = $creditmemo->getInvoice();
            $baseGiftcardapiDiscountToCredit = $invoice->getBaseGiftcardapiDiscount();
            $giftcardapiDiscountToCredit = $invoice->getGiftcardapiDiscount();
        } else {
            $baseGiftcardapiDiscountToCredit = $baseGiftcardapiDiscountInvoiced;
            $giftcardapiDiscountToCredit = $giftcardapiDiscountInvoiced;
        }

        if (!$baseGiftcardapiDiscountToCredit > 0) {
            return $this;
        }

        $creditmemo->setBaseGrandTotal($baseCreditmemoTotal - $baseGiftcardapiDiscountToCredit);
        $creditmemo->setGrandTotal($creditmemoTotal - $giftcardapiDiscountToCredit);

        $creditmemo->setBaseGiftcardapiDiscount($baseGiftcardapiDiscountToCredit);
        $creditmemo->setGiftcardapiDiscount($giftcardapiDiscountToCredit);

        return $this;
    }
}