<?php

class TreC_RestrictGuest_Model_Observer {

	private $restrictedPath = array('catalog_category', 'catalog_product', 'catalogsearch_result', 'checkout_cart', 'onestepcheckout_index', 'splashpro_page');  
    
    public function hookToControllerActionPreDispatch($observer)
    {

		$controller = $observer->getEvent()->getControllerAction();
		$requestPath = strtolower(Mage::app()->getRequest()->getModuleName() . '_' . Mage::app()->getRequest()->getControllerName());
		$session = Mage::getSingleton('customer/session');
		$msgAdded = false;
		
		if(Mage::getStoreConfig('restrictguest/general/enabled')){
			
			if(!$session->isLoggedIn()){
				
				if(in_array($requestPath, $this->restrictedPath)){
					
					if(Mage::getStoreConfig('restrictguest/restrictsections/enable_' . $requestPath)){
						$controller->setFlag('', $controller->FLAG_NO_DISPATCH, true);
						
						$msg = Mage::getStoreConfig('restrictguest/general/msg');
						if($msg){
							$messages = $session->getMessages();
							
							foreach($messages->getItems() as $message)
							{  
							   if($message->getText() == $msg){ 
							   		$msgAdded = true;
							   		break;
							   }
							}
							
							if(!$msgAdded){
								$session->addError($msg);
							}
						} 
						
						Mage::app()->getResponse()->setRedirect(Mage::getUrl('customer/account/login'))->sendResponse();
					}
				}
			}
		}

    }
}

