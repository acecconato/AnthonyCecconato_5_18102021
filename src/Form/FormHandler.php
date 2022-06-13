<?php

declare(strict_types=1);

namespace Blog\Form;

use Blog\Hydration\ObjectHydrator;
use Blog\Validator\Validator;
use Exception;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

class FormHandler
{

    private ?object $formObject = null;

    private Request $request;

    private bool $wasSubmitted = false;

    public function __construct(
        private Validator $validator,
        private ObjectHydrator $hydrator
    ) {
    }

    public function isSubmitted(): bool
    {
        return (bool)$this->request->request->get('submit');
    }

    /**
     * @throws Exception
     */
    public function isValid(): bool
    {
        $sessionCsrf = $this->request->getSession()->get('csrf_token');
        $formCsrf = $this->request->request->get('csrf_token');

        if (!$sessionCsrf || !$formCsrf || $sessionCsrf !== $formCsrf) {
            throw new Exception('CSRF Token error');
        }

        return $this->validator->validateObject($this->formObject);
    }

    /**
     * @return array<mixed>
     * @throws Exception
     */
    public function getErrors(): array
    {
        return $this->validator->getErrors();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function loadFromRequest(Request $request, string $fqcnClassName): self
    {
        $this->request = $request;

        if (!$this->isSubmitted()) {
            return $this;
        }

        if ($this->wasSubmitted) {
            throw new Exception('The form was already submitted');
        }

        $this->wasSubmitted = true;

        $formData = array_map(fn($field) => trim($field), $this->request->request->all('form'));

        dd($this->hydrator->hydrateSingle($formData, $fqcnClassName));

        $this->formObject = $this->hydrator->hydrateSingle($formData, $fqcnClassName);

        return $this;
    }

    public function get(string $field): string
    {
        return (string)$this->request->request->get($field);
    }
}