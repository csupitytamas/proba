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
        return $this->jsonResponse($data);
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
        // TODO add bad request header status code
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