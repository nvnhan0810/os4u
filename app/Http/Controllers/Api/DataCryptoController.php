<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Http\Request;

class DataCryptoController extends Controller
{
    private $cipher = 'aes-128-cbc';

    public function decrypt(Request $request) {
        $request->validate([
            'data_decrypt' => 'required|string',
        ]);

        try {
            $result = $this->decryptData($request->data_decrypt);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (DecryptException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function encrypt(Request $request) {
        $request->validate([
            'data_encrypt' => 'required|string',
        ]);

        try {
            $result = $this->encryptData($request->data_encrypt);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch(EncryptExceptionn $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }


    private function encryptData(#[\SensitiveParameter] $value) {
        $iv = random_bytes(openssl_cipher_iv_length(strtolower($this->cipher)));

        $value = \openssl_encrypt(
            $value,
            strtolower($this->cipher),
            config('o4u.secret_key'),
            0,
            $iv,
            $tag
        );

        if ($value === false) {
            throw new EncryptException('Could not encrypt the data.');
        }

        $iv = base64_encode($iv);
        $tag = base64_encode($tag ?? '');

        $mac = $this->hash($iv, $value, config('o4u.secret_key'));

        $json = json_encode(compact('iv', 'value', 'mac', 'tag'), JSON_UNESCAPED_SLASHES);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new EncryptException('Could not encrypt the data.');
        }

        return base64_encode($json);
    }

    private function decryptData(string $payload) {
        $payload = $this->getJsonPayload($payload);

        $iv = base64_decode($payload['iv']);

        if (!empty($payload['tag'])) {
            throw new DecryptException('Could not decrypt the data.');
        }

        $validMac = hash_equals(
            $this->hash($payload['iv'], $payload['value'], config('o4u.secret_key')),
            $payload['mac']
        );

        if (!$validMac) {
            throw new DecryptException('The MAC is invalid.');
        }


        $decrypted = \openssl_decrypt(
            $payload['value'],
            strtolower($this->cipher),
            config('o4u.secret_key'),
            0,
            $iv,
            $tag ?? ''
        );

        if (($decrypted ?? false) === false) {
            throw new DecryptException('Could not decrypt the data.');
        }

        return $decrypted;
    }

    /**
     * Get the JSON array from the given payload.
     *
     * @param  string  $payload
     * @return array
     *
     * @throws \Illuminate\Contracts\Encryption\DecryptException
     */
    protected function getJsonPayload($payload)
    {
        if (! is_string($payload)) {
            throw new DecryptException('The payload is invalid.');
        }

        $payload = json_decode(base64_decode($payload), true);

        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (! $this->validPayload($payload)) {
            throw new DecryptException('The payload is invalid.');
        }

        return $payload;
    }

    /**
     * Verify that the encryption payload is valid.
     *
     * @param  mixed  $payload
     * @return bool
     */
    protected function validPayload($payload)
    {
        if (! is_array($payload)) {
            return false;
        }

        foreach (['iv', 'value', 'mac'] as $item) {
            if (! isset($payload[$item]) || ! is_string($payload[$item])) {
                return false;
            }
        }

        if (isset($payload['tag']) && ! is_string($payload['tag'])) {
            return false;
        }

        return strlen(base64_decode($payload['iv'], true)) === openssl_cipher_iv_length(strtolower($this->cipher));
    }

    /**
     * Create a MAC for the given value.
     *
     * @param  string  $iv
     * @param  mixed  $value
     * @param  string  $key
     * @return string
     */
    protected function hash(#[\SensitiveParameter] $iv, #[\SensitiveParameter] $value, #[\SensitiveParameter] $key)
    {
        return hash_hmac('sha256', $iv . $value, $key);
    }
}
