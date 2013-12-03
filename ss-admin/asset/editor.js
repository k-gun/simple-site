// command list: http://help.dottoro.com/larpvnhw.php
// insertImage varmis

function _(id) {
    return document.getElementById(id);
}

function _e() {
    if (window.frames) { return window.frames["editor"]; }
    if (document.frames) { return document.frames["editor"]; }
    alert("Please use proper browser!");
}

function _x(a, b) {
    _("editor").contentWindow.document.execCommand(a, false, b);
    _("editor").contentWindow.focus();
}

mii.onReady(function($){
    var iframe = _e();
    iframe.document.designMode = "on";
    iframe.document.open();
    iframe.document.write("<link rel='stylesheet' href='/ss-admin/asset/editor-frame.css?"+ (new Date).getTime() +"'>");
    iframe.document.write("<body spellcheck='false'></body>");
    iframe.document.close();

    var editor = _("editor");
    editor.contentWindow.onfocus = function(){
        editor.className = "editor-focus";
    };
    editor.contentWindow.onblur = function(){
        editor.className = "";
    };

    // Form
    $.dom(".ss-admin-item-update").on("submit", function(e){
        e.preventDefault();
        var html = iframe.document.body.innerHTML;
        html.replace(/<p><br([\s\/]*)><\/p>/, "");
        alert(html);
    });

    // Buttons
    var ssAdminEditor = $.dom(".ss-admin-editor");
    ssAdminEditor.find(".fa[role='button']").on("click", function(e){
        $.dom(this).toggleClass("fa-active");
    });
    ssAdminEditor.find(".fa[exec='list']").on("click", function(e){
        $.dom(this).siblings().removeClass("fa-active");
    });
    ssAdminEditor.find(".fa[exec='justify']").on("click", function(e){
        $.dom(this).siblings().removeClass("fa-active");
    });
    ssAdminEditor.find(".fa-eraser").on("click", function(e){
        ssAdminEditor.find(".fa[exec='format']").removeClass("fa-active");
    });
});