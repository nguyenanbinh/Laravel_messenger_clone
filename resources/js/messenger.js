function imageReview(input, selector) {
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(selector).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}


$('#select_file').change(function (e) {
    imageReview(this, '.profile-image-preview');
});
