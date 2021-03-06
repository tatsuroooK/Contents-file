<?php

namespace ContentsFile\Validation;

use Cake\Validation\Validation;

class ContentsFileValidation extends Validation
{
    /**
     * checkMaxSize
     *
     */
    public static function checkMaxSize($value, $max, $context)
    {
        $maxValue = self::calcFileSizeUnit($max);
        return $maxValue >= $value['size'];
    }

    /**
     * uploadMaxSizeCheck
     *
     */
    public static function uploadMaxSizeCheck($value, $context)
    {
        return $value['error'] != UPLOAD_ERR_INI_SIZE;
    }

    /**
     * Calculate file size by unit
     *
     * e.g.) 100KB -> 1024000
     *
     * @param $size mixed
     * @return int file size
     */
    private static function calcFileSizeUnit($size)
    {
        $units = ['K', 'M', 'G', 'T'];
        $byte = 1024;

        if (is_numeric($size) || is_int($size)) {
            return $size;
        } else if (is_string($size) && preg_match('/^([0-9]+(?:\.[0-9]+)?)(' . implode('|', $units) . ')B?$/i', $size, $matches)) {
            return $matches[1] * pow($byte, array_search($matches[2], $units) + 1);
        }
        return false;
    }

    /**
     * checkExtension
     * 拡張子のチェック
     *
     * @param $value mixed
     * @return bool
     */
    public static function checkExtension($value, $extensions = ['gif', 'jpeg', 'png', 'jpg'])
    {
        // データがない場合はチェックしない
        if (!is_array($value) || !array_key_exists('name', $value)) {
            return true;
        }
        $check = $value['name'];

        $extension = strtolower(pathinfo($check, PATHINFO_EXTENSION));
        foreach ($extensions as $value) {
            if ($extension === strtolower($value)) {
                return true;
            }
        }

        return false;
    }

    /* ドラッグアンドドロップアップロード専用 */
    /**
     * extensionDd
     * 拡張子のチェック
     *
     * @param $value mixed
     * @return bool
     */
    public static function extensionDd($value, $extensions, $filenameField, $context)
    {
        // チェックに必要なフィールドがない
        if (!array_key_exists($filenameField, $context['data'])) {
            return false;
        }
        $filename = $context['data'][$filenameField];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        foreach ($extensions as $value) {
            if ($extension === strtolower($value)) {
                return true;
            }
        }

        return false;
    }

}
