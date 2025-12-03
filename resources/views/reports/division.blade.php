@extends('layouts.app')

@section('title', '{{ $division->title }} Report - Daily Log System')

@section('content')
<style>
    select, input[type=checkbox] {
        cursor: pointer;
    }
    
    select.emoji {
        font-size: 21px;
    }

    input[type=checkbox] {
        height: 31px;
        width: 31px;
    }

    td i {
        font-size: 14px !important;
    }

    .tbl-employee td {
        padding: 0 4px;
        font-size: 14px;
    }
    input[type=date], select {
        min-height: 31px;
        width: 100%;
        border-radius: 4px;
        border: 0;
    }
    #note-main,
    .note-sub {
        background-color: #fff;
        color: #121212;
        font-size: 14px;
        padding: 5px;
        min-height: 31px;
        border-radius: 4px;
        text-align: left;
    }

    .main-display {
        display: inline-block;
    }

    .readOnly {
        pointer-events: none;
    }
    .headTitle td {
        font-size: 14px;
        font-weight:700;
    }
    
    /* Table Auto Adjust */
    .table-log, .table-leave {
        table-layout: auto !important;
        width: 100% !important;
    }
    
    .table-log th, .table-log td,
    .table-leave th, .table-leave td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 8px 4px;
        font-size: 12px;
    }
    
    .table-log th:nth-child(2), .table-log td:nth-child(2),
    .table-log th:nth-child(3), .table-log td:nth-child(3),
    .table-log th:nth-child(11), .table-log td:nth-child(11) {
        white-space: normal;
        word-wrap: break-word;
    }
    
    /* Responsive table */
    @media (max-width: 768px) {
        .table-log, .table-leave {
            font-size: 10px;
        }
        .table-log th, .table-log td,
        .table-leave th, .table-leave td {
            padding: 4px 2px;
        }
    }
</style>

<main id="main" class="main main-display">
    <div class="pagetitle">
        <h1 class="text-capitalize">{{ $division->title }} Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Reports</li>
                <li class="breadcrumb-item active text-capitalize">{{ $division->title }} Report</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body pt-3">
                <div class="main-set d-flex justify-content-between align-items-end mb-3">
                    <div class="d-inline-flex border rounded bg-dark text-light" style="box-shadow: 0px 2px 20px rgba(1, 41, 112, 0.1)">
                        <div class="p-2" style="width: 234px">
                            <div class="fw-bold text-center mb-2">Choose Date</div>
                            <div><input type="date" id="datePicker" max="{{ date('Y-m-d') }}" class="px-2"></div>
                            <div id="notes" class="text-center text-light w-100 fs-6 p-2 d-none">This Date has no Data</div>
                            <div id="pdf" class="btn btn-secondary btn-sm w-100 bg-primary mt-3" role="button">Print PDF</div>
                        </div>
                    </div>
                </div>
                <div id="containerLeave">
                    <table class="table-leave table table-bordered table-sm table-striped caption-top" style="width: 100%; table-layout: auto;">
                        <caption style="padding:0" class="text-danger pb-1 fw-bold">Leave List</caption>
                        <tbody>
                            <tr class="bg-danger text-center align-top">
                                <th class="text-light" style="width: 5%;">No</th>
                                <th class="text-light" style="width: 20%;">Name</th>
                                <th class="text-light" style="width: 15%;">Division</th>
                                <th class="text-light" style="width: 12%;">Sub Division</th>
                                <th class="text-light" style="width: 18%;">Role</th>
                                <th class="text-light" style="width: 10%;">Level</th>
                                <th class="text-light" style="width: 20%;">Reason</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="containerLog">
                    <!-- Logs will be loaded here -->
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

