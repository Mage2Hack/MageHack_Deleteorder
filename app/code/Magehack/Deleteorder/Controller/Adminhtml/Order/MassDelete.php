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
                /*Mage::getModel('sales/order')->load($orderId)->delete()->unsetAll();*/
                /*$this->_remove($orderId);*/
            }
            /*Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__(
                    'Total of %d record(s) were successfully deleted', count($orderIds)
                )
            );*/
            $this->messageManager->addSuccess(__('We deleted %1 order(s).', $countDeleteOrder));
        } catch (Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('sales/*/');
        return $resultRedirect;

        /*$this->_redirectUrl(Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/index'));*/
    }

    public function _remove($order_id)
    {


        $resource = $this->_resource;
        /*$resource = Mage::getSingleton('core/resource');*/
        $delete = $resource->getConnection('core_read');
        $order_table = $resource->getTableName('sales_flat_order_grid');
        $invoice_table = $resource->getTableName('sales_flat_invoice_grid');
        $shipment_table = $resource->getTableName('sales_flat_shipment_grid');
        $creditmemo_table = $resource->getTableName('sales_flat_creditmemo_grid');
        $sql = "DELETE FROM  " . $order_table . " WHERE entity_id = " . $order_id . ";";
        $delete->query($sql);
        $sql = "DELETE FROM  " . $invoice_table . " WHERE order_id = " . $order_id . ";";
        $delete->query($sql);
        $sql = "DELETE FROM  " . $shipment_table . " WHERE order_id = " . $order_id . ";";
        $delete->query($sql);
        $sql = "DELETE FROM  " . $creditmemo_table . " WHERE order_id = " . $order_id . ";";
        $delete->query($sql);

        return true;
    }




}
