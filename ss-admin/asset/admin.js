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