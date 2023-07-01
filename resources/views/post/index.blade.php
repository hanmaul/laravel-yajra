@extends('layouts.app')
@section('content')
<div class="container">

    <!-- Data Post -->
    <div class="row my-5">
        <div class="col-lg-12">
            <h2>Master Data Post</h2>
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="text-light">Manage Post</h3>
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalCreatePost">
                        <i class="bi-plus-circle me-2"></i>
                        Add New Post
                    </button >
                </div>
                <div class="car-body" id="show-all-posts">
                    <h1 class="text-center text-secondary my-5">Loading...</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create Post -->
    <div class="modal fade" id="modalCreatePost" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id="formCreatePost" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title" id="ModalLabel">
                        Add New Post</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form-result"></span>
                        <div class="form-group">
                            <label>Photo : </label>
                            <input type="file" name="pict" id="pict" class="form-control" required>
                        </div>
                        <div class="form-group my-2">
                            <label>Title : </label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="form-group my-2">
                            <label>Content :</label>
                            <textarea class="form-control" id="content" name="content" required></textarea>
                        </div>
                        <input type="hidden" name="action" id="action" value="Add">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnSubmitPost" class="btn btn-primary">Add Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Post -->
    <div class="modal fade" id="editPost">

    </div>

    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id="formCreatePost" class="form-horizontal" enctype="multipart/form-data">
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
<script> 
    $(function() {

        // add Post
        $("#formCreatePost").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $("#btnSubmitPost").text('Adding...');
            $.ajax({
                url: '{{ route('store') }}',
                method: 'post',
                data: fd, 
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        Swal.fire(
                            'Added!',
                            'Post Added Successfully!',
                            'success'
                        )
                        getAllPost();
                    }
                    $("#btnSubmitPost").text('Add Post');
                    $("#formCreatePost")[0].reset();
                    $("#modalCreatePost").modal('hide');
                }
            });
        });

        // get All Post ajax request
        getAllPost();

        function getAllPost() {
            $.ajax({
                url: '{{ route('getAll') }}',
                method: 'get',
                success: function(response) {
                    $("#show-all-posts").html(response);
                }   
            });
        }

    });
</script>
@endpush