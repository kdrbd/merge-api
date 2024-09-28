<?php
    
    use Core\Api;

    require __DIR__.'/../vendor/autoload.php';
    require __DIR__.'/aconfig.php';

    if (isset($_GET['key']) AND !$_GET['key'] == "") {
        $key = $_GET['key'];
    } elseif (isset($_GET['key']) AND $_GET['key'] == "") {
        Api::eror("Please Enter API Key Value!");
    } elseif (!isset($_GET['key'])) {
        Api::eror("Please Sent Key and Value!");
    }

    if (isset($_GET['nid']) AND !$_GET['nid'] == "") {
        $nid = $_GET['nid'];
    } elseif (isset($_GET['nid']) AND $_GET['nid'] == "") {
        Api::eror("Please Enter NID!");
    } elseif (!isset($_GET['nid'])) {
        Api::eror("Please Sent NID!");
    }

    if (isset($_GET['dob']) AND !$_GET['dob'] == "") {
        $dob = $_GET['dob'];
    } elseif (isset($_GET['dob']) AND $_GET['dob'] == "") {
        Api::eror("Please Enter DOB!");
    } elseif (!isset($_GET['dob'])) {
        Api::eror("Please Sent DOB!");
    }

    if (isset($key) AND isset($nid) AND isset($dob)) {

        $limit = Api::getLimit($key);

        if($limit) {

            $data1 = Api::callOne($nid, $dob);

            dd($data1);

            if($data1['status'] == 'success') {

                Api::cutLimit($key, $limit);
                echo json_encode($data1, JSON_UNESCAPED_UNICODE);
            } else {

                // $data2 = "";

                // if("") {
                //     //
                // } 
            }

            // echo "You Have Limit = " . $limit;
        }

    } else {
        Api::eror("Check inputs and Try Again Later!");
    }

?>