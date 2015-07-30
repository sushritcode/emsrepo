var Validate = {};
Validate.isset = function(string)
{
	return !!string;
};
Validate.empty = function(string)
{
	return string.replace(/\s+/g, '').length == 0;
};
Validate.email = function(string)
{
	return /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i.test(string);
};
Validate.url = function(string)
{
	return /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/i.test(string);
};
Validate.date = function(string, preutc)
{
	var date = Date.parse(string);
	if(isFinite(date))
	{
		return true;
	}
	if(preutc)
	{
		var now = new Date();
		string = string.replace(/\d{4}/, now.getFullYear());
		date = Date.parse(string);
		return isFinite(date);
	}
	return false;
};
Validate.zip = function(string, plus4)
{
	var pattern = plus4 ? /^\d{5}-\d{4}$/ : /^\d{5}$/;
	return pattern.test(string);
};
Validate.phone = function(string)
{
	return /^\(?([0-9]{3})\)?[-.\s]?([0-9]{3})[-.\s]?([0-9]{4})$/.test(string);
};
Validate.creditCard = function(string)
{
	var valid = /^[\d-\s]$/.test(string);
	if(!valid)
	{
		return false;
	}
	return Validate.luhn(string);
};
Validate.luhn = function(string)
{
	var numeric = string.replace(/\d+/g, '');
	var digits = numeric.split('');
	var count = digits.length;
	var parity = count % 2;
	var total = 0;
	for(var i = 0; i < count; i++)
	{
		var digit = digits[i];	
		if ((i % 2) == parity) {
			digit *= 2;
			if(digit > 9) {
				digit -= 9;
			}
		}
		total += digit;	
	}
	return (total % 10) == 0;
};
Validate.integer = function(string)
{
	return /^\-?\d+$/.test(string);
};
Validate.numeric = function(string)
{
	return /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(string);
};
Validate.currency = function(string, us)
{
	return /^\$-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(string);
};
Validate.ip = function(string)
{
	return /^((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){3}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})$/.test(string);
};
Validate.ssn = function(string)
{
	return /^\d{3}-\d{2}-\d{4}$/.test(string);
};
Validate.tin = function(string)
{
	return /^\d{2}-\d{7}$/.test(string);
};
Validate.base64 = function(string)
{
	return /[^a-zA-Z0-9\/\+=]/i.test(string);
};
Validate.alpha = function(string)
{
	return /^[a-z]$/i.test(string);
};
Validate.alphaNumeric = function(string)
{
	return /^[a-z0-9]$/i.test(string);
};
Validate.lowercase = function(string)
{
	return string.toLowerCase() == string;
};
Validate.uppercase = function(string)
{
	return string.toUpperCase() == string;
};
Validate.min = function(string, length)
{
	return string.length >= length;
};
Validate.max = function(string, length)
{
	return string.length <= length;
};
Validate.between = function(string, min, max)
{
	return string.length >= min && string.length <= max;
};



function returnValidateVal(type , val)
{
	var arrType = type.split(",");
	for(i=0;i<=arrType.length;i++)
		switch(arrType[i])
		{
			case "empty":
				if(!Validate.empty(val))
				 return false;
				break;
			case "email":
				return Validate.email(val);
				break;
			case "url":
				return Validate.url(val);
				break;
			case "date":
				return Validate.date(val);
				break;
			case "zip":
				return Validate.zip(val);
				break;
			case "phone":
				return Validate.phone(val);
				break;
			case "creditCard":
				return Validate.creditCard(val);
				break;
			case "integer":
				return Validate.integer(val);
				break;
			case "numeric":
				return Validate.numeric(val);
				break;
			case "currency":
				return Validate.currency(val);
				break;
			case "ip":
				return Validate.ip(val);
				break;
			case "alphaNumeric":
				return Validate.alphaNumeric(val);
				break;
			case "alpha":
				return Validate.alpha(val);
				break;

			case "min":
				return Validate.min(val);
				break;
			case "max":
				return Validate.max(val);
				break;
		}
};
