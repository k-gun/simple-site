var Editor = {
    http: ENV.get("http"),
    init: function() {
        var iframe  = document.createElement("iframe");
        iframe.setAttribute("id", "editor");
        iframe.setAttribute("name", "editor");
        iframe.setAttribute("frameborder", "0");
        document.getElementById("editorInner").appendChild(iframe);

        var edi = this.edi();
        edi.document.designMode = "on";
        edi.document.open();
        edi.document.write("<link rel='stylesheet' href='"+ this.http +"site/asset/css/editor.css?"+ (new Date).getTime() +"' \/>");
        edi.document.write("<body spellcheck='false'></body>");
        edi.document.close();

        if (document.addEventListener) {
            document.addEventListener("mousedown", dismissButtons, true);
            document.getElementById("editor").contentWindow.document.addEventListener("mousedown", dismissButtons, true);
        } else if (document.attachEvent) {
            document.attachEvent("onmousedown", dismissButtons, true);
            document.getElementById("editor").contentWindow.document.attachEvent("onmousedown", dismissButtons, true);
        }
    },

    edi: function() {
        if (document.frames)
            return document.frames["editor"]
        else if(window.frames)
            return window.frames["editor"];
        else {
            alert("Tarayýcýnýz editör kullanmak için uygun deðil. Lütfen geliþmiþ bir tarayýcý kullanýn!");
            return;
        }
    },

    exec: function(x, y) {
        document.getElementById("editor").contentWindow.document.execCommand(x, false, y);
        document.getElementById("editor").contentWindow.focus();
    },

    insertHTML: function(html) {
        var edi = this.edi();
        if (window.getSelection) {
            var s = edi.window.getSelection();
            var t = s.toString();
            if (t != "")
                this.exec('inserthtml', html.replace("%{CONTENT}", t));
        } else {
            var s = edi.document.selection.createRange();
            var t = s.text;
            if (t != "")
                s.pasteHTML(html.replace("%{CONTENT}", t));
        }
        edi.focus();
    },

    head: function(x) {
        var hs = ["h1", "h2", "h3", "h4", "h5", "h6"];
        if (x in hs)
            this.exec("formatBlock", x);
        else
            this.insertHTML("<"+ x +">%{CONTENT}</"+ x +">");
    },

    fontSize: function(size) {
        return this.exec('fontsize', size);
        // bakcez...
        // this.insertHTML("<span style='font-size:"+ size +"px'>%{CONTENT}</span>");
    },

    box: function(c) {
        this.insertHTML("<div class='box "+ c +"'>%{CONTENT}</div>");
    },

    image: function(s, c, a, i) {
        var edi = this.edi();
        var img = edi.document.createElement("img");
        img.setAttribute("src", this.http + s);
        img.setAttribute("alt", a);
        img.setAttribute("title", a);
        img.setAttribute("rel", "news-image");
        img.setAttribute("id", i);
        img.setAttribute("class", c +" news-image");
        // eðer resimlerin altýnda açýklama falan çýksýn derlerse bunu açarsýn
        // img.setAttribute("data", "copyright etc...");
        edi.document.body.appendChild(img);
    },

    video: function(service, id, sizes, name) {
        var edi = this.edi();
        var vid = edi.document.createTextNode("[video="+ service +"|"+ id +"|"+ sizes.join("x") +"|"+ encodeURIComponent(name) +"]");
        edi.document.body.appendChild(vid);
    },

    removeFormat: function(x) {
        return this.exec('removeformat');
    },

    preview: function() {
        var html = window.frames["editor"].document.body.innerHTML;
        if (!html) return !1;

        var re = /(\[video=(.*)\])/gi, m = [];
        while (m = re.exec(html)) {
            html = html.replace(m[1], function(x) {
                var video = Video.parseData(x);
                // local'de id alanýna path basýlýr
                var url = video.service == "local" ? video.id : null;
                var embed = Video.getHtmlObject(
                    video.service,
                    video.size[0],
                    video.size[1],
                    {"id": video.id, "name": video.name , "url": url}
                );
                return embed;
            });
        }

        html = html
            // internal keyword search
            .replace(/#((<[^>]*>|)[a-z0-9]+(<[^>]*>|))/gi, function($1,$2) {
                log($2);
                var keyword = stripTags($2);
                return "<a href='"+ ENV.http +"?q="+ keyword +"' rel='tag' title='"+ keyword +"'>"+ $2 +"</a>"
            })
            // twitter user link
            .replace(/@((<[^>]*>|)[a-z0-9]+(<[^>]*>|))/gi, function($1,$2) {
                log($2);
                var user = stripTags($2);
                return "<a href='http://twitter.com/"+ user +"' rel='external nofollow' title='http://twitter.com/"+ user +"' target='_blank'>"+ $2 +"</a>"
            })
            .replace(/<br>/gi, "<br />");

        var win = window.open("about:blank", "Preview", "width=650,height=550,top=1,left=1,status=1,scrollbars=1,resizable=1");
        win.document.write("<!DOCTYPE html>\n<html>\n<head>\n\t<link rel='stylesheet' href='"+ this.http +"site/asset/css/editor.css?"+ (new Date).getTime() +"'>\n</head>\n<body style='padding:12px 12px 16px 12px; cursor:default'>\n\n"+ html +"\n\n<br /><br />\n</body>\n</html>\n<script>document.onkeydown=function(e){if(!e)var e=window.event;if(((e.which)?e.which:e.keyCode)==27)self.close();}</script>");
        win.document.close();
        win.focus();
    }
};

function dismissButtons() {
    if (ENV.get("stopDismissButtons")) return;
    var timeout = 150;
    if (document.getElementById("forecolor").style.display != "none")
        window.setTimeout(function() {document.getElementById("forecolor").style.display = "none";}, timeout);
    if (document.getElementById("createlink").style.display != "none")
        window.setTimeout(function() {document.getElementById("createlink").style.display = "none";}, timeout);
    if (document.getElementById("select_font").style.display != "none")
        window.setTimeout(function() {document.getElementById("select_font").style.display = "none";}, timeout);
    if (document.getElementById("select_head").style.display != "none")
        window.setTimeout(function() {document.getElementById("select_head").style.display = "none";}, timeout);
    if (document.getElementById("select_size").style.display != "none")
        window.setTimeout(function() {document.getElementById("select_size").style.display = "none";}, timeout);
}

function submitEditorForm(f) {
    var c = window.frames["editor"].document.body.innerHTML
        .replace(/^&nbsp;/gi, "").replace(/&nbsp;$/gi, "") // trim
        .replace(/^(\s|<br(\/|)>)|(\s|<br(\/|)>)$/gi, "") // trim
        .replace(/<br>/gi, "<br />\n")           // FF
        .replace(/<p>&nbsp;<\/p>/gi, "<br />\n") // IE
        .replace(/<p><\/p>/gi, "<br />\n")
        .replace(/\r\n/gi, "")                   // IE
        .replace(/src=(["']|^)\.\.\/(.*?)(["']|$)/gi, 'src="'+ ENV.get("http") +'$2"'); // cut,copy falan yapýnca sorun cýkýyor
    $("#news_text").val(c);
    return 1;
}