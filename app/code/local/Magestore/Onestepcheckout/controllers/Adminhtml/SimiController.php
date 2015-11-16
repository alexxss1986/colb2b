<?php 
class Magestore_Onestepcheckout_Adminhtml_SimiController extends Mage_Adminhtml_Controller_Action
{
public function indexAction(){
		$url = "https://www.simicart.com/usermanagement/checkout/buyProfessional/?extension=3&utm_source=magestorebuyer&utm_medium=backend&utm_campaign=Magestore Buyer Backend";

		Mage::app()->getResponse()->setRedirect($url)->sendResponse();
		exit();
	}

}