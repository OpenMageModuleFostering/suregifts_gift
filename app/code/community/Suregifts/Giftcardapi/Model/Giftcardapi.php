<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package   Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Model_Giftcardapi extends Mage_Core_Model_Abstract
{

    private $_validationUrl=array(
		'KEN'=>array('test'=>"http://kenyastaging.oms-suregifts.com/api/voucherredemption","live"=>"http://kenya.oms-suregifts.com/api/voucherredemption"),
		'NGR'=>array('test'=>"http://sandbox.oms-suregifts.com/api/voucherredemption","live"=>"https://oms-suregifts.com/api/voucherredemption")	
	);

    private $_confirmationUrl=array(
		'KEN'=>array('test'=>"http://kenyastaging.oms-suregifts.com/api/voucherredemption","live"=>"http://kenya.oms-suregifts.com/api/voucherredemption"),
		'NGR'=>array('test'=>"http://sandbox.oms-suregifts.com/api/voucherredemption","live"=>"https://oms-suregifts.com/api/voucherredemption")			
	);

    public function getValidationUrl($country,$mode){
	return $this->_validationUrl[$country][$mode=="1"?"test":"live"];
    }

    public function getConfirmationUrl($country,$mode){
	return $this->_confirmationUrl[$country][$mode=="1"?"test":"live"];
    }

    


    public function _construct()
    {
        parent::_construct();
    }
}
