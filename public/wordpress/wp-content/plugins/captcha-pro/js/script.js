/*!
 * License: Proprietary
 * License URI: https://bestwebsoft.com/end-user-license-agreement/
 */
(function( $ ) {
	$(document).ready( function() {
		/*
		* Hide/show checkboxes for network settings on network settings page
		*/
		function cptch_network_apply() {
			if ( $( 'input[name="cptch_network_apply"]:checked' ).val() != 'all' ) {
				$( '.cptch_network_settings .bws_network_apply_all, #cptch_network_notice' ).hide();
				if ( $( 'input[name="cptch_network_apply"]:checked' ).val() == 'off' ) {
					$( '.cptch_settings_form tr' ).hide();
					$( '.cptch_settings_form tr.cptch_network_settings' ).show();
				} else
					$( '.cptch_settings_form tr' ).show();
			} else {
				$( '#cptch_network_notice, .cptch_network_settings .bws_network_apply_all, .cptch_settings_form tr' ).show();
			}
		}
		if ( $( 'input[name="cptch_network_apply"]' ).length ) {
			cptch_network_apply();
			$( 'input[name="cptch_network_apply"]' ).change( function() { cptch_network_apply() });
		}

		/**
		 * Handle the styling of the "Settings" page
		 * @since 4.2.3
		 */
		var imageFormat		= $( '#cptch_operand_format_images' ),
			packageLoader	= $( '.cptch_install_package_wrap' );

		/*
		* Hide "time limit thershold" fields under all unchecked "time limit" fields
		*/
		$( 'input[name*="[enable_time_limit]"]' ).each( function() {
			if ( ! $( this ).is( ':checked' ) ) {
				$( this ).closest( 'tr' ).nextAll( '.cptch_time_limit' ).hide();
			}

			$( this ).click( function() {
				$( this ).closest( 'tr' ).nextAll( '.cptch_time_limit' ).toggle();
			});
		});

		/*
		 * Hide all related forms on settings page
		 */
		$.each( $( 'input[name*="[enable]"]' ), function() {
			var formName  = '.' + $( this ).attr( 'id' ).replace( 'enable', 'related_form' ),
				formBlock = $( formName );

			$( this ).is( ':checked' ) ? formBlock.show() : formBlock.hide();

			$( this ).click( function() {
				$( this ).is( ':checked' ) ? formBlock.show() : formBlock.hide();
			});
		});

		/*
		 * Hide/show settings of the forms related with captcha
		 * depending on "general settings" is checked or not
		 */
		$( "input[name*='[use_general]']" ).click( function() {
			var rows = $( this )
					.closest( 'tr' )
					.siblings( 'tr' )
					.not( '.cptch_time_limit, .cptch_form_option_hide_from_registered, .cptch_form_option_used_packages' );

			if ( $( this ).is( ':checked' ) )  {
				rows.hide();
				$( this ).closest( 'tr' ).siblings( 'tr.cptch_form_option_used_packages' ).hide();
			} else {
				rows.show();
				if ( $( '.cptch_images_options' ).css( 'display' ) !== 'none' ) {
					$( this ).closest( 'tr' ).siblings( 'tr.cptch_form_option_used_packages' ).show();
				}
			}			
		} );

		/*
		* Handle the displaying of notice message above lists of image packages
		*/
		function cptchImageOptions() {
			var isChecked = imageFormat.is( ':checked' );
			if ( isChecked ){
				$( '.cptch_images_options, .cptch_enable_to_use_several_packages' ).show();

				$( "input[name*='[use_general]']:not(:checked)" ).each( function() {
					$( this ).closest( 'tr' ).siblings( 'tr.cptch_form_option_used_packages' ).show();
				});
			} else {
				$( '.cptch_images_options, .cptch_enable_to_use_several_packages, .cptch_form_option_used_packages' ).hide();				
			}

			$( ".cptch_tabs_package_list:not(.cptch_pro_pack_tab)" ).each( function() {
				var notice = imageFormat.prev( '.cptch_enable_images_notice' );
				if ( ! notice.length ) {
					return;
				}

				if ( imageFormat.find( 'input:checked' ).length && ! isChecked ) {
					notice.show();
				} else {
					notice.hide();
				}
			});
		}
		cptchImageOptions()
		imageFormat.click( function() { cptchImageOptions(); } );

		function cptch_type() {
			if ( 'recognition' == $( 'input[name="cptch_type"]:checked' ).val() ) {
				$( '.cptch_for_math_actions' ).hide();
				$( '.cptch_for_recognition' ).show();				
				imageFormat.attr( 'checked', 'checked' );
				cptchImageOptions();
			} else if ( 'invisible' == $( 'input[name="cptch_type"]:checked' ).val() ) {
				$( '.cptch_for_recognition, .cptch_for_math_actions' ).hide();
			} else {
				$( '.cptch_for_recognition' ).hide();
				$( '.cptch_for_math_actions' ).show();
			}
		}
		cptch_type();
		$( 'input[name="cptch_type"]' ).click( function() { cptch_type(); } );

		/**
		 * Hide/show whitelist "add new form"
		 */
		$( 'button[name="cptch_show_whitelist_form"]' ).click(function() {
			$( this ).parent( 'form' ).hide();
			$( '.cptch_whitelist_form' ).show();
			return false;
		});

		/*
		* add to whitelist my ip
		*/
		$( 'input[name="cptch_add_to_whitelist_my_ip"]' ).change(function() {
			if ( $( this ).is( ':checked' ) ) {
				var reason = $( this ).parent().text();
				var my_ip = $( 'input[name="cptch_add_to_whitelist_my_ip_value"]' ).val();
				$( 'textarea[name="cptch_add_to_whitelist"]' ).val( my_ip ).attr( 'readonly', 'readonly' );
				$( 'textarea[name="cptch_add_to_whitelist_reason"]' ).val( $.trim( reason ) );
			} else {
				$( 'textarea[name="cptch_add_to_whitelist_reason"]' ).val( '' );
				$( 'textarea[name="cptch_add_to_whitelist"]' ).val( '' ).removeAttr( 'readonly' );
			}
		} );

		/**
		 * Show/hide package loader form on the "Packages" tab on the plugin settings page
		 * @since 1.6.9
		 */
		if ( packageLoader.length ) {
			var disabled = $( '.cptch_install_disabled' );
			disabled.attr( 'disabled', true );
			$( '#cptch_install_package_input' ).change(function() {
				disabled.attr( 'disabled', false );
			});
			$( '#cptch_show_loader' ).click(function( event ) {
				event = event || window.event;
				event.preventDefault();
				if ( packageLoader.is( ':visible' ) ) {
					packageLoader.hide();
				} else {
					packageLoader.show();
				}
			});
		}

		/**
		 * Handle the "Whitelist" on the whitelist page
		 */
		$( 'button[name="cptch_show_whitelist_form"]' ).click( function() {
			$( this ).parent( 'form' ).hide();
			$( '.cptch_whitelist_form' ).show();
			return false;
		} );

		/* Putting initial value of each textarea into data 'default-value' attr */
		$( '.cptch-add-reason-textarea' ).each( function( e ) {
			$( this ).data( 'default-value', $( this ).val() );
		} );

		$( '.cptch-add-reason-textarea' ).css( {"overflow": "hidden"} );
		/* Hiding display and edit link and showing textarea field with buttons for edit add_reason for whitelist/blacklist by click on edit link */
		$( '.cptch_edit_reason_link' ).on( "click", function( event ) {
			event.preventDefault();
			parent = $( this ).closest( 'td' );
			parent.find( '.cptch-add-reason, .cptch_edit_reason_link' ).hide();
			parent.find( '.cptch-add-reason-button' ).show();
			parent.find( '.cptch-add-reason-textarea' ).show().removeClass( 'hidden' ).trigger( 'focus' );
		} );

		/* preparing arguments and calling cptch_update_reason() function */
		$( '.cptch-add-reason-button[name=cptch_reason_submit]' ).on( "click", function( event ) {
			event.preventDefault();
			parent = $( this ).parent();
			id = $( this ).closest( 'tr' ).find( '.check-column input' ).val();
			reason = parent.find( '.cptch-add-reason-textarea' ).val();
			cptch_update_reason( id, reason );
			parent.find( '.cptch-add-reason-button, .cptch-add-reason-textarea' ).hide();
			parent.find( '.cptch-add-reason, .cptch_edit_reason_link' ).show();
		} );

		/* restoring initial value of textarea from data 'default-value' by click on cancel button */
		$( '.cptch-add-reason-button[name=cptch_reason_cancel]' ).on( "click", function( event ) {
			event.preventDefault();
			parent = $( this ).parent();
			default_data = $( this ).parent().find( '.cptch-add-reason-textarea' ).data( 'default-value' );
			parent.find( '.cptch-add-reason-textarea' ).val( default_data );
			parent.find( '.cptch-add-reason-button, .cptch-add-reason-textarea' ).hide();
			parent.find( '.cptch-add-reason, .cptch_edit_reason_link' ).show();
		} );

		/* function to resize textarea according to the 'add_reason' content */
		$( '.cptch-autoexpand' ).on( "focus input", function() {
			var el = this;
			el.style.cssText = 'height:auto; padding:0; overflow:hidden';
			el.style.cssText = 'height:' + el.scrollHeight + 'px; overflow:hidden';
		} );

	} );
} )( jQuery );

