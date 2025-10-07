@extends('layouts.app')

@section('title', 'Daily Log')

@section('content')
<main id="main" class="main d-inline-block">
    <div class="pagetitle">
        <h1 class="text-capitalize">Daily Log</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                <li class="breadcrumb-item active text-capitalize">Daily Log</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body pt-3">
                <h4 class="text-capitalize mb-4">Manage Daily Log</h4>
                <div class="btn-parent d-inline-flex">
                    <button type="button" id="btn-addRow" class="btn btn-sm btn-primary me-1"><i class="bi bi-plus-lg"></i>&nbsp;Add Row</button>
                    <button type="button" id="btn-saveRow" class="btn btn-sm me-3 btn-success"><i class="bi bi-check2-all"></i>&nbsp;Submit</button>
                    <input type="date" min="2019-09-01" class="border rounded border-1 ps-2 me-3" id="chaDate" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                    <button type="button" id="btn-reload" class="btn btn-sm btn-primary me-1"><i class="bi bi-arrow-clockwise"></i></button>
                    <button type="button" id="btn-reset-columns" class="btn btn-sm btn-outline-secondary me-1" title="Reset Column Widths"><i class="bi bi-arrows-expand"></i></button>
                    
                    <div class="expired text-danger ms-2 fs-5 d-none">Daily Log has expired, Wait until next day to input new data.</div>
                </div>
                <table class="table table-bordered table-sm table-striped mt-3" style="table-layout: auto; width: 100%;">
                    <thead class="table-dark border-secondary text-center align-top">
                        <tr>
                            <th class="resizable" data-column="0">Status<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="1">#<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="2">Subject +<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="3">Description<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="4">Unit Qty<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="5">Category<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="6">Task<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="7">Builder +<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="8">Dwelling<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="9">Status<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="10">Duration Minutes<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="11">Additional Notes<div class="resize-handle"></div></th>
                            <th class="resizable" data-column="12">Overtime<div class="resize-handle"></div></th>
                            <th class="cell-add bg-success border-white border-end-1 resizable" data-column="13" hidden="">Approved Note<div class="resize-handle"></div></th>
                            <th class="cell-add bg-success resizable" data-column="14" hidden="">Approved Emoji<div class="resize-handle"></div></th>
                        </tr>
                    </thead>
                    <tbody id="logsTableBody">
                        @forelse($logs as $index => $log)
                        <tr id="{{ $log->id }}" temp="{{ $log->temp ? '1' : '0' }}" app="{{ $log->approved ? '1' : '0' }}" appnote="{{ $log->approved_note ? '1' : '0' }}" appicon="{{ $log->approved_emoji ? '1' : '0' }}" style="color: #0d6efd">
                            <td class="text-center">
                                @if($log->approved)
                                    <button class="btns btn btn-sm btn-outline-success" disabled="true" style="pointer-events: none;"><i class="bi bi-check-lg"></i> Approved</button>
                                @else
                                    <button class="btns btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="bi bi-x-square"></i> Remove</button>
                                @endif
                            </td>
                            <td class="text-end">{{ $index + 1 }}</td>
                            <td><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="log" field="subject" placeholder="Subject" data="{{ $log->subject }}" spellcheck="false">{{ $log->subject }}</div></td>
                            <td><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="log" field="description" placeholder="Description" data="{{ $log->description }}" spellcheck="false">{{ $log->description }}</div></td>
                            <td class="text-end"><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="log" field="qty" placeholder="Qty" data="{{ $log->qty }}" spellcheck="false">{{ $log->qty }}</div></td>
                            <td><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="category" field="category" placeholder="Category" data="{{ $log->category->title ?? '' }}" spellcheck="false">{{ $log->category->title ?? '' }}</div></td>
                            <td><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="task" field="task" placeholder="Task" data="{{ $log->task->title ?? '' }}" spellcheck="false">{{ $log->task->title ?? '' }}</div></td>
                            <td><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="builder" field="builder" placeholder="Builder" data="{{ $log->builder->title ?? '' }}" spellcheck="false">{{ $log->builder->title ?? '' }}</div></td>
                            <td><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="dweling" field="dweling" placeholder="Dwelling" data="{{ $log->dweling->title ?? '' }}" spellcheck="false">{{ $log->dweling->title ?? '' }}</div></td>
                            <td><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="status" field="status" placeholder="Status" data="{{ $log->status->title ?? '' }}" spellcheck="false">{{ $log->status->title ?? '' }}</div></td>
                            <td class="text-end"><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="log" field="duration" placeholder="Duration" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" data="{{ $log->duration }}" spellcheck="false">{{ $log->duration }}</div></td>
                            <td><div contenteditable="{{ $log->approved ? 'false' : 'true' }}" style="pointer-events: {{ $log->approved ? 'none' : 'auto' }}; background-color: {{ $log->approved ? '#f8f9fa' : 'transparent' }};" class="log" field="note" placeholder="Additional Notes" data="{{ $log->note ?? '' }}" spellcheck="false">{{ $log->note ?? '' }}</div></td>
                            <td class="text-center"><input type="checkbox" trid="{{ $log->id }}" class="fwtime" style="width: 24px; height: 24px; cursor: {{ $log->approved ? 'not-allowed' : 'pointer' }};" onclick="fwtime(this)" {{ $log->temp ? 'checked' : '' }} {{ $log->approved ? 'disabled' : '' }}></td>
                            <td class="cell-add" data-bs-toggle="modal" data-bs-target="#replynoted1" hidden=""><div placeholder="Approved Note">{{ $log->approved_note ?? '' }}</div></td>
                            <td class="cell-add text-center" hidden=""><div placeholder="Emoji">{{ $log->approved_emoji ?? '' }}</div></td>
                        </tr>
                        @empty
                        <tr id="no-data">
                            <td colspan="14" class="text-center text-muted py-4">No logs found for today. Click "Add Row" to create a new log entry.</td>
                        </tr>
                        @endforelse
                    </tbody>
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
                            <th></th>
                            <th>Total Hours:</th>
                            <th class="text-center total-duration">{{ number_format($logs->sum('duration') / 60, 2) }}</th>
                            <th></th>
                            <th></th>
                            <th class="cell-add" hidden=""></th>
                            <th class="cell-add" hidden=""></th>
                        </tr>
                    </tfoot>
                </table>
                <div class="text-end mt-3 fst-italic text-secondary" style="font-size: 11px">* Column title with "+" sign, can input additional data</div>
            </div>
        </div>
    </section>
