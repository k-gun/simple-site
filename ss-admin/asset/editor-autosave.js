mii.onReady(function($){
    var form = $.dom(".ss-admin-form");
    var draftSaved = $.dom(".ss-admin-form-submit .floatr");

    var change = false;
    form.find("select, input, textarea").on("keyup, keydown, change", function(){
        change = true;
    });

    var editor = document.getElementById("editor");
    $.dom(editor.contentWindow).on("focus, blur, keyup, keydown", function(e){
        change = true;
    });

    var time = 1000, data;
    setInterval(function(){
        data = form.buildQueryArray();
        if (!change || $.trim(data["item[title]"]) === "") {
            return;
        }
        $.ajax.post("/ss-admin/ajax?do=item-save-draft", data, function(r){
            draftSaved.setStyle("opacity", 1).setText("Saved!");
            var t = setTimeout(function(){
                draftSaved.fadeOut(1000);
                clearTimeout(t);
            }, time);
            change = false;
        });
    }, time);
});