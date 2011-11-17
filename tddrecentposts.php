<?php
/*

Plugin Name: TDD Recent Posts
Version: 2
Plugin URI: http://tddewey.com/tdd-recent-posts-wordpress-plugin
Description: A recent-posts widget that displays a small amount of the post text
Licence: GPLv3
tags: widget,recent posts
Author: Taylor Dewey
Author URI: http://www.tddewey.com

*/

/*
This software is distributed under the following license:

GNU General Public License (GPL) version 3

http://www.gnu.org/licenses/gpl.html

    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License as published by

    the Free Software Foundation; either version 2 of the License, or

    (at your option) any later version.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


register_activation_hook( __FILE__, 'tdd_rp_install');

function tdd_rp_install() {
		//The old version of the plugin stored options in the database. We can unset them here since they're no longer needed.
		delete_option( 'tddrecentposts' );
}


add_action( 'widgets_init', 'tdd_rp_register_widget' );

function tdd_rp_register_widget() {
	register_widget( 'TDD_RP_Widget' );
}


class tdd_rp_widget extends WP_Widget {

	function tdd_rp_widget() {
		$widget_ops = array(
			'classname' => 'tdd_rp_widget',
			'description' => 'Displays a list of recent posts with excerpts'
			);
		
		$this->WP_Widget( 'tdd_rp_widget', 'TDD Recent Posts', $widget_ops );
	}

	function form($instance) {
		$defaults = array (
			'title' => 'Recent Posts',
			'returnnum' => 5, // Number of rows to return. Default is 5.
			'lengthof' => 50, // Number of characters in the returned excerpts.
			'truncate_excerpts' => null, // Truncate excerpts instead
			'ver' => 1.2,
			);
		
		$instance = wp_parse_args( (array) $instance, $defaults);
		$title = $instance['title'];
		$returnnum = $instance['returnnum'];
		$lengthof = $instance['lengthof'];
		$truncate_excerpts = $instance['truncate_excerpts'];
		?>
		
		<p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p>Show <input name="<?php echo $this->get_field_name( 'returnnum' ); ?>" type="text" maxlength="5" size="3" value="<?php echo esc_attr( $returnnum ); ?>" /> posts</p>
		
		<p>Truncate at: <input name="<?php echo $this->get_field_name( 'lengthof' ); ?>" type="text" maxlength="5" size="3" value="<?php echo esc_attr( $lengthof ); ?>" /> characters</p>
		
		<p>Truncate Excerpts <input name="<?php echo $this->get_field_name( 'truncate_excerpts' ); ?>" type="checkbox" <?php checked( $truncate_excerpts, 'on'); ?> /><br />
		<small>If checked, the post excerpt will be used (and possibly truncated) instead of the post content</small>
	
		<?php

	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['returnnum'] = strip_tags( $new_instance['returnnum'] );
		$instance['lengthof'] = strip_tags( $new_instance['lengthof'] );
		$instance['truncate_excerpts'] = strip_tags( $new_instance['truncate_excerpts'] );	
		
		return $instance;
	}
	
	function widget($args, $instance) {
		//displays the widget
		extract($args);
		echo $before_widget;
		$title = apply_filters( 'widget_title', $instance['title'] );
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }

		//Set-up a new WP_Query to return the number of posts we want...
		$tdd_rp_query = new WP_Query();
		$tdd_rp_query->query(array(
			'posts_per_page' => ( $instance['returnnum'] ) ? $instance['returnnum'] : -1,
			'post_type' => 'post',
			'post_status' => 'publish',
		));
	
		if ( $tdd_rp_query->have_posts() ): 
			echo '<ul>';
			while ( $tdd_rp_query->have_posts() ): $tdd_rp_query->the_post();
			?>
					<li>
						<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
						
						<?php
							echo ( $instance['truncate_excerpts'] ) ? tdd_rp_truncate_intelligently( strip_tags(get_the_excerpt()), $instance['lengthof'] ) : tdd_rp_truncate_intelligently( strip_tags(get_the_content()), $instance['lengthof'] );
						?>
					</li>
			<?php
			endwhile; 
			echo '</ul>';	
		else:
			echo 'No posts to display';	
		endif;
	
		echo $after_widget;
	}
	
}

/*
* Truncate text to a number of characters somewhat intellegently...
*
* Truncates text to a number of characters, but preserves the word. Strips ending punctuation. Will always err on the side of returning more than less.
* Props to alishahnovin at hotmail dot com who posted this at php.net
*
* @param	str		$text	Text to truncate
* @param	int		$numb	Number of characters to truncate to
* @param	str		$etc	Ending. Default is an ellipsis
* @return	str		Returns the truncated text

*/
function tdd_rp_truncate_intelligently($text,$numb,$etc = "&hellip;") {
	$text = html_entity_decode($text, ENT_QUOTES);
		if (strlen($text) > $numb) {
			$text = substr($text, 0, $numb);
			$text = substr($text,0,strrpos($text," "));
			
			$punctuation = ".!?:;,-"; //punctuation you want removed
			
			$text = (strspn(strrev($text),  $punctuation)!=0) ? substr($text, 0, -strspn(strrev($text),  $punctuation)) : $text;

			$text = htmlentities($text, ENT_QUOTES);
			
			$text = $text.$etc;
		}

	return $text;
}
?>