</main>

<!-- Hidden form for autocomplete data -->
<div id="autocompleteData" style="display: none;">
    <div id="categories">
        @foreach($categories as $category)
        <option value="{{ $category->title }}">{{ $category->title }}</option>
        @endforeach
    </div>
    <div id="tasks">
        @foreach($tasks as $task)
        <option value="{{ $task->title }}">{{ $task->title }}</option>
        @endforeach
    </div>
    <div id="builders">
        @foreach($builders as $builder)
        <option value="{{ $builder->title }}">{{ $builder->title }}</option>
        @endforeach
    </div>
    <div id="dwelings">
        @foreach($dwelings as $dweling)
        <option value="{{ $dweling->title }}">{{ $dweling->title }}</option>
        @endforeach
    </div>
    <div id="statuses">
        @foreach($statuses as $status)
        <option value="{{ $status->title }}">{{ $status->title }}</option>
        @endforeach
    </div>
</div>

<script>
let rowCounter = {{ $logs->count() }};
let currentDate = '{{ date('Y-m-d') }}';

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Add Row Function
document.getElementById('btn-addRow').addEventListener('click', function() {
    // Check if there are any approved logs
    const approvedRows = document.querySelectorAll('#logsTableBody tr[app="1"]');
    if (approvedRows.length > 0) {
        alert('Cannot add new rows when there are approved logs. Please create a new date entry.');
        return;
    }
    
    rowCounter++;
    const tbody = document.getElementById('logsTableBody');
    
    // Remove "no data" row if exists
    const noDataRow = document.getElementById('no-data');
    if (noDataRow) {
        noDataRow.remove();
    }
    
    const newRow = document.createElement('tr');
    newRow.id = 'new-' + rowCounter;
    newRow.setAttribute('temp', '1');
    newRow.setAttribute('app', '0');
    newRow.setAttribute('appnote', '0');
    newRow.setAttribute('appicon', '0');
    newRow.style.color = '#0d6efd';
    
    newRow.innerHTML = `
        <td class="text-center"><button class="btns btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="bi bi-x-square"></i> Remove</button></td>
        <td class="text-end">${rowCounter}</td>
        <td><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="log" field="subject" placeholder="Subject" data="" spellcheck="false"></div></td>
        <td><div contenteditable="true" style="pointer-events: auto" class="log" field="description" placeholder="Description" data="" spellcheck="false"></div></td>
        <td class="text-end"><div contenteditable="true" style="pointer-events: auto" class="log" field="qty" placeholder="Qty" data="1" spellcheck="false">1</div></td>
        <td><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="category" field="category" placeholder="Category" data="" spellcheck="false"></div></td>
        <td><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="task" field="task" placeholder="Task" data="" spellcheck="false"></div></td>
        <td><div contenteditable="true" style="pointer-events: auto" class="builder" field="builder" placeholder="Builder" data="" spellcheck="false"></div></td>
        <td><div contenteditable="true" style="pointer-events: auto" class="dweling" field="dweling" placeholder="Dwelling" data="" spellcheck="false"></div></td>
        <td><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="status" field="status" placeholder="Status" data="" spellcheck="false"></div></td>
        <td class="text-end"><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="log" field="duration" placeholder="Duration" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" data="0" spellcheck="false">0</div></td>
        <td><div contenteditable="true" style="pointer-events: auto" class="log" field="note" placeholder="Additional Notes" data="" spellcheck="false"></div></td>
        <td class="text-center"><input type="checkbox" trid="new-${rowCounter}" class="fwtime" style="width: 24px; height: 24px; cursor: pointer;" onclick="fwtime(this)"></td>
        <td class="cell-add" data-bs-toggle="modal" data-bs-target="#replynoted1" hidden=""><div placeholder="Approved Note"></div></td>
        <td class="cell-add text-center" hidden=""><div placeholder="Emoji"></div></td>
    `;
    
    tbody.appendChild(newRow);
    updateRowNumbers();
    // Setup autocomplete for new row
    setTimeout(() => {
        setupAutocomplete();
    }, 100);
});

