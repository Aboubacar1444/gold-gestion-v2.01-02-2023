<?php

namespace App\Service;

use UltraMsg\WhatsAppApi;

class WhatsAppService
{
    private ?String $token = "omra640n4jnjan4yhhh" ;
    private ?String $instance_id = "instance41654";



    public function sendMessage (String $to, String $body, string $referenceId = ""){
        $client = new WhatsAppApi($this->token, $this->instance_id);
        $messageBody = $body;
        return $client->sendChatMessage($to, $messageBody, 5, $referenceId);
    }







}
