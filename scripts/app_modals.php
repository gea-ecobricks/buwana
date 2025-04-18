<?



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

      // 🚨 No escaping or nl2br, just insert raw HTML
      modalBox.innerHTML = `<?= $app_info['app_terms_txt']; ?>`;
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

      modalBox.innerHTML = `<?= $app_info['app_privacy_txt']; ?>`;
    }

?>