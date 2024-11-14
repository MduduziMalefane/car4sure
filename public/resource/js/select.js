
var wbs_SelectMmode = 1;
var wbs_SelectMlimit = 10;
var wbs_SelectMpage = 0;
var wbs_SelectMpages = 0;
var wbs_SelectMSelect = -1;
var wbs_SelectMrecords = "";//decodeDatas('');
var wbs_Curl = "";
var wbsResultID = null;
var wbsDisplayID = null;


function OpenWebSelect(Curl, Elements, ResultID, DisplayID)
{
    clearMwbs_Select();
    clearSelectCombo();
    $("#wbsSearchctrl").hide();
    wbs_Curl = Curl;
    wbsResultID = ResultID;
    wbsDisplayID = DisplayID;
    if (Elements != null)
    {
        try
        {
            var ei = Elements.split(",");
            var o = $("#wbsSearchCombo");
            for (var x = 0; x < ei.length; x++)
            {
                var vo = ei[x].split(":");
                var f1, f2;
                if (vo.length == 1)
                {
                    f1 = vo[0];
                    f2 = vo[0];
                }
                else
                {
                    f1 = vo[0];
                    f2 = vo[1];
                }
                o.append("<option value=\"" + f1 + "\">" + f2 + "</option>");
            }
        }
        catch (e)
        {

        }
    }

    $("#web_select").show();
    wbsRefreshResult();
}

function wbsOnChangeRecord()
{
    var choice = $('#wbs_numOpt');

    if (choice !== null)
    {
        switch (choice.val())
        {
            case '1':
                wbs_SelectMlimit = 1;
                break;
            case '2':
                wbs_SelectMlimit = 10;
                break;

            case '3':
                wbs_SelectMlimit = 50;
                break;

            case '4':
                wbs_SelectMlimit = 100;
                break;

            case '5':
                wbs_SelectMlimit = 500;
                break;

            case '6':

                wbs_SelectMlimit = wbs_SelectMpages * wbs_SelectMlimit;
                break;

            default:
                wbs_SelectMlimit = 10;
                break;
        }
    }

    wbs_SelectMpage = 0;
    wbsRefreshResult();
}

function changePageMwbs_Select()
{
    var choice = $('#optPagerMwbs_Select');

    if (choice !== null)
    {
        curr = choice.val();

        wbs_SelectMpage = curr;
        clearMwbs_Select();
        wbsRefreshResult();
    }
}

function wbsRefreshResult()
{

    var pageType = "lst";
    var searchObject = null;
    if ($("#wbsSearchctrl").is(":hidden") == false)
    {
        searchObject = new Object();

        searchObject.searchType = ItemValue("wbsSearchCombo");
        searchObject.searchValue = ItemValue("wbsSearchText");

        if (searchObject.searchType == "")
        {
            showMessage("Select a search Type", 'Error');
            return;
        }
        else if (searchObject.searchValue == "")
        {
            showMessage("Enter a search value", 'Error');
            return;
        }


        pageType = "fnd";
    }

    wbs_SelectMrecords = null;
    clearMwbs_Select();
    $("#loadMwbs_Select").show();

    PostDataOld(searchObject, wbs_Curl + "?postid=" + pageType + "&limit=" + wbs_SelectMlimit + "&page=" + wbs_SelectMpage,
            function ()
            {
                showMessage("Could not process your request");
                wbs_SelectMPopulateNew();
            },
            function (Data)
            {
                wbs_SelectMrecords = Data;
                wbs_SelectMPopulateNew();
            }

    );

}

function wbs_SelectMPopulateNew()
{

    if (wbs_SelectMrecords != null && wbs_SelectMrecords.count != null && wbs_SelectMrecords.count > 0 && wbs_SelectMrecords.show != null)
    {

        for (var i in wbs_SelectMrecords.show)
        {
            $("#dataHMwbs_Select").append("<td>" + wbs_SelectMrecords.show[i] + "</td>");
        }


        for (var i = 0; i < wbs_SelectMrecords.count; i++)
        {
            $("#dataRMwbs_Select").append("<tr class=\"w3-link w3-hover-blue\" onclick='wbs_SelectMRowClick(" + i + ")' id='dataDMwbs_Select" + i + "'></tr>");

            record1 = wbs_SelectMrecords.data[i];

            for (var e in wbs_SelectMrecords.show)
            {
                $("#dataDMwbs_Select" + i).append("<td>" + record1[e] + "</td>");
            }
        }

        $("#tableMTopwbs_Select").show();
        $("#tableMBelowwbs_Select").show();

        wbs_SelectMpage = wbs_SelectMrecords.page;
        wbs_SelectMpages = wbs_SelectMrecords.pages;
        populateMwbs_Select();
        $("#wbs_SelectMtopages").html(wbs_SelectMpages);
    }
    else
    {
        emptyM_wbs_Selecttable();
    }

    $("#loadMwbs_Select").attr('style', "display:none");

}

function wbs_SelectMRowClick(id)
{
    var record1 = wbs_SelectMrecords.data[id];
    var dis = record1[1];
    var idv = record1[0];

    $("#" + wbsResultID).val(idv);
    $("#" + wbsDisplayID).val(dis);


    $("#web_select").hide();
}

function clearMwbs_Select()
{
    clearM_wbs_Selecttable();
}

function populateMwbs_Select()
{
//optPagerMwbs_Select
    for (var i = 0; i < wbs_SelectMrecords.pages; i++)
    {
        if (wbs_SelectMpage != i)
        {
            $("#optPagerMwbs_Select").append("<option value=\"" + i + "\">Page " + (i + 1) + "</option>");
        }
        else
        {
            $("#optPagerMwbs_Select").append("<option selected=\"true\" value=\"" + i + "\">Page " + (i + 1) + "</option>");
        }
    }


}

function clearM_wbs_Selecttable()
{
    wbs_SelectMpages = 0;
    $("#dataHMwbs_Select").empty();
    $("#dataRMwbs_Select").empty();
    $("#optPagerMwbs_Select").empty();
    $("#tableMTopwbs_Select").hide();
    $("#tableMBelowwbs_Select").hide();
    $("#wbs_SelectMtopages").html("0");
}

function  clearSelectCombo()
{
    $("#wbsSearchCombo").html("");
}

function emptyM_wbs_Selecttable()
{
    $("#dataHMwbs_Select").html("<td></td>");
    $("#dataRMwbs_Select").html("<tr><td class='w3-large'>No data</td></tr>");
}

function nextMwbs_Select()
{
    if (wbs_SelectMpage < wbs_SelectMpages - 1)
    {
        wbs_SelectMpage++;
        clearMwbs_Select();
        wbsRefreshResult();
    }
}

function prevMwbs_Select()
{
    if (wbs_SelectMpage > 0)
    {
        wbs_SelectMpage--;
        clearMwbs_Select();
        wbsRefreshResult();
    }
}


function findMwbs_Select()
{
    $("#wbsSearchctrl").show();
}

$("#onSearchMwbs_Select").click(function ()
{
    wbsRefreshResult();
}
);

$("#onSearchCloseMwbs_Select").click(function ()
{
    $("#wbsSearchctrl").hide();
    wbsRefreshResult();
}
);

$(".selectbox").click(function ()
{

    var did = null;
    var vid = null;
    var fl = "";
    var vcurl = "";

    did = $(this).attr('id');
    vid = $(this).attr('mid');

    if (did != null && vid != null)
    {
        fl = $("#" + vid).attr('field');
        vcurl = $("#" + vid).attr('ref');

        OpenWebSelect(vcurl, fl, vid, did);
    }
});