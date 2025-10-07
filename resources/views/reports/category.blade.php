@extends('layouts.app')

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

    label.div-list, label.div-list > div input {
        cursor: pointer;
        width: 190px
    }
    label.div-list.parent-items :hover {
        font-weight: bold;
    }
    label.div-list {
        display: inline-flex;
        align-items: center;
        border: 1px solid #ccc;
        margin: 4px;
        border-radius: 4px;
    }
    label.div-list > div {
        display: flex;
        padding: 4px;
    }
    label.div-list, label.div-list > div input {
        cursor: pointer;
    }
    label.div-list > div input, label > input {
        height: 16px;
        width: 16px;
    }
    
    .titleEmployee div {
        color: #000;
        font-size: 14px;
    }

    .table-log, .table-leave {
        table-layout: auto !important;
        width: 100% !important;
    }

    .table-log td, .table-leave td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 12px;
        padding: 4px;
    }

    .table-log td:nth-child(2), .table-log td:nth-child(3), .table-log td:nth-child(11) {
        white-space: normal;
        word-wrap: break-word;
    }

    @media (max-width: 768px) {
        .table-log td, .table-leave td {
            font-size: 10px;
            padding: 2px;
        }
    }
</style>

<div class="pagetitle">
    <h1 class="text-capitalize">Category Report</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
            <li class="breadcrumb-item active text-capitalize">Category Report</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body pt-3">
            <div class="d-inline-block rounded mb-4 bg-dark text-light">
                <div class="p-3" style="max-width: 660px">
                    <div class="d-inline-flex">
                        <div class="fw-bold fs-5" style="width: 150px">Choose Date</div>
                        <div><input type="date" id="datePicker" max="{{ date('Y-m-d') }}" class="px-2"></div>        
                    </div>
                    <div class="border text-light rounded my-3 p-2 bg-light text-dark">
                        <div id="containerCheckboxTitle" class="fw-bold"></div>
                        <div id="containerCheckboxContent"></div>
                    </div>
                    <button id="pdf" class="btn w-100 btn-secondary pe-none">Print PDF</button>
                </div>
            </div>
            <div id="containerLog"></div>
        </div>
    </div>
</section>

