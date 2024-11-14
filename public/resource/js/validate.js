
function isEmpty(e)
{
    return e == null || e.length == 0;
}

function InvalidRange(value, min, max)
{
    var len = value != null ? value.length : 0;
    return len < min || len > max;
}

function checkNumber(value)
{
    var e = value.trim();

    if (isEmpty(e))
    {
        return false;
    }

    if (e.charAt(0) == '-')
    {
        if (e.length == 1)
        {
            return false;
        }
        e = e.substr(1, e.length - 1);
    }


    var sChar = '';
    if (e.indexOf('.') >= 0)
    {
        sChar = '.';
    }
    else if (e.indexOf(',') >= 0)
    {
        sChar = ',';
    }

    if (sChar.length > 0)
    {

        var eS = e.split(sChar);

        if (eS.length > 2)
        {
            return false;
        }


        for (var x = 0; x < eS.length; x++)
        {
            var eV = eS[x];

            if (eV.length == 0)
            {
                return false;
            }

            for (var y = 0; y < eV.length; y++)
            {
                var ee = eV.charAt(y);
                if (!checkDigit(ee))
                {
                    return false;
                }
            }

        }
    }
    else
    {

        if (e.length == 0)
        {
            return false;
        }
        for (var y = 0; y < e.length; y++)
        {
            var ee = e.charAt(y);
            if (!checkDigit(ee))
            {

                return false;
            }
        }
    }
    return true;
}

function checkNumeric(value)
{
    var e = value.trim();

    if (isEmpty(e))
    {
        return false;
    }

    if (e.length == 0)
    {
        return false;
    }
    for (var y = 0; y < e.length; y++)
    {
        var ee = e.charAt(y);
        if (!checkDigit(ee))
        {
            return false;
        }
    }

    return true;
}

function checkAlpha(value)
{
    var e = value.trim();

    if (isEmpty(e))
    {
        return false;
    }
    for (var i = 0; i < e.length; i++)
    {
        var ee = e.charAt(i);
        if (!checkLetter(ee))
        {

            return false;
        }
    }

    return true;
}

function checkAlpha(value)
{
    var e = value.trim();

    if (isEmpty(e))
    {
        return false;
    }
    for (var i = 0; i < e.length; i++)
    {
        var ee = e.charAt(i);
        if (!checkLetter(ee))
        {

            return false;
        }
    }

    return true;
}

function checkAlphaSpace(value)
{
    var e = value.trim();

    if (isEmpty(e))
    {
        return false;
    }
    for (var i = 0; i < e.length; i++)
    {
        var ee = e.charAt(i);
        if (!checkLetter(ee) && ee != ' ')
        {

            return false;
        }
    }

    return true;
}

function checkAlphaNumeric(value)
{
    var e = value.trim();

    if (isEmpty(e))
    {

        return false;
    }
    for (var i = 0; i < e.length; i++)
    {
        var ee = e.charAt(i);
        if (!checkLetter(ee) && !checkDigit(ee))
        {
     
            return false;
        }
    }

    return true;
}

function checkAlphaNumericSpace(value)
{
    var e = value.trim();

    if (isEmpty(e))
    {
        return false;
    }
    for (var i = 0; i < e.length; i++)
    {
        var ee = e.charAt(i);
        if (!checkLetter(ee) && !checkDigit(ee) && ee != ' ')
        {

            return false;
        }
    }

    return true;
}


function checkEmailAddress(e)
{

}

function checkLetter(e)
{
    return (e >= 'a' && e <= 'z') || (e >= 'A' && e <= 'Z');
}

function checkDigit(e)
{

    return (e >= '0' && e <= '9');
}