jQuery( document ).ready(function() {
    console.log( "Merkzettel JS loaded!" );

    // Get Parameters from Joomla

    const params = Joomla.getOptions('params');


    console.log(params);

    getUserDownloads();


    jQuery('.btnDeletedownloadUserList').live('click', function(event) {

        // default preventen
        event.preventDefault();

        // button auslesen aus event
        var $button = jQuery(event.target).closest('button');

        // wen nicht valide direkt returnen
        if(!$button) return;

        // auslesen infos
        var downloadId = $button.data('downloadid');

        // erster case: ist auf merkliste
        var token = jQuery("#token").attr("name");
        var ajaxToHtml = '';
        jQuery.ajax({
            data: { [token]: "1", task: "ajaxDeleteDownload", format: "json", downloadid: downloadId },
            success: function(result) {

                jQuery.each(result.data.downloads, function (index, value) {

                    ajaxToHtml  +=    '<tr>'
                        +   '<td>' + value.download_titel + '</td>'
                        +   '<td>' + value.download_url  + '</td>'
                        +   '<td><button class="btnDelete btnDeletedownloadUserList btn btn-primary" id="buttonDelete-' + value.download_id
                        +   '" data-downloadid="' + value.download_id + '">'
                        +   '<i class="fa fa-trash"></i>'
                        +   '</button></td>'
                        +   '</tr>';
                    console.log('data');
                    console.log(result.data);
                });


            },
            complete: function(result) {
                if(ajaxToHtml != ''){
                    jQuery('.tableBody').html(ajaxToHtml);
                }
                else{
                    jQuery('.downloadAll ').remove();
                    jQuery('.tableBody').html('<tr><td colspan="3">Keine Einträge mehr in Merkliste</td></tr>');
                }

            },
            error: function(e) {
                console.log(e);
                console.log('ajax call failed');
            }
        });


    });

    /*
    jQuery('.downloadAll').live('click', function(event) {

        // default preventen
        event.preventDefault();

        // button auslesen aus event
        var $button = jQuery(event.target).closest('button');

        // wen nicht valide direkt returnen
        if(!$button) return;

        // auslesen infos
        var downloadId = $button.data('downloadid');

        // erster case: ist auf merkliste
        var token = jQuery("#token").attr("name");

        jQuery.ajax({
            data: { [token]: "1", task: "makeZipFromFiles", format: "raw"},
            success: function(result) {
                //var cleanedUpFilePath = result.data.filepath.replace(params,'');
                //jQuery('.tester').html('<a class="downloader hidden" href="../' + cleanedUpFilePath + '">' + cleanedUpFilePath  + '</a>');
                //jQuery(".downloader").trigger("click");
                //jQuery('.downloader')[0].click();
            },
            complete: function(result) {

            },
            error: function(e) {
                console.log(e);
                console.log('ajax call failed');
            }
        });


    })
    */;


});
function getUserDownloads(){
    var token = jQuery("#token").attr("name");
    var ajaxToHtml = '';
    jQuery.ajax({
        data: { [token]: "1", task: "showUserDownloads", format: "json"},
        success: function(result) {
            console.log( result.data.length);

            jQuery.each(result.data.downloads, function (index, value) {

                ajaxToHtml  +=    '<tr>'
                    +   '<td>' + value.download_titel + '</td>'
                    +   '<td>' + value.download_url  + '</td>'
                    +   '<td><button class="btnDelete btnDeletedownloadUserList btn btn-primary" id="buttonDelete-' + value.download_id
                    +   '" data-downloadid="' + value.download_id + '">'
                    +   '<i class="fa fa-trash"></i>'
                    +   '</button></td>'
                    +   '</tr>';
            });

        },

        complete: function(result) {
            if(ajaxToHtml != ''){
                jQuery('.tableBody').html(ajaxToHtml);
            }
            else{
                jQuery('.tableBody').html('<tr><td colspan="3" style="text-align: center">Keine Einträge mehr in Merkliste</td></tr>');
            }

        },
        error: function(e) {
            console.log(e);
            console.log('ajax call failed');
        }
    });
}
