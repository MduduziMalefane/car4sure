/*
 * A class to manipulate and manage UI data
 * @type type
 */
class FormObject
{
    /*
     * Create a new instance of the form object with a defined form element tage
     * @param {type} formElement
     * @returns {FormObject}
     */
    constructor(formElement)
    {
        this.FormElements = [];
        this.FormElement = formElement;
    }

    /*
     * Initialize the form object and return if the process was a success
     * @returns {Boolean} Return if the object was able to read attributes
     */
    readFormElements()
    {
        if (this.FormElement === undefined || this.FormElement.length < 1)
        {
            return false;
        }

        let elements = document.querySelectorAll("[data-sform=" + this.FormElement + "]");

        if (elements !== null && elements.length > 0)
        {
            for (let i = 0; i < elements.length; i++)
            {
                let element = elements[i];

                let id = element.getAttribute("id");
                let tag = element.tagName.toUpperCase();
                let validate = element.getAttribute("data-validate");
                let min = UtilityObject.getNumber(element.getAttribute("data-validateMin"));
                let max = UtilityObject.getNumber(element.getAttribute("data-validateMax"));

                if (id !== undefined && id.length > 0)
                {
                    let iObject = new Object();
                    iObject.id = id;
                    iObject.condition = [];

                    if (validate !== undefined)
                    {
                        switch (validate)
                        {
                            case "a":
                            case "alphabetic":
                                iObject.condition.push(ValidateObject.validateAlphabetic);
                                break;

                            case "as":
                            case "alphabeticspace":
                                iObject.condition.push(ValidateObject.validateAlphabeticSpace);
                                break;

                            case "an":
                            case "alphanumeric":
                                iObject.condition.push(ValidateObject.validateAlphaNumeric);
                                break;

                            case "ans":
                            case "alphanumericspace":
                                iObject.condition.push(ValidateObject.validateAlphaNumericSpace);
                                break;

                            case "d":
                            case "decimal":
                                iObject.condition.push(ValidateObject.validateDecimal);
                                break;

                            case "n":
                            case "numeric":
                                iObject.condition = ValidateObject.validateNumeric;
                                break;

                            case "nu":
                            case "number":
                                iObject.condition = ValidateObject.validateNumber;
                                break;

                            case "da":
                            case "date":
                                iObject.condition = ValidateObject.validateDate;
                                break;

                            case "dt":
                            case "datetime":
                                iObject.condition = ValidateObject.validateDateTime;
                                break;


                            case "":
                            case "custom":
                                break;
                        }
                    }

                    if (min !== undefined && max !== undefined)
                    {

                    }

                    switch (tag)
                    {
                        case "INPUT":
                        case "TEXTAREA":
                            iObject.value = element.value;
                            break;

                        case "DIV":
                        case "P":
                            iObject.value = element.innerHTML;
                            break;

                    }



                    this.FormElements.push(iObject);
                }

            }
        }
    }

    clearForm()
    {

    }

    validateForm()
    {

        if (this.FormElements.length > 0)
        {

            for (let i = 0; i < this.FormElements.length; i++)
            {
                let element = this.FormElements[i];


                if (element.condition != undefined)
                {

                }
            }
        }

        return true;
    }

    getObject()
    {

        let rObject = new Object();


        for (let i = 0; i < this.FormElements.length; i++)
        {
            let element = this.FormElements[i];
            rObject[element.id] = element.value;
        }

        return rObject;
    }
}


class ValidateObject
{

    isEmpty(value)
    {
        return value !== undefined || value.length == 0;
    }

    validateValidRange(value, min, max)
    {
        let len = value !== undefined ? value.length : 0;
        return len >= min && len <= max;
    }

    invalidRange(value, min, max)
    {
        let len = value !== undefined ? value.length : 0;
        return len < min || len > max;
    }

    validateNumber(value)
    {
        let e = value.trim();

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
            e = e.substr(1, e.length - 1).trim();
        }


        for (var y = 0; y < e.length; y++)
        {
            var ee = e.charAt(y);
            if (!validateDigit(ee))
            {
                return false;
            }
        }

        return true;
    }

    validateDecimal(value)
    {
        let e = value.trim();

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
            e = e.substr(1, e.length - 1).trim();
        }


        let sChar = '';
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

            if (eS.length > 2 || e.length < 3)
            {
                return false;
            }


            for (var x = 0; x < eS.length; x++)
            {
                var eV = eS[x];

                for (var y = 0; y < eV.length; y++)
                {
                    var ee = eV.charAt(y);
                    if (!validateDigit(ee))
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
                if (!validateDigit(ee))
                {

                    return false;
                }
            }
        }
        return true;
    }

    validateNumeric(value)
    {
        let e = value.trim();

        if (isEmpty(e))
        {
            return false;
        }

        for (var y = 0; y < e.length; y++)
        {
            var ee = e.charAt(y);
            if (!validateDigit(ee))
            {
                return false;
            }
        }

        return true;
    }

    validateAlphabetic(value)
    {
        let e = value.trim();

        if (isEmpty(e))
        {
            return false;
        }
        for (var i = 0; i < e.length; i++)
        {
            var ee = e.charAt(i);
            if (!validateLetter(ee))
            {
                return false;
            }
        }

        return true;
    }

    validateAlphabeticSpace(value)
    {
        let e = value.trim();

        if (isEmpty(e))
        {
            return false;
        }
        for (var i = 0; i < e.length; i++)
        {
            var ee = e.charAt(i);
            if (!validateLetter(ee) && ee != ' ')
            {

                return false;
            }
        }

        return true;
    }

    validateAlphaNumeric(value)
    {
        let e = value.trim();

        if (isEmpty(e))
        {
            return false;
        }
        for (var i = 0; i < e.length; i++)
        {
            var ee = e.charAt(i);
            if (!validateLetter(ee) && !validateDigit(ee))
            {
                return false;
            }
        }

        return true;
    }

    validateAlphaNumericSpace(value)
    {
        let e = value.trim();

        if (isEmpty(e))
        {
            return false;
        }
        for (var i = 0; i < e.length; i++)
        {
            var ee = e.charAt(i);
            if (!validateLetter(ee) && !validateDigit(ee) && ee != ' ')
            {
                return false;
            }
        }

        return true;
    }

    validateLetter(value)
    {
        return (value >= 'a' && value <= 'z') || (value >= 'A' && value <= 'Z');
    }

    validateDigit(value)
    {
        return (value >= '0' && value <= '9');
    }

    validateDate(value)
    {

    }

    validateDateTime(value)
    {

    }
}

class UtilityObject
{

    getNumber(value)
    {
        let m = undefined;

        if (ValidateObject.validateNumber(value))
        {
            m = new Number(value);
        }

        return m;
    }

}