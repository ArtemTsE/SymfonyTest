$(document).ready(function(){
    var phoneNumberTemplate = $('#phoneNumberTemplate');
    var addContactPath      = $('#addContactPath').data('value');

    if (phoneNumberTemplate.length) {
        $('input#contact_phone').mask(phoneNumberTemplate.data('value'));
    }

    $('form[name="contact"]').submit(function(event) {
        event.preventDefault();

        if (!addContactPath.length) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: addContactPath,
            data: $(this).serialize(),

            success: function(data) {
                let contactInput = $('input#contact_phone');
                contactInput.removeClass('is-invalid');

                let messageErrorBlock = $('.contact-form-messages #form-message-error-block');
                let messageSuccessBlock = $('.contact-form-messages #form-message-success-block');
                messageErrorBlock.addClass('d-none');
                messageSuccessBlock.addClass('d-none');

                if (data.result == false) {
                    $(messageErrorBlock).find('span').text(data.message);
                    messageErrorBlock.removeClass('d-none');
                    contactInput.addClass('is-invalid');
                } else
                {
                    $(messageSuccessBlock).find('span').text(data.message);
                    messageSuccessBlock.removeClass('d-none');
                }
            },
            error: function (xhr, desc, err)
            {}
        });

    });
});