// Remove Row Function
function removeRow(button) {
    const row = button.closest('tr');
    const rowId = row.id;
    
    if (rowId.startsWith('new-')) {
        // New row, just remove from DOM
        row.remove();
    } else {
        // Existing row, delete from database
        if (confirm('Are you sure you want to delete this log entry?')) {
            fetch(`/log/${rowId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    row.remove();
                    updateRowNumbers();
                    updateTotalDuration();
                } else {
                    alert('Error deleting log: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting log');
            });
        }
    }
}

// Update Row Numbers
function updateRowNumbers() {
    const rows = document.querySelectorAll('#logsTableBody tr');
    rows.forEach((row, index) => {
        const numberCell = row.querySelector('td:nth-child(2)');
        if (numberCell) {
            numberCell.textContent = index + 1;
        }
    });
}

// Update Total Duration
function updateTotalDuration() {
    const durationCells = document.querySelectorAll('.log[field="duration"]');
    let totalMinutes = 0;
    
    durationCells.forEach(cell => {
        const duration = parseFloat(cell.textContent) || 0;
        totalMinutes += duration;
    });
    
    const totalHours = (totalMinutes / 60).toFixed(2);
    document.querySelector('.total-duration').textContent = totalHours;
}

// Global autocomplete data
let autocompleteData = {};

// Load autocomplete data
async function loadAutocompleteData() {
    try {
        const response = await fetch('/log/autocomplete');
        autocompleteData = await response.json();
    } catch (error) {
        console.error('Error loading autocomplete data:', error);
    }
}

// Create dropdown for a cell
function createDropdown(cell, field, data) {
    // Remove existing dropdowns
    const existingDropdowns = document.querySelectorAll('.autocomplete-dropdown');
    existingDropdowns.forEach(dropdown => dropdown.remove());
    
    // Create dropdown container
    const dropdown = document.createElement('div');
    dropdown.className = 'autocomplete-dropdown';
    dropdown.style.cssText = `
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        min-width: 200px;
        display: none;
    `;
    
    // Filter data based on current input
    const currentValue = cell.textContent.toLowerCase().trim();
    let filteredData;
    
    if (currentValue === '') {
        // If cell is empty, show all data
        filteredData = data;
    } else {
        // If cell has content, show all data but highlight matching ones
        filteredData = data;
    }
    
    if (filteredData.length === 0) {
        dropdown.style.display = 'none';
        return;
    }
    
    // Create dropdown items
    filteredData.slice(0, 15).forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'dropdown-item';
        
        // Check if item matches current value
        const isMatch = currentValue !== '' && item.toLowerCase().includes(currentValue);
        
        if (isMatch) {
            // Highlight matching text
            const regex = new RegExp(`(${currentValue})`, 'gi');
            const highlightedText = item.replace(regex, '<strong>$1</strong>');
            itemDiv.innerHTML = highlightedText;
            itemDiv.style.cssText = `
                padding: 8px 12px;
                cursor: pointer;
                border-bottom: 1px solid #eee;
                background-color: #fff3cd;
            `;
        } else {
            itemDiv.textContent = item;
            itemDiv.style.cssText = `
                padding: 8px 12px;
                cursor: pointer;
                border-bottom: 1px solid #eee;
            `;
        }
        
        itemDiv.addEventListener('mouseenter', function() {
            this.style.backgroundColor = isMatch ? '#ffeaa7' : '#f5f5f5';
        });
        
        itemDiv.addEventListener('mouseleave', function() {
            this.style.backgroundColor = isMatch ? '#fff3cd' : 'white';
        });
        
        itemDiv.addEventListener('click', function() {
            cell.textContent = item;
            cell.setAttribute('data', item);
            dropdown.style.display = 'none';
            cell.focus();
        });
        
        dropdown.appendChild(itemDiv);
    });
    
    // Position dropdown relative to the cell
    const cellRect = cell.getBoundingClientRect();
    const containerRect = cell.closest('.card-body').getBoundingClientRect();
    
    dropdown.style.left = (cellRect.left - containerRect.left) + 'px';
    dropdown.style.top = (cellRect.bottom - containerRect.top + 2) + 'px';
    
    // Add to card body container
    cell.closest('.card-body').appendChild(dropdown);
    dropdown.style.display = 'block';
    
    // Close dropdown when clicking outside
    setTimeout(() => {
        document.addEventListener('click', function closeDropdown(e) {
            if (!dropdown.contains(e.target) && e.target !== cell) {
                dropdown.style.display = 'none';
                document.removeEventListener('click', closeDropdown);
            }
        });
    }, 100);
}

// Setup Autocomplete
function setupAutocomplete() {
    // Subject autocomplete
    const subjectCells = document.querySelectorAll('.log[field="subject"]');
    subjectCells.forEach(cell => {
        cell.addEventListener('click', function(e) {
            e.stopPropagation();
            if (autocompleteData.subjects && autocompleteData.subjects.length > 0) {
                createDropdown(this, 'subject', autocompleteData.subjects);
            }
        });
        
        cell.addEventListener('input', function() {
            if (autocompleteData.subjects && autocompleteData.subjects.length > 0) {
                createDropdown(this, 'subject', autocompleteData.subjects);
            }
        });
    });
    
    // Category autocomplete
    const categoryCells = document.querySelectorAll('.category[field="category"]');
    categoryCells.forEach(cell => {
        cell.addEventListener('click', function(e) {
            e.stopPropagation();
            if (autocompleteData.categories && autocompleteData.categories.length > 0) {
                createDropdown(this, 'category', autocompleteData.categories);
            }
        });
        
        cell.addEventListener('input', function() {
            if (autocompleteData.categories && autocompleteData.categories.length > 0) {
                createDropdown(this, 'category', autocompleteData.categories);
            }
        });
    });
    
    // Task autocomplete
    const taskCells = document.querySelectorAll('.task[field="task"]');
    taskCells.forEach(cell => {
        cell.addEventListener('click', function(e) {
            e.stopPropagation();
            if (autocompleteData.tasks && autocompleteData.tasks.length > 0) {
                createDropdown(this, 'task', autocompleteData.tasks);
            }
        });
        
        cell.addEventListener('input', function() {
            if (autocompleteData.tasks && autocompleteData.tasks.length > 0) {
                createDropdown(this, 'task', autocompleteData.tasks);
            }
        });
    });
    
    // Builder autocomplete
    const builderCells = document.querySelectorAll('.builder[field="builder"]');
    builderCells.forEach(cell => {
        cell.addEventListener('click', function(e) {
            e.stopPropagation();
            if (autocompleteData.builders && autocompleteData.builders.length > 0) {
                createDropdown(this, 'builder', autocompleteData.builders);
            }
        });
        
        cell.addEventListener('input', function() {
            if (autocompleteData.builders && autocompleteData.builders.length > 0) {
                createDropdown(this, 'builder', autocompleteData.builders);
            }
        });
    });
    
    // Dweling autocomplete
    const dwelingCells = document.querySelectorAll('.dweling[field="dweling"]');
    dwelingCells.forEach(cell => {
        cell.addEventListener('click', function(e) {
            e.stopPropagation();
            if (autocompleteData.dwelings && autocompleteData.dwelings.length > 0) {
                createDropdown(this, 'dweling', autocompleteData.dwelings);
            }
        });
        
        cell.addEventListener('input', function() {
            if (autocompleteData.dwelings && autocompleteData.dwelings.length > 0) {
                createDropdown(this, 'dweling', autocompleteData.dwelings);
            }
        });
    });
    
    // Status autocomplete
    const statusCells = document.querySelectorAll('.status[field="status"]');
    statusCells.forEach(cell => {
        cell.addEventListener('click', function(e) {
            e.stopPropagation();
            if (autocompleteData.statuses && autocompleteData.statuses.length > 0) {
                createDropdown(this, 'status', autocompleteData.statuses);
            }
        });
        
        cell.addEventListener('input', function() {
            if (autocompleteData.statuses && autocompleteData.statuses.length > 0) {
                createDropdown(this, 'status', autocompleteData.statuses);
            }
        });
    });
}

// Overtime Function
function fwtime(checkbox) {
    const row = checkbox.closest('tr');
    const temp = checkbox.checked ? '1' : '0';
    row.setAttribute('temp', temp);
}

// Date Change Function
document.getElementById('chaDate').addEventListener('change', function() {
    const selectedDate = this.value;
    if (selectedDate !== currentDate) {
        // Load logs for selected date
        fetch(`/log/date/${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                // Update table with new data
                updateTableWithData(data);
                currentDate = selectedDate;
                
                // Update the date input value to match the selected date
                document.getElementById('chaDate').value = selectedDate;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading logs for selected date');
            });
    }
});

