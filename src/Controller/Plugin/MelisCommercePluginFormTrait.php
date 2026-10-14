<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

/**
 * Front form helpers shared by the commerce plugins.
 */
trait MelisCommercePluginFormTrait
{
    /**
     * Put each input's NotEmpty validator FIRST, breaking the chain when it fails (Mantis #0011158).
     * The configs list it last: since a NotEmpty is present, Laminas doesn't inject its own leading one,
     * so an empty field also ran StringLength / EmailAddress / password rules / Identical and showed
     * several misleading messages ("Invalid type given", password rules, "does not match"…) next to
     * "required". Applied to the merged config, so site overrides of these forms get it too.
     *
     * @param array $formConfig a Laminas form factory config (with an 'input_filter' key)
     * @return array
     */
    protected function notEmptyValidatorFirst(array $formConfig): array
    {
        if (empty($formConfig['input_filter']) || !is_array($formConfig['input_filter'])) {
            return $formConfig;
        }
        foreach ($formConfig['input_filter'] as $key => $input) {
            if (empty($input['validators']) || !is_array($input['validators'])) {
                continue;
            }
            $notEmpty = [];
            $others = [];
            foreach ($input['validators'] as $validator) {
                $name = is_array($validator) ? ltrim((string) ($validator['name'] ?? ''), '\\') : '';
                if ($name === 'NotEmpty' || $name === 'Laminas\Validator\NotEmpty') {
                    $validator['break_chain_on_failure'] = true;
                    $notEmpty[] = $validator;
                } else {
                    $others[] = $validator;
                }
            }
            if ($notEmpty) {
                $formConfig['input_filter'][$key]['validators'] = array_merge($notEmpty, $others);
            }
        }
        return $formConfig;
    }
}
