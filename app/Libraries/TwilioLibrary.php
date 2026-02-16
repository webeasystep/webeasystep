<?php

namespace App\Libraries;

use Twilio\Rest\Client;

class TwilioLibrary
{
    protected Client $client;
    protected string $from;

    public function __construct()
    {
        $sid        = getenv('TWILIO_SID');
        $token      = getenv('TWILIO_AUTH_TOKEN');
        $this->from = getenv('TWILIO_WHATSAPP_FROM'); // e.g. "whatsapp:+1234567890"
        $this->client = new Client($sid, $token);
    }

    /**
     * Send a WhatsApp message using either:
     * - A plain text message, OR
     * - A Twilio template with placeholders.
     *
     * @param string      $to                Recipient phone in E.164 format WITHOUT plus sign (e.g. "964780xxxxxx").
     * @param string|null $plainTextMessage  If provided, sends as plain text.
     * @param string|null $templateId        If provided, uses a Twilio template with placeholders.
     * @param array       $templateVariables Key-value pairs for template placeholders.
     *
     * @return bool True if sent successfully, false on error.
     */
    public function sendWhatsAppMessage(
        string $to,
        ?string $plainTextMessage = null,
        ?string $templateId = null,
        array $templateVariables = []
    ): bool {
        try {
            // Remove leading zero from phone number
            $to = ltrim($to, '0');  // This removes ALL leading zeros. If you want to remove only the first zero, use: $to = (strlen($to) > 0 && $to[0] === '0') ? substr($to, 1) : $to;
            // Build the common payload
            $payload = [
                'from' => $this->from,
                'to'   => "whatsapp:+964{$to}", // Twilio requires "whatsapp:+CountryCodeNumber"
            ];

            // 1) If a template ID is provided, use contentSid approach
            if ($templateId && !empty($templateVariables)) {
                $payload['contentSid']       = $templateId;
                $payload['contentVariables'] = json_encode($templateVariables);

                // 2) If plain text message provided, send directly
            } elseif ($plainTextMessage) {
                $payload['body'] = $plainTextMessage;
            } else {
                throw new \InvalidArgumentException(
                    'Either a plain text message or a templateId with variables must be provided.'
                );
            }

            // Send the message via Twilio
            $this->client->messages->create($payload['to'], $payload);
            return true;

        } catch (\Exception $e) {
            log_message('error', 'Twilio Error: ' . $e->getMessage());
            return false;
        }
    }
}
