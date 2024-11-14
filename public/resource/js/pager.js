
var Max = 10;
var Offset = 0;
var busy = false;

function ClearGrid()
{
    $('.list-entry').attr('class', 'list-entry list-hide');
}

function ChangeMax()
{
    var choice = $('#list-table-select');

    if (choice !== null)
    {
        Offset = 0;
        switch (choice.val())
        {
            case '1':
                Max = 1;
                break;
            case '2':
                Max = 10;
                break;

            case '3':
                Max = 50;
                break;

            case '4':
                Max = 100;
                break;

            case '5':
                Max = 500;
                break;

            case '6':

                Max = $('.list-entry').length;
                break;
        }
    }

    RefreshAll();
}

function UpdatePages()
{
    var elCount = $('.list-entry').length;

    var PageC = $('#list-table-page');
    PageC.html(' ');


    var PageCount = elCount / Max;

    if (PageCount * Max !== elCount)
    {
        PageCount = PageCount + 1;
    }

    for (var i = 0; i < PageCount; i++)
    {
        PageC.append($('<option>').text('Page ' + (i + 1)).attr('value', i));// '<option value='' + PageC + ''>' + + '</option>');
    }


}

function ChangePage()
{
    var choice = $('#list-table-page');

    if (choice !== null)
    {
        curr = choice.val();

        var elCount = $('.list-entry').length;
        var PageCount = elCount / Max;

        if (PageCount * Max !== elCount)
        {
            PageCount = PageCount + 1;
        }


        if (curr >= 0 && curr <= PageCount - 1)
        {
            Offset = new Number(curr);
            ClearGrid();


            var start = Offset;
            var count = Max;
            var maxx = start * count;

            if (maxx > elCount)
            {
                count = maxx - elCount;
            }

            start = start * Max;
            count = count + start;

            for (var i = start; i < count; i++)
            {
                $('.list-entry').eq(i).attr('class', 'list-entry');
            }
        }

    }
}

function NextPage()
{
    var index = Offset + 1;


    var elCount = $('.list-entry').length;
    var PageCount = elCount / Max;

    if (PageCount * Max !== elCount)
    {
        PageCount = PageCount + 1;
    }

    if (index <= PageCount)
    {
        $('#list-table-page').children()[index].selected = true;

        var curr = index;



        if (curr >= 0 && curr <= PageCount - 1)
        {
            Offset = curr;
            ClearGrid();


            var start = Offset;
            var count = Max;
            var maxx = start * count;

            if (maxx > elCount)
            {
                count = maxx - elCount;
            }

            start = start * Max;
            count = count + start;

            for (var i = start; i < count; i++)
            {
                $('.list-entry').eq(i).attr('class', 'list-entry');
            }
        }
    }
}

function BackPage()
{
    var index = Offset - 1;


    var elCount = $('.list-entry').length;
    var PageCount = elCount / Max;

    if (PageCount * Max !== elCount)
    {
        PageCount = PageCount + 1;
    }

    if (index >= 0)
    {
        $('#list-table-page').children()[index].selected = true;

        var curr = index;



        if (curr >= 0 && curr <= PageCount - 1)
        {
            Offset = curr;
            ClearGrid();


            var start = Offset;
            var count = Max;
            var maxx = start * count;

            if (maxx > elCount)
            {
                count = maxx - elCount;
            }

            start = start * Max;
            count = count + start;

            for (var i = start; i < count; i++)
            {
                $('.list-entry').eq(i).attr('class', 'list-entry');
            }
        }
    }
}


function RefreshAll()
{
    busy = true;
    UpdatePages();

    ClearGrid();


    var elCount = $('.list-entry').length;
    var start = Offset;
    var count = Max;
    var maxx = start * count;

    if (maxx > elCount)
    {
        count = maxx - elCount;
    }

    start = start * Max;
    count = count + start;

    for (var i = start; i < count; i++)
    {
        $('.list-entry').eq(i).attr('class', 'list-entry');
    }
    busy = false;
}


function SearchPager()
{
    busy = true;

    var Search = $('#pagersearch');

    if (Search !== null && Search.val().trim().length > 0)
    {
        var PageC = $('#list-table-page');
        PageC.html(' ');
        ClearGrid();

        var searchVal = Search.val().toLowerCase().trim();

        var elCount = $('.list-entry').length;

        for (var i = 0; i < elCount; i++)
        {

            var el = $('.list-entry').eq(i);

            if (SearchElement(el.children(), searchVal) === true)
            {
                el.attr('class', 'list-entry');
            }

        }
        busy = false;
    }
    else
    {
        AlertMessage('Enter a search term')
    }
}

function SearchElement(el, value)
{

    if (el !== null)
    {
        var count = el.length;


        for (var i = 0; i < count; i++)
        {
            var val = new String();
            val = el.eq(i).html();

            if (val.toLowerCase().indexOf(value) !== -1)
            {
                return true;
            }
        }
    }

    return false;
}
RefreshAll();
