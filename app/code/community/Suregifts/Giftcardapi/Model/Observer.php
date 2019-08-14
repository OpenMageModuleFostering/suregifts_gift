<?php
/**
 * Suregifts Giftcard API Extension
 * @category   Magento Extensions
 * @package    Suregifts_Giftcardapi
 * @author     Sodiq <damilolasodiq@gmail.com>
 */
class Suregifts_Giftcardapi_Model_Observer {

	public function salesOrderSaveBefore($observer){
		$order = $observer->getEvent()->getOrder();
		$giftcard = Mage::getSingleton('checkout/session')->getQuote()->getGiftcardapiCode();
		$order->setGiftcardapiCode($giftcard);
		
		
	}
	public function salesOrderSaveAfter($observer){
		$order = $observer->getEvent()->getOrder();
		$giftcartCode = $order->getGiftcardapiCode();
		 
			
	   
		if($giftcartCode){
			
		  $array_config= Mage::getStoreConfig('giftcardapi/suregifts_group',Mage::app()->getStore());
			$username =$array_config['suregifts_username'];
        	$password =$array_config['suregifts_password'];
        	$website_host = $array_config['suregifts_websitehost'];
        	$mode = $array_config['suregifts_mode'];
       
			
			 $auth = $username.':'.$password;
			 
			  $data = array( 
          "AmountToUse" => $order->getBaseGiftcardapiDiscount() , 
        //"AmountToUse" => 2000,
          "VoucherCode" => $giftcartCode,
          "WebsiteHost" => $website_host
          );  
		  
		  $data_string = json_encode($data);
		  

       		  if ($mode == 1 ){
          $ch = curl_init('https://stagging.oms-suregifts.com/api/voucher');
          }else{
           $ch = curl_init('https://oms-suregifts.com/api/voucher');
        }

			$header= array();
		    $header[0]= 'Content-Type: application/json';
			$header[1]='Content-Length: ' . strlen($data_string);
			
		
          	curl_setopt($ch, CURLOPT_POST, true);
          	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
          	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
			
			if ($username!=''){
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          	$header[2]= "Authorization: Basic ".base64_encode($auth);
			}
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			

			$resp = curl_exec($ch);
			$response_info=array();
			curl_close($ch);
			$response_info=json_decode($resp, true);
			Mage::log($order->getBaseGiftcardapiDiscount()  , null, 'mode.log');
			if ($response_info['Response']!="00"){
				//Mage::getSingleton('checkout/session')->addError($this->__('Your Gift card discount amount is invalid or has already been used'));
				//$url = Mage::getUrl('checkout/cart');
				//$response = Mage::app()->getFrontController()->getResponse();
				//$response->setRedirect($url);

				$controllerAction = $observer->getEvent()->getControllerAction();
				$result = array();
				$result['error'] = '-1';
				$result['message'] = $response_info['Description']!=null?$response_info['Description']:"Could not redeem giftcard on suregift";
				$controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
exit;
			}
			
				
			
			Mage::getSingleton('checkout/session')->getQuote()->setGiftcardapiCode('')->save();
			Mage::getSingleton('checkout/session')->setUseGiftcardapiDiscount(false);
			Mage::getSingleton('checkout/session')->setGiftcardapiDiscountAmount(0);
		

        
		}
	}
	public function salesInvoiceCreateAfter($observer){

	}
	
	public function getProductFinalPrice($observer){	

	}
	
	public function insertBlock($observer){
		$_block=$observer->getBlock();
		$_type=$_block->getType();
		echo $_type;
		if($_type=='checkout/cart_crosssell'){
				$_child= clone $_block;
				$_child->setType('checkout/cart_coupon');
				$_block->setChild('child',$_child);
				$_block->setTemplate('giftcardapi/discount.phtml');
			
			}
		
	}
}