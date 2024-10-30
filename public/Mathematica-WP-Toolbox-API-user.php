<?php
class Mathematica_WP_Toolbox_API_User extends Mathematica_WP_Toolbox_API {

	/**
	 * User ID that is used to make requests to the Stackexchange API.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int       $user_id    User ID.
	 */
	private $user_id;
	
	/**
	 * Default expiration time for profile data.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      int       $profile_expiration    Expiration time in number of seconds.
	 */
	private $profile_expiration;
	
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
	 * @var      int       $questoins_expiration    Expiration time in number of seconds.
	 */
	private $questions_expiration;


	/**
	 * Set up user id and default expiration times.
	 *
	 * @since    1.0.0
	 * @param    int         $user_id   User ID.
	 * @param    array       $args      Default expiration times          
	 */	
	public function __construct( $user_id, $args = array() ) {
	
		$args = shortcode_atts(array(
			'profile_expiration' => WEEK_IN_SECONDS,
			'answers_expiration' => HOUR_IN_SECONDS,
			'questions_expiration' => HOUR_IN_SECONDS
		), $args);
		
		$this->user_id = $user_id;
		$this->profile_expiration = $args['profile_expiration'];
		$this->answers_expiration = $args['answers_expiration'];
		$this->questions_expiration = $args['questions_expiration'];
		
	}
	
	/**
	 * Request profile data.
	 *
	 * @since    1.0.0 
	 * @param      int          $expiration    Expiration time in seconds.          
	 */	
	public function get_profile( $expiration ) {
	
		$query = "http://api.stackexchange.com/2.2/users/{$this->user_id}?order=desc&sort=reputation&site=mathematica";
		$res = $this->evaluate_query( $query, $expiration );

		return $res[0];
	}
	
	
	/**
	 * Request questions.
	 *
	 * @since    1.0.0 
	 * @param      array        $parameters    A list of parameters and their values.
	 * @param      int          $expiration    Expiration time in seconds.          
	 */
	public function get_questions( $parameters = array('order'=>'desc','sort'=>'activity'), $expiration ) {

		$parameters = $this->filter_parameters( $parameters );

		$base = "http://api.stackexchange.com/2.2/users/{$this->user_id}/questions?site=mathematica&filter=!-*f(6t*Zc2R_";
		
		$query = $this->build_query( $base, $parameters );
		
		return $this->evaluate_query( $query, $expiration );
	}
	
	/**
	 * Request answers.
	 *
	 * @since    1.0.0
	 * @param      array        $parameters    A list of parameters and their values.
	 * @param      int          $expiration    Expiration time in seconds.          
	 */
	public function get_answers( $parameters = array('order'=>'desc','sort'=>'activity'), $expiration ) {
	
		$parameters = $this->filter_parameters( $parameters );

		$base = "http://api.stackexchange.com/2.2/users/{$this->user_id}/answers?site=mathematica&filter=!-*f(6t*Zc2R_";
		
		$query = $this->build_query( $base, $parameters );
		
		return $this->evaluate_query( $query, $expiration );
	}
	
}