// Update Table with Data
function updateTableWithData(logs) {
    const tbody = document.getElementById('logsTableBody');
    tbody.innerHTML = '';
    
    if (logs.length === 0) {
        tbody.innerHTML = '<tr id="no-data"><td colspan="14" class="text-center text-muted py-4">No logs found for selected date. Click "Add Row" to create a new log entry.</td></tr>';
        rowCounter = 0;
    } else {
        logs.forEach((log, index) => {
            const row = document.createElement('tr');
            row.id = log.id;
            row.setAttribute('temp', log.temp ? '1' : '0');
            row.setAttribute('app', log.approved ? '1' : '0');
            row.setAttribute('appnote', log.approved_note ? '1' : '0');
            row.setAttribute('appicon', log.approved_emoji ? '1' : '0');
            row.style.color = '#0d6efd';
            
            row.innerHTML = `
                <td class="text-center"><button class="btns btn btn-sm btn-outline-danger" onclick="removeRow(this)"><i class="bi bi-x-square"></i> Remove</button></td>
                <td class="text-end">${index + 1}</td>
                <td><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="log" field="subject" placeholder="Subject" data="${log.subject}" spellcheck="false">${log.subject}</div></td>
                <td><div contenteditable="true" style="pointer-events: auto" class="log" field="description" placeholder="Description" data="${log.description}" spellcheck="false">${log.description}</div></td>
                <td class="text-end"><div contenteditable="true" style="pointer-events: auto" class="log" field="qty" placeholder="Qty" data="${log.qty}" spellcheck="false">${log.qty}</div></td>
                <td><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="category" field="category" placeholder="Category" data="${log.category ? log.category.title : ''}" spellcheck="false">${log.category ? log.category.title : ''}</div></td>
                <td><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="task" field="task" placeholder="Task" data="${log.task ? log.task.title : ''}" spellcheck="false">${log.task ? log.task.title : ''}</div></td>
                <td><div contenteditable="true" style="pointer-events: auto" class="builder" field="builder" placeholder="Builder" data="${log.builder ? log.builder.title : ''}" spellcheck="false">${log.builder ? log.builder.title : ''}</div></td>
                <td><div contenteditable="true" style="pointer-events: auto" class="dweling" field="dweling" placeholder="Dwelling" data="${log.dweling ? log.dweling.title : ''}" spellcheck="false">${log.dweling ? log.dweling.title : ''}</div></td>
                <td><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="status" field="status" placeholder="Status" data="${log.status ? log.status.title : ''}" spellcheck="false">${log.status ? log.status.title : ''}</div></td>
                <td class="text-end"><div contenteditable="true" style="pointer-events: auto; background-color: transparent;" class="log" field="duration" placeholder="Duration" min="0" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" data="${log.duration}" spellcheck="false">${log.duration}</div></td>
                <td><div contenteditable="true" style="pointer-events: auto" class="log" field="note" placeholder="Additional Notes" data="${log.note || ''}" spellcheck="false">${log.note || ''}</div></td>
                <td class="text-center"><input type="checkbox" trid="${log.id}" class="fwtime" style="width: 24px; height: 24px; cursor: pointer;" onclick="fwtime(this)" ${log.temp ? 'checked' : ''}></td>
                <td class="cell-add" data-bs-toggle="modal" data-bs-target="#replynoted1" hidden=""><div placeholder="Approved Note">${log.approved_note || ''}</div></td>
                <td class="cell-add text-center" hidden=""><div placeholder="Emoji">${log.approved_emoji || ''}</div></td>
            `;
            
            tbody.appendChild(row);
        });
        rowCounter = logs.length;
    }
    
    updateTotalDuration();
    // Setup autocomplete for loaded data
    setTimeout(() => {
        setupAutocomplete();
    }, 100);
}

