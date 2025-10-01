<?php

namespace App\Model;

class DtoResponse{

    private string $success;
    private string $message;
    private mixed $data;

    public function __construct(string $success = '', string $message = '', mixed $data = null){
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }

    public function setSuccess(string $success) : static
    {
        $this->success = $success;
        return $this;
    }

    public function getSuccess(): string
    {
        return $this->success;
    }

      public function setMessage(string $message) : static
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

      public function setData(string $data) : static
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}