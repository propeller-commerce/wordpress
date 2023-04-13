<?php

namespace Propeller;

class PropellerUtils {

	/**
	 * Sanitizes input data array
	 * @param $data
	 *
	 * @return mixed
	 */
	public static function sanitize($data) {
		foreach ($data as $key => $value) {
			if (is_array($value))
				$data[$key] = self::sanitize($data[$key]);
			else {
				if ( is_numeric( $data[ $key ] ) ) {
					if ( strpos( $data[ $key ], '.' ) !== false ) {
						$data[ $key ] = (double) $data[ $key ];
					} else {
						$data[ $key ] = (int) $data[ $key ];
					}
				} elseif ( is_string( $data[ $key ] ) ) {
					if ( stripos( $key, 'mail' ) !== false ) {
						$data[ $key ] = sanitize_email( $data[ $key ] );
					} else {
						$data[ $key ] = sanitize_text_field( urldecode($data[ $key ]) );
					}
				} elseif ( is_bool( $data[ $key ] ) ) {
					$data[ $key ] = (bool) $data[ $key ];
				}
			}
		}

		return $data;
	}

	/**
	 * Sanitizes query strings, eg:
	 *
	 * eg input: t=234328942&test=2384238423
	 *
	 * http_build_query will use urlencode on the properties.
	 *
	 * @param $query_string
	 *
	 * @return string
	 */
	public static function sanitize_query_string($query_string) {
		parse_str($query_string, $vars);
		return http_build_query($vars);
	}

}