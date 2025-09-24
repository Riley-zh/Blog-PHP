<?php

namespace App\Core;

class Validator
{
    protected array $data = [];
    protected array $rules = [];
    protected array $errors = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Set the data to validate
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set validation rules
     */
    public function setRules(array $rules): self
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * Add a validation rule
     */
    public function rule(string $field, string $rules): self
    {
        $this->rules[$field] = $rules;
        return $this;
    }

    /**
     * Validate the data
     */
    public function validate(): bool
    {
        $this->errors = [];

        foreach ($this->rules as $field => $rules) {
            $value = $this->data[$field] ?? null;
            $ruleList = explode('|', $rules);

            foreach ($ruleList as $rule) {
                if (!$this->validateRule($field, $value, $rule)) {
                    break; // Stop validating this field if one rule fails
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Validate a single rule
     */
    protected function validateRule(string $field, $value, string $rule): bool
    {
        // Handle rules with parameters (e.g., min:3, max:255)
        if (strpos($rule, ':') !== false) {
            [$ruleName, $ruleValue] = explode(':', $rule, 2);
            return $this->validateRuleWithParam($field, $value, $ruleName, $ruleValue);
        }

        switch ($rule) {
            case 'required':
                if ($value === null || $value === '') {
                    $this->errors[$field][] = "{$field} is required";
                    return false;
                }
                return true;

            case 'email':
                if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "{$field} must be a valid email address";
                    return false;
                }
                return true;

            case 'numeric':
                if ($value !== null && $value !== '' && !is_numeric($value)) {
                    $this->errors[$field][] = "{$field} must be a number";
                    return false;
                }
                return true;

            case 'string':
                if ($value !== null && !is_string($value)) {
                    $this->errors[$field][] = "{$field} must be a string";
                    return false;
                }
                return true;

            case 'array':
                if ($value !== null && !is_array($value)) {
                    $this->errors[$field][] = "{$field} must be an array";
                    return false;
                }
                return true;

            case 'boolean':
                if ($value !== null && !is_bool($value) && !in_array($value, ['0', '1', 'true', 'false'], true)) {
                    $this->errors[$field][] = "{$field} must be a boolean";
                    return false;
                }
                return true;

            default:
                // Custom rule or unknown rule, ignore for now
                return true;
        }
    }

    /**
     * Validate a rule with parameters
     */
    protected function validateRuleWithParam(string $field, $value, string $rule, string $param): bool
    {
        switch ($rule) {
            case 'min':
                $min = (int) $param;
                if ($value !== null && $value !== '') {
                    if (is_string($value) && strlen($value) < $min) {
                        $this->errors[$field][] = "{$field} must be at least {$min} characters";
                        return false;
                    } elseif (is_numeric($value) && $value < $min) {
                        $this->errors[$field][] = "{$field} must be at least {$min}";
                        return false;
                    } elseif (is_array($value) && count($value) < $min) {
                        $this->errors[$field][] = "{$field} must have at least {$min} items";
                        return false;
                    }
                }
                return true;

            case 'max':
                $max = (int) $param;
                if ($value !== null && $value !== '') {
                    if (is_string($value) && strlen($value) > $max) {
                        $this->errors[$field][] = "{$field} must not exceed {$max} characters";
                        return false;
                    } elseif (is_numeric($value) && $value > $max) {
                        $this->errors[$field][] = "{$field} must not exceed {$max}";
                        return false;
                    } elseif (is_array($value) && count($value) > $max) {
                        $this->errors[$field][] = "{$field} must not have more than {$max} items";
                        return false;
                    }
                }
                return true;

            case 'size':
                $size = (int) $param;
                if ($value !== null && $value !== '') {
                    if (is_string($value) && strlen($value) != $size) {
                        $this->errors[$field][] = "{$field} must be exactly {$size} characters";
                        return false;
                    } elseif (is_numeric($value) && $value != $size) {
                        $this->errors[$field][] = "{$field} must be exactly {$size}";
                        return false;
                    } elseif (is_array($value) && count($value) != $size) {
                        $this->errors[$field][] = "{$field} must have exactly {$size} items";
                        return false;
                    }
                }
                return true;

            case 'in':
                $values = explode(',', $param);
                if ($value !== null && $value !== '' && !in_array($value, $values)) {
                    $this->errors[$field][] = "{$field} must be one of: " . implode(', ', $values);
                    return false;
                }
                return true;

            default:
                // Unknown rule with parameter, ignore for now
                return true;
        }
    }

    /**
     * Get validation errors
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get the first error for a field
     */
    public function firstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }

    /**
     * Check if there are validation errors
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Check if validation passes
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * Get validated data
     */
    public function validated(): array
    {
        $validated = [];
        foreach (array_keys($this->rules) as $field) {
            if (array_key_exists($field, $this->data)) {
                $validated[$field] = $this->data[$field];
            }
        }
        return $validated;
    }
}