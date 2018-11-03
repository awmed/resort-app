<?php
/**
 * footer_end.php
 *
 * Author: pixelcave
 *
 * The last block of code used in every page of the template
 *
 * We put it in a separate file for consistency. The reason we separated
 * footer_start.php and footer_end.php is for enabling us
 * put between them extra JavaScript code needed only in specific pages
 *
 */
?>
        <script>
            function signOut() {
                swal({
                title: 'Are you sure?',
                text: 'You will be signed out!',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d26a5c',
                confirmButtonText: 'Yes, sign out!',
                html: false,
                preConfirm: function() {
                    return new Promise(function (resolve) {
                        setTimeout(function () {
                            resolve();
                        }, 50);
                    });
                }
            }).then(function(result){
                if (result.value) {
                    //swal('Deleted!', 'Your imaginary file has been deleted.', 'success');
                    window.location = "sign_out.php";
                    // result.dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
                } 
            });
            };
        </script>
    </body>
</html>