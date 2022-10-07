<?php
namespace Tribuladores\Weather\Block;

class Weather extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    private $remoteAddress;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
    ) {
        $this->remoteAddress = $remoteAddress;
        parent::__construct($context);
    }

    public function getBlock()
    {
        function get_ip_detail($ip){
            $ip_response = file_get_contents('http://ip-api.com/json/'.$ip);
            $ip_array=json_decode($ip_response);
            return  $ip_array;
        }
         
        $user_ip=$_SERVER['REMOTE_ADDR'];
        $ip_array= get_ip_detail($user_ip);
        $country_name=$ip_array->countryCode; 
        $city=$ip_array->city;    
       
       
        $apiKey = "your_api_key";
        $cityId = "$city,$country_name";
        $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=$cityId&units=metric&appid=$apiKey";

        $jsonfile = file_get_contents($apiUrl);        
        $jsondata = json_decode($jsonfile);
        $temp = $jsondata->main->temp;      
        $humidity = $jsondata->main->humidity;
        $otra_ip =  $this->remoteAddress->getRemoteAddress();
        $weather = "La temperatura en $city es de $temp ºC y humedad de $humidity%";
        return $weather;    
    }
}
