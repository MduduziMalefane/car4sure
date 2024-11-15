class scListControl
{

    constructor(id, tableStyle = 'table table-bordered', headerStyle = 'car4sure-table-header', listStyle = "w3-row w3-border w3-padding", customSearch = '')
    {
        this.id = id;
        let scParent = $(`#${this.id}`);
        if (scParent == null)
        {
            return;
        }

        this.pageLimit = 10;
        this.currentPage = 0;
        this.currentPages = 0;
        this.dataRecords = null;
        this.searchForText = "";
        this.searchMode = false;
        this.header = [];
        this.tableStyle = tableStyle;
        this.headerStyle = headerStyle;
        this.listStyle = listStyle;

        // preset function

        this.refreshFunc = null;
        this.clearFunc = null;
        this.populateFunc = null;
        this.afterPopulateFunc = null;
        this.showAfterPopulateFunc = null;
        this.customSearchValidation = null;

        this.tilePopulateFunc = null;
        this.tileClearFunc = null;

        this.currentView = 1;

        let searchView = '';

        this.searchLimits = [1, 10, 50, 100];
        this.searchLimitDefault = 10;

        if (customSearch != null && customSearch != '')
        {
            searchView = customSearch;

            this.customSearchValidation = () => {
                return false;
            };
        }
        else
        {

            searchView = `
            <div class="input-group w3-center pull-right">
                
                <input type="search"  id='${this.id}_searchText' class="form-control w3-border w3-border-indigo" />

                <button class="btn car4sure-green w3-hover-gray" type="button" id='${this.id}_scSearchButton' title="Search">
                  <i class="fas fa-search"></i>
                </button>
            
                <button class="btn car4sure-green w3-hover-gray" title='Clear Search' id='${this.id}_scCloseSearchButton'>
                    <i class="fa fa-window-close"></i>
                </button>
            
                <button class="btn car4sure-green w3-hover-gray" title="refresh" id="${this.id}_scRefreshButton">
                    <i class="fa fa-sync-alt"></i>
                </button>
            
            </div>

            <div>

                <div class="w3-center" id="${this.id}_searchFor"></div>
            </div>`;
        }

        scParent.html(`
       
       
        <div id='${this.id}_scHeader' class="w3-row sc-header" style='display:none'>
            <div class="pb-2">
                <button id="${this.id}_switchGrid" class='btn car4sure-green w3-hover-gray'><i class="fas fa-border-all"></i></button>
                <button id="${this.id}_switchTile" class='btn car4sure-green w3-hover-gray'><i class="fas fa-th-large"></i></button>
            </div>
            <div class="w3-half">
                ${searchView}
            </div>
            <div class='w3-right-align w3-half page-limiter'>

            </div>
        </div>
        
        <div>
        
            <table id="${this.id}-sc-table" class="${this.tableStyle}">
                <thead id="${this.id}-sc-head" class="${this.headerStyle}">
                </thead>
                <tbody id="${this.id}-sc-body">

                </tbody>
            </table>

            <div id="${this.id}-sc-list"  class='${this.listStyle}' style="display: none">

            </div>
        </div>

        <div class='w3-padding w3-padding-16 w3-center ' id='${this.id}_scOptPager' style='display:none'>

        </div>
        `);

        this.populateLimits();

        $(`#${this.id}_scSearchButton`).click(() => {

            this.scSearchValidate();

        });

        $(`#${this.id}_scCloseSearchButton`).click(() => {
            this.searchMode = false;
            this.searchForText = ``;
            $(`#${this.id}_Msearchtext`).val(``);
            $(`#${this.id}_searchFor`).html(``);
            this.refreshLister();
        });

        $(`#${this.id}_scRefreshButton`).click(() => {

            if (this.searchMode)
            {
                this.scSearchValidate();
            }
            else
            {
                this.refreshLister();
            }
        });

        $(`#${this.id}_switchGrid`).click(() => {

            this.switchView(1);
        });

        $(`#${this.id}_switchTile`).click(() => {

            this.switchView(0);
        });

    }

    setLimits(limtValues, limitDefaultValue)
    {
        this.searchLimits = limtValues;
        this.searchLimitDefault = limitDefaultValue;
        populateLimits();
    }

    populateLimits()
    {
        let searchLimitC = $(`#${this.id}_scHeader .page-limiter`);
        searchLimitC.empty();

        for (let i = 0; i < this.searchLimits.length; i++)
        {
            let searchLimit = this.searchLimits[i];
            let activePage = '';
            if (searchLimit == this.searchLimitDefault)
            {
                activePage = ' pager-limit-selected';
            }

            searchLimitC.append(`<button class='pager-limit${activePage}' data-limit='${searchLimit}' id='${this.id}_pgrlim${searchLimit}'>${searchLimit}</button>`);
        }



        $(`#${this.id}_scHeader .page-limiter .pager-limit`).click(() =>
        {

            let limit = $(event.currentTarget).attr("data-limit");
            if (checkNumeric(limit))
            {
                this.pageLimit = new Number(limit);
                let filterResult = this.searchLimits.filter((x) => x == this.pageLimit);

                if (filterResult.length == 0)
                {
                    this.pageLimit = this.searchLimitDefault;
                }
                


                $(`#${this.id}_scHeader .page-limiter .pager-limit-selected`).removeClass("pager-limit-selected");
                $(`#${this.id}_pgrlim${this.pageLimit}`).addClass("pager-limit-selected");
                this.refreshLister();
            }
        });

    }

    scSetupLister(refreshArg, clearArg, populateArg, afterPopulateArg, showAfterPopulateArg, tilePopulateFuncArg, tileClearFuncArg, customSearchValidationArg = null)
    {
        this.refreshFunc = refreshArg;
        this.clearFunc = clearArg;
        this.populateFunc = populateArg;
        this.afterPopulateFunc = afterPopulateArg;
        this.showAfterPopulateFunc = showAfterPopulateArg;

        this.tilePopulateFunc = tilePopulateFuncArg;
        this.tileClearFunc = tileClearFuncArg;
        this.customSearchValidation = customSearchValidationArg;
    }

    setHeader(headerData)
    {
        this.header = (headerData == null) ? [] : headerData;
    }

    populatePager()
    {
        $(`#${this.id}_scOptPager`).empty();
        if (this.dataRecords.pages > 1)
        {
            $(`#${this.id}_scOptPager`).append(`<a class='pager-item' data-page='0' id='${this.id}_scPgr0'>1</a>`);
            if (this.dataRecords.pages > 2)
            {
                let numCount = 3;
                let max = this.dataRecords.page + numCount;
                let min = this.dataRecords.page - numCount + 1;
                if (max >= this.dataRecords.pages)
                {
                    max = this.dataRecords.pages - 1;
                }

                let reduce = this.dataRecords.pages - 2 - (this.dataRecords.page);
                if (reduce < numCount)
                {
                    min = this.dataRecords.page - reduce - (numCount * 2);
                }

                if (min < 1)
                {
                    min = 1;
                    if (max + 1 < this.dataRecords.pages - 1)
                    {
                        max = max + 1;
                    }
                }

                for (let i = min; i < max; i++)
                {
                    let p = i + 1;
                    $(`#${this.id}_scOptPager`).append(`<a class='pager-item' data-page='${i}' id='${this.id}_scPgr${i}'>${p}</a>`);
                }
            }

            let next = this.dataRecords.pages - 1;
            $(`#${this.id}_scOptPager`).append(`<a class='pager-item' data-page='${next}' id='${this.id}_scPgr${next}'>${this.dataRecords.pages}</a>`);
            $(`#${this.id}_scPgr${this.dataRecords.page}`).addClass("pager-selected");
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
        $(`#${this.id}-sc-list`).empty();
        $(`#${this.id}-sc-body`).empty();
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
        for (let i = 0; i < this.dataRecords.count; i++)
        {
            if (expression(this.dataRecords.data[i]))
            {
                results.push(this.dataRecords.data[i]);
            }
        }

        return results;
    }

    switchView(viewId)
    {
        this.clearResults();
        this.currentView = viewId;

        if (viewId == 1)
        {
            $(`#${this.id}-sc-table`).show();
            $(`#${this.id}-sc-list`).hide();
        }
        else
        {
            $(`#${this.id}-sc-table`).hide();
            $(`#${this.id}-sc-list`).show();
        }


        this.populateLister(this.dataRecords);
    }

    populateLister(Data)
    {
        this.clearResults(false);
        this.dataRecords = Data;
        if (this.dataRecords != null && this.dataRecords.count != null && this.dataRecords.count > 0)
        {

            for (let i = 0; i < this.dataRecords.count; i++)
            {
                let pop_item = this.dataRecords.data[i];

                if (this.currentView == 1)
                {
                    this.populateFunc(pop_item);
                }
                else
                {
                    this.tilePopulateFunc(pop_item);

                }
            }

            this.afterPopulateFunc();
            this.currentPage = this.dataRecords.page;
            this.currentPages = this.dataRecords.pages;
            this.populatePager();
            $(`#${this.id}_scOptPager`).show();
            $(`#${this.id}_scHeader`).show();
            $(`#${this.id}_scFooter`).show();

            if (this.currentView == 1)
            {
                this.showHeader();
            }

        }
        else
        {
            this.clearResults(true);
            $(`#${this.id}_scHeader`).show();
        }

        this.showAfterPopulateFunc();
    }

    showHeader()
    {
        $(`#${this.id}-sc-head`).html("");
        if (this.header.length > 0)
        {
            for (let i = 0; i < this.header.length; i++)
            {
                $(`#${this.id}-sc-head`).append(`<td>${this.header[i]}</td>`);
            }
        }
    }

    clearResults(showPrompt = false)
    {
        $(`#${this.id}-sc-body`).html("");
        $(`#${this.id}-sc-head`).html("");
        $(`#${this.id}-sc-list`).empty();

        if (showPrompt)
        {
            if (this.currentView == 1)
            {
                this.clearFunc();
            }
            else
            {
                this.tileClearFunc();
            }
        }

    }

    appendResult(value)
    {
        if (this.currentView == 1)
        {
            $(`#${this.id}-sc-body`).append(value);
        }
        else
        {
            $(`#${this.id}-sc-list`).append(value);
        }

    }

    scSearchValidate()
    {

        if (this.customSearchValidation != null)
        {
            if (this.customSearchValidation())
            {
                this.searchMode = true;
                $(`#${this.id}_searchFor`).text('Search Selected').html();
                this.refreshLister();
            }
            return;
        }

        let searchValue = $(`#${this.id}_searchText`).val();
        if (searchValue != null && searchValue.trim() != "")
        {
            this.searchMode = true;
            this.searchForText = searchValue;
            $(`#${this.id}_searchFor`).text('Searching for:  ' + searchValue).html();
            this.refreshLister();
        }
        else
        {
            showMessage("Enter a search term");
        }
    }

}