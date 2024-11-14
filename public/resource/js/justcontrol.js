var funcCall = null;
function showMessage(Text, Title = "Message", funcToCall = null) {
    var e = $("#messageModal");
    var f = $("#messageTitle");
    var g = $("#messageText");

    if (e != null && f != null && g != null) {
        f.html(Title);
        g.html(Text);
        e.modal("show");
        funcCall = funcToCall;
    }
}

$(".message-close").click(function (e) {
    if (funcCall != null) {
        try {
            funcCall();
        } catch (e) { }
    }
    $("#messageModal").modal("hide");

    e.stopPropagation();
});

function getSelectedInfo(id) {
    let nId = `#${id}-info`;
    return $(nId).val();
}

function showConfirmModal(Text, Title = "Message", closeButton = "Close", okButton = "Yes", funcToCall = null) {
    let em = $(`#ConfirmModal`);
    let fm = $(`#ConfirmModalTitle`);
    let gm = $(`#ConfirmModalText`);
    let gbt = $(`#ConfirmModalButtonOk`);
    let gbtt = $(`#ConfirmModalButtonClose`);

    if (em != null && fm != null && gm != null && gbt != null && gbtt != null) {
        fm.html(Title);
        gm.html(Text);
        gbt.html(okButton);
        gbtt.html(closeButton);
        em.modal("show");
        funcCall = funcToCall;
    }
}

$("#ConfirmModalButtonOk").click(function (e) {
    if (funcCall != null) {
        try {
            funcCall();
        } catch (e) { }
    }
    $("#ConfirmModal").modal("hide");
    e.stopPropagation();
});

// sc-tab functionality
$(".sc-tab-button").click(function (e) {
    let tabId = $(this).attr("data-sc-tabid");
    if (tabId != null) {
        let activeTab = $(`.sc-tab[data-sc-tabid='${tabId}']`);
        if (activeTab != null) {
            $(".sc-tab").hide();
            $(".sc-tab-active").removeClass("sc-tab-active");
            $(this).addClass("sc-tab-active");

            activeTab.show();
        }
    }
});
