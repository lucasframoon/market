<?php

namespace Src\Controller;

use DateTime;
use Src\Exception\ApiException;

class AbstractController
{

    /**
     * Validate data based on given rules
     *
     * @param array|null $data if null, $_POST will be used
     * @param array $rules
     * @return array
     * @throws ApiException
     * @example validatePostData( $data,['name' => ['type' => 'string', 'required' => true]])
     */
    protected function validateInput(?array $data, array $rules): array
    {
        $validatedData = [];
        if (is_null($data)) {
            $data = $_POST;
        }

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;

            // Check if the value is required and missing
            if (!isset($value)) {
                if (isset($rule['required']) && $rule['required']) {
                    throw new ApiException("Parametro '" . $field . "' é obrigatório", 400);
                }
                $validatedData[$field] = null;
                continue;
            }

            // Check if is possible to convert the value to the expected type 
            if (!isset($rule['type'])) {
                $rule['type'] = 'string';
            }
            switch ($rule['type']) {
                case 'int':
                    if (!filter_var($value, FILTER_VALIDATE_INT)) {
                        throw new ApiException("Parametro '" . $field . "' deve ser um número inteiro", 400);
                    }
                    $validatedData[$field] = (int)$value;
                    break;
                case 'float':
                    if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
                        throw new ApiException("Parametro '" . $field . "' deve ser um número decimal", 400);
                    }
                    $validatedData[$field] = (float)$value;
                    break;
                case 'bool':
                    if (!is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
                        throw new ApiException("Parametro '" . $field . "' deve ser um booleano", 400);
                    }
                    $validatedData[$field] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'json':
                    if (!$this->isValidJson($value)) {
                        throw new ApiException("Parametro '" . $field . "' deve ser um JSON válido", 400);
                    }
                    $validatedData[$field] = json_decode($value, true);
                    break;
                case 'date':
                    if (!$this->isValidDate($value)) {
                        throw new ApiException("Parametro '" . $field . "' deve ser uma data válida", 400);
                    }
                    $validatedData[$field] = $value;
                    break;
                default:
                    if (!is_string($value)) {
                        throw new ApiException("Parametro '" . $field . "' inválido", 400);
                    }
                    $validatedData[$field] = $value;
                    break;
            }
        }
        return $validatedData;
    }

    /**
     * Check if a string is a valid JSON
     *
     * @param string $json
     * @return bool
     */
    protected function isValidJson(string $json): bool
    {
        json_decode($json);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * Check if a string is a valid date in YYYY-MM-DD format
     *
     * @param string $date
     * @return bool
     */
    protected function isValidDate(string $date): bool
    {
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        return $dt && $dt->format('Y-m-d') === $date;
    }

}