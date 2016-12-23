<?php 
	class Tinkerlust_InternalApi_Helper_Data extends Mage_Core_Helper_Abstract{		
		public function curl($path,$params = null,$method = 'GET') {

		    $ch = curl_init();

		    if ($method == 'POST'){
				curl_setopt($ch, CURLOPT_POST,1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

			}
			else if ($method == 'GET' && $params != null){
				$path .= '?' . http_build_query($params);	
			}

			curl_setopt($ch, CURLOPT_URL,$path);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json','Content-Type: application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_FAILONERROR,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 15);			
			$returnValue = curl_exec($ch);          
			curl_close($ch);
			return $returnValue;
		}

		public function buildJson($data, $status = true, $message = null){
			header('Content-type: application/json');
			$message = $message?:(($status == true) ? 'Success' : 'Failed');
			echo json_encode(array('data'=>$data,'status'=>$status,'message'=>$message));
			die();
		}

		public function returnJson($data){
			header('Content-type: application/json');
			echo $data;
			die();
		}

		public function createCustomer($registrationData = null){
			$customer = Mage::getModel('customer/customer')
						->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
						->setData($registrationData);
			$return = array();
			try {
				$customer->save();
				$return['status'] = true;
				$return['message'] = 'Success';
				$customer->sendNewAccountEmail('registered','',Mage::app()->getStore()->getId());
			}	
			catch(Exception $ex){
				$return['status'] = false;
				$return['message'] = $ex->getMessage();
			}
			return $return;
		}
	}
 ?>