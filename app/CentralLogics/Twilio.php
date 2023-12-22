<?php

namespace App\CentralLogics;

use Twilio\Rest\Client;


class Twilio
{
    public $sid;
    public $token;
    public $client;
    public $number;
    public function __construct()
    {
        $this->token = env('TWILIO_TOKEN') ?? '42c90a18cacc36c865f4bb11d70fd87a';
        $this->sid =  env('TWILIO_SID') ?? 'AC6a96436ba82d4a587adecdd137d55a9a';
        $this->number = env('TWILIO_PHONE') ?? '(562) 354-4249';
        $this->client = new Client($this->sid, $this->token);
    }

    public function sendOTP($phone, $body)
    {
        try {
            $this->client->messages->create(
                $phone,
                array(
                    'from' => $this->number,
                    'body' => $body
                )
            );
            return true;
        } catch (\Exception $error) {
            return false;
        }
    }
}