<script>
    function getCustomDate(date, format) {
        const d = new Date(date);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        
        switch(format) {
            case 'date_mysql':
                return `${year}-${month}-${day}`;
            case 'dayDate_mysql':
                return `${dayNames[d.getDay()]}, ${d.getDate()} ${monthNames[d.getMonth()]} ${year}`;
            default:
                return `${day}/${month}/${year}`;
        }
    }
    
    function toHoursAndMinutes(minutes) {
        const hours = Math.floor(minutes / 60);
        const mins = minutes % 60;
        return hours + (mins / 60);
    }

    if(!localStorage.getItem('dateLocal')) {
        localStorage.setItem('dateLocal', getCustomDate(new Date(), 'date_mysql'));
    }
    var timeout = null, num = 1, datas;
    const mainRoute = 'report/category';
    datePicker = document.getElementById('datePicker'),
    containerLog = document.getElementById('containerLog'),
    containerCheckboxTitle = document.getElementById('containerCheckboxTitle'),
    containerCheckboxContent = document.getElementById('containerCheckboxContent'),
    pdf = document.getElementById('pdf');

    var arrCheckbox = []

    const headerLog = `
    <table class="table-log table table-bordered table-sm table-striped mb-4" style="width: 100%; table-layout: auto;">
        <thead class="table-primary text-center align-top">
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Subject / Address</th>
                <th style="width: 20%;">Description</th>
                <th style="width: 8%;">Unit Qty</th>
                <th style="width: 12%;">Category</th>
                <th style="width: 12%;">Task</th>
                <th style="width: 12%;">Builder</th>
                <th style="width: 12%;">Dweling</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 8%;">Duration Minutes</th>
                <th style="width: 20%;">Additional Notes</th>
                <th style="width: 10%;">Work Status</th>
            </tr>
        <tbody>`;

    const getData = () => {
        var tableCategories = '', tableLog = '';
        const date = localStorage.getItem('dateLocal');
        console.log('Fetching data for date:', date);
        fetch(`/${mainRoute}/getData?date=${date}`).then((response) => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        }).then((data) => {
            console.log('Data received:', data);
            arrCheckbox = []; 
            const dataCategories = data.category;
            if(dataCategories.length > 0) {
                var checkboxCategories = '';
                dataCategories.forEach(element => {
                    let open_categori_employee = element['category'];
                    checkboxCategories += `
                    <label class="div-list parent-items" for="${element.category}">
                        <div><input class="chk" id="${element.category}" type="checkbox" value="${element.category}"></div>
                        <div>${element.category}</div>
                    </label>`;

                    let titleCategory = `
                    <div class="titleCategory">
                        <div class="d-flex fs-6 fw-bold text-primary"><div style="width: 90px">Category</div><div>: ${element.category}</div></div>
                    </div>`;
                    let parentTable = '';
                    element.employee.forEach(element1 => {
                        let titleEmployee = `
                        <div class="titleEmployee rounded border mt-2 mb-2 p-2 d-inline-block">
                            <div class="d-flex fw-bold"><div style="width: 80px">Name</div><div>: ${element1.employee.split(',')[0]} ${element1.employee.split(',')[2]}</div></div>
                            <div class="d-flex fw-bold"><div style="width: 80px">Division</div><div>: ${element1.employee.split(',')[1]}</div></div>
                            <div class="d-flex fw-bold"><div style="width: 80px">Date</div><div>: ${getCustomDate(date, 'dayDate_mysql')}</div></div>
                        </div>`;
                        let bodyLog = '', no = 1, minute = 0;
                        element1.items.forEach(el => {
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
                        parentTable += headerLog + titleEmployee + bodyLog + '</tbody>' + tableFooter + '</table></div>';
                    });
                    tableCategories += titleCategory + parentTable;
                });
                containerCheckboxTitle.innerHTML = '';
                containerCheckboxContent.innerHTML = checkboxCategories;
                document.querySelectorAll('.chk').forEach(checkbox => {
                    checkbox.addEventListener('change', e => {
                        if (e.target.checked) {
                            arrCheckbox.push(e.target.value);
                        } else {
                            arrCheckbox.splice(arrCheckbox.indexOf(e.target.value), 1);
                        }
                        toggledata();
                    });
                });
                containerLog.innerHTML = tableCategories +'<div id="bs" class="text-danger text-end mt-3 fst-italic" style="font-size: 14px">* Non Active Employee</div>';
                console.log('Table HTML generated:', tableCategories.length, 'characters');
            } else {
                containerCheckboxTitle.innerHTML = '<div class="text-danger fs-6 fw-bold text-center">This Date has no Data</div>';
                containerCheckboxContent.innerHTML = '';
                pdf.classList.remove('btn-primary'); pdf.classList.add('btn-secondary'); pdf.classList.add('pe-none');
                containerLog.innerHTML = '';
            }
        }).catch((error) => {
            console.error('Error fetching data:', error);
            containerCheckboxTitle.innerHTML = '<div class="text-danger fs-6 fw-bold text-center">Error loading data: ' + error.message + '</div>';
            containerCheckboxContent.innerHTML = '';
            containerLog.innerHTML = '';
        });
    };

    const toggledata = () => {
        if(arrCheckbox.length > 0) {
            pdf.classList.remove('btn-secondary');
            pdf.classList.add('btn-primary');
            pdf.classList.remove('pe-none');
        } else {
            pdf.classList.remove('btn-primary');
            pdf.classList.add('btn-secondary');
            pdf.classList.add('pe-none');
        }
    }
    
    document.querySelector('#pdf').addEventListener('click', () => {
        const date = localStorage.getItem('dateLocal');
        
        if (!date) {
            alert('Please select a date');
            return;
        }
        
        if (arrCheckbox.length === 0) {
            alert('Please select at least one category');
            return;
        }
        
        // Show loading state
        pdf.innerHTML = 'Generating PDF...';
        pdf.disabled = true;
        
        fetch(`/${mainRoute}/print?date=${date}&data=${encodeURIComponent(JSON.stringify(arrCheckbox))}`).then((response) => {
            return response.json();
        }).then((data) => {
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                data.forEach(element => {
                    window.open(element, '_blank');
                });
            }
        }).catch((error) => {
            alert('Error generating PDF: ' + error.message);
        }).finally(() => {
            // Reset button state
            pdf.innerHTML = 'Print PDF';
            pdf.disabled = false;
        });
    });

    datePicker.value = localStorage.getItem('dateLocal');
    datePicker.addEventListener('change', (e) => {
        let val = e.target.value;
        localStorage.setItem('dateLocal', val);
        if(val) {
            getData();
        } else {
            localStorage.clear();
            getData();
        }
    });

    window.addEventListener('load', () => {
        console.log('Page loaded, calling getData()');
        getData();
    });
    
    // Test function to check if JavaScript is working
    function testJavaScript() {
        console.log('JavaScript is working!');
        alert('JavaScript is working!');
    }
</script>
@endsection
