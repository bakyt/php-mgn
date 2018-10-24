$(function () {
    var type = $("#cat-type");
    type_changer(type.val());
    type.on('change', function () {
        type_changer($(this).val());
    });
    var old_images = document.getElementsByClassName('remove_old_image');
    for(var i=0; i<old_images.length;i++){
        $(old_images[i]).on('click', function () {
            document.getElementById("old_image_"+this.id.replace ( /[^\d.]/g, '' )).outerHTML="";
        });
    }
    $('#adder').on('keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            $("#create-feature-button").click();
            return false;
        }
    });
    function type_changer(type){
        var feat = document.getElementsByClassName("features-content");
        if(type === "0") {
            for(var i=0; i<feat.length; i++) {
                $(feat[i]).hide();
            }
            $("#element-adder").hide();
        }
        else {
            for(var j=0; j<feat.length; j++) {
                $(feat[j]).show();
            }
            $("#element-adder").show();
        }
    }
});
function delete_feature(feature){
    feature = transtext(feature);
    var tabs = document.getElementsByClassName("feature-" + feature);
    for(var i=tabs.length; i>=0; i--) {
        $(tabs[i]).remove();
    }
}

function transtext(word) {
    var a = {
        " ":"_",
        "Ё": "YO",
        "Й": "I",
        "Ц": "TS",
        "У": "U",
        "К": "K",
        "Е": "E",
        "Н": "N",
        "Г": "G",
        "Ш": "SH",
        "Щ": "SCH",
        "З": "Z",
        "Х": "H",
        "Ъ": "",
        "ё": "yo",
        "й": "i",
        "ц": "ts",
        "у": "u",
        "к": "k",
        "е": "e",
        "н": "n",
        "г": "g",
        "ш": "sh",
        "щ": "sch",
        "з": "z",
        "х": "h",
        "ъ": "",
        "Ф": "F",
        "Ы": "I",
        "В": "V",
        "А": "A",
        "П": "P",
        "Р": "R",
        "О": "O",
        "Л": "L",
        "Д": "D",
        "Ж": "J",
        "Э": "E",
        "ф": "f",
        "ы": "i",
        "в": "v",
        "а": "a",
        "п": "p",
        "р": "r",
        "о": "o",
        "л": "l",
        "д": "d",
        "ж": "j",
        "э": "e",
        "Я": "Ya",
        "Ч": "CH",
        "С": "S",
        "М": "M",
        "И": "I",
        "Т": "T",
        "Ь": "",
        "Б": "B",
        "Ю": "YU",
        "я": "ya",
        "ч": "ch",
        "с": "s",
        "м": "m",
        "и": "i",
        "т": "t",
        "ь": "",
        "б": "b",
        "ю": "yu"
    };
    return word.split('').map(function (char) {
        return a[char] || char;
    }).join("");
}