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

    /**
     * Sanitize HTML content to prevent XSS while preserving rich-text formatting
     */
    protected function sanitizeHtml(string $html): string
    {
        // Recursively remove script & style tags to prevent nested tag bypasses
        do {
            $prev = $html;
            $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
            $html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);
        } while ($html !== $prev);

        // Remove dangerous tags (applet, embed, object, form, base, meta, link, svg, math, canvas)
        $html = preg_replace('#</?(applet|embed|object|form|base|meta|link|svg|math|canvas)(.*?)>#is', '', $html);

        // Remove javascript:, vbscript:, and data:text/html URIs
        $html = preg_replace('#(javascript|vbscript|data\s*:\s*text/html):#is', '$1-blocked:', $html);

        // Remove inline event handlers (onload, onerror, onclick, etc.)
        $html = preg_replace('#\s*on[a-zA-Z]+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)#is', '', $html);

        return $html;
    }
}