// Submit Function
document.getElementById('btn-saveRow').addEventListener('click', function() {
    const rows = document.querySelectorAll('#logsTableBody tr');
    const logsToSave = [];
    
    rows.forEach(row => {
        if (row.id === 'no-data') return;
        
        // Check if log is already approved
        const isApproved = row.getAttribute('app') === '1';
        if (isApproved) {
            console.log('Skipping approved log:', row.id);
            return;
        }
        
        const logData = {
            id: row.id.startsWith('new-') ? null : row.id,
            date: document.getElementById('chaDate').value,
            subject: row.querySelector('.log[field="subject"]').textContent.trim(),
            description: row.querySelector('.log[field="description"]').textContent.trim(),
            qty: parseInt(row.querySelector('.log[field="qty"]').textContent) || 1,
            category: row.querySelector('.category[field="category"]').textContent.trim(),
            task: row.querySelector('.task[field="task"]').textContent.trim(),
            builder: row.querySelector('.builder[field="builder"]').textContent.trim(),
            dweling: row.querySelector('.dweling[field="dweling"]').textContent.trim(),
            status: row.querySelector('.status[field="status"]').textContent.trim(),
            duration: parseFloat(row.querySelector('.log[field="duration"]').textContent) || 0,
            note: row.querySelector('.log[field="note"]').textContent.trim(),
            temp: row.querySelector('.fwtime') ? row.querySelector('.fwtime').checked : false
        };
        
        // Only save if subject is not empty
        if (logData.subject) {
            console.log('Adding log to save:', logData);
            logsToSave.push(logData);
        } else {
            console.log('Skipping log - no subject:', logData);
        }
    });
    
    console.log('Total logs to save:', logsToSave.length);
    console.log('Logs to save:', logsToSave);
    
    if (logsToSave.length === 0) {
        alert('Please fill in at least Subject for one or more logs.');
        return;
    }
    
    let savedCount = 0;
    let errorCount = 0;
    
    // Save logs sequentially to avoid conflicts
    const saveLogs = async () => {
        for (const logData of logsToSave) {
            try {
                const url = logData.id ? `/log/${logData.id}` : '/log';
                const method = logData.id ? 'PUT' : 'POST';
                
                console.log(`Saving log: ${logData.subject}`, {
                    url: url,
                    method: method,
                    data: logData
                });
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(logData)
                });
                
                console.log(`Response status: ${response.status}`);
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.success) {
                    savedCount++;
                    console.log('Log saved successfully:', logData.subject);
                } else {
                    errorCount++;
                    console.error('Error saving log:', data.message);
                    console.error('Full error response:', data);
                }
            } catch (error) {
                errorCount++;
                console.error('Error:', error);
                console.error('Error details:', {
                    message: error.message,
                    stack: error.stack,
                    logData: logData
                });
            }
        }
        
        // Show result
        console.log('Final results:', { savedCount, errorCount });
        if (errorCount === 0) {
            alert(`All ${savedCount} logs saved successfully!`);
            // Reload the page to show updated data
            location.reload();
        } else {
            alert(`${savedCount} logs saved, ${errorCount} errors occurred.`);
        }
    };
    
    saveLogs();
});

