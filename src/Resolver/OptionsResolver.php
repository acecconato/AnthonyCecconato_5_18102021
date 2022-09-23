<?php

declare(strict_types=1);

namespace Blog\Resolver;

use ArrayObject;
use InvalidArgumentException;

class OptionsResolver
{
    /**
     * @var ArrayObject<string, Option>
     */
    private ArrayObject $options;

    /**
     * @param  array<Option> $options
     * @return void
     */
    public function setOptions(array $options = []): void
    {
        $this->options = new ArrayObject();
        foreach ($options as $option) {
            $this->add($option);
        }
    }

    /**
     * @param  Option $option
     * @return $this
     */
    public function add(Option $option): self
    {
        $this->options->offsetSet($option->getName(), $option);

        return $this;
    }

    /**
     * @param  array $options
     * @return array
     */
    public function resolve(array $options): array
    {
        $resolvedOptions = [];

        /**
         * @var Option $option
        */
        foreach ($this->options as $option) {
            $optionName = $option->getName();

            if (array_key_exists($optionName, $options)) {
                $value = $options[$optionName];

                if (!$option->isValid($value)) {
                    throw new InvalidArgumentException(
                        sprintf(
                            'Option "%s" with value "%s" is invalid. Check the validator',
                            $option->getName(),
                            (string)$value
                        )
                    );
                }

                $resolvedOptions[$optionName] = $value;
                continue;
            }

            if ($option->hasDefaultValue()) {
                $resolvedOptions[$optionName] = $option->getDefaultValue();
                continue;
            }

            throw new InvalidArgumentException(sprintf('Required option "%s" is missing', $optionName));
        }

        return $resolvedOptions;
    }

    /**
     * @param  array $options
     * @return void
     */
    public function checkdiff(array $options): void
    {
        $defined = $this->options->getArrayCopy();
        $diff = array_diff_key($defined, $options);

        if (count($diff) > 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Following options does not exists: "%s". Defined options are: "%s"',
                    implode(', ', array_keys($diff)),
                    implode(', ', array_keys($defined))
                )
            );
        }
    }
}
