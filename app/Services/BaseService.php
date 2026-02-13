<?php

namespace App\Services;

class BaseService
{
    protected $errors = [];

    /**
     * Get the first error message or all errors
     */
    public function getError(): ?string
    {
        return empty($this->errors) ? null : (is_array($this->errors) ? reset($this->errors) : $this->errors);
    }

    /**
     * Get all error messages
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Set error message(s)
     */
    public function setError($error)
    {
        if (is_array($error)) {
            $this->errors = array_merge($this->errors, $error);
        } else {
            $this->errors[] = $error;
        }
    }

    /**
     * Clear errors
     */
    public function clearErrors()
    {
        $this->errors = [];
    }

    /**
     * Validate data against rules
     */
    public function validate(array $data, array $rules): bool
    {
        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->run($data)) {
            $this->errors = $validation->getErrors();
            return false;
        }

        return true;
    }
}
