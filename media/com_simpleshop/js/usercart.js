jQuery( document ).ready(function() {

    const params = Joomla.getOptions('params');

    getUserCart(params);


});



/******************************************************************************/
// Get current user cart fpr show in cart view
/*****************************************************************************/

function getUserCart(params){
    var tokenKiki = jQuery("#token").attr("name");
    var ajaxToHtml = '';
    jQuery.ajax({
        data: { [tokenKiki]: "1", task: "showUserCart", format: "json"},
        success: function(result) {
            //console.log(result.data[0]);
            //jQuery('#result').html(result.data[0].produkt_titel);
            var html = '';
            var differentProducts = 0;

            var summeArray = [];

            jQuery.each( result.data, function( key, value ) {
                console.log(value);
                differentProducts++;
                var oddEven;

                if(differentProducts % 2 == 0){
                    oddEven = 'odd';
                }
                else{
                    oddEven = 'even';
                }
                var sammelpreis = parseFloat(value.produkt_preis) * parseInt(value.counter);
                html += '<tr class="' + oddEven + '">';
                html += '<td>' + value.produkt_titel + '</td>';
                html += '<td>' + value.counter + '</td>';
                html += '<td>' + parseFloat(value.produkt_preis).toFixed(2) + params.currency + '</td>';
                html += '<td>' + parseFloat(sammelpreis).toFixed(2) + params.currency + '</td>';
                //html += '<input id="deleteID-'+ value.produkt_id + '" name="deleteProduct" value="' + value.counter + '">';
                //html += '<button data-produktid="' + value.produkt_id + '" class="btnDeleteCart btn btn-primary">Löschen</button>';
                html += '</tr>';

                summeArray.push(parseFloat(sammelpreis).toFixed(2));
                console.log(value.produkt_id);
            });

            var total = parseFloat(0);
            for (var i = 0; i < summeArray.length; i++) {

                total = parseFloat(total) +  parseFloat(summeArray[i] << 0);
            }

            html += '<tr class="totalRow">';
            html += '   <td colspan="3">' +  params.total + '</td>';
            html += '   </td>';
            html += '   <td>' + parseFloat(total).toFixed(2) + params.currency + '</td>';
            html += '   </td>';
            html += '</tr>';


            jQuery('.tableBody').html(html);

        },
        error: function(e) {
            console.log(e);
            console.log('ajax call failed');
        }
    });
}
