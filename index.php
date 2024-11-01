<?php 
/*
Plugin Name: WP Team Members
Plugin URI: https://www.codingbank.com/item/wp-team-members-wordpress-plugins/
Description: This is full responsive cb team members plugin for wordpress websites with shortcode support. shortcode is [cb-members].
Version: 1.2
Author: Md Abul Bashar
Author URI: http://www.codingbank.com/

*/

function cb_team_members(){
	
	wp_enqueue_style( 'cb-members-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css', array(), '1.0' );
	wp_enqueue_style( 'cb-members-bootstrap', plugin_dir_url( __FILE__ ) . '/css/bootstrap.min.css', array(), '1.0' );
	wp_enqueue_style( 'cb-member-style',  plugin_dir_url( __FILE__ ) . '/css/style.css', array(), '1.0' );
	wp_enqueue_style( 'cb-member-responsive',  plugin_dir_url( __FILE__ ) . '/css/responsive.css', array(), '1.0' );
	
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'cb-member-modernizr',  plugin_dir_url( __FILE__ ) . '/js/modernizr-latest.js', array( 'jquery' ), '1.0', true );
	
	
}
add_action('wp_enqueue_scripts', 'cb_team_members');

function cb_member_thum(){
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-thumbnails', array( 'cb-team-member' ) );  
	add_image_size( 'cb_member_img', 300, 300, true );
	
}
add_action('after_setup_theme', 'cb_member_thum');





add_action( 'init', 'cb_member_custom_posts' );
function cb_member_custom_posts() {
	register_post_type( 'cb-team-member',
		array(
			'labels' => array(
				'name' => __( 'Team Members' ),
				'singular_name' => __( 'Team Member' ),
				'add_new_item' => __( 'Add New Member' )
			),
			'public' => true,
			'supports' => array('title', 'custom-fields', 'thumbnail', 'editor'),
			'has_archive' => true,
			'capability_type' => 'page',
			'rewrite' => array('slug' => 'cb-team-member'),
		)
	);
}



function cb_temmember_shortcode($atts){
	extract( shortcode_atts( array(
		'count' => 4,
		'posttype' => 'cb-team-member',
		'cb_member_title' => 'Our Powerful Team',
		'cb_column'	=> 3,		
	), $atts ) );
	
    $q = new WP_Query(
        array('posts_per_page' => $count, 'post_type' => $posttype)
        );		
		
		
	$list = '<section id="about" class="contain nav-link"><div class="about inner">	<div class="second-section"><div class="header" data-animation="slideInRight" data-animation-delay="0"> '.$cb_member_title.' </div><div class="row"><ul class="grid cs-style-1">';
	while($q->have_posts()) : $q->the_post();
		$idd = get_the_ID();
		$cb_members_img = wp_get_attachment_image_src( get_post_thumbnail_id( $idd ), 'cb_member_img' );
		$position = get_post_meta($idd, 'position', true);
		$facebook_url = get_post_meta($idd, 'facebook-url', true);
		$twitter_url = get_post_meta($idd, 'twitter-url', true);
		$github_url = get_post_meta($idd, 'github-url', true);
		$skype_user = get_post_meta($idd, 'skype-user', true);

		$list .= '
		
							<li class="col-md-'.$cb_column.' col-sm-6">
							<div class="figure">

								
								<img src="'.$cb_members_img[0].'" alt="'.get_the_title().'" />
								<div class="figcaption">
									<span>'.$position.'</span>
									<p>'.get_the_content() .'</p>									
									<a href="'.$twitter_url.'"><i class="fa fa-twitter"></i></a>
									<a href="'.$facebook_url.'"><i class="fa fa-facebook"></i></a>
									<a href="'.$github_url.'"><i class="fa fa-github"></i></a>
									<a href="'.$skype_user.'"><i class="fa fa-skype"></i></a>
								</div>
								<h4>'.get_the_title().'</h4>								
							</div>
						</li>	

		
		';        
	endwhile;
	$list.= '</ul></div></div></div></section>';
	wp_reset_query();
	return $list;
}
add_shortcode('cb-members', 'cb_temmember_shortcode');	



// Change Default Title Place holder in custom post
function cb_change_member_title( $title ){
	
     $screen = get_current_screen();		
     if  ( $screen->post_type == 'cb-team-member' ) {
          return 'Enter Your Member Name';
     } 	
	 else {
          return $title = 'Enter Your Title Here';
     }
}
 
add_filter( 'enter_title_here', 'cb_change_member_title' );


// Add settings link on plugin page
function cb_member_pluginlink($links) { 
  $settings_link = '<a href="http://code.realwebcare.com/item/wp-team-members-pro-wordpress-plugin/" style="color:red;font-weight:bold;" target="_blank">Premium Version</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'cb_member_pluginlink' );	


/*----------------------------------------------------------
Custom metabox
------------------------------------------------------------*/

function cb_faq_custom_metabox() {

	add_meta_box('cbteammemberpro', '<span style="color:red">Premium Version Available</span>', 'cb_member_ads_metabox_output', 'cb-team-member', 'side', 'high');

}
add_action('add_meta_boxes', 'cb_faq_custom_metabox');

function cb_member_ads_metabox_output(){
	

	echo '#Responsive<br/>#Custom Post with shortcode support<br/>#Add Socials Profiles<br/>#Member Position<br/>#Department<br/>And More only $5<br/> <a href="http://code.realwebcare.com/item/wp-team-members-pro-wordpress-plugin/" style="color:red;font-weight:bold;" target="_blank">Click Here</a> For Details<br/> You can <a href="http://code.realwebcare.com/item/cb-responsive-jquery-accordion-pro/" target="_blank" style="color:red;font-weight:bold;">Buy Now</a><br/><iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Fcodingbank&width=200px&layout=standard&action=like&size=small&show_faces=false&share=true&height=35&appId=242933392551977" width="200px" height="35" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>';

}

?>