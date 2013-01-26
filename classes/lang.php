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

    /**
     * Create localized URI
     *
     * @param    string       $uri       URI to localize
     * @param    string|null  $language  name of the language with which create URI, null for active language
     * @return   string                  localized URI
     */
    public static function localized($uri, $language = null)
    {
        if (empty($language))
        {
            $language = self::get_lang();
        }
        if (!empty($language) and $language != self::$default_language)
        {
            $uri = '/' . $language . $uri;
        }
        return $uri;
    }

}
