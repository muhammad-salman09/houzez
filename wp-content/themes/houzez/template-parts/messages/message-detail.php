<?php
/**
 * Created by PhpStorm.
 * User: waqasriaz
 * Date: 08/12/16
 * Time: 8:13 PM
 */

global $wpdb, $current_user;

wp_get_current_user();
$current_user_id =  $current_user->ID;
$tabel = $wpdb->prefix . 'houzez_threads';
$thread_id = $_REQUEST['thread_id'];
$user_status = 'Offline';

if ( isset( $_GET['seen'] ) && $_GET['seen'] == 1 ) {
	houzez_update_message_status( $current_user_id, $thread_id );
}

$houzez_thread = $wpdb->get_row(
	"
	SELECT * 
	FROM $tabel
	WHERE id = $thread_id
	"
);

$tabel = $wpdb->prefix . 'houzez_thread_messages';
$houzez_messages = $wpdb->get_results(
	"
	SELECT * 
	FROM $tabel
	WHERE thread_id = $thread_id
	ORDER BY id DESC
	"
);

$thread_author = $houzez_thread->sender_id;

if ( $thread_author == $current_user_id ) {
	$thread_author = $houzez_thread->receiver_id;
} 

$thread_author_first_name  =  get_the_author_meta( 'first_name', $thread_author );
$thread_author_last_name  =  get_the_author_meta( 'last_name', $thread_author );
$thread_author_display_name = get_the_author_meta( 'display_name', $thread_author );
if( !empty($thread_author_first_name) && !empty($thread_author_last_name) ) {
	$thread_author_display_name = $thread_author_first_name.' '.$thread_author_last_name;
}

$user_custom_picture =  get_the_author_meta( 'fave_author_custom_picture' , $thread_author );

if ( empty( $user_custom_picture )) {
	$user_custom_picture = get_template_directory_uri().'/images/profile-avatar.png';
}

if ( houzez_is_user_online( $thread_author ) ) {
	$user_status = '<span class="text-primary">'.esc_html__('Online', 'houzez').'</span>';
}

?>
<div class="messages-area">

	<?php if ( isset( $_GET['success'] ) && $_GET['success'] == true ) { ?>
		<div class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<?php esc_html_e( 'The message has been sent.', 'houzez' ); ?>
		</div>
	<?php } ?>

	<?php if ( isset( $_GET['success'] ) && $_GET['success'] == false ) { ?>
		<div class="alert alert-error alert-dismissible" role="alert">
			<button type="button" class="close" data-hide="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<?php esc_html_e( 'Oopps some thing getting wrong, please try again!', 'houzez' ); ?>
		</div>
	<?php } ?>

	<div class="msg-to-agent-block">
		<div class="media">
			<div class="media-left">
				<a href="#" class="media-object">
					<img src="<?php echo esc_url( $user_custom_picture ); ?>" class="img-circle" alt="<?php echo $thread_author_display_name; ?>">
				</a>
			</div>
			<div class="media-body media-middle">
				<div class="msg-agent-left">
					<h4 class="agent-title"> <?php echo ucfirst( $thread_author_display_name ); ?> </h4>
					<p class="agent-company"><?php echo get_the_title( $houzez_thread->property_id ); ?></p>
				</div>
				<div class="msg-agent-status">
					<ul>
						<li><i class="fa fa-comments-o"></i>  <?php the_author_meta( 'fave_author_language', $thread_author ); ?> </li>
						<li><i class="fa fa-user"></i> <?php esc_html_e( 'status', 'houzez' ); ?>: <?php echo $user_status; ?> </li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="media msg-send-block">
		<div class="media-left">
			<div class="media-object">
				<?php

				$current_user_picture =  get_the_author_meta( 'fave_author_custom_picture' , $current_user_id );

				if ( empty( $current_user_picture )) {
					$current_user_picture = get_template_directory_uri().'/images/profile-avatar.png';
				}

				?>
				<img src="<?php echo $current_user_picture; ?>" class="img-circle" alt="<?php the_author_meta( 'display_name', $current_user_id ) ?>">
			</div>
		</div>
		<div class="media-body">
			<h4 class="media-heading"><?php esc_html_e( 'Reply Message', 'houzez' ); ?></h4>
			<form class="form-msg" method="post">
				<input type="hidden" name="start_thread_message_form_ajax"
					   value="<?php echo wp_create_nonce('start-thread-message-form-nonce'); ?>"/>
				<input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>"/>
				<input type="hidden" name="action" value="houzez_thread_message">
				<div class="msg-type-block">
					<div class="arrow"></div>
					<textarea name="message" rows="7" class="form-control" placeholder="<?php esc_html_e( 'Type your message here...', 'houzez' ); ?>"></textarea>
					<div class="msg-attachment-row">
						<div class="msg-attachment">
							<ul class="msg-attachment" id="property-thumbs-container">
								<li class="new-attach" id="thread-message-attachment">
									<div class="attach-icon new-attachment">
										<i class="fa fa-paperclip"></i>
									</div>
								</li>
							</ul>
						</div>
						<div id="plupload-container"></div>
						<div id="errors-log"></div>
					</div>
				</div>
				<div class="form-msg-btns">
					<button class="btn btn-cancel reset_thread_message_form"><?php esc_html_e( 'Cancel', 'houzez' ); ?></button>
					<button class="btn btn-primary start_thread_message_form"><?php esc_html_e( 'Send', 'houzez' ); ?></button>
				</div>
			</form>
		</div>
	</div>

	<div class="msgs-list">

		<?php foreach ( $houzez_messages AS $message ) { ?>
			<?php

			$message_class = 'msg-me';
			$message_author = $message->created_by;
			$message_author_name = ucfirst( $thread_author_display_name );
			$message_author_picture =  get_the_author_meta( 'fave_author_custom_picture' , $message_author );

			if ( $message_author == $current_user_id ) {
				$message_author_name = esc_html__( 'Me', 'houzez' );
				$message_class = '';
			}

			if ( empty( $message_author_picture )) {
				$message_author_picture = get_template_directory_uri().'/images/profile-avatar.png';
			}

			?>
			<div class="media <?php echo $message_class; ?>">
				<div class="media-left">
					<a href="#" class="media-object">
						<img src="<?php echo $message_author_picture; ?>" class="img-circle" alt="<?php echo $message_author_name; ?>">
					</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading"><?php echo $message_author_name; ?></h4>
					<div class="message_des"><?php echo $message->message; ?></div>
					<?php
					if ( !empty( $message->attachments ) ) {

						$attachments = unserialize( $message->attachments );

						if ( sizeof( $attachments ) ) {

							echo '<ul>';

							foreach ( $attachments AS $attachment ) {

								$attachment_url  = wp_get_attachment_url( $attachment );

								echo '<li> <a href="' . $attachment_url . '" target="_blank"> ' . get_the_title( $attachment ) . ' </a> </li>';
							}

							echo '</ul>';

						}

					}

					?>
					<p class="message-date">
						<span><i class="fa fa-clock-o"></i>  <?php echo date_i18n( get_option('time_format'), strtotime( $message->time ) ); ?> </span>
						<span><i class="fa fa-calendar"></i> <?php echo date_i18n( get_option('date_format'), strtotime( $message->time ) ); ?> </span>
					</p>
				</div>
			</div>
		<?php } ?>

	</div>

</div>