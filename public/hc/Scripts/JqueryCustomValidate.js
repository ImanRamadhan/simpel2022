//Mencegah hanya spasi saja
jQuery.validator.addMethod("Mencegah_Hanya_Spasi", function (value, element) {
    return this.optional(element) || /(.|\s)*\S(.|\s)*/i.test(value);
}, "No white space please"); 

//Number Only
jQuery.validator.addMethod("Number_Only", function (value, element) {
    return this.optional(element) || /^-?\d+$/.test(value);
}, "Number Only Please");

//Custom Datepicker
$(function ($) {
    $.fn.datepicker.dates['qtrs'] = {
        days: ["Sunday", "Moonday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        daysShort: ["Sun", "Moon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
        months: ["Q1", "Q2", "Q3", "Q4", "", "", "", "", "", "", "", ""],
        monthsShort: ["Jan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Feb&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mar", "Apr&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;May&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jun", "Jul&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aug&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sep", "Oct&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nov&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dec", "", "", "", "", "", "", "", ""],
        today: "Today",
        clear: "Clear",
        format: "mm/dd/yyyy",
        titleFormat: "MM yyyy",
        /* Leverages same syntax as 'format' */
        weekStart: 0
    };

    if (getCookie("Secret") === null) {
        createCookie("Secret", window.btoa(CreateSecret()))
    }
});


//Encrypt
function EncryptAES(Message) {
    if (getCookie("Secret") === null) {
        createCookie("Secret", window.btoa(CreateSecret()))
    }
    var EncryptText = CryptoJS.AES.encrypt(String(Message), window.atob(getCookie("Secret"))).toString();
    return window.btoa(EncryptText);
}

//Decrypt
function DecryptAES(EncMessage) {
    if (getCookie("Secret") === null) {
        console.log("Secret not created")
    }
    var DecryptText = CryptoJS.AES.decrypt(window.atob(EncMessage), window.atob(getCookie("Secret"))).toString(CryptoJS.enc.Utf8);
    return DecryptText;
}

//CreateSecret
function CreateSecret() {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < 10; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}