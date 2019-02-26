jQuery( document ).ready(function() {
    console.log( "Merkzettel JS loaded!" );

    // Get Parameters from Joomla



    const params = Joomla.getOptions('params');

    /*

    jQuery.each(params, function (index, value) {
        console.log(value.download_id);
        setButtonDisabled(value.download_id);
    });

    /**************************************************************************************************************************************************************************/
    // Functions
    /**************************************************************************************************************************************************************************/


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
    // HTML Structure for every product added to cart
    /*****************************************************************************/

    function getCartHTML(value){
        var htmlStructure;
        var sammelpreis = parseFloat(value.produkt_preis) * parseInt(value.counter);
        htmlStructure  = '<li class="cartProduct">';
        htmlStructure += '   <h4 class="cartProductTitle">' + value.produkt_titel + '( ' + value.counter  + ')' +  ' </h4>';
        htmlStructure += '   <span class="cartProductProperty">'  + value.produkt_eigenschaft + ' </span>';
        htmlStructure += '   <div class="btnWrapper">';
        htmlStructure += '       <input id="deleteID-'+ value.produkt_id + '-' + value.produkt_eigenschaft + '" class="deleteProduct" name="deleteProduct" value="' + value.counter + '">';
        htmlStructure += '       <button data-produktid="' + value.produkt_id + '" data-produkteigenschaft="' + value.produkt_eigenschaft + '" class="btnDeleteCart btn btn-danger"><i class="fa fa-trash"></i></button>';
        htmlStructure += '       <button data-produktid="' + value.produkt_id + '" data-produkteigenschaft="' + value.produkt_eigenschaft + '" class="btnRefreshCart btn btn-success"><i class="fa fa-sync-alt"></i></button>';
        htmlStructure += '   </div>';
        htmlStructure += '   <p><strong>Einzelpreis: </strong>' + parseFloat(value.produkt_preis_mit_steuer).toFixed(2) + params.currency + '</p>';
        htmlStructure += '   <p><strong>Summe: </strong>' + parseFloat(value.produkt_total).toFixed(2) + params.currency + '</p>';
        //html += '   <p><strong>Summe: </strong>' + parseFloat(sammelpreis).toFixed(2) + ' EUR</p>';
        htmlStructure += '</li>';

        return htmlStructure;

    };

    /******************************************************************************/
    // Cart refresh message
    /*****************************************************************************/

    function refreshMessage(){
        jQuery('<p class="cartRefreshed">Warenkorb aktualisiert</p>').hide().prependTo('.submitForm').fadeIn(1000);
        jQuery([document.documentElement, document.body]).animate({
            scrollTop: jQuery(".submitForm").offset().top
        }, 500);

        setTimeout(function(){
            jQuery('.cartRefreshed').fadeOut(1000);
            //jQuery('.cartRefreshed').remove();
        },2000);
        alert('refreshed');
    }



    /******************************************************************************/
    // Refresh product price on property change
    /*****************************************************************************/

    function refreshProductPrice(produktID, eigenschaft){

        jQuery.ajax({
            data: { [tokenKiki]: "1", task: "getProductProperties", format: "json", produktID: produktID},
            success: function(result) {

                var productPrice = parseInt(result.data.produkt_preis);
                var productTax = parseInt(result.data.produkt_steuer);

                //alert(productPrice);

                jQuery.each( result.data.produkt_eigenschaften, function( key, value ) {
                    if(key == eigenschaft){
                        priceDiv = jQuery('.productPrice' + produktID);
                        taxDiv = jQuery('.taxValue' + produktID);
                        price = parseInt(productPrice) + parseInt(value);
                        tester = parseInt(productTax);
                        priceTax = price * (parseFloat(productTax/100));
                        priceWithTax = price + priceTax;
                        priceDiv.text(priceWithTax.toFixed(2) + ' ' + params.currency);

                    }

                });
            },
            error: function(e) {
                console.log(e);
                console.log('ajax call failed');
            }
        });

    }

    /**************************************************************************************************************************************************************************/
    // Logic
    /**************************************************************************************************************************************************************************/

    /******************************************************************************/
    // Change product price on property change
    /*****************************************************************************/

    jQuery('select').on('change',function(){
        var eigenschaft = jQuery(this).val();
        var id = jQuery(this).next( "button" ).attr('data-produktid');
        refreshProductPrice(id, eigenschaft);
    });

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
               html += getCartHTML(value);
            });

            html += '<li>';
            html += '<p><strong>Warenkorb Gesamt: </strong>' + parseFloat(gesamtsummeCart).toFixed(2) +  params.currency + '</p>';html += '</li>';
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
        refreshMessage();

        // default preventen
        event.preventDefault();

        // button auslesen aus event
        var $button = jQuery(event.target).closest('button');


        // wenn nicht valide direkt returnen
        if(!$button) return;

        // auslesen infos
        var produktID = $button.data('produktid');
        var quantity = jQuery('#menge-' + produktID).val();
        var eigenschaft = jQuery('#produkt_eigenschaften' +  produktID + ' option:selected').text();



        var isAdded = $button.hasClass('btnDelete');
        var token = jQuery("#token").attr("name");

        jQuery.ajax({
            data: { [token]: "1", task: "ajaxAddProduct", format: "json", produktID: produktID, quantity: quantity, eigenschaft: eigenschaft},
            success: function(result) {
                var tokenKiki = jQuery("#token").attr("name");

                jQuery.ajax({
                    data: { [tokenKiki]: "1", task: "showUserCart", format: "json"},
                    success: function(result) {

                        addOrRemoveCart(result.data.length);

                        var html = '<h3>Warenkorb</h3><ul class="cartlist">';
                        var gesamtsummeCart = 0;
                        jQuery.each( result.data, function( key, value ) {
                            html += getCartHTML(value);
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

    jQuery('.btnRefreshCart').live('click', function(event) {

        refreshMessage();

        // default preventen
        event.preventDefault();

        // button auslesen aus event
        var $button = jQuery(event.target).closest('button');


        // wenn nicht valide direkt returnen
        if(!$button) return;

        // auslesen infos
        var produktID = $button.data('produktid');
        var produktEigenschaft = $button.data('produkteigenschaft');
        var quantity = jQuery('#deleteID-' + produktID + '-' + produktEigenschaft).val();
        //alert(produktEigenschaft);

        var token = jQuery("#token").attr("name");

        jQuery.ajax({
            data: { [token]: "1", task: "ajaxRefreshCart", format: "json", produktID: produktID, quantity: quantity, produktEigenschaft: produktEigenschaft},
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
                            html += getCartHTML(value);
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
    // Remove Product from card cart
    /*****************************************************************************/

    jQuery('.btnDeleteCart').live('click', function(event) {

        refreshMessage();

        // default preventen
        event.preventDefault();

        // button auslesen aus event
        var $button = jQuery(event.target).closest('button');


        // wenn nicht valide direkt returnen
        if(!$button) return;

        // auslesen infos
        var produktID = $button.data('produktid');
        var produktEigenschaft = $button.data('produkteigenschaft');
        var quantity = jQuery('#deleteID-' + produktID + '-' + produktEigenschaft).val();
        //alert(produktEigenschaft);

        var token = jQuery("#token").attr("name");

        jQuery.ajax({
            data: { [token]: "1", task: "ajaxRemoveProduct", format: "json", produktID: produktID, quantity: quantity, produktEigenschaft: produktEigenschaft},
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
                            html += getCartHTML(value);
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

