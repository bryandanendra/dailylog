@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Cut Off</h1>
    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cut Off</li>
        </ol>
    </nav>

    <div class="row justify-content-start">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body text-center py-4">
                    <h5 class="mb-3">Set Time Cut Off<br><span class="text-muted">(GMT +7)</span></h5>
                    
                    <form id="cutoffForm">
                        <div class="row g-2 align-items-end justify-content-center mb-3">
                            <!-- Day Offset -->
                            <div class="col-4">
                                <label for="dayOffset" class="form-label small">Day +</label>
                                <input type="number" 
                                       class="form-control text-center" 
                                       id="dayOffset" 
                                       name="day_offset"
                                       min="0" 
                                       max="1" 
                                       value="0" 
                                       required>
                            </div>

                            <!-- Hour -->
                            <div class="col-4">
                                <label for="hour" class="form-label small">Hour</label>
                                <select class="form-select text-center" 
                                        id="hour" 
                                        name="hour" 
                                        required>
                                    @for($i = 0; $i < 24; $i++)
                                        <option value="{{ $i }}" {{ $i == 23 ? 'selected' : '' }}>
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Minute -->
                            <div class="col-4">
                                <label for="minute" class="form-label small">Minute</label>
                                <select class="form-select text-center" 
                                        id="minute" 
                                        name="minute" 
                                        required>
                                    @for($i = 0; $i < 60; $i++)
                                        <option value="{{ $i }}" {{ $i == 0 ? 'selected' : '' }}>
                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success px-5">
                            Update
                        </button>
                    </form>

                    <hr class="my-3">

                    <div class="text-start">
                        <p class="mb-0 small text-muted" id="currentSetting">
                            <i class="bi bi-clock"></i> Loading...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCurrentSetting();

    // Form submission
    document.getElementById('cutoffForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateCutoff();
    });
});

function loadCurrentSetting() {
    fetch('{{ url("/cutoff/current") }}')
        .then(response => response.json())
        .then(data => {
            if (data.day_offset !== undefined) {
                document.getElementById('dayOffset').value = data.day_offset;
                document.getElementById('hour').value = parseInt(data.hour);
                document.getElementById('minute').value = parseInt(data.minute);
            }
            
            // Display current setting
            const dayText = data.day_offset == 0 ? 'Same Day' : 'Next Day';
            const timeText = `${data.hour}:${data.minute}`;
            document.getElementById('currentSetting').innerHTML = `
                <i class="bi bi-clock"></i> Current: <strong>${dayText}</strong> at <strong>${timeText}</strong> (${data.timezone})
            `;
        })
        .catch(error => {
            console.error('Error loading cutoff settings:', error);
            document.getElementById('currentSetting').innerHTML = 
                '<i class="bi bi-exclamation-triangle text-warning"></i> Error loading settings';
        });
}

function updateCutoff() {
    const formData = {
        day_offset: parseInt(document.getElementById('dayOffset').value),
        hour: parseInt(document.getElementById('hour').value),
        minute: parseInt(document.getElementById('minute').value)
    };

    fetch('{{ url("/cutoff/update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Time Cut Off updated successfully!');
            loadCurrentSetting();
        } else {
            alert(data.message || 'Error updating Time Cut Off');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating Time Cut Off');
    });
}
</script>

<style>
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-control, .form-select {
    font-size: 1rem;
    padding: 0.5rem;
}

.form-label.small {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

#currentSetting {
    font-size: 0.875rem;
}

.btn-success {
    padding: 0.5rem 2rem;
}
</style>
@endsection

