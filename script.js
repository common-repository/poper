function getDomain() {
    // Remove www from the subdomain
    // if its present in start of the domain
    return window?.location?.hostname?.replace("www.", "") || "";
}


jQuery(document).on( 'click', '.poper-admin-notice .notice-dismiss', function() {

    jQuery.ajax({
        url: window.ajaxurl,
        data: {
            action: 'poper_dismiss_notice_temporary'
        }
    })

})

jQuery(document).on( 'click', '.poper-admin-notice .poper-dismiss-perma', function() {

    jQuery.ajax({
        url: window.ajaxurl,
        data: {
            action: 'poper_dismiss_notice'
        }
    }).done(function() {
        window.location.reload()
    });

})


jQuery(document).on( 'click', '.poper-admin-c-notice .notice-dismiss', function() {

    jQuery.ajax({
        url: window.ajaxurl,
        data: {
            action: 'poper_dismiss_cache_notice'
        }
    })

})

jQuery(document).on( 'click', '.poper-admin-c-notice .poper-dismiss-perma', function() {

    jQuery.ajax({
        url: window.ajaxurl,
        data: {
            action: 'poper_dismiss_cache_notice'
        }
    }).done(function() {
        window.location.reload()
    });

})


jQuery(document).on( 'click', '.poper-container .verification-btn', function() {

    // Call Poper API to verify domain
    var domain = getDomain();
    var email = jQuery('#account_id')?.val();

    if( !email || !domain ) {
        alert('Please enter your email and domain');
        return;
    }

    var API_URL = "https://api.poper.ai/general/user/verify_domain_status";

    // Call Poper API to verify domain 
    // Post request with body as JSON
    jQuery.ajax({
        url: API_URL,
        type: 'POST',
        data: {
            email: email,
            domain: domain
        },
        dataType: 'json',
        success: function(response) {
            if(response?.verified === "true") {
                jQuery.ajax({
                    url: window.ajaxurl,
                    data: {
                        action: 'poper_mark_domain_verified'
                    }
                }).done(function() {
                    window.location.reload()
                });
            } else {
                alert('Domain not verified');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
            alert('Cannot verify domain status');
        }
    });
    

})

