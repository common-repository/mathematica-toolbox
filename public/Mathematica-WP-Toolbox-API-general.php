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
class Mathematica_WP_Toolbox_API_General extends Mathematica_WP_Toolbox_API {

	/**
	 * Default expiration time for answers.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int       $answers_expiration    Expiration time in number of seconds.
	 */
	private $answers_expiration;
	
	/**
	 * Default expiration time for questions.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int       $questions_expiration    Expiration time in number of seconds.
	 */
	private $questions_expiration;

	/**
	 * Set up default expiration times.
	 *
	 * @since    1.0.0
	 * @param    array       $args    Default expiration times          
	 */	
	public function __construct( $args = array() ) {
	
		$args = shortcode_atts(array(
			'answers_expiration' => 4*WEEK_IN_SECONDS,
			'questions_expiration' => 4*WEEK_IN_SECONDS
		), $args);
		
		$this->answers_expiration = $args['answers_expiration'];
		$this->questions_expiration = $args['questions_expiration'];
		
	}

	/**
	 * Request answers.
	 *
	 * @since    1.0.0
	 * @param      string       $ids           Semi-colon separated list of answer ids. 
	 * @param      array        $parameters    A list of parameters and their values.
	 * @param      int          $expiration    Expiration time in seconds.          
	 */
	public function get_answers( $ids, $parameters, $expiration ) {
	
		if( is_array( $ids ) ) {
			$ids = implode( ';', $ids );
		}

		$query = "http://api.stackexchange.com/2.2/answers/$ids?site=mathematica";
		$parameters = $this->filter_parameters( $parameters );
		
		$query = $this->build_query( $query, $parameters );
		
		return $this->evaluate_query( $query, $expiration );
	
	}
	
	/**
	 * Request questions.
	 *
	 * @since    1.0.0
	 * @param      string       $ids           Semi-colon separated list of question ids. 
	 * @param      array        $parameters    A list of parameters and their values.
	 * @param      int          $expiration    Expiration time in seconds.          
	 */
	public function get_questions( $ids, $parameters = array('order'=>'desc','sort'=>'activity'), $expiration ) {

		if( is_array( $ids ) ) {
			$ids = implode( ';', $ids );
		}
		
		$query = "http://api.stackexchange.com/2.2/questions/$ids?site=mathematica";
		$parameters = $this->filter_parameters( $parameters );
		
		$query = $this->build_query( $query, $parameters );
		
		return $this->evaluate_query( $query, $expiration );
		
	}

}
