<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisCommerce\Controller\Plugin;

/**
 * Resolves the MULTI-VALUE config fields of a plugin modal (category trees, text types, attribute
 * values…) when the config is saved from the React page editor (the legacy editor is left untouched):
 *
 *  - React config tabs: post a real array, used as is.
 *  - React editor, iframe view: posts every input as ONE scalar per name, so `x[]` arrays collapse to
 *    their last value. It does post hidden scalars, hence the `<field>_list` companion input
 *    (comma-separated ids) that the config views add and keep in sync in the React editor.
 *  - React editor, schema form: only knows plain fields, so it posts neither. The field is then left
 *    as it currently is (getFormData()) instead of being wiped by the save.
 */
trait MelisCommercePluginListParamsTrait
{
    /**
     * @param array $params  posted values (validation POST or savePluginConfigToXml parameters)
     * @param array $lists   multi-value field names, e.g. ['m_category_ids']
     * @param array $scalars single-value fields only set by a custom widget (not posted by the schema form)
     * @return array
     */
    protected function resolveListParams(array $params, array $lists, array $scalars = [])
    {
        if (!MelisCommercePageEditor::isReactRequest()) {
            return $params;
        }

        $current = null;

        foreach ($lists as $field) {
            $listKey = $field . '_list';

            if (isset($params[$field]) && is_array($params[$field])) {
                // Real array (legacy editor) wins
            } elseif (array_key_exists($listKey, $params)) {
                $ids = array_map('trim', explode(',', (string) $params[$listKey]));
                $params[$field] = array_values(array_filter($ids, 'strlen'));
            } else {
                $current = $current ?? (array) $this->getFormData();
                if (!empty($current[$field])) {
                    $params[$field] = array_values((array) $current[$field]);
                }
            }

            unset($params[$listKey], $params[$field . '[]']);
        }

        foreach ($scalars as $field) {
            if (!array_key_exists($field, $params)) {
                $current = $current ?? (array) $this->getFormData();
                if (isset($current[$field]) && $current[$field] !== '') {
                    $params[$field] = $current[$field];
                }
            }
        }

        return $params;
    }
}
