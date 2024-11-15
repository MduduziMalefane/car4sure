class scLister
{

    constructor(id)
    {
        this.id = id;
        let scParent = $(`#${this.id}`);
        if (scParent === null)
        {
            return;
        }

        this.pageLimit = 10;
        this.currentPage = 0;
        this.currentPages = 0;
        this.dataRecords = null;
        this.searchForText = "";

        // preset function

        this.refreshFunc = null;
        this.clearFunc = null;
        this.populateFunc = null;
        this.afterPopulateFunc = null;
        this.showAfterPopulateFunc = null;


        scParent.html(`
        <div>
            <div class='w3-padding w3-center'>

                <input
                    class='w3-input w3-border w3-border-blue midinput w3-inline-block w3-margin-bottom searchinput'
                    id='${this.id}_searchText' placeholder="Search" />
                <button class='w3-button car4sure-green w3-round-medium fa w3-large fa-search searchinput'
                        title='Search' id='${this.id}_scSearchButton'></button>
                <button class='w3-button car4sure-green w3-round-medium fa fa w3-large fa-window-close searchinput'
                        title='Clear Search' id='${this.id}_scCloseSearchButton'></button>
                <button class="w3-button car4sure-green w3-round-medium fa fa-sync-alt w3-large refreshclick"
                        title="refresh" id="${this.id}_scRefreshButton"></button>
            </div>

            <div class="w3-padding w3-center" id="${this.id}_searchFor"></div>
        </div>
        <div class='w3-padding w3-right-align' id='${this.id}_scHeader' style='display:none'>
            <button class='pager-limit' data-limit='1' id='${this.id}_pgrlim1'>1</button>
            <button class='pager-limit pager-limit-selected' data-limit='10' id='${this.id}_pgrlim10'>10</button>
            <button class='pager-limit' data-limit='50' id='${this.id}_pgrlim50'>50</button>
            <button class='pager-limit' data-limit='100' id='${this.id}_pgrlim100'>100</button>
        </div>
        <div class='w3-padding'>
            <div id="${this.id}_scResult-window" class='w3-padding w3-border w3-border-gray w3-round-medium w3-padding-top w3-padding-64 w3-row'>

            </div>

        </div>

        <div class='w3-padding w3-padding-16 w3-center ' id='${this.id}_scOptPager' style='display:none'>

        </div>
        `);



        $(`#${this.id}_scHeader .pager-limit`).click(() =>
        {

            let limit = $(event.currentTarget).attr("data-limit");
            if (checkNumeric(limit))
            {
                switch (limit)
                {
                    case '1':
                        this.pageLimit = 1;
                        break;
                    case '10':
                        this.pageLimit = 10;
                        break;
                    case '50':
                        this.pageLimit = 50;
                        break;
                    case '100':
                        this.pageLimit = 100;
                        break;
                    default:
                        this.pageLimit = 10;
                        break;
                }

                $(`#${this.id}_scHeader .pager-limit-selected`).removeClass("pager-limit-selected");
                $(`#${this.id}_pgrlim${this.pageLimit}`).addClass("pager-limit-selected");
                this.refreshLister();
            }
        });

        $(`#${this.id}_scSearchButton`).click(() => {

            let searchValue = $(`#${this.id}_searchText`).val();
            if (searchValue !== null && searchValue.trim() != "")
            {
                this.searchForText = searchValue;
                $(`#${this.id}_searchFor`).text('Searching for:  ' + searchValue).html();
                this.refreshLister();
            }
            else
            {
                showMessage("Enter a search term");
            }

        });

        $(`#${this.id}_scCloseSearchButton`).click(() => {
            this.searchForText = ``;
            $(`#${this.id}_Msearchtext`).val(``);
            $(`#${this.id}_searchFor`).html(``);
            this.refreshLister();
        });

        $(`#${this.id}_scRefreshButton`).click(() => {
            this.refreshLister();
        });
    }

    scSetupLister(refreshArg, clearArg, populateArg, afterPopulateArg, showAfterPopulateArg)
    {
        this.refreshFunc = refreshArg;
        this.clearFunc = clearArg;
        this.populateFunc = populateArg;
        this.afterPopulateFunc = afterPopulateArg;
        this.showAfterPopulateFunc = showAfterPopulateArg;
    }

    populatePager()
    {
        $(`#${this.id}_scOptPager`).empty();
        if (this.dataRecords.pages > 1)
        {
            $(`#${this.id}_scOptPager`).append('<span class="inline-block w3-padding">Page: </span>');
            $(`#${this.id}_scOptPager`).append(`<a class='pager-item' data-page='0' id='${this.id}_scPgr0'>1</a>`);
            if (this.dataRecords.pages > 2)
            {
                let numCount = 4;
                let max = this.dataRecords.offset + numCount + 1;
                let min = this.dataRecords.offset - numCount;
                if (max >= this.dataRecords.pages)
                {
                    max = this.dataRecords.pages - 1;
                }

                let reduce = this.dataRecords.pages - 2 - (this.dataRecords.offset);
                if (reduce < numCount)
                {
                    min = this.dataRecords.offset - reduce - (numCount * 2);
                }

                if (min <= 2)
                {
                    min = 1;
                }

                for (let i = min; i < max; i++)
                {
                    let p = i + 1;
                    $(`#${this.id}_scOptPager`).append(`<a class='pager-item' data-page='${i}' id='${this.id}_scPgr${i}'>${p}</a>`);
                }
            }

            let next = this.dataRecords.pages - 1;
            $(`#${this.id}_scOptPager`).append(`<a class='pager-item' data-page='${next}' id='${this.id}_scPgr${next}'>${this.dataRecords.pages}</a>`);
            $(`#${this.id}_scPgr${this.dataRecords.offset}`).addClass("pager-selected");
            $(`#${this.id}_scOptPager .pager-item`).click(() =>
            {
                let selPage = $(event.currentTarget).attr("data-page");
                if (checkNumeric(selPage))
                {
                    this.currentPage = new Number(selPage);
                    $(`#${this.id}_scOptPager .pager-selected`).removeClass("pager-selected");
                    $(`#${this.id}_scPgr${this.currentPage}`).addClass("pager-selected");
                    this.refreshLister();
                }
            });
        }


    }

    clearLister()
    {
        this.currentPages = 0;
        $(`#${this.id}_scResult-window`).empty();
        $(`#${this.id}_scOptPager`).empty();
        $(`#${this.id}_scHeader`).hide();
        $(`#${this.id}_scFooter`).hide();
    }

    refreshLister()
    {
        this.dataRecords = null;
        this.clearLister();
        this.refreshFunc();
    }
    
    filter(expression)
    {
        let results = [];
        for(let i=0;i<this.dataRecords.count;i++)
        {
            if(expression(this.dataRecords.data[i]))
            {
                results.push(this.dataRecords.data[i]);
            }
        }
        
        return results;
    }

    populateLister(Data)
    {
        this.dataRecords = Data;
        if (this.dataRecords !== null && this.dataRecords.count !== null && this.dataRecords.count > 0)
        {

            for (let i = 0; i < this.dataRecords.count; i++)
            {
                let pop_item = this.dataRecords.data[i];
                this.populateFunc(pop_item);
            }

            this.afterPopulateFunc();
            this.currentPage = this.dataRecords.page;
            this.currentPages = this.dataRecords.pages;
            this.populatePager();
            $(`#${this.id}_scOptPager`).show();
            $(`#${this.id}_scHeader`).show();
            $(`#${this.id}_scFooter`).show();
        }
        else
        {
            this.clearResults();
        }

        this.showAfterPopulateFunc();
    }

    clearResults()
    {
        let message = this.clearFunc();
        $(`#${this.id}_scResult-window`).html(message);
    }

    appendResult(value)
    {
        $(`#${this.id}_scResult-window`).append(value);
    }

}