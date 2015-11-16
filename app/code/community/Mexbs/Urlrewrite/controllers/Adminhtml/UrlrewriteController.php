<?php
require_once('Mage/Adminhtml/controllers/UrlrewriteController.php');
/**
 * Mexbs_Urlrewrite_Adminhtml_IndexController
 * class that is used for overriding Mage_Adminhtml_UrlrewriteController
 * to add a delete mass action
 *
 * @copyright Mexbs
 */
class Mexbs_Urlrewrite_Adminhtml_UrlrewriteController extends Mage_Adminhtml_UrlrewriteController
{
    /**
     * mass delete action, deletes the selected url rewrites
     */
    public function massDeleteAction()
    {
        $reviewsIds = $this->getRequest()->getParam('url_rewrites');
        $session    = Mage::getSingleton('adminhtml/session');

        if(!is_array($reviewsIds)) {
            $session->addError(Mage::helper('mexbs_urlrewrite')->__('Please select rewrite(s).'));
        } else {
            try {
                foreach ($reviewsIds as $reviewId) {
                    $model = Mage::getModel('core/url_rewrite')->load($reviewId);
                    $model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) have been deleted.', count($reviewsIds))
                );
            } catch (Exception $e) {
                $session->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/' . $this->getRequest()->getParam('ret', 'index'));
    }

    /**
     * check whether the current user is allowed to access this controller
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('mexbs_urlrewrite');
    }
}