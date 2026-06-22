<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Reflects public component properties for composition-language K1/K6 checks.
 */
final class ComponentPublicAttributeReflector
{
    /**
     * @return list<string>
     */
    public static function reflect(string $className): array
    {
        if (!class_exists($className)) {
            return [];
        }

        $reflection = new \ReflectionClass($className);
        $names = [];

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $names[] = $property->getName();
        }

        sort($names);

        return $names;
    }

    /**
     * Default values for selected public properties (K6 size drift).
     *
     * @param list<string> $propertyNames
     *
     * @return list<string>
     */
    public static function defaultStringValues(string $className, array $propertyNames): array
    {
        if (!class_exists($className)) {
            return [];
        }

        $reflection = new \ReflectionClass($className);
        $defaults = [];

        foreach ($propertyNames as $name) {
            if (!$reflection->hasProperty($name)) {
                continue;
            }

            $property = $reflection->getProperty($name);
            if (!$property->isPublic() || $property->isStatic()) {
                continue;
            }

            $default = $property->getDefaultValue();
            if (\is_string($default)) {
                $defaults[] = $default;
            }
        }

        return $defaults;
    }
}
