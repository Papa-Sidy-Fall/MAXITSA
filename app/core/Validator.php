<?php

namespace App\Core;

class Validator
{
    private array $data;
    private array $rules;
    private array $messages;
    private array $errors = [];

    public function __construct()
    {
        // Constructeur vide
    }

    /**
     * Créer une instance de validation
     */
    public static function make(array $data, array $rules, array $messages = []): self
    {
        $validator = new self();
        $validator->data = $data;
        $validator->rules = $rules;
        $validator->messages = $messages;
        $validator->validate();
        
        return $validator;
    }

    /**
     * Effectuer la validation
     */
    private function validate(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $this->validateRule($field, $value, $rule);
            }
        }
    }

    /**
     * Valider une règle spécifique
     */
    private function validateRule(string $field, $value, string $rule): void
    {
        $parameters = [];
        
        // Analyser la règle et ses paramètres
        if (strpos($rule, ':') !== false) {
            [$rule, $paramString] = explode(':', $rule, 2);
            $parameters = explode(',', $paramString);
        }

        $method = 'validate' . ucfirst($rule);
        
        if (method_exists($this, $method)) {
            if (!$this->$method($field, $value, $parameters)) {
                $this->addError($field, $rule, $parameters);
            }
        }
    }

    /**
     * Ajouter une erreur
     */
    private function addError(string $field, string $rule, array $parameters = []): void
    {
        $key = "$field.$rule";
        $message = $this->messages[$key] ?? $this->getDefaultMessage($field, $rule, $parameters);
        $this->errors[$field] = $message;
    }

    /**
     * Obtenir un message d'erreur par défaut
     */
    private function getDefaultMessage(string $field, string $rule, array $parameters = []): string
    {
        $messages = [
            'required' => "Le champ {$field} est requis",
            'email' => "Le champ {$field} doit être une adresse email valide",
            'min' => "Le champ {$field} doit contenir au moins " . ($parameters[0] ?? 'X') . " caractères",
            'max' => "Le champ {$field} ne peut pas dépasser " . ($parameters[0] ?? 'X') . " caractères",
            'confirmed' => "La confirmation du champ {$field} ne correspond pas",
            'phone' => "Le champ {$field} doit être un numéro de téléphone valide",
            'alpha' => "Le champ {$field} ne peut contenir que des lettres",
            'numeric' => "Le champ {$field} doit être numérique",
        ];
        
        return $messages[$rule] ?? "Le champ {$field} n'est pas valide";
    }

    /**
     * Vérifier si la validation a échoué
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Obtenir les erreurs
     */
    public function errors(): array
    {
        return $this->errors;
    }

    // === RÈGLES DE VALIDATION ===

    protected function validateRequired(string $field, $value, array $parameters): bool
    {
        return !empty($value) && $value !== '';
    }

    protected function validateEmail(string $field, $value, array $parameters): bool
    {
        if (empty($value)) return true;
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    protected function validateMin(string $field, $value, array $parameters): bool
    {
        if (empty($value)) return true;
        $min = (int)($parameters[0] ?? 0);
        return strlen($value) >= $min;
    }

    protected function validateMax(string $field, $value, array $parameters): bool
    {
        if (empty($value)) return true;
        $max = (int)($parameters[0] ?? 0);
        return strlen($value) <= $max;
    }

    protected function validateConfirmed(string $field, $value, array $parameters): bool
    {
        if (!$value) return true;
        
        // Si le champ est confirm_password, on compare avec password
        if ($field === 'confirm_password') {
            $originalValue = $this->data['password'] ?? null;
            return $value === $originalValue;
        }
        
        // Sinon, on cherche le champ de confirmation classique
        $confirmField = $field . '_confirmation';
        $confirmValue = $this->data[$confirmField] ?? null;
        
        return $value === $confirmValue;
    }

    protected function validatePhone(string $field, $value, array $parameters): bool
    {
        if (empty($value)) return true;
        
        // Supprimer tous les caractères non numériques
        $cleanPhone = preg_replace('/[^0-9]/', '', $value);
        
        // Vérifier que c'est un numéro sénégalais valide
        return preg_match('/^(77|78|76|70|75)[0-9]{7}$/', $cleanPhone) ||
               preg_match('/^22177[0-9]{7}$/', $cleanPhone) ||
               preg_match('/^22178[0-9]{7}$/', $cleanPhone);
    }

    protected function validateAlpha(string $field, $value, array $parameters): bool
    {
        if (empty($value)) return true;
        return ctype_alpha(str_replace(' ', '', $value));
    }

    protected function validateNumeric(string $field, $value, array $parameters): bool
    {
        if (empty($value)) return true;
        return is_numeric($value);
    }
}
