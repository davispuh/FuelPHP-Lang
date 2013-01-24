<?php

class Lang extends \Fuel\Core\Lang {
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
	public static function get_lang() {
		$language = \Config::get('language');
		if ($language == self::$default_language) {
			$lang_segment = \Uri::segment(1);
			if (!empty($lang_segment) and $lang_segment != false) {
				$languages = \Config::get('languages');
				if (!empty($languages) and array_search($lang_segment, $languages) !== false) {
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
	 * Loads a language file.
	 *
	 * @param    mixed        $file        string file | language array | Lang_Interface instance
	 * @param    mixed       $group        null for no group, true for group is filename, false for not storing in the master lang
	 * @param    string|null $language     name of the language to load, null for the configurated language
	 * @param    bool        $overwrite    true for array_merge, false for \Arr::merge
	 * @param    bool        $reload       true to force a reload even if the file is already loaded
	 * @return   array                     the (loaded) language array
	 */
	public static function load($file, $group = null, $language = null, $overwrite = false, $reload = false)
	{
		$language or $language = self::get_lang();
		return parent::load($file, $group, $language, $overwrite, $reload);
	}

	/**
	 * Save a language array to disk.
	 *
	 * @param   string          $file      desired file name
	 * @param   string|array    $lang      master language array key or language array
	 * @param   string|null     $language  name of the language to load, null for the configurated language
	 * @return  bool                       false when language is empty or invalid else \File::update result
	 */
	public static function save($file, $lang, $language = null)
	{
		if ($language === null)
		{
			$language = self::get_lang();
		}
		return parent::save($file, $lang, $language);
	}

	/**
	 * Returns a (dot notated) language string
	 *
	 * @param   string       $line      key for the line
	 * @param   array        $params    array of params to str_replace
	 * @param   mixed        $default   default value to return
	 * @param   string|null  $language  name of the language to get, null for the configurated language
	 * @return  mixed                   either the line or default when not found
	 */
	public static function get($line, array $params = array(), $default = null, $language = null)
	{
		if ($language === null)
		{
			$language = self::get_lang();
		}
		return parent::get($line, $params, $default, $language);
	}

	/**
	 * Sets a (dot notated) language string
	 *
	 * @param    string       $line      a (dot notated) language key
	 * @param    mixed        $value     the language string
	 * @param    string       $group     group
	 * @param    string|null  $language  name of the language to set, null for the configurated language
	 * @return   void                    the \Arr::set result
	 */
	public static function set($line, $value, $group = null, $language = null)
	{
		if ($language === null)
		{
			$language = self::get_lang();
		}
		return parent::set($line, $value, $group, $language);
	}

	/**
	 * Deletes a (dot notated) language string
	 *
	 * @param    string       $item      a (dot notated) language key
	 * @param    string       $group     group
	 * @param    string|null  $language  name of the language to set, null for the configurated language
	 * @return   array|bool              the \Arr::delete result, success boolean or array of success booleans
	 */
	public static function delete($item, $group = null, $language = null)
	{
		if ($language === null)
		{
			$language = self::get_lang();
		}
		return parent::delete($item, $group, $language);
	}

	/**
	 * Create localized URI
	 *
	 * @param    string       $uri       URI to localize
	 * @param    string|null  $language  name of the language with which create URI, null for active language
	 * @return   string                  localized URI
	 */
	public static function localized($uri, $language = null) {
		if (empty($language)) {
			$language = self::get_lang();
		}
		if (!empty($language) and $language != self::$default_language) {
			$uri = '/' . $language . $uri;
		}
		return $uri;
	}
}
