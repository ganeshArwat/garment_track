<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
<script>
    function ask_confirmation(msg, url) {
        bootbox.confirm(msg, function(result) {
            if (result) {
                window.location = url;
            }
        });
    }
</script>