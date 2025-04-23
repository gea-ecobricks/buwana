<!-- app-modals.php -->
<script>
  function openTermsModal() {
    closeMainMenu();

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
    closeMainMenu();

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
       const earthlings = appEmojis.length > 0 ? appEmojis : ["🐵", "🦉", "🦋"];

       let index = 0;
       emojiContainer.style.display = 'block';
       emojiContainer.style.opacity = 1;

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
       }, 100);

       // ✅ Submit after 0.5s regardless of how many emojis remain
       setTimeout(() => {
         form.submit();
       }, 500);
     }




    </script>
