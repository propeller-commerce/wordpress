<?php

namespace Propeller\Includes\Controller;

use GraphQL\RawObject;
use Propeller\Includes\Enum\EmailEventTypes;

class EmailController extends BaseController {
    protected $model;

    public function __construct() {
        parent::__construct();

        $this->model = $this->load_model('email'); 
    }

    public function send_propeller_email($to, $from, $subject, $content, $email_type = EmailEventTypes::TRANSACTIONAL, $args = [], $attachments = [], $vars = []) {
        $type = 'publishEmailEvent';

        $raw_args = [];

        $raw_args[] = 'subject: "' . $subject . '"';
        $raw_args[] = 'content: "' . $content . '"';
        $raw_args[] = $from;
        $raw_args[] = $to;

        $raw_args[] = 'siteId: ' . PROPELLER_SITE_ID;
        $raw_args[] = 'type: ' . $email_type;

        if (isset($args['orderId'])) $raw_args[] = 'orderId: ' . $args['orderId'];
        if (isset($args['userId'])) $raw_args[] = 'userId: ' . $args['userId'];
        if (isset($args['letterId'])) $raw_args[] = 'letterId: ' . $args['letterId'];
        if (isset($args['language'])) $raw_args[] = 'language: ' . $args['language'];
        
        if (is_array($attachments) && count($attachments))
            $raw_args[] = 'attachments: { ' . new RawObject(json_encode($attachments)) . ' }';

        $raw_args[] = 'variables: { ' . $vars . ' } ';

        $raw_params = '{ ' . implode(',', $raw_args) . ' }';

        $gql = $this->model->send_propeller_email(['input' => new RawObject($raw_params)]);

        return $this->query($gql, $type);
    }

    public static function send_wp_email($to, $from, $subject, $content, $cc = '', $bcc = '', $attachments = []) {
        $headers = [];

        $headers[] = "From: $from";

        if (!empty($cc)) $headers[] = "Cc: $cc";
        if (!empty($bcc)) $headers[] = "Bcc: $bcc";
        
        return wp_mail($to, $subject, $content, $headers, $attachments);
    }
}