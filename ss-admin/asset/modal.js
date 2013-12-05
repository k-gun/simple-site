var $ss = window.$ss || {};

$ss.modal = function(options) {
    this.defaultOptions = {
        width: 350, height: null,
        buttons: [],
        layerCanClose: true,
        closeText: "close",
        animateTop: 350
    };
    this.options = mii.mix({}, this.defaultOptions, options || {});
    this.tpl = '<div class="modal"><div class="modal_head"></div><div class="modal_body"></div><div class="modal_foot"></div></div>';
    this.uuid = mii.uuid();
    this.modal = null;
    this.modalLay = null;
    this.modalBox = null;
    this.head = this.body = this.foot;
};

mii.extend($ss.modal.prototype, {
    generate: function() {
        this.modalLay = mii.dom("<div class='modal_lay' id='modalLay_"+ this.uuid +"' data-zindex="+ this.uuid +"></div>");
        if (this.options.layerCanClose !== false) {
            var _this = this;
            this.modalLay.on("click", function(){
                _this.close();
            });
        }
        this.modalLay.setStyle({zIndex: "1000"+ this.uuid});
        this.modalLay.appendTo("body");

        this.modalBox = mii.dom("<div class='modal_box' id='modalBox_"+ this.uuid +"' data-zindex="+ this.uuid +"></div>");
        this.modalBox.setStyle({zIndex: "1000"+ (this.uuid + 1), width: this.options.width});
        this.modalBox.setHtml(this.tpl);
        this.modalBox.appendTo("body");
        this.modalBox.setStyle({marginLeft: -(this.modalBox.outerWidth() / 2)});
    },
    open: function (head, body, callback) {
        // Generate
        this.generate();
        // Set elements
        this.head = this.modalBox.find(".modal_head").first();
        this.body = this.modalBox.find(".modal_body").first();
        this.foot = this.modalBox.find(".modal_foot").first();

        var _this = this;
        if (head) {
            this.head.setHtml(head);
        } else {
            this.head.addClass("hidden");
        }

        this.body.setHtml(body || "");
        this.foot.append(mii.dom({
            "tag": "a",
            "href": "#",
            "text": this.options.closeText,
            "class": "lc",
            "onclick": function(){ _this.close(); return !1; }
        }));

        // Add buttons
        if (this.options.buttons.length) {
            var button, buttonElement, i;
            this.foot.setStyle("padding-top:6px;");
            this.foot.append("<sep sym='pipe'></sep>");
            while (button = this.options.buttons.shift()) {
                buttonElement = document.createElement("input");
                buttonElement.type = "button";
                buttonElement.className = "btn btn_blue cc";
                // buttonElement.onfocus = function(){ this.blur(); };
                for (i in button) {
                    buttonElement[i] = button[i];
                }
                this.foot.append(buttonElement);
            }
        }

        // Set height
        if (this.options.height) {
            var ok, i;
            if (!ok) {
                this.body.setStyle({"overflow": "hidden", "height": this.options.height});
                // Wait for ajax contents
                i = setInterval(function(){
                    if (_this.body.height() >= _this.options.height) {
                        _this.body.setStyle("overflow", "auto");
                        clearInterval(i);
                        ok = 1;
                    }
                }, 100);
            }
        }

        // Remove scroll
        var $els, a, b;
        $els = mii.dom("html, body");
        a = mii.dom("body").width();
        $els.setStyle("overflow", "hidden");
        b = mii.dom("body").width();
        mii.dom(".container").setStyle("padding-right", (b - a));

        // Check callback
        if (typeof callback == "function") {
            callback(this);
        }

        return this;
    },
    close: function() {
        var _this = this;
        var close = function(){
            mii.dom(".modal_lay, .modal_box").remove();
            _this.clearStyles();
        };
        close();
    },
    destroy: function() {
        mii.dom(".modal_lay, .modal_box").remove();
        this.clearStyles();
    },
    clearStyles: function() {
        mii.dom("html, body").setStyle("overflow", "");
        mii.dom(".container").setStyle("padding-right", "");
    },
    setHeadContent: function(head, append) {
        if (append) {
            return this.head.append(head);
        }
        return this.head.setHtml(head);
    },
    setBodyContent: function(body, append) {
        if (append) {
            return this.body.append(body);
        }
        return this.body.setHtml(body);
    },
    setFootContent: function(foot, append) {
        if (append) {
            return this.foot.append(foot);
        }
        return this.foot.setHtml(foot);
    }
});

// Listen ESC key
mii.dom(window).on("keydown", function(e){
    if (e.keyCode == 27) {
        (new $ss.modal).destroy();
    }
});
