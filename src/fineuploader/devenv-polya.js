qq(window).attach("load", function() {
    "use strict";

    var errorHandler = function(event, id, fileName, reason) {
            return qq.log("id: " + id + ", fileName: " + fileName + ", reason: " + reason);
        },
        azureUploader, s3Uploader, manualUploader, validatingUploader, failingUploader;

    manualUploader = new qq.FineUploader({
        element: document.getElementById("manual-example-polya"),
        autoUpload: false,
        debug: true,
        uploadButtonText: "Select Files",
        display: {
            fileSizeOnSubmit: true
        },
        request: {
            endpoint: "./src/fineuploader/server/endpoint.php"
        },
        deleteFile: {
            enabled: false,
            endpoint: "./src/fineuploader/server/endpoint.php",
            forceConfirm: true,
            params: {
                foo: "bar"
            }
        },
	validation: {
	    itemLimit: 1
	},
        chunking: {
            enabled: false,
	    partSize: 100000000,
            concurrent: {
                enabled: true
            },
            success: {
                endpoint: "./src/fineuploader/server/endpoint.php?done"
            }
        },
        resume: {
            enabled: true
        },
        retry: {
            enableAuto: true
        },
        thumbnails: {
            placeholders: {
                waitingPath: "./src/fineuploader/pic/waiting-generic.png",
                notAvailablePath: "./src/fineuploader/pic/not_available-generic.png"
            }
        },
        scaling: {
            sizes: [{name: "small", maxSize: 300}, {name: "medium", maxSize: 600}]
        },
        callbacks: {
            onError: errorHandler,
            onUpload: function (id, filename) {
                this.setParams({
                    "hey": "hi ɛ $ hmm \\ hi",
                    "ho": "foobar-polya"
                }, id);

            },
            onStatusChange: function (id, oldS, newS) {
                qq.log("id: " + id + " " + newS);
            },
            onComplete: function (id, name, response) {
		var real = name.substring(0,name.indexOf("."));
		var file = "li.qq-file-id-"+ id +".qq-upload-success";
		$(file).append('<input name="group-' + real + '" placeholder="Input group here" style="width:120px;height:25px"/>');
                qq.log(response);
            }
        }
    });

    qq(document.getElementById("triggerUpload-polya")).attach("click", function() {
        manualUploader.uploadStoredFiles();
    });



});
