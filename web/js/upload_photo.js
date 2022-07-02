$(document).ready(function () {
    var cropper;
    function destroy(){
        var data = { };
        data.file = $("#crop_icon").attr("src");
        var blob= new Blob([JSON.stringify(data)], {type : 'application/json; charset=UTF-8'});
        console.log("destroy");
        console.log(data);
        navigator.sendBeacon("/photo/destroy", blob);
    }

    function initEvents() {
        $('.close').on('click', function (e) {
            console.log($("#crop_icon").attr("src"), "SRC");
            destroy();
        });
       $('#btn-close').on('click', function (e) {
            console.log($("#crop_icon").attr("src"), "SRC");
            destroy();
        });

       $('.delete').on("click", function(){
           var form_data = new FormData();
           form_data.append('obj_id', $("#obj_id").val());
           form_data.append('file', $("#icon").attr('src'));
           form_data.append('type',$(".delete").attr('data-type'));
           $.ajax({
               url        : "/photo/delete",
               cache      : false,
               contentType: false,
               processData: false,
               data       : form_data,
               dataType   : "JSON",
               type       : "post",
               success    : function (data) {
                   $("#icon").attr('src', "/uploads/profile/default.png");

               },
           });
       });

        console.log("INIT EVENTS HERE");
        var upload_photo_id;
        $(".js-show-upload-image").on("click", function () {
            $("#btn_upload").val('');
        })
        $(".js-show-upload-icon").on("click", function () {
            $("#btn_upload").val('');
        })

        $("#btn_upload").on("change", function () {
            console.log($("#btn_upload").attr('data-type'));
            if ($("#btn_upload").attr('data-type') == 7) {

                 upload_photo_id = $(".js-show-upload-icon").attr('data-id');
                 console.log($(".js-show-upload-icon").attr('data-id'));
            }

            if ($("#btn_upload").attr('data-type') == 8) {
                 upload_photo_id = $(".js-show-upload-image").attr('data-id');
            }

            console.log(upload_photo_id, "UF");
            var file_data = $(this).prop("files")[0];
            var form_data = new FormData();
            form_data.append("upload_photo_id", upload_photo_id);
            form_data.append("upload_user_id", $("#upload_user_id").val());
            form_data.append("obj_id", $("#obj_id").val());
            form_data.append("type", $("#btn_upload").attr('data-type'));
            form_data.append("file", file_data, file_data["name"]);
            $.ajax({
                url        : "/photo/upload",
                cache      : false,
                contentType: false,
                processData: false,
                data       : form_data,
                dataType   : "JSON",
                type       : "post",
                success    : function (data) {
                    if (data.type == 5 || data.type == 7) {
                        initCropperIcon(data.file_full, data.type);
                    }
                    if (data.type == 6 || data.type == 8) {
                        initCropperImage(data.file_full, data.type);
                    }
                },
            });
        });
        $(".js-show-upload-icon").click(function () {
            if (cropper) {
                cropper.destroy();
            }
            $("#crop_icon").attr("src", "");
            $("#crop_image").attr("src", "");
            $(".btn_upload").val(null);
            var photo_id = $(this).data("id");
            $("#upload_photo_id").val(photo_id);
            var type = $(this).data('type');
            $("#btn_upload").attr('data-type', type);
            $("#crop_image").attr("src", "");
            $("#modal_photo_upload").addClass("show");
        });
        $(".js-show-upload-image").click(function () {
            if (cropper) {
                cropper.destroy();
            }
            $("#crop_icon").attr("src", "");
            $("#crop_image").attr("src", "");
            $(".btn_upload").val(null);
            var photo_id = $(this).data("id");
            var type     = $(this).data('type');
            $("#btn_upload").attr('data-type', type);
            $("#upload_photo_id").val(photo_id);
            $("#crop_image").attr("src", "");
            $("#modal_photo_upload").addClass("show");
        });
    }
    function initCropperIcon(full_link, type) {
        $(".btn-secondary").on("click", function () {
            cropper.destroy();
            $("#crop_icon").attr("src", "");
            $("#crop_image").attr("src", "");
        })
        if (cropper) {
            cropper.destroy();
        }

        $("#crop_icon").attr("src", full_link);
        var image = document.getElementById("crop_icon");
        cropper   = new Cropper(image, {
            aspectRatio       : NaN,
            InitialAspectRatio: NaN,
            minCropBoxWidth   : 192,
            minCropBoxHeight  : 192,
            viewMode: 1,
            crop(event) {
                console.log(event.detail.x);
                console.log(event.detail.y);
                console.log(event.detail.width);
                console.log(event.detail.height);
                console.log(event.detail.rotate);
                console.log(event.detail.scaleX);
                console.log(event.detail.scaleY);
            },
        });

        $("#btn_crop").on("click", function () {
            cropper.getCroppedCanvas().toBlob(function (blob) {
                var form_data = new FormData();
                var type      = $("#btn_upload").attr('data-type');
                form_data.append("upload_photo_id", $(".js-show-upload-icon").attr('data-id'));
                form_data.append("upload_user_id", $("#upload_user_id").val());
                form_data.append("obj_id", $("#obj_id").val());
                form_data.append("type", type);
                form_data.append("file", blob, $("#crop_icon").attr("src"));
                $.ajax({
                    url        : "/photo/crop",
                    cache      : false,
                    contentType: false,
                    processData: false,
                    data       : form_data,
                    dataType   : "JSON",
                    type       : "post",
                    success    : function (data) {
                        console.log(data, "RES");
                        $("#icon").attr('src', data.file);
                        cropper.destroy();
                        $('.modal').modal('hide');
                        //$("#modal_photo_upload").removeClass("show");
                        $("#ppc-file-icon").val('');
                        $('#profile-photo').val(data.file);
                        $("#ppc-file-icon").val(JSON.stringify(data.ppc_file));
                        $("#btn_upload").val('');
                        //  window.location.reload();
                    },
                });
            });
        });

    }
    function initCropperImage(full_link, type) {
        $(".btn-secondary").on("click", function () {
            cropper.destroy();
            $("#crop_icon").attr("src", "");
            $("#crop_image").attr("src", "");
        })
        if (cropper) {
            cropper.destroy();
        }
        $("#crop_image").attr("src", full_link);
        var image = document.getElementById("crop_image");
        cropper   = new Cropper(image, {
            aspectRatio       : NaN,
            InitialAspectRatio: NaN,
            minCropBoxWidth   : 492,
            minCropBoxHeight  : 328,
            viewMode          : 1,
            crop(event) {
                console.log(event.detail.x);
                console.log(event.detail.y);
                console.log(event.detail.width);
                console.log(event.detail.height);
                console.log(event.detail.rotate);
                console.log(event.detail.scaleX);
                console.log(event.detail.scaleY);
            },
        });
        $("#btn_crop").on("click", function () {
            cropper.getCroppedCanvas().toBlob(function (blob) {
                var form_data = new FormData();
                $("#btn_upload").attr('data-type');
                form_data.append("upload_photo_id", $(".js-show-upload-image").attr('data-id'));
                form_data.append("upload_user_id", $("#upload_user_id").val());
                form_data.append("obj_id", $("#obj_id").val());
                form_data.append("type", type);
                form_data.append("file", blob, $("#crop_image").attr("src"));

                $.ajax({
                    url        : "/photo/crop",
                    cache      : false,
                    contentType: false,
                    processData: false,
                    data       : form_data,
                    dataType   : "JSON",
                    type       : "post",
                    success    : function (data) {
                        $("#image").attr('src', data.file);
                        $("#modal_photo_upload").removeClass("show");
                        $("#ppc-file-image").val('');
                        $("#ppc-file-image").val(JSON.stringify(data.ppc_file));
                        $("#btn_upload").val('');
                    },
                });
            });
        });
    }
    initEvents();

});
