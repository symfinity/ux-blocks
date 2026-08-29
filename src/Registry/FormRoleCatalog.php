<?php

declare(strict_types=1);

namespace Symfinity\UxBlocks\Registry;

/**
 * Canonical form-domain role ids for symfinity/ux-blocks-form (symfinity 110).
 *
 * @return list<string>
 */
final class FormRoleCatalog
{
    /** @return list<string> */
    public static function roles(): array
    {
        return [
            'label',
            'input',
            'textarea',
            'checkbox',
            'radio-group',
            'select',
            'switch',
            'file-input',
            'input-group',
            'fieldset',
            'field',
            'floating-field',
            'range',
            'radio',
            'form',
            'form-actions',
            'file-upload',
        ];
    }
}
