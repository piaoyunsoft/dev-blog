var s = 'hello';
s.join('');
Array.prototype.join.call(s,'');

s.length;
s.charAt(0)
s.charAt(1)
s.charCodeAt(0)
s.charCodeAt(1)

var string = 'Hello World!'
btoa(string);
atob('')

function b64Encode(str) {
    return btoa(encodeURIComponent(str));
}

function b64Decode(str) {
    return decodeURIComponent(atob(str));
}
b64Encode('hell0')
b64Decode()
