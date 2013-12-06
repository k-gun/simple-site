function printf() {
    var args = arguments, s = args[0], ms = s.match(/(%s)/g) || [], i = 1, m;
    while (m = ms.shift()) {
        s = s.replace(/(%s)/, args[i++]);
    }
    return s;
}

function redirect() {
    window.location.href = printf.apply(null, arguments);
}

mii.onReady(function($){
    $.dom("a[href='#']").on("click", function(e){
        e.preventDefault();
    });

    $.dom("a[confirm^='#']").on("click", function(e){
        if (!confirm(this.getAttribute("confirm").substring(1))) {
            e.preventDefault();
        }
    });
});