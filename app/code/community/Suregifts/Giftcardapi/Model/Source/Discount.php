<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Model_Source_Discount
{
    const FIXED_TYPE = '1';
    const PERCENTAGE_TYPE = '2';


    /*
     * Returns array of style options
     * @return array Options array like id => name
     */
    public static function toShortOptionArray()
    {
        $helper = Mage::helper('giftcardapi');
        $result = array(
            self::FIXED_TYPE   => $helper->__('Fixed'),
            self::PERCENTAGE_TYPE    => $helper->__('Percentage')
        );
        return $result;
    }

    public static function toOptionArray()
    {
        $options = self::toShortOptionArray();
        $res = array();

        foreach($options as $k => $v)
            $res[] = array(
                'value' => $k,
                'label' => $v
            );

        return $res;
    }

}