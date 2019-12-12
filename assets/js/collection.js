function handleDeleteButton() {
    $("button[data-action='delete']").click(function () {
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$("#trick_pictures div.form-group").length;
    const countVideo = +$("#trick_videos div.form-group").length;

    $("#widgets-counter-pictures").val(count);
    $("#widgets-counter-video").val(countVideo);
}

$("#add-pictures").click(function () {

    const index = +$("#widgets-counter-pictures").val();
    const tmpl = $("#trick_pictures").data("prototype").replace(/__name__/g, index);


    $("#trick_pictures").append(tmpl);
    bsCustomFileInput.init();
    $("#widgets-counter-pictures").val(index + 1);
    handleDeleteButton();
});

$("#add-video").click(function () {

    const index = +$("#widgets-counter-video").val();
    const tmpl = $("#trick_videos").data("prototype").replace(/__name__/g, index);

    $("#trick_videos").append(tmpl);
    $("#widgets-counter-video").val(index + 1);

    handleDeleteButton();
});

updateCounter();
handleDeleteButton();