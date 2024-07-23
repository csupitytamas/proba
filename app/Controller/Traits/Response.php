<?php

namespace App\Controller\Traits;

use Exception;

trait Response
{
    protected function getExceptionFormat($message): string
    {
        $data = [
            'status' => 'error',
            'message' => $message
        ];
        return $this->jsonResponse($data, 400);
    }

    /**
     * @param     $data
     * @param int $status
     *
     * @return false|string
     */
    protected function jsonResponse($data, int $status = 200): false|string
    {
        header('Content-Type: application/json');
        if ($status !== 200) {
            http_response_code($status);
        }
        return json_encode($data);
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function getPostCheck(): void
    {
        if (!isset($_POST)) {
            throw new Exception('POST method only allowed');
        }
    }
}