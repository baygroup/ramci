<?php
/**
 * API Library for RAMCI reports.
 * User: Michael Grunewalder (BayGroup Holdings)
 * Date: 15/03/2018
 * Time: 4:59 PM
 */

namespace Baygroup\Ramci;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class RamciApi
{
    private $username;
    private $password;
    private $serviceURL;


    public function __construct($username, $password, $serviceUrl){
        $this->username     =   $username;
        $this->password     =   $password;
        $this->serviceURL   =   $serviceUrl;
    }

    /*
     * This function takes an array of options for the RAMCU API and generates the XML code
     * that can be submitted via the API Call. Example:
     * [
     *  'ProductType'   =>  'CHECK_DATE_COMPANY',
     *  'EntityName'    =>  'My Company Name',
     *  'EntityId'      =>  '123456789'
     * ]
     *
     * will generate
     * <?xml version="1.0"?>
     * <xml>
     *  <ProductType>CHECK_DATE_COMPANY</ProductType>
     *  <EntityName>My Company Name</EntityName>
     *  <EntityId>12356789</EntityId>
     * </xml>
     *
     */
    public function generateXMLFromArray($data)
    {
        $xmlString  =   '<xml><request>';

        foreach ($data as $key => $value)
        {
            $xmlString .= "<$key>$value</$key>";
        }
        $xmlString      .=  '</request></xml>';

        // Hey Siri, give me a DOM
        $dom    =   new \DOMDocument;
        $dom->preserveWhiteSpace    =   false;

        //Hey Siri, add my XML string to the DOM and return it
        $dom->loadXML($xmlString);

        return $dom->saveXML();
    }


    /**
     * @param $requestXML
     * @param string $command
     * @return bool|mixed
     *
     * This function tries to retrieve the report data from RAMCI and returns an associate=ive array with the response;
     *
     * In case of a connectino error, it returns FALSE,
     *
     * if the request was succesful but the query resulted in data related errors, the returned array will have the fields:
     *
     * code  : contains the error code received from RAMCI
     * error : contains the error message received from RAMCI
     *
     * a succesful request returns an associative array with the fields according to the requested data
     *
     * OPTIONAL PARAMETER $command:
     *
     * By default the request calls the "report" endpoint you can change to the 'xml' endpoint by sending
     * the optional parameter $command with a value of 'xml' - 'pdf' is not supported by this function
     *
     * OPTIONAL PARAMETER $sendJSON:
     *
     * If this parameter is set to true, the funcitno will return the data in JSON format
     *
     */
    public function getReport($requestXML, $command='report', $sendJSON=false)
    {
        $client     =   new Client();

        $response =  $client->post(
            $this->serviceURL . '/' . $command,
            [
                'auth'      =>  [$this->username, $this->password],
                'headers'   =>  [
                    'Content-Type'  =>  'application/xml',
                    'Accept'        =>  'application/xml',
                ],
                'body'      =>  $requestXML,
                'debug'     =>  false
            ]
        );
        //Hey Siri, can you make this useful?
        if ($response->getStatusCode() != 200)
        {
            return false;
        }
        $xml        =   simplexml_load_string($response->getBody()->getContents());
        $json       =   json_encode($xml);
        $reportData =   ($sendJSON) ? $json : json_decode($json,true);

        return $reportData;

    }

}