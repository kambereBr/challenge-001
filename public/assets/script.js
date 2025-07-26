document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('table').forEach(table => {
    const headers = Array.from(table.querySelectorAll('th'));
    const tbody   = table.tBodies[0];

    headers.forEach((th, idx) => {
      if (! th.classList.contains('sortable')) return;

      th.addEventListener('click', () => {
        // Determine sort direction
        const currentlyAsc = th.classList.contains('asc');
        headers.forEach(h => h.classList.remove('asc','desc'));
        th.classList.add(currentlyAsc ? 'desc' : 'asc');

        // Grab rows as array
        const rows = Array.from(tbody.rows);
        rows.sort((a, b) => {
          let aText = a.cells[idx].innerText.trim();
          let bText = b.cells[idx].innerText.trim();
          // numeric if both are numbers
          const aNum = parseFloat(aText.replace(/[^0-9.-]/g, ''));
          const bNum = parseFloat(bText.replace(/[^0-9.-]/g, ''));
          if (! isNaN(aNum) && !isNaN(bNum)) {
            return aNum - bNum;
          }
          return aText.localeCompare(bText);
        });
        if (currentlyAsc) rows.reverse();

        // Re-attach in new order
        rows.forEach(r => tbody.appendChild(r));
      });
    });
  });

  // Search functionality
  document.querySelectorAll('.table-filter').forEach(input => {
    const tableId = input.dataset.table;
    const table = document.getElementById(tableId);
    const tbody = table.tBodies[0];

    input.addEventListener('input', () => {
      const term = input.value.trim().toLowerCase();
      Array.from(tbody.rows).forEach(row => {
        // join all cell text for that row
        const text = Array.from(row.cells)
          .map(cell => cell.innerText.trim().toLowerCase())
          .join(' ');
        row.style.display = text.includes(term) ? '' : 'none';
      });
    });
  });

  // table pagination
  document.querySelectorAll('table').forEach(table => {
    const tableId = table.id;
    if (! tableId) return; // skip tables without id
    const tbody = table.tBodies[0];
    const allRows = Array.from(tbody.rows);
    const pageSize = 5;
    let filteredRows = allRows.slice();
    let currentPage = 1;

    // Find the filter input for this table
    const filterInput = document.querySelector(`.table-filter[data-table="${tableId}"]`);

    // Create pagination controls
    const pagination = document.createElement('div');
    pagination.className = 'pagination';

    const info = document.createElement('span');
    pagination.appendChild(info);

    const btnPrev = document.createElement('button');
    btnPrev.textContent = 'Prev';
    pagination.appendChild(btnPrev);

    const btnNext = document.createElement('button');
    btnNext.textContent = 'Next';
    pagination.appendChild(btnNext);

    table.parentNode.insertBefore(pagination, table.nextSibling);

    const pageButtons = [];

    function renderPage(page) {
      const totalPages = Math.max(1, Math.ceil(filteredRows.length / pageSize));
      if (page < 1) page = 1;
      if (page > totalPages) page = totalPages;
      currentPage = page;

      // Hide all rows, then show current page rows
      allRows.forEach(row => row.style.display = 'none');
      const start = (page - 1) * pageSize;
      const end = start + pageSize;
      filteredRows.slice(start, end).forEach(row => row.style.display = '');

      info.textContent = `Page ${currentPage} of ${totalPages}, Total: ${filteredRows.length}`;

      // Remove old page buttons
      pageButtons.forEach(btn => pagination.removeChild(btn));
      pageButtons.length = 0;

      // Create new page buttons
      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.classList.toggle('active', i === currentPage);
        btn.addEventListener('click', () => renderPage(i));
        pagination.insertBefore(btn, btnNext);
        pageButtons.push(btn);
      }

      // Enable/disable prev/next
      btnPrev.disabled = currentPage === 1;
      btnNext.disabled = currentPage === totalPages;
    }

    btnPrev.addEventListener('click', () => renderPage(currentPage - 1));
    btnNext.addEventListener('click', () => renderPage(currentPage + 1));

    // Filter handler
    if (filterInput) {
      filterInput.addEventListener('input', () => {
        const term = filterInput.value.trim().toLowerCase();
        filteredRows = allRows.filter(row => {
          return Array.from(row.cells).some(cell =>
            cell.innerText.trim().toLowerCase().includes(term)
          );
        });
        renderPage(1);
      });
    }

    // Initialize
    renderPage(1);
  });

  // Confirmation for delete forms
  document.querySelectorAll('form[action*="/delete"]').forEach(form => {
    form.addEventListener('submit', e => {
      if (! window.confirm('Are you sure you want to delete this record?')) {
        e.preventDefault();
      }
    });
  });
});
