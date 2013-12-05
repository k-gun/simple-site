// command list: http://help.dottoro.com/larpvnhw.php
// insertImage varmis

var $ss = window.$ss || {};

$ss.editor = {
    $: function(id) {
        return document.getElementById(id);
    },
    frame: function() {
        if (window.frames)   { return window.frames["editor"]; }
        if (document.frames) { return document.frames["editor"]; }
        alert("Please use proper browser!");
    },
    exec: function(a, b) {
        var editor = this.$("editor");
        if (a.toLowerCase() == "createlink") {
            b = prompt("Please enter target link below.\t\t\t\t\t\t\t\t\t\nFor emails: \"mailto:mail@mail.com\"",
                       "http://");
            if (b === "http://") return;
        }
        editor.contentWindow.document.execCommand(a, false, b);
        editor.contentWindow.focus();
    }
};

mii.onReady(function($){
    var iframe = $ss.editor.frame();
    iframe.document.designMode = "on";
    iframe.document.open();
    iframe.document.write("<html>");
    iframe.document.write("<head><link rel='stylesheet' href='/ss-admin/asset/editor-frame.css?"+ $.now() +"'></head>");
    iframe.document.write("<body spellcheck='false'></body>");
    iframe.document.write("</html>");
    iframe.document.close();

    var editor = $ss.editor.$("editor");
    editor.contentWindow.onfocus = function(){
        setTextareaValue();
        editor.className = "editor-focus";
    };
    editor.contentWindow.onblur = function(){
        setTextareaValue();
        editor.className = "";
    };
    editor.contentWindow.onkeyup = editor.contentWindow.onkeydown = function() {
        setTextareaValue();
    };

    var itemContent = $ss.editor.$("itemContent");
    itemContent.onchange = function() {
        setEditorHtml();
    };

    // Form
    $.dom(".ss-admin-item-form").on("submit", function(e){
        setTextareaValue();
    });

    // Buttons
    var ssae = $.dom(".ss-admin-editor");
    ssae.find(".fa[role='button']").on("click", function(e){
        $.dom(this).toggleClass("fa-active");
    });
    ssae.find(".fa.e-list").on("click", function(e){
        $.dom(this).siblings().removeClass("fa-active");
    });
    ssae.find(".fa-eraser").on("click", function(e){
        ssae.find(".fa.e-format").removeClass("fa-active");
    });
    ssae.find(".e-toggle-editor").on("click", function(e){
        var el = $.dom(this);
        if (el.hasClass("fa-active")) {
            $.dom("#editor, .ss-admin-buttons").hide(0);
            $.dom("#itemContent").addClass("item-content-visible");
        } else {
            $.dom("#editor, .ss-admin-buttons").show(0);
            $.dom("#itemContent").removeClass("item-content-visible");
        }
    });

    // Image modal
    ssae.find(".fa-picture-o").on("click", function(){
        var modal = new $ss.modal({width: 750, height: 450});
        modal.open("Insert Image", "", function(){
            var $iframe = $.dom("<iframe>");
            $iframe.setAttr({width: "100%", height: "98%", frameBorder: 0, src: "/ss-admin/media_iframe.php"});
            // $iframe.setStyle("border", "1px solid #000");
            $iframe.appendTo(modal.body);
        });
    });

    // Set original height
    $.dom("#editor").setAttr("data-original-height", $.dom("#editor").height());
    ssae.find(".fa-plus-square, .fa-minus-square").on("click", function(){
        var el = $.dom(this);
        var ed = $.dom("#editor");
        if (ed.height() < parseInt(ed.getAttr("data-original-height"))) {
            ed.setStyle("height", ed.getAttr("data-original-height"));
            return;
        }

        if (el.hasClass("fa-plus-square")) {
            ed.setStyle("height", ed.height() + 30);
        } else if (el.hasClass("fa-minus-square")) {
            if (ed.height() - 30 <= parseInt(ed.getAttr("data-original-height"))) {
                ed.setStyle("height", ed.getAttr("data-original-height"));
                return;
            }
            ed.setStyle("height", ed.height() - 30);
        }
    });

    var setEditorHtml = function() {
        iframe.document.body.innerHTML = getTextareaValue();
    };
    var getEditorHtml = function(){
        var html = iframe.document.body.innerHTML;
        html = $.trim(""+ html);
        html = html.replace(/<p><br([\s\/]*)><\/p>/i, "");
        html = html.replace(/<strike([^>]*)>(.*?)<\/strike>/i, "<s$1>$2</s>");
        return html;
    };

    var setTextareaValue = function(){
        itemContent.value = getEditorHtml();
    };
    var getTextareaValue = function(){
        var value = itemContent.value;
        value = value.replace(/(\r\n)/, "<br><br>");
        return value;
    };
});