// Debug function to check current data
function debugCurrentData() {
    const rows = document.querySelectorAll('#logsTableBody tr');
    console.log('=== DEBUG CURRENT DATA ===');
    console.log('Total rows:', rows.length);
    
    rows.forEach((row, index) => {
        if (row.id === 'no-data') return;
        
        const logData = {
            id: row.id.startsWith('new-') ? null : row.id,
            date: document.getElementById('chaDate').value,
            subject: row.querySelector('.log[field="subject"]').textContent.trim(),
            description: row.querySelector('.log[field="description"]').textContent.trim(),
            qty: parseInt(row.querySelector('.log[field="qty"]').textContent) || 1,
            category: row.querySelector('.category[field="category"]').textContent.trim(),
            task: row.querySelector('.task[field="task"]').textContent.trim(),
            builder: row.querySelector('.builder[field="builder"]').textContent.trim(),
            dweling: row.querySelector('.dweling[field="dweling"]').textContent.trim(),
            status: row.querySelector('.status[field="status"]').textContent.trim(),
            duration: parseFloat(row.querySelector('.log[field="duration"]').textContent) || 0,
            note: row.querySelector('.log[field="note"]').textContent.trim(),
            temp: row.querySelector('.fwtime') ? row.querySelector('.fwtime').checked : false
        };
        
        console.log(`Row ${index}:`, logData);
    });
    console.log('=== END DEBUG ===');
}

// Add debug button
document.addEventListener('DOMContentLoaded', function() {
    const debugBtn = document.createElement('button');
    debugBtn.textContent = 'Debug Data';
    debugBtn.className = 'btn btn-warning btn-sm';
    debugBtn.onclick = debugCurrentData;
    debugBtn.style.marginLeft = '10px';
    debugBtn.style.marginTop = '5px';
    debugBtn.style.display = 'inline-block';
    debugBtn.style.float = 'right';
    debugBtn.style.position = 'relative';
    debugBtn.style.top = '-5px';
    debugBtn.style.zIndex = '1000';
    debugBtn.style.backgroundColor = '#ffc107';
    debugBtn.style.borderColor = '#ffc107';
    debugBtn.style.color = '#000';
    debugBtn.style.fontWeight = 'bold';
    debugBtn.style.padding = '5px 10px';
    debugBtn.style.borderRadius = '4px';
    debugBtn.style.cursor = 'pointer';
    debugBtn.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
    debugBtn.style.transition = 'all 0.3s ease';
    debugBtn.style.fontSize = '12px';
    debugBtn.style.lineHeight = '1.2';
    debugBtn.style.textTransform = 'uppercase';
    debugBtn.style.letterSpacing = '0.5px';
    debugBtn.style.border = 'none';
    debugBtn.style.outline = 'none';
    debugBtn.style.minWidth = '80px';
    document.querySelector('.card-header').appendChild(debugBtn);
});

// Reload Function
document.getElementById('btn-reload').addEventListener('click', function() {
    const currentDateValue = document.getElementById('chaDate').value;
    
    // Store the current date in localStorage before reload
    localStorage.setItem('lastSelectedDate', currentDateValue);
    
    location.reload();
});

// Reset Column Widths Function
document.getElementById('btn-reset-columns').addEventListener('click', function() {
    if (confirm('Reset all column widths to default? This will reload the page.')) {
        resetColumnWidths();
    }
});

