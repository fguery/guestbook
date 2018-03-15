function refresh()
{
    jQuery('.errors').empty();
    jQuery.getJSON(
        '/messages',
        function(data) {
            jQuery('#guestbook tbody').empty();
            data.forEach(function (item) {
                jQuery('#guestbook tbody').append(
                    '<tr>'
                    + '<td>' + item['name'] + '</td>'
                    + '<td>' + item['creation_date'].replace(/(\.[0-9]+)/, '') + '</td>'
                    + '<td>' + item['comment'] + '</td>'
                    + '</tr>'
                );
            });
        }
    );
}

jQuery(document).ready(function () {
    refresh();

    jQuery('form#guestbookAdd').submit(function(e) {
        jQuery('.errors').empty();
        e.preventDefault();
        jQuery.ajax(
            '/messages',
            {
                method: 'POST',
                data: {
                    'email': jQuery('#email').val(),
                    'name': jQuery('#name').val(),
                    'comment': jQuery('#comment').val()
                },
                success: function () {
                    refresh();
                },
                error: function (xhr) {
                    jQuery('.errors').append(xhr.statusText);
                }
            }
        );
    });
});
