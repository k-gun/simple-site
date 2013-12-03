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
    var editor = _e();
    editor.document.designMode = "on";
    editor.document.open();
    editor.document.write("<link rel='stylesheet' href='/ss-admin/asset/editor-frame.css?"+ (new Date).getTime() +"'>");
    editor.document.write("<body spellcheck='false'></body>");
    editor.document.close();

    editor = _("editor");
    editor.contentWindow.onfocus = function(){
        editor.className = "editor-focus";
    };
    editor.contentWindow.onblur = function(){
        editor.className = "";
    };

    // Buttons
    $.dom(".ss-admin-editor .fa[role='button']").on("click", function(e){
        $.dom(this).toggleClass("fa-active");
    });
    $.dom(".ss-admin-editor .fa[exec='justify']").on("click", function(e){
        $.dom(this).siblings().removeClass("fa-active");
    });
    $.dom(".ss-admin-editor .fa-eraser").on("click", function(e){
        $.dom(".ss-admin-editor .fa[exec='format']").removeClass("fa-active");
    });
});