/**
 * Update add reason for whitelist
 * @param		string		id				reason of which id is edited
 * @param		string		reason			reason text
 * @return		void
 */
function cptch_update_reason( id, reason ) {
	(function( $ ) {
		$.ajax( {
			type: 'POST',
			url: ajaxurl,
			data: {
				action: 'cptch_update_reason',
				cptch_edit_id:	id,
				cptch_reason:	reason,
				cptch_nonce:	cptch_vars.cptch_ajax_nonce
			},
			success: function( result ) {
				var parent_row	= $( '.check-column input[value="' + id + '"]' ).closest( 'tr' ),
					reason_display = parent_row.find( '.cptch-add-reason' ),
					reason_textarea = parent_row.find( '.cptch-add-reason-textarea' ),
					old_color = reason_display.css( 'color' );
				try {
					result		= $.parseJSON( result );
					if ( result['success'] != '' ){
						reason_textarea.val( result['reason'] );
						reason_textarea.data( 'default-value', result['reason'] );
						reason_display.html( result['reason-html'] );
						reason_display
							.animate(
								{ color: "#46b450" },
								250
							)
							.animate(
								{ color: old_color },
								250
							);
					} else {
						if ( result['no_changes'] != '' ) {
						} else {
							var str = reason_display.html();
							reason_textarea.val( str.replace(/<br>/g, "") );
							reason_display
								.animate(
									{ color: "#dc3232" },
									250
								)
								.animate(
									{ color: old_color },
									250
								);
						}
					}
				} catch( e ) {
					var str = reason_display.html();
					reason_textarea.val( str.replace(/<br>/g, "") );
					reason_display
						.animate(
							{ color: "#dc3232" },
							250
						)
						.animate(
							{ color: old_color },
							250
						);
				}
			},
			error : function ( xhr, ajaxOptions, thrownError ) {
				alert( xhr.status );
				alert( thrownError );
			}
		} );
		return false;
	})( jQuery );
}