<?php
require_once 'Mage/Contacts/controllers/IndexController.php';
class TreC_ContactPage_IndexController extends Mage_Contacts_IndexController {
    public function postAction()
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

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['comment']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                /* campi aggiuntivi */
                if (!Zend_Validate::is(trim($post['motivo']), 'NotEmpty')) {
                    $error = true;
                }

                if (($post['motivo']=='Informazioni sul prodotto' || $post['motivo']=='Product information')&& !Zend_Validate::is(trim($post['codice_prodotto']), 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['negozio']), 'NotEmpty')) {
                    $error = true;
                }
                /* fine campi aggiuntivi */

                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }

                if ($post['motivo']=='Informazioni sul prodotto' || $post['motivo']=='Product information'){
                    $templateAdmin = Mage::getModel('core/email_template') ->loadByCode('contatti_admin_ita_prodotto')->getTemplateId();
                }
                else {
                    $templateAdmin = Mage::getModel('core/email_template') ->loadByCode('contatti_admin_ita_standard')->getTemplateId();
                }

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

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                // invio mail anche a cliente
                if ($post['motivo']=='Informazioni sul prodotto' || $post['motivo']=='Product information'){
                    if (Mage::app()->getStore()->getId()==1) {
                        $templateClient = Mage::getModel('core/email_template') ->loadByCode('contatti_cliente_prodotto_ita')->getTemplateId();
                    }
                    else if (Mage::app()->getStore()->getId()==2) {
                        $templateClient = Mage::getModel('core/email_template') ->loadByCode('contatti_cliente_prodotto_eng')->getTemplateId();
                    }
                    else if (Mage::app()->getStore()->getId()==3) {
                        $templateClient = Mage::getModel('core/email_template') ->loadByCode('contatti_cliente_prodotto_usa')->getTemplateId();
                    }
                }
                else {
                    if (Mage::app()->getStore()->getId()==1) {
                        $templateClient = Mage::getModel('core/email_template') ->loadByCode('contatti_cliente_standard_ita')->getTemplateId();
                    }
                    else if (Mage::app()->getStore()->getId()==2) {
                        $templateClient = Mage::getModel('core/email_template') ->loadByCode('contatti_cliente_standard_eng')->getTemplateId();
                    }
                    else if (Mage::app()->getStore()->getId()==3) {
                        $templateClient = Mage::getModel('core/email_template') ->loadByCode('contatti_cliente_standard_usa')->getTemplateId();
                    }
                }

                $mailTemplate2 = Mage::getModel('core/email_template');
                $mailTemplate2->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT))
                    ->sendTransactional(
                        $templateClient,
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        $post['email'],
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate2->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                $this->_redirect('*/*/');
                return;
            }

        } else {
            $this->_redirect('*/*/');
        }
    }


}