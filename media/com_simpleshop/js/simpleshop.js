jQuery( document ).ready(function() {
    console.log( "Merkzettel JS loaded!" );

    // Get Parameters from Joomla


    /*
    const params = Joomla.getOptions('params');

    jQuery.each(params, function (index, value) {
        console.log(value.download_id);
        setButtonDisabled(value.download_id);
    });



    /******************************************************************************/
    // Set cart divs
    /*****************************************************************************/

    function addOrRemoveCart(dataLength){
        if(dataLength > 0){
            jQuery('.cartContainer').html('');
            jQuery('.cartContainer').prepend('<div id="cart"><div>');
            jQuery('.submitCart').removeClass('buttonHide');
            jQuery('.sendCart').removeClass('buttonHide');
            jQuery('.webshopContentWrapper').removeClass('col-md-12');
            jQuery('.webshopContentWrapper').addClass('col-md-9');
            jQuery('.sidebar').removeClass('sidebarHide');
        }
        else{
            jQuery('.cartContainer').html('');
            jQuery('.submitCart').addClass('buttonHide');
            jQuery('.sendCart').addClass('buttonHide');
            jQuery('.webshopContentWrapper').addClass('col-md-12');
            jQuery('.webshopContentWrapper').removeClass('col-md-9');
            jQuery('.sidebar').addClass('sidebarHide');
        }
    }

    /******************************************************************************/
    // Validate formfields guest
    // Disbale button when fields not filled
    /*****************************************************************************/


    jQuery(".submitCart").click(function(e) {
        notValid = jQuery(this).hasClass('disabled');
        if(notValid){
            e.preventDefault();
            jQuery(".guestContainer").css('display','block');
            jQuery([document.documentElement, document.body]).animate({
                scrollTop: jQuery(".guestRow").offset().top
            }, 1000);
            jQuery('.warning').append('<p class="warningMessage">Zum Bestellen bitte alle Felder ausfüllen</p>');
        }
        else{
            return;
        }
    });

    /******************************************************************************/
    // Validate formfields guest
    // Disbale button when fields not filled
    /*****************************************************************************/

    jQuery('.btnSubmitCartGuest').addClass('disabled');
    //jQuery('.warning').append('<p class="warningMessage">Zum Bestellen bitte alle Felder ausfüllen</p>');

    jQuery('.guestInput').change(function(){
        console.log('blurred');
        var fields = jQuery('.guestInput');
        for(var i=0;i<fields.length;i++){
            if(jQuery(fields[i]).val() != ''){
                jQuery('.btnSubmitCartGuest').removeClass('disabled');
                jQuery('.warningMessage').remove();
            }
            else{
                jQuery('.btnSubmitCartGuest').addClass('disabled');
                if(jQuery('.warningMessage').length < 1) {
                    jQuery('.warningMessage').remove();
                    jQuery('.warning').append('<p class="warningMessage">Zum Bestellen bitte alle Felder ausfüllen</p>');
                }
            }
        }
    });

    /******************************************************************************/
    // Get current user cart, if one i s set
    /*****************************************************************************/

    var tokenKiki = jQuery("#token").attr("name");

    jQuery.ajax({
        data: { [tokenKiki]: "1", task: "showUserCart", format: "json"},
        success: function(result) {
            //console.log(result.data[0]);


            addOrRemoveCart(result.data.length);

            var gesamtsummeCart = 0;
            var html = '<h3>Warenkorb</h3><ul class="cartlist">';
            jQuery.each( result.data, function( key, value ) {
                var sammelpreis = parseFloat(value.produkt_preis) * parseInt(value.counter);
                html += '<li class="cartProduct">';
                html += '   <h4 class="cartProductTitle">' + value.produkt_titel + '( ' + value.counter  + ') </h4>';
                html += '   <div class="btnWrapper">';
                html += '       <input id="deleteID-'+ value.produkt_id + '" class="deleteProduct" name="deleteProduct" value="' + value.counter + '">';
                html += '       <button data-produktid="' + value.produkt_id + '" class="btnDeleteCart btn btn-danger"><i class="fa fa-sync-alt"></i></button>';
                html += '   </div>';
                html += '   <p><strong>Einzelpreis: </strong>' + parseFloat(value.produkt_preis).toFixed(2) + 'EUR</p>';
                html += '   <p><strong>Summe: </strong>' + parseFloat(sammelpreis).toFixed(2) + ' EUR</p>';
                html += '</li>';

                gesamtsummeCart = parseFloat(gesamtsummeCart) + parseFloat(sammelpreis);

                console.log(value.produkt_id)
            });


            html += '<li>';
            html += '<p><strong>Warenkorb Gesamt: </strong>' + parseFloat(gesamtsummeCart).toFixed(2) + ' EUR</p>';
            html += '</li>';
            html += '</ul>';
            jQuery('#cart').html(html);

        },
        error: function(e) {
            console.log(e);
            console.log('ajax call failed');
        }
    });

    /******************************************************************************/
    // Add product to cart
    /*****************************************************************************/


    jQuery('.btnAddProduct').bind('click', function(event) {

        jQuery('<p class="productAdded">Produkt zum Warenkorb hinzugefügt</p>').hide().appendTo('.shopHeadline').fadeIn(1000);
        jQuery([document.documentElement, document.body]).animate({
            scrollTop: jQuery(".shopHeadline").offset().top
        }, 500);

        setTimeout(function(){
            jQuery('.productAdded').fadeOut(1000);
        },2000);

        // default preventen
        event.preventDefault();

        // button auslesen aus event
        var $button = jQuery(event.target).closest('button');


        // wenn nicht valide direkt returnen
        if(!$button) return;

        // auslesen infos
        var produktID = $button.data('produktid');
        var quantity = jQuery('#menge-' + produktID).val();

        var isAdded = $button.hasClass('btnDelete');
        var token = jQuery("#token").attr("name");

        jQuery.ajax({
            data: { [token]: "1", task: "ajaxAddProduct", format: "json", produktID: produktID, quantity: quantity},
            success: function(result) {
                var tokenKiki = jQuery("#token").attr("name");

                jQuery.ajax({
                    data: { [tokenKiki]: "1", task: "showUserCart", format: "json"},
                    success: function(result) {

                        addOrRemoveCart(result.data.length);

                        var html = '<h3>Warenkorb</h3><ul class="cartlist">';
                        var gesamtsummeCart = 0;
                        jQuery.each( result.data, function( key, value ) {
                            var sammelpreis = parseFloat(value.produkt_preis) * parseInt(value.counter);
                            html += '<li class="cartProduct">';
                            html += '   <h4 class="cartProductTitle">' + value.produkt_titel + '( ' + value.counter  + ') </h4>';
                            html += '   <div class="btnWrapper">';
                            html += '       <input id="deleteID-'+ value.produkt_id + '" class="deleteProduct" name="deleteProduct" value="' + value.counter + '">';
                            html += '       <button data-produktid="' + value.produkt_id + '" class="btnDeleteCart btn btn-danger"><i class="fa fa-sync-alt"></i></button>';
                            html += '   </div>';
                            html += '   <p><strong>Einzelpreis: </strong>' + parseFloat(value.produkt_preis).toFixed(2) + ' EUR</p>';
                            html += '   <p><strong>Summe: </strong>' + parseFloat(sammelpreis).toFixed(2) + ' EUR</p>';
                            html += '</li>';

                            gesamtsummeCart = parseFloat(gesamtsummeCart) + parseFloat(sammelpreis);

                            console.log(value.produkt_id)
                        });

                        html += '<li>';
                        html += '<p><strong>Warenkorb Gesamt: </strong>' + parseFloat(gesamtsummeCart).toFixed(2) + ' EUR</p>';
                        html += '</li>';
                        html += '</ul>';
                        jQuery('#cart').html(html);

                    },
                    error: function(e) {
                        console.log(e);
                        console.log('ajax call failed');
                    }
                });

                console.log($button);
            },
            error: function(e) {
                console.log(e);
                console.log('ajax call failed');
            }
        });

    });

    /******************************************************************************/
    // Refresh cart
    /*****************************************************************************/

    jQuery('.btnDeleteCart').live('click', function(event) {


        jQuery('<p class="cartRefreshed">Warenkorb aktualisiert</p>').hide().appendTo('.shopHeadline').fadeIn(1000);
        jQuery([document.documentElement, document.body]).animate({
            scrollTop: jQuery(".shopHeadline").offset().top
        }, 500);

        setTimeout(function(){
            jQuery('.cartRefreshed').fadeOut(1000);
        },2000);

        // default preventen
        event.preventDefault();

        // button auslesen aus event
        var $button = jQuery(event.target).closest('button');


        // wenn nicht valide direkt returnen
        if(!$button) return;

        // auslesen infos
        var produktID = $button.data('produktid');
        var quantity = jQuery('#deleteID-' + produktID).val();

        var token = jQuery("#token").attr("name");

        jQuery.ajax({
            data: { [token]: "1", task: "ajaxRefreshCart", format: "json", produktID: produktID, quantity: quantity},
            success: function(result) {
                $button.find('span').text($button.data('messageremove'));

                var tokenKiki = jQuery("#token").attr("name");

                jQuery.ajax({
                    data: { [tokenKiki]: "1", task: "showUserCart", format: "json"},
                    success: function(result) {

                        addOrRemoveCart(result.data.length);

                        var gesamtsummeCart = 0;
                        var html = '<h3>Warenkorb</h3><ul class="cartlist">';
                        jQuery.each( result.data, function( key, value ) {
                            var sammelpreis = parseFloat(value.produkt_preis) * parseInt(value.counter);
                            html += '<li class="cartProduct">';
                            html += '   <h4 class="cartProductTitle">' + value.produkt_titel + '( ' + value.counter  + ') </h4>';
                            html += '   <div class="btnWrapper">';
                            html += '       <input id="deleteID-'+ value.produkt_id + '" class="deleteProduct" name="deleteProduct" value="' + value.counter + '">';
                            html += '       <button data-produktid="' + value.produkt_id + '" class="btnDeleteCart btn btn-danger"><i class="fa fa-sync-alt"></i></button>';
                            html += '   </div>';
                            html += '   <p><strong>Einzelpreis: </strong>' + parseFloat(value.produkt_preis).toFixed(2) + ' EUR</p>';
                            html += '   <p><strong>Summe: </strong>' + parseFloat(sammelpreis).toFixed(2) + ' EUR</p>';
                            html += '</li>';

                            gesamtsummeCart = parseFloat(gesamtsummeCart) + parseFloat(sammelpreis);

                            console.log(value.produkt_id)
                        });

                        html += '<li>';
                        html += '<p><strong>Warenkorb Gesamt: </strong>' + parseFloat(gesamtsummeCart).toFixed(2) + ' EUR</p>';
                        html += '</li>';
                        html += '</ul>';
                        jQuery('#cart').html(html);

                    },
                    error: function(e) {
                        console.log(e);
                        console.log('ajax call failed');
                    }
                });

                console.log($button);
            },
            error: function(e) {
                console.log(e);
                console.log('ajax call failed');
            }
        });

    });



});

