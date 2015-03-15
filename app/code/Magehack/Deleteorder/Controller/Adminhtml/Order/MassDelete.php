<?php

namespace Magehack\Deleteorder\Controller\Adminhtml\Order;

class MassDelete extends \Magento\Sales\Controller\Adminhtml\Order
{
    /**
     * Delete selected orders
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {

        $orderIds = $this->getRequest()->getPost('order_ids', []);
        try {
            $countDeleteOrder = 0;
            foreach ($orderIds as $orderId) {
                $order = $this->_objectManager->create('Magento\Sales\Model\Order')->load($orderId);
                $order->delete()->unsetAll();
                $countDeleteOrder++;
            }
            $this->messageManager->addSuccess(__('We deleted %1 order(s).', $countDeleteOrder));
        } catch (Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('sales/*/');
        return $resultRedirect;

    }



}
