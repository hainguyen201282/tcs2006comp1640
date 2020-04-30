$(document).ready(function () {
    // get student info when click button
    let dataInfo;
    $('.open-mes-modal').click(function () {
        dataInfo = {
            email: $(this).attr('data-email'),
            senderId: $(this).attr('data-senderId'),
            senderRole: $(this).attr('data-senderRole'),
            receiverId: $(this).attr('data-receiverId'),
            receiverRole: $(this).attr('data-receiverRole'),
        };
        $('#content').attr('required', true);
    });

    // display email
    $('#mes-modal').on('show.bs.modal', function () {
        $('#receiver').val(dataInfo.email);
    }).submit(function (event) {
        // get value from submit form
        const email = $('#receiver').val();
        const subject = $('#subject').val();
        const content = $('#content').val();

        const senderId = dataInfo.senderId;
        const senderRole = dataInfo.senderRole;

        const receiverId = dataInfo.receiverId;
        const receiverRole = dataInfo.receiverRole;

        // message entity
        const message = {
            subject: subject,
            content: content,
            senderId: senderId,
            senderRole: senderRole,

        };

        // message_attr entity
        const messageAttr = {
            receiverId: receiverId,
            receiverRole: receiverRole,
        };

        $.ajax({
            type: "POST",
            dataType: "json",
            url: baseURL + "sendMessage",
            data: {
                email: email,
                message: message,
                messageAttr: messageAttr,
            },
        }).done(function (data) {
            if (data.result === true) {
                alert("Send message successfully");
            } else {
                alert("Send message failure");
            }
        });

        $('#messageBoxReset').click();
        $('#closeMessageBox').click();
    })
});
