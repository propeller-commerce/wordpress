<?php

namespace Propeller\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client implements ClientInterface {

	/**
	 * Options
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor
	 *
	 * @param $options
	 */
	public function __construct( $options = [] ) {
		$this->options = wp_parse_args( $options, [
			'connect_timeout' => 60,
			'timeout'         => 60,
			'redirection'     => 3,
		] );
	}

	/**
	 * Handles a request.
	 *
	 * @param RequestInterface $request
	 *
	 * @return ResponseInterface
	 */
	protected function handle( RequestInterface $request ): ResponseInterface {
		$uri     = (string) $request->getUri();
		$args    = $this->prepareArgs( $request );
		$httpVer = $request->getProtocolVersion();

		$responseData = wp_remote_request( $uri, $args );

		$code    = wp_remote_retrieve_response_code( $responseData );
		$code    = is_numeric( $code ) ? (int) $code : 400;
		$reason  = wp_remote_retrieve_response_message( $responseData );
		$headers = wp_remote_retrieve_headers( $responseData );
		$headers = is_array( $headers ) ? $headers : iterator_to_array( $headers );
		$body    = wp_remote_retrieve_body( $responseData );

		return new Response( $code, $headers, $body, $httpVer, $reason );
	}

	/**
	 * Prepares the args array for a specific request. The result can be used with WordPress' remote functions.
	 *
	 * @param RequestInterface $request The request.
	 *
	 * @return array<string, mixed> The prepared args array.
	 *
	 * @psalm-return array
	 */
	protected function prepareArgs( RequestInterface $request ): array {
		return array_merge( $this->options, [
			'method'      => $request->getMethod(),
			'httpversion' => $request->getProtocolVersion(),
			'headers'     => $this->prepareHeaders( $request ),
			'body'        => (string) $request->getBody(),
		] );
	}

	/**
	 * Transforms a request's headers into the format expected by WordPress' remote functions.
	 *
	 * @param RequestInterface $request The request.
	 *
	 * @return array<string, string> The prepared headers array.
	 */
	protected function prepareHeaders( RequestInterface $request ): array {
		$headers = [];

		foreach ( $request->getHeaders() as $header => $values ) {
			$headers[ $header ] = $request->getHeaderLine( $header );
		}

		return $headers;
	}

	/**
	 * Sends a PSR-7 request and returns a PSR-7 response.
	 *
	 * @param RequestInterface $request
	 *
	 * @return ResponseInterface
	 *
	 * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
	 */
	public function sendRequest( RequestInterface $request ): ResponseInterface {
		return $this->handle( $request );
	}
}