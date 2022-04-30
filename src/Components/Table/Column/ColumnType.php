<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Components/Table/Column/ColumnType.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 29/08/2021 14:37
 */

namespace App\Components\Table\Column;

use App\Utils\HtmlUtils;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColumnType
{
    // FIXME : statically called to avoid to have add parent::configureOptions() on all inherit Type class
    final public static function __configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('id')
            ->setAllowedTypes('id', 'string')
            ->setDefault('label', fn(Options $options) => HtmlUtils::humanize($options['id']))
            ->setAllowedTypes('label', ['null', 'string'])
            ->setDefault('translation_domain', 'table')
            ->setAllowedTypes('translation_domain', ['null', 'string'])
            ->setDefault('order', false)
            ->setAllowedValues('order', [false, null, 'ASC', 'DESC'])
            ->setDefault('order_by', null)
            ->setAllowedTypes('order_by', ['null', 'string', 'array'])
            ->setDefault('class', null)
            ->setAllowedTypes('class', ['null', 'string'])
            ->setDefault('width', null)
            ->setAllowedTypes('width', ['null', 'string'])
            ->setDefault('render', null)
            ->setAllowedTypes('render', ['null', 'callable'])
            ->setNormalizer('render', fn(Options $options, $value) => $options['render_html'] ?? $value)
            ->setDefault('render_html', null)
            ->setAllowedTypes('render_html', ['null', 'callable'])
            ->setDefault('is_safe_html', true)
            ->setAllowedTypes('is_safe_html', 'bool')
            ->setNormalizer('is_safe_html', fn(Options $options, $value) => $options['render_html'] ? true : $value);
    }

    public function render(mixed $rowData, array $options): string
    {
        return (string) $rowData;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
