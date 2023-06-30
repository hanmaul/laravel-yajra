@extends('layouts.app')
@section('content')
<div class="container">

    <!-- Data Post -->
    <div class="row">
        <div class="col-12 table-responsive">
            <br>
            <h3 align="center">Master Data Post</h3>
            <br>
            <div align="right">
                <button type="button" name="createPost" id="createPost" class="btn btn-success"><i class="bi bi-plus-square"></i> Create</button>
            </div>
            <br>
            <table class="table table-striped table-bordered post-datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th width="180px">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Modal Post -->
    <div class="modal fade" id="modalPost" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id="formPost" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title" id="ModalLabel">
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form-result"></span>
                        <div class="form-group">
                            <label>Photo : </label>
                            <input type="file" name="pict" id="pict" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Title : </label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Content :</label>
                            <textarea class="form-control" id="content" name="content" required></textarea>
                        </div>
                        <input type="hidden" name="action" id="action" value="Add">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" value="Add" id="action_button" name="action_button" class="btn btn-info">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id="formPost" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title" id="ModalLabel">Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 align="center" style="margin:0;">Are you sure want to remove this product?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="ok_button" name="ok_button" class="btn btn-danger">OK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('.post-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('posts.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'pict',
                    name: 'pict'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'content',
                    name: 'content'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#createPost').click(function() {
            $('.modal-title').text('Add New Post');
            $('#action_button').val('Add');
            $('#action').val('Add');
            $('#form-result').html('');

            $('#modalPost').modal('show');
        });

        $('#formPost').on('submit', function(event) {
            event.preventDefault();
            const fd = new FormData(this);
            if ($('#action').val() == 'Add') {
                var action_url = "{{ route('posts.store') }}";
                $.ajax({
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: action_url,
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        console.log('success: ' + data);
                        var html = '';
                        if (data.errors) {
                            html = '<div class="alert alert-danger">';
                            for (var count = 0; count < data.errors.length; count++) {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if (data.success) {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#formPost')[0].reset();
                            $('#post-datatable').DataTable().ajax.reload();
                        }
                        $('#form-result').html(html);
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
            } else if ($('#action').val() == 'Edit') {
                var id = document.getElementById("hidden_id").value;
                // action_url = "{{ route('products.update'," + id + ") }}"
                action_url = "/products/" + id,
                    $.ajax({
                        type: 'patch',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: action_url,
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(data) {
                            console.log('success: ' + data);
                            var html = '';
                            if (data.errors) {
                                html = '<div class="alert alert-danger">';
                                for (var count = 0; count < data.errors.length; count++) {
                                    html += '<p>' + data.errors[count] + '</p>';
                                }
                                html += '</div>';
                            }
                            if (data.success) {
                                html = '<div class="alert alert-success">' + data.success + '</div>';
                                $('#formPost')[0].reset();
                                $('#post-datatable').DataTable().ajax.reload();
                            }
                            $('#form-result').html(html);
                        },
                        error: function(data) {
                            var errors = data.responseJSON;
                            console.log(errors);
                        }
                    });
            }
        });

        // $(document).on('click', '.edit', function(event) {
        //     event.preventDefault();
        //     var id = $(this).attr('id');
        //     $('#form-result').html('');

        //     $.ajax({
        //         url: "/products/" + id + "/edit/",
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         dataType: "json",
        //         success: function(data) {
        //             console.log('success: ' + data);
        //             $('#name').val(data.result.name);
        //             $('#details').val(data.result.details);
        //             $('#hidden_id').val(id);
        //             $('.modal-title').text('Edit Product');
        //             $('#action_button').val('Update');
        //             $('#action').val('Edit');
        //             $('#modalPost').modal('show');
        //         },
        //         error: function(data) {
        //             var errors = data.responseJSON;
        //             console.log(errors);
        //         }
        //     })
        // });

        // $(document).on('click', '.delete', function() {
        //     var user_id = $(this).attr('id');
        //     $('#hidden_id').val(user_id);
        //     $('#confirmModal').modal('show');

        //     $('#ok_button').click(function() {
        //         var id = document.getElementById("hidden_id").value;
        //         $.ajax({
        //             type: 'delete',
        //             url: "/products/" + id,
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             beforeSend: function() {
        //                 $('#ok_button').text('Deleting...');
        //             },
        //             success: function(data) {
        //                 setTimeout(function() {
        //                     $('#confirmModal').modal('hide');
        //                     $('#post-datatable').DataTable().ajax.reload();
        //                     alert('Data Deleted');
        //                 }, 2000);
        //             }
        //         })
        //     });
        // });
    });
</script>
@endpush