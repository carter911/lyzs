$.fn.pasteUploadImage = function (options) {
    options = $.extend({}, {
        ajaxUrl: '',
        loading: null,
        sucCall: function () {
        },
        errCall: function () {
        }
    }, options);
    return this.each(function () {
        var $ajaxUrl = options.ajaxUrl;
        var $this = $(this);
        $this.on('paste', function (event) {
            var filename, image, pasteEvent, text;
            pasteEvent = event.originalEvent;
            if (pasteEvent.clipboardData && pasteEvent.clipboardData.items) {
                image = isImage(pasteEvent);
                if (image) {
                    event.preventDefault();
                    filename = getFilename(pasteEvent) || "image.png";
                    text = "{{" + filename + "(uploading...)}}";
                    pasteText(text);
                    return uploadFile(image.getAsFile(), filename);
                } else {
                    options.sucCall.call(this, {code: 1, msg: "粘贴进文本的数据不是图片，需粘贴图片。"});
                }
            }
        });
        $this.on('drop', function (event) {
            var filename, image, pasteEvent, text;
            pasteEvent = event.originalEvent;
            if (pasteEvent.dataTransfer && pasteEvent.dataTransfer.files) {
                image = isImageForDrop(pasteEvent);
                if (image) {
                    event.preventDefault();
                    filename = pasteEvent.dataTransfer.files[0].name || "image.png";
                    text = "{{" + filename + "(uploading...)}}";
                    pasteText(text);
                    return uploadFile(image, filename);
                }
            }
        });

        function pasteText(text) {
            var afterSelection, beforeSelection, caretEnd, caretStart, textEnd;
            caretStart = $this[0].selectionStart;
            caretEnd = $this[0].selectionEnd;
            textEnd = $this.val().length;
            beforeSelection = $this.val().substring(0, caretStart);
            afterSelection = $this.val().substring(caretEnd, textEnd);
            $this.val(beforeSelection + text + afterSelection);
            $this.get(0).setSelectionRange(caretStart + text.length, caretEnd + text.length);
            return $this.trigger("input");
        };

        function isImage(data) {
            var i, item;
            i = 0;
            while (i < data.clipboardData.items.length) {
                item = data.clipboardData.items[i];
                if (item.type.indexOf("image") !== -1) {
                    return item;
                }
                i++;
            }
            return false;
        };

        function isImageForDrop(data) {
            var i, item;
            i = 0;
            while (i < data.dataTransfer.files.length) {
                item = data.dataTransfer.files[i];
                if (item.type.indexOf("image") !== -1) {
                    return item;
                }
                i++;
            }
            return false;
        };

        function getFilename(e) {
            var value;
            if (window.clipboardData && window.clipboardData.getData) {
                value = window.clipboardData.getData("Text");
            } else if (e.clipboardData && e.clipboardData.getData) {
                value = e.clipboardData.getData("text/plain");
            }
            value = value.split("\r");
            return value[0];
        };

        function getMimeType(file, filename) {
            var mimeType = file.type;
            var extendName = filename.substring(filename.lastIndexOf('.') + 1);
            if (mimeType != 'image/' + extendName) {
                return 'image/' + extendName;
            }
            return mimeType
        };

        function uploadFile(file, filename) {
            var formData = new FormData();
            var $loading = $(options.loading);
            formData.append('file', file);
            formData.append("mimeType", getMimeType(file, filename));

            !!$loading && !!$loading.length && $loading.show();
            $.ajax({
                url: $ajaxUrl,
                data: formData,
                type: 'post',
                processData: false,
                contentType: false,
                dataType: 'json',
                xhrFields: {
                    withCredentials: true
                },
                success: function (data) {
                    options.sucCall.call(this, data);
                },
                error: function (xOptions, textStatus) {
                    !!$loading && !!$loading.length && $loading.hide();
                    options.errCall.call(this, {
                        xOptions: xOptions,
                        textStatus: textStatus
                    });
                }
            });
        };

        function insertToTextArea(filename, url) {
            return $this.val(function (index, val) {
                return val.replace("{{" + filename + "(uploading...)}}", "![" + filename + "](" + url + ")" + "\n");
            });
        };

        function replaceLoadingTest(filename) {
            return $this.val(function (index, val) {
                return val.replace("{{" + filename + "(uploading...)}}", filename + "\n");
            });
        };

    });

};
