$(document).ready(function () {
    var item = $(".carousel-inner").find('.carousel-item').first();
    var height = $("#options").attr("data-height");
    var width  = $("#options").attr("data-width");
    item.addClass('active');
    $(".redo").on("click", function(e){
        e.preventDefault();
        var div =  $(this).parent();
        var img = div.next().find('.img-fluid');
        console.log(img, "IMG");
        console.log(div, "DIV");
        $("#icon").attr("src", img.attr('src'));
        $("#link").val(' ');
        $("#link").val(img.attr('src'));
        $("#obj_id").val(' ');
        console.log(img.attr('id'), "ID");
        $("#photo_id").val(img.attr('id'));
    });

    var cropper;
    var controller_id;
    function destroy(){
        var data = { };
        data.file = $("#crop_icon").attr("src");
        var blob= new Blob([JSON.stringify(data)], {type : 'application/json; charset=UTF-8'});
        console.log("destroy");
        console.log(data);
        navigator.sendBeacon(controller_id + "/destroy", blob);
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
        controller_id = $("#controller_id").val();
        console.log(controller_id, "CONTROLLER");
        $('.rem').on("click", function(e){
            e.preventDefault();
            var form_data = new FormData();
            var div =  $(this).parent();
            var img = div.next().find('.img-fluid');
            var photo_id = img.attr('id');
            var file = img.attr("src");

            form_data.append('obj_id', photo_id);
            form_data.append('file', file);
            $.ajax({
                url        : controller_id + "/remove",
                cache      : false,
                contentType: false,
                processData: false,
                data       : form_data,
                dataType   : "JSON",
                type       : "post",
                success    : function (data) {
                    $("#link").val(' ');
                    $("#photo_id").val(0);
                    console.log(div, "DIV");
                    console.log(div.parent());
                    var next = div.parent().next();
                    var prev = div.parent().prev();
                    console.log(prev.length, "L");
                    div.parent().remove();
                    if(prev.length == 0 && next.length == 0) {
                        console.log(1);
                    }
                    if(prev.length != 0 && next.length == 0) {
                        prev.addClass('active');
                    }
                    if(prev.length == 0 && next.length != 0) {
                        console.log(3);
                        next.addClass('active');
                    }
                    if(prev.length != 0 && next.length != 0) {
                        next.addClass('active');
                    }
                    console.log(div.parent().next());
                    console.log(div.parent().prev());
                    alert("Фото было удалено");
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
                url        : controller_id + "/upload",
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
        cropper = new Cropper(image, {
            aspectRatio       : NaN,
            InitialAspectRatio: NaN,
            minCropBoxWidth: width,
            minCropBoxHeight: height,
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
        /*cropper   = new Cropper(image, {
            aspectRatio       : NaN,
            InitialAspectRatio: NaN,
            minCropBoxWidth   : width,
            minCropBoxHeight  : height,
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
        }); */

        $("#btn_crop").on("click", function () {
            cropper.getCroppedCanvas().toBlob(function (blob) {
                var form_data = new FormData();
                var type      = $("#btn_upload").attr('data-type');
                form_data.append("upload_photo_id", $(".js-show-upload-icon").attr('data-id'));
                form_data.append("upload_user_id", $("#upload_user_id").val());
                form_data.append("obj_id", $("#photo_id").val());
                form_data.append("type", type);
                form_data.append("width", width);
                form_data.append("height", height);
                form_data.append("file", blob, $("#crop_icon").attr("src"));
                $.ajax({
                    url        : controller_id + "/crop",
                    cache      : false,
                    contentType: false,
                    processData: false,
                    data       : form_data,
                    dataType   : "JSON",
                    type       : "post",
                    success    : function (data) {
                        console.log(data, "RES");
                        $("#icon").attr('src', data.filepath);
                        $("#link").val(data.filepath);
                        $("#photo_id").val(data.photo_id);
                        cropper.destroy();
                        $('.modal').modal('hide');
                        //$("#modal_photo_upload").removeClass("show");
                        $("#ppc-file-icon").val('');
                        //$('#profile-photo').val(data.file);
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
                    url        : controller_id + "/crop",
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
