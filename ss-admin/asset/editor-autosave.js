mii.onReady(function($){
    var form = $.dom(".ss-admin-item-form");
    var draftSaved = $.dom(".ss-admin-item-form-submit .floatr");
    var change = false;

    form.find("select, input, textarea").forEach(function(el){
        el.onkeyup =
        el.onchange =
        el.onkeydown =
            function(){ change = true; };
    });

    var editor = $ss.editor.$("editor");
    $.dom(editor.contentWindow).on("focus, blur, keydown, keyup", function(){
        change = true;
    });

    var time = 1000;
    setInterval(function(){
        if (!change) {
            return;
        }
        $.ajax.post("/ss-admin/ajax?do=item-save-draft", form.builtQueryArray(), function(r){
            draftSaved.setStyle("opacity", 1).setText("Saved!");
            var t = setTimeout(function(){
                draftSaved.fadeOut(1000);
                clearTimeout(t);
            }, time);
            change = false;
        });
    }, time);
});