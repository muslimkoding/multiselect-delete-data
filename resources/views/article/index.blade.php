<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Multi-Select Delete</title>
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Select Items to Delete</h2>
        <form id="delete-form">
          <button type="button" id="delete-button" class="btn btn-danger mb-3">Delete Selected</button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selected_all_ids"></th>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Body</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            <input type="checkbox" name="ids[]" class="checkbox_ids" value="{{ $item->id }}">
                        </td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->body }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </form>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
      // function select all
    $(function(e) {
      $("#selected_all_ids").click(function() {
        $('.checkbox_ids').prop('checked', $(this).prop('checked'));
      });
    });

    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#delete-button').on('click', function() {
            var ids = [];
            $('input[name="ids[]"]:checked').each(function() {
                ids.push($(this).val());
            });

            if (ids.length > 0) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover these records!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('delete.selected') }}',
                            type: 'DELETE',
                            data: {
                                ids: ids
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your records have been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire(
                                    'Error!',
                                    'An error occurred while deleting records.',
                                    'error'
                                );
                                console.error('Error:', error);
                                console.error('Status:', status);
                                console.error('Response:', xhr.responseText);
                            }
                        });
                    }
                });
            } else {
                Swal.fire(
                    'No selection',
                    'Please select at least one item to delete.',
                    'info'
                );
            }
        });
    </script>
</body>
</html>
