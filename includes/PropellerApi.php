<?php

namespace Propeller;

use GraphQL\Client;
use GraphQL\Exception\QueryError;
use Propeller\Includes\Controller\SessionController;

class PropellerApi {
    protected $client;
    protected $endpoint;
    protected $key;
    protected $secret;

    protected $execution_time;
    

    public function __construct() {
        if (defined('PROPELLER_API_URL') && defined('PROPELLER_API_KEY')) {
            $this->endpoint = PROPELLER_API_URL;
            $this->key = PROPELLER_API_KEY;
        }
        else {
            $this->get_credentials();

            if (empty($this->endpoint) || empty($this->key))
                return;
        }
    }

    protected function buildClient($ommit_access_token) {
        $this->client = new Client(
            $this->endpoint,
            [],
            [ 
                'connect_timeout' => 60,
                'timeout' => 60,
                'headers' => $this->buildHeaders($ommit_access_token)
            ]
        );
    }

    protected function buildHeaders($ommit_access_token) {
        $headers = [
            'apikey' => $this->key
        ];
        
        if (!$ommit_access_token && SessionController::has(PROPELLER_ACCESS_TOKEN) && SessionController::get(PROPELLER_ACCESS_TOKEN))
            $headers['Authorization'] = 'Bearer ' . SessionController::get(PROPELLER_ACCESS_TOKEN);
        
        return $headers;
    }

    protected function query($gql, $type, $ommit_access_token = false, $display_error = true) {
        if (empty($this->endpoint) || empty($this->key))
            return;

        // if (!$this->client)
            $this->buildClient($ommit_access_token);
        
        try {
            $result = gettype($gql) == 'string' ? $this->client->runRawQuery($gql) : $this->client->runQuery($gql);
            
            $this->dump_builder($gql);
            if (gettype($gql) != 'string') {
                echo "<pre>";
                debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                echo "</pre>";
            }
            
            // Display original response from endpoint
            // var_dump($result->getResponseObject());

            // Reformat the results to an array and get the results of part of the array
            // $result->reformatResults(true);

            return $result->getData()->$type;
        }
        catch (QueryError $exception) {
            ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $backtrace = ob_get_clean();

            if (PROPELLER_DEBUG && $display_error) {
                echo "<pre class=\"text-danger\">";
                var_dump($exception->getErrorDetails());
                echo "</pre>";

                echo "<pre class=\"text-danger\">";
                echo $this->dump($gql);
                echo "</pre>";

                echo "<pre class=\"text-danger\">";
                print_r($backtrace);
                echo "</pre>";
            }

            $this->log_error($exception->getErrorDetails()['message'], $backtrace);
            
            $err_array = $exception->getErrorDetails();
            $err_array['query'] = gettype($gql) == 'string' ? $gql : $gql->__toString();

            return $this->process_errors($err_array);
        }
    }

    public function dump_builder($gql, $execution_time = 0) {
        if (gettype($gql) != 'string')
            echo 'BUILDER: ' . $gql->__toString();

        // echo gettype($gql) == 'string' ? 'RAW: ' . $gql : 'BUILDER: ' . $gql->__toString();
    }

    public function dump($gql, $execution_time = 0) {
        // if (gettype($gql) != 'string')
        //     echo 'BUILDER: ' . $gql->__toString();
        echo '<pre>';
        echo gettype($gql) == 'string' ? 'RAW: ' . $gql : 'BUILDER: ' . $gql->__toString();
        echo '</pre>';
    }

    protected function process_errors($err_array)
    {
        $errors = $this->collect_errors($err_array);
        $message = $err_array['message'];


        if (defined('PROPELLER_DEBUG') && PROPELLER_DEBUG) {
           
            if (isset($err_array['path']))
                $details[] = '<b>Call:</b> ' . $err_array['path'][0];
            
            if (isset($err_array['extensions']) && isset($err_array['extensions']['serviceName']))
                $details[] = '<b>' . $err_array['extensions']['serviceName'] . ':</b> '. $err_array['extensions']['code'];

            if (isset($err_array['query']))
                $details[] = '<b>Query:</b> ' . $err_array['query'];

            return '<div><p>Error: ' . $message . '</p><p>' . implode('<br />', $errors) . '</p><p>' . implode('<br />', $details) . '</p></div>';
        }
        
        return '<div><p>' . $message . '</p><p>' . implode('<br />', $errors) . '</p></div>';
    }

    private function collect_errors($err_array) {
        $errors = [];

        if (isset($err_array['extensions'])) {
            if (isset($err_array['extensions']['invalidArgs'])) {
                if (count($err_array['extensions']['invalidArgs'])) {
                    foreach ($err_array['extensions']['invalidArgs'] as $error) {
                        if (is_array($error['constraints']) && count($error['constraints'])) {
                            foreach ($error['constraints'] as $err_key => $err_val)
                                $errors[] = $err_val;
                        }
                    }
                }
            }
            else if (isset($err_array['extensions']['exception'])) {
                if (isset($err_array['extensions']['messages']) && count($err_array['extensions']['messages'])) {
                    foreach ($err_array['extensions']['messages'] as $error) {
                        $errors[] = $error;
                    }
                }
            }
        }

        return $errors;
    }

    protected function get_credentials() {
        global $wpdb;

        $results = $wpdb->get_results( 
            $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . PROPELLER_SETTINGS_TABLE. " WHERE id = %d", 1) 
        );

        if (sizeof($results)) {
            $this->endpoint = $results[0]->api_url;
            $this->key = $results[0]->api_key;

            if (!defined('PROPELLER_API_URL')) 
                define('PROPELLER_API_URL', $results[0]->api_url);

            if (!defined('PROPELLER_API_KEY')) 
                define('PROPELLER_API_KEY', $results[0]->api_key);

            if (!defined('PROPELLER_ANONYMOUS_USER')) 
                define('PROPELLER_ANONYMOUS_USER', (int) $results[0]->anonymous_user);

            if (!defined('PROPELLER_SITE_ID')) 
                define('PROPELLER_SITE_ID', (int) $results[0]->site_id);

            if (!defined('PROPELLER_BASE_CATALOG')) 
                define('PROPELLER_BASE_CATALOG', (int) $results[0]->catalog_root);

            if (!defined('PROPELLER_DEFAULT_CONTACT_PARENT')) 
                define('PROPELLER_DEFAULT_CONTACT_PARENT', (int) $results[0]->contact_root);

            if (!defined('PROPELLER_DEFAULT_CUSTOMER_PARENT')) 
                define('PROPELLER_DEFAULT_CUSTOMER_PARENT', (int) $results[0]->customer_root);
        }
    }

    public function log_error($message, $backtrace) {
        error_log($message);

        if (is_array($backtrace)) {
            foreach ($backtrace as $trace) {
                error_log($trace['file'] . ': ' . $trace['line'] . ', function: ' . $trace['function']);
            }
        }
    }
}