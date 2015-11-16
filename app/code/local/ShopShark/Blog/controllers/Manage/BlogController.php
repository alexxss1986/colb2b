<?php
/**
 * ShopShark Blog Extension
 * @version   1.0 12.09.2013
 * @author    ShopShark http://www.shopshark.net <info@shopshark.net>
 * @copyright Copyright (C) 2010 - 2013 ShopShark
 */

class ShopShark_Blog_Manage_BlogController extends Mage_Adminhtml_Controller_Action {

    public function preDispatch() {
        parent::preDispatch();
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('admin/blog/posts');
    }

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('blog/posts');

        return $this;
    }

    public function indexAction() {

        $this->displayTitle('Posts');


        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('blog/blog')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('blog_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('blog/posts');
            $this->displayTitle('Edit post');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('blog/manage_blog_edit'))
                    ->_addLeft($this->getLayout()->createBlock('blog/manage_blog_edit_tabs'));

            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blog')->__('Post does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('blog/blog')->load($id);

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('blog_data', $model);

        $this->loadLayout();
        $this->_setActiveMenu('blog/posts');
        $this->displayTitle('Add new post');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addContent($this->getLayout()->createBlock('blog/manage_blog_edit'))
                ->_addLeft($this->getLayout()->createBlock('blog/manage_blog_edit_tabs'));

        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);

        $this->renderLayout();
    }

    public function duplicateAction() {
        $oldIdentifier = $this->getRequest()->getParam('identifier');
        $i = 1;
        $newIdentifier = $oldIdentifier . $i;
        while (Mage::getModel('blog/post')->loadByIdentifier($newIdentifier)->getData())
            $newIdentifier = $oldIdentifier . ++$i;
        $this->getRequest()->setPost('identifier', $newIdentifier);
        $this->_forward('save');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('blog/post');
			
			if(isset($_FILES['post_image']['name']) and (file_exists($_FILES['post_image']['tmp_name']))) {
				try {
					$result['file'] = '';
					
					$uploader = new Varien_File_Uploader('post_image');
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
				 
				 
					$uploader->setAllowRenameFiles(true);
				 
					$uploader->setFilesDispersion(false);
				   
					$path = Mage::getBaseDir('media').DS.'shopshark'.DS.'blog'.DS ;
							   
					$result = $uploader->save($path, $_FILES['post_image']['name']);
				 
					$data['post_image'] = 'shopshark'.DS.'blog'.DS.$result['file'];
				}catch(Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . '  '. $path);
	                Mage::getSingleton('adminhtml/session')->setFormData($data);
	                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
	                return; 
				}
			} else {       
				if(isset($data['post_image']['delete']) && $data['post_image']['delete'] == 1)
					$data['post_image'] = '';
				else
					unset($data['post_image']);
			}


            if(isset($_FILES['post_image1']['name']) and (file_exists($_FILES['post_image1']['tmp_name']))) {
                try {
                    $result['file'] = '';

                    $uploader = new Varien_File_Uploader('post_image1');
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));


                    $uploader->setAllowRenameFiles(true);

                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media').DS.'shopshark'.DS.'blog'.DS ;

                    $result = $uploader->save($path, $_FILES['post_image1']['name']);

                    $data['post_image1'] = 'shopshark'.DS.'blog'.DS.$result['file'];
                }catch(Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . '  '. $path);
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
            } else {
                if(isset($data['post_image1']['delete']) && $data['post_image1']['delete'] == 1)
                    $data['post_image1'] = '';
                else
                    unset($data['post_image1']);
            }


            if(isset($_FILES['post_image2']['name']) and (file_exists($_FILES['post_image2']['tmp_name']))) {
                try {
                    $result['file'] = '';

                    $uploader = new Varien_File_Uploader('post_image2');
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));


                    $uploader->setAllowRenameFiles(true);

                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media').DS.'shopshark'.DS.'blog'.DS ;

                    $result = $uploader->save($path, $_FILES['post_image2']['name']);

                    $data['post_image2'] = 'shopshark'.DS.'blog'.DS.$result['file'];
                }catch(Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . '  '. $path);
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
            } else {
                if(isset($data['post_image2']['delete']) && $data['post_image2']['delete'] == 1)
                    $data['post_image2'] = '';
                else
                    unset($data['post_image2']);
            }


            if(isset($_FILES['post_image3']['name']) and (file_exists($_FILES['post_image3']['tmp_name']))) {
                try {
                    $result['file'] = '';

                    $uploader = new Varien_File_Uploader('post_image3');
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));


                    $uploader->setAllowRenameFiles(true);

                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media').DS.'shopshark'.DS.'blog'.DS ;

                    $result = $uploader->save($path, $_FILES['post_image3']['name']);

                    $data['post_image3'] = 'shopshark'.DS.'blog'.DS.$result['file'];
                }catch(Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . '  '. $path);
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
            } else {
                if(isset($data['post_image3']['delete']) && $data['post_image3']['delete'] == 1)
                    $data['post_image3'] = '';
                else
                    unset($data['post_image3']);
            }


            if(isset($_FILES['post_image4']['name']) and (file_exists($_FILES['post_image4']['tmp_name']))) {
                try {
                    $result['file'] = '';

                    $uploader = new Varien_File_Uploader('post_image4');
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));


                    $uploader->setAllowRenameFiles(true);

                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media').DS.'shopshark'.DS.'blog'.DS ;

                    $result = $uploader->save($path, $_FILES['post_image4']['name']);

                    $data['post_image4'] = 'shopshark'.DS.'blog'.DS.$result['file'];
                }catch(Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . '  '. $path);
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
            } else {
                if(isset($data['post_image4']['delete']) && $data['post_image4']['delete'] == 1)
                    $data['post_image4'] = '';
                else
                    unset($data['post_image4']);
            }


            if(isset($_FILES['post_image5']['name']) and (file_exists($_FILES['post_image5']['tmp_name']))) {
                try {
                    $result['file'] = '';

                    $uploader = new Varien_File_Uploader('post_image5');
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));


                    $uploader->setAllowRenameFiles(true);

                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media').DS.'shopshark'.DS.'blog'.DS ;

                    $result = $uploader->save($path, $_FILES['post_image5']['name']);

                    $data['post_image5'] = 'shopshark'.DS.'blog'.DS.$result['file'];
                }catch(Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . '  '. $path);
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
            } else {
                if(isset($data['post_image5']['delete']) && $data['post_image5']['delete'] == 1)
                    $data['post_image5'] = '';
                else
                    unset($data['post_image5']);
            }


            if(isset($_FILES['post_image6']['name']) and (file_exists($_FILES['post_image6']['tmp_name']))) {
                try {
                    $result['file'] = '';

                    $uploader = new Varien_File_Uploader('post_image6');
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));


                    $uploader->setAllowRenameFiles(true);

                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media').DS.'shopshark'.DS.'blog'.DS ;

                    $result = $uploader->save($path, $_FILES['post_image6']['name']);

                    $data['post_image6'] = 'shopshark'.DS.'blog'.DS.$result['file'];
                }catch(Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage() . '  '. $path);
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
            } else {
                if(isset($data['post_image6']['delete']) && $data['post_image6']['delete'] == 1)
                    $data['post_image6'] = '';
                else
                    unset($data['post_image6']);
            }


            if (isset($data['tags'])) {
                if ($this->getRequest()->getParam('id')) {
                    $model->load($this->getRequest()->getParam('id'));
                    $originalTags = explode(",", $model->getTags());
                } else {
                    $originalTags = array();
                }

                $tags = preg_split("/[,    ]+\s*/i", $data['tags'], -1, PREG_SPLIT_NO_EMPTY);

                foreach ($tags as $key => $tag) {
                    $tags[$key] = Mage::helper('blog')->convertSlashes($tag, 'forward');
                }
                $tags = array_unique($tags);



                $commonTags = array_intersect($tags, $originalTags);
                $removedTags = array_diff($originalTags, $commonTags);
                $addedTags = array_diff($tags, $commonTags);

                if (count($tags)) {
                    $data['tags'] = trim(implode(',', $tags));
                } else {
                    $data['tags'] = '';
                }
            }
            if (isset($data['stores'])) {
                if ($data['stores'][0] == 0) {
                    unset($data['stores']);
                    $data['stores'] = array();
                    $stores = Mage::getSingleton('adminhtml/system_store')->getStoreCollection();
                    foreach ($stores as $store)
                        $data['stores'][] = $store->getId();
                }
            }


            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {

                $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
                if (isset($data['created_time']) && $data['created_time']) {
                    $dateFrom = Mage::app()->getLocale()->date($data['created_time'], $format);
                    $model->setCreatedTime(Mage::getModel('core/date')->gmtDate(null, $dateFrom->getTimestamp()));
                    $model->setUpdateTime(Mage::getModel('core/date')->gmtDate());
                } else {
                    $model->setCreatedTime(Mage::getModel('core/date')->gmtDate());
                }


                if ($this->getRequest()->getParam('user') == NULL) {
                    $model->setUser(Mage::getSingleton('admin/session')->getUser()->getFirstname() . " " . Mage::getSingleton('admin/session')->getUser()->getLastname())
                            ->setUpdateUser(Mage::getSingleton('admin/session')->getUser()->getFirstname() . " " . Mage::getSingleton('admin/session')->getUser()->getLastname());
                } else {
                    $model->setUpdateUser(Mage::getSingleton('admin/session')->getUser()->getFirstname() . " " . Mage::getSingleton('admin/session')->getUser()->getLastname());
                }

                $model->save();


                /* recount affected tags */
                if (isset($data['stores'])) {
                    $stores = $data['stores'];
                } else {
                    $stores = array(null);
                }

                $affectedTags = array_merge($addedTags, $removedTags);

                foreach ($affectedTags as $tag) {
                    foreach ($stores as $store) {
                        if (trim($tag)) {
                            Mage::getModel('blog/tag')->loadByName($tag, $store)->refreshCount();
                        }
                    }
                }




                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('blog')->__('Post was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('blog')->__('Unable to find post to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        $postId = (int) $this->getRequest()->getParam('id');
        if ($postId) {
            try {
                $this->_postDelete($postId);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Post was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $blogIds = $this->getRequest()->getParam('blog');
        if (!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select post(s)'));
        } else {
            try {
                foreach ($blogIds as $postId) {
                    $this->_postDelete($postId);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($blogIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    protected function _postDelete($postId) {
        $model = Mage::getModel('blog/blog')->load($postId);
        $_tags = explode(',', $model->getData('tags'));
        $model->delete();
        $_stores = Mage::getSingleton('adminhtml/system_store')->getStoreCollection();
        foreach ($_tags as $tag) {
            foreach ($_stores as $store)
                if (trim($tag)) {
                    Mage::getModel('blog/tag')->loadByName($tag, $store->getId())->refreshCount();
                }
        }
    }

    public function massStatusAction() {
        $blogIds = $this->getRequest()->getParam('blog');
        if (!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select post(s)'));
        } else {
            try {

                foreach ($blogIds as $blogId) {
                    $blog = Mage::getModel('blog/blog')
                            ->load($blogId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setStores('')
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($blogIds))
                );
            } catch (Exception $e) {

                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    protected function displayTitle($data = null, $root = 'Blog') {

        if (!Mage::helper('blog')->magentoLess14()) {
            if ($data) {
                if (!is_array($data)) {
                    $data = array($data);
                }
                foreach ($data as $title) {
                    $this->_title($this->__($title));
                }
                $this->_title($this->__($root));
            } else {
                $this->_title($this->__('Blog'))->_title($root);
            }
        }
        return $this;
    }

}