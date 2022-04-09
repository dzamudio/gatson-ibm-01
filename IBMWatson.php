<?php
namespace API\Watson;

use http\Params;

include("DatabaseHandler.php");
include_once("helpers.php");

class NLP
{

    private $apiUrl = "https://api.us-south.natural-language-understanding.watson.cloud.ibm.com/instances/4343cace-f35a-465f-b9c0-5cf106825657";
    private $apiKey = "eQK34W4VWmLYYnWn_BXpUV2jE8E0BMKpRtA6kGPZpfYU";
    private $apiVer = "/v1/analyze?version=2020-12-09";
    public $debug;
    public $apiCallSignature;

    /**
     * @param string $url The url whose contents will be crawled and analyzed.
     * @return boolean Returns true if successful, false if not.
     */
    public function analyzeURL($url)
    {
        /*
         * Please see https://cloud.ibm.com/apidocs/natural-language-understanding#text-analytics-features
         * for more options in what can be added to the request api
         */
        $featuresArr = (object) array();
        $featuresArr->{'sentiment'} = (object) array();
        $featuresArr->{'categories'} = (object) array();
        $featuresArr->{'concepts'} = (object) array();
        $featuresArr->{'entities'} = (object) array();
        $featuresArr->{'keywords'} = (object) array();
        $featuresArr->{'emotion'} = (object) array();
        $featuresArr->{'metadata'} = (object) array();
        $featuresArr->{'relations'} = (object) array();
        $featuresArr->{'semantic_roles'} = (object) array();
        $featuresArr->{'syntax'} = (object) array('sentences'=>true,'tokens'=>array('lemma'=>true,'part_of_speech'=>true));
        $requestPayload = array(
            'url' => $url,
            'features' => $featuresArr
        );

        $this->apiCallSignature = md5( serialize($requestPayload) . serialize($this->apiVer ));
        $existingJSONFileName = $this->apiCallSignature.'.json';

        // check if this exact API Call has already been made...
        if ( !file_exists($existingJSONFileName) ) {
            error_log('IBMWatson.php - NLP->analyzeURL(): API Response does not exist.');
            $proceedMakeCall = true;
        } else {
            error_log('IBMWatson.php - NLP->analyzeURL(): API Response Exists');
            $proceedMakeCall = false;
        }

        // perform api call if it hasn't before...
        if( $proceedMakeCall ) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $this->apiVer);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestPayload));
            curl_setopt($ch, CURLOPT_USERPWD, 'apikey' . ':' . $this->apiKey);
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                error_log('IBMWatson.php - NLP->analyzeURL(): Curl error on line:' . __LINE__ . ' - ' . curl_error($ch));
            }
            $curlDebug = curl_getinfo($ch); // stored in lastCurlDebugInfo.txt
            $debugFile_handle = fopen('lastCurlDebugInfo.txt','a');
            fwrite($debugFile_handle,"test:".time()."\n".json_encode($curlDebug)."\n\n");
            fclose($debugFile_handle);
            curl_close($ch);

            $this->debug =  $result;

            // save json response to file using the apiCallSignature to name it.
            $file_handle = fopen($existingJSONFileName,'w');
            fwrite($file_handle,$this->debug);
            fclose($file_handle);
            // now save info to database
        } else {
            $this->debug = file_get_contents($existingJSONFileName);
        }

        $db = new \Core\DatabaseHandler;

        /*
         * Recursive function needed ...
         */


        print("\n\nAPI CALL ALREADY EXISTS - {$existingJSONFileName}\n\n");

        $responseObj = json_decode($this->debug);
        recursive_columns_finder(json_decode($this->debug, true));
        echo "done with recursion";
        die();

        // KEYWORDS -------------------------------------------------------------------------------
        for($i = 0; $i < sizeof($responseObj->keywords); $i++) {
            //print("\n{$responseObj->keywords[$i]->text}");
            $sanitized = sanitizeText($db->conn,$responseObj->keywords[$i]->text);

            $sql = "INSERT INTO gatson_data_nlp_ibm_1.keywords (text, apiCallSignatureID) VALUES ('{$sanitized}','{$this->apiCallSignature}') ON DUPLICATE KEY UPDATE apiCallSignatureID = apiCallSignatureID";
            $execQuery = mysqli_query($db->conn,$sql);
            if($execQuery) {
            }else{
                error_log('IBMWatson.php - NLP->analyzeURL(): MySQL Query error on line:' . __LINE__ . ' - ' . mysqli_error($db->conn));
            }
        }
        //var_dump($responseObj->entities);
        //var_dump($responseObj);
        print("\n\n");

        // ENTITIES -------------------------------------------------------------------------------
        for($i = 0; $i < sizeof($responseObj->entities); $i++) {


            $sanitizedText = sanitizeText($db->conn,$responseObj->entities[$i]->text);
            $sanitizedType = sanitizeText($db->conn,$responseObj->entities[$i]->type);

            $cols = "text, apiCallSignatureID, type";
            $vals = "'{$sanitizedText}', '{$this->apiCallSignature}', '{$sanitizedType}'";

            try {
                $counter = 1;
                foreach($responseObj->entities[$i]->disambiguation->subtype as $subtype) {
                    $sanitizedSubtype = sanitizeText($db->conn,$subtype);
                    $cols .= ", subtype{$counter}";
                    $vals .= ", '{$sanitizedSubtype}'";
                    $counter++;
                }
            } catch (Exception $e) {
                // quiet
            }

            try {
                if (!is_null($responseObj->entities[$i]->disambiguation->dbpedia_resource)) {
                    $sanitizedDBPediaResource = sanitizeText($db->conn,$responseObj->entities[$i]->disambiguation->dbpedia_resource);
                    $cols .= ", dbpedia_resource";
                    $vals .= ", '{$sanitizedDBPediaResource}'";
                }
                if (!is_null($responseObj->entities[$i]->disambiguation->name)) {
                    $sanitizedName = sanitizeText($db->conn,$responseObj->entities[$i]->disambiguation->name);
                    $cols .= ", dbpedia_name";
                    $vals .= ", '{$sanitizedName}'";
                }
            } catch (Exception $e2) {

            }

            $colsFinal = " ($cols) ";
            $valsFinal = " ($vals) ";

            error_log("IBMWatson.php - NLP->analyzeURL(): cols data: " . $colsFinal);
            error_log("IBMWatson.php - NLP->analyzeURL(): vals data: " . $valsFinal);
            sleep(0.3);
            $sql = "INSERT INTO gatson_data_nlp_ibm_1.entities {$colsFinal} VALUES {$valsFinal} ON DUPLICATE KEY UPDATE apiCallSignatureID = apiCallSignatureID";
            $execQuery = mysqli_query($db->conn,$sql);
            sleep(0.7);
            if($execQuery) {

            }else{
                error_log('IBMWatson.php - NLP->analyzeURL(): MySQL Query error on line:' . __LINE__ . ' - ' . mysqli_error($db->conn) . $sql);
            }
        }


        return false;
    }

    public function diagnostics()
    {
        // @todo
    }
}

class Entity
{
    public $props;
    function __construct($data)
    {
        $this->props = $data;
    }
}