<script>
    if(!localStorage.getItem('dateLocal')) {
        localStorage.setItem('dateLocal', getCustomDate(new Date(), 'date_mysql'));
    }
    
    var timeout = null, num = 1, datas;
    const mainRoute = 'report/division';
    const datePicker = document.getElementById('datePicker');
    const containerLeave = document.getElementById('containerLeave');
    const containerLog = document.getElementById('containerLog');
    const notes = document.getElementById('notes');
    const pdfbtn = document.getElementById('pdf');

    var hasHoliday = false;
    
    const getHoliday = () => {
        const dateNow = localStorage.getItem('dateLocal');
        fetch(`/${mainRoute}/getHoliday?date=${dateNow}`)
            .then((response) => response.json())
            .then((data) => {
                hasHoliday = data;
            })
            .catch(error => console.error('Error:', error));
    };

    const headerLeave = `
        <table class="table-leave table table-bordered table-sm table-striped caption-top" style="width: 100%; table-layout: auto;">
            <caption style="padding:0" class="text-danger pb-1 fw-bold">Leave List</caption>
            <tr class="bg-danger text-center align-top">
                <th class="text-light" style="width: 5%;">No</th>
                <th class="text-light" style="width: 20%;">Name</th>
                <th class="text-light" style="width: 15%;">Division</th>
                <th class="text-light" style="width: 12%;">Sub Division</th>
                <th class="text-light" style="width: 18%;">Role</th>
                <th class="text-light" style="width: 10%;">Level</th>
                <th class="text-light" style="width: 20%;">Reason</th>
            </tr>`;
    
    const headerLog = `
        <table class="table-log table table-bordered table-sm table-striped" style="width: 100%; table-layout: auto;">
            <thead class="table-primary text-center align-top">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Subject / Address</th>
                    <th style="width: 15%;">Description</th>
                    <th style="width: 8%;">Unit Qty</th>
                    <th style="width: 10%;">Category</th>
                    <th style="width: 12%;">Task</th>
                    <th style="width: 12%;">Builder</th>
                    <th style="width: 10%;">Dweling</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 8%;">Duration Minutes</th>
                    <th style="width: 15%;">Additional Notes</th>
                    <th style="width: 8%;">Work Status</th>
                </tr>
            </thead>
            <tbody>`;

    const getData = (id) => {
        const date = localStorage.getItem('dateLocal');
        var tableLeave = '', bodyLeave = '', leaveLength = 0;
        var tableLog = '';
        
        fetch(`/${mainRoute}/getData?date=${date}`)
            .then((response) => response.json())
            .then((data) => {
                datas = data;
                let no = 1;
                
                data.forEach(element => {
                    if(element.leave.length > 0) {
                        element.leave.forEach(el => {
                            if(el.archive === null) {
                                bodyLeave += `
                                <tr>
                                    <td>${no}</td>
                                    <td class="fw-bold">${element.title}</td>
                                    <td>${element.division}</td>
                                    <td>${element.subdivision}</td>
                                    <td>${element.role}</td>
                                    <td>${element.position}</td>
                                    <td>${el.title}</td>
                                </tr>`;
                                no++;
                                leaveLength++;
                            }
                        });
                    }
                    
                    if(element.log.length > 0) {
                        const act = (element.archive === 1) ? `*` : '';
                        tableLog += `
                        <div class="tblEmployee rounded border mt-4 mb-1 p-2 d-inline-block">
                            <table class="table table-borderless table-sm headTitle" style="table-layout: auto">
                                <tr><td style="width: 88px">Name</td><td>: ${element.title} ${act}</td></tr>
                                <tr><td style="width: 88px">Division</td><td>: ${element.division}</td></tr>
                                <tr><td style="width: 88px">Date</td><td>: ${getCustomDate(date, 'dayDate_mysql')}</td></tr>
                            </table>
                        </div>`;
                        
                        let bodyLog = '', no = 1, minute = 0;
                        element.log.forEach(el => {
                            bodyLog += `
                            <tr>
                                <td>${no}</td>
                                <td>${el.title}</td>
                                <td>${el.description}</td>
                                <td>${el.qty}</td>
                                <td>${el.category}</td>
                                <td>${el.task}</td>
                                <td>${el.builder}</td>
                                <td>${el.dweling}</td>
                                <td>${el.status}</td>
                                <td>${el.duration}</td>
                                <td>${el.note}</td>
                                <td>${el.wtime}</td>
                            </tr>`;
                            no++;
                            minute += parseFloat(el.duration) || 0;
                        });
                        
                        var tableFooter = `
                        <tfoot class="table-dark border-secondary text-center align-top">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Hours:</th>
                                <th class="text-center total-duration">${parseFloat(toHoursAndMinutes(minute)).toFixed(2)}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>`;
                        tableLog += headerLog + bodyLog + '</tbody>' + tableFooter + '</table></div>';
                    }
                });
                
                containerLeave.innerHTML = '';
                tableLeave = headerLeave + bodyLeave + '</table>';
                if(leaveLength > 0) containerLeave.innerHTML = tableLeave;
                
                containerLog.innerHTML = tableLog + '<div id="bs" class="d-none text-danger text-end mt-3 fst-italic" style="font-size: 14px; display:none">* Non Active Employee</div>';

            })
            .then(() => {
                let logCount = 0;
                datas.forEach(element => {
                    if (element.log.length > 0) {
                        logCount++;
                    }
                });
                
                if(logCount > 0 && hasHoliday) containerLeave.innerHTML = '<div class="text-danger fs-5 fw-bold">Public Holiday</div>';

                if(logCount > 0) {
                    notes.classList.add('d-none');
                    pdfbtn.classList.remove('pe-none');
                    pdfbtn.classList.remove('bg-secondary');
                    pdfbtn.classList.add('bg-primary');
                    pdfbtn.classList.add('mt-3');
                    document.getElementById('bs').classList.remove('d-none');
                } else {
                    notes.classList.remove('d-none');
                    pdfbtn.classList.add('pe-none');
                    pdfbtn.classList.remove('bg-primary');
                    pdfbtn.classList.add('bg-secondary');
                    pdfbtn.classList.remove('mt-3');
                    document.getElementById('bs').classList.add('d-none');
                }
            })
            .catch(error => console.error('Error:', error));
    };
    
    pdfbtn.addEventListener('click', () => {
        const date = localStorage.getItem('dateLocal');
        const holiday = (hasHoliday) ? 1 : 0;
        
        if (!date) {
            Swal.fire({
                title: 'Error',
                text: 'Please select a date first',
                icon: 'error',
                confirmButtonColor: '#0d6efd'
            });
            return;
        }
        
        // Show loading state
        pdfbtn.innerHTML = 'Generating PDF...';
        pdfbtn.disabled = true;
        
        fetch(`/${mainRoute}/print?date=${date}&holiday=${holiday}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    Swal.fire({
                        title: 'Error',
                        text: data.error,
                        icon: 'error',
                        confirmButtonColor: '#0d6efd'
                    });
                    return;
                }
                
                // Open PDF in new tab
                window.open(data, '_blank');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Error generating PDF. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#0d6efd'
                });
            })
            .finally(() => {
                // Reset button state
                pdfbtn.innerHTML = 'Print PDF';
                pdfbtn.disabled = false;
            });
    });

    datePicker.value = localStorage.getItem('dateLocal');
    datePicker.addEventListener('change', (e) => {
        let val = e.target.value;
        localStorage.setItem('dateLocal', val);
        getHoliday();
        if(val) {
            getData();
        } else {
            localStorage.clear();
            getData();
        }
    });

    window.addEventListener('load', () => {
        getHoliday();
        getData();
    });

    // Helper functions
    function getCustomDate(date, format) {
        const d = new Date(date);
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        if (format === 'date_mysql') {
            return d.toISOString().split('T')[0];
        } else if (format === 'dayDate_mysql') {
            return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
        }
        return date;
    }

    function toHoursAndMinutes(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return hours + (mins / 60);
    }

</script>
@endsection
