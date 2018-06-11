<?php

namespace App\Utility;

use App\Entity\EventInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Yaml\Yaml;

class Formatter
{
    private $translator;
    private $dictionaries;
    private $entries = [];

    public function __construct(TranslatorInterface $translator, array $dictionaries = [])
    {
        $this->translator = $translator;
        $this->dictionaries = $dictionaries;

        foreach($this->dictionaries as $dictionary) {
            $this->entries[] = Yaml::parseFile('resources/dictionaries/'.$dictionary.'.yaml');
        }
    }

    public function format(EventInterface $event): string
    {
        $data = $event->getData();
        $substitutes = $this->processSubstitutables($data, $event->getSubstitutables());
        $transformables = $this->processTransformables($data, $event->getTransformables());
        $parameters = $this->createPlaceholders($substitutes + $transformables + $data);

        return $this->translator->trans($event->getType(), $parameters, 'events');
    }

    private function processSubstitutables(array $data, array $keys): array
    {
        $substitutables= \array_intersect_key($data, \array_flip($keys));

        return \array_map(function($substitutable) {
            return \array_map(function($entry) use($substitutable) {
                return \str_replace(\array_keys($entry), \array_values($entry), $substitutable);
            }, $this->entries)[0];
        }, $substitutables);
    }

    private function processTransformables(array $data, array $keys): array
    {
        $values = [];

        foreach($keys as $property => $names) {
            $domain = $data['type'].'-'.$property;

            foreach($names as $name) {
                $values[$name] = $this->translator->trans($data[$property].'.'.$name, [], $domain);
            }
        }

        return $values;
    }

    private function createPlaceholders(array $parameters): array
    {
        return \array_combine(
            \array_map(function($key) {
                return '%' . $key . '%';
            }, \array_keys($parameters)),
            $parameters
        );
    }
}
