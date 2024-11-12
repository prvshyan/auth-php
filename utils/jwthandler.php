<?php
class JWTHandler {
    private $secret_key = "your_secret_key_here";

    public function generateToken($user_id, $email) {
        $header = $this->generateHeader();
        $payload = $this->generatePayload($user_id, $email);
        $signature = $this->generateSignature($header, $payload);

        return $header . "." . $payload . "." . $signature;
    }
    private function generateHeader() {
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);
        return $this->base64UrlEncode($header);
    }
    private function generatePayload($user_id, $email) {
        $payload = json_encode([
            'user_id' => $user_id,
            'email' => $email,
            'iat' => time(),
            'exp' => time() + (60 * 60)
        ]);
        return $this->base64UrlEncode($payload);
    }
    private function generateSignature($header, $payload) {
        $signature = hash_hmac('sha256',
            $header . "." . $payload,
            $this->secret_key,
            true
        );
        return $this->base64UrlEncode($signature);
    }
    private function base64UrlEncode($data) {
        return str_replace(['+', '/', '='],
            ['-', '_', ''],
            base64_encode($data)
        );
    }
}