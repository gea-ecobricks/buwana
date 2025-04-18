<!-- app-modals.php -->
<script>
  function openTermsModal() {
    closeSettings();

    const modal = document.getElementById('form-modal-message');
    const modalBox = document.getElementById('modal-content-box');

    modal.style.display = 'flex';
    modalBox.style.flexFlow = 'column';
    document.getElementById('page-content')?.classList.add('blurred');
    document.getElementById('footer-full')?.classList.add('blurred');
    document.body.classList.add('modal-open');

    modalBox.style.maxHeight = '80vh';
    modalBox.style.overflowY = 'auto';

    modalBox.innerHTML = <?= json_encode($app_info['app_terms_txt']) ?>;
  }

  function openPrivacyModal() {
    closeSettings();

    const modal = document.getElementById('form-modal-message');
    const modalBox = document.getElementById('modal-content-box');

    modal.style.display = 'flex';
    modalBox.style.flexFlow = 'column';
    document.getElementById('page-content')?.classList.add('blurred');
    document.getElementById('footer-full')?.classList.add('blurred');
    document.body.classList.add('modal-open');

    modalBox.style.maxHeight = '80vh';
    modalBox.style.overflowY = 'auto';

    modalBox.innerHTML = <?= json_encode($app_info['app_privacy_txt']) ?>;
  }


    const appEmojis = <?= json_encode(json_decode($app_info['app_emojis_array'] ?? '[]')) ?>;


   function startEarthlingEmojiSpinner() {
     const emojiContainer = document.getElementById('submit-emoji');
     const earthlings = JSON.parse(appEmojis || '["🐵","🦉","🦋"]'); // Fallback

     let index = 0;
     emojiContainer.style.display = 'block';
     emojiContainer.style.opacity = 1;

     // Start showing the emoji animation
     const emojiInterval = setInterval(() => {
       if (index >= earthlings.length) {
         clearInterval(emojiInterval);
         return;
       }

       emojiContainer.textContent = earthlings[index];
       emojiContainer.style.opacity = 1;

       setTimeout(() => {
         emojiContainer.style.opacity = 0;
       }, 200);

       index++;
     }, 100); // Just for display purposes, no longer controlling form submit

     // 🔁 Submit form shortly after animation starts
     setTimeout(() => {
       form.submit(); // ✅ Submit after 0.5s regardless of how many emojis remain
     }, 500);
   }


    </script>
