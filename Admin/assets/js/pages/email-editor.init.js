/*
Template Name: Upzet - Responsive Bootstrap 4 Admin Dashboard
Author: Themesdesign
Version: 1.3.0
Website: https://themesdesign.in/
File: Quill Js File
*/


var quill = new Quill("#email-editor", {
    theme: "snow",
    modules: {
        toolbar: [
            [{
                font: []
            }, {
                size: []
            }],
            ["bold", "italic", "underline", "strike"],
            [{
                color: []
            }, {
                background: []
            }],
            [{
                script: "super"
            }, {
                script: "sub"
            }],
            [{
                header: [!1, 1, 2, 3, 4, 5, 6]
            }, "blockquote", "code-block"],
            [{
                list: "ordered"
            }, {
                list: "bullet"
            }, {
                indent: "-1"
            }, {
                indent: "+1"
            }],
            ["direction", {
                align: []
            }],
            ["link", "image", "video"],
            ["clean"]
        ]
    }
});