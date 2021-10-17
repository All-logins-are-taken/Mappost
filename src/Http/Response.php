<?php

declare(strict_types=1);

namespace App\Http;

class Response
{
    private int $pageStatus = 200;

    public function pageStatus(int $pageStatus): static
    {
        $this->pageStatus = $pageStatus;

        return $this;
    }

    public function getResponse($data): void
    {
        http_response_code($this->pageStatus);
        header('Content-Type: text/html; charset=utf-8');
        print_r($data);
    }
}
