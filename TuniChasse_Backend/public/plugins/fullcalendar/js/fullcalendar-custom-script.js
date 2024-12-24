$(document).ready(function() {



    $('#calendar').fullCalendar({

      header: {

        left: 'prev,next today',

        center: 'title',

        right: 'month,agendaWeek,agendaDay'

      },

      defaultDate: moment().format('YYYY-MM-DD'),

      navLinks: true, // can click day/week names to navigate views

      selectable: true,

      selectHelper: true,
      eventRender: function(event, element) {
        element.find('.fc-time').remove();
      },

      select: function(start, end) {
        var title = prompt('Event Title:');
        if (title) {
            var eventData = {
                title: title,
                start: start.format(),
                end: end.format()
            };

            $.ajax({
                url: 'enregistrer-evenement',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: eventData,
                success: function(response) {
                    if (response.success) {
                        // Rafraîchissez le calendrier ou affichez un message de succès
                        $('#calendar').fullCalendar('renderEvent', eventData, true);
                        alert('Événement enregistré avec succès!');
                    } else {
                        alert('Erreur lors de l\'enregistrement de l\'événement.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Une erreur s\'est produite lors de l\'enregistrement de l\'événement.');
                }
            });

        }
        $('#calendar').fullCalendar('unselect');
    },


      editable: true,

      eventLimit: true,
       // allow "more" link when too many events
       events: function(start, end, timezone, callback) {
        $.ajax({
          url: 'recuperer-evenements',
          method: 'GET',
          data: {
            start: start.format(),
            end: end.format()
          },
          success: function(response) {
            var events = [];
            response.forEach(function(event) {
              events.push({
                id: event.id,
                title: event.title,
                start: moment(event.start),
                end: moment(event.end)
              });
            });
            callback(events);
          },
          error: function(xhr, status, error) {
            console.error(error);
          }
        });
      }

    });



  });
