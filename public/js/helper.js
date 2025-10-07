// Helper functions for table management

// Table resize functionality
function resizableGrid(table) {
    const row = table.getElementsByTagName('tr')[0];
    const cols = row ? row.children : [];
    
    for (let i = 0; i < cols.length; i++) {
        const div = createDiv(table.offsetHeight);
        cols[i].appendChild(div);
        cols[i].style.position = 'relative';
        setListeners(div);
    }
}

function createDiv(height) {
    const div = document.createElement('div');
    div.style.top = 0;
    div.style.right = 0;
    div.style.width = '5px';
    div.style.position = 'absolute';
    div.style.cursor = 'col-resize';
    div.style.userSelect = 'none';
    div.style.height = height + 'px';
    return div;
}

function setListeners(div) {
    let pageX, curCol, nxtCol, curColWidth, nxtColWidth;

    div.addEventListener('mousedown', function (e) {
        curCol = e.target.parentElement;
        nxtCol = curCol.nextElementSibling;
        pageX = e.pageX;

        const padding = paddingDiff(curCol);

        curColWidth = curCol.offsetWidth - padding;
        if (nxtCol)
            nxtColWidth = nxtCol.offsetWidth - padding;
    });

    div.addEventListener('mouseover', function (e) {
        e.target.style.borderRight = '2px solid #0000ff';
    });

    div.addEventListener('mouseout', function (e) {
        e.target.style.borderRight = '';
    });

    document.addEventListener('mousemove', function (e) {
        if (curCol) {
            const diffX = e.pageX - pageX;

            if (nxtCol)
                nxtCol.style.width = (nxtColWidth - (diffX)) + 'px';

            curCol.style.width = (curColWidth + diffX) + 'px';
        }
    });

    document.addEventListener('mouseup', function (e) {
        curCol = undefined;
        nxtCol = undefined;
        pageX = undefined;
        nxtColWidth = undefined;
        curColWidth = undefined;
    });
}

function paddingDiff(col) {
    if (getStyleVal(col, 'box-sizing') == 'border-box') {
        return 0;
    }

    const padLeft = getStyleVal(col, 'padding-left');
    const padRight = getStyleVal(col, 'padding-right');
    return (parseInt(padLeft) + parseInt(padRight));
}

function getStyleVal(elm, css) {
    return (window.getComputedStyle(elm, null).getPropertyValue(css));
}

// Table move functionality
function tableMove(table) {
    const headers = table.querySelectorAll('th');
    
    headers.forEach(header => {
        header.draggable = true;
        
        header.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', this.cellIndex);
            this.style.opacity = '0.5';
        });
        
        header.addEventListener('dragend', function(e) {
            this.style.opacity = '1';
        });
        
        header.addEventListener('dragover', function(e) {
            e.preventDefault();
        });
        
        header.addEventListener('drop', function(e) {
            e.preventDefault();
            const sourceIndex = e.dataTransfer.getData('text/plain');
            const targetIndex = this.cellIndex;
            
            if (sourceIndex !== targetIndex) {
                swapColumns(table, sourceIndex, targetIndex);
            }
        });
    });
}

function swapColumns(table, sourceIndex, targetIndex) {
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cells = row.children;
        if (cells.length > Math.max(sourceIndex, targetIndex)) {
            const sourceCell = cells[sourceIndex];
            const targetCell = cells[targetIndex];
            
            // Swap the cells
            if (sourceIndex < targetIndex) {
                targetCell.parentNode.insertBefore(sourceCell, targetCell.nextSibling);
            } else {
                targetCell.parentNode.insertBefore(sourceCell, targetCell);
            }
        }
    });
}

// Utility functions
function formatDate(date) {
    if (!date) return '';
    const d = new Date(date);
    return d.toLocaleDateString('en-GB');
}

function formatDateTime(date) {
    if (!date) return '';
    const d = new Date(date);
    return d.toLocaleString('en-GB');
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Notification functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Form validation helpers
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    });
    
    return isValid;
}

function clearFormValidation(form) {
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
}

// API helpers
function apiRequest(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    };
    
    const finalOptions = { ...defaultOptions, ...options };
    
    return fetch(url, finalOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        });
}

// Local storage helpers
function saveToLocalStorage(key, data) {
    try {
        localStorage.setItem(key, JSON.stringify(data));
    } catch (e) {
        console.error('Error saving to localStorage:', e);
    }
}

function getFromLocalStorage(key, defaultValue = null) {
    try {
        const item = localStorage.getItem(key);
        return item ? JSON.parse(item) : defaultValue;
    } catch (e) {
        console.error('Error reading from localStorage:', e);
        return defaultValue;
    }
}

function removeFromLocalStorage(key) {
    try {
        localStorage.removeItem(key);
    } catch (e) {
        console.error('Error removing from localStorage:', e);
    }
}

// Initialize table functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tables with resize and move functionality
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        resizableGrid(table);
        tableMove(table);
    });
});
