function DisableItem(id) {
    var in1 = document.getElementById(id);
    if (in1 != null) {
        in1.setAttribute("disabled", "");
    }
}

function EnableItem(id) {
    var in1 = document.getElementById(id);
    if (in1 != null && in1.getAttribute("disabled") != null) {
        in1.removeAttribute("disabled");
    }
}

function ItemValue(id, Default = "") {
    var eItem = document.getElementById(id);

    var vale = Default;

    if (eItem == null) {
        vale = Default;
    }
    else if (eItem.tagName == "INPUT") {
        vale = eItem.value.trim();
    }
    else if (eItem.tagName == "TEXTAREA") {
        if (document.getElementById(id + "_ifr") != null && eItem.style.display === 'none') {
            try {
                var ve = tinymce.get(id);
                var vea = ve.getContent().trim();

                if (vea != null && vea.length > 0) {
                    vale = vea;
                }
            }
            catch (e) {

            }
        }
        else {
            vale = eItem.value.trim();
        }
    }
    else {
        vale = eItem.value.trim();
    }

    return vale;
}

function PostDataOld(data, postpage, onfail, onsuccess) {

    $.ajax({
        url: postpage,
        dataType: 'json',
        type: 'POST',
        //contentType: 'application/json',
        data: { form_data: JSON.stringify(data) },

        success: function (data1, textStatus, jQxhr) {

            if (onsuccess != null) {
                onsuccess(data1);
            }
        },
        error: function (jqXhr, textStatus, errorThrown) {
            //console.log(errorThrown);
            if (onfail != null) {
                onfail();
            }
        }
    });

}

function PostData(data, postpage, onfail, onsuccess) {

    $.ajax({
        url: postpage,
        dataType: 'json',
        type: 'POST',
        //contentType: 'application/json',
        data: { jdata: JSON.stringify(data) },

        success: function (data1, textStatus, jQxhr) {

            if (onsuccess != null) {
                onsuccess(data1);
            }
        },
        error: function (jqXhr, textStatus, errorThrown) {
            //console.log(errorThrown);
            if (onfail != null) {
                onfail();
            }
        }
    });

}


function PostDataRaw(data, postpage, onfail, onsuccess) {

    $.ajax({
        url: postpage,
        type: 'post',
        //contentType: 'application/json',
        data: { jdata: JSON.stringify(data) },
        success: function (data1, textStatus, jQxhr) {

            if (onsuccess != null) {
                onsuccess(data1);
            }
        },
        error: function (jqXhr, textStatus, errorThrown) {
            //console.log(errorThrown);
            if (onfail != null) {
                onfail();
            }
        }
    });

}


function PostDataCompact(data, postpage, onResponse) {

    $.ajax({
        url: postpage,
        dataType: 'json',
        type: 'post',
        //contentType: 'application/json',
        data: { jdata: JSON.stringify(data) },
        success: function (data1, textStatus, jQxhr) {

            if (onResponse != null) {
                onResponse(data1, 1);
            }
        },
        error: function (jqXhr, textStatus, errorThrown) {
            if (onResponse != null) {
                onResponse(null, 0);
            }
        }
    });
}

function encodeDatas(e) {
    return JSON.stringify(e);
}

function decodeDatas(e) {

    try {
        return JSON.parse(e);
    }
    catch (ev) {
        //console.trace("Decodas Error: "+ ev);
        return null;
    }
}

function showView(view) {
    $(".app-view").hide();
    $(`#${view}`).show();
}

function showLoading(loadingMessage) {
    $("#loadingModalMessage").html(loadingMessage);
    $("#loadingModal").modal("show");
}

function closeLoading() {
    $("#loadingModal").modal("hide");
}