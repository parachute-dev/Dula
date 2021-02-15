<?php
namespace Parachute\ReviewLL\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProductReview implements ObserverInterface
{
	protected $_storeManager;
	protected $_request;
	protected $_customerSession;

	public function __construct(
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\App\Request\Http $request,
	    \Magento\Customer\Model\Session $customerSession
	) {
		$this->_storeManager = $storeManager;
		$this->_request = $request;
	    $this->_customerSession = $customerSession;

	}

	public function execute(\Magento\Framework\Event\Observer $observer)
	{

		$curl = curl_init();

		$customerData = $this->_customerSession->getCustomer(); 

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.loyaltylion.com/v2/activities',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>'{
				"name": "posted_comment",
				"customer_email": "'  .  $customerData->getEmail()  .  '",
				"customer_id": "'  .  $customerData->getId()  .  '"
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Authorization: Basic NTNhOTY2MmNkM2ZiZDU0MmI0ZTM5ZTI2NWJlYjFmYTM6NWFlYjU1NDEwMzYxY2FlYmU3NTQ3OWExNjQ1MzI2YzQ='
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
	

	}
}