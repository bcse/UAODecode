<?php
/**
 * Decode Unicode-At-On Big5 to Unicode.
 *
 * This script is ported from {@link http://svn.openfoundry.org/pcman/trunk/UAOTabGen/ PCMan::UAOTabGen}.
 * Licensed under {@link http://zh.wikipedia.org/wiki/GPL GNU GPL}.
 * @author Joel Lee <elohim@gmail.com>
 * @version 1.0
 * @package UAO
 */
class UAODecode
{
	/**
	 * B2U table
	 *
	 * @access private
	 * @staticvar string
	 */
	private static $B2U;

	/**
	 * Load B2U table
	 *
	 * @return void
	 */
	public static function init() {
		self::$B2U = file_get_contents('B2U');
	}

	/**
	 * Decode UAO-Big5 characters to numeric character reference
	 *
	 * @param string $text Big5 chracters
	 * @return string
	 */
	public static function big5_to_ncr($text) {
		if ( empty(self::$B2U) ) {
			self::init();
		}

		$ret = '';
		$len = strlen($text);

		for ( $i = 0; $i < $len; $i++ ) {
			if ( ord($text[$i]) > 127 ) {
				$ch1 = ord($text[$i]);
				$ch2 = ord($text[$i+1]);
				if ( $ch1 >= 129 && $ch2 >= 64 ) {
					$index = 2 * ((($ch1 - 129) * 158) + ($ch2 < 161 ? $ch2 - 64 : $ch2 - 98)) - 2 ;
					$ret .= '&#' . ((ord(self::$B2U[$index]) << 8) | ord(self::$B2U[$index+1])) . ';';
				}
				++$i;
			}
			else {
				$ret .= $text[$i];
			}
		}
		return $ret;
	}

	/**
	 * Decode UAO-Big5 characters to UTF-8 characters
	 *
	 * @param string $text Big5 chracters
	 * @return string
	 */
	public static function big5_to_utf8($text) {
		return html_entity_decode(self::big5_to_ncr($text), ENT_NOQUOTES, 'UTF-8');
	}
}
?>