<?php

declare(strict_types=1);

namespace Blog\Form;

use Blog\Hydration\ObjectHydrator;
use Blog\Validator\Validator;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

class FormHandler
{
    private ?object $formObject = null;

    private Request $request;

    private bool $wasSubmitted = false;

    private array $formData = [];

    public function __construct(
        private readonly Validator $validator,
        private readonly ObjectHydrator $hydrator
    ) {
    }

    public function isSubmitted(): bool
    {
        return (bool)$this->request->request->get('submit');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function isValid(): bool
    {
        $sessionCsrf = $this->request->get('csrf_token');
        $formCsrf = $this->request->request->get('csrf_token');

        if (!$sessionCsrf || !$formCsrf || $sessionCsrf !== $formCsrf) {
            throw new Exception('The csrf token is not valid');
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
    public function loadFromRequest(Request $request, object $object): self
    {
        $this->request = $request;

        if (!$this->isSubmitted()) {
            return $this;
        }

        if ($this->wasSubmitted) {
            throw new Exception('The form was already submitted');
        }

        $this->wasSubmitted = true;

        $this->formData = array_map(fn($field) => trim($field), $this->request->request->all('form'));

        if ($request->files->has('form')) {
            foreach ($request->files->all('form') as $filename => $file) {
                $this->formData[$filename] = $file;
            }
        }

        $this->formObject = $object;
        $this->hydrator->hydrateSingle($this->formData, $object);

        return $this;
    }

    public function get(string $field): string
    {
        if (array_key_exists($field, $this->formData)) {
            return (string)$this->formData[$field];
        }

        return '';
    }

    public function addValidatorError(string $message): void
    {
        $this->validator->addError($message);
    }
}
