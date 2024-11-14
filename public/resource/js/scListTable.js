class scListTable
{

    constructor(id, tableStyle = 'table table-bordered', headerStyle = 'rmb-header rbm-blue-header ', customSearch = '')
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

        // preset function

        this.refreshFunc = null;
        this.clearFunc = null;
        this.populateFunc = null;
        this.afterPopulateFunc = null;
        this.showAfterPopulateFunc = null;
        this.customSearchValidation = null;

        let searchView = '';

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
                
            
                <button class="btn rbm-blue w3-hover-gray" type="button" id='${this.id}_scSearchButton' title="Search">
                  <i class="fas fa-search"></i>
                </button>
            
                <button class="btn rbm-blue w3-hover-gray" title='Clear Search' id='${this.id}_scCloseSearchButton'>
                    <i class="fa fa-window-close"></i>
                </button>
            
                <button class="btn rbm-blue w3-hover-gray" title="refresh" id="${this.id}_scRefreshButton">
                    <i class="fa fa-sync-alt"></i>
                </button>
            
            </div>

            <div>

                <div class="w3-center" id="${this.id}_searchFor"></div>
            </div>`;
        }

        scParent.html(`
       
        <div id='${this.id}_scHeader' class="w3-row sc-header" style='display:none'>
        
            <div class="w3-half">
                ${searchView}
            </div>
            <div class='w3-right-align w3-half page-limiter'>
                <button class='pager-limit' data-limit='1' id='${this.id}_pgrlim1'>1</button>
                <button class='pager-limit pager-limit-selected' data-limit='10' id='${this.id}_pgrlim10'>10</button>
                <button class='pager-limit' data-limit='50' id='${this.id}_pgrlim50'>50</button>
                <button class='pager-limit' data-limit='100' id='${this.id}_pgrlim100'>100</button>
            </div>
        </div>
        

        <table class="${this.tableStyle}">
            <thead id="${this.id}-sc-head" class="${this.headerStyle}">
            </thead>
            <tbody id="${this.id}-sc-body">

            </tbody>
        </table>

        <div class='w3-padding w3-padding-16 w3-center ' id='${this.id}_scOptPager' style='display:none'>

        </div>
        `);



        $(`#${this.id}_scHeader .pagelimiter .pager-limit`).click(() =>
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

                $(`#${this.id}_scHeader .page-limiter .pager-limit-selected`).removeClass("pager-limit-selected");
                $(`#${this.id}_pgrlim${this.pageLimit}`).addClass("pager-limit-selected");
                this.refreshLister();
            }
        });

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
    }

    scSetupLister(refreshArg, clearArg, populateArg, afterPopulateArg, showAfterPopulateArg, customSearchValidationArg = null)
    {
        this.refreshFunc = refreshArg;
        this.clearFunc = clearArg;
        this.populateFunc = populateArg;
        this.afterPopulateFunc = afterPopulateArg;
        this.showAfterPopulateFunc = showAfterPopulateArg;
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
            //$(`#${this.id}_scOptPager`).append('<span class="inline-block w3-padding">Page: </span>');
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

    populateLister(Data)
    {
        this.dataRecords = Data;
        if (this.dataRecords != null && this.dataRecords.count != null && this.dataRecords.count > 0)
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
            this.showHeader();

        }
        else
        {
            this.clearResults();
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

    clearResults()
    {

        let message = this.clearFunc();
        $(`#${this.id}-sc-body`).html(message);
        $(`#${this.id}-sc-head`).html("");

    }

    appendResult(value)
    {
        $(`#${this.id}-sc-body`).append(value);
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