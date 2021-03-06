$(function() {
    // add message to container
    var add_message = function(message, method) {
        method = method || 'prepend';
        var template = '<h3>%title%<span>%created_at%</span></h3><p>%body%</p>';
        $(['title', 'body', 'created_at']).each(function() {
            template = template.replace('%'+this+'%', message[this]);
        });
        $('#messages')[method](template);
    };
    var add_more = function(data) {
        if (data.more) {
            if (!$('#more a').length) {
                $('<a href="">More</a>')
                    .click(function() {
                        load_messages($(this).attr('href'));
                        return false;
                    })
                    .appendTo('#more');
            }
            $('#more a').attr('href', '?action=list&o='+(parseInt(data.o)+parseInt(data.l))+'&l='+data.l);
        } else {
          $('#more').html('');
        }
    };
    var load_messages = function(url) {
        // receive message list
        $.getJSON(url, function(data) {
            $(data.messages).each(function() { add_message(this, 'append'); });
            add_more(data);
        });
    }

    load_messages('?action=list');

    var form_send = function(e) {
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $.param($('input, textarea', this)),
            dataType: 'json',
            success: function(data) { add_message(data); }
        });
        $(this).hide();
        $('#new').show();
        return false;
    };

    // add new message
    $('#new').click(function() {
        $.ajax({
            url: $(this).attr('href'),
            type: 'GET',
            success: function(data, textStatus, XMLHttpRequest) {
                $('#new').hide();
                $('#form').html(data);
                $('#form').show();
                $('#form form').submit(form_send);
            }
        });
        return false;
    });
});