
            $(function(){
            $(document).on('click', '#delete', function(e){
            e.preventDefault();
            var link = $(this).attr("href");

            Swal.fire({
                title: 'Are you sure?',
                        text: "Delete This Data!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = link
            Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                    )
            }
            })
            });
            });

                $(function(){
                    $(document).on('click', '#applicant', function(e){
                        e.preventDefault();
                        var form = $(this).closest('form');
                        var actionText = $(this).text().trim(); // Get the button text to determine action

                        Swal.fire({
                            title: 'Are you sure?',
                            text: actionText + " this applicant!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, ' + actionText.toLowerCase() + '!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit(); // Submit the form
                                Swal.fire(
                                    'Updated!',
                                    'Applicant status has been updated.',
                                    'success'
                                )
                            }
                        });
                    });
                });


                $(function(){
                    $(document).on('click', '#changeInterviewStatus', function(e){
                        e.preventDefault();
                        var form = $(this).closest('form');
                        var actionText = $(this).text().trim(); // Get the button text to determine action

                        Swal.fire({
                            title: 'Are you sure?',
                            text: actionText + " this applicant!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, ' + actionText.toLowerCase() + '!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit(); // Submit the form
                                Swal.fire(
                                    'Updated!',
                                    'Interview status has been updated.',
                                    'success'
                                )
                            }
                        });
                    });
                });





            $(function(){

                $(document).on('click','#ApproveBtn',function(e){
                e.preventDefault();
                var link = $(this).attr("href");

            Swal.fire({
                            // title: 'Are you sure?',
                            title:"You Can't Delete This Data After Approval",
                            text: "Approve This Data?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, Approve it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                            window.location.href = link
                            Swal.fire(
                                'Approved!',
                                'Your file has been Approved.',
                                'success'
                    )
            }
            })
            });
            });