<?php
namespace Tribuladores\Checkout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use \Magento\Customer\Api\CustomerRepositoryInterface;

class Orderplaceafter implements ObserverInterface
{
    
    protected $messageManager;
    protected $customerRepositoryInterface;

    

    public function __construct(
        ManagerInterface $messageManager,
        CollectionFactory $orderCollectionFactory,
        CustomerRepositoryInterface $customerRepositoryInterface
    ){
        
        $this->messageManager = $messageManager;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
                    
    }
    
    public function execute(Observer $observer){

        $order = $observer->getEvent()->getOrder();
        $order_id = $order->getID(); 
                
        $customerId = $order->getCustomerId();
        //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //$customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
        $customer = $this->customerRepositoryInterface->getById($customerId);
        $address = $observer->getShippingAddress();
        $address = json_encode($address);
        $name = $customer->getFirstname();                
        $customerEmail = $customer->getEmail();
        
        $total=0;
        $orders = $this->orderCollectionFactory->create()->addAttributeToFilter('customer_email', $customerEmail);
        foreach ($orders as $order) {
            $total=$total+$order->getGrandTotal();
        }


        $this->messageManager->addSuccessMessage("Gracias $name por tu pedido $order_id, llevas gastado $total €"); 
        
    }
        
 }