<?php

$pluginUrl = plugins_url( '', dirname( __DIR__ ) );

?>
<div class="wrap" id="content">
	<?php if ( $options['active'] ) { ?>
		<div class="alert alert-warning gift">
			<img src="<?php echo esc_url( $pluginUrl ); ?>/images/gift.svg" alt="Gift icon">
			<span>
				<?php
				printf(
					/* translators: %1$s and %2$s are opening and closing anchor tags */
					esc_html__( 'Give us review on Wordpress.org and get 10€. %1$sRead more%2$s', 'smartsupp-live-chat' ),
					'<a href="https://www.smartsupp.com/help/give-us-review-wordpress-org/" target="_blank">',
					'</a>'
				);
				?>
			</span>
		</div>
		<div class="active">
			<header class="header">
				<img src="<?php echo esc_url( $pluginUrl ); ?>/images/logo.png" alt="smartsupp logo" class="header__logo" />
				<nav class="hide--up-md">
					<div class="header-user">
						<img src="<?php echo esc_url( $pluginUrl ); ?>/images/avatar-grey.png" alt="" class="header-user__avatar">
						<span class="header-user__email">
							<?php echo isset( $options['email'] ) ? esc_html( $options['email'] ) : ''; ?>
						</span>
						<a href="javascript: void(0);" data-nonce="<?php echo esc_attr( wp_create_nonce( 'smartsupp_disable' ) ); ?>" class="js-action-disable btn btn--sm btn-center">
							<?php esc_html_e( 'Deactivate Chat', 'smartsupp-live-chat' ); ?>
						</a>
					</div>
				</nav>
				<div class="navbar-toggle">
					<div class="line"></div>
					<div class="line"></div>
					<div class="line"></div>
				</div>
			</header>

			<main class="main main--all-set" role="main">
				<div class="main__left">
					<div class="main-all-set">
						<h1 class="main-all-set__h1">
							<?php esc_html_e( 'All set and running', 'smartsupp-live-chat' ); ?>
						</h1>
						<p class="main-all-set__text">
							<?php esc_html_e( 'Congratulations! Smartsupp live chat is already visible on your website.', 'smartsupp-live-chat' ); ?>
						</p>
						<a href="https://dashboard.smartsupp.com?utm_source=Wordpress&utm_medium=integration&utm_campaign=link" target="_blank" class="btn btn--primary btn--arrow">
							<?php esc_html_e( 'Chat with your visitors', 'smartsupp-live-chat' ); ?>
						</a>
						<p class="main-all-set__bottom-text">
							<?php
							printf(
								/* translators: %1$s and %2$s are opening and closing anchor tags */
								esc_html__( 'or %1$sSet up%2$s chat box design first', 'smartsupp-live-chat' ),
								'<a href="https://app.smartsupp.com/app/settings/chatbox/text?utm_source=Wordpress&utm_medium=integration&utm_campaign=link" target="_blank">',
								'</a>'
							);
							?>
						</p>
					</div>
				</div>
				<div class="main__right">
					<img src="<?php echo esc_url( $pluginUrl ); ?>/images/all-done.png" alt="All done">
				</div>
			</main>

			<section class="advanced">
				<div class="advanced__header collapse
				<?php
				if ( ! $options['optional-code'] ) {
					echo 'closed';
				} ?>">
					<span class="advanced__caret"></span> <?php esc_html_e( 'Advanced settings', 'smartsupp-live-chat' ); ?>
				</div>
				<div class="advanced__content"
				<?php
				if ( ! $options['optional-code'] ) {
					echo 'style="display: none;"';
				} ?>>
					<p class="advanced__text">
						<?php
						printf(
							/* translators: %1$s and %2$s are opening and closing anchor tags */
							esc_html__( 'Don\'t put the chat code here! This box is for (optional) advanced customizations via %1$sSmartsupp API%2$s', 'smartsupp-live-chat' ),
							'<a href="https://developers.smartsupp.com?utm_source=Wordpress&utm_medium=integration&utm_campaign=link" target="_blank">',
							'</a>'
						);
						?>
					</p>
					<form action="" method="post" id="settingsForm" class="js-code-form" autocomplete="off">
						<textarea name="code" id="textAreaSettings" class="input input--area" cols="30" rows="10"><?php echo esc_textarea( $options['optional-code'] ); ?></textarea>
						<?php wp_nonce_field( 'smartsupp_update', '_nonce' ); ?>
						<div class="advanced__bottom">
							<button type="submit" name="_submit" class="btn btn--sm">
								<?php esc_html_e( 'Save changes', 'smartsupp-live-chat' ); ?>
							</button>

							<div class="saved">
								<?php if ( $message ) { ?>
									<img src="<?php echo esc_url( $pluginUrl ); ?>/images/all-changes-saved.png" class="saved__img" alt="Saved icon">
									<p class="saved__text">
										<?php echo esc_html( $message ); ?>
									</p>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
			</section>
		</div>
	<?php } else { ?>
		<div class="">
			<header class="header">
				<img src="<?php echo esc_url( $pluginUrl ); ?>/images/logo.png" alt="smartsupp logo" class="header__logo" />
				<nav class="hide--up-md">
					<div class="header-user">
						<span class="header-user__email" data-toggle-form data-multitext data-register="<?php esc_attr_e( 'Already have an account?', 'smartsupp-live-chat' ); ?>" data-login="<?php esc_attr_e( 'Not a Smartsupp user yet?', 'smartsupp-live-chat' ); ?>">
							<?php echo esc_html( $formAction === 'login' ? __( 'Not a Smartsupp user yet?', 'smartsupp-live-chat' ) : __( 'Already have an account?', 'smartsupp-live-chat' ) ); ?>
						</span>
						<a href="javascript: void(0);" class="btn btn--sm" data-toggle-form data-multitext data-register="<?php esc_attr_e( 'Log in', 'smartsupp-live-chat' ); ?>" data-login="<?php esc_attr_e( 'Create a free account', 'smartsupp-live-chat' ); ?>">
							<?php echo esc_html( $formAction === 'login' ? __( 'Create a free account', 'smartsupp-live-chat' ) : __( 'Log in', 'smartsupp-live-chat' ) ); ?>
						</a>
					</div>
				</nav>
				<a href="javascript: void(0);" class="navbar-toggle">
					<div class="line"></div>
					<div class="line"></div>
					<div class="line"></div>
				</a>
			</header>

			<main class="main" role="main" id="connect">
				<div class="main__left">
					<div class="main-form">
						<h1 class="main-form__h1" data-multitext data-login="<?php esc_attr_e( 'Log in', 'smartsupp-live-chat' ); ?>" data-register="<?php esc_attr_e( 'Create a free account', 'smartsupp-live-chat' ); ?>">
							<?php echo esc_html( $formAction === 'login' ? __( 'Log in', 'smartsupp-live-chat' ) : __( 'Create a free account', 'smartsupp-live-chat' ) ); ?>
						</h1>
						<p class="main-form__top-text<?php
						if ( $formAction ) {
							echo ' js-' . esc_attr( $formAction ) . '-form';
						}
						?>" data-toggle-class>
							<?php esc_html_e( 'Start personal conversation with your visitors today.', 'smartsupp-live-chat' ); ?>
						</p>
						<form action="" method="post" id="signUpForm" class="form-horizontal<?php
						if ( $formAction ) {
							echo ' js-' . esc_attr( $formAction ) . '-form';
						} else {
							echo ' js-register-form';
						}
						?>" data-toggle-class autocomplete="off">
							<div class="alerts">
								<?php if ( $message ) { ?>
									<div class="alert alert-danger js-clear">
										<?php echo esc_html( $message ); ?>
									</div>
								<?php } ?>
							</div>
							<?php wp_nonce_field( 'smartsupp', '_nonce' ); ?>
							<input type="email" class="input" placeholder="<?php esc_attr_e( 'Email:', 'smartsupp-live-chat' ); ?>" name="email" id="frm-signUp-form-email" required="" value="<?php echo isset( $email ) ? esc_attr( $email ) : ''; ?>">
							<input type="password" class="input" placeholder="<?php esc_attr_e( 'Password:', 'smartsupp-live-chat' ); ?>" name="password" autocomplete="off" id="frm-signUp-form-password" required="">
							<div class="loader"></div>
							<button type="submit" name="_submit" class="btn btn--primary btn--arrow btn--all-width" data-multitext data-login="<?php esc_attr_e( 'Log in', 'smartsupp-live-chat' ); ?>" data-register="<?php esc_attr_e( 'Create a free account', 'smartsupp-live-chat' ); ?>">
								<?php echo esc_html( $formAction === 'login' ? __( 'Log in', 'smartsupp-live-chat' ) : __( 'Create a free account', 'smartsupp-live-chat' ) ); ?>
							</button>
							<p class="main-form__bottom-text<?php
							if ( $formAction ) {
								echo ' js-' . esc_attr( $formAction ) . '-form';
							} else {
								echo ' js-register-form';
							}
							?>" data-toggle-class>
								<span class="js-login">
									<?php
									printf(
										/* translators: %1$s and %2$s are opening and closing anchor tags */
										esc_html__( '%1$sI forgot my password%2$s', 'smartsupp-live-chat' ),
										'<a href="https://app.smartsupp.com/app/sign/reset" target="_blank">',
										'</a>'
									);
									?>
								</span>
								<span class="js-register">
									<?php
									printf(
										/* translators: %1$s, %2$s, %3$s, %4$s are opening and closing anchor tags */
										esc_html__( 'By signing up, you agree with %1$sTerms%2$s and %3$sDPA%4$s', 'smartsupp-live-chat' ),
										'<a href="https://help.smartsupp.com/en_US/privacy-legal/terms" target="_blank">',
										'</a>',
										'<a href="https://help.smartsupp.com/en_US/privacy-legal/dpa" target="_blank">',
										'</a>'
									);
									?>
								</span>
							</p>
						</form>
					</div>
				</div>

				<div class="main__right">
					<img src="<?php echo esc_url( $pluginUrl ); ?>/images/tablet-screen.png" alt="Tablet screen">
				</div>

			</main>

			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $features;
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $customers;
			?>

		</div>
	<?php } ?>

</div>

<?php echo '<script src="' . esc_url( $pluginUrl ) . '/assets/script.js"></script>'; ?>
