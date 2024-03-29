<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_IndexController extends Mage_Core_Controller_Front_Action
{
	
	protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }
    public function indexAction()
    {
		$this->loadLayout();  
		$this->renderLayout();
    }
	
	public function reorderAction()
    {	
		$orderId = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($orderId);
		
    }

	
	public function discountPostAction()
	{

		if($data = $this->getRequest()->getPost()){
			if($data['remove'] ==1){
				$this->_getSession()->getQuote()->setGiftcardapiCode('')->save();
				$this->_getSession()->setUseGiftcardapiDiscount(false);
				$this->_getSession()->setGiftcardapiDiscountAmount(0);
				$this->_getSession()->addSuccess($this->__('Your Gift card discount has been removed.'));
				$this->_redirect('checkout/cart');
				return;
			}
			if($data['giftcard'] ==''){
				$this->_getSession()->addError($this->__('Please enter your gift card.'));
				$this->_redirect('checkout/cart');
				return;
			}
			
			 
			$array_config= Mage::getStoreConfig('giftcardapi/suregifts_group',Mage::app()->getStore());
			$username =$array_config['suregifts_username'];
        	$password =$array_config['suregifts_password'];
        	$website_host = $array_config['suregifts_websitehost'];
        	$mode = $array_config['suregifts_mode'];
		$country=$array_config['suregifts_country'];
       		$auth = $username.':'.$password;
     $validationUrl =Mage::getModel('giftcardapi/giftcardapi')->getValidationUrl($country,$mode);

                

	//just added this Feb 12 2016

	$ch = curl_init($validationUrl.'?vouchercode='.$data['giftcard']);



          	curl_setopt($ch, CURLOPT_POST, false);
          	
          	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
			
			if ($username!=''){
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  "Authorization: Basic ".base64_encode($auth),
                    )
              );
			}
			

			$resp = curl_exec($ch);
			$response_info=array();
			curl_close($ch);
			$response_info=json_decode($resp, true);
			//$response_info['AmountToUse']=20000;
			Mage::log($response_info['AmountToUse']  , null, 'mode.log');
			if(isset($response_info['AmountToUse']) && $response_info['AmountToUse']) {
				$this->_getSession()->getQuote()->setGiftcardapiCode($data['giftcard'])->save();
				$this->_getSession()->addSuccess($this->__('Your SureGifts card discount has been applied.'));
				$this->_getSession()->setUseGiftcardapiDiscount(true);
				$this->_getSession()->setGiftcardapiDiscountAmount($response_info['AmountToUse']);
			} else {
				$this->_getSession()->getQuote()->setGiftcardapiCode('')->save();
				$this->_getSession()->setUseGiftcardapiDiscount(false);
				$this->_getSession()->setGiftcardapiDiscountAmount(0);
				$this->_getSession()->addError($this->__('Your Gift card discount amount is invalid or has already been used'));
			}
		}
        $this->_redirect('checkout/cart');
	}
	
	
}
