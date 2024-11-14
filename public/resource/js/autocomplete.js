function autocomplete(inp, listLink, splits)
{
    var busy = false;
    var currentFocus;
    inp.addEventListener("input", function (e)
    {
        if (busy)
        {
            return;
        }

        var val = this.value;
        closeAllLists();
        if (!val || val.length < 3)
        {
            return false;
        }
        currentFocus = -1;


        FetchList(val, this);

    });

    inp.addEventListener("keydown", function (e)
    {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x)
        {
            x = x.getElementsByTagName("div");
        }
        
        if (e.keyCode == 40)
        {
            currentFocus++;
            addActive(x);
        }
        else if (e.keyCode == 38)
        {
            currentFocus--;
            addActive(x);
        }
        else if (e.keyCode == 13)
        {
            e.preventDefault();
            if (currentFocus > -1)
            {
                if (x)
                {
                    x[currentFocus].click();
                }
            }
        }
    });



    function addActive(x)
    {
        /*a function to classify an item as "active":*/
        if (!x)
            return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length)
            currentFocus = 0;
        if (currentFocus < 0)
            currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x)
    {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++)
        {
            x[i].classList.remove("autocomplete-active");
        }
    }

    function closeAllLists(elmnt)
    {
        /*close all autocomplete lists in the document,
         except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++)
        {
            if (elmnt != x[i] && elmnt != inp)
            {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }

    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e)
    {
        closeAllLists(e.target);
    });


    function ListSuccess(arr, val, inputParent)
    {
        busy = false;

        var a, b, i;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", inputParent.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        inputParent.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++)
        {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase())
            {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/

                if (splits == true)
                {
                    b.innerHTML += "<input type='hidden' value='" + arr[i].split(",")[0] + "'>";
                }
                else
                {
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                }

                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e)
                {
                  
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    /*close the list of autocompleted values,
                     (or any other open lists of autocompleted values:*/
                    closeAllLists();
                });
                a.appendChild(b);
            }
        }
    }

    function ListFail()
    {
        busy = false;
    }

    function FetchList(param, parent)
    {

        busy = true;
        $.ajax({
            url: listLink + param,
            dataType: 'json',
            type: 'post',
            //contentType: 'application/json',
            data: null,
            processData: false,
            success: function (data1, textStatus, jQxhr)
            {
                ListSuccess(data1, param, parent);
            },
            error: function (jqXhr, textStatus, errorThrown)
            {
                ListFail();
            }
        });

    }
} 