<?php

declare(strict_types=1);

namespace App\Http;

class Request
{
    public array $parameters;
    public string $requestMethod;
    public string $contentType;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    }

    public function getBody(): array|string
    {
        if ($this->requestMethod !== 'POST') {
            return '';
        }
        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        error_log(print_r($_POST, true), 3, '/home/fkan/PhpstormProjects/Mappost/test.log');
        return $_POST;
    }

    public function getRequest(): array
    {
        if ($this->requestMethod !== 'POST') {
            return [];
        }

        if (strcasecmp($this->contentType, 'application/json') !== 0) {
            return [];
        }
        $postContent = trim(file_get_contents("php://input"));

        return json_decode($postContent);
    }
}
