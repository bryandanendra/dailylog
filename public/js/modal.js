// Modal functions for table management
function modalCustom(id, type) {
    let modalContent = '';
    
    switch(type) {
        case 'division':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Division</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
            
        case 'subdivision':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Sub Division</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
            
        case 'role':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Role</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
            
        case 'position':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Position</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label for="level" class="form-label mb-1">Level <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="level" min="1" max="10" required>
                                            <div class="invalid-feedback">Please provide a valid level.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
            
        case 'category':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Category</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
            
        case 'task':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Task</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
            
        case 'builder':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Builder</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
            
        case 'dweling':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Dwelling</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
            
        case 'status':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Status</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
            
        case 'wtime':
            modalContent = `
                <div class="modal fade" id="modal-table" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white py-2">
                                <h1 class="modal-title fs-5 text-capitalize" id="modalLabel">${id ? 'Edit' : 'Add'} Work Status</h1>
                                <i data-bs-dismiss="modal" class="bi bi-x-lg" style="font-size: 20px; cursor: pointer;"></i>
                            </div>
                            <form id="form-modal" class="needs-validation" novalidate>
                                <div class="modal-body">
                                    <input type="hidden" id="id" value="${id || ''}">
                                    <div class="row mb-2">
                                        <div class="col-md-12 mb-4">
                                            <label for="title" class="form-label mb-1">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" required>
                                            <div class="invalid-feedback">Please provide a valid name.</div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="description" class="form-label mb-1">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center align-items-center p-1">
                                    <button type="button" id="submit-form" class="btn btn-primary btn-sm" style="width:100px">
                                        <i class="bi bi-save pe-2"></i>SUBMIT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>`;
            break;
    }
    
    document.querySelector('body').insertAdjacentHTML('beforeend', modalContent);
    
    // Load data if editing
    if (id) {
        loadEditData(id, type);
    }
    
    // Setup form submission
    setupFormSubmission(type);
}

function loadEditData(id, type) {
    const endpoint = `/${type}/getData?id=${id}`;
    
    fetch(endpoint)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('title').value = data.data.title || '';
                document.getElementById('description').value = data.data.description || '';
                if (type === 'position' && data.data.level) {
                    document.getElementById('level').value = data.data.level;
                }
            }
        })
        .catch(error => {
            console.error('Error loading data:', error);
        });
}

function setupFormSubmission(type) {
    document.getElementById('submit-form').addEventListener('click', function() {
        const form = document.getElementById('form-modal');
        const id = document.getElementById('id').value;
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const level = document.getElementById('level') ? document.getElementById('level').value : null;
        
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
        
        const data = {
            title: title,
            description: description
        };
        
        if (level !== null) {
            data.level = level;
        }
        
        const url = id ? `/${type}/${id}` : `/${type}`;
        const method = id ? 'PUT' : 'POST';
        
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message);
                bootstrap.Modal.getInstance(document.getElementById('modal-table')).hide();
                // Reload table data
                if (typeof getData === 'function') {
                    getData();
                }
            } else {
                alert(result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
}

function modalWait(show) {
    if (show) {
        if (!document.getElementById('modal-wait')) {
            const modal = `
                <div class="modal fade" id="modal-wait" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="mt-2">Loading...</div>
                            </div>
                        </div>
                    </div>
                </div>`;
            document.querySelector('body').insertAdjacentHTML('beforeend', modal);
        }
        const myModal = new bootstrap.Modal(document.getElementById('modal-wait'));
        myModal.show();
    } else {
        const modal = document.getElementById('modal-wait');
        if (modal) {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
            modal.remove();
        }
    }
}

function pagination(pages, currentPage, maxVisible) {
    const pagination = document.querySelector('.pagination');
    let html = '';
    
    // Previous button
    if (currentPage > 1) {
        html += `<li class="page-item"><a class="page-link" style="cursor: pointer">${currentPage - 1}</a></li>`;
    }
    
    // Current page
    html += `<li class="page-item active"><a class="page-link" style="cursor: pointer">${currentPage}</a></li>`;
    
    // Next button
    if (currentPage < pages) {
        html += `<li class="page-item"><a class="page-link" style="cursor: pointer">${currentPage + 1}</a></li>`;
    }
    
    pagination.innerHTML = html;
}

// Sort icons
const up = document.createElement('i');
up.className = 'bi bi-caret-up-fill float-end';
const down = document.createElement('i');
down.className = 'bi bi-caret-down-fill float-end';
