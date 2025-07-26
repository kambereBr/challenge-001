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
    const rows = Array.from(tbody.rows);
    const pageSize = 5;
    const totalPages = Math.ceil(rows.length / pageSize);

    // Create pagination controls
    const pagination = document.createElement('div');
    pagination.className = 'pagination';
    const info = document.createElement('span');
    pagination.appendChild(info);

    const btnPrev = document.createElement('button');
    btnPrev.textContent = 'Prev';
    btnPrev.disabled = true;
    pagination.appendChild(btnPrev);

    const pageButtons = [];
    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement('button');
      btn.textContent = i;
      btn.addEventListener('click', () => goToPage(i));
      pagination.appendChild(btn);
      pageButtons.push(btn);
    }

    const btnNext = document.createElement('button');
    btnNext.textContent = 'Next';
    btnNext.disabled = totalPages <= 1;
    pagination.appendChild(btnNext);

    table.parentNode.insertBefore(pagination, table.nextSibling);

    let currentPage = 1;
    
    function renderPage(page) {
      const start = (page - 1) * pageSize;
      const end = start + pageSize;
      rows.forEach((row, idx) => {
        row.style.display = (idx >= start && idx < end) ? '' : 'none';
      });
      info.textContent = `Page ${page} of ${totalPages}, Total: ${rows.length}`;
      pageButtons.forEach((btn, idx) => {
        btn.classList.toggle('active', idx + 1 === page);
      });
      btnPrev.disabled = page === 1;
      btnNext.disabled = page === totalPages;
    }

    btnPrev.addEventListener('click', () => goToPage(currentPage - 1));
    btnNext.addEventListener('click', () => goToPage(currentPage + 1));

    function goToPage(page) {
      if (page < 1 || page > totalPages) return;
      currentPage = page;
      renderPage(page);
    }

    // Initialize
    renderPage(1);
  });
});
