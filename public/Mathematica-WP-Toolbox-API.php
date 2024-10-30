<?php

/**
 * Provide an interface with automatic caching
 * for the Stackexchange API
 *
 * @since      1.0.0
 *
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/public
 */

/**
 * Provide interface for the Stackexchange API.
 *
 * Provide an interface for general requests to
 * the Stackexchange API which can evaluate these
 * requests or retrieve them from cached data if such
 * data exists. Create such cache data upon completing
 * requests.
 *
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/public
 * @author     Calle Ekdahl <calle@ekdahl.email>
 */
class Mathematica_WP_Toolbox_API {

	/**
	 * Evaluate a query by either sending it to the Stackexhange API or
	 * retrieving it from cached data. Cache data upon completing an
	 * uncached request.
	 *
	 * @since    1.0.0
	 * @param      string               $query             The API query.
	 * @param      int      Optional    $expiration        Number of seconds until the cache expires.
	 */				
	protected function evaluate_query( $query, $expiration ) {
	
		$transient_key = $this->get_transient_key( $query, $expiration );
		
		$data = get_transient( $transient_key );
		if( $data ) {
			return $data;
		}
		
		$response = wp_remote_get( $query );
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		
		if( array_key_exists( 'items', $data ) ) {
			$data = $data['items'];	
		}
		else {
			return array();
		}

		set_transient( $transient_key, $data, $expiration );
		$this->update_transient_keys( $transient_key );
		
		return $data;
	}
	
	/**
	 * Create a unique key corresponding to a certain query with a certain
	 * expiration, that can be used internally to identify the query.
	 *
	 * @since    1.0.0
	 * @param      string               $query             The API query.
	 * @param      int      Optional    $expiration        Number of seconds until the cache expires.
	 */
	protected function get_transient_key( $query, $expiration ) {
		return 'mma'.md5($query.$expiration);
	}
	
	/**
	 * Each queryin the cache are identified by a transient key. This
	 * function adds said key to a list, so that that list can be used
	 * to keep track of which queries have been cached.
	 *
	 * @since    1.0.0
	 * @param      string               $query             Transient key.
	 */
	private function update_transient_keys( $new_transient_key ) {
	  
		$transient_keys = get_option( 'mma_transient_keys' );
	
		$transient_keys[]= $new_transient_key;
	  
		update_option( 'mma_transient_keys', $transient_keys );
		
	}

	/**
	 * Remove parameters from a list of parameters if they do not
	 * appear in the hardcoded whitelist. The whitelist corresponds
	 * to arguments available in the Stackexchange API.
	 *
	 * @since    1.0.0
	 * @param      array               $query             The API query.
	 */
	protected function filter_parameters( $parameters ) {
	
		$allowed = array( 'page', 'pagesize', 'fromdate', 'todate', 'order', 'min', 'max', 'sort', 'filter' );
		$parameters = array_intersect_key( $parameters, array_flip( $allowed ) );
		
		return $parameters;
		
	}

	/**
	 * Build query from a base and a list of parameters.
	 *
	 * @since    1.0.0
	 * @param      string               $query             Base URL.
	 * @param      array                $expiration        List of parameters.
	 */
	protected function build_query( $base, $parameters ) {
	
		foreach( $parameters as $key => $value ) {
			$base .= '&'.$key.'='.$value;
		}
		
		return $base;
		
	}

	/**
	 * Delete all cached API query results.
	 *
	 * @since    1.0.0
	 */
	public function clear_cache() {
	
		$transient_keys = get_option( 'mma_transient_keys' );
	  
		foreach( $transient_keys as $t ) {
			delete_transient( $t );
		}

		update_option( 'mma_transient_keys', array() );
		
	} 
}
