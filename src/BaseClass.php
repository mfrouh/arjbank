<?php

namespace MFrouh\ArjBank;

class BaseClass
{
    public function Payment(array $paymentData, int $amount, string $response_url, string $error_url)
    {
        $trackId = rand(111111111, 999999999);

        $data = [
            "id" => config('ArjBank.transportal_id'),
            "password" => config('ArjBank.transportal_password'),
            "action" => "1",
            "currencyCode" => "682",
            "responseURL" => $response_url,
            "errorURL" => $error_url,
            "trackId" => $trackId,
            "amt" => $amount,
        ] + $paymentData;

        $encoded_data = json_encode($data, JSON_UNESCAPED_SLASHES);

        $encryptedData = [
            "id" => config('ArjBank.transportal_id'),
            "trandata" => $this->encryption($encoded_data, config('ArjBank.resource_key')),
            "responseURL" => $response_url,
            "errorURL" => $error_url,
        ];

        $encodedData = json_encode($encryptedData, JSON_UNESCAPED_SLASHES);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => config('ArjBank.mode') == 'live' ? config('ArjBank.live_url') : config('ArjBank.test_url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $encodedData,

            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Accept-Language: application/json',
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response_data = json_decode($response, true)[0];

        if ($response_data["status"] == "1") {
            $url = "https:" . explode(":", $response_data["result"])[2];
            return ["status" => 'success', "url" => $url];
        } else {
            return ["status" => 'fail', "message" => $response_data["result"]];
        }
    }

    public function result($data)
    {
        $decrypted = $this->decryption($data, config('ArjBank.resource_key'));
        $raw = urldecode($decrypted);
        $dataArr = json_decode($raw, true);
        if (isset($dataArr[0]['errorText'])) {
            return ["status" => 400, 'data' => $dataArr[0]];
        }
        $paymentStatus = $dataArr[0]["result"];
        if (isset($paymentStatus) && $paymentStatus === 'CAPTURED') {
            return ["status" => 200, 'data' => $dataArr[0]];
        }
        return ["status" => 400, 'data' => $dataArr[0]];
    }

    private function encryption($str, $key)
    {
        $blocksize = openssl_cipher_iv_length("AES-256-CBC");
        $pad = $blocksize - (strlen($str) % $blocksize);
        $str = $str . str_repeat(chr($pad), $pad);
        $encrypted = openssl_encrypt($str, "AES-256-CBC", $key, OPENSSL_ZERO_PADDING, "PGKEYENCDECIVSPC");
        $encrypted = base64_decode($encrypted);
        $encrypted = unpack('C*', ($encrypted));
        $chars = array_map("chr", $encrypted);
        $bin = join($chars);
        $encrypted = bin2hex($bin);
        $encrypted = urlencode($encrypted);
        return $encrypted;
    }

    private function decryption($code, $key)
    {
        $string = hex2bin(trim($code));
        $code = unpack('C*', $string);
        $chars = array_map("chr", $code);
        $code = join($chars);
        $code = base64_encode($code);
        $decrypted = openssl_decrypt($code, "AES-256-CBC", $key, OPENSSL_ZERO_PADDING, "PGKEYENCDECIVSPC");
        $pad = ord($decrypted[strlen($decrypted) - 1]);
        if ($pad > strlen($decrypted)) {
            return false;
        }
        if (strspn($decrypted, chr($pad), strlen($decrypted) - $pad) != $pad) {
            return false;
        }
        return urldecode(substr($decrypted, 0, -1 * $pad));
    }
}
