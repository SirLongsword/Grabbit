// search.js
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('searchInput');
  const resultsBox = document.getElementById('liveResults');
// Using listener for user input
  input.addEventListener('input', () => {
    const q = input.value.trim();
    if (!q) {
      resultsBox.style.display = 'none';
      return;
    }
// Comparing input to php results
    fetch('livesearch.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'query=' + encodeURIComponent(q)
    })
    .then(res => res.text())
    .then(html => {
      resultsBox.innerHTML = html;
      resultsBox.style.display = html ? 'block' : 'none';
    });
  });

  // Hide when clicking outside
  document.addEventListener('click', e => {
    if (!e.target.closest('#searchInput') && !e.target.closest('#liveResults')) {
      resultsBox.style.display = 'none';
    }
  });
});
