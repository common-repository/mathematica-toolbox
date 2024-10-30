<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name and version, registers shortcodes,
 * and enqueues scripts and CSS styles.
 *
 * @package    Mathematica_WP_Toolbox
 * @subpackage Mathematica_WP_Toolbox/public
 * @author     Calle Ekdahl <calle@ekdahl.email>
 */
class Mathematica_WP_Toolbox_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register shortcodes.
	 *
	 * @since 1.0.0
	 */
	public function register_shortcodes() {	
		/**
		 * This shortcode prints prettify enabled <pre> markup
		 * with the code inserted. The code can either be code that
		 * is enclosed by the shortcode, or code that comes from a
		 * a custom post field specified by the "field" attribute.
		 */
		add_shortcode( 'wlcode', function( $atts, $content = '' ) {
			extract(shortcode_atts(array(
				'field' => '',
				'class' => '',
				'id' => ''
			), $atts ));

			$str = "<pre id=\"$id\" class=\"prettyprint lang-mma $class\"><code>";
			if( $content != '' ) {
				$str .= apply_filters( 'wlcode_render_pre', $content );
			}
			else {
				global $post;
				$str .= apply_filters( 'wlcode_render_pre', get_post_meta( $post->ID, $field, true ) );
			}
			$str .= '</code></pre>';
			
			if( $field == '' && $content == '' ) {
				$str = '<div class="wl-error">';
				$str .= __('Error: either [wl] must enclose code or the field attribute must have a value.');
				$str .= '</div>';
			}
			
			return $str;
		});
		
		/**
		 * This shortcode creates a link to the documentation for a specific
		 * function. The link is merely a good guess, but it works in most
		 * cases.
		 */
		 add_shortcode( 'wldoc', function( $atts, $content = null ) {
			extract(shortcode_atts(array(
				'class' => '',
				'id' => ''
			), $atts ));
		 
			 if( is_null($content) ) {
				 $str = '<span class="wl-error">';
				 $str .= __('Error: [wldoc] should enclose the function name.');
				 $str .= '</span>';
			 }
			 else {
				 $str = "<code id=\"$id\" class=\"wldoc $class\"><a href=\"http://reference.wolfram.com/language/ref/{$content}.html\">$content</a></code>";
			 }
			 
			 return $str;
		 });
		 
		 /**
		  * This shortcode generates the HTML and Javascript required to render
		  * the specified CDF in the document.
		  */
		 add_shortcode( 'WolframCDF', function( $atts, $content = '' ) {

			 if( !empty($content) ) {
				 return 'Wrong content'; // The syntax is not right. The user has to figure it out.
			 }
			 	 
			 extract(shortcode_atts(array(
			 	'source' => '',
			 	'width' => 320,
			 	'height' => 415,
			 	'altimage' => '',
			 	'altimagewidth' => '',
			 	'altimageheight' => ''
			 ), $atts ));

			if( $source == '' ) {
				return 'Wrong source';
			}

			$id = 'CDF_' . sha1(mt_rand());
			$default_image = plugin_dir_url( __FILE__ ) . 'images/default.png';
			
			if( $altimagewidth == '' ) {
				$altimagewidth = $width;
			}
			if( $altimageheight == '' ) {
				$altimageheight = $height;
			}

			ob_start(); ?>
			<div class="WolframCDF" style="display: block; text-align: center;">
				<div id="<?php echo $id; ?>" style="display: block; margin: 0 auto;">
						<a style="display:block; width:<?php echo $altimagewidth; ?>px; height:<?php echo $altimageheight; ?>px; background: transparent url('<?php echo ( $altimage == '' ) ? $default_image : $altimage; ?>') no-repeat center center; text-decoration: none; border: none;" href="http://www.wolfram.com/cdf-player"></a>
				</div>
				
				<script type="text/javascript">
			 		var WolframCDF = WolframCDF || new cdf_plugin();
			 		WolframCDF.addCDFObject("<?php echo $id; ?>", "<?php echo $source; ?>", <?php echo $width; ?>, <?php echo $height; ?>);
			 	</script>
			</div>
					 
		 <?php
		 return ob_get_clean();
		 });
		 
		 /**
		  * Shortcode to display a cached version of a username
		  * corresponding to a Stackexchange user id. Optionally
		  * link to that user's profile.
		  */
		 add_shortcode( 'mma_se_user', function( $atts, $content = '' ) {
			 if( $content != '' ) {
				 return; // The syntax is not right. The user has to figure it out.
			 }

			 extract(shortcode_atts(array(
			 	'user_id' => '',
			 	'link' => true,
			 	'expiration' => WEEK_IN_SECONDS
			 ), $atts ));
			 
			 $link = ($link === "false" || !$link) ? false : true;
			 
			 if( !is_numeric($user_id ) ) {
				 return 'not numeric';
			 }
			 
			 $APIHandler = new Mathematica_WP_Toolbox_API_User( $user_id );
			 $profile = $APIHandler->get_profile( $expiration );
			 $display_name = $profile['display_name'];
			 $profile_link = $profile['link'];
			 
			 
			 if( $display_name ) {
				 if( $link ) {
					 return "<a href='$profile_link' title=\"$display_name's user profile\" class='Mathematica-WP-Toolbox-display-name'>$display_name</a>";
				 }
				 else {
				 	return $display_name;
				 }
			 }
			 			 
		 });
		 
		 /**
		  * Shortcode to display a list of answers by a certain user
		  * on the Stackexchange network.
		  */
		 add_shortcode( 'mma_se_user_answers', function( $atts, $content = '' ) {
			 if( $content != '' ) {
				 return; // The syntax is not right. The user has to figure it out.
			 }

			 extract(shortcode_atts(array(
			 	'user_id' => '',
			 	'link' => true,
			 	'expiration' => DAY_IN_SECONDS
			 ), $atts ));
			 
			 $link = ($link === "false" || !$link) ? false : true;
			 
			 if( !is_numeric($user_id ) ) {
				 return;
			 }
			 
			 $APIHandler = new Mathematica_WP_Toolbox_API_User( $user_id );
			 $answers = $APIHandler->get_answers( $atts, $expiration );

			 $answers_str = "<ul class='Mathematica-WP-Toolbox-answers'>\n";
			 foreach( $answers as $answer ) {
			 
				 $answers_str .= "<li>";
			 	
				 if( $link ) {
	    			$answers_str .= "<a href='{$answer['link']}' title='{$answer['title']}' class='Mathematica-WP-Toolbox-answer'>{$answer['title']}</a>";
	    		 }
	    		 else {
	    		 	$answers_str .= $answer['title'];
	    		 }
	    		 
	    		 $answers_str .= "</li>\n";
			 }
			 			 
			 $answers_str .= "</ul>\n";
			 
			 return $answers_str;
		 });
		 
		 /**
		  * Shortcode to display a list of questions by a certain user
		  * on the Stackexchange network.
		  */
		 add_shortcode( 'mma_se_user_questions', function( $atts, $content = '' ) {
			 if( $content != '' ) {
				 return; // The syntax is not right. The user has to figure it out.
			 }
			 
			 extract(shortcode_atts(array(
			 	'user_id' => '',
			 	'link' => true,
			 	'expiration' => DAY_IN_SECONDS
			 ), $atts ));
			 
			 $link = ($link === "false" || !$link) ? false : true;
			 
			 if( !is_numeric($user_id ) ) {
				 return;
			 }
			 
			 $APIHandler = new Mathematica_WP_Toolbox_API_User( $user_id );
			 $questions = $APIHandler->get_questions( $atts, $expiration );

			 $questions_str = "<ul class='Mathematica-WP-Toolbox-answers'>\n";
			 foreach( $questions as $question ) {
			 
				 $questions_str .= "<li>";
			 	
				 if( $link ) {
	    			$questions_str .= "<a href='{$question['link']}' title='{$question['title']}' class='Mathematica-WP-Toolbox-question'>{$question['title']}</a>";
	    		 }
	    		 else {
	    		 	$questions_str .= $question['title'];
	    		 }
	    		 
	    		 $questions_str .= "</li>\n";
			 }
			 			 
			 $questions_str .= "</ul>\n";
			 
			 return $questions_str;
		 });
		 
		 /**
		  * Shortcode to display a list of questions
		  * from the Stackexchange network.
		  */
		 add_shortcode( 'mma_se_questions', function( $atts, $content = null ) {
			 if( $content != '' ) {
				 return; // The syntax is not right. The user has to figure it out.
			 }
			 
			 extract(shortcode_atts(array(
			 	'ids' => '',
			 	'link' => true,
			 	'single' => false,
			 	'expiration' => 4*WEEK_IN_SECONDS
			 ), $atts ));
			 
			 $single = ($single === "false" || !$single) ? false : true;
			 $link = ($link === "false") ? false : true;

			 $APIHandler = new Mathematica_WP_Toolbox_API_General();
			 $questions = $APIHandler->get_questions( $ids, $atts, $expiration );

			 if( $single ) {
				 $question = $questions[0];
				 return "<a href='{$question['link']}' title='{$question['title']}' class='Mathematica-WP-Toolbox-question'>{$question['title']}</a>";
			 }

			 $questions_str = "<ul class='Mathematica-WP-Toolbox-answers'>\n";
			 foreach( $questions as $question ) {
			 
				 $questions_str .= "<li>";
			 	
				 if( $link ) {
	    			$questions_str .= "<a href='{$question['link']}' title='{$question['title']}' class='Mathematica-WP-Toolbox-answer'>{$question['title']}</a>";
	    		 }
	    		 else {
	    		 	$questions_str .= $question['title'];
	    		 }
	    		 
	    		 $questions_str .= "</li>\n";
			 }
			 			 
			 $questions_str .= "</ul>\n";
			 
			 return $questions_str;
		 });
		 
		 /**
		  * Shortcode to display a list of answers
		  * from the Stackexchange network.
		  */
		 add_shortcode( 'mma_se_answers', function( $atts, $content = null ) {
			 if( $content != '' ) {
				 return; // The syntax is not right. The user has to figure it out.
			 }
			 
			 extract(shortcode_atts(array(
			 	'ids' => '',
			 	'link' => true,
			 	'single' => false,
			 	'expiration' => 4*WEEK_IN_SECONDS
			 ), $atts ));
			 
			 $single = ($single === "false" || !$single) ? false : true;
			 $link = ($link === "false") ? false : true;
			 
			 $atts['filter'] = '!-*f(6t*Zc2R_';
			 
			 $APIHandler = new Mathematica_WP_Toolbox_API_General();
			 $answers = $APIHandler->get_answers( $ids, $atts, $expiration );

			 if( $single ) {
				 $answer = $answers[0];
				 return "<a href='{$answer['link']}' title='{$answer['title']}' class='Mathematica-WP-Toolbox-answer'>{$answer['title']}</a>";
			 }

			 $answers_str = "<ul class='Mathematica-WP-Toolbox-answers'>\n";
			 foreach( $answers as $answer ) {
			 
				 $answers_str .= "<li>";
			 	
				 if( $link ) {
	    			$answers_str .= "<a href='{$answer['link']}' title='{$answer['title']}' class='Mathematica-WP-Toolbox-answer'>{$answer['title']}</a>";
	    		 }
	    		 else {
	    		 	$answers_str .= $answer['title'];
	    		 }
	    		 
	    		 $answers_str .= "</li>\n";
			 }
			 			 
			 $answers_str .= "</ul>\n";
			 
			 return $answers_str;
		 });
		 
		 /**
		  * Shortcode to display a box that shows the
		  * avatar, name, reputation and badges of a MMA.SE
		  * user with a given id.
		  */
		 add_shortcode( 'mma_se_profile_box', function( $atts, $content = null ) {
			 if( $content != '' ) {
				 return; // The syntax is not right. The user has to figure it out.
			 }
			 
			 extract(shortcode_atts(array(
			 	'user_id' => '',
			 	'expiration' => DAY_IN_SECONDS
			 ), $atts ));
			 
			 if( !is_numeric($user_id ) ) {
				 return;
			 }
			 
			 $APIHandler = new Mathematica_WP_Toolbox_API_User( $user_id );
			 $profile = $APIHandler->get_profile( $expiration );
			 
			 $display_name = $profile['display_name'];
			 $link = $profile['link'];
			 $profile_image = $profile['profile_image'];
			 $reputation = $profile['reputation'];
			 $gold = $profile['badge_counts']['gold'];
			 $silver = $profile['badge_counts']['silver'];
			 $bronze = $profile['badge_counts']['bronze'];
			 
			 ob_start(); ?>
<div class="mma-se-user"><div class="avatar"><a href="<?php echo $link; ?>"><img src="<?php echo $profile_image; ?>" alt="" width="164" height="164"></a></div><h2><?php echo $display_name; ?></h2><div class="mma-se-reputation"><?php echo $reputation; ?><span>Reputation</span></div><div class="mma-se-badges"><span class="mma-se-badge1"><span class="mma-se-symbol"></span><span class="mma-se-count"><?php echo $gold; ?></span>	</span><span class="mma-se-badge2"><span class="mma-se-symbol"></span><span class="mma-se-count"><?php echo $silver; ?></span></span><span class="mma-se-badge3"><span class="mma-se-symbol"></span><span class="mma-se-count"><?php echo $bronze; ?></span></span></div></div>
			 <?php
			 return ob_get_clean();
		});
		
		
		/**
		  * Shortcode that displays the result of the specified
		  * API call, otherwise displays whatever information the
		  * shortcode tag is encapsulating.
		  */
		  add_shortcode( 'WolframCloudAPI', function( $parameters, $content = '' ) {

			  if( !isset($parameters['image']) ) {
				  $parameters['image'] = true;
			  }
			  if( $parameters['image'] === "false" || !$parameters['image'] ) {
				  $parameters['image'] = false;
			  }
			  else {
				  $parameters['image'] = true;
			  }
			  
			  $query = "https://www.wolframcloud.com/objects/{$parameters['id']}?";
			  
			  if( !isset($parameters['id']) || $parameters['id'] == '' ) {
				  return 'The ID parameters is required.';
			  }
			  
			  foreach( $parameters as $parameter => $value ) {
				  
				  if( $parameter == 'id' || $parameter == 'image' ) {
					  continue;
				  }
				  
				  $query .= $parameter.'='.$value.'&';
				  
			  }
			  
			  $query = trim( $query, '&' );
			  
			  if( $parameters['image'] ) {
				return "<img src='$query'>";
			  }
			  
			  $response = wp_remote_get( $query );
			  $code = wp_remote_retrieve_response_code( $response );

			  if( $code == 200) {
				  return wp_remote_retrieve_body( $response );
			  }
			  else {
				  return $content;
			  }
			  
		  });
		  
		/**
		  * Shortcode that displays inline code
		  */
		  add_shortcode( 'wlinline', function( $parameters, $content = '') {
			  return '<code class="wl">' . $content . '</code>';
		  });
	}
	
	/**
	 * This filter replaces all WL glyph codes with the
	 * glyphs themselves in the passed string.
	 *
	 * @since 1.0.0
	 */
	public function replace_glyphs( $content ) {
		$new_content = $content;
		foreach( Mathematica_WP_Toolbox_Glyphs::$glyphs as $code => $glyph ) {
			$new_content = str_replace( $code, $glyph, $new_content );
		}
		 	
		return $new_content;		
	}
	
	/**
	 * This filter replaces curly quotes with regular
	 * quotes inside <pre>
	 *
	 * @since 1.0.0
	 */
	public function replace_curly_quotes( $content ) {
		return preg_replace_callback(
			'/\<pre(.+?)\>(.+?)\<\/pre\>/s',
			function( $matches ) {
				$replaced = str_replace( '&#8220;', '"', $matches[2] );
				$replaced = str_replace('&#8221;', '"', $replaced );
				return "<pre{$matches[1]}>$replaced</pre>";
			},
			$content
		);
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'Mathematica-WP-Toolbox-public', plugin_dir_url( __FILE__ ) . 'css/Mathematica-WP-Toolbox-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'prettify', plugin_dir_url( __FILE__ ) . 'css/prettify-mma.css', array(), $this->version, 'all' );
		//wp_enqueue_style( 'Mathematica-WP-Toolbox-public', plugin_dir_url( __FILE__ ) . 'css/minified.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'lang-mma', plugin_dir_url( __FILE__ ) . 'js/lang-mma.js', array( 'prettify' ), $this->version, true );
		wp_enqueue_script( 'prettify', plugin_dir_url( __FILE__ ) . 'js/prettify.js', array(), $this->version, true );
		wp_enqueue_script( 'cdfplugin', plugin_dir_url( __FILE__ ) . 'js/cdfplugin.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/Mathematica-WP-Toolbox-public.js', array( 'jquery' ), $this->version, true );
		//wp_enqueue_script( 'Mathematica-WP-Toolbox-public', plugin_dir_url( __FILE__ ) . 'js/minified.js', array( 'jquery' ), $this->version, true );
	}

}
