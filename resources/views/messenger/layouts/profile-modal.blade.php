<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-body">
            <form action="#" id="profile-form" enctype="multipart/form-data">
                @csrf
                <div class="file">
                    <img src="{{ asset(auth()->user()->avatar) }}" alt="Upload" class="img-fluid profile-image-preview">
                    <label for="select_file"><i class="fal fa-camera-alt"></i></label>
                    <input id="select_file" type="file" hidden name="avatar">
                </div>
                <p>Edit information</p>
                <input type="text" value="{{ auth()->user()->name }}" placeholder="Name" name="name">
                <input type="text" value="{{ auth()->user()->user_name }}" placeholder="User Name" name="user_name">
                <input type="email" value="{{ auth()->user()->email }}" placeholder="Email" name="email">
                <p>Change password</p>
                <div class="row">
                    <div class="col-xl-6">
                        <input type="password" placeholder="Current Password" name="current_password">
                    </div>
                    <div class="col-xl-6">
                        <input type="password" placeholder="New Password" name="password">
                    </div>
                    <div class="col-xl-12">
                        <input type="password" placeholder="Confirm Password" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer p-0 mt-3">
                    <button type="button" class="btn btn-secondary cancel"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary save save-profile">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        $('#profile-form').on('submit',function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const saveBtn = $('.save-profile');

            $.ajax({
                type: "POST",
                url: "{{ route('profile.update') }}",
                data: formData,
                processData: false,
                contentType: false,
                beforSend: function () {
                        saveBtn.text('saving...');
                        saveBtn.prop('disabled', true);
                },
                success: function (response) {
                    window.location.reload();
                },
                error: function (xhr, status, error) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (index, value) {
                        notyf.error(value[0]);
                    });

                    saveBtn.text('Save changes');
                    saveBtn.prop("disabled", false);
                }
            });
        });
    });

</script>
@endpush
