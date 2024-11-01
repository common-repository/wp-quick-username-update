<form method="get">
	<table style="display: none">
		<tbody id="inlineedit">
		<?php
		
                $wp_user_list_table = _get_list_table('WP_Users_List_Table');
                $column_count = $wp_user_list_table->get_column_count();
            ?>	
			<tr id="inline-edit" class="inline-edit-row inline-edit-row-user inline-edit-user quick-edit-row quick-edit-row-user inline-edit-user" style="display:none">
				<td colspan="<?php echo $column_count; ?>" class="colspanchange">

					<fieldset class="inline-edit-col-left">
						<legend class="inline-edit-legend"><?php _e( 'Quick Edit', 'WMAMC-wp-quick-username-update' ) ?></legend>
						<div class="inline-edit-col">
							<label>
								<span class="title"><?php _e( 'Username', 'WMAMC-wp-quick-username-update' ) ?></span>
								<span class="input-text-wrap"><input type="text" name="username" class="ptitle" value=""></span>
							</label>

						
						
							<label>
								<span class="title"><?php _e( 'Email', 'WMAMC-wp-quick-username-update' ) ?></span>
								<span class="input-text-wrap"><input type="email" name="email" class="ptitle" value=""></span>
							</label>

							
							<label>
								<span class="title"><?php _e( 'Website', 'WMAMC-wp-quick-username-update' ) ?></span>
								<span class="input-text-wrap"><input type="url" name="url" class="ptitle" value=""></span>
							</label>

							
				
							<label>
								<span class="title"><?php _e( 'Role', 'WMAMC-wp-quick-username-update' ) ?></span>
								<span class="input-text-wrap">
								<select name="role" id="role" class="select-field">
												<?php
													wp_dropdown_roles();
												?>
											</select>
								</span>
							</label>

							
				
						<br class="clear">
						</div>
						
						
						
					</fieldset>
					
					<fieldset class="inline-edit-col-right">
						<legend class="inline-edit-legend"><?php  /* _e( 'User Details', 'WMAMC-wp-quick-username-update' ) */  ?></legend>
						<div class="inline-edit-col">
							<label>
								<span class="title"><?php _e( 'Display Name', 'WMAMC-wp-quick-username-update' ) ?></span>
								<span class="input-text-wrap"><input type="text" name="display_name" class="ptitle" value=""></span>
							</label>

							
							<label>
								<span class="title"><?php _e( 'Nickname', 'WMAMC-wp-quick-username-update' ) ?></span>
								<span class="input-text-wrap"><input type="text" name="nickname" class="ptitle" value=""></span>
							</label>

							
				
							<label>
								<span class="title"><?php _e( 'First Name', 'WMAMC-wp-quick-username-update' ) ?></span>
								<span class="input-text-wrap"><input type="text" name="first_name" class="ptitle" value=""></span>
							</label>

							
				
							<label>
								<span class="title"><?php _e( 'Last Name', 'WMAMC-wp-quick-username-update' ) ?></span>
								<span class="input-text-wrap"><input type="text" name="last_name" class="ptitle" value=""></span>
							</label>

							
				
						<br class="clear">
						</div>
						
						
						
					</fieldset>

					<?php do_action( 'wp_user_quick_edit_custom_fields' ); ?>
					<p class="submit inline-edit-save">
						<?php do_action( 'wp_user_quick_edit_form_submit' ); ?>
						<button type="button" class="button-secondary cancel alignleft"><?php _e( 'Cancel', 'WMAMC-wp-quick-username-update' ) ?></button>
						<button type="button" class="button-primary save alignright"><?php _e( 'Update', 'WMAMC-wp-quick-username-update' ) ?></button>
						<span class="spinner"></span>
						<span class="error" style="display:none"></span>
						<br class="clear">
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</form>