<?php

// exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * User Groups Widget
 */
class BP_MY_GROUP extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$widget_ops = array(
			'description' => __( 'A Dynamic groups on tab ' ),
		);

		parent::__construct( false, _x( 'Custom bp Widget', 'widget name', 'bp-extended-custom-widget' ), $widget_ops );
	}

	/**
	 * Display widget content.
	 *
	 * @param array $args args.
	 * @param array $instance widget instance.
	 */
	public function widget( $args, $instance ) {

		$user_id = get_current_user_id();

		// don't show to non logged in user if is not BuddyPress user page.
		$defaults = array(
			'title'     => __( 'Your Groups', 'bp-extended-custom-widget' ),
			'groups_of' => 'displayed',
			'list_type' => 'member',
			'type'      => 'newest',
			'order'     => 'ASC',
			'limit'     => 5,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		if ( $instance['groups_of'] == 'loggedin' ) {
			if ( ! $user_id ) {
				return;
			}

			$user_id = $user_id;

		} elseif ( $instance['groups_of'] == 'displayed' ) {
			$user_id = bp_displayed_user_id();

			if ( ! $user_id ) {
				return;
			}
		}
       
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		echo $args['before_title'] . $title . $args['after_title'];

		$group_args = array(
			'user_id'  => $user_id,
			'type'     => $instance['type'],
			'group_type' => $instance['groups_type'],
			//'order'    => $instance['order'],
			'per_page' => $instance['limit'],
			'max'      => $instance['limit'],
		);
        

		// modify list for groups  when we need t list all groups of which the current user is admin.
		if ( $instance['list_type'] == 'admin' ) {

			unset( $group_args['user_id'] );

			$groups    = BP_Groups_Member::get_is_admin_of( $user_id, $instance['limit'] );
			$groups    = $groups['groups'];
			$group_ids = wp_list_pluck( $groups, 'id' );

			if ( empty( $group_ids ) ) {
				$group_ids = array( 0, 0 );
			}

			$group_args['include'] = $group_ids;

		}
		// $group_args['show_hidden'] = true; // show hidden groups too.
		// $group_args['user_id']     = $user_id;
		$rand=rand(10,100);
		?>
		<div class="item-options">
			<div class="tab_bp_css">
				<div id="material-tabs<?php echo $rand;?>" class="material-tabs my_groups">
						<a id="tab1-tab<?php echo $rand;?>" href="#tab1<?php echo $rand;?>" class="tab1-tab">Newest</a>
						<a id="tab2-tab<?php echo $rand;?>" href="#tab2<?php echo $rand;?>" class="tab2-tab active">Active</a>
						<a id="tab3-tab<?php echo $rand;?>" href="#tab3<?php echo $rand;?>" class="tab3-tab">Popular</a>
						<!-- <span class="yellow-bar"></span> -->
				</div>
			</div>
			<div class="tab-content">
				<div id="tab1<?php echo $rand;?>">
					<?php if ( bp_has_groups( $group_args ) ) : ?>
							<!--bp_is_user_groups( $group_args )-->
			            	<ul id="extended-groups-list<?php echo $rand;?>" class="item-list">

								<?php while ( bp_groups() ) : bp_the_group(); ?>

					                    <li <?php bp_group_class( array( 'bp-extended-custom-widget-item bp-extended-groups-clearfix' ) ); ?>>

					                        <div class="item-avatar">

					                            <a href="<?php bp_group_permalink() ?>"
					                               title="<?php bp_group_name() ?>"><?php bp_group_avatar_thumb() ?></a>

					                        </div>

					                        <div class="item">

					                            <div class="item-title">

					                                <a href="<?php bp_group_permalink() ?>"
					                                   title="<?php bp_group_name() ?>"><?php bp_group_name() ?></a>

					                            </div>

					                            <div class="item-meta">
					                                                 
													<span class="activity">
														<?php
														
															printf( __( 'created %s', 'bp-extended-custom-widget' ), bp_get_group_date_created() );
														
														?>
													</span>

					                            </div>

					                        </div>

					                    </li>

									<?php endwhile; ?>

					        </ul>
                            
                            

							<?php wp_nonce_field( 'groups_widget_groups_list', '_wpnonce-groups' ); ?>

			            	<input type="hidden" name="groups_widget_max" id="groups_widget_max"
			                   	value="<?php echo esc_attr( $instance['limit'] ); ?>"/>
                           	
                            <div class="more-block"><a href="<?php bp_groups_directory_permalink(); ?>" class="count-more"><?php _e( 'More', 'buddyboss' ); ?><i class="bb-icon-angle-right"></i></a></div>

						<?php else: ?>

			            		<div class="widget-error">

								<?php _e( 'There are no groups to display.', 'bp-extended-custom-widget' ) ?>

			            		</div>

					<?php endif; ?>
				</div>
				<div id="tab2<?php echo $rand;?>">
					<?php
						$defaults = array(
							'title'     => __( 'Your Groups', 'bp-extended-custom-widget' ),
							'groups_of' => 'loggedin',
							'list_type' => 'member',
							'type'      => 'active',
							'order'     => 'ASC',
							'limit'     => 5,
						);

						$instance = wp_parse_args( (array) $instance, $defaults );
						$group_args = array(
							'user_id'  => $user_id,
							'type'     => 'active',
							'group_type' => $instance['groups_type'],
							'order'    => $instance['order'],
							'per_page' => $instance['limit'],
							'max'      => $instance['limit'],
						);
						if ( $instance['list_type'] == 'admin' ) {

						unset( $group_args['user_id'] );

						$groups    = BP_Groups_Member::get_is_admin_of( $user_id, $instance['limit'] );
						$groups    = $groups['groups'];
						$group_ids = wp_list_pluck( $groups, 'id' );

						if ( empty( $group_ids ) ) {
							$group_ids = array( 0, 0 );
						}

						$group_args['include'] = $group_ids;

						}
                        
                        if ( $instance['list_type'] == 'member' ) {

							unset( $group_args['user_id'] );

							$group_ids = groups_get_user_groups($user_id);

							if ( empty( $group_ids ) ) {
								$group_ids = array( 0, 0 );
							}

							$group_args['include'] = $group_ids;

						}
						// $group_args['show_hidden'] = true; // show hidden groups too.
						// $group_args['user_id']     = get_current_user_id();
					?>
					<?php if ( bp_has_groups( $group_args ) ) : ?>

			            	<ul id="extended-groups-list<?php echo $rand;?>" class="item-list">

								<?php while ( bp_groups() ) : bp_the_group(); ?>
									<?php //if(is_user_a_member_of_this_group()):?>
					                    <li <?php bp_group_class( array( 'bp-extended-custom-widget-item bp-extended-groups-clearfix' ) ); ?>>

					                        <div class="item-avatar">

					                            <a href="<?php bp_group_permalink() ?>"
					                               title="<?php bp_group_name() ?>"><?php bp_group_avatar_thumb() ?></a>

					                        </div>

					                        <div class="item">

					                            <div class="item-title">

					                                <a href="<?php bp_group_permalink() ?>"
					                                   title="<?php bp_group_name() ?>"><?php bp_group_name() ?></a>

					                            </div>

					                            <div class="item-meta">
					                                                 
													<span class="activity">
														<?php
														
															printf( __( 'active %s', 'bp-extended-custom-widget' ), bp_get_group_last_active() );
														
														?>
													</span>

					                            </div>

					                        </div>

					                    </li>
					                  <?php //endif;?>
									<?php endwhile; ?>

					        </ul>

							<?php wp_nonce_field( 'groups_widget_groups_list', '_wpnonce-groups' ); ?>

			            	<input type="hidden" name="groups_widget_max" id="groups_widget_max"
			                   	value="<?php echo esc_attr( $instance['limit'] ); ?>"/>

						<?php else: ?>

			            		<div class="widget-error">

								<?php _e( 'There are no groups to display.', 'bp-extended-custom-widget' ) ?>

			            		</div>

					<?php endif; ?>
				</div>
				<div id="tab3<?php echo $rand;?>">
					<?php
						$defaults = array(
							'title'     => __( 'Your Groups', 'bp-extended-custom-widget' ),
							'groups_of' => 'loggedin',
							'list_type' => 'member',
							'type'      => 'popular',
							'order'     => 'ASC',
							'limit'     => 5,
						);
						$instance = wp_parse_args( (array) $instance, $defaults );
						$group_args = array(
							'user_id'  => $user_id,
							'type'     => 'popular',
							'group_type' => $instance['groups_type'],
							'order'    => $instance['order'],
							'per_page' => $instance['limit'],
							'max'      => $instance['limit'],
						);

						if ( $instance['list_type'] == 'admin' ) {

							unset( $group_args['user_id'] );

							$groups    = BP_Groups_Member::get_is_admin_of( $user_id, $instance['limit'] );
							$groups    = $groups['groups'];
							$group_ids = wp_list_pluck( $groups, 'id' );

							if ( empty( $group_ids ) ) {
								$group_ids = array( 0, 0 );
							}

							$group_args['include'] = $group_ids;

						}
						// $group_args['show_hidden'] = true; // show hidden groups too.
						// $group_args['user_id']     = get_current_user_id();
					?>
					<?php if ( bp_has_groups( $group_args ) ) : ?>

			            	<ul id="extended-groups-list<?php echo $rand;?>" class="extended-groups-list item-list">

								<?php while ( bp_groups() ) : bp_the_group(); ?>
									<?php //if(is_user_a_member_of_this_group()):?>
					                    <li <?php bp_group_class( array( 'bp-extended-custom-widget-item bp-extended-groups-clearfix' ) ); ?>>

					                        <div class="item-avatar">

					                            <a href="<?php bp_group_permalink() ?>"
					                               title="<?php bp_group_name() ?>"><?php bp_group_avatar_thumb() ?></a>

					                        </div>

					                        <div class="item">

					                            <div class="item-title">

					                                <a href="<?php bp_group_permalink() ?>"
					                                   title="<?php bp_group_name() ?>"><?php bp_group_name() ?></a>

					                            </div>

					                            <div class="item-meta">
					                                                 
													<span class="activity">
														<?php
														
															bp_group_member_count();
														
														?>
													</span>

					                            </div>

					                        </div>

					                    </li>
					                  <?php //endif;?>
									<?php endwhile; ?>

					        </ul>

							<?php wp_nonce_field( 'groups_widget_groups_list', '_wpnonce-groups' ); ?>

			            	<input type="hidden" name="groups_widget_max" id="groups_widget_max"
			                   	value="<?php echo esc_attr( $instance['limit'] ); ?>"/>

						<?php else: ?>

			            		<div class="widget-error">

								<?php _e( 'There are no groups to display.', 'bp-extended-custom-widget' ) ?>

			            		</div>

					<?php endif; ?>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			var ran="<?php echo $rand;?>";
			console.log(ran);
			jQuery(document).ready(function() {
            	
            	
				jQuery('.material-tabs').each(function() {

						var $active, $content, $links = jQuery(this).find('a');

						$active = jQuery($links[0]);
						$active.addClass('active');

						$content = jQuery($active[0].hash);

						$links.not($active).each(function() {
								jQuery(this.hash).hide();
						});

						jQuery(this).on('click', 'a', function(e) {

								$active.removeClass('active');
								$content.hide();

								$active = jQuery(this);
								$content = jQuery(this.hash);

								$active.addClass('active');
								$content.show();

								e.preventDefault();
						});
				});
                jQuery(".my_groups .tab2-tab").click();
			});
		</script>
        <style type="text/css">
            .bp-extended-groups-clearfix:after {
                content: "";
                display: table;
                clear: both;
            }
            li.bp-extended-custom-widget-item .item-avatar {
			    width: 20%;
			    float: left;
			    text-align: left !important;
			}
			li.bp-extended-custom-widget-item {
			    width: 100% !important;
			    border: none !important;
			    margin-left: 0 !important;
			    margin-right: 0 !important;
			}
			li.bp-extended-custom-widget-item .item-avatar img
			{
				border-radius: 4px !important;
			}
			li.bp-extended-custom-widget-item .item {
			    width: 76%;
			    float: left;
			  /*  line-height: 2.5em;*/
			}
			li.bp-extended-custom-widget-item .item-avatar
			{
				margin: 0 !important;
			}
            #extended-groups-list {
                list-style: none;
                margin-left: 0;
            }
            .extended-groups-list
            {
            	list-style: none;
                margin-left: 0;
            }
            /*.tab-content {
				padding:25px;
			}*/
			.tab_bp_css
			{
				position: relative;
			}
			.hide {
					display: none;
			}

			.material-tabs {
					position: relative;
					display: block;
				  padding:0;
					border-bottom: 1px solid #e0e0e0;
			}

			.material-tabs>a {
					position: relative;
					text-decoration: none;
					font-size: .875rem;
				    display: inline-block;
				    padding-bottom: 10px;
				    margin-right: 1.25rem;
				    margin-top: 10px;
					color: #939597;
					text-align: center;
					outline:;
			}

			.material-tabs>a.active {
					font-weight: 700;
					outline:none;
					border-bottom: 1px solid;
			    border-bottom-color: #086598;
			    color: #122B46;
			}

			.material-tabs>a:not(.active):hover {
					background-color: inherit;
					color: #283e4a;
			}
			li.bp-extended-custom-widget-item .item-title a
			{
				font-size: .9375rem;
			    font-weight: 500;
			    letter-spacing: -.24px;
			}

			@media only screen and (max-width: 520px) {
					.nav-tabs#material-tabs>li>a {
							font-size: 11px;
					}
			}

			.yellow-bar {
					position: absolute;
					z-index: 10;
					bottom: 0;
					height: 3px;
					background: #458CFF;
					display: block;
					left: 0;
					transition: left .2s ease;
					-webkit-transition: left .2s ease;
			}

			.tab1-tab.active ~ span.yellow-bar {
					left: 0;
					width: 160px;
			}

			.tab2-tab.active ~ span.yellow-bar {
					left:165px;
					width: 82px;
			}
			.tab3-tab.active ~ span.yellow-bar {
					left:165px;
					width: 82px;
			}
			li.bp-extended-custom-widget-item .item-meta .activity {
			    display: block !important;
			    line-height: 1.8em;
			}
			li.bp-extended-custom-widget-item .item-meta .item-title{
				line-height: 1.6em;
			}
        </style>

		<?php echo $args['after_widget']; ?>

		<?php

	}

	/**
	 * Update widget settings.
	 *
	 * @param array $new_instance widget settings.
	 * @param array $old_instance widget settings.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance              = $old_instance;
		$instance['title']     = strip_tags( $new_instance['title'] );
		$instance['groups_of'] = strip_tags( $new_instance['groups_of'] );
		$instance['type']      = strip_tags( $new_instance['type'] );
		$instance['groups_type']      = strip_tags( $new_instance['groups_type'] );
		$instance['order']     = strip_tags( $new_instance['order'] );
		$instance['limit']     = strip_tags( $new_instance['limit'] );
		$instance['list_type'] = $new_instance['list_type'];

		return $instance;

	}

	/**
     * Show widget form.
     *
	 * @param array $instance instance.
	 */
	public function form( $instance ) {

		$defaults = array(
			'title'     => __( 'Your Groups', 'bp-extended-custom-widget' ),
			'groups_of' => 'loggedin',
			'list_type' => 'member',
			'type'      => 'active',
			'groups_type' => '',
			'order'     => 'ASC',
			'limit'     => 5,
		);
        
        $query = new WP_Query([
            'post_per_page' => -1,
            'post_type'     => 'bp-group-type',
            'post_status'   => 'publish',
            //'fields'        => 'ids',
            'orderby'       => 'menu_order'
        ]);
        $group_types = $query->posts;
        

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title     = strip_tags( $instance['title'] );
		$groups_of = strip_tags( $instance['groups_of'] );
		$limit     = strip_tags( $instance['limit'] );
		$type      = strip_tags( $instance['type'] );
		$groups_type = strip_tags( $instance['groups_type'] );
		$order     = strip_tags( $instance['order'] );
		$list_type = $instance['list_type'];

		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php _e( 'Title:', 'bp-extended-custom-widget' ); ?>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>" style="width: 100%"/>
            </label>
        </p>
        
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'groups_type' ) ); ?>">
				<?php _e( 'Group Type: ', 'bp-extended-user-groups-widget' ); ?>
            </label>
            <label>
                <select name="<?php echo esc_attr( $this->get_field_name( 'groups_type' ) ); ?>">
                	<option value="" <?php selected( '', $groups_type, true ) ?> ><?php _e( 'All Types', 'buddyboss' ); ?></option><?php
					foreach ( $group_types as $group_type ) {
                        ?>
						<option value="<?php echo $group_type->post_name; ?>" <?php selected(  $group_type->post_name , $groups_type, true ) ?> ><?php echo $group_type->post_title; ?></option><?php
					}
					?>
                </select>
                
            </label>
        </p>

      <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'groups_of' ) ); ?>">
				<?php _e( 'List Groups of: ', 'bp-extended-custom-widget' ); ?>
            </label>
            <label>
                <input name="<?php echo esc_attr( $this->get_field_name( 'groups_of' ) ); ?>" type="radio" value="loggedin" <?php checked( $groups_of, 'loggedin' ); ?> />
                <?php _e( 'LoggedIn User', 'bp-extended-custom-widget' ); ?>
            </label>
            <label>
                <input name="<?php echo esc_attr( $this->get_field_name( 'groups_of' ) ); ?>" type="radio" value="displayed" <?php checked( $groups_of, 'displayed' ); ?> />
                <?php _e( 'Displayed User', 'bp-extended-custom-widget' ); ?>
            </label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'list_type' ) ); ?>">
				<?php _e( 'In Group User is: ', 'bp-extended-custom-widget' ); ?>
            </label>
            <label>
                <input name="<?php echo esc_attr( $this->get_field_name( 'list_type' ) ); ?>" type="radio" value="member" <?php checked( $list_type, 'member' ); ?> />
                <?php _e( 'Member', 'bp-extended-custom-widget' ); ?>
            </label>
            <label>
                <input name="<?php echo $this->get_field_name( 'list_type' ); ?>" type="radio" value="admin" <?php checked( $list_type, 'admin' ); ?> />
                <?php _e( 'Admin', 'bp-extended-custom-widget' ); ?>
            </label>
        </p>
         <!-- 
        <p>
            <label for="<?php echo $this->get_field_id( 'type' ); ?>">
				<?php _e( 'List Type', 'bp-extended-custom-widget' ); ?>
            </label>
            <select name="<?php echo $this->get_field_name( 'type' ); ?>"
                    id="<?php echo $this->get_field_id( 'type' ); ?>">

                <option value="active" <?php selected( 'active', $type, true ) ?>>
					<?php _e( 'Most Recent Active', 'bp-extended-custom-widget' ) ?>
                </option>

                <option value="popular" <?php selected( 'popular', $type, true ) ?>>
					<?php _e( 'Most Popular', 'bp-extended-custom-widget' ) ?>
                </option>
                <option value="alphabetical" <?php selected( 'alphabetical', $type, true ) ?>>
					<?php _e( 'Alphabetical', 'bp-extended-custom-widget' ) ?>
                </option>
                <option value="newest" <?php selected( 'newest', $type, true ) ?>>
					<?php _e( 'New Groups', 'bp-extended-custom-widget' ) ?>
                </option>
                <option value="random" <?php selected( 'random', $type, true ) ?>>
					<?php _e( 'Random', 'bp-extended-custom-widget' ) ?>
                </option>

            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'order' ); ?>">
				<?php _e( 'Order', 'bp-extended-custom-widget' ); ?>
            </label>
            <select name="<?php echo $this->get_field_name( 'order' ); ?>"
                    id="<?php echo $this->get_field_id( 'order' ); ?>">
                <option value="ASC" <?php selected( 'ASC', $order, true ) ?>>
					<?php _e( 'Ascending Order', 'bp-extended-custom-widget' ) ?>
                </option>
                <option value="DESC" <?php selected( 'DESC', $order, true ) ?>>
					<?php _e( 'Descending Order', 'bp-extended-custom-widget' ) ?>
                </option>
            </select>
        </p>-->

        <p>
            <label for="<?php echo $this->get_field_id( 'limit' ); ?>">
				<?php _e( 'Limit Group To Show:', 'bp-extended-custom-widget' ); ?>
                <input id="<?php echo $this->get_field_name( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" style="width: 30%"/>
            </label>
        </p>
		<?php
	}

}
