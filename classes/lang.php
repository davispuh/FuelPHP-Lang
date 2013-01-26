<?php

class Lang extends \Fuel\Core\Lang
{
    /**
     * @var  string default language when language is not specified in URI
     */
    public static $default_language;

    public static function _init()
    {
        static::$default_language = \Config::get('language');
        parent::_init();
    }

    /**
     * Returns currently active language.
     *
     * @return   string    currently active language
     */
    public static function get_lang()
    {
        $language = \Config::get('language');
        if ($language == self::$default_language)
        {
            $lang_segment = \Uri::segment(1);
            if (!empty($lang_segment) and $lang_segment != false)
            {
                $languages = \Config::get('languages');
                if (!empty($languages) and array_search($lang_segment, $languages) !== false)
                {
                    $language = $lang_segment;
                }
            }
        }
        if (empty($language))
        {
            $language = self::$fallback[0];
        }
        return $language;
    }

}
