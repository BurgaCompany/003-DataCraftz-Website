<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class HttpResponseFormatter
{
  private $statusCode;
  private $headers;
  private $metadata;
  private $result;

  public function __construct()
  {
    $this->statusCode = 200;
    $this->headers = [];
    $this->metadata = [
      'success' => true,
      'message' => '',
    ];
    $this->result = null;
  }

  public function setStatusCode($code)
  {
    $this->statusCode = $code;
    $this->metadata['success'] = ($code >= 200 && $code < 300);
    return $this;
  }

  public function addHeader($header, $value)
  {
    $this->headers[$header] = $value;
    return $this;
  }

  public function setMessage($message)
  {
    $this->metadata['message'] = $message;
    return $this;
  }

  public function setResult($data)
  {
    $this->result = $data;
    return $this;
  }

  public function format()
  {
    $response = [
      'metadata' => $this->metadata,
      'result' => $this->result,
    ];

    return response()->json($response, $this->statusCode)->withHeaders($this->headers);
  }
}
