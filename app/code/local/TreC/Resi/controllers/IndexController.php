<?php
class TreC_Resi_IndexController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';

    public function indexAction()
    {
        //Get current layout state
        $this->loadLayout();
        $block = $this->getLayout()->createBlock(
            'Mage_Core_Block_Template',
            'trec.resi',
            array(
                'template' => 'trec/resi.phtml'
            )
        );
        $this->getLayout()->getBlock('content')->append($block);
        //$this->getLayout()->getBlock('right')->insert($block, 'catalog.compare.sidebar', true);
        $this->_initLayoutMessages('core/session');
        $this->renderLayout();
    }
    public function sendemailAction()
    {

        $post = $this->getRequest()->getPost();
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                if (!Zend_Validate::is(trim($post['id_ordine']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['nome']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['cognome']), 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }


                if ($error) {
                    throw new Exception();
                }


                $templateAdmin = Mage::getModel('core/email_template') ->loadByCode('template_reso_admin')->getTemplateId();

                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($post['email'])
                    ->sendTransactional(
                        $templateAdmin,
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );


                if (Mage::app()->getStore()->getId()==1) {
                    $templateClient = Mage::getModel('core/email_template') ->loadByCode('template_reso_ita')->getTemplateId();
                }
                else if (Mage::app()->getStore()->getId()==2) {
                    $templateClient = Mage::getModel('core/email_template') ->loadByCode('template_reso_eng')->getTemplateId();
                }
                else if (Mage::app()->getStore()->getId()==3) {
                    $templateClient = Mage::getModel('core/email_template') ->loadByCode('template_reso_usa')->getTemplateId();
                }



                $mailTemplate2 = Mage::getModel('core/email_template');
                /* @var $mailTemplate2 Mage_Core_Model_Email_Template */
                $mailTemplate2->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT))
                    ->sendTransactional(
                        $templateClient,
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        $post['email'],
                        null,
                        array('data' => $postObject)
                    );


                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }
                if (!$mailTemplate2->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                $this->_redirect('trec-resi');

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                $this->_redirect('trec-resi/');
                return;
            }

        } else {
            $this->_redirect('trec-resi');
        }
    }
}
?>