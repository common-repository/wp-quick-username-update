
(function(jQuery) {
jQuery('table.users').on( 'click', 'a.user-quick-editinline', function(){
	
	/***********************
		this code is used to close the opened quick edit section before closing the new one *****************/
	
	var $tableWideFat = jQuery( '.widefat' ),
                id = jQuery( '.inline-editor', $tableWideFat ).attr( 'id' );

            if ( id ) {
				jQuery( '.spinner', $tableWideFat ).removeClass( 'is-active' );
                jQuery('#'+id).siblings('tr.hidden').addBack().remove();
                id = id.substr( id.lastIndexOf('-') + 1 );
                jQuery( '#user-' + id ).show().find( '.editinline' ).focus();
            }
	
	/*************************************************************************************/
	
	var userID = jQuery(this).data('id');
	
	fields = ['username','email', 'first_name', 'last_name', 'nickname','role', 'description', 'url', 'display_name' ];
		editRow = jQuery('#inline-edit').clone(true);
		
		jQuery( 'td', editRow ).attr( 'colspan', jQuery( 'th:visible, td:visible', '.widefat:first thead' ).length );
		
		jQuery( '#user-' + userID ).removeClass('is-expanded').hide().after(editRow).after('<tr class="hidden"></tr>');
		
		rowData = jQuery(this).closest('span.inline').find( '#inline_' + userID );
			for ( f = 0; f < fields.length; f++ ) {
                val = jQuery('.'+fields[f], rowData);
                val = val.text();
                jQuery(':input[name="' + fields[f] + '"]', editRow).val( val );
            }
		var roleText = jQuery('tr#user-'+userID+' td.role').text();		if(roleText){					jQuery(':input[name="role"] option').filter(function() { return jQuery.trim( jQuery(this).text() ) == roleText; }).attr('selected','selected');				}			
		jQuery(editRow).attr('id', 'edit-'+userID).addClass('inline-editor').show();
        jQuery('.ptitle', editRow).focus();
	
});



jQuery('table.users').on( 'click', 'button.cancel', function(e)
{
	e.preventDefault();
    var $tableWideFat = jQuery( '.widefat' ),
                id = jQuery( '.inline-editor', $tableWideFat ).attr( 'id' );

            if ( id ) {
				jQuery( '.spinner', $tableWideFat ).removeClass( 'is-active' );
                jQuery('#'+id).siblings('tr.hidden').addBack().remove();
                id = id.substr( id.lastIndexOf('-') + 1 );
                jQuery( '#user-' + id ).show().find( '.editinline' ).focus();
            }

            return false;
} );


jQuery('table.users').on( 'click', 'button.save', function(){
	var self= jQuery(this) ,params, fields;
	if ( typeof(this) === 'object' ) {
			var idd = jQuery(this).closest('tr').attr('id');
                parts = idd.split('-');
			var id = parts[parts.length - 1];
         }
		jQuery('table.widefat .spinner').addClass('is-active');
		
		params = {
                action: 'user-inline-save',
                _wpnonce: wpQUserUp.nonce,
                user_id: id
            };
			
		fields = jQuery('#edit-'+id).find(':input').serialize();
		 
		params = fields + '&' + jQuery.param(params);
		
		// Make ajax request.
		jQuery.post( wpQUserUp.ajaxurl, params,function(response){
			
			
			jQuery('table.widefat .spinner').removeClass('is-active');
			
			var resp = jQuery.parseJSON(response);
			 var $errorSpan = jQuery( '#edit-' + id + ' .inline-edit-save .error' );
			 var uname = jQuery('#edit-'+id).find(':input:first').serialize().split("=").pop();
			
			if ( resp.success ) {
				
                        if ( -1 !== resp.data.indexOf( '<tr' ) ) {
                            jQuery('#user-'+id).siblings('tr.hidden').addBack().remove();
                            jQuery('#edit-'+id).before(resp.data).remove();
							
					
                            jQuery( '#user-'+id ).hide().fadeIn( 400, function() {
                            Query(this).find( '.editinline' ).focus();
								/* setTimeout(function(){ location.reload(); }, 100); */
                            });
							var abc = '#user-'+id;
							var def = '#edit-'+id;
							/* var uname = jQuery(def+' .ptitle:first input[type="text"]').val(); */
							
							jQuery(abc+' a:first').text(uname);
                        } else {
                            r = resp.data.replace( /<.[^<>]*?>/g, '' );
                            $errorSpan.html(r).show();
                        }
						

                    } else {
                        $errorSpan.html( resp.data.join(' | ') ).show();
                    }
		},'html');
		
		
	
	return false;
});


})(jQuery);
 
 

 
 
 

























