$(document).ready(function() {
    $('#accountSetting').on('click', function() {
        var userId = $(this).attr('data-id');
        $.ajax({
            url: 'process_getaccountdata.php',
            type: 'GET',
            data: { id: userId },
            dataType: 'json',
            success: function(data) {
                $('#editFirstName').val(data.firstname);
                $('#editLastName').val(data.lastname);
                $('#editUsername').val(data.username);
                $('#editUserId').val(data.user_id);
                $('#editEmail').val(data.email);
                $('#editAccountModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data: " + error);
            }
        });
    });
});