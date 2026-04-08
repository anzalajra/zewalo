@extends('layouts.frontend')

@section('title', 'Schedule Calendar')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css' rel='stylesheet' />
<style>
    #schedule-calendar .fc-event {
        cursor: pointer;
        font-size: 0.75rem;
        padding: 2px 4px;
    }
    #schedule-calendar .fc-toolbar-title {
        font-size: 1.25rem !important;
    }
    #schedule-calendar .fc-daygrid-more-link {
        font-size: 0.75rem;
    }

    /* Tooltip */
    .fc-tooltip {
        position: absolute;
        z-index: 9999;
        background: #1f2937;
        color: #fff;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.8rem;
        line-height: 1.5;
        max-width: 300px;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .fc-tooltip .tooltip-label {
        color: #9ca3af;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Legend */
    .schedule-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 12px 0;
    }
    .schedule-legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #374151;
    }
    .schedule-legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        #schedule-calendar .fc-toolbar {
            flex-direction: column;
            gap: 8px;
        }
        #schedule-calendar .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-900">Rental Schedule</h1>

    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-gray-200">
        <div id="schedule-calendar"></div>

        <div class="schedule-legend mt-4 pt-4 border-t border-gray-100">
            <div class="schedule-legend-item">
                <span class="schedule-legend-dot" style="background:#f97316"></span> Quotation
            </div>
            <div class="schedule-legend-item">
                <span class="schedule-legend-dot" style="background:#3b82f6"></span> Confirmed
            </div>
            <div class="schedule-legend-item">
                <span class="schedule-legend-dot" style="background:#22c55e"></span> Active
            </div>
            <div class="schedule-legend-item">
                <span class="schedule-legend-dot" style="background:#a855f7"></span> Completed
            </div>
            <div class="schedule-legend-item">
                <span class="schedule-legend-dot" style="background:#6b7280"></span> Cancelled
            </div>
            <div class="schedule-legend-item">
                <span class="schedule-legend-dot" style="background:#ef4444"></span> Late Pickup / Return
            </div>
            <div class="schedule-legend-item">
                <span class="schedule-legend-dot" style="background:#eab308"></span> Partial Return
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipEl = null;

    var calendar = new FullCalendar.Calendar(document.getElementById('schedule-calendar'), {
        initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
        firstDay: 1,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        events: {
            url: '{{ route("frontend.schedule.events") }}',
            method: 'GET',
            failure: function() {
                console.error('Failed to load schedule events.');
            }
        },
        eventDisplay: 'block',
        dayMaxEvents: 3,
        moreLinkClick: 'popover',
        selectable: false,
        editable: false,

        eventMouseEnter: function(info) {
            var props = info.event.extendedProps;
            tooltipEl = document.createElement('div');
            tooltipEl.className = 'fc-tooltip';
            tooltipEl.innerHTML =
                '<div><span class="tooltip-label">Status</span><br>' + props.status + '</div>' +
                '<div style="margin-top:6px"><span class="tooltip-label">Period</span><br>' + props.start_formatted + ' — ' + props.end_formatted + '</div>' +
                (props.items ? '<div style="margin-top:6px"><span class="tooltip-label">Items</span><br>' + props.items + '</div>' : '');

            document.body.appendChild(tooltipEl);

            var rect = info.el.getBoundingClientRect();
            tooltipEl.style.top = (rect.bottom + window.scrollY + 8) + 'px';
            tooltipEl.style.left = Math.min(rect.left + window.scrollX, window.innerWidth - 320) + 'px';
        },

        eventMouseLeave: function() {
            if (tooltipEl) {
                tooltipEl.remove();
                tooltipEl = null;
            }
        }
    });

    calendar.render();
});
</script>
@endpush
