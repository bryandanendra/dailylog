// Table Move Functionality
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
