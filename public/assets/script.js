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
});
