// initial set-up
$(document).ready(function()
{
    $('.datepicker').datepicker();

    updateParameterFields();
    updateVisuals();
 });

function updateParameterFields()
{
    // set defaults
    $('#number-of-users').hide();

    if($('#participant-most-frequent').is(':checked'))
    {
        $('#number-of-users').show();            
    }

}

function updateVisuals()
{    
    // prepare parameters
    var graphType = $('input:radio[name=graph-type]:checked').val();
    //alert(graphType);
    
    var participantGroup = $('input:checkbox.participant-group:checked').val();
    if(!participantGroup)
    {
        participantGroup = '';
    }
    //alert(participantGroup);
    
    /*
    var participants = $('input:checkbox.participant:checked');

    if(participants.length > 0)
    {
        participants = participants.join();
    }
    else
    {
        participants = '';
    }*/
    
    //alert(participants);
    
    var dateRange = false;
    var dateFrom = false;
    var dateTo = false;

    if($('input#time-range:checked').length > 0)
    {
        dateRange = true;
        dateFrom = $('#date-from').val();
        dateTo = $('#date-to').val();
    }
    
    //alert(dateRange);
    //alert(dateFrom);
    //alert(dateTo);
    
    var timeSlot = $('#time-slot').val();
    //alert(timeSlot);
    
    var dataSet = $('#dataset').val();
    //alert(dataSet);

    var numberUsers = $('#number-users').val();
        
    // get the data points from the database
    // e.g. streamgraph expects a set of x-y pairs for each line
    $.ajax({
        async: false,
        url: 'handler.php',
        type: 'POST',
        dataType: 'json',
        data: 
        {
            group: participantGroup,
            //participants: participants,
            range: dateRange,
            from: dateFrom,
            to: dateTo,
            timeslots: timeSlot,
            dataset: dataSet,
            userlimit: numberUsers
        },
        success: function(data, textStatus, xhr)
        {
            console.debug(data);
            //startBarChart(data, 'chart-placeholder-1');
            startLineChart(data, 'chart-placeholder');

            setDataSetDescription(dataSet);
        },
        error: function (xhr, textStatus, errorThrown)
        {
            console.debug(xhr)
            console.debug(xhr.responseText);
            console.debug(textStatus);
            console.debug(errorThrown);
            alert('Failed! ' + textStatus + ' ' + errorThrown);
        }
    });    
}

function setDataSetDescription(dataSet)
{
    var description = '';
    switch(dataSet)
    {
        case 'overall-sentiment-by-user':
            description = 'The Overall Sentiment by User graph shows the total number of positive (and negative) scored tweets posted by each named person over a period of time.';
        break;
    }

    $('#dataset-description').text(description);
}