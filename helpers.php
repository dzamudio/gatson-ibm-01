<?php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);  // 7 day cookie lifetime
session_start();
$_SESSION['TEST'] = "LOL TEST";

error_log(" ");
error_log(" ");
error_log(" ");

error_log("START EXECUTION MEMORY");
error_log(" " . (memory_get_usage()/1024) . "kb");
error_log(" " . (memory_get_usage()/1048576) . "mb");

function sanitizeText($dbConn, $string)
{
    $singleQuotesCleanString = str_replace(["’","‘"],"'",$string);
    $doubleQuotesCleanString = str_replace(["“","”"],'"',$singleQuotesCleanString);
    $sanitized = mysqli_real_escape_string($dbConn,$doubleQuotesCleanString);
    return $sanitized;
}

// check if this function exists in PHP version
if( !function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle){
        $checkVal = strpos($haystack, $needle, 0);
        if($checkVal === 0) { // str does start with
            return true;
        }else{
            return false;
        }
    }
}


$simpleArray = array(
    'food'=>array(
        'veg'=>array('carrot','celery'),
        'fruit'=>array('apple'),
        'fast'=>array('pizza','sandwich'),
        'testIntegers1' =>array("3",'5',19,1.1,0.01,.2)
    ),
    'desk'=>array(
        'writing'=>array('pen','pencil','marker'),
        'stationary'=>array('notebook','stickynotes')
    ),
    4.5
);

function gGettype($val){
    $acceptableTypes = array("integer","double", "float", "string");
    $valType = gettype($val);

    if ( is_numeric($val) ){ // will be true if number or numeric string
        $newVal = $val + 0;
        $valType = gettype($newVal);
    }

    if ( !in_array($valType, $acceptableTypes) ) {
        error_log("------------UNACCEPTABLE TYPE ({$valType}) FOR: " . $val);
        return "NA";
    } else {
        return $valType;
    }
}

