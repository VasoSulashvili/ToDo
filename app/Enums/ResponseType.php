<?php

namespace App\Enums;

enum ResponseType
{
    case SUCCESS;
    case CREATED;
    case ERROR;


    public function response($data = null, $status = null, $message = null)
    {
        return match ($this) {
            ResponseType::CREATED => response()->json([
                'type' => 'success',
                'message' => $message ? $message : 'Record created successfully!',
                'data' => $data,
            ], $status ? (int) $status : 201),
            ResponseType::SUCCESS => response()->json([
                'type' => 'success',
                'message' => $message ? $message : 'Operation was successfully!',
                'data' => $data,
            ],$status ? (int) $status : 200),
            ResponseType::ERROR => response()->json([
                'type' => 'error',
                'message' => $message ? $message : 'Operation was unsuccessfully!',
                'data' => null,
            ],$status ? (int) $status : 403)
        };
    }
}
