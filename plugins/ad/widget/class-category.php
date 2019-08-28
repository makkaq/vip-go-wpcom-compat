<?php

namespace Sophos\Ad\Widget;


/**
 * Sophos ads based on category selection
 */
class Category extends \Sophos\Ad\Widget {


	/**
	 * Category ID of 'all categories'
	 */
	const CATEGORY_ALL = 0;


	/**
	 * Outputs the current widget instance
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Text widget instance.
	 */
	public function widget( $args, $instance ) {

		$categories = get_the_terms( $post->ID, 'category' );

		if ( is_wp_error( $categories ) ) {
			throw new \Exception( $categories->get_error_message() );
		}

		if ( false === $categories ) {
			echo null;
		}

		$ids      = array_merge( wp_list_pluck( $categories, 'term_id' ), array( self::CATEGORY_ALL ) );
		$matching = in_array( $instance[ self::CATEGORY_KEY ], $ids, true );

		if ( 0 === self::$_count && $matching ) {
			parent::widget( $args, $instance );

			self::$_count++;
		} else {
			echo null;
		}
	}


	/**
	 * Update settings for the current widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = parent::update( $new_instance, $old_instance );
		$instance[ self::CATEGORY_KEY ] = filter_var( $new_instance[ self::CATEGORY_KEY ], FILTER_VALIDATE_INT, array(
				'options'   => array(
				'default'   => 0,
				'min_range' => 0,
			),
		));

		return $instance;
	}


	/**
	 * Outputs the widget settings form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		// Output common fields
		parent::form( $instance );

		$instance = wp_parse_args( (array) $instance, array(
			self::CATEGORY_KEY => self::CATEGORY_ALL,
		));

		?>
		<p><?php wp_dropdown_categories([
			'orderby'         => 'name',
			'selected'        => $instance[ self::CATEGORY_KEY ],
			'show_option_all' => __( 'All categories' ),
			'hierarchical'    => 99,
			'name'            => $this->get_field_name( self::CATEGORY_KEY ),
			'id'              => $this->get_field_id( self::CATEGORY_KEY ),
		]); ?></p>
		<?php
	}
}