function lazyIteration(){

    global $simpleArray;

    //$sampleJSON = file_get_contents("6390224b9c11a720fa4508dd608b5f7e.json"); 5a5a3332f508cafcddbc6715104162a8.json
    $sampleJSON = file_get_contents("6390224b9c11a720fa4508dd608b5f7e.json");
    $sampleJSON_encoded = json_decode($sampleJSON, true); // compatible with what the api call receives in IBMWatson.php
    //$sampleJSON_encoded = json_decode(json_encode($simpleArray), true);

    $printFlag1 = false;


    $arrayIndexes = array();
    $arrayIndexesLex = array();
    $arrayPathsToValues = array();
    $arrayValueTypes = array();
    $arrayIndexedValues = array();
    // $arrayIndexesLex[] = "{$ssss}.{$ssss}.{$ssss}.{$ssss}.{$ssss}.{$ssss}.{$ssss}.{$ssss}.{$ssss}.{$ssss}";

    // 0 LEVEL
    foreach( $sampleJSON_encoded as $L0_key => $L0_val ){

        // @todo , clean this up a bit so it's not so repetitive
        // make a function that accepts the repetitive stuff
        // for example function  ($levelNum, $key, $val, $currTree)
        // keep debugging compare api calls and compare paths.
        // print every property (if the string is less than 15 characters


        // 0? (5:56am) using 0.1,0.5
        // 0? (5:59am) using 0.5,0.8
        // 0? (6:00am) using 0.5,0.9
        // 0.8,0.9
        // 48 (6:05am) using (0.1,1)
        // 51 (5:55am) using 0.5,1
        // 150 (5:45am) using 1,2
        // 140 (5:47am) using 1,2
        // 231 (5:53am) using 1,4

        // $printFlag1 prints the current level the loop is in...
        // $printFlag2

        if ( is_array($L0_val) ){
            if ( $printFlag1 ) { print("\n1-{$L0_key}"); }
            $arrayIndexes[$L0_key] = array();
            //$arrayIndexesLex[] = "{$L0_key}"; // removing for now-test1
            foreach( $L0_val as $L1_key => $L1_val ){
                if ( is_array($L1_val) ){
                    if ( $printFlag1 ) { print("\n2- -{$L1_key}"); }
                    $arrayIndexes[$L0_key][$L1_key] = array();
                    //$arrayIndexesLex[] = "{$L0_key}.{$L1_key}"; // removing for now-test1
                    foreach( $L1_val as $L2_key => $L2_val ){
                        if ( is_array($L2_val) ){
                            if ( $printFlag1 ) { print("\n3- - -{$L2_key}"); }
                            $arrayIndexes[$L0_key][$L1_key][$L2_key] = array();
                            //$arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}"; // removing for now-test1
                            foreach( $L2_val as $L3_key => $L3_val ){
                                if ( is_array($L3_val) ){
                                    if ( $printFlag1 ) { print("\n4- - - -{$L3_key}"); }
                                    $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key] = array();
                                    //$arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}"; // removing for now-test1
                                    foreach( $L3_val as $L4_key => $L4_val ){
                                        if ( is_array($L4_val) ){
                                            if ( $printFlag1 ) { print("\n5- - - - -{$L4_key}"); }
                                            $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key] = array();
                                            //$arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}"; // removing for now-test1
                                            foreach( $L4_val as $L5_key => $L5_val ){
                                                if ( is_array($L5_val) ){
                                                    if ( $printFlag1 ) { print("\n6- - - - - -{$L5_key}"); }
                                                    $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key] = array();
                                                    //$arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}"; // removing for now-test1
                                                    foreach( $L5_val as $L6_key => $L6_val ){
                                                        if ( is_array($L6_val) ){
                                                            if ( $printFlag1 ) { print("\n7- - - - - - -{$L6_key}"); }
                                                            $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key][$L6_key] = array();
                                                            //$arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}"; // removing for now-test1
                                                            foreach( $L6_val as $L7_key => $L7_val ){
                                                                if ( is_array($L7_val) ){
                                                                    if ( $printFlag1 ) { print("\n8- - - - - - - -{$L7_key}"); }
                                                                    $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key][$L6_key][$L7_key] = array();
                                                                    //$arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$L7_key}"; // removing for now-test1
                                                                    foreach( $L7_val as $L8_key => $L8_val ){
                                                                        if ( is_array($L8_val) ){
                                                                            if ( $printFlag1 ) { print("\n9- - - - - - - - -{$L8_key}"); }
                                                                            $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key][$L6_key][$L7_key][$L8_key] = array();
                                                                            //$arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$L7_key}.{$L8_key}"; // removing for now-test1
                                                                            foreach( $L8_val as $L9_key => $L9_val ){
                                                                                if ( is_array($L9_val) ){
                                                                                    if ( $printFlag1 ) { print("\n10- - - - - - - - - -{$L9_key}"); }
                                                                                    $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key][$L6_key][$L7_key][$L8_key][$L9_key] = array();
                                                                                    //$arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$L7_key}.{$L8_key}.{$L9_key}"; // removing for now-test1
                                                                                }else{
                                                                                    // not array, but still a key
                                                                                    if ( $printFlag1 ) { print("\n10- - - - - - - - - -{$L9_key}"); }
                                                                                    $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key][$L6_key][$L7_key][$L8_key][$L9_key] = array();
                                                                                    $arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$L7_key}.{$L8_key}.{$L9_key}";
                                                                                    $trimmedVal = (gettype($L9_val) == 'string') ? substr($L9_val,0,15) : $L9_val;
                                                                                    $arrayPathsToValues[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$L7_key}.{$L8_key}.{$L9_key}.{$trimmedVal}";
                                                                                    $arrayValueTypes[] = gGettype($L9_val);
                                                                                    $arrayIndexedValues[] = $L9_val;
                                                                                }
                                                                            }
                                                                        }else{
                                                                            // not array, but still a key
                                                                            if ( $printFlag1 ) { print("\n9- - - - - - - - -{$L8_key}"); }
                                                                            $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key][$L6_key][$L7_key][$L8_key] = array();
                                                                            $arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$L7_key}.{$L8_key}";
                                                                            $trimmedVal = (gettype($L8_val) == 'string') ? substr($L8_val,0,15) : $L8_val;
                                                                            $arrayPathsToValues[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$L7_key}.{$L8_key}.{$trimmedVal}";
                                                                            $arrayValueTypes[] = gGettype($L8_val);
                                                                            $arrayIndexedValues[] = $L8_val;
                                                                        }
                                                                    }
                                                                }else{
                                                                    // not array, but still a key
                                                                    if ( $printFlag1 ) { print("\n8- - - - - - - -{$L7_key}"); }
                                                                    $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key][$L6_key][$L7_key] = array();
                                                                    $arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$L7_key}";
                                                                    $trimmedVal = (gettype($L7_val) == 'string') ? substr($L7_val,0,15) : $L7_val;
                                                                    $arrayPathsToValues[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$L7_key}.{$trimmedVal}";
                                                                    $arrayValueTypes[] = gGettype($L7_val);
                                                                    $arrayIndexedValues[] = $L7_val;
                                                                }
                                                            }
                                                        }else{
                                                            // not array, but still a key
                                                            if ( $printFlag1 ) { print("\n7- - - - - - -{$L6_key}"); }
                                                            $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key][$L6_key] = array();
                                                            $arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}";
                                                            $trimmedVal = (gettype($L6_val) == 'string') ? substr($L6_val,0,15) : $L6_val;
                                                            $arrayPathsToValues[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$L6_key}.{$trimmedVal}";
                                                            $arrayValueTypes[] = gGettype($L6_val);
                                                            $arrayIndexedValues[] = $L6_val;
                                                        }
                                                    }
                                                }else{
                                                    // not array, but still a key
                                                    if ( $printFlag1 ) { print("\n6- - - - - -{$L5_key}"); }
                                                    $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key][$L5_key] = array();
                                                    $arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}";
                                                    $trimmedVal = (gettype($L5_val) == 'string') ? substr($L5_val,0,15) : $L5_val;
                                                    $arrayPathsToValues[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$L5_key}.{$trimmedVal}";
                                                    $arrayValueTypes[] = gGettype($L5_val);
                                                    $arrayIndexedValues[] = $L5_val;
                                                }
                                            }
                                        }else{
                                            // not array, but still a key
                                            if ( $printFlag1 ) { print("\n5- - - - -{$L4_key}"); }
                                            $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key][$L4_key] = array();
                                            $arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}";
                                            $trimmedVal = (gettype($L4_val) == 'string') ? substr($L4_val,0,15) : $L4_val;
                                            $arrayPathsToValues[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$L4_key}.{$trimmedVal}";
                                            $arrayValueTypes[] = gGettype($L4_val);
                                            $arrayIndexedValues[] = $L4_val;
                                        }
                                    }
                                }else{
                                    // not array, but still a key
                                    if ( $printFlag1 ) { print("\n4- - - -{$L3_key}"); }
                                    $arrayIndexes[$L0_key][$L1_key][$L2_key][$L3_key] = array();
                                    $arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}";
                                    $trimmedVal = (gettype($L3_val) == 'string') ? substr($L3_val,0,15) : $L3_val;
                                    $arrayPathsToValues[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$L3_key}.{$trimmedVal}";
                                    $arrayValueTypes[] = gGettype($L3_val);
                                    $arrayIndexedValues[] = $L3_val;
                                }
                            }
                        }else{
                            // not array, but still a key
                            if ( $printFlag1 ) { print("\n3- - -{$L2_key} = ".gettype($L2_val)); }
                            $arrayIndexes[$L0_key][$L1_key][$L2_key] = array();
                            $arrayIndexesLex[] = "{$L0_key}.{$L1_key}.{$L2_key}";
                            $trimmedVal = (gettype($L2_val) == 'string') ? substr($L2_val,0,15) : $L2_val;
                            $arrayPathsToValues[] = "{$L0_key}.{$L1_key}.{$L2_key}.{$trimmedVal}";
                            $arrayValueTypes[] = gGettype($L2_val);
                            $arrayIndexedValues[] = $L2_val;
                        }
                    }
                }else{
                    // not array, but still a key
                    if ( $printFlag1 ) { print("\n2- -{$L1_key}"); }
                    $arrayIndexes[$L0_key][$L1_key] = array();
                    $arrayIndexesLex[] = "{$L0_key}.{$L1_key}";
                    $trimmedVal = (gettype($L1_val) == 'string') ? substr($L1_val,0,15) : $L1_val;
                    $arrayPathsToValues[] = "{$L0_key}.{$L1_key}.{$trimmedVal}";
                    $arrayValueTypes[] = gGettype($L1_val);
                    $arrayIndexedValues[] = $L1_val;
                }
            }
        }else{
            // not array, but still a key
            if ( $printFlag1 ) { print("\n1-{$L0_key}"); }
            $arrayIndexes[$L0_key] = array();
            $arrayIndexesLex[] = "{$L0_key}";
            $trimmedVal = (gettype($L0_val) == 'string') ? substr($L0_val,0,15) : $L0_val;
            $arrayPathsToValues[] = "{$L0_key}.{$trimmedVal}";
            $arrayValueTypes[] = gGettype($L0_val);
            $arrayIndexedValues[] = $L0_val;
        }

