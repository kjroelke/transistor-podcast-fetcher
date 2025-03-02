<?php
/**
 * Class: API
 * The class that handles the actual API calls
 *
 * @package KJR_Dev
 */

namespace KJR_Dev\Podcast_API\Api;

use Exception;
use WP_Error;

/** API Class
 * Handles getting the Transistor data from their endpoint/
 */
class API {
	/**
	 * The Base Transistor API endpoint
	 *
	 * @var $base_url
	 */
	protected string $base_url = 'https://api.transistor.fm/v1';

	/**
	 * The Transistor Show ID
	 *
	 * @var $show_id
	 */
	protected int $show_id;

	/**
	 * The Transistor API Key
	 *
	 * @var $api_key
	 */
	protected string $api_key;

	/**
	 * Constructor
	 *
	 * @param string $api_key The Transistor API Key
	 * @param string $show_name The Show Name
	 */
	public function __construct( string $api_key, string $show_name ) {
		$this->api_key = $api_key;
		$this->show_id = $this->fetch_show_id( $show_name );
	}

	/**
	 * Fetches the Show ID
	 *
	 * @param string $show_name The Show Name
	 * @return int The Show ID
	 * @throws Exception If the API Key is not set or if something goes wrong.
	 */
	private function fetch_show_id( string $show_name ): int {
		if ( ! $this->api_key ) {
			throw new Exception( 'No API Key' );
		}
		$response = wp_remote_get(
			'https://api.transistor.fm/v1/shows?fields[show][]=title',
			array(
				'headers' => array(
					'x-api-key' => $this->api_key,
				),
			)
		);
		if ( is_wp_error( $response ) ) {
			throw new Exception( esc_textarea( $response->get_error_message() ) );
		}
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( isset( $data['data'] ) ) {
			foreach ( $data['data'] as $show ) {
				if ( $show['attributes']['title'] === $show_name ) {
					return intval( $show['id'] );
				}
			}
		}
		throw new Exception( 'Show not found' );
	}

	/**
	 * Gets the data.
	 *
	 * @param array $params Additional parameters to pass to the API
	 * @return array The Episodes Data
	 */
	protected function get_episode_data( array $params = array() ) {
		$transistor_endpoint = $this->base_url . '/episodes' . "?show_id={$this->show_id}";
		if ( ! empty( $params ) ) {
			$transistor_endpoint .= '&' . http_build_query( $params );
		}
		$response = wp_remote_get( $transistor_endpoint, array( 'headers' => array( 'x-api-key' => $this->api_key ) ) );
		if ( isset( $response['response']['message'] ) && 'OK' !== $response['response']['message'] ) {
			$response = new WP_Error( 'transistor_api', $response['response']['message'], $response['headers']['data'] );
		}
		if ( is_wp_error( $response ) ) {
			echo $response->get_error_message();
		} else {
			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			return $data;
		}
	}
}
