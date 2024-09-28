<?php

namespace Core;

use Core\Database;

class Api
{
    public static function db()
    {
        $db = (new Database())->pdo;
        return $db;
    }

    public static function users()
    {
        $sql   = "SELECT * FROM users ORDER BY id";
        $query = self::db()->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public static function checKey($key)
    {
        $sql = "SELECT * FROM api_keys WHERE api_key = :api_key";
        $query = self::db()->prepare($sql);
        $query->bindValue(':api_key', $key);
        $query->execute();
        $user = $query->fetch(\PDO::FETCH_ASSOC);
        return $user;
    }

    public static function getLimit($key)
    {
        $sql = "SELECT * FROM api_keys WHERE api_key = :api_key";
        $query = self::db()->prepare($sql);
        $query->bindValue(':api_key', $key);
        $query->execute();
        $user = $query->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            if($user['request_limit'] >= 1) {
                return $user['request_limit'];
            } else {
                self::eror("Limit Exceeded");
            }
        } else {
            self::eror("API Key Not Found.!");
        }
    }

    public static function cutLimit($key, $limit)
    {
        $newLimit = $limit - 1;

        $updateQuery = "UPDATE api_keys SET request_limit = :req_limit WHERE api_key = :api_key";
        $updateStmt  = self::db()->prepare($updateQuery);
        $updateStmt->bindValue(':req_limit', $newLimit);
        $updateStmt->bindValue(':api_key', $key);
        $updateStmt->execute();
    }

    public static function curlCall($src)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $src);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($ch, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)
        $infos = curl_exec($ch);
        $data = json_decode($infos);

        curl_close($ch);

        if($data) {
            return $data;
        } else {
            return "bad";
        }
    }

    public static function callOne($nid, $dob)
    {
        $data1 = self::curlCall("https://publicx.top/sv/sv.php?$nid&dob=$dob");
        $data2 = self::curlCall("https://tbbtech.xyz/fu/nai.php?nid=$nid&dob=$dob");

        if(!$data1 == "bad" AND !$data2 == "bad") {

            $data = $data1->data;

            $res['status']                = 'success';

            $res['data']['name']          = $data->name;
            $res['data']['nameEn']        = $data->nameEn;
            $res['data']['nationalId']    = $data->nationalId;
            $res['data']['pin']           = "";
        
            $res['data']['dateOfBirth']   = $data->dateOfBirth;
            $res['data']['gender']        = $data->gender;
            $res['data']['bloodGroup']    = $data->bloodGroup;
            $res['data']['occupation']    = "";
            $res['data']['spouse']        = "";
            $res['data']['religion']      = $data->religion;
            $res['data']['father']        = $data->father;
            $res['data']['mother']        = $data->mother;
            $res['data']['photo']         = $data->photo; //
            $res['data']['birthPlace']    = $data->permanentAddress->district;

            // Permanent Address
            $per = $data->permanentAddress;
            $res['data']['permanentAddress']['homeHolding']     = $per->homeHolding;
            $res['data']['permanentAddress']['villageOrRoad']   = $per->villageOrRoad;
            $res['data']['permanentAddress']['mouzaMoholla']    = $per->mouzaMoholla;
            $res['data']['permanentAddress']['postOffice']      = $per->postOffice;
            $res['data']['permanentAddress']['postalCode']      = $per->postalCode;
            $res['data']['permanentAddress']['upozila']         = $per->upozila;
            $res['data']['permanentAddress']['district']        = $per->district;
            $res['data']['permanentAddress']['division']        = $per->division;
            $res['data']['permanentAddress']['region']          = $per->region;
            // $res['data']['permanentAddress']['fullAddress']     = $per->fullAddress;

            // Present Address
            $pre = $data->presentAddress;
            $res['data']['presentAddress']['homeHolding']     = $pre->homeHolding;
            $res['data']['presentAddress']['villageOrRoad']   = $pre->villageOrRoad;
            $res['data']['presentAddress']['mouzaMoholla']    = $pre->mouzaMoholla;
            $res['data']['presentAddress']['postOffice']      = $pre->postOffice;
            $res['data']['presentAddress']['postalCode']      = $pre->postalCode;
            $res['data']['presentAddress']['upozila']         = $pre->upozila;
            $res['data']['presentAddress']['district']        = $pre->district;
            $res['data']['presentAddress']['division']        = $pre->division;
            $res['data']['presentAddress']['region']          = $pre->region;

            $res['data']['sl_no']         = $data->sl_no;
            $res['data']['voter_no']      = $data->voter_no;
            $res['data']['voterAreaCode'] = $data->voterAreaCode;

            $res['author']['name']  = "Jacky Twins";
            $res['author']['link']  = "https://t.me/jacky915";
        } else {
            $res['status'] = "failed";
        }

        return $res;
    }

    public static function eror($msg)
    {
        $data = [
            "Success" => "False", 
            "Message" => $msg, 
            "Owner"   => "Jacky_Twins"
        ];

        return die(json_encode($data));
    }
}

?>