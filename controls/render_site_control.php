<?php namespace cahnrswp\pagebuilder;

class render_site_control {
	
	public $layout_model;
	public $site_view;
	
	public function __construct(){
		$this->layout_model = new layout_model();
		$this->site_view = new site_view();
	}
	
	public function init(){
		\add_filter('pagebuilder_the_content', 'do_shortcode');
		\add_filter( 'pagebuilder_the_content', 'wptexturize'        );
		\add_filter( 'pagebuilder_the_content', 'convert_smilies'    );
		\add_filter( 'pagebuilder_the_content', 'convert_chars'      );
		\add_filter( 'pagebuilder_the_content', 'wpautop'            );
		\add_filter( 'pagebuilder_the_content', 'shortcode_unautop'  );
		\add_filter( 'pagebuilder_the_content', 'prepend_attachment' );
		
		
		 \add_filter( 'the_content', array( $this , 'render_site' ), 15 ); 
		 
		 \add_action( 'wp_enqueue_scripts', array( $this , 'add_scripts' ) );
	}
	
	public function render_site( $content ) {
		global $post; 
		if ( ( is_singular('post') || is_singular('page') ) && is_main_query() ) {
			$layout_obj = $this->layout_model->get_layout_obj( $post );
			ob_start();
			$this->site_view->get_site_view( $post , $layout_obj , $this->layout_model );
			$content = ob_get_clean();
		} 
		else if ( is_singular('email') ){
			$layout_obj = $this->layout_model->get_layout_obj( $post );
			ob_start();
			$this->site_view->get_email_view( $post , $layout_obj , $this->layout_model );
			$content = ob_get_clean();
		}
	
		return $content;
	}
	
	public function add_scripts(){
		wp_register_style( 'pagebuilder_css', URL . '/css/pagebuilder.css', false, '1.0.0' );
        wp_enqueue_style( 'pagebuilder_css' );
	}
	
};?>