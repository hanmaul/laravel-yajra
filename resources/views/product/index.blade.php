@extends('layouts.app')
@section('content')
<div class="container">
    <!-- Data Produk -->
    <div class="row">
        <div class="col-12 table-responsive">
            <br>
            <h3 align="center">Master Data Product</h3>
            <br>
            <div align="right">
                <button type="button" name="createProduct" id="createProduct" class="btn btn-success"><i class="bi bi-plus-square"></i> Create</button>
            </div>
            <br>
            <table class="table table-striped table-bordered product-datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Details</th>
                        <th width="180px">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <!-- Modal Product -->
    <div class="modal fade" id="modalProduct" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id="formProduct" class="form-horizontal">
                    <div class="modal-header">
                        <h6 class="modal-title" id="ModalLabel">
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form-result"></span>
                        <div class="form-group">
                            <label>Product : </label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Details :</label>
                            <textarea class="form-control" id="details" name="details"></textarea>
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
                <form method="post" id="formProduct" class="form-horizontal">
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
        var table = $('.product-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'details',
                    name: 'details'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#createProduct').click(function() {
            $('.modal-title').text('Add New Product');
            $('#action_button').val('Add');
            $('#action').val('Add');
            $('#form-result').html('');

            $('#modalProduct').modal('show');
        });

        $('#formProduct').on('submit', function(event) {
            event.preventDefault();
            var action_url = '';

            if ($('#action').val() == 'Add') {
                action_url = "{{ route('products.store') }}";
                $.ajax({
                    type: 'post',
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
                            $('#formProduct')[0].reset();
                            $('#product-datatable').DataTable().ajax.reload();
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
                                $('#formProduct')[0].reset();
                                $('#product-datatable').DataTable().ajax.reload();
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

        $(document).on('click', '.edit', function(event) {
            event.preventDefault();
            var id = $(this).attr('id');
            $('#form-result').html('');

            $.ajax({
                url: "/products/" + id + "/edit/",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(data) {
                    console.log('success: ' + data);
                    $('#name').val(data.result.name);
                    $('#details').val(data.result.details);
                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Product');
                    $('#action_button').val('Update');
                    $('#action').val('Edit');
                    $('#modalProduct').modal('show');
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            })
        });

        $(document).on('click', '.delete', function() {
            var user_id = $(this).attr('id');
            $('#hidden_id').val(user_id);
            $('#confirmModal').modal('show');

            $('#ok_button').click(function() {
                var id = document.getElementById("hidden_id").value;
                $.ajax({
                    type: 'delete',
                    url: "/products/" + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#ok_button').text('Deleting...');
                    },
                    success: function(data) {
                        setTimeout(function() {
                            $('#confirmModal').modal('hide');
                            $('#product-datatable').DataTable().ajax.reload();
                            alert('Data Deleted');
                        }, 2000);
                    }
                })
            });
        });
    });
</script>
@endpush