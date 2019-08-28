<?php

namespace Sophos\Ad;


/**
 * Sophos Ad, based on Text Widget.
 */
class Widget extends \WP_Widget {


	/**
	 * CSS class name of widget
	 */
	const CLASS_NAME = 'sophos_widget_ad';


	/**
	 * Array key for category ID property
	 */
	const CATEGORY_KEY = 'sophos-ad-cat';


	/**
	 * Count the number of inline ads
	 * @var integer
	 */
	public static $_count = 0;


	/**
	 * Sets up a new Ad widget instance
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => self::CLASS_NAME,
			'description' => __( 'Inline advert', 'nakedsecurity' ),
		);

		parent::__construct( 'sophos_ad', __( 'Inline Ad', 'nakedsecurity' ), $widget_ops );
	}


	/**
	 * Outputs the current widget instance
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Text widget instance.
	 */
	public function widget( $args, $instance ) {

			$text = apply_filters( 'widget_text', $instance['text'] ?: '', $instance, $this );
			$ad   = ! empty( $instance['filter'] ) ? wpautop( $text ) : $text;
			$css  = array_key_exists( 'css', $instance ) && isset( $instance['css'] ) ? $instance['css'] : '';

			echo wp_kses( $args['before_widget'], $this->allowed_html(), [] ); ?>
				<style><?php echo wp_strip_all_tags( $instance['css'], true ); ?></style>
				<div class="<?php echo esc_attr( self::CLASS_NAME ); ?>"><?php echo wp_kses( $ad, $this->allowed_html(), [] ); ?></div>
			<?php echo wp_kses( $args['after_widget'], $this->allowed_html(), [] );
	}


	/**
	 * Update settings for the current widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['css']	= $new_instance['css'];
		$instance['filter'] = ! empty( $new_instance['filter'] );
		$instance['text']   = ( current_user_can( 'unfiltered_html' ) )
							? $instance['text'] = $new_instance['text']
							: $instance['text'] = wp_kses( $new_instance['text'], $this->allowed_html() );

		return $instance;
	}


	/**
	 * Outputs the widget settings form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, [
			'title' => '',
			'text'  => '',
			'css'	=> '',
		]);
		$filter = isset( $instance['filter'] ) ? $instance['filter'] : 0;
		$title  = sanitize_text_field( $instance['title'] );
		$css    = array_key_exists( 'css', $instance ) && isset( $instance['css'] ) ? $instance['css'] : '';

		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'css' ) ); ?>"><?php esc_html_e( 'CSS:' ); ?></label>
		<textarea class="widefat" rows="8" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'css' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'css' ) ); ?>"><?php echo esc_textarea( wp_strip_all_tags( $instance['css'], false ) ); ?></textarea></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Content:' ); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>
		<p><input id="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'filter' ) ); ?>" type="checkbox"<?php checked( $filter ); ?> />&nbsp;<label for="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>"><?php esc_html_e( 'Automatically add paragraphs' ); ?></label></p>
		<?php
	}


	protected function allowed_html() {
		return array_merge( wp_kses_allowed_html( 'post' ), [
			'style' => [],
			'a' 	=> [
				'href' 			=> [],
				'class' 		=> [],
				'data-ga-label' => [],
			],
		]);
	}
}
