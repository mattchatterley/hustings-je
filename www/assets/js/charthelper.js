function expandYValues(values)
{
    var expanded = new Array(values.length * 2);

    var j = 0;
    for(var i=0;i<values.length;i++)
    {
        expanded[j++] = values[i].y;
        expanded[j++] = values[i].y1;
    }

    return expanded;
}

function getYCoordinate(element)
{
    return element.y;
}

function getYCoordinate2(element)
{
    return element.y1;
}
