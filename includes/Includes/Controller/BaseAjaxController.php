<?php 
namespace Propeller\Includes\Controller;

class BaseAjaxController {
    protected $reCaptcha_url = PROPELLER_GRECAPTCHA_VERIFY_URL;

    public function __construct() { }

    /**
	 * Sanitize frontend input
	 *
	 * @param $data
	 *
	 * @return array
	 */
    public function sanitize($data) {
        foreach ($data as $key => $value) {
            if (is_array($value))
                $data[$key] = $this->sanitize($data[$key]);
            else {
                if (is_string($data[$key])) {
                    if (stripos($key, 'mail') !== false) 
                        $data[$key] = sanitize_email($data[$key]);
                    else
                        $data[$key] = sanitize_text_field($data[$key]);
                }
                else if (is_numeric($data[$key])) {}
                else if (is_bool($data[$key])) {}
            }
        }

        return $data;
    }

    /**
	 * Check the front-end request
	 *
	 * @param $nonce_data_key
	 * @param string $nonce_action_key
	 *
	 * @return false|int
	 */
	protected function validate_form_request( $nonce_data_key, $nonce_action_key = PROPELLER_NONCE_KEY_FRONTEND ) {
		$nonce = isset( $_REQUEST[ $nonce_data_key ] ) ? $_REQUEST[ $nonce_data_key ] : false;

		if ( false === $nonce ) {
			return false;
		}

		return (defined('DOING_AJAX') && DOING_AJAX) && wp_verify_nonce( $nonce, $nonce_action_key );
	}

    /**
     * Validate Google reCaptcha
     * @param $data
     * 
     * @return bool;
     */
    protected function validate_recaptcha($data) {
        if (!isset($data['rc_token']))
            return false;

        $response = file_get_contents(
            "$this->reCaptcha_url?secret=" . PROPELLER_RECAPTCHA_SECRET . "&response=" . $data['rc_token'] . "&remoteip=" . $_SERVER['REMOTE_ADDR']
        );

        // use json_decode to extract json response
        $response = json_decode($response);

        if ($response->success === false)
            return false;

        if ($response->success && $response->score < PROPELLER_RECAPTCHA_MIN_SCORE)
            return false;

        return true;
    }
}