// Column Resizing Functionality
function initializeColumnResizing() {
    const resizeHandles = document.querySelectorAll('.resize-handle');
    
    resizeHandles.forEach(handle => {
        let isResizing = false;
        let startX = 0;
        let startWidth = 0;
        let columnIndex = 0;
        
        handle.addEventListener('mousedown', function(e) {
            isResizing = true;
            startX = e.clientX;
            
            const th = handle.parentElement;
            columnIndex = parseInt(th.getAttribute('data-column'));
            startWidth = th.offsetWidth;
            
            document.body.style.cursor = 'col-resize';
            document.body.style.userSelect = 'none';
            
            e.preventDefault();
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isResizing) return;
            
            const diff = e.clientX - startX;
            const newWidth = Math.max(50, startWidth + diff); // Minimum width 50px
            
            // Update all cells in this column
            const allCells = document.querySelectorAll(`th:nth-child(${columnIndex + 1}), td:nth-child(${columnIndex + 1})`);
            allCells.forEach(cell => {
                cell.style.width = newWidth + 'px';
            });
        });
        
        document.addEventListener('mouseup', function() {
            if (isResizing) {
                isResizing = false;
                document.body.style.cursor = '';
                document.body.style.userSelect = '';
                
                // Save column widths to localStorage
                saveColumnWidths();
            }
        });
    });
}

// Save column widths to localStorage
function saveColumnWidths() {
    const columnWidths = {};
    const headers = document.querySelectorAll('th.resizable');
    
    headers.forEach((header, index) => {
        columnWidths[index] = header.offsetWidth;
    });
    
    localStorage.setItem('tableColumnWidths', JSON.stringify(columnWidths));
}

// Load column widths from localStorage
function loadColumnWidths() {
    const savedWidths = localStorage.getItem('tableColumnWidths');
    if (savedWidths) {
        const columnWidths = JSON.parse(savedWidths);
        
        Object.keys(columnWidths).forEach(columnIndex => {
            const width = columnWidths[columnIndex];
            const allCells = document.querySelectorAll(`th:nth-child(${parseInt(columnIndex) + 1}), td:nth-child(${parseInt(columnIndex) + 1})`);
            allCells.forEach(cell => {
                cell.style.width = width + 'px';
            });
        });
    }
}

// Reset column widths to default
function resetColumnWidths() {
    localStorage.removeItem('tableColumnWidths');
    location.reload();
}

