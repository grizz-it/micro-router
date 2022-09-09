<?php

use Symfony\Component\HttpFoundation\Response;

if (!function_exists('json_response')) {
    /**
     * Create a JSON response.
     *
     * @param string $content
     * @param int $code
     * @param array $additionalHeaders
     *
     * @return Response
     */
    function json_response(
        mixed $content,
        int $code = 200,
        array $additionalHeaders = []
    ): Response {
        return new Response(
            is_string($content) ? $content : json_encode($content),
            $code,
            array_merge(['Content-Type' => 'application/json'], $additionalHeaders)
        );
    }
}