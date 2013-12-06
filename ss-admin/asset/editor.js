var __ss = window.__ss || {};

__ss.editor = {
    getWindow : function() {
        var edi = document.getElementById("editor");
        return edi.contentWindow;
    },
    getDocument: function(){
        var edi = document.getElementById("editor");
        return edi.contentDocument || edi.contentWindow.document;
    },
    getDocumentBody: function(){
        var doc = this.getDocument();
        return doc.body || doc.getElementsByTagName("body")[0];
    },
    setDocumentBodyContent: function(html){
        var body = this.getDocumentBody();
        body.innerHTML = html;
    },
    getDocumentBodyContent: function(){
        var body = this.getDocumentBody();
        var html = mii.trim(body.innerHTML);
        html = html.replace(/<p><br([\s\/]*)><\/p>/i, "");
        html = html.replace(/<strike([^>]*)>(.*?)<\/strike>/i, "<s$1>$2</s>");
        return html;
    },
    insertHtml: function(html) {
        // http://stackoverflow.com/a/17439316/362780
        var doc = this.getDocument();
        if(doc.all) {
            var range = doc.selection.createRange();
            range.pasteHTML(html);
            range.collapse(false);
            range.select();
        } else {
            this.exec("inserthtml", html);
        }
    },
    insertImage: function(src){
        var img = "<img src='" + src + "' class='ss-item-image'>";
        this.insertHtml(img);
    },
    exec: function(cmd, content) {
        var win = this.getWindow();
        var doc = this.getDocument();
        if (cmd.toLowerCase() == "createlink") {
            content = prompt("Please enter target link below.\t\t\t\t\t\t\t\t\t\nFor emails: \"mailto:mail@mail.com\"",
                       "http://");
            if (content === "http://") return;
        }
        win.focus(); // sometimes does not work unless this
        doc.execCommand(cmd, false, content);
        win.focus();
    }
};

mii.onReady(function($){
    var setTextareaValue = function(){
        itemContent.value = __ss.editor.getDocumentBodyContent();
    };
    var getTextareaValue = function(){
        var value = $.trim(itemContent.value);
        value = value.replace(/(\r\n)/, "<br><br>");
        return value;
    };

    var iframeDocument = __ss.editor.getDocument();
    iframeDocument.designMode = "on";

    var iframeHead, iframeBody;
    iframeHead = $.dom("head", iframeDocument).first();
    iframeHead.append("<link href='/ss-admin/asset/editor-frame.css?"+ $.now() +"' rel='stylesheet'>");
    iframeHead.append("<script src='/ss-admin/asset/editor-frame.js?"+ $.now() +"'><\/script>");
    iframeBody = $.dom("body", iframeDocument).first();
    iframeBody.setAttr({id: "body", spellcheck: "false"});

    var editor = document.getElementById("editor");
    $.dom(editor.contentWindow).on("blur", function(){
        setTextareaValue(); editor.className = "";
    }).on("focus", function(){
        setTextareaValue(); editor.className = "editor-focus";
    }).on("keyup, keydown", function(){
        setTextareaValue();
    });

    var itemContent = document.getElementById("itemContent");
    $.dom(itemContent).on("keyup, keydown, change", function(){
        __ss.editor.setDocumentBodyContent(getTextareaValue());
    });

    // Form
    $.dom(".ss-admin-form").on("submit", function(e){
        setTextareaValue();
    });

    // Buttons
    var buttons = $.dom(".ss-admin-editor-buttons");
    buttons.find(".fa[role='button']").on("click", function(e){
        $.dom(this).toggleClass("fa-active");
    });
    buttons.find(".fa.e-list, .e-justify").on("click", function(e){
        $.dom(this).siblings().removeClass("fa-active");
    });
    buttons.find(".fa-eraser").on("click", function(e){
        buttons.find(".fa.e-format").removeClass("fa-active");
    });
    buttons.find(".e-toggle-editor").on("click", function(e){
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
    buttons.find(".fa-picture-o").on("click", function(){
        var modal = new __ss.modal({width: 750, height: 450});
        modal.open("Insert Image", "", function(){
            var iframe = $.dom("iframe", {
                width: "100%",
                height: "98%",
                frameBorder: 0,
                src: "/ss-admin/media_image_iframe.php"
            });
            iframe.appendTo(modal.body);
        });
    });

    // Set original height
    $.dom("#editor").setAttr("data-original-height", $.dom("#editor").height());
    buttons.find(".fa-plus-square, .fa-minus-square").on("click", function(){
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
});