/*
        if( is_array($L0_val) ){ // if( is_array($L0_val) ){}
            // 1 LEVEL
            foreach( $arrayLevel_1 as $L1_key => $L1_val ){

                if( is_array($L1_val) ){
                    // 2 LEVEL
                    foreach( $arrayLevel_2 as $L2_key => $L2_val ){

                        // 3 LEVEL
                        foreach( $arrayLevel_3 as $L3_key => $L3_val ){

                            // 4 LEVEL
                            foreach( $arrayLevel_4 as $L4_key => $L4_val ){

                                // 5 LEVEL
                                foreach( $arrayLevel_5 as $L5_key => $L5_val ){

                                    // 6 LEVEL
                                    foreach( $arrayLevel_6 as $L6_key => $L6_val ){

                                    }// 6 END

                                }// 5 END

                            }// 4 END

                        }// 3 END

                    }// 2 END

                }

            }// 1 END

        }
        */

    }// 0 END

    $_SESSION['AVT1'] = $arrayValueTypes;
    $_SESSION['AIV1'] = $arrayIndexedValues;
    $_SESSION['AP2V'] = $arrayPathsToValues;
    $_SESSION['AIL'] = $arrayIndexesLex;

    $arrayIndexesNumStripped = array();
    foreach($_SESSION['AIL'] as $path){
        $arrayIndexesNumStripped[] = preg_replace('/[0-9]{1,4}/', 'i', $path);
    }

    $_SESSION['AINS'] = $arrayIndexesNumStripped;

    $debugFile_handle = fopen('debug2.txt','a');
    fwrite($debugFile_handle,"test:".time()."\n".json_encode($arrayIndexes)."\n\n");
    fclose($debugFile_handle);

    $printFlag2 = true;
    $printFlag3 = true;
    $printFlag4 = true;

    if ( $printFlag2 ) {

        if ( $printFlag4 ){
            print("\n\n");
            print("Tree: ".sizeof($arrayIndexes) . "\n");
            print("\n------------------------------------------------\n");
            print_r($arrayIndexes);
        }

        print("\n\n");
        print("Data Points: ".sizeof($arrayPathsToValues) . "\n");
        print("\n------------------------------------------------\n");
        if ( $printFlag3 ){
            print("\n\n");
            print("Data Value Types (indexed): ".sizeof($arrayValueTypes) . "\n");
            foreach( $arrayValueTypes as $index => $type ){
                print("\n{$index} - {$type} - {$arrayIndexedValues[$index]}");
            }
            print("\n------------------------------------------------\n");
        }

    }

    if ( $printFlag2 ) {
        print("\n\n");
        print("Path Frequency: \n");
        print("\n------------------------------------------------\n");
    }
    $array_paths_pass_1 = array();
    foreach($arrayIndexesLex as $path){
        $array_paths_pass_1[] = preg_replace('/[0-9]{1,4}/', 'i', $path);
    }
    $_SESSION['APP1'] = $array_paths_pass_1;
    $newArray = array();
    foreach ( $_SESSION['APP1'] as $indexKey => $indexDotDelimitedPath ){
        $newArray[$indexDotDelimitedPath] = $_SESSION['AVT1'][$indexKey];
    }
    $_SESSION['APP1_AVT1'] = $newArray;

    if ( $printFlag2 ) {

        $acv_app1 = array_count_values($array_paths_pass_1);
        $acv_app2 = array();
        foreach( $acv_app1 as $path => $freq ){
            print("\n{$freq} - {$path}");
//            if( in_array($path,$arrayHaystack) ){
//
//            }
        }
        $_SESSION['ACV1'] = $acv_app1;
    }

    //var_dump($arrayIndexesLex);  array_count_values
    $uniqueList = array_unique($arrayIndexesLex);

    $newList = array();
    foreach($uniqueList as $path){
        $newList[] = preg_replace('/[0-9]{1,4}/', 'i', $path);
    }
    $superUnique = array_unique($newList);
    $superUniqueReIndex = array_merge($superUnique); // this will be used to create the schemas dynamically

    if ( $printFlag2 ) {
        print("\n\n");
        print("Data XPaths (u): ".sizeof($superUniqueReIndex) . "\n");
        foreach( $superUniqueReIndex as $path ){
            print("\n{$path}");
        }
        print("\n------------------------------------------------\n");
    }


    if ( $printFlag2 ) {
        $totalProps = sizeof($arrayPathsToValues);
        $counter = 1;
        $startTime = time();
        if ($printFlag3 && $printFlag4) {
            print("\n\n-----------------------------VALUES PATHS Rev(1)-----------------------------\n\n");

            foreach ($arrayPathsToValues as $val) {
                $timings = array(10000, 100000, 10000);

                $randTime = mt_rand(0, 2);

                if (!strpos($val, "syntax.tokens") && !strpos($val, "syntax.sentences")) {
                    // do nothing, but this is an example of excluding
                }
                include("liveIncludeTest.php");
                //error_log($randTime);
                usleep($timings[$randTime]); // wont work with decimals?
                print(time() . " - [{$counter}/{$totalProps}] - $val\n");
                $counter++;
            }
            print("\n\n\n--------------------------------------------");
            print("\n PROCESS TIME: " . (time() - $startTime));
        }
        //var_dump($superUniqueReIndex);
    }
    error_log(" - - END lazyIteration() EXECUTION MEMORY");
    error_log(" - -  - - " . (memory_get_usage()/1024) . "kb");
    error_log(" - -  - - " . (memory_get_usage()/1048576) . "mb");
}

