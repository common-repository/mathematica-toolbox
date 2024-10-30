(function( $ ) {
	'use strict';

	$( '#mathematica-wp-toolbox-shortcode-cdf' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[WolframCDF source="" width="320" height="415" altimage="" altimagewidth="" altimageheight=""]' );
	} );

	$( '#mathematica-wp-toolbox-shortcode-api' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[WolframCloudAPI id=""]' );
	} );
	
	$( '#mathematica-wp-toolbox-shortcode-wlembedded' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[wlcode]code[/wlcode]' );
	} );
	
	$( '#mathematica-wp-toolbox-shortcode-wlfield' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[wlcode field=""]' );
	} );
	
	$( '#mathematica-wp-toolbox-shortcode-wlinline' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[wlinline]' );
	} );
	
	$( '#mathematica-wp-toolbox-shortcode-wldoc' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[wldoc]function[/wldoc]' );
	} );

	$( '#mathematica-wp-toolbox-shortcode-mma-se-username' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[mma_se_user user_id=""]' );
	} );
	
	$( '#mathematica-wp-toolbox-shortcode-mma-se-user-questions' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[mma_se_user_questions user_id=""]' );
	} );
	
	$( '#mathematica-wp-toolbox-shortcode-mma-se-user-answers' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[mma_se_user_answers user_id=""]' );
	} );
	
	$( '#mathematica-wp-toolbox-shortcode-mma-se-questions' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[mma_se_questions ids=""]' );
	} );
	
	$( '#mathematica-wp-toolbox-shortcode-mma-se-answers' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[mma_se_answers ids=""]' );
	} );
	
	$( '#mathematica-wp-toolbox-shortcode-mma-se-profile-box' ).click( function( event ) {

		event.preventDefault();

		send_to_editor( '[mma_se_profile_box user_id=""]' );
	} );

})( jQuery );