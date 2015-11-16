<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Adminhtml_SplashController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Display the list of all splash pages
	 *
	 * @return void
	 */
	public function indexAction()
	{
		$this->loadLayout();
		$this->_title('Splash Pro by FishPig');
		$this->renderLayout();
	}
	
	/**
	 * Allow the user to enter a new splash page
	 * This is just a wrapper for the edit action
	 *
	 * @return void
	 */
	public function newAction()
	{
		$this->_forward('edit');
	}
	
	/**
	 * Edit an existing splash page
	 *
	 * @return void
	 */
	public function editAction()
	{
		$titles = array(
			$this->_title('Splash Pro'),
		);

		if (($page = $this->_getPage()) !== false) {
			$titles[] = $this->_title($page->getName());
		}
		else {
			$titles[] = Mage::helper('cms')->__('New Page');
		}

		$this->loadLayout();
		
		foreach($titles as $title) {
			$this->_title($title);
		}
			
		$this->renderLayout();
	}
	
	/**
	 * Retrieve the data for the category tree as JSON
	 *
	 * @return void
	 */
	public function categoriesJsonAction()
	{
        $page = $this->_getPage();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('splash/adminhtml_page_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
	}

	/**
	 * Save a splash page
	 *
	 * @return void
	 */
	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost('splash')) {
			$page = Mage::getModel('splash/page')
				->setData($data)
				->setId($this->getRequest()->getParam('id', null));

			$categoryIds = trim($this->getRequest()->getPost('category_ids'), ',');
			$categoryOperator = $this->getRequest()->getPost('category_operator', Fishpig_AttributeSplashPro_Model_Resource_Page::FILTER_OPERATOR_DEFAULT);
			
			if ($categoryIds !== '') {
				$categoryFilters = array(
					'ids' => explode(',', $categoryIds),
					'operator' => $categoryOperator,
				);
				
				$page->setCategoryFilters($categoryFilters);
			}

			try {
				$page->save();

				$this->_getSession()->addSuccess(Mage::helper('cms')->__('The page has been saved.'));
			}
			catch (Exception $e) {
				$this->_getSession()->addError($this->__($e->getMessage()));
			}
				
			if ($page->getId() && $this->getRequest()->getParam('back', false)) {
				$this->_redirect('*/*/edit', array('id' => $page->getId()));
				return;
			}
		}
		else {
			$this->_getSession()->addError($this->__('There was no data to save.'));
		}

		$this->_redirect('*/*');
	}
	
	/**
	 * Delete the selected spash page
	 *
	 * @return void
	 */
	public function deleteAction()
	{
		if ($objectId = $this->getRequest()->getParam('id')) {
			$object = Mage::getModel('splash/page')->load($objectId);
			
			if ($object->getId()) {
				try {
					$object->delete();
					$this->_getSession()->addSuccess($this->__('The Splash Page was deleted.'));
				}
				catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
		}

		$this->_redirect('*/*');
	}
	
	/**
	 * Retrieve the current page
	 *
	 * @return false|Fishpig_AttributeSplashPro_Model_Page
	 */
	protected function _getPage()
	{
		if (($page = Mage::registry('splash_page')) !== null) {
			return $page;
		}

		$page = Mage::getModel('splash/page')->load($this->getRequest()->getParam('id', 0));

		if ($page->getId()) {
			Mage::register('splash_page', $page);
			return $page;
		}
		
		return false;
	}
}