// Fix calendar date selection issue
function fixCalendarDateSelection() {
    // Get the date input element
    const dateInput = document.getElementById('chaDate');
    
    // Get today's date for comparison
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time part for proper date comparison
    
    // Create a MutationObserver to detect when the calendar popup is added to the DOM
    const observer = new MutationObserver((mutations) => {
        for (const mutation of mutations) {
            if (mutation.addedNodes.length) {
                // Look for calendar popup
                const calendar = document.querySelector('.calendar-popup');
                if (calendar) {
                    // Find all date cells in the calendar
                    const dateCells = calendar.querySelectorAll('td[data-date]');
                    
                    // Process all date cells
                    dateCells.forEach(cell => {
                        const dateStr = cell.getAttribute('data-date');
                        if (dateStr) {
                            const cellDate = new Date(dateStr);
                            
                            if (cellDate > today) {
                                // Future dates - disable
                                cell.setAttribute('disabled', 'disabled');
                                cell.classList.add('disabled');
                                cell.style.pointerEvents = 'none';
                                cell.style.opacity = '0.5';
                                cell.style.cursor = 'not-allowed';
                                cell.style.color = '#999';
                                cell.style.backgroundColor = '#f5f5f5';
                            } else {
                                // Past or today's dates - enable
                                cell.removeAttribute('disabled');
                                cell.classList.remove('disabled');
                                cell.style.pointerEvents = 'auto';
                                cell.style.opacity = '1';
                                cell.style.cursor = 'pointer';
                                
                                // Add click event listener
                                cell.addEventListener('click', function() {
                                    const date = this.getAttribute('data-date');
                                    if (date) {
                                        dateInput.value = date;
                                        dateInput.dispatchEvent(new Event('change'));
                                    }
                                });
                            }
                        }
                    });
                }
            }
        }
    });
    
    // Start observing the document body for added nodes
    observer.observe(document.body, { childList: true, subtree: true });
    
    // Add click event to date input to ensure calendar opens properly
    dateInput.addEventListener('click', function() {
        // Ensure max date is set to today
        const today = new Date().toISOString().split('T')[0];
        this.setAttribute('max', today);
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', async function() {
    await loadAutocompleteData();
    setupAutocomplete();
    updateTotalDuration();
    
    // Ensure max date is set to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('chaDate').setAttribute('max', today);
    
    // Check if there's a stored date from previous session
    const lastSelectedDate = localStorage.getItem('lastSelectedDate');
    if (lastSelectedDate) {
        // Set the date input to the stored date
        document.getElementById('chaDate').value = lastSelectedDate;
        
        // Load logs for the stored date
        fetch(`/log/date/${lastSelectedDate}`)
            .then(response => response.json())
            .then(data => {
                // Update table with stored date data
                updateTableWithData(data);
                currentDate = lastSelectedDate;
            })
            .catch(error => {
                console.error('Error loading stored date logs:', error);
            });
            
        // Clear the stored date after using it
        localStorage.removeItem('lastSelectedDate');
    }
    
    // Initialize column resizing
    setTimeout(() => {
        initializeColumnResizing();
        loadColumnWidths();
    }, 100);
    
    // Fix calendar date selection issue
    fixCalendarDateSelection();
});
</script>
@endsection

<style>
.autocomplete-dropdown {
    position: absolute !important;
    background: white;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    min-width: 200px;
}

.autocomplete-dropdown .dropdown-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

.autocomplete-dropdown .dropdown-item:hover {
    background-color: #f5f5f5;
}

.autocomplete-dropdown .dropdown-item:last-child {
    border-bottom: none;
}

/* Highlight matching items */
.autocomplete-dropdown .dropdown-item strong {
    background-color: #ffeb3b;
    padding: 1px 2px;
    border-radius: 2px;
}

/* Make table container relative for dropdown positioning */
.card-body {
    position: relative;
}

/* Ensure editable cells have proper cursor */
.log[contenteditable="true"],
.category[contenteditable="true"],
.task[contenteditable="true"],
.builder[contenteditable="true"],
.dweling[contenteditable="true"],
.status[contenteditable="true"] {
    cursor: text;
}

/* Highlight when focused */
.log[contenteditable="true"]:focus,
.category[contenteditable="true"]:focus,
.task[contenteditable="true"]:focus,
.builder[contenteditable="true"]:focus,
.dweling[contenteditable="true"]:focus,
.status[contenteditable="true"]:focus {
    outline: 2px solid #007bff;
    outline-offset: 1px;
}

/* Reduce text size in table cells */
.table td {
    font-size: 0.8rem;
    padding: 2px 4px !important;
    white-space: nowrap;
}

.table th {
    padding: 4px 6px !important;
    white-space: nowrap;
}

.table td div[contenteditable="true"] {
    font-size: 0.8rem;
    padding: 0;
    margin: 0;
    min-height: 20px;
}

.table td .btn {
    font-size: 0.75rem;
    padding: 2px 6px;
}

.table td input[type="checkbox"] {
    transform: scale(0.8);
}

/* Auto-adjust table columns */
.table {
    table-layout: auto !important;
    width: 100% !important;
}

.table th,
.table td {
    width: auto !important;
    min-width: fit-content;
}

/* Resizable columns */
.resizable {
    position: relative;
}

.resize-handle {
    position: absolute;
    top: 0;
    right: 0;
    width: 5px;
    height: 100%;
    background: transparent;
    cursor: col-resize;
    z-index: 10;
}

.resize-handle:hover {
    background: #007bff;
    opacity: 0.5;
}

.resize-handle:active {
    background: #0056b3;
    opacity: 0.8;
}

/* Fix for calendar date selection */
input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 1;
    display: block;
}

input[type="date"] {
    cursor: pointer;
}

/* Calendar date styling */
td[data-date], 
.calendar-popup td,
.datepicker-days td {
    cursor: pointer;
    pointer-events: auto;
    opacity: 1;
}

/* Future dates (disabled) */
td[data-date].disabled,
.calendar-popup td.disabled,
.datepicker-days td.disabled {
    cursor: not-allowed !important;
    pointer-events: none !important;
    opacity: 0.5 !important;
    color: #999 !important;
    background-color: #f5f5f5 !important;
    text-decoration: line-through;
}

/* Default column widths - can be overridden by user */
.table th:nth-child(1), .table td:nth-child(1) { width: 80px; min-width: 60px; } /* Status */
.table th:nth-child(2), .table td:nth-child(2) { width: 30px; min-width: 25px; } /* # */
.table th:nth-child(3), .table td:nth-child(3) { width: 150px; min-width: 100px; } /* Subject */
.table th:nth-child(4), .table td:nth-child(4) { width: 200px; min-width: 120px; } /* Description */
.table th:nth-child(5), .table td:nth-child(5) { width: 60px; min-width: 50px; } /* Unit Qty */
.table th:nth-child(6), .table td:nth-child(6) { width: 120px; min-width: 80px; } /* Category */
.table th:nth-child(7), .table td:nth-child(7) { width: 100px; min-width: 70px; } /* Task */
.table th:nth-child(8), .table td:nth-child(8) { width: 120px; min-width: 80px; } /* Builder */
.table th:nth-child(9), .table td:nth-child(9) { width: 180px; min-width: 120px; } /* Dwelling */
.table th:nth-child(10), .table td:nth-child(10) { width: 80px; min-width: 60px; } /* Status */
.table th:nth-child(11), .table td:nth-child(11) { width: 80px; min-width: 60px; } /* Duration */
.table th:nth-child(12), .table td:nth-child(12) { width: 150px; min-width: 100px; } /* Additional Notes */
.table th:nth-child(13), .table td:nth-child(13) { width: 60px; min-width: 50px; } /* Overtime */
</style>