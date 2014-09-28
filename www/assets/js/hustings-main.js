// initial set-up
$(document).ready(function()
{
    $('.datepicker').datepicker();
});

function updateVisuals()
{
    alert('updating...');
    
    // prepare parameters
    var graphType = $('input:radio[name=graph-type]:checked').val();
    //alert(graphType);
    
    var participantGroup = $('input:checkbox.participant-group:checked').val();
    if(!participantGroup)
    {
        participantGroup = '';
    }
    //alert(participantGroup);
    
    var participants = $('input:checkbox.participant:checked');

    if(participants.length > 0)
    {
        participants = participants.join();
    }
    else
    {
        participants = '';
    }
    
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
            participants: participants,
            range: dateRange,
            from: dateFrom,
            to: dateTo,
            timeslots: timeSlot,
            dataset: dataSet
        },
        success: function(data, textStatus, xhr)
        {
            startBarChart(data, 'chart-placeholder-1');
            startLineChart(data, 'chart-placeholder-2');
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