function run(){
    global $simpleArray;
    print("\n\n\n-------------------------------------------\n");
    print("--------Running lazyIteration()-------\n");
    print("-------------------------------------------\n\n\n");
    sleep(3);
    lazyIteration();
    // iterate over entities
    /*
            $_SESSION['APP1']
                [4701] => categories.i.label

            $_SESSION['AVT1']
                [4701] => string

            $_SESSION['AIV1']
                [4701] => /health and fitness/disease/cancer/brain tumor

    */

    $entitiesArr = array();
    $schemaTest1 = array();
    foreach ( $_SESSION['APP1'] as $index => $pathsAsi ){
        if (str_starts_with($pathsAsi, "entities.i")) {
            $columnName = str_replace("entities.i.","ent_",$pathsAsi);
            $finalColumnName = str_replace(".","_",$columnName);
            $columnDataType = $_SESSION['AVT1'][$index];
            $columnDataTypeDefault = " varchar(150) ";
            switch( $columnDataType ){
                case 'string':
                    $columnDataTypeDefault = " varchar(150) ";
                    break;
                case 'integer':
                    $columnDataTypeDefault = " int(11) ";
                    break;
                case 'double':
                    $columnDataTypeDefault = " double(9,8) ";
                    break;
                default:
                    $columnDataTypeDefault = " varchar(150) ";
            }
            $schemaTest1[$finalColumnName] = $columnDataTypeDefault;
        }
    }
    $_SESSION['a2'] = $schemaTest1;
    $schemaQuery = "CREATE TABLE `ibm_entities` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `maintenance` varchar(100) DEFAULT NULL,
                  `apiCallSignatureID` varchar(50) DEFAULT NULL,";
    foreach($_SESSION['a2'] as $colName => $type){
        $schemaQuery .= "`{$colName}` {$type} default null,";
    }
    $schemaQuery .= "  PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $_SESSION['a3'] = $schemaQuery;
    error_log(" - END run() EXECUTION MEMORY");
    error_log(" -  - " . (memory_get_usage()/1024) . "kb");
    error_log(" -  - " . (memory_get_usage()/1048576) . "mb");
}

run();


error_log("END EXECUTION MEMORY");
error_log(" " . (memory_get_usage()/1024) . "kb");
error_log(" " . (memory_get_usage()/1048576) . "mb");

die();
