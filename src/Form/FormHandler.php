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
        $sessionCsrf = $this->request->getSession()->get('csrf_token');
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

        $formData = array_map(fn($field) => trim($field), $this->request->request->all('form'));

        if ($request->files->has('form')) {
            foreach ($request->files->all('form') as $filename => $file) {
                $formData[$filename] = $file;
            }
        }

        $this->formObject = $object;
        $this->hydrator->hydrateSingle($formData, $object);

        return $this;
    }

    public function get(string $field): string
    {
        return (string)$this->request->request->get($field);
    }

    public function addValidatorError(string $message): void
    {
        $this->validator->addError($message);
    }
}
