@extends('layouts.app')


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

@section('content')

<div class="container">
    <h2 class="h2 text-center mb-5 border-bottom pb-3">Activities</h2>
    <div id='full_calendar_events'></div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    var activity_start, activity_end;
    $(document).ready(function() {
        var SITEURL = "{{ url('/') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var calendar = $('#full_calendar_events').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,listYear'
            },
            editable: true,
            events: SITEURL + "/getActivities",
            googleCalendarApiKey: 'AIzaSyDcnW6WejpTOCffshGDDb4neIrXVUA1EAE',
            displayEventTime: false,
            eventRender: function(event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function(activity_start, activity_end, allDay) {
                $('#addActivityForm')[0].reset();
                $('#addActivity').modal('show');
                $('#activity_start').val($.fullCalendar.formatDate(activity_start, "Y-MM-DD HH:mm:ss"));
                $('#activity_end').val($.fullCalendar.formatDate(activity_end, "Y-MM-DD HH:mm:ss"));
                $('.formSubmit').off('click').on('click', function(e) {
                    $(this)
                        .text("Please Wait...")
                        .attr('disabled', 'disabled');
                    e.preventDefault();
                    var formElement = document.getElementById("addActivityForm");
                    data = new FormData(formElement);
                    data.append('type', 'create');
                    $.ajax({
                        url: SITEURL + "/activity_action",
                        data: data,
                        processData: false,
                        contentType: false,
                        type: "POST",
                        success: function(data) {
                            $("button[type='submit']")
                                .text("Submit")
                                .attr('disabled', false);
                            if (data.msg != '' && data.msg !== undefined) {
                                displayMessage(data.msg);
                            } else {
                                displayMessage("Actvity created.");
                                $('#addActivity').modal('hide');
                                calendar.fullCalendar('renderEvent', {
                                    id: data.id,
                                    title: data.title,
                                    start: activity_start,
                                    end: activity_end,
                                    allDay: allDay
                                }, true);
                                calendar.fullCalendar('unselect');
                            }

                        }
                    });
                    return false;

                })
            },
            eventDrop: function(event, delta) {
                var activity_start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                var activity_end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
                $.ajax({
                    url: SITEURL + '/activity_action',
                    data: {
                        title: event.title,
                        activity_start: activity_start,
                        activity_end: activity_end,
                        id: event.id,
                        type: 'edit'
                    },
                    type: "POST",
                    success: function(response) {
                        displayMessage("Actvity updated");
                    }
                });
            },
            eventClick: function(event) {
                var eventDelete = confirm("Are you sure?");
                if (eventDelete) {
                    $.ajax({
                        type: "POST",
                        url: SITEURL + '/activity_action',
                        data: {
                            id: event.id,
                            type: 'delete'
                        },
                        success: function(response) {
                            calendar.fullCalendar('removeEvents', event.id);
                            displayMessage("Actvity removed");
                        }
                    });
                }
            }
        });

    });

    function displayMessage(message) {
        toastr.success(message, 'Event');
    }
</script>

<!-- Modal -->
<div class="modal fade" id="addActivity" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addActivityLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addActivityForm">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addActivityLabel">Add activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Title</label>
                        <input type="text" class="form-control" name="title" required id="title">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Image</label>
                        <input type="file" class="form-control" required name="image" id="title">
                    </div>
                    <div class="form-group">
                        <label for="email">User</label>
                        <select class="form-control" name="user_id">
                            <option value="0">Global</option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    @csrf
                    <input type="hidden" id="activity_start" name="activity_start">
                    <input type="hidden" id="activity_end" name="activity_end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="formSubmit btn btn-primary">Submit</button>
                </div>
        </form>
    </div